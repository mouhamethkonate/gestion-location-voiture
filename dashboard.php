<?php $pageTitle = 'Mon espace – CarLoc'; ?>
<div class="container py-4">
  <h2 class="fw-bold mb-1">Bonjour, <?= htmlspecialchars(explode(' ', $_SESSION['user_nom'])[0]) ?> 👋</h2>
  <p class="text-muted mb-4">Gérez vos réservations de véhicules</p>

  <!-- Stats rapides -->
  <div class="row g-3 mb-4">
    <?php
    $cs = [
      ['📋', 'Total', $stats['total'], 'primary'],
      ['⏳', 'En attente', $stats['attente'], 'warning'],
      ['✅', 'Confirmées', $stats['conf'], 'success'],
      ['🏁', 'Terminées', $stats['termines'], 'secondary'],
    ];
    foreach ($cs as [$icon, $label, $val, $color]): ?>
    <div class="col-6 col-md-3">
      <div class="card border-0 shadow-sm border-top border-3 border-<?= $color ?>">
        <div class="card-body text-center py-3">
          <div style="font-size:1.5rem;"><?= $icon ?></div>
          <div class="fs-4 fw-bold"><?= $val ?></div>
          <div class="text-muted small"><?= $label ?></div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0">Mes réservations</h5>
    <div class="d-flex gap-2">
      <a href="<?= BASE_URL ?>client/profil" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
        <i class="bi bi-person-gear me-1"></i>Mon profil
      </a>
      <a href="<?= BASE_URL ?>" class="btn btn-primary rounded-pill btn-sm px-3">
        <i class="bi bi-plus me-1"></i>Nouvelle réservation
      </a>
    </div>
  </div>

  <?php if (empty($reservations)): ?>
  <div class="text-center py-5 bg-body-secondary rounded-3">
    <div style="font-size:3rem;">🚗</div>
    <h5 class="mt-3">Aucune réservation</h5>
    <a href="<?= BASE_URL ?>" class="btn btn-primary mt-2">Voir les véhicules disponibles</a>
  </div>
  <?php else: ?>
  <div class="row g-3">
    <?php foreach ($reservations as $r): ?>
    <div class="col-md-6">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
              <h6 class="fw-bold mb-0"><?= htmlspecialchars($r['vehicule_nom']) ?></h6>
              <small class="text-muted"><?= htmlspecialchars($r['immatriculation']) ?></small>
            </div>
            <?php
              $badges = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'danger','terminee'=>'secondary'];
              $labels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'];
              echo '<span class="badge bg-'.$badges[$r['statut']].'">'.$labels[$r['statut']].'</span>';
            ?>
          </div>
          <div class="row g-2 small text-muted mt-1">
            <div class="col-6"><i class="bi bi-calendar me-1"></i><?= $r['date_debut'] ?></div>
            <div class="col-6"><i class="bi bi-calendar-x me-1"></i><?= $r['date_fin'] ?></div>
            <div class="col-6"><i class="bi bi-clock me-1"></i><?= $r['nb_jours'] ?> jour(s)</div>
            <div class="col-6 fw-semibold text-primary"><i class="bi bi-cash me-1"></i><?= number_format($r['montant_total'], 0, ',', ' ') ?> FCFA</div>
          </div>
          <div class="mt-3 d-flex gap-2 flex-wrap">
            <?php if ($r['statut'] === 'en_attente'): ?>
            <a href="<?= BASE_URL ?>paiement/choix/<?= $r['id'] ?>"
               class="btn btn-sm btn-success rounded-pill">
              <i class="bi bi-credit-card me-1"></i>Payer
            </a>
            <a href="<?= BASE_URL ?>reservation/annuler/<?= $r['id'] ?>"
               class="btn btn-sm btn-outline-danger rounded-pill"
               onclick="return confirm('Annuler cette réservation ?')">
              <i class="bi bi-x-circle me-1"></i>Annuler
            </a>
            <?php elseif ($r['statut'] === 'confirmee'): ?>
            <a href="<?= BASE_URL ?>paiement/facture/<?= $r['id_facture'] ?? '' ?>"
               class="btn btn-sm btn-outline-primary rounded-pill">
              <i class="bi bi-receipt me-1"></i>Facture
            </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>
