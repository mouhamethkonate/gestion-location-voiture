<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>
<!DOCTYPE html>
<html lang="fr" data-bs-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'CarLoc – Location de voitures' ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top carloc-nav">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>">
      <i class="bi bi-car-front-fill text-primary me-1"></i>Car<span class="text-primary">Loc</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>">Accueil</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>home/contact">Contact</a></li>
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>admin/dashboard">Dashboard Admin</a></li>
        <?php elseif (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'client'): ?>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>client/dashboard">Mon espace</a></li>
        <?php endif; ?>
      </ul>
      <div class="d-flex align-items-center gap-2">
        <button id="themeToggle" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:34px;height:34px;">
          <i class="bi bi-moon-fill"></i>
        </button>
        <?php if (isset($_SESSION['user_id'])): ?>
        <span class="text-muted small">
          <?= htmlspecialchars($_SESSION['user_nom']) ?>
          <span class="badge bg-primary ms-1"><?= $_SESSION['user_role'] ?></span>
        </span>
        <a href="<?= BASE_URL ?>auth/logout" class="btn btn-sm btn-outline-danger rounded-pill px-3">
          <i class="bi bi-box-arrow-right me-1"></i>Déconnexion
        </a>
        <?php else: ?>
        <a href="<?= BASE_URL ?>auth/login" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">Connexion</a>
        <a href="<?= BASE_URL ?>auth/register" class="btn btn-sm btn-primary rounded-pill px-3">S'inscrire</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</nav>
<main>
