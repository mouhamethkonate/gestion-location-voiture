<?php if (empty($_SESSION['csrf_token'])) { $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); } ?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="auto" id="html-root">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? APP_NAME ?></title>
<link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🚗</text></svg>">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="<?= BASE_URL ?>css/style.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg sticky-top carloc-nav shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
      <i class="bi bi-car-front-fill text-primary me-1"></i> Car<span class="text-primary">Loc</span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navmenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navmenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>"><i class="bi bi-house me-1"></i>Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>home/contact"><i class="bi bi-envelope me-1"></i>Contact</a></li>
        <?php if (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin','agent'])): ?>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/dashboard"><i class="bi bi-speedometer2 me-1"></i>Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/vehicules"><i class="bi bi-car-front me-1"></i>Véhicules</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/reservations"><i class="bi bi-calendar-check me-1"></i>Réservations</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/categories"><i class="bi bi-grid me-1"></i>Catégories</a></li>
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/utilisateurs"><i class="bi bi-people me-1"></i>Clients</a></li>
        <?php endif; ?>
        <?php elseif (isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>client/dashboard"><i class="bi bi-person-circle me-1"></i>Mon espace</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav align-items-center gap-2">
        <!-- Dark mode toggle -->
        <li class="nav-item">
          <button class="btn btn-sm btn-outline-secondary rounded-pill" id="themeToggle" title="Changer le thème">
            <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
          </button>
        </li>
        <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown">
            <span class="avatar-circle"><?= strtoupper(substr($_SESSION['user_nom'], 0, 1)) ?></span>
            <?= htmlspecialchars($_SESSION['user_nom']) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end shadow">
            <li><span class="dropdown-item-text small text-muted"><?= $_SESSION['user_email'] ?></span></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="<?= BASE_URL ?>client/profil"><i class="bi bi-person-gear me-2"></i>Mon profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item text-danger" href="<?= BASE_URL ?>auth/logout"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
          </ul>
        </li>
        <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>auth/login">Connexion</a></li>
        <li class="nav-item"><a class="btn btn-primary btn-sm rounded-pill px-3" href="<?= BASE_URL ?>auth/register">S'inscrire</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<!-- FLASH MESSAGE -->
<?php if (!empty($_SESSION['flash'])): ?>
<div class="container mt-3">
  <div class="alert alert-<?= $_SESSION['flash']['type'] ?> alert-dismissible fade show" role="alert">
    <i class="bi bi-<?= $_SESSION['flash']['type'] === 'success' ? 'check-circle' : 'exclamation-triangle' ?>-fill me-2"></i>
    <?= htmlspecialchars($_SESSION['flash']['msg']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<!-- MAIN CONTENT -->
<main>
