<?php $pageTitle = 'Facture – CarLoc'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Facture</h2>
    <button onclick="window.print()" class="btn btn-primary rounded-pill px-4">
      <i class="bi bi-printer me-2"></i>Imprimer
    </button>
  </div>
  <div class="card border-0 shadow-sm p-4" id="facture-print">
    <!-- En-tête -->
    <div class="row mb-4">
      <div class="col-6">
        <h4 class="fw-bold text-primary">CarLoc</h4>
        <p class="mb-0 small text-muted">Ngallel, Saint-Louis/Ndar, Sénégal</p>
        <p class="mb-0 small text-muted">+221 77 000 00 00</p>
        <p class="mb-0 small text-muted">contact@carloc.sn</p>
      </div>
      <div class="col-6 text-end">
        <h5 class="fw-bold">FACTURE</h5>
        <p class="mb-0 small">N° <?= htmlspecialchars($facture['numero']) ?></p>
        <p class="mb-0 small text-muted">Date : <?= htmlspecialchars($facture['date_emission']) ?></p>
        <span class="badge bg-success">Payée</span>
      </div>
    </div>
    <hr>
    <!-- Client -->
    <div class="mb-4">
      <h6 class="fw-bold">Facturé à :</h6>
      <p class="mb-0"><?= htmlspecialchars($facture['client_nom'] . ' ' . $facture['client_prenom']) ?></p>
      <p class="mb-0 text-muted small"><?= htmlspecialchars($facture['client_email']) ?></p>
      <p class="mb-0 text-muted small"><?= htmlspecialchars($facture['client_telephone'] ?? '') ?></p>
    </div>
    <!-- Détails -->
    <table class="table table-bordered mb-4">
      <thead class="table-primary">
        <tr>
          <th>Description</th>
          <th class="text-center">Période</th>
          <th class="text-center">Durée</th>
          <th class="text-end">Montant</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Location <?= htmlspecialchars($facture['vehicule_nom']) ?><br>
            <small class="text-muted"><?= htmlspecialchars($facture['immatriculation']) ?></small>
          </td>
          <td class="text-center small"><?= $facture['date_debut'] ?> → <?= $facture['date_fin'] ?></td>
          <td class="text-center"><?= $facture['nb_jours'] ?> jour(s)</td>
          <td class="text-end fw-semibold"><?= number_format($facture['montant_ht'], 0, ',', ' ') ?> FCFA</td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="3" class="text-end">Montant HT</td>
          <td class="text-end"><?= number_format($facture['montant_ht'], 0, ',', ' ') ?> FCFA</td>
        </tr>
        <tr>
          <td colspan="3" class="text-end">TVA (18%)</td>
          <td class="text-end"><?= number_format($facture['tva'], 0, ',', ' ') ?> FCFA</td>
        </tr>
        <tr class="table-primary fw-bold">
          <td colspan="3" class="text-end">Total TTC</td>
          <td class="text-end"><?= number_format($facture['montant_ttc'], 0, ',', ' ') ?> FCFA</td>
        </tr>
      </tfoot>
    </table>
    <!-- Paiement -->
    <div class="row">
      <div class="col-md-6">
        <p class="small text-muted mb-1">Mode de paiement : <strong><?= htmlspecialchars($facture['mode_paiement']) ?></strong></p>
        <p class="small text-muted">Référence réservation : <strong><?= htmlspecialchars($facture['reference']) ?></strong></p>
      </div>
      <div class="col-md-6 text-end">
        <p class="small text-muted">Merci de votre confiance !</p>
        <p class="small text-muted">CarLoc — Saint-Louis/Ndar</p>
      </div>
    </div>
  </div>
  <div class="text-center mt-4">
    <a href="<?= BASE_URL ?>client/dashboard" class="btn btn-outline-primary rounded-pill px-4">
      <i class="bi bi-arrow-left me-2"></i>Retour à mon espace
    </a>
  </div>
</div>
