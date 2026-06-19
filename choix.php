<?php $pageTitle = 'Paiement – CarLoc'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-7">

      <!-- Breadcrumb -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb small">
          <li class="breadcrumb-item"><a href="<?= BASE_URL ?>">Accueil</a></li>
          <li class="breadcrumb-item"><a href="<?= BASE_URL ?>client/dashboard">Mes réservations</a></li>
          <li class="breadcrumb-item active">Paiement</li>
        </ol>
      </nav>

      <!-- Récapitulatif réservation -->
      <div class="card border-0 shadow-sm mb-4 rounded-4 overflow-hidden">
        <div class="card-header py-3" style="background:linear-gradient(135deg,#0f1923,#1a6bc8);">
          <div class="d-flex align-items-center gap-3">
            <i class="bi bi-receipt text-white fs-4"></i>
            <div>
              <h6 class="fw-bold text-white mb-0">Récapitulatif de la réservation</h6>
              <small class="text-white opacity-75">Réf. <?= htmlspecialchars($reservation['reference']) ?></small>
            </div>
          </div>
        </div>
        <div class="card-body p-4">
          <div class="row g-3">
            <div class="col-sm-6">
              <div class="recap-item p-3 rounded-3">
                <div class="small text-muted mb-1"><i class="bi bi-car-front me-1"></i>Véhicule</div>
                <div class="fw-semibold"><?= htmlspecialchars($reservation['vehicule_nom']) ?></div>
                <div class="small text-muted"><?= htmlspecialchars($reservation['immatriculation']) ?></div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="recap-item p-3 rounded-3">
                <div class="small text-muted mb-1"><i class="bi bi-calendar-range me-1"></i>Période</div>
                <div class="fw-semibold"><?= date('d/m/Y', strtotime($reservation['date_debut'])) ?> → <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></div>
                <div class="small text-muted"><?= $reservation['nb_jours'] ?> jour(s)</div>
              </div>
            </div>
          </div>

          <!-- Total -->
          <div class="mt-3 p-3 rounded-3 d-flex justify-content-between align-items-center" style="background:var(--bs-primary-bg-subtle, #D5E8F0);">
            <div>
              <div class="small text-muted">Montant total à payer</div>
              <div class="fs-3 fw-bold text-primary"><?= number_format($reservation['montant_total'], 0, ',', ' ') ?> <span class="fs-6">FCFA</span></div>
            </div>
            <i class="bi bi-cash-coin fs-2 text-primary opacity-50"></i>
          </div>
        </div>
      </div>

      <!-- Choix du mode de paiement -->
      <h5 class="fw-bold mb-3"><i class="bi bi-credit-card me-2 text-primary"></i>Choisissez votre mode de paiement</h5>

      <form method="POST" action="<?= BASE_URL ?>paiement/traiter/<?= $reservation['id'] ?>" id="payForm">
        <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">

        <div class="payment-options d-flex flex-column gap-3 mb-4">

          <!-- Wave -->
          <label class="pay-option" for="pay_wave">
            <input type="radio" name="mode_paiement" id="pay_wave" value="wave" class="pay-radio" required>
            <div class="pay-card d-flex align-items-center gap-3 p-4 rounded-4 border">
              <div class="pay-icon wave-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0">
                <span style="font-size:1.8rem;">🌊</span>
              </div>
              <div class="flex-grow-1">
                <div class="fw-bold">Wave</div>
                <div class="small text-muted">Paiement instantané par Wave Money</div>
              </div>
              <div class="pay-check"><i class="bi bi-check-circle-fill text-primary fs-5"></i></div>
            </div>
          </label>

          <!-- Orange Money -->
          <label class="pay-option" for="pay_orange">
            <input type="radio" name="mode_paiement" id="pay_orange" value="orange_money" class="pay-radio" required>
            <div class="pay-card d-flex align-items-center gap-3 p-4 rounded-4 border">
              <div class="pay-icon orange-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0">
                <span style="font-size:1.8rem;">🟠</span>
              </div>
              <div class="flex-grow-1">
                <div class="fw-bold">Orange Money</div>
                <div class="small text-muted">Paiement via Orange Money Sénégal</div>
              </div>
              <div class="pay-check"><i class="bi bi-check-circle-fill text-primary fs-5"></i></div>
            </div>
          </label>

          <!-- Espèces -->
          <label class="pay-option" for="pay_especes">
            <input type="radio" name="mode_paiement" id="pay_especes" value="especes" class="pay-radio" required>
            <div class="pay-card d-flex align-items-center gap-3 p-4 rounded-4 border">
              <div class="pay-icon cash-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0">
                <span style="font-size:1.8rem;">💵</span>
              </div>
              <div class="flex-grow-1">
                <div class="fw-bold">Espèces à l'agence</div>
                <div class="small text-muted">Paiement en espèces lors de la prise en charge</div>
              </div>
              <div class="pay-check"><i class="bi bi-check-circle-fill text-primary fs-5"></i></div>
            </div>
          </label>

          <!-- Virement -->
          <label class="pay-option" for="pay_virement">
            <input type="radio" name="mode_paiement" id="pay_virement" value="virement" class="pay-radio" required>
            <div class="pay-card d-flex align-items-center gap-3 p-4 rounded-4 border">
              <div class="pay-icon bank-icon d-flex align-items-center justify-content-center rounded-3 flex-shrink-0">
                <span style="font-size:1.8rem;">🏦</span>
              </div>
              <div class="flex-grow-1">
                <div class="fw-bold">Virement bancaire</div>
                <div class="small text-muted">Virement vers le compte de l'agence CarLoc</div>
              </div>
              <div class="pay-check"><i class="bi bi-check-circle-fill text-primary fs-5"></i></div>
            </div>
          </label>
        </div>

        <!-- Zone numéro de téléphone (Wave / Orange) -->
        <div id="phoneSection" class="d-none mb-4">
          <div class="p-4 rounded-4 border">
            <label class="form-label fw-semibold">Numéro de téléphone <span id="payLabel"></span></label>
            <div class="input-group">
              <span class="input-group-text"><i class="bi bi-phone"></i></span>
              <input type="tel" name="telephone_paiement" id="telephone_paiement" class="form-control"
                     placeholder="7X XXX XX XX" pattern="[0-9]{9,10}">
            </div>
            <div class="small text-muted mt-2">
              <i class="bi bi-info-circle me-1"></i>
              Vous recevrez une demande de paiement sur ce numéro.
            </div>
          </div>
        </div>

        <!-- Zone virement -->
        <div id="virementSection" class="d-none mb-4">
          <div class="p-4 rounded-4" style="background:var(--bs-warning-bg-subtle, #FFF3CD); border:1px solid #ffc107;">
            <h6 class="fw-bold"><i class="bi bi-bank me-2"></i>Coordonnées bancaires CarLoc</h6>
            <div class="small mt-2">
              <div><strong>Banque :</strong> CBAO Groupe Attijariwafa Bank</div>
              <div><strong>IBAN :</strong> SN28 0100 1234 5678 9012 3456 789</div>
              <div><strong>Bénéficiaire :</strong> CarLoc Saint-Louis SARL</div>
              <div class="mt-2 text-danger"><i class="bi bi-exclamation-triangle me-1"></i>Mentionner la référence <strong><?= htmlspecialchars($reservation['reference']) ?></strong> en motif.</div>
            </div>
          </div>
        </div>

        <!-- Info espèces -->
        <div id="especesSection" class="d-none mb-4">
          <div class="p-4 rounded-4" style="background:var(--bs-success-bg-subtle, #d4edda); border:1px solid #28a745;">
            <h6 class="fw-bold"><i class="bi bi-geo-alt me-2 text-success"></i>Adresse de l'agence</h6>
            <div class="small mt-2">
              <div><i class="bi bi-pin-map me-1"></i>Ngallel, Saint-Louis/Ndar, Sénégal</div>
              <div><i class="bi bi-telephone me-1"></i>+221 77 000 00 00</div>
              <div><i class="bi bi-clock me-1"></i>Lun–Sam : 8h00–19h00 · Dim : 9h00–14h00</div>
              <div class="mt-2 text-success"><i class="bi bi-check-circle me-1"></i>Votre réservation est sécurisée. Paiement à la prise en charge.</div>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 rounded-pill fw-semibold" id="submitBtn" disabled>
          <i class="bi bi-lock-fill me-2"></i>Confirmer le paiement
        </button>

        <p class="text-center text-muted small mt-3">
          <i class="bi bi-shield-check me-1 text-success"></i>
          Votre réservation est sécurisée. Vous recevrez une confirmation par email.
        </p>
      </form>

    </div>
  </div>
