<?php $pageTitle = 'Véhicules – CarLoc Admin'; ?>
<div class="container-fluid py-4 px-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0"><i class="bi bi-car-front me-2 text-primary"></i>Parc automobile</h2>
    <?php if ($_SESSION['user_role'] === 'admin'): ?>
    <a href="<?= BASE_URL ?>admin/addVehicule" class="btn btn-primary rounded-pill px-4">
      <i class="bi bi-plus-lg me-2"></i>Ajouter
    </a>
    <?php endif; ?>
  </div>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>Image</th><th>Immat.</th><th>Marque / Modèle</th>
              <th>Catégorie</th><th>Année</th><th>Prix/jour</th>
              <th>Statut</th><th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($vehicules as $v): ?>
            <tr>
              <td>
                <img src="<?= BASE_URL ?>img/vehicules/<?= htmlspecialchars($v['image']) ?>"
                     onerror="this.src='<?= BASE_URL ?>img/vehicules/default.svg'"
                     style="width:60px;height:40px;object-fit:cover;border-radius:4px;">
              </td>
              <td><code><?= htmlspecialchars($v['immatriculation']) ?></code></td>
              <td class="fw-semibold"><?= htmlspecialchars($v['marque'].' '.$v['modele']) ?></td>
              <td><span class="badge bg-secondary bg-opacity-10 text-secondary"><?= htmlspecialchars($v['categorie_nom'] ?? '–') ?></span></td>
              <td><?= $v['annee'] ?></td>
              <td class="text-primary fw-semibold"><?= number_format($v['prix_par_jour'], 0, ',', ' ') ?> F</td>
              <td><?php
                $s = ['disponible'=>['success','Disponible'],'loue'=>['warning','En location'],'maintenance'=>['danger','Maintenance']];
                [$c,$l] = $s[$v['statut']] ?? ['secondary','–'];
                echo "<span class='badge bg-$c'>$l</span>";
              ?></td>
              <td>
                <div class="d-flex gap-1">
                  <?php if ($_SESSION['user_role'] === 'admin'): ?>
                  <a href="<?= BASE_URL ?>admin/editVehicule/<?= $v['id'] ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                  <a href="<?= BASE_URL ?>admin/deleteVehicule/<?= $v['id'] ?>" class="btn btn-sm btn-outline-danger"
                     onclick="return confirm('Supprimer ce véhicule ?')"><i class="bi bi-trash"></i></a>
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
