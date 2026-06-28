<?php
require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Vehicule.php';
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Utilisateur.php';

class AdminController extends Controller {

    private Vehicule $vehiculeModel;
    private Reservation $resaModel;
    private Utilisateur $userModel;

    public function __construct() {
        $this->vehiculeModel = new Vehicule();
        $this->resaModel     = new Reservation();
        $this->userModel     = new Utilisateur();
    }

    public function dashboard(?string $p = null): void {
        $this->requireAuth('agent');
        $stats = [
            'vehicules_total'      => $this->vehiculeModel->count(),
            'vehicules_dispo'      => $this->vehiculeModel->countByStatut('disponible'),
            'vehicules_loues'      => $this->vehiculeModel->countByStatut('loue'),
            'reservations_total'   => $this->resaModel->count(),
            'reservations_attente' => $this->resaModel->countByStatut('en_attente'),
            'reservations_conf'    => $this->resaModel->countByStatut('confirmee'),
            'clients_total'        => $this->userModel->countByRole('client'),
            'chiffre_affaires'     => $this->resaModel->chiffreAffaires(),
            'categories_total'     => Database::getInstance()->query('SELECT COUNT(*) FROM categories')->fetchColumn(),
        ];
        $reservations_recentes = array_slice($this->resaModel->findAllDetails(), 0, 5);
        $this->render('admin/dashboard', compact('stats', 'reservations_recentes'));
    }

    // ── VÉHICULES ────────────────────────────────────────────

    public function vehicules(?string $p = null): void {
        $this->requireAuth('agent');
        $vehicules = $this->vehiculeModel->findAllWithCategory();
        $db = Database::getInstance();
        $categories = $db->query("SELECT * FROM categories")->fetchAll();
        $this->render('admin/vehicules', compact('vehicules', 'categories'));
    }

    public function addVehicule(?string $p = null): void {
        $this->requireAuth('admin');
        $errors = [];
        $db = Database::getInstance();
        $categories = $db->query("SELECT * FROM categories")->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'immatriculation' => $this->post('immatriculation'),
                'marque'          => $this->post('marque'),
                'modele'          => $this->post('modele'),
                'annee'           => $this->post('annee'),
                'couleur'         => $this->post('couleur'),
                'places'          => (int)($_POST['places'] ?? 5),
                'prix_par_jour'   => (float)($_POST['prix_par_jour'] ?? 0),
                'statut'          => $this->post('statut') ?: 'disponible',
                'description'     => $this->post('description'),
                'id_categorie'    => (int)($_POST['id_categorie'] ?? 0),
                'image'           => 'default.jpg',
            ];

