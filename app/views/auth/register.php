<?php $pageTitle = 'Inscription – CarLoc'; ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header auth-header text-white text-center py-4">
          <div style="font-size:2.5rem;">👤</div>
          <h4 class="fw-bold mb-0 mt-2">Créer un compte</h4>
        </div>
        <div class="card-body p-4">
          <?php if (!empty($errors)): ?>
          <div class="alert alert-danger small">
            <ul class="mb-0 ps-3"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
          </div>
          <?php endif; ?>
          <form method="POST" action="<?= BASE_URL ?>auth/register">
            <div class="row g-3">
              <div class="col-6">
                <label class="form-label fw-semibold small">Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($data['prenom']) ?>" required>
              </div>
              <div class="col-6">
                <label class="form-label fw-semibold small">Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($data['nom']) ?>" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold small">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold small">Téléphone</label>
                <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($data['telephone']) ?>" placeholder="7X XXX XX XX">
              </div>
              <div class="col-6">
                <label class="form-label fw-semibold small">Mot de passe</label>
                <input type="password" name="mot_de_passe" class="form-control" required minlength="6">
              </div>
              <div class="col-6">
                <label class="form-label fw-semibold small">Confirmer</label>
                <input type="password" name="mot_de_passe_confirm" class="form-control" required>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold mt-4">
              <i class="bi bi-person-plus me-2"></i>Créer mon compte
            </button>
          </form>
          <div class="text-center mt-3">
            <a href="<?= BASE_URL ?>auth/login" class="small text-muted">Déjà un compte ? Se connecter</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
