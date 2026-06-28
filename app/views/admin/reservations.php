<<?php $pageTitle = 'Réservations – CarLoc Admin'; ?>
<div class="container-fluid py-4 px-4">
  <h2 class="fw-bold mb-4"><i class="bi bi-calendar-check me-2 text-primary"></i>Gestion des réservations</h2>

  <!-- Filtres -->
<form method="GET" action="<?= BASE_URL ?>admin/reservations" class="card border-0 shadow-sm mb-4">
  <div class="card-body py-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label fw-semibold small mb-1">Rechercher</label>
        <input type="text" name="q" class="form-control form-control-sm"
               placeholder="Nom client, véhicule, référence..."
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small mb-1">Statut</label>
        <select name="statut" class="form-select form-select-sm">
          <option value="">Tous</option>
          <?php foreach (['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'] as $v=>$l): ?>
          <option value="<?= $v ?>" <?= ($_GET['statut']??'')===$v?'selected':'' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small mb-1">Date début</label>
        <input type="date" name="date_debut" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 w-100">
          <i class="bi bi-search me-1"></i>Filtrer
        </button>
      </div>
      <div class="col-md-1">
        <a href="<?= BASE_URL ?>admin/reservations" class="btn btn-outline-secondary btn-sm rounded-pill w-100">
          <i class="bi bi-x-lg"></i>
        </a>
      </div>
    </div>
  </div>
</form>

<?php
// Filtrage côté PHP
$q = strtolower($_GET['q'] ?? '');
$fStatut = $_GET['statut'] ?? '';
$fDate   = $_GET['date_debut'] ?? '';
if ($q || $fStatut || $fDate) {
    $reservations = array_filter($reservations, function($r) use ($q, $fStatut, $fDate) {
        $match = true;
        if ($q) $match = $match && (
            str_contains(strtolower($r['client_nom']), $q) ||
            str_contains(strtolower($r['vehicule_nom']), $q) ||
            str_contains(strtolower($r['reference']), $q)
        );
        if ($fStatut) $match = $match && $r['statut'] === $fStatut;
        if ($fDate)   $match = $match && $r['date_debut'] >= $fDate;
        return $match;
    });
}
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle small">
          <thead class="table-light">
            <tr>
              <th>Référence</th><th>Client</th><th>Tél.</th>
              <th>Véhicule</th><th>Date début</th><th>Date fin</th>
              <th>Jours</th><th>Montant</th><th>Statut</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $r): ?>
            <tr>
              <td><code class="text-primary"><?= htmlspecialchars($r['reference']) ?></code></td>
              <td class="fw-semibold"><?= htmlspecialchars($r['client_nom']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($r['client_tel'] ?? '–') ?></td>
              <td><?= htmlspecialchars($r['vehicule_nom']) ?><br><small class="text-muted"><?= $r['immatriculation'] ?></small></td>
              <td><?= $r['date_debut'] ?></td>
              <td><?= $r['date_fin'] ?></td>
              <td class="text-center"><?= $r['nb_jours'] ?></td>
              <td class="fw-semibold text-primary"><?= number_format($r['montant_total'], 0, ',', ' ') ?> F</td>
              <td><?php
                $badges = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'danger','terminee'=>'secondary'];
                $labels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'];
                echo '<span class="badge bg-'.$badges[$r['statut']].'">'.$labels[$r['statut']].'</span>';
              ?></td>
              <td>
                <div class="d-flex gap-1">
                  <?php if ($r['statut'] === 'en_attente'): ?>
                  <a href="<?= BASE_URL ?>admin/confirmerResa/<?= $r['id'] ?>" class="btn btn-success btn-sm" title="Confirmer">
                    <i class="bi bi-check2-circle"></i>
                  </a>
                  <?php elseif ($r['statut'] === 'confirmee'): ?>
                  <a href="<?= BASE_URL ?>admin/terminerResa/<?= $r['id'] ?>" class="btn btn-secondary btn-sm" title="Retour véhicule">
                    <i class="bi bi-flag-fill"></i>
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
</div>
<<?php $pageTitle = 'Réservations – CarLoc Admin'; ?>
<div class="container-fluid py-4 px-4">
  <h2 class="fw-bold mb-4"><i class="bi bi-calendar-check me-2 text-primary"></i>Gestion des réservations</h2>

  <!-- Filtres -->
