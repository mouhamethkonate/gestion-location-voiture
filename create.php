<?php $pageTitle = 'Réserver – ' . $vehicule['marque'] . ' ' . $vehicule['modele']; ?>
<div class="container py-5">
  <div class="row g-4 justify-content-center">

    <!-- INFOS VÉHICULE -->
    <div class="col-lg-5">
      <div class="card border-0 shadow-sm sticky-top" style="top:80px;">
        <img src="<?= BASE_URL ?>img/vehicules/<?= htmlspecialchars($vehicule['image']) ?>"
             onerror="this.src='<?= BASE_URL ?>img/vehicules/default.svg'"
             class="card-img-top" style="height:220px;object-fit:cover;" alt="">
        <div class="card-body">
          <h4 class="fw-bold mb-1"><?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?></h4>
          <p class="text-muted small mb-3"><?= htmlspecialchars($vehicule['categorie_nom'] ?? '') ?></p>
          <div class="row g-2 small mb-3">
            <div class="col-6"><i class="bi bi-calendar2 text-primary me-1"></i><?= $vehicule['annee'] ?></div>
            <div class="col-6"><i class="bi bi-people text-primary me-1"></i><?= $vehicule['places'] ?> places</div>
            <div class="col-6"><i class="bi bi-paint-bucket text-primary me-1"></i><?= htmlspecialchars($vehicule['couleur']) ?></div>
            <div class="col-6"><i class="bi bi-tag text-primary me-1"></i><?= htmlspecialchars($vehicule['immatriculation']) ?></div>
          </div>
          <div class="alert alert-primary mb-0 py-2 text-center">
            <span class="fs-5 fw-bold"><?= number_format($vehicule['prix_par_jour'], 0, ',', ' ') ?> FCFA</span>
            <span class="small"> / jour</span>
          </div>
        </div>
      </div>
    </div>

    <!-- FORMULAIRE -->
    <div class="col-lg-6">
      <div class="d-flex align-items-center gap-3 mb-4">
        <a href="<?= BASE_URL ?>" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-arrow-left"></i></a>
        <h3 class="fw-bold mb-0">Formulaire de réservation</h3>
      </div>

      <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
      </div>
      <?php endif; ?>

      <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
          <form method="POST" id="resaForm">
            <div class="mb-3">
              <label class="form-label fw-semibold">Date de début *</label>
              <input type="date" name="date_debut" id="dateDebut" class="form-control"
                     min="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold">Date de fin *</label>
              <input type="date" name="date_fin" id="dateFin" class="form-control"
                     min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
            </div>

            <!-- Récapitulatif dynamique -->
            <div id="recap" class="d-none alert alert-success mb-3">
              <div class="d-flex justify-content-between">
                <span>Durée :</span><strong id="recapJours">–</strong>
              </div>
              <div class="d-flex justify-content-between">
                <span>Prix/jour :</span><strong><?= number_format($vehicule['prix_par_jour'], 0, ',', ' ') ?> FCFA</strong>
              </div>
              <hr class="my-2">
              <div class="d-flex justify-content-between fs-5">
                <span class="fw-bold">Total estimé :</span><strong class="text-success" id="recapTotal">–</strong>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold">Notes / Demandes particulières</label>
              <textarea name="notes" class="form-control" rows="3" placeholder="Ex : livraison à l'aéroport..."></textarea>
            </div>

            <div class="p-3 bg-body-secondary rounded-3 small mb-4">
              <i class="bi bi-info-circle text-primary me-1"></i>
              Paiement à effectuer à la prise en charge. Nous acceptons <strong>Wave</strong>, <strong>Orange Money</strong> et espèces.
            </div>

            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
              <i class="bi bi-calendar-check me-2"></i>Confirmer la réservation
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const prixJour = <?= $vehicule['prix_par_jour'] ?>;
const debut = document.getElementById('dateDebut');
const fin   = document.getElementById('dateFin');
const recap = document.getElementById('recap');

function calcul() {
  if (!debut.value || !fin.value) return;
  const d1 = new Date(debut.value), d2 = new Date(fin.value);
  const jours = Math.ceil((d2 - d1) / 86400000);
  if (jours <= 0) { recap.classList.add('d-none'); return; }
  recap.classList.remove('d-none');
  document.getElementById('recapJours').textContent = jours + ' jour(s)';
  document.getElementById('recapTotal').textContent = new Intl.NumberFormat('fr-FR').format(jours * prixJour) + ' FCFA';
  fin.min = debut.value;
}
debut.addEventListener('change', calcul);
fin.addEventListener('change', calcul);
</script>
