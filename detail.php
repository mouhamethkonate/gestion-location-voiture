<?php $pageTitle = $vehicule['marque'] . ' ' . $vehicule['modele'] . ' – CarLoc'; ?>

<div class="container py-5">

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none">Accueil</a></li>
      <li class="breadcrumb-item"><a href="<?= BASE_URL ?>" class="text-decoration-none">Véhicules</a></li>
      <li class="breadcrumb-item active"><?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?></li>
    </ol>
  </nav>

  <div class="row g-5">

    <!-- IMAGE & GALERIE -->
    <div class="col-lg-6">
      <div class="vehicule-detail-img-wrap rounded-4 overflow-hidden shadow mb-3">
        <img
          src="<?= BASE_URL ?>img/vehicules/<?= htmlspecialchars($vehicule['image']) ?>"
          onerror="this.src='<?= BASE_URL ?>img/vehicules/default.svg'"
          class="img-fluid w-100"
          style="height:360px; object-fit:cover;"
          alt="<?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?>">
      </div>

      <!-- Badge statut -->
      <div class="d-flex gap-2 flex-wrap">
        <?php if ($vehicule['statut'] === 'disponible'): ?>
          <span class="badge bg-success px-3 py-2 fs-6"><i class="bi bi-check-circle me-1"></i>Disponible maintenant</span>
        <?php else: ?>
          <span class="badge bg-warning text-dark px-3 py-2 fs-6"><i class="bi bi-clock me-1"></i>Actuellement indisponible</span>
        <?php endif; ?>
        <span class="badge bg-secondary px-3 py-2 fs-6"><?= htmlspecialchars($vehicule['categorie_nom'] ?? 'Véhicule') ?></span>
      </div>
    </div>

    <!-- INFOS & RÉSERVATION -->
    <div class="col-lg-6">
      <h1 class="fw-bold mb-1"><?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?></h1>
      <p class="text-muted mb-4"><i class="bi bi-tag me-1"></i><?= htmlspecialchars($vehicule['immatriculation']) ?></p>

      <!-- Prix -->
      <div class="prix-box rounded-3 p-4 mb-4 shadow-sm">
        <div class="d-flex align-items-baseline gap-2">
          <span class="display-5 fw-bold text-primary"><?= number_format($vehicule['prix_par_jour'], 0, ',', ' ') ?></span>
          <span class="text-muted fs-5">FCFA / jour</span>
        </div>
        <p class="small text-muted mb-0 mt-1"><i class="bi bi-info-circle me-1"></i>TVA incluse · Paiement à la prise en charge</p>
      </div>

      <!-- Caractéristiques -->
      <h5 class="fw-semibold mb-3">Caractéristiques</h5>
      <div class="row g-3 mb-4">
        <?php
        $specs = [
          ['bi-calendar2',     'Année',        $vehicule['annee']],
          ['bi-people',        'Places',        $vehicule['places'] . ' personnes'],
          ['bi-paint-bucket',  'Couleur',       $vehicule['couleur']],
          ['bi-grid',          'Catégorie',     $vehicule['categorie_nom'] ?? '–'],
        ];
        foreach ($specs as [$icon, $label, $val]): ?>
        <div class="col-6">
          <div class="spec-item d-flex align-items-center gap-3 p-3 rounded-3">
            <div class="spec-icon"><i class="bi <?= $icon ?> text-primary fs-5"></i></div>
            <div>
              <div class="small text-muted"><?= $label ?></div>
              <div class="fw-semibold"><?= htmlspecialchars((string)$val) ?></div>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Description -->
      <?php if (!empty($vehicule['description'])): ?>
      <div class="mb-4">
        <h5 class="fw-semibold mb-2">Description</h5>
        <p class="text-muted"><?= nl2br(htmlspecialchars($vehicule['description'])) ?></p>
      </div>
      <?php endif; ?>

      <!-- CTA -->
      <?php if ($vehicule['statut'] === 'disponible'): ?>
        <?php if (isset($_SESSION['user_id'])): ?>
        <a href="<?= BASE_URL ?>reservation/create/<?= $vehicule['id'] ?>"
           class="btn btn-primary btn-lg w-100 py-3 rounded-pill fw-semibold shadow">
          <i class="bi bi-calendar-plus me-2"></i>Réserver ce véhicule
        </a>
        <?php else: ?>
        <div class="d-grid gap-2">
          <a href="<?= BASE_URL ?>auth/login" class="btn btn-primary btn-lg py-3 rounded-pill fw-semibold">
            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter pour réserver
          </a>
          <a href="<?= BASE_URL ?>auth/register" class="btn btn-outline-primary rounded-pill">
            Pas de compte ? S'inscrire gratuitement
          </a>
        </div>
        <?php endif; ?>
      <?php else: ?>
        <button class="btn btn-secondary btn-lg w-100 py-3 rounded-pill" disabled>
          <i class="bi bi-x-circle me-2"></i>Véhicule non disponible
        </button>
        <p class="text-muted small text-center mt-2">
          <a href="<?= BASE_URL ?>" class="text-decoration-none">← Voir d'autres véhicules disponibles</a>
        </p>
      <?php endif; ?>
    </div>
  </div>

  <!-- AVANTAGES -->
  <div class="row g-3 mt-5">
    <div class="col-12"><h5 class="fw-bold mb-3">Inclus dans votre location</h5></div>
    <?php
    $inclus = [
      ['bi-shield-check','Assurance tous risques incluse'],
      ['bi-tools','Assistance routière 24h/24'],
      ['bi-droplet-half','Plein de carburant à la prise en charge'],
      ['bi-phone','Support client joignable par Wave & Orange Money'],
    ];
    foreach ($inclus as [$icon, $texte]): ?>
    <div class="col-sm-6 col-lg-3">
      <div class="d-flex align-items-center gap-3 p-3 rounded-3 border spec-item">
        <i class="bi <?= $icon ?> text-success fs-4"></i>
        <span class="small fw-semibold"><?= $texte ?></span>
      </div>
    </div>
    <?php endforeach; ?>
  </div>

</div>

<style>
.vehicule-detail-img-wrap { background: var(--bs-body-secondary); }
.prix-box { background: var(--bs-body-secondary); border: 1px solid var(--bs-border-color); }
.spec-item { background: var(--bs-body-secondary); border: 1px solid var(--bs-border-color); transition: transform 0.15s; }
.spec-item:hover { transform: translateY(-2px); }
</style>
