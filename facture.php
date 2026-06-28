<?php $pageTitle = 'Facture ' . $facture['numero'] . ' – CarLoc'; ?>

<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-lg-8">

      <!-- Boutons d'action (cachés à l'impression) -->
      <div class="d-flex gap-2 mb-4 no-print">
        <button onclick="window.print()" class="btn btn-primary rounded-pill px-4">
          <i class="bi bi-printer me-2"></i>Imprimer / Sauvegarder PDF
        </button>
        <a href="<?= BASE_URL ?>client/dashboard" class="btn btn-outline-secondary rounded-pill px-4">
          <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
      </div>

      <!-- Facture -->
      <div class="facture-wrapper p-5 rounded-4 shadow" id="facture">

        <!-- En-tête -->
        <div class="row align-items-center mb-5">
          <div class="col-7">
            <h1 class="fw-bold" style="color:#1A6BC8; font-size:2rem;">Car<span style="color:#0F1923;">Loc</span></h1>
            <div class="small text-muted mt-1">
              <div><i class="bi bi-geo-alt me-1"></i>Ngallel, Saint-Louis/Ndar, Sénégal</div>
              <div><i class="bi bi-telephone me-1"></i>+221 77 000 00 00</div>
              <div><i class="bi bi-envelope me-1"></i>contact@carloc.sn</div>
            </div>
          </div>
          <div class="col-5 text-end">
            <h2 class="fw-bold text-uppercase mb-1" style="letter-spacing:0.1em; color:#E85C30;">FACTURE</h2>
            <div class="fw-bold text-primary fs-5"><?= htmlspecialchars($facture['numero']) ?></div>
            <div class="small text-muted">Date : <?= date('d/m/Y', strtotime($facture['date_emission'])) ?></div>
            <div class="mt-2">
              <?php if ($facture['statut_paiement'] === 'paye'): ?>
              <span class="badge bg-success px-3 py-2 fs-6">✅ PAYÉE</span>
              <?php else: ?>
              <span class="badge bg-warning text-dark px-3 py-2 fs-6">⏳ EN ATTENTE</span>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <hr style="border-color:#1A6BC8; border-width:2px;">

        <!-- Client + Réservation -->
        <div class="row g-4 mb-4">
          <div class="col-sm-6">
            <div class="p-3 rounded-3" style="background:#f8f9fa; border-left:4px solid #1A6BC8;">
              <div class="small fw-bold text-muted text-uppercase mb-2">Facturé à</div>
              <div class="fw-bold"><?= htmlspecialchars($facture['client_nom']) ?></div>
              <div class="small text-muted"><?= htmlspecialchars($facture['client_email']) ?></div>
              <div class="small text-muted"><?= htmlspecialchars($facture['client_tel'] ?? '') ?></div>
              <?php if (!empty($facture['client_adresse'])): ?>
              <div class="small text-muted"><?= htmlspecialchars($facture['client_adresse']) ?></div>
              <?php endif; ?>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="p-3 rounded-3" style="background:#f8f9fa; border-left:4px solid #E85C30;">
              <div class="small fw-bold text-muted text-uppercase mb-2">Réservation</div>
              <div class="fw-bold"><?= htmlspecialchars($facture['reference']) ?></div>
              <div class="small text-muted">Du <?= date('d/m/Y', strtotime($facture['date_debut'])) ?></div>
              <div class="small text-muted">Au <?= date('d/m/Y', strtotime($facture['date_fin'])) ?></div>
              <div class="small text-muted"><?= $facture['nb_jours'] ?> jour(s)</div>
            </div>
          </div>
        </div>

        <!-- Tableau des prestations -->
        <table class="table mb-4" style="border-collapse:collapse;">
          <thead>
            <tr style="background:#1A6BC8; color:white;">
              <th class="p-3 rounded-start">Description</th>
              <th class="p-3 text-center">Durée</th>
              <th class="p-3 text-end">Prix/jour</th>
              <th class="p-3 text-end rounded-end">Total HT</th>
            </tr>
          </thead>
          <tbody>
            <tr style="background:#f8f9fa;">
              <td class="p-3">
                <div class="fw-semibold">Location de véhicule</div>
                <div class="small text-muted"><?= htmlspecialchars($facture['vehicule_nom']) ?> — <?= htmlspecialchars($facture['immatriculation']) ?></div>
              </td>
              <td class="p-3 text-center"><?= $facture['nb_jours'] ?> jour(s)</td>
              <td class="p-3 text-end"><?= number_format($facture['montant_ht'] / $facture['nb_jours'], 0, ',', ' ') ?> FCFA</td>
              <td class="p-3 text-end fw-semibold"><?= number_format($facture['montant_ht'], 0, ',', ' ') ?> FCFA</td>
            </tr>
          </tbody>
        </table>

        <!-- Totaux -->
        <div class="row justify-content-end">
          <div class="col-sm-6">
            <table class="table table-borderless small">
              <tr>
                <td class="text-muted">Montant HT</td>
                <td class="text-end fw-semibold"><?= number_format($facture['montant_ht'], 0, ',', ' ') ?> FCFA</td>
              </tr>
              <tr>
                <td class="text-muted">TVA (18%)</td>
                <td class="text-end fw-semibold"><?= number_format($facture['tva'], 0, ',', ' ') ?> FCFA</td>
              </tr>
              <tr style="background:#1A6BC8; color:white; border-radius:6px;">
                <td class="p-2 ps-3 fw-bold rounded-start">TOTAL TTC</td>
                <td class="p-2 pe-3 fw-bold text-end rounded-end fs-5"><?= number_format($facture['montant_ttc'], 0, ',', ' ') ?> FCFA</td>
              </tr>
            </table>
          </div>
        </div>

        <!-- Mode de paiement -->
        <?php if (!empty($facture['mode_paiement'])): ?>
        <div class="mt-3 p-3 rounded-3 small" style="background:#e8f4ff; border:1px solid #1A6BC820;">
          <i class="bi bi-credit-card me-2 text-primary"></i>
          <strong>Mode de paiement :</strong>
          <?php
          $modes = ['wave'=>'Wave','orange_money'=>'Orange Money','especes'=>'Espèces à l\'agence','virement'=>'Virement bancaire'];
          echo htmlspecialchars($modes[$facture['mode_paiement']] ?? $facture['mode_paiement']);
          ?>
        </div>
        <?php endif; ?>

        <!-- Pied de facture -->
        <hr class="mt-4" style="border-color:#eee;">
        <div class="row text-center small text-muted mt-3">
          <div class="col-4"><i class="bi bi-telephone me-1"></i>+221 77 000 00 00</div>
          <div class="col-4"><i class="bi bi-envelope me-1"></i>contact@carloc.sn</div>
          <div class="col-4"><i class="bi bi-geo-alt me-1"></i>Saint-Louis/Ndar, Sénégal</div>
        </div>
        <p class="text-center small text-muted mt-2 mb-0">Merci de votre confiance — CarLoc</p>
      </div>

    </div>
  </div>
</div>

<style>
.facture-wrapper {
  background: white;
  border: 1px solid #e0e0e0;
}
[data-bs-theme="dark"] .facture-wrapper {
  background: #1a1a1a;
  border-color: #333;
}
[data-bs-theme="dark"] .facture-wrapper td,
[data-bs-theme="dark"] .facture-wrapper th { color: inherit; }

@media print {
  .no-print, header, footer, nav { display: none !important; }
  .facture-wrapper { box-shadow: none !important; border: none !important; }
  body { background: white !important; }
}
</style>