            // Upload image
            if (!empty($_FILES['image']['name'])) {
                $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $allowed = ['jpg','jpeg','png','webp'];
                if (in_array(strtolower($ext), $allowed)) {
                    $filename = uniqid('car_') . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], __DIR__ . '/../../public/img/vehicules/' . $filename);
                    $data['image'] = $filename;
                }
            }

            if (empty($data['immatriculation'])) $errors[] = 'Immatriculation requise.';
            if (empty($data['marque']))           $errors[] = 'Marque requise.';
            if ($data['prix_par_jour'] <= 0)      $errors[] = 'Prix invalide.';

            if (empty($errors)) {
                $this->vehiculeModel->create($data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Véhicule ajouté avec succès.'];
                $this->redirect('admin/vehicules');
            }
        }
        $this->render('admin/vehicule_form', compact('categories', 'errors'));
    }

    public function editVehicule(?string $id = null): void {
        $this->requireAuth('admin');
        if (!$id) { $this->redirect('admin/vehicules'); }
        $vehicule = $this->vehiculeModel->findById((int)$id);
        if (!$vehicule) { $this->redirect('admin/vehicules'); }

        $db = Database::getInstance();
        $categories = $db->query("SELECT * FROM categories")->fetchAll();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'immatriculation' => $this->post('immatriculation'),
                'marque'          => $this->post('marque'),
                'modele'          => $this->post('modele'),
                'annee'           => $this->post('annee'),
                'couleur'         => $this->post('couleur'),
                'places'          => (int)($_POST['places'] ?? 5),
                'prix_par_jour'   => (float)($_POST['prix_par_jour'] ?? 0),
                'statut'          => $this->post('statut'),
                'description'     => $this->post('description'),
                'id_categorie'    => (int)($_POST['id_categorie'] ?? 0),
                'image'           => $vehicule['image'] ?? 'default.jpg',
            ];

            // Upload nouvelle image si fournie
            if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];
                if (in_array($ext, $allowed)) {
                    $oldImage = $vehicule['image'] ?? '';
                    if ($oldImage && !in_array($oldImage, ['default.jpg','default.svg'])) {
                        $oldPath = __DIR__ . '/../../public/img/vehicules/' . $oldImage;
                        if (file_exists($oldPath)) unlink($oldPath);
                    }
                    $filename       = uniqid('car_') . '.' . $ext;
                    move_uploaded_file($_FILES['image']['tmp_name'],
                        __DIR__ . '/../../public/img/vehicules/' . $filename);
                    $data['image']  = $filename;
                } else {
                    $errors[] = 'Format non autorisé (jpg, jpeg, png, webp).';
                }
            }

            if (empty($errors)) {
                $this->vehiculeModel->update((int)$id, $data);
                $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Véhicule modifié avec succès.'];
                $this->redirect('admin/vehicules');
            }
        }

        $this->render('admin/vehicule_form', compact('vehicule', 'categories', 'errors'));
    }

    public function deleteVehicule(?string $id = null): void {
        $this->requireAuth('admin');
        if ($id) { $this->vehiculeModel->delete((int)$id); }
        $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Véhicule supprimé.'];
        $this->redirect('admin/vehicules');
    }

    // ── RÉSERVATIONS ─────────────────────────────────────────

    public function reservations(?string $p = null): void {
        $this->requireAuth('agent');
        $reservations = $this->resaModel->findAllDetails();
        $this->render('admin/reservations', compact('reservations'));
    }

    public function confirmerResa(?string $id = null): void {
        $this->requireAuth('agent');
        if ($id) {
            $this->resaModel->updateStatut((int)$id, 'confirmee', $_SESSION['user_id']);
            // Mettre le véhicule en "loué"
            $resa = $this->resaModel->findById((int)$id);
            if ($resa) $this->vehiculeModel->updateStatut($resa['id_vehicule'], 'loue');
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Réservation confirmée.'];
        }
        $this->redirect('admin/reservations');
    }

    public function terminerResa(?string $id = null): void {
        $this->requireAuth('agent');
        if ($id) {
            $this->resaModel->updateStatut((int)$id, 'terminee', $_SESSION['user_id']);
            $resa = $this->resaModel->findById((int)$id);
            if ($resa) $this->vehiculeModel->updateStatut($resa['id_vehicule'], 'disponible');
            $_SESSION['flash'] = ['type' => 'success', 'msg' => 'Retour enregistré. Véhicule disponible.'];
        }
        $this->redirect('admin/reservations');
    }

    // ── CATÉGORIES ───────────────────────────────────────────

    public function categories(?string $p = null): void {
        $this->requireAuth('agent');
        $db = Database::getInstance();
        $categories = $db->query("
            SELECT c.*, COUNT(v.id) AS nb_vehicules
            FROM categories c
            LEFT JOIN vehicules v ON v.id_categorie = c.id
            GROUP BY c.id ORDER BY c.nom
        ")->fetchAll();
        $errors = [];
        $this->render('admin/categories', compact('categories', 'errors'));
    }

    public function addCategorie(?string $p = null): void {
        $this->requireAuth('admin');
        $db = Database::getInstance();
        $errors = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom  = $this->post('nom');
            $desc = $this->post('description');
            if (empty($nom)) { $errors[] = 'Le nom est requis.'; }
            if (empty($errors)) {
                $db->prepare("INSERT INTO categories (nom, description) VALUES (?, ?)")->execute([$nom, $desc]);
                $_SESSION['flash'] = ['type'=>'success','msg'=>'Catégorie ajoutée.'];
                $this->redirect('admin/categories');
            }
        }
        $categories = $db->query("SELECT c.*, COUNT(v.id) AS nb_vehicules FROM categories c LEFT JOIN vehicules v ON v.id_categorie = c.id GROUP BY c.id ORDER BY c.nom")->fetchAll();
        $this->render('admin/categories', compact('categories', 'errors'));
    }

    public function editCategorie(?string $id = null): void {
        $this->requireAuth('admin');
        if (!$id) { $this->redirect('admin/categories'); }
        $db = Database::getInstance();
        $errors = [];
        $stmt = $db->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $editCategorie = $stmt->fetch();
        if (!$editCategorie) { $this->redirect('admin/categories'); }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom  = $this->post('nom');
            $desc = $this->post('description');
            if (empty($nom)) { $errors[] = 'Le nom est requis.'; }
            if (empty($errors)) {
                $db->prepare("UPDATE categories SET nom=?, description=? WHERE id=?")->execute([$nom, $desc, $id]);
                $_SESSION['flash'] = ['type'=>'success','msg'=>'Catégorie modifiée.'];
                $this->redirect('admin/categories');
            }
        }
        $categories = $db->query("SELECT c.*, COUNT(v.id) AS nb_vehicules FROM categories c LEFT JOIN vehicules v ON v.id_categorie = c.id GROUP BY c.id ORDER BY c.nom")->fetchAll();
        $this->render('admin/categories', compact('categories', 'errors', 'editCategorie'));
    }

    public function updateCategorie(?string $id = null): void {
        $this->editCategorie($id);
    }

    public function deleteCategorie(?string $id = null): void {
        $this->requireAuth('admin');
        if ($id) {
            $db = Database::getInstance();
            $stmt = $db->prepare("SELECT COUNT(*) FROM vehicules WHERE id_categorie = ?");
            $stmt->execute([$id]);
            if ((int)$stmt->fetchColumn() === 0) {
                $db->prepare("DELETE FROM categories WHERE id = ?")->execute([$id]);
                $_SESSION['flash'] = ['type'=>'success','msg'=>'Catégorie supprimée.'];
            } else {
                $_SESSION['flash'] = ['type'=>'danger','msg'=>'Impossible : des véhicules utilisent cette catégorie.'];
            }
        }
        $this->redirect('admin/categories');
    }

    // ── UTILISATEURS ─────────────────────────────────────────

    public function utilisateurs(?string $p = null): void {
        $this->requireAuth('admin');
        $utilisateurs = $this->userModel->findAll('created_at DESC');
        $this->render('admin/utilisateurs', compact('utilisateurs'));
    }

    public function editUtilisateur(?string $id = null): void {
        $this->requireAuth('admin');
        if (!$id) { $this->redirect('admin/utilisateurs'); }

        $user   = $this->userModel->findById((int)$id);
        if (!$user) { $this->redirect('admin/utilisateurs'); }

        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $this->post('action');

            if ($action === 'profil') {
                $data = [
                    'nom'       => $this->post('nom'),
                    'prenom'    => $this->post('prenom'),
                    'telephone' => $this->post('telephone'),
                    'adresse'   => $this->post('adresse'),
                ];
                if (empty($data['nom']))    $errors[] = 'Le nom est requis.';
                if (empty($data['prenom'])) $errors[] = 'Le prénom est requis.';

                // Mise à jour du rôle (admin seulement)
                $role = $this->post('role');
                if (in_array($role, ['admin','agent','client'])) {
                    Database::getInstance()->prepare(
                        "UPDATE utilisateurs SET nom=?, prenom=?, telephone=?, adresse=?, role=? WHERE id=?"
                    )->execute([$data['nom'], $data['prenom'], $data['telephone'], $data['adresse'], $role, $id]);
                } else {
                    $this->userModel->update((int)$id, $data);
                }

                if (empty($errors)) {
                    $_SESSION['flash'] = ['type'=>'success','msg'=>'Utilisateur modifié avec succès.'];
                    $this->redirect('admin/utilisateurs');
                }
            } elseif ($action === 'password') {
                $nouveau = $_POST['mdp_nouveau'] ?? '';
                $confirm = $_POST['mdp_confirm'] ?? '';
                if (strlen($nouveau) < 6)    $errors[] = 'Minimum 6 caractères.';
                if ($nouveau !== $confirm)   $errors[] = 'Les mots de passe ne correspondent pas.';
                if (empty($errors)) {
                    $this->userModel->updatePassword((int)$id, $nouveau);
                    $_SESSION['flash'] = ['type'=>'success','msg'=>'Mot de passe réinitialisé.'];
                    $this->redirect('admin/utilisateurs');
                }
            } elseif ($action === 'toggle') {
                $actif = $user['actif'] ? 0 : 1;
                Database::getInstance()->prepare(
                    "UPDATE utilisateurs SET actif=? WHERE id=?"
                )->execute([$actif, $id]);
                $_SESSION['flash'] = ['type'=>'success','msg'=>'Statut utilisateur mis à jour.'];
                $this->redirect('admin/utilisateurs');
            }
        }

        $this->render('admin/edit_utilisateur', compact('user', 'errors'));
    }

    public function toggleUtilisateur(?string $id = null): void {
        $this->requireAuth('admin');
        if ($id) {
            $user = $this->userModel->findById((int)$id);
            if ($user) {
                $actif = $user['actif'] ? 0 : 1;
                Database::getInstance()->prepare(
                    "UPDATE utilisateurs SET actif=? WHERE id=?"
                )->execute([$actif, $id]);
                $msg = $actif ? 'Compte activé.' : 'Compte désactivé.';
                $_SESSION['flash'] = ['type'=>'success','msg'=>$msg];
            }
        }
        $this->redirect('admin/utilisateurs');
    }
}