<form method="GET" action="<?= BASE_URL ?>admin/reservations" class="card border-0 shadow-sm mb-4">
  <div class="card-body py-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label fw-semibold small mb-1">Rechercher</label>
        <input type="text" name="q" class="form-control form-control-sm"
               placeholder="Nom client, véhicule, référence..."
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small mb-1">Statut</label>
        <select name="statut" class="form-select form-select-sm">
          <option value="">Tous</option>
          <?php foreach (['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'] as $v=>$l): ?>
          <option value="<?= $v ?>" <?= ($_GET['statut']??'')===$v?'selected':'' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small mb-1">Date début</label>
        <input type="date" name="date_debut" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 w-100">
          <i class="bi bi-search me-1"></i>Filtrer
        </button>
      </div>
      <div class="col-md-1">
        <a href="<?= BASE_URL ?>admin/reservations" class="btn btn-outline-secondary btn-sm rounded-pill w-100">
          <i class="bi bi-x-lg"></i>
        </a>
      </div>
    </div>
  </div>
</form>

<?php
// Filtrage côté PHP
$q = strtolower($_GET['q'] ?? '');
$fStatut = $_GET['statut'] ?? '';
$fDate   = $_GET['date_debut'] ?? '';
if ($q || $fStatut || $fDate) {
    $reservations = array_filter($reservations, function($r) use ($q, $fStatut, $fDate) {
        $match = true;
        if ($q) $match = $match && (
            str_contains(strtolower($r['client_nom']), $q) ||
            str_contains(strtolower($r['vehicule_nom']), $q) ||
            str_contains(strtolower($r['reference']), $q)
        );
        if ($fStatut) $match = $match && $r['statut'] === $fStatut;
        if ($fDate)   $match = $match && $r['date_debut'] >= $fDate;
        return $match;
    });
}
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle small">
          <thead class="table-light">
            <tr>
              <th>Référence</th><th>Client</th><th>Tél.</th>
              <th>Véhicule</th><th>Date début</th><th>Date fin</th>
              <th>Jours</th><th>Montant</th><th>Statut</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $r): ?>
            <tr>
              <td><code class="text-primary"><?= htmlspecialchars($r['reference']) ?></code></td>
              <td class="fw-semibold"><?= htmlspecialchars($r['client_nom']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($r['client_tel'] ?? '–') ?></td>
              <td><?= htmlspecialchars($r['vehicule_nom']) ?><br><small class="text-muted"><?= $r['immatriculation'] ?></small></td>
              <td><?= $r['date_debut'] ?></td>
              <td><?= $r['date_fin'] ?></td>
              <td class="text-center"><?= $r['nb_jours'] ?></td>
              <td class="fw-semibold text-primary"><?= number_format($r['montant_total'], 0, ',', ' ') ?> F</td>
              <td><?php
                $badges = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'danger','terminee'=>'secondary'];
                $labels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'];
                echo '<span class="badge bg-'.$badges[$r['statut']].'">'.$labels[$r['statut']].'</span>';
              ?></td>
              <td>
                <div class="d-flex gap-1">
                  <?php if ($r['statut'] === 'en_attente'): ?>
                  <a href="<?= BASE_URL ?>admin/confirmerResa/<?= $r['id'] ?>" class="btn btn-success btn-sm" title="Confirmer">
                    <i class="bi bi-check2-circle"></i>
                  </a>
                  <?php elseif ($r['statut'] === 'confirmee'): ?>
                  <a href="<?= BASE_URL ?>admin/terminerResa/<?= $r['id'] ?>" class="btn btn-secondary btn-sm" title="Retour véhicule">
                    <i class="bi bi-flag-fill"></i>
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
</div>
<<?php $pageTitle = 'Réservations – CarLoc Admin'; ?>
<div class="container-fluid py-4 px-4">
  <h2 class="fw-bold mb-4"><i class="bi bi-calendar-check me-2 text-primary"></i>Gestion des réservations</h2>

  <!-- Filtres -->
