<?php $pageTitle = 'Modifier utilisateur – CarLoc Admin'; ?>
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold mb-0"><i class="bi bi-person-gear me-2 text-primary"></i>Modifier l'utilisateur</h3>
    <a href="<?= BASE_URL ?>admin/utilisateurs" class="btn btn-outline-secondary rounded-pill btn-sm">
      <i class="bi bi-arrow-left me-1"></i>Retour
    </a>
  </div>

  <?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
  </div>
  <?php endif; ?>

  <div class="row g-4">

    <!-- Informations + rôle -->
    <div class="col-lg-7">
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent py-3 border-bottom">
          <h6 class="fw-bold mb-0"><i class="bi bi-person me-2 text-primary"></i>Informations personnelles</h6>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="<?= BASE_URL ?>admin/editUtilisateur/<?= $user['id'] ?>">
            <input type="hidden" name="action" value="profil">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Prénom *</label>
                <input type="text" name="prenom" class="form-control"
                       value="<?= htmlspecialchars($user['prenom']) ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Nom *</label>
                <input type="text" name="nom" class="form-control"
                       value="<?= htmlspecialchars($user['nom']) ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Email</label>
                <input type="email" class="form-control"
                       value="<?= htmlspecialchars($user['email']) ?>" disabled>
                <div class="form-text">Non modifiable.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Téléphone</label>
                <input type="tel" name="telephone" class="form-control"
                       value="<?= htmlspecialchars($user['telephone'] ?? '') ?>"
                       placeholder="7X XXX XX XX">
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold small">Adresse</label>
                <textarea name="adresse" class="form-control" rows="2"
                          placeholder="Adresse..."><?= htmlspecialchars($user['adresse'] ?? '') ?></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Rôle</label>
                <select name="role" class="form-select">
                  <?php foreach (['client'=>'Client','agent'=>'Agent','admin'=>'Administrateur'] as $val => $label): ?>
                  <option value="<?= $val ?>" <?= $user['role'] === $val ? 'selected' : '' ?>>
                    <?= $label ?>
                  </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-6 d-flex align-items-end">
                <div class="p-3 rounded-3 w-100 text-center <?= $user['actif'] ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' ?>">
                  <div class="small text-muted mb-1">Statut actuel</div>
                  <span class="badge bg-<?= $user['actif'] ? 'success' : 'danger' ?> px-3 py-2">
                    <?= $user['actif'] ? '✅ Actif' : '❌ Inactif' ?>
                  </span>
                </div>
              </div>
              <div class="col-12 d-flex gap-2 justify-content-end">
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                  <i class="bi bi-save me-2"></i>Enregistrer
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Colonne droite -->
    <div class="col-lg-5 d-flex flex-column gap-4">

      <!-- Réinitialiser mot de passe -->
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent py-3 border-bottom">
          <h6 class="fw-bold mb-0"><i class="bi bi-key me-2" style="color:#E85C30;"></i>Réinitialiser le mot de passe</h6>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="<?= BASE_URL ?>admin/editUtilisateur/<?= $user['id'] ?>">
            <input type="hidden" name="action" value="password">
            <div class="mb-3">
              <label class="form-label fw-semibold small">Nouveau mot de passe</label>
              <input type="password" name="mdp_nouveau" class="form-control" minlength="6" required placeholder="Min. 6 caractères">
            </div>
            <div class="mb-3">
              <label class="form-label fw-semibold small">Confirmer</label>
              <input type="password" name="mdp_confirm" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-outline-danger rounded-pill w-100">
              <i class="bi bi-key me-2"></i>Réinitialiser
            </button>
          </form>
        </div>
      </div>

      <!-- Activer / Désactiver -->
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent py-3 border-bottom">
          <h6 class="fw-bold mb-0"><i class="bi bi-toggle-on me-2 text-primary"></i>Statut du compte</h6>
        </div>
        <div class="card-body p-4 text-center">
          <p class="text-muted small mb-3">
            <?= $user['actif']
              ? 'Ce compte est <strong>actif</strong>. L\'utilisateur peut se connecter.'
              : 'Ce compte est <strong>désactivé</strong>. L\'utilisateur ne peut pas se connecter.' ?>
          </p>
          <a href="<?= BASE_URL ?>admin/toggleUtilisateur/<?= $user['id'] ?>"
             class="btn btn-<?= $user['actif'] ? 'outline-danger' : 'outline-success' ?> rounded-pill w-100"
             onclick="return confirm('<?= $user['actif'] ? 'Désactiver' : 'Activer' ?> ce compte ?')">
            <i class="bi bi-<?= $user['actif'] ? 'person-x' : 'person-check' ?> me-2"></i>
            <?= $user['actif'] ? 'Désactiver le compte' : 'Activer le compte' ?>
          </a>
        </div>
      </div>

      <!-- Infos compte -->
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
          <div class="small text-muted mb-2 fw-semibold">Informations du compte</div>
          <div class="d-flex justify-content-between small border-bottom py-2">
            <span class="text-muted">ID</span><span class="fw-semibold">#<?= $user['id'] ?></span>
          </div>
          <div class="d-flex justify-content-between small border-bottom py-2">
            <span class="text-muted">Inscription</span>
            <span class="fw-semibold"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
          </div>
          <div class="d-flex justify-content-between small py-2">
            <span class="text-muted">Rôle actuel</span>
            <?php $colors = ['admin'=>'danger','agent'=>'warning','client'=>'primary']; ?>
            <span class="badge bg-<?= $colors[$user['role']] ?? 'secondary' ?>"><?= ucfirst($user['role']) ?></span>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
