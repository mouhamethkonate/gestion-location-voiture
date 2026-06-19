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
    <?php endforeach; ?>
  </div>

  <!-- CA -->
  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card border-0 shadow-sm h-100 ca-card text-white">
        <div class="card-body d-flex align-items-center gap-3">
          <div style="font-size:2.5rem;">💵</div>
          <div>
            <div class="small opacity-75">Chiffre d'affaires confirmé</div>
            <div class="fs-4 fw-bold"><?= number_format($stats['chiffre_affaires'], 0, ',', ' ') ?> FCFA</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- RÉSERVATIONS RÉCENTES -->
  <div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
      <h5 class="fw-bold mb-0"><i class="bi bi-clock-history me-2 text-primary"></i>Réservations récentes</h5>
      <a href="<?= BASE_URL ?>admin/reservations" class="btn btn-sm btn-outline-primary rounded-pill">Voir tout</a>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>Référence</th><th>Client</th><th>Véhicule</th>
              <th>Période</th><th>Montant</th><th>Statut</th><th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations_recentes as $r): ?>
            <tr>
              <td><code class="text-primary"><?= htmlspecialchars($r['reference']) ?></code></td>
              <td><?= htmlspecialchars($r['client_nom']) ?></td>
              <td><?= htmlspecialchars($r['vehicule_nom']) ?></td>
              <td class="small text-muted"><?= $r['date_debut'] ?> → <?= $r['date_fin'] ?></td>
              <td class="fw-semibold"><?= number_format($r['montant_total'], 0, ',', ' ') ?> F</td>
              <td><?php
                $badges = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'danger','terminee'=>'secondary'];
                $labels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'];
                echo '<span class="badge bg-'.$badges[$r['statut']].'">'.$labels[$r['statut']].'</span>';
              ?></td>
              <td>
                <?php if ($r['statut'] === 'en_attente'): ?>
                <a href="<?= BASE_URL ?>admin/confirmerResa/<?= $r['id'] ?>" class="btn btn-xs btn-success btn-sm">
                  <i class="bi bi-check2"></i>
                </a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
