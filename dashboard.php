<?php $pageTitle = 'Dashboard Admin – CarLoc'; ?>
<div class="container-fluid py-4 px-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="fw-bold mb-1">Tableau de bord</h2>
      <p class="text-muted small mb-0">Bienvenue, <?= htmlspecialchars($_SESSION['user_nom']) ?> · <span class="badge bg-primary"><?= $_SESSION['user_role'] ?></span></p>
    </div>
    <a href="<?= BASE_URL ?>admin/addVehicule" class="btn btn-primary rounded-pill px-4">
      <i class="bi bi-plus-lg me-2"></i>Nouveau véhicule
    </a>
  </div>

  <!-- STATS CARDS -->
  <div class="row g-3 mb-4">
    <?php
    $cards = [
      ['🚗', 'Véhicules', $stats['vehicules_total'], 'primary', BASE_URL.'admin/vehicules'],
      ['✅', 'Disponibles', $stats['vehicules_dispo'], 'success', BASE_URL.'admin/vehicules'],
      ['🔑', 'En location', $stats['vehicules_loues'], 'warning', BASE_URL.'admin/reservations'],
      ['📋', 'Réservations', $stats['reservations_total'], 'info', BASE_URL.'admin/reservations'],
      ['⏳', 'En attente', $stats['reservations_attente'], 'danger', BASE_URL.'admin/reservations'],
      ['👥', 'Clients', $stats['clients_total'], 'secondary', BASE_URL.'admin/utilisateurs'],
      ['🗂️', 'Catégories', $stats['categories_total'] ?? 0, 'dark', BASE_URL.'admin/categories'],
    ];
    foreach ($cards as [$icon, $label, $val, $color, $link]): ?>
    <div class="col-6 col-md-4 col-xl-2">
      <a href="<?= $link ?>" class="text-decoration-none">
        <div class="card stat-card border-0 shadow-sm h-100 border-top border-4 border-<?= $color ?>">
          <div class="card-body text-center py-3">
            <div style="font-size:1.8rem;"><?= $icon ?></div>
            <div class="fs-4 fw-bold mt-1"><?= number_format($val) ?></div>
            <div class="text-muted small"><?= $label ?></div>
          </div>
        </div>
      </a>
</div>