</div>

<style>
.recap-item { background: var(--bs-body-secondary); }
.pay-option { cursor: pointer; }
.pay-option .pay-radio { display: none; }
.pay-option .pay-check { display: none; }
.pay-option .pay-card {
  transition: all 0.2s;
  background: var(--bs-body-bg);
  border-color: var(--bs-border-color) !important;
}
.pay-option:hover .pay-card { border-color: var(--bs-primary) !important; background: var(--bs-primary-bg-subtle, #D5E8F0); }
.pay-option .pay-radio:checked ~ .pay-card {
  border-color: var(--bs-primary) !important;
  background: var(--bs-primary-bg-subtle, #D5E8F0);
  box-shadow: 0 0 0 3px rgba(26,107,200,0.15);
}
.pay-option .pay-radio:checked ~ .pay-card .pay-check { display: block; }
.wave-icon  { background: #e8f4ff; width:52px; height:52px; }
.orange-icon{ background: #fff3e0; width:52px; height:52px; }
.cash-icon  { background: #e8f5e9; width:52px; height:52px; }
.bank-icon  { background: #f3e5f5; width:52px; height:52px; }
</style>

<script>
const radios = document.querySelectorAll('.pay-radio');
const phoneSection    = document.getElementById('phoneSection');
const virementSection = document.getElementById('virementSection');
const especesSection  = document.getElementById('especesSection');
const submitBtn       = document.getElementById('submitBtn');
const payLabel        = document.getElementById('payLabel');

radios.forEach(r => {
  r.addEventListener('change', () => {
    phoneSection.classList.add('d-none');
    virementSection.classList.add('d-none');
    especesSection.classList.add('d-none');
    submitBtn.disabled = false;

    if (r.value === 'wave') {
      phoneSection.classList.remove('d-none');
      payLabel.textContent = '(Wave)';
    } else if (r.value === 'orange_money') {
      phoneSection.classList.remove('d-none');
      payLabel.textContent = '(Orange Money)';
    } else if (r.value === 'virement') {
      virementSection.classList.remove('d-none');
    } else if (r.value === 'especes') {
      especesSection.classList.remove('d-none');
    }
  });
});
</script>
