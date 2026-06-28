<?php $pageTitle = 'Utilisateurs – CarLoc Admin'; ?>
<div class="container-fluid py-4 px-4">
  <h2 class="fw-bold mb-4"><i class="bi bi-people me-2 text-primary"></i>Gestion des utilisateurs</h2>

  <div class="card border-0 shadow-sm">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th>ID</th><th>Nom complet</th><th>Email</th>
              <th>Téléphone</th><th>Rôle</th><th>Inscription</th><th>Statut</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($utilisateurs as $u): ?>
            <tr>
              <td class="text-muted small">#<?= $u['id'] ?></td>
              <td class="fw-semibold"><?= htmlspecialchars($u['prenom'] . ' ' . $u['nom']) ?></td>
              <td><?= htmlspecialchars($u['email']) ?></td>
              <td><?= htmlspecialchars($u['telephone'] ?? '–') ?></td>
              <td><?php
                $roleColors = ['admin'=>'danger','agent'=>'warning','client'=>'primary'];
                $r = $u['role'];
                echo '<span class="badge bg-'.($roleColors[$r]??'secondary').'">'.ucfirst($r).'</span>';
              ?></td>
              <td class="small text-muted"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
              <td>
                <?php if ($u['actif']): ?>
                  <span class="badge bg-success">Actif</span>
                <?php else: ?>
                  <span class="badge bg-danger">Inactif</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="<?= BASE_URL ?>admin/editUtilisateur/<?= $u['id'] ?>"
                   class="btn btn-sm btn-outline-primary rounded-pill">
                  <i class="bi bi-pencil"></i>
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