<form method="GET" action="<?= BASE_URL ?>admin/reservations" class="card border-0 shadow-sm mb-4">
  <div class="card-body py-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label fw-semibold small mb-1">Rechercher</label>
        <input type="text" name="q" class="form-control form-control-sm"
               placeholder="Nom client, véhicule, référence..."
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small mb-1">Statut</label>
        <select name="statut" class="form-select form-select-sm">
          <option value="">Tous</option>
          <?php foreach (['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'] as $v=>$l): ?>
          <option value="<?= $v ?>" <?= ($_GET['statut']??'')===$v?'selected':'' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small mb-1">Date début</label>
        <input type="date" name="date_debut" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 w-100">
          <i class="bi bi-search me-1"></i>Filtrer
        </button>
      </div>
      <div class="col-md-1">
        <a href="<?= BASE_URL ?>admin/reservations" class="btn btn-outline-secondary btn-sm rounded-pill w-100">
          <i class="bi bi-x-lg"></i>
        </a>
      </div>
    </div>
  </div>
</form>

<?php
// Filtrage côté PHP
$q = strtolower($_GET['q'] ?? '');
$fStatut = $_GET['statut'] ?? '';
$fDate   = $_GET['date_debut'] ?? '';
if ($q || $fStatut || $fDate) {
    $reservations = array_filter($reservations, function($r) use ($q, $fStatut, $fDate) {
        $match = true;
        if ($q) $match = $match && (
            str_contains(strtolower($r['client_nom']), $q) ||
            str_contains(strtolower($r['vehicule_nom']), $q) ||
            str_contains(strtolower($r['reference']), $q)
        );
        if ($fStatut) $match = $match && $r['statut'] === $fStatut;
        if ($fDate)   $match = $match && $r['date_debut'] >= $fDate;
        return $match;
    });
}
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle small">
          <thead class="table-light">
            <tr>
              <th>Référence</th><th>Client</th><th>Tél.</th>
              <th>Véhicule</th><th>Date début</th><th>Date fin</th>
              <th>Jours</th><th>Montant</th><th>Statut</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $r): ?>
            <tr>
              <td><code class="text-primary"><?= htmlspecialchars($r['reference']) ?></code></td>
              <td class="fw-semibold"><?= htmlspecialchars($r['client_nom']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($r['client_tel'] ?? '–') ?></td>
              <td><?= htmlspecialchars($r['vehicule_nom']) ?><br><small class="text-muted"><?= $r['immatriculation'] ?></small></td>
              <td><?= $r['date_debut'] ?></td>
              <td><?= $r['date_fin'] ?></td>
              <td class="text-center"><?= $r['nb_jours'] ?></td>
              <td class="fw-semibold text-primary"><?= number_format($r['montant_total'], 0, ',', ' ') ?> F</td>
              <td><?php
                $badges = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'danger','terminee'=>'secondary'];
                $labels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'];
                echo '<span class="badge bg-'.$badges[$r['statut']].'">'.$labels[$r['statut']].'</span>';
              ?></td>
              <td>
                <div class="d-flex gap-1">
                  <?php if ($r['statut'] === 'en_attente'): ?>
                  <a href="<?= BASE_URL ?>admin/confirmerResa/<?= $r['id'] ?>" class="btn btn-success btn-sm" title="Confirmer">
                    <i class="bi bi-check2-circle"></i>
                  </a>
                  <?php elseif ($r['statut'] === 'confirmee'): ?>
                  <a href="<?= BASE_URL ?>admin/terminerResa/<?= $r['id'] ?>" class="btn btn-secondary btn-sm" title="Retour véhicule">
                    <i class="bi bi-flag-fill"></i>
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
</div>
<<?php $pageTitle = 'Réservations – CarLoc Admin'; ?>
<div class="container-fluid py-4 px-4">
  <h2 class="fw-bold mb-4"><i class="bi bi-calendar-check me-2 text-primary"></i>Gestion des réservations</h2>

  <!-- Filtres -->
