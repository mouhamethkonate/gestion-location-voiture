<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Utilisateur.php';

class ClientController extends Controller {

    public function dashboard(?string $p = null): void {
        $this->requireAuth();
        $model = new Reservation();
        $reservations = $model->findByClient($_SESSION['user_id']);
        $stats = [
            'total'    => count($reservations),
            'attente'  => count(array_filter($reservations, fn($r) => $r['statut'] === 'en_attente')),
            'conf'     => count(array_filter($reservations, fn($r) => $r['statut'] === 'confirmee')),
            'termines' => count(array_filter($reservations, fn($r) => $r['statut'] === 'terminee')),
        ];
        $this->render('client/dashboard', compact('reservations', 'stats'));
    }

    public function profil(?string $p = null): void {
        $this->requireAuth();
        $userModel = new Utilisateur();
        $user      = $userModel->findById($_SESSION['user_id']);
        $resaModel = new Reservation();
        $resas     = $resaModel->findByClient($_SESSION['user_id']);

        $nbReservations = count($resas);
        $nbConfirmees   = count(array_filter($resas, fn($r) => $r['statut'] === 'confirmee'));
        $totalDepense   = array_sum(array_column(
            array_filter($resas, fn($r) => $r['statut'] === 'confirmee'),
            'montant_total'
        ));

        $errors = [];
        $this->render('client/profil', compact('user', 'errors', 'nbReservations', 'nbConfirmees', 'totalDepense'));
    }

    public function updateProfil(?string $p = null): void {
        $this->requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $this->redirect('client/profil'); }

        $userModel = new Utilisateur();
        $errors    = [];
        $action    = $this->post('action');

        if ($action === 'profil') {
            $data = [
                'nom'       => $this->post('nom'),
                'prenom'    => $this->post('prenom'),
                'telephone' => $this->post('telephone'),
                'adresse'   => $this->post('adresse'),
            ];
            if (empty($data['nom']))    $errors[] = 'Le nom est requis.';
            if (empty($data['prenom'])) $errors[] = 'Le prénom est requis.';

            if (empty($errors)) {
                $ok = $userModel->update($_SESSION['user_id'], $data);
                if ($ok) {
                    $_SESSION['user_nom'] = $data['prenom'] . ' ' . $data['nom'];
                    $_SESSION['flash'] = ['type'=>'success','msg'=>'✅ Profil mis à jour avec succès.'];
                    $this->redirect('client/profil');
                } else {
                    $errors[] = 'Erreur lors de la mise à jour. Veuillez réessayer.';
                }
            }
        } elseif ($action === 'password') {
            $actuel  = $_POST['mdp_actuel']  ?? '';
            $nouveau = $_POST['mdp_nouveau'] ?? '';
            $confirm = $_POST['mdp_confirm'] ?? '';
            $user    = $userModel->findById($_SESSION['user_id']);

            if (!$userModel->verifyPassword($actuel, $user['mot_de_passe'])) $errors[] = 'Mot de passe actuel incorrect.';
            if (strlen($nouveau) < 6) $errors[] = 'Le nouveau mot de passe doit avoir au moins 6 caractères.';
            if ($nouveau !== $confirm) $errors[] = 'Les mots de passe ne correspondent pas.';

            if (empty($errors)) {
                $userModel->updatePassword($_SESSION['user_id'], $nouveau);
                $_SESSION['flash'] = ['type'=>'success','msg'=>'Mot de passe changé avec succès.'];
                $this->redirect('client/profil');
            }
        }

        // En cas d'erreur, réafficher le profil
        $user      = $userModel->findById($_SESSION['user_id']);
        $resaModel = new Reservation();
        $resas     = $resaModel->findByClient($_SESSION['user_id']);
        $nbReservations = count($resas);
        $nbConfirmees   = count(array_filter($resas, fn($r) => $r['statut'] === 'confirmee'));
        $totalDepense   = array_sum(array_column(array_filter($resas, fn($r) => $r['statut'] === 'confirmee'), 'montant_total'));

        $this->render('client/profil', compact('user','errors','nbReservations','nbConfirmees','totalDepense'));
    }
}
