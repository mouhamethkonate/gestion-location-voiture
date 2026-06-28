<?php $pageTitle = 'Réservation confirmée – CarLoc'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7 text-center">

      <!-- Icône succès animée -->
      <div class="success-icon mb-4">
        <div class="success-circle">
          <i class="bi bi-check-lg text-white"></i>
        </div>
      </div>

      <h2 class="fw-bold mb-2">Réservation confirmée !</h2>
      <p class="text-muted mb-4">Votre réservation a été enregistrée avec succès. Un email de confirmation vous a été envoyé.</p>

      <!-- Carte récapitulatif -->
      <div class="card border-0 shadow-sm rounded-4 mb-4 text-start">
        <div class="card-header py-3 d-flex justify-content-between align-items-center" style="background:linear-gradient(135deg,#0f1923,#1a6bc8);">
          <div>
            <h6 class="text-white fw-bold mb-0"><i class="bi bi-receipt me-2"></i>Détails de la réservation</h6>
          </div>
          <span class="badge bg-success px-3 py-2">✅ Confirmée</span>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            <div class="col-sm-6">
              <?php foreach ([
                ['bi-hash',         'Référence',    $reservation['reference']],
                ['bi-car-front',    'Véhicule',     $reservation['vehicule_nom']],
                ['bi-calendar',     'Date début',   date('d/m/Y', strtotime($reservation['date_debut']))],
                ['bi-calendar-x',   'Date fin',     date('d/m/Y', strtotime($reservation['date_fin']))],
              ] as [$icon, $label, $val]): ?>
              <div class="d-flex gap-2 mb-2">
                <i class="bi <?= $icon ?> text-primary mt-1 flex-shrink-0"></i>
                <div>
                  <div class="small text-muted"><?= $label ?></div>
                  <div class="fw-semibold small"><?= htmlspecialchars($val) ?></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
            <div class="col-sm-6">
              <?php
              $modePaiement = ['wave'=>'Wave','orange_money'=>'Orange Money','especes'=>'Espèces à l\'agence','virement'=>'Virement bancaire'];
              foreach ([
                ['bi-clock',        'Durée',        $reservation['nb_jours'] . ' jour(s)'],
                ['bi-credit-card',  'Paiement',     $modePaiement[$facture['mode_paiement'] ?? 'especes'] ?? 'Espèces'],
                ['bi-tag',          'Réf. facture', $facture['numero']],
                ['bi-cash',         'Montant total', number_format($reservation['montant_total'],0,',',' ') . ' FCFA'],
              ] as [$icon, $label, $val]): ?>
              <div class="d-flex gap-2 mb-2">
                <i class="bi <?= $icon ?> text-primary mt-1 flex-shrink-0"></i>
                <div>
                  <div class="small text-muted"><?= $label ?></div>
                  <div class="fw-semibold small"><?= htmlspecialchars($val) ?></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Montant mis en valeur -->
          <div class="mt-3 p-3 rounded-3 d-flex justify-content-between align-items-center" style="background:var(--bs-success-bg-subtle, #d4edda);">
            <div>
              <div class="small text-muted">Montant total payé</div>
              <div class="fs-3 fw-bold text-success"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> <span class="fs-6">FCFA</span></div>
            </div>
            <i class="bi bi-check-circle-fill text-success fs-2"></i>
          </div>
        </div>
      </div>

      <!-- Instructions selon mode de paiement -->
      <?php if (($facture['mode_paiement'] ?? '') === 'wave'): ?>
      <div class="alert alert-info text-start mb-4">
        <h6 class="fw-bold"><i class="bi bi-phone me-2"></i>Paiement Wave</h6>
        <p class="small mb-0">Une demande de paiement Wave a été initiée vers votre numéro. Veuillez valider la transaction dans votre application Wave.</p>
      </div>
      <?php elseif (($facture['mode_paiement'] ?? '') === 'orange_money'): ?>
      <div class="alert alert-warning text-start mb-4">
        <h6 class="fw-bold"><i class="bi bi-phone me-2"></i>Paiement Orange Money</h6>
        <p class="small mb-0">Composez <strong>#144#</strong> sur votre téléphone Orange pour valider le paiement, ou utilisez l'application Orange Money.</p>
      </div>
      <?php elseif (($facture['mode_paiement'] ?? '') === 'especes'): ?>
      <div class="alert alert-success text-start mb-4">
        <h6 class="fw-bold"><i class="bi bi-geo-alt me-2"></i>Paiement en espèces</h6>
        <p class="small mb-0">Présentez-vous à notre agence (Ngallel, Saint-Louis/Ndar) avec votre référence <strong><?= htmlspecialchars($reservation['reference']) ?></strong> pour finaliser le paiement.</p>
      </div>
      <?php endif; ?>

      <!-- Actions -->
      <div class="d-flex flex-wrap gap-3 justify-content-center">
        <a href="<?= BASE_URL ?>paiement/facture/<?= $facture['id'] ?>" class="btn btn-primary rounded-pill px-4">
          <i class="bi bi-download me-2"></i>Télécharger la facture
        </a>
        <a href="<?= BASE_URL ?>client/dashboard" class="btn btn-outline-primary rounded-pill px-4">
          <i class="bi bi-person-circle me-2"></i>Mes réservations
        </a>
        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary rounded-pill px-4">
          <i class="bi bi-house me-2"></i>Accueil
        </a>
      </div>

    </div>
  </div>
</div>

<style>
.success-circle {
  width: 90px; height: 90px; border-radius: 50%;
  background: linear-gradient(135deg, #28a745, #20c997);
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto;
  font-size: 2.5rem;
  box-shadow: 0 8px 32px rgba(40,167,69,0.35);
  animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
@keyframes popIn {
  0%   { transform: scale(0); opacity: 0; }
  100% { transform: scale(1); opacity: 1; }
}
</style>
