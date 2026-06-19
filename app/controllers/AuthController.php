<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Utilisateur.php';

class AuthController extends Controller {

    private Utilisateur $model;

    public function __construct() {
        $this->model = new Utilisateur();
    }

    public function login(?string $p = null): void {
        if ($this->isAuthenticated()) { $this->redirect(''); }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->post('email');
            $mdp   = $_POST['mot_de_passe'] ?? '';

            $user = $this->model->findByEmail($email);

            if ($user && $this->model->verifyPassword($mdp, $user['mot_de_passe']) && $user['actif']) {
                $_SESSION['user_id']    = $user['id'];
                $_SESSION['user_nom']   = $user['prenom'] . ' ' . $user['nom'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role']  = $user['role'];
                $_SESSION['flash']      = ['type' => 'success', 'msg' => 'Bienvenue, ' . $user['prenom'] . ' !'];

                match($user['role']) {
                    'admin', 'agent' => $this->redirect('admin/dashboard'),
                    default          => $this->redirect('client/dashboard'),
                };
            } else {
                $_SESSION['flash'] = ['type' => 'danger', 'msg' => 'Email ou mot de passe incorrect.'];
                $this->render('auth/login', ['email' => $email]);
            }
            return;
        }
        $this->render('auth/login', ['email' => '']);
    }

    public function register(?string $p = null): void {
        if ($this->isAuthenticated()) { $this->redirect(''); }

        $errors = [];
        $data = ['nom' => '', 'prenom' => '', 'email' => '', 'telephone' => ''];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom'       => $this->post('nom'),
                'prenom'    => $this->post('prenom'),
                'email'     => $this->post('email'),
                'telephone' => $this->post('telephone'),
            ];
            $mdp    = $_POST['mot_de_passe']         ?? '';
            $mdpCfm = $_POST['mot_de_passe_confirm'] ?? '';

            if (empty($data['nom']))      $errors[] = 'Le nom est requis.';
            if (empty($data['prenom']))   $errors[] = 'Le prénom est requis.';
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
            if (strlen($mdp) < 6)         $errors[] = 'Le mot de passe doit avoir au moins 6 caractères.';
            if ($mdp !== $mdpCfm)         $errors[] = 'Les mots de passe ne correspondent pas.';
            if ($this->model->findByEmail($data['email'])) $errors[] = 'Cet email est déjà utilisé.';

            if (empty($errors)) {
                $this->model->create([...$data, 'mot_de_passe' => $mdp]);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Compte créé ! Connectez-vous.'];
                $this->redirect('auth/login');
            }
        }
        $this->render('auth/register', ['data' => $data, 'errors' => $errors]);
    }

    public function logout(?string $p = null): void {
        session_destroy();
        header('Location: ' . BASE_URL . 'auth/login');
        exit;
    }
}