<form method="GET" action="<?= BASE_URL ?>admin/reservations" class="card border-0 shadow-sm mb-4">
  <div class="card-body py-3">
    <div class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label fw-semibold small mb-1">Rechercher</label>
        <input type="text" name="q" class="form-control form-control-sm"
               placeholder="Nom client, véhicule, référence..."
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small mb-1">Statut</label>
        <select name="statut" class="form-select form-select-sm">
          <option value="">Tous</option>
          <?php foreach (['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'] as $v=>$l): ?>
          <option value="<?= $v ?>" <?= ($_GET['statut']??'')===$v?'selected':'' ?>><?= $l ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-2">
        <label class="form-label fw-semibold small mb-1">Date début</label>
        <input type="date" name="date_debut" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['date_debut'] ?? '') ?>">
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3 w-100">
          <i class="bi bi-search me-1"></i>Filtrer
        </button>
      </div>
      <div class="col-md-1">
        <a href="<?= BASE_URL ?>admin/reservations" class="btn btn-outline-secondary btn-sm rounded-pill w-100">
          <i class="bi bi-x-lg"></i>
        </a>
      </div>
    </div>
  </div>
</form>

<?php
// Filtrage côté PHP
$q = strtolower($_GET['q'] ?? '');
$fStatut = $_GET['statut'] ?? '';
$fDate   = $_GET['date_debut'] ?? '';
if ($q || $fStatut || $fDate) {
    $reservations = array_filter($reservations, function($r) use ($q, $fStatut, $fDate) {
        $match = true;
        if ($q) $match = $match && (
            str_contains(strtolower($r['client_nom']), $q) ||
            str_contains(strtolower($r['vehicule_nom']), $q) ||
            str_contains(strtolower($r['reference']), $q)
        );
        if ($fStatut) $match = $match && $r['statut'] === $fStatut;
        if ($fDate)   $match = $match && $r['date_debut'] >= $fDate;
        return $match;
    });
}
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle small">
          <thead class="table-light">
            <tr>
              <th>Référence</th><th>Client</th><th>Tél.</th>
              <th>Véhicule</th><th>Date début</th><th>Date fin</th>
              <th>Jours</th><th>Montant</th><th>Statut</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($reservations as $r): ?>
            <tr>
              <td><code class="text-primary"><?= htmlspecialchars($r['reference']) ?></code></td>
              <td class="fw-semibold"><?= htmlspecialchars($r['client_nom']) ?></td>
              <td class="text-muted"><?= htmlspecialchars($r['client_tel'] ?? '–') ?></td>
              <td><?= htmlspecialchars($r['vehicule_nom']) ?><br><small class="text-muted"><?= $r['immatriculation'] ?></small></td>
              <td><?= $r['date_debut'] ?></td>
              <td><?= $r['date_fin'] ?></td>
              <td class="text-center"><?= $r['nb_jours'] ?></td>
              <td class="fw-semibold text-primary"><?= number_format($r['montant_total'], 0, ',', ' ') ?> F</td>
              <td><?php
                $badges = ['en_attente'=>'warning','confirmee'=>'success','annulee'=>'danger','terminee'=>'secondary'];
                $labels = ['en_attente'=>'En attente','confirmee'=>'Confirmée','annulee'=>'Annulée','terminee'=>'Terminée'];
                echo '<span class="badge bg-'.$badges[$r['statut']].'">'.$labels[$r['statut']].'</span>';
              ?></td>
              <td>
                <div class="d-flex gap-1">
                  <?php if ($r['statut'] === 'en_attente'): ?>
                  <a href="<?= BASE_URL ?>admin/confirmerResa/<?= $r['id'] ?>" class="btn btn-success btn-sm" title="Confirmer">
                    <i class="bi bi-check2-circle"></i>
                  </a>
                  <?php elseif ($r['statut'] === 'confirmee'): ?>
                  <a href="<?= BASE_URL ?>admin/terminerResa/<?= $r['id'] ?>" class="btn btn-secondary btn-sm" title="Retour véhicule">
                    <i class="bi bi-flag-fill"></i>
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
</div>
