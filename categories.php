<?php $pageTitle = 'Catégories – CarLoc Admin'; ?>
<div class="container-fluid py-4 px-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-grid me-2 text-primary"></i>Gestion des catégories</h2>
  </div>
  <div class="row g-4">

    <!-- Formulaire ajout/modification -->
    <div class="col-md-4">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3 border-bottom">
          <h6 class="fw-bold mb-0">
            <i class="bi bi-<?= isset($editCategorie) ? 'pencil' : 'plus-circle' ?> me-2 text-primary"></i>
            <?= isset($editCategorie) ? 'Modifier la catégorie' : 'Ajouter une catégorie' ?>
          </h6>
        </div>
        <div class="card-body p-4">
          <?php if (!empty($errors)): ?>
          <div class="alert alert-danger small">
            <?php foreach($errors as $e): ?><div><?= htmlspecialchars($e) ?></div><?php endforeach; ?>
          </div>
          <?php endif; ?>
          <form method="POST" action="<?= BASE_URL ?>admin/<?= isset($editCategorie) ? 'updateCategorie/'.$editCategorie['id'] : 'addCategorie' ?>">
            <div class="mb-3">
              <label class="form-label fw-semibold small">Nom de la catégorie *</label>
              <input type="text" name="nom" class="form-control"
                     value="<?= htmlspecialchars($editCategorie['nom'] ?? '') ?>"
                     placeholder="Ex: SUV, Berline, Citadine..." required>
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold small">Description</label>
              <textarea name="description" class="form-control" rows="3"
                        placeholder="Description de la catégorie..."><?= htmlspecialchars($editCategorie['description'] ?? '') ?></textarea>
            </div>
            <div class="d-flex gap-2">
              <?php if (isset($editCategorie)): ?>
              <a href="<?= BASE_URL ?>admin/categories" class="btn btn-outline-secondary flex-grow-1">Annuler</a>
              <?php endif; ?>
              <button type="submit" class="btn btn-primary flex-grow-1">
                <i class="bi bi-save me-1"></i><?= isset($editCategorie) ? 'Modifier' : 'Ajouter' ?>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Liste des catégories -->
    <div class="col-md-8">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent py-3 border-bottom">
          <h6 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>Liste des catégories</h6>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
              <thead class="table-light">
                <tr><th>ID</th><th>Nom</th><th>Description</th><th>Véhicules</th><th>Actions</th></tr>
              </thead>
              <tbody>
                <?php foreach ($categories as $cat): ?>
                <tr>
                  <td class="text-muted small">#<?= $cat['id'] ?></td>
                  <td class="fw-semibold"><?= htmlspecialchars($cat['nom']) ?></td>
                  <td class="text-muted small"><?= htmlspecialchars(substr($cat['description'] ?? '–', 0, 60)) ?></td>
                  <td class="text-center">
                    <span class="badge bg-primary"><?= $cat['nb_vehicules'] ?? 0 ?></span>
                  </td>
                  <td>
                    <div class="d-flex gap-1">
                      <a href="<?= BASE_URL ?>admin/editCategorie/<?= $cat['id'] ?>"
                         class="btn btn-sm btn-outline-primary rounded-pill">
                        <i class="bi bi-pencil"></i>
                      </a>
                      <?php if (($cat['nb_vehicules'] ?? 0) == 0): ?>
                      <a href="<?= BASE_URL ?>admin/deleteCategorie/<?= $cat['id'] ?>"
                         class="btn btn-sm btn-outline-danger rounded-pill"
                         onclick="return confirm('Supprimer cette catégorie ?')">
                        <i class="bi bi-trash"></i>
                      </a>
                      <?php else: ?>
                      <button class="btn btn-sm btn-outline-secondary rounded-pill" disabled title="Catégorie utilisée">
                        <i class="bi bi-lock"></i>
                      </button>
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
  </div>
</div>







