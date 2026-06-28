<!-- HERO -->
<section class="hero-section">
  <div class="container py-5">
    <div class="row align-items-center min-vh-50">
      <div class="col-lg-7">
        <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">
          <i class="bi bi-geo-alt me-1"></i> Saint-Louis, Sénégal
        </span>
        <h1 class="display-4 fw-bold mb-3">
          Louez le véhicule<br><span class="text-primary">idéal</span> à Saint-Louis/Ndar
        </h1>
        <p class="lead text-muted mb-4">Citadines, SUV, Berlines — Des véhicules fiables au meilleur prix en FCFA. Paiement Wave & Orange Money accepté.</p>
      </div>
      <div class="col-lg-5 text-center d-none d-lg-block">
        <div class="hero-car-icon">
          <img src="https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=600&q=80&auto=format&fit=crop"
               alt="Voiture CarLoc"
               class="hero-car-img"
               onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
          <span style="display:none; font-size:9rem;">🚗</span>
        </div>
      </div>
    </div>

    <!-- SEARCH FORM -->
    <div class="search-card shadow-lg rounded-4 p-4 mt-4">
      <form method="GET" action="<?= BASE_URL ?>" class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label fw-semibold small"><i class="bi bi-calendar3 me-1 text-primary"></i>Date de début</label>
          <input type="date" name="date_debut" class="form-control" value="<?= htmlspecialchars($dateDebut) ?>" min="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-3">
          <label class="form-label fw-semibold small"><i class="bi bi-calendar3-range me-1 text-primary"></i>Date de fin</label>
          <input type="date" name="date_fin" class="form-control" value="<?= htmlspecialchars($dateFin) ?>" min="<?= date('Y-m-d') ?>">
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold small"><i class="bi bi-grid me-1 text-primary"></i>Catégorie</label>
          <select name="categorie" class="form-select">
            <option value="">Toutes</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= $categorie == $cat['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat['nom']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label fw-semibold small"><i class="bi bi-cash me-1 text-primary"></i>Prix max (FCFA)</label>
          <input type="number" name="prix_max" class="form-control" placeholder="Ex: 50000"
                 value="<?= htmlspecialchars($_GET['prix_max'] ?? '') ?>" min="0" step="5000">
        </div>
        <div class="col-md-1">
          <label class="form-label fw-semibold small">&nbsp;</label>
          <button type="submit" class="btn btn-primary w-100 py-2">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<!-- VEHICULES -->
<section class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="fw-bold mb-1">Véhicules disponibles</h2>
        <p class="text-muted small"><?= count($vehicules) ?> véhicule(s) trouvé(s)</p>
      </div>
    </div>

    <?php if (empty($vehicules)): ?>
    <div class="text-center py-5">
      <div style="font-size:4rem;">🔍</div>
      <h5 class="mt-3">Aucun véhicule disponible</h5>
      <p class="text-muted">Essayez d'autres dates ou catégories.</p>
      <a href="<?= BASE_URL ?>" class="btn btn-outline-primary">Réinitialiser</a>
    </div>
    <?php else: ?>
    <div class="row g-4">
      <?php foreach ($vehicules as $v): ?>
      <div class="col-sm-6 col-lg-4">
        <div class="card car-card h-100 border-0 shadow-sm">
          <div class="car-img-wrapper">
            <img src="<?= BASE_URL ?>img/vehicules/<?= htmlspecialchars($v['image']) ?>"
                 onerror="this.src='<?= BASE_URL ?>img/vehicules/default.svg'"
                 class="card-img-top" alt="<?= htmlspecialchars($v['marque'].' '.$v['modele']) ?>">
            <span class="car-badge-cat"><?= htmlspecialchars($v['categorie_nom'] ?? 'Véhicule') ?></span>
          </div>
          <div class="card-body">
            <h5 class="card-title fw-bold mb-1">
              <?= htmlspecialchars($v['marque'] . ' ' . $v['modele']) ?>
            </h5>
            <p class="text-muted small mb-3">
              <i class="bi bi-calendar2 me-1"></i><?= $v['annee'] ?> &nbsp;·&nbsp;
              <i class="bi bi-people me-1"></i><?= $v['places'] ?> places &nbsp;·&nbsp;
              <i class="bi bi-paint-bucket me-1"></i><?= htmlspecialchars($v['couleur']) ?>
            </p>
            <p class="text-muted small mb-3"><?= htmlspecialchars(substr($v['description'] ?? '', 0, 80)) ?>...</p>
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <span class="fs-5 fw-bold text-primary"><?= number_format($v['prix_par_jour'], 0, ',', ' ') ?></span>
                <span class="text-muted small"> FCFA/jour</span>
              </div>
              <span class="badge bg-<?= $v['statut'] === 'disponible' ? 'success' : 'warning text-dark' ?>">
                <?= $v['statut'] === 'disponible' ? 'Disponible' : 'Indisponible' ?>
              </span>
            </div>
            <div class="d-flex gap-2">
              <a href="<?= BASE_URL ?>home/vehicule/<?= $v['id'] ?>" class="btn btn-outline-primary btn-sm rounded-pill flex-grow-1">
                <i class="bi bi-eye me-1"></i>Détails
              </a>
              <?php if ($v['statut'] === 'disponible'): ?>
              <a href="<?= BASE_URL ?>reservation/create/<?= $v['id'] ?>" class="btn btn-primary btn-sm rounded-pill flex-grow-1">
                <i class="bi bi-calendar-plus me-1"></i>Réserver
              </a>
              <?php else: ?>
              <button class="btn btn-secondary btn-sm rounded-pill flex-grow-1" disabled>
                <i class="bi bi-x-circle me-1"></i>Indisponible
              </button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<!-- AVANTAGES -->
<section class="py-5 bg-body-secondary">
  <div class="container">
    <h2 class="text-center fw-bold mb-5">Pourquoi choisir CarLoc ?</h2>
    <div class="row g-4 text-center">
      <?php
      $avantages = [
        ['🚗','Parc diversifié','Citadines, SUV, Berlines pour tous vos besoins'],
        ['💰','Prix en FCFA','Tarifs transparents, pas de frais cachés'],
        ['📱','Paiement mobile','Wave et Orange Money acceptés'],
        ['⚡','Réservation rapide','En ligne en moins de 2 minutes'],
        ['🛡️','Véhicules fiables','Entretenus et assurés'],
        ['📍','Basés à Saint-Louis/Ndar','Livraison possible sur Saint-Louis et environs'],
      ];
      foreach ($avantages as [$icon, $titre, $desc]): ?>
      <div class="col-sm-6 col-lg-4">
        <div class="p-4 rounded-3 h-100 avantage-card">
          <div style="font-size:2.5rem;" class="mb-3"><?= $icon ?></div>
          <h5 class="fw-semibold"><?= $titre ?></h5>
          <p class="text-muted small mb-0"><?= $desc ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
