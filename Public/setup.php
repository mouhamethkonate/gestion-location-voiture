<?php
// ============================================================
//  CarLoc – Setup initial
//  Lance ce fichier UNE SEULE FOIS après l'import SQL
//  URL : http://localhost/carloc/public/setup.php
//  SUPPRIME ce fichier après utilisation !
// ============================================================

// Sécurité : clé secrète à changer
define('SETUP_KEY', 'carloc_setup_2025');

if (($_GET['key'] ?? '') !== SETUP_KEY) {
    die('<h2>❌ Clé incorrecte.</h2><p>Ajoutez <code>?key=carloc_setup_2025</code> à l\'URL.</p>');
}

require_once __DIR__ . '/../config/database.php';
$pdo = Database::getInstance();

// ── Comptes à créer ──────────────────────────────────────────
$comptes = [
    [
        'nom'       => 'Diallo',
        'prenom'    => 'Mamadou',
        'email'     => 'admin@carloc.sn',
        'password'  => 'Admin@2025',
        'telephone' => '77 000 00 00',
        'role'      => 'admin',
    ],
    [
        'nom'       => 'Ndiaye',
        'prenom'    => 'Fatou',
        'email'     => 'agent@carloc.sn',
        'password'  => 'Agent@2025',
        'telephone' => '76 000 00 01',
        'role'      => 'agent',
    ],
    [
        'nom'       => 'Sow',
        'prenom'    => 'Ibrahima',
        'email'     => 'client@carloc.sn',
        'password'  => 'Client@2025',
        'telephone' => '70 000 00 02',
        'role'      => 'client',
    ],
];

$resultats = [];

foreach ($comptes as $c) {
    // Supprimer si existe déjà
    $pdo->prepare("DELETE FROM utilisateurs WHERE email = ?")->execute([$c['email']]);

    // Hash PHP natif
    $hash = password_hash($c['password'], PASSWORD_BCRYPT, ['cost' => 10]);

    // Vérification immédiate
    $ok = password_verify($c['password'], $hash);

    // Insertion
    $stmt = $pdo->prepare("
        INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, telephone, role, actif)
        VALUES (?, ?, ?, ?, ?, ?, 1)
    ");
    $stmt->execute([$c['nom'], $c['prenom'], $c['email'], $hash, $c['telephone'], $c['role']]);

    $resultats[] = [
        'email'    => $c['email'],
        'password' => $c['password'],
        'role'     => $c['role'],
        'hash_ok'  => $ok,
        'inserted' => $stmt->rowCount() > 0,
    ];
}

