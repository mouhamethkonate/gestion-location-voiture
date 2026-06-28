<?php $pageTitle = (isset($vehicule) ? 'Modifier' : 'Ajouter') . ' un véhicule – CarLoc'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><?= isset($vehicule) ? 'Modifier le véhicule' : 'Ajouter un véhicule' ?></h2>
    <a href="<?= BASE_URL ?>admin/vehicules" class="btn btn-outline-secondary rounded-pill"><i class="bi bi-arrow-left me-2"></i>Retour</a>
  </div>

  <?php if (!empty($errors)): ?>
  <div class="alert alert-danger"><ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul></div>
  <?php endif; ?>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-4">
      <form method="POST" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Immatriculation *</label>
            <input type="text" name="immatriculation" class="form-control" value="<?= htmlspecialchars($vehicule['immatriculation'] ?? '') ?>" placeholder="SL-0000-AA" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Marque *</label>
            <input type="text" name="marque" class="form-control" value="<?= htmlspecialchars($vehicule['marque'] ?? '') ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Modèle *</label>
            <input type="text" name="modele" class="form-control" value="<?= htmlspecialchars($vehicule['modele'] ?? '') ?>" required>
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold small">Année</label>
            <input type="number" name="annee" class="form-control" value="<?= $vehicule['annee'] ?? date('Y') ?>" min="2000" max="<?= date('Y')+1 ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold small">Couleur</label>
            <input type="text" name="couleur" class="form-control" value="<?= htmlspecialchars($vehicule['couleur'] ?? '') ?>">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold small">Nombre de places</label>
            <input type="number" name="places" class="form-control" value="<?= $vehicule['places'] ?? 5 ?>" min="1" max="20">
          </div>
          <div class="col-md-3">
            <label class="form-label fw-semibold small">Prix par jour (FCFA) *</label>
            <input type="number" name="prix_par_jour" class="form-control" value="<?= $vehicule['prix_par_jour'] ?? '' ?>" step="500" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Catégorie</label>
            <select name="id_categorie" class="form-select">
              <option value="">-- Choisir --</option>
              <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['id'] ?>" <?= ($vehicule['id_categorie'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['nom']) ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Statut</label>
            <select name="statut" class="form-select">
              <?php foreach (['disponible'=>'Disponible','loue'=>'En location','maintenance'=>'Maintenance'] as $val => $label): ?>
              <option value="<?= $val ?>" <?= ($vehicule['statut'] ?? 'disponible') === $val ? 'selected' : '' ?>><?= $label ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold small">Image</label>
            <?php if (!empty($vehicule['image'])): ?>
            <div class="mb-2 d-flex align-items-center gap-3">
              <img src="<?= BASE_URL ?>img/vehicules/<?= htmlspecialchars($vehicule['image']) ?>"
                   onerror="this.src='<?= BASE_URL ?>img/vehicules/default.svg'"
                   id="imgPreview"
                   style="width:100px;height:65px;object-fit:cover;border-radius:6px;border:2px solid var(--bs-border-color);"
                   alt="Image actuelle">
              <span class="text-muted small">Image actuelle — choisir un nouveau fichier pour la remplacer</span>
            </div>
            <?php else: ?>
            <div class="mb-2">
              <img src="<?= BASE_URL ?>img/vehicules/default.svg" id="imgPreview"
                   style="width:100px;height:65px;object-fit:cover;border-radius:6px;border:2px solid var(--bs-border-color);">
            </div>
            <?php endif; ?>
            <input type="file" name="image" class="form-control" accept="image/*" id="imgInput">
            <script>
            document.getElementById('imgInput').addEventListener('change', function(e) {
              const file = e.target.files[0];
              if (file) {
                const reader = new FileReader();
                reader.onload = (ev) => document.getElementById('imgPreview').src = ev.target.result;
                reader.readAsDataURL(file);
              }
            });
            </script>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold small">Description</label>
            <textarea name="description" class="form-control" rows="3"><?= htmlspecialchars($vehicule['description'] ?? '') ?></textarea>
          </div>
          <div class="col-12 d-flex gap-2 justify-content-end">
            <a href="<?= BASE_URL ?>admin/vehicules" class="btn btn-outline-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary px-4">
              <i class="bi bi-save me-2"></i><?= isset($vehicule) ? 'Enregistrer' : 'Ajouter' ?>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
