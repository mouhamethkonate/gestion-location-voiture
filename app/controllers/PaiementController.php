<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Facture.php';
require_once __DIR__ . '/../models/Vehicule.php';

class PaiementController extends Controller {

    private Reservation $resaModel;
    private Facture     $factureModel;
    private Vehicule    $vehiculeModel;

    public function __construct() {
        $this->resaModel     = new Reservation();
        $this->factureModel  = new Facture();
        $this->vehiculeModel = new Vehicule();
    }

    // ── Afficher le choix du mode de paiement ─────
    public function choix(?string $resaId = null): void {
        $this->requireAuth();
        if (!$resaId) { $this->redirect('client/dashboard'); }

        $reservation = $this->resaModel->findByIdDetails((int)$resaId);

        if (!$reservation) {
            $_SESSION['flash'] = ['type'=>'danger','msg'=>'Réservation introuvable.'];
            $this->redirect('client/dashboard');
        }

        // Vérifier que c'est bien la réservation du client connecté
        if ($reservation['id_client'] != $_SESSION['user_id'] && $_SESSION['user_role'] === 'client') {
            $_SESSION['flash'] = ['type'=>'danger','msg'=>'Accès non autorisé.'];
            $this->redirect('client/dashboard');
        }

        // Vérifier si déjà une facture
        $facture = $this->factureModel->findByReservation((int)$resaId);
        if ($facture && $facture['statut_paiement'] === 'paye') {
            $this->redirect('paiement/facture/' . $facture['id']);
        }

        $this->render('paiement/choix', compact('reservation'));
    }

    // ── Traiter le paiement ───────────────────────
    public function traiter(?string $resaId = null): void {
        $this->requireAuth();
        if (!$resaId || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('client/dashboard');
        }

        $reservation = $this->resaModel->findByIdDetails((int)$resaId);
        if (!$reservation) {
            $this->redirect('client/dashboard');
        }

        $modePaiement = $this->post('mode_paiement');
        $modesValides = ['wave', 'orange_money', 'especes', 'virement'];
        if (!in_array($modePaiement, $modesValides)) {
            $_SESSION['flash'] = ['type'=>'warning','msg'=>'Veuillez choisir un mode de paiement.'];
            $this->redirect('paiement/choix/' . $resaId);
        }

        // Calculer HT et TVA (18%)
        $montantTotal = (float) $reservation['montant_total'];
        $tva          = round($montantTotal * 0.18, 2);
        $montantHT    = round($montantTotal - $tva, 2);

        // Créer ou mettre à jour la facture
        $factureExist = $this->factureModel->findByReservation((int)$resaId);

        if (!$factureExist) {
            $factureId = $this->factureModel->create([
                'montant_ht'       => $montantHT,
                'tva'              => $tva,
                'montant_ttc'      => $montantTotal,
                'statut_paiement'  => $modePaiement === 'especes' ? 'en_attente' : 'paye',
                'id_reservation'   => (int)$resaId,
                'mode_paiement'    => $modePaiement,
            ]);
        } else {
            $factureId = $factureExist['id'];
            $this->factureModel->updateStatutPaiement(
                $factureId,
                $modePaiement === 'especes' ? 'en_attente' : 'paye'
            );
        }

        // Confirmer la réservation
        $this->resaModel->updateStatut((int)$resaId, 'confirmee', $_SESSION['user_id']);

        // Mettre le véhicule en "loué"
        $this->vehiculeModel->updateStatut($reservation['id_vehicule'], 'loue');

        $this->redirect('paiement/confirmation/' . $factureId);
    }

    // ── Page de confirmation ──────────────────────
    public function confirmation(?string $factureId = null): void {
        $this->requireAuth();
        if (!$factureId) { $this->redirect('client/dashboard'); }

        $facture = $this->factureModel->findByReservation(0);
        // Récupérer via id facture
        $db = \Database::getInstance();
        $stmt = $db->prepare("
            SELECT f.*, f.id_reservation,
                   r.reference, r.date_debut, r.date_fin, r.nb_jours, r.montant_total, r.id_vehicule,
                   CONCAT(u.prenom,' ',u.nom) AS client_nom, u.email AS client_email, u.telephone AS client_tel,
                   CONCAT(v.marque,' ',v.modele) AS vehicule_nom, v.immatriculation
            FROM factures f
            JOIN reservations r ON f.id_reservation = r.id
            JOIN utilisateurs u ON r.id_client = u.id
            JOIN vehicules v ON r.id_vehicule = v.id
            WHERE f.id = ?
        ");
        $stmt->execute([(int)$factureId]);
        $facture = $stmt->fetch();

        if (!$facture) {
            $this->redirect('client/dashboard');
        }

        $reservation = $facture; // Les données sont fusionnées
        $this->render('paiement/confirmation', compact('facture', 'reservation'));
    }

    // ── Afficher la facture ───────────────────────
    public function facture(?string $factureId = null): void {
        $this->requireAuth();
        if (!$factureId) { $this->redirect('client/dashboard'); }

        $db = \Database::getInstance();
        $stmt = $db->prepare("
            SELECT f.*, f.id_reservation,
                   r.reference, r.date_debut, r.date_fin, r.nb_jours, r.montant_total,
                   CONCAT(u.prenom,' ',u.nom) AS client_nom, u.email AS client_email,
                   u.telephone AS client_tel, u.adresse AS client_adresse,
                   CONCAT(v.marque,' ',v.modele) AS vehicule_nom, v.immatriculation
            FROM factures f
            JOIN reservations r ON f.id_reservation = r.id
            JOIN utilisateurs u ON r.id_client = u.id
            JOIN vehicules v ON r.id_vehicule = v.id
            WHERE f.id = ?
        ");
        $stmt->execute([(int)$factureId]);
        $facture = $stmt->fetch();

        if (!$facture) {
            $this->redirect('client/dashboard');
        }

        // Sécurité : seul le client propriétaire ou un admin peut voir
        $resaDetails = $this->resaModel->findById($facture['id_reservation']);
        if ($resaDetails['id_client'] != $_SESSION['user_id'] && $_SESSION['user_role'] === 'client') {
            $this->redirect('client/dashboard');
        }

        $this->render('paiement/facture', compact('facture'));
    }
}