// Test de connexion réel
$tests = [];
foreach ($resultats as $r) {
    $user = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $user->execute([$r['email']]);
    $row = $user->fetch(PDO::FETCH_ASSOC);
    $tests[] = [
        'email'   => $r['email'],
        'role'    => $r['role'],
        'found'   => $row !== false,
        'verify'  => $row ? password_verify($r['password'], $row['mot_de_passe']) : false,
        'actif'   => $row ? (bool)$row['actif'] : false,
        'password'=> $r['password'],
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>CarLoc – Setup</title>
<style>
  body { font-family: Arial, sans-serif; max-width: 700px; margin: 40px auto; padding: 20px; }
  h1 { color: #1a6bc8; }
  .card { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin: 12px 0; }
  .ok  { background: #d4edda; border-color: #28a745; }
  .err { background: #f8d7da; border-color: #dc3545; }
  code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; font-size: 0.9em; }
  .badge { display:inline-block; padding: 3px 10px; border-radius: 20px; font-size: 0.8em; font-weight:bold; color:#fff; }
  .bg-admin  { background:#dc3545; }
  .bg-agent  { background:#ffc107; color:#000; }
  .bg-client { background:#1a6bc8; }
  table { width:100%; border-collapse: collapse; margin-top: 20px; }
  th { background: #1a6bc8; color: white; padding: 10px; text-align: left; }
  td { padding: 10px; border-bottom: 1px solid #eee; }
  .warn { background: #fff3cd; border: 1px solid #ffc107; border-radius:6px; padding:12px; margin-top:20px; }
</style>
</head>
<body>

<h1>🚗 CarLoc – Initialisation des comptes</h1>

<?php foreach ($tests as $t): ?>
<div class="card <?= ($t['found'] && $t['verify'] && $t['actif']) ? 'ok' : 'err' ?>">
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <div>
      <span class="badge bg-<?= $t['role'] ?>"><?= strtoupper($t['role']) ?></span>
      <strong style="margin-left:10px;"><?= $t['email'] ?></strong>
    </div>
    <div><?= ($t['found'] && $t['verify'] && $t['actif']) ? '✅ OK' : '❌ ERREUR' ?></div>
  </div>
  <div style="margin-top:8px; font-size:0.85em; color:#555;">
    Mot de passe : <code><?= $t['password'] ?></code> &nbsp;|&nbsp;
    Trouvé en BDD : <?= $t['found'] ? '✅' : '❌' ?> &nbsp;|&nbsp;
    Hash valide : <?= $t['verify'] ? '✅' : '❌' ?> &nbsp;|&nbsp;
    Actif : <?= $t['actif'] ? '✅' : '❌' ?>
  </div>
</div>
<?php endforeach; ?>

<h2 style="margin-top:30px;">📋 Comptes de connexion</h2>
<table>
  <tr><th>Rôle</th><th>Email</th><th>Mot de passe</th><th>Accès</th></tr>
  <tr><td><span class="badge bg-admin">Admin</span></td><td>admin@carloc.sn</td><td><code>Admin@2025</code></td><td>Complet</td></tr>
  <tr><td><span class="badge bg-agent">Agent</span></td><td>agent@carloc.sn</td><td><code>Agent@2025</code></td><td>Réservations</td></tr>
  <tr><td><span class="badge bg-client">Client</span></td><td>client@carloc.sn</td><td><code>Client@2025</code></td><td>Espace client</td></tr>
</table>

<div class="warn">
  <strong>⚠️ Important :</strong> Supprime ce fichier <code>setup.php</code> après utilisation pour des raisons de sécurité !<br>
  <code>C:\xampp\htdocs\carloc\public\setup.php</code>
</div>

<div style="margin-top:20px; text-align:center;">
  <a href="<?= defined('BASE_URL') ? BASE_URL : '../public/' ?>auth/login"
     style="background:#1a6bc8; color:white; padding:12px 30px; border-radius:25px; text-decoration:none; font-weight:bold;">
    → Aller à la page de connexion
  </a>
</div>


<!-- ═══════════════════════════════════════════ -->
<!-- OUTIL : Générer un hash pour phpMyAdmin    -->
<!-- ═══════════════════════════════════════════ -->
<hr>
<h2 style="margin-top:30px;">🔑 Générer un hash bcrypt pour phpMyAdmin</h2>
<p style="font-size:0.9rem; color:#555;">
  Si tu modifies un mot de passe directement dans phpMyAdmin, tu dois entrer 
  un <strong>hash bcrypt</strong> et non le mot de passe en clair.
  Utilise ce formulaire pour générer le bon hash.
</p>

<form method="GET" style="background:#f4f4f4; padding:1rem; border-radius:8px; margin-bottom:1rem;">
  <input type="hidden" name="key" value="carloc_setup_2025">
  <input type="hidden" name="action" value="hash">
  <div style="display:flex; gap:1rem; align-items:flex-end; flex-wrap:wrap;">
    <div>
      <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:4px;">Mot de passe à hasher</label>
      <input type="text" name="mdp" placeholder="Ex: MonMotDePasse123"
             value="<?= htmlspecialchars($_GET['mdp'] ?? '') ?>"
             style="padding:0.5rem 0.8rem; border:1px solid #ccc; border-radius:6px; font-size:0.9rem; width:250px;">
    </div>
    <button type="submit" style="background:#1a6bc8; color:white; border:none; padding:0.5rem 1.5rem; border-radius:6px; cursor:pointer; font-weight:600;">
      Générer le hash
    </button>
  </div>
</form>

<?php if (($_GET['action'] ?? '') === 'hash' && !empty($_GET['mdp'])): ?>
<?php $generatedHash = password_hash($_GET['mdp'], PASSWORD_BCRYPT, ['cost' => 10]); ?>
<div style="background:#d4edda; border:1px solid #28a745; border-radius:8px; padding:1rem; margin-bottom:1rem;">
  <strong style="color:#155724;">✅ Hash généré pour : "<?= htmlspecialchars($_GET['mdp']) ?>"</strong>
  <br><br>
  <div style="background:#fff; border:1px solid #ccc; border-radius:4px; padding:0.6rem; font-family:monospace; font-size:0.85rem; word-break:break-all; user-select:all;">
    <?= $generatedHash ?>
  </div>
  <br>
  <p style="font-size:0.85rem; color:#155724; margin:0;">
    <strong>Comment utiliser :</strong><br>
    1. Copie ce hash (clique dessus, Ctrl+A puis Ctrl+C)<br>
    2. Dans phpMyAdmin → table <code>utilisateurs</code> → colonne <code>mot_de_passe</code><br>
    3. Colle le hash à la place de l'ancien<br>
    4. Sauvegarde ✅
  </p>
</div>
<div style="background:#fff3cd; border:1px solid #ffc107; border-radius:6px; padding:0.8rem; font-size:0.85rem;">
  <strong>Vérification :</strong>
  <?= password_verify($_GET['mdp'], $generatedHash) ? '✅ Le hash est valide' : '❌ Erreur' ?>
</div>
<?php endif; ?>

</body>
</html>
