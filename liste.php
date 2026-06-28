<?php $pageTitle = 'Mes réservations – CarLoc'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0"><i class="bi bi-calendar-check me-2 text-primary"></i>Mes réservations</h3>
    <a href="<?= BASE_URL ?>" class="btn btn-primary rounded-pill btn-sm px-3">
      <i class="bi bi-plus me-1"></i>Nouvelle réservation
    </a>
  </div>

  <?php if (empty($reservations)): ?>
  <div class="text-center py-5 bg-body-secondary rounded-4">
    <div style="font-size:3rem;">📋</div>
    <h5 class="mt-3 fw-semibold">Aucune réservation</h5>
    <p class="text-muted">Vous n'avez pas encore effectué de réservation.</p>
    <a href="<?= BASE_URL ?>" class="btn btn-primary rounded-pill px-4 mt-2">
      <i class="bi bi-car-front me-2"></i>Voir les véhicules disponibles
    </a>
  </div>
  <?php else: ?>
  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>Référence</th><th>Véhicule</th><th>Date début</th>
              <th>Date fin</th><th>Jours</th><th>Montant</th><th>Statut</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $r):
              $badges = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'danger','terminee'=>'secondary'];
              $labels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'];
            ?>
            <tr>
              <td><code class="text-primary"><?= htmlspecialchars($r['reference']) ?></code></td>
              <td>
                <div class="fw-semibold small"><?= htmlspecialchars($r['vehicule_nom']) ?></div>
                <div class="text-muted" style="font-size:0.75rem;"><?= htmlspecialchars($r['immatriculation']) ?></div>
              </td>
              <td class="small"><?= date('d/m/Y', strtotime($r['date_debut'])) ?></td>
              <td class="small"><?= date('d/m/Y', strtotime($r['date_fin'])) ?></td>
              <td class="text-center"><?= $r['nb_jours'] ?></td>
              <td class="fw-semibold text-primary small"><?= number_format($r['montant_total'],0,',',' ') ?> F</td>
              <td><span class="badge bg-<?= $badges[$r['statut']] ?>"><?= $labels[$r['statut']] ?></span></td>
              <td>
                <div class="d-flex gap-1">
                  <?php if ($r['statut'] === 'en_attente'): ?>
                  <a href="<?= BASE_URL ?>paiement/choix/<?= $r['id'] ?>" class="btn btn-success btn-sm rounded-pill">
                    <i class="bi bi-credit-card"></i>
                  </a>
                  <a href="<?= BASE_URL ?>reservation/annuler/<?= $r['id'] ?>"
                     class="btn btn-outline-danger btn-sm rounded-pill"
                     onclick="return confirm('Annuler cette réservation ?')">
                    <i class="bi bi-x"></i>
                  </a>
                  <?php elseif ($r['statut'] === 'confirmee'): ?>
                  <a href="<?= BASE_URL ?>client/dashboard" class="btn btn-outline-primary btn-sm rounded-pill">
                    <i class="bi bi-receipt"></i>
                  </a>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>
