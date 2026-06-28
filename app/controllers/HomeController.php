<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Vehicule.php';

class HomeController extends Controller {

    public function index(?string $p = null): void {
        $vehiculeModel = new Vehicule();

        $dateDebut = $_GET['date_debut'] ?? '';
        $dateFin   = $_GET['date_fin']   ?? '';
        $categorie = $_GET['categorie']  ?? '';
        $prixMax   = (int)($_GET['prix_max'] ?? 0);

        $vehicules = $vehiculeModel->findDisponibles($dateDebut, $dateFin);

        // Filtrer par catégorie côté PHP
        if ($categorie) {
            $vehicules = array_filter($vehicules, fn($v) => $v['id_categorie'] == $categorie);
        }
        // Filtrer par prix maximum
        if ($prixMax > 0) {
            $vehicules = array_filter($vehicules, fn($v) => $v['prix_par_jour'] <= $prixMax);
        }

        $db = Database::getInstance();
        $categories = $db->query("SELECT * FROM categories ORDER BY nom")->fetchAll();

        $this->render('home/index', compact('vehicules', 'categories', 'dateDebut', 'dateFin', 'categorie'));
    }

    public function vehicule(?string $id = null): void {
        if (!$id) { $this->redirect(''); }
        $vehiculeModel = new Vehicule();
        $vehicule = $vehiculeModel->findByIdWithCategory((int)$id);
        if (!$vehicule) { $this->notFound(); return; }
        $this->render('vehicules/detail', compact('vehicule'));
    }

    public function contact(?string $p = null): void {
        $this->render('home/contact', []);
    }

    public function sendContact(?string $p = null): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ici on pourrait envoyer un email avec mail()
            // Pour la démo on redirige avec confirmation
            $_SESSION['flash'] = ['type' => 'success', 'msg' => '✅ Message envoyé ! Nous vous répondrons sous 24h.'];
        }
        $this->redirect('home/contact?sent=1');
    }

    public function notFound(?string $p = null): void {
        http_response_code(404);
        $this->render('errors/404', []);
    }
}
