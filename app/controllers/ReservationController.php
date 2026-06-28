<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Vehicule.php';

class ReservationController extends Controller {

    private Reservation $model;
    private Vehicule $vehiculeModel;

    public function __construct() {
        $this->model         = new Reservation();
        $this->vehiculeModel = new Vehicule();
    }

    public function index(?string $p = null): void {
        $this->requireAuth();
        $reservations = $this->model->findByClient($_SESSION['user_id']);
        $this->render('reservations/liste', compact('reservations'));
    }

    public function create(?string $vehiculeId = null): void {
        $this->requireAuth();

        if (!$vehiculeId) { $this->redirect(''); }

        $vehicule = $this->vehiculeModel->findByIdWithCategory((int)$vehiculeId);
        if (!$vehicule || $vehicule['statut'] !== 'disponible') {
            $_SESSION['flash'] = ['type' => 'warning', 'msg' => 'Ce véhicule n\'est pas disponible.'];
            $this->redirect('');
        }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dateDebut = $_POST['date_debut'] ?? '';
            $dateFin   = $_POST['date_fin']   ?? '';
            $notes     = $this->post('notes');

            if (!$dateDebut || !$dateFin)                   $errors[] = 'Les dates sont requises.';
            elseif ($dateFin <= $dateDebut)                  $errors[] = 'La date de fin doit être après la date de début.';
            elseif ($dateDebut < date('Y-m-d'))              $errors[] = 'La date de début ne peut pas être dans le passé.';

            if (empty($errors)) {
                $nbJours = (int) ceil((strtotime($dateFin) - strtotime($dateDebut)) / 86400);
                $montant = $nbJours * $vehicule['prix_par_jour'];

                $id = $this->model->create([
                    'date_debut'    => $dateDebut,
                    'date_fin'      => $dateFin,
                    'nb_jours'      => $nbJours,
                    'montant_total' => $montant,
                    'notes'         => $notes,
                    'id_client'     => $_SESSION['user_id'],
                    'id_vehicule'   => $vehicule['id'],
                ]);

                $_SESSION['flash'] = ['type' => 'success', 'msg' => '✅ Réservation créée ! Choisissez maintenant votre mode de paiement.'];
                $this->redirect('paiement/choix/' . $id);
            }
        }

        $this->render('reservations/create', compact('vehicule', 'errors'));
    }

    public function annuler(?string $id = null): void {
        $this->requireAuth();
        if (!$id) { $this->redirect('reservation'); }

        $resa = $this->model->findById((int)$id);
        if ($resa && $resa['id_client'] == $_SESSION['user_id'] && $resa['statut'] === 'en_attente') {
            $this->model->updateStatut((int)$id, 'annulee');
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Réservation annulée.'];
        } else {
            $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Annulation impossible.'];
        }
        $this->redirect('client/dashboard');
    }
}
