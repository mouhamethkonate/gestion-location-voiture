<?php $pageTitle = 'Connexion – CarLoc'; ?>
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-5">
      <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header auth-header text-white text-center py-4">
          <div style="font-size:2.5rem;">🚗</div>
          <h4 class="fw-bold mb-0 mt-2">Connexion CarLoc</h4>
          <p class="small opacity-75 mb-0">Accédez à votre espace</p>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="<?= BASE_URL ?>auth/login">
            <div class="mb-3">
              <label class="form-label fw-semibold small">Email</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="votre@email.com" required>
              </div>
            </div>
            <div class="mb-4">
              <label class="form-label fw-semibold small">Mot de passe</label>
              <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" name="mot_de_passe" class="form-control" placeholder="••••••••" required>
              </div>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
              <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
            </button>
          </form>
          <hr class="my-3">
          <div class="text-center">
            <p class="small text-muted mb-2">Pas encore de compte ?</p>
            <a href="<?= BASE_URL ?>auth/register" class="btn btn-outline-primary btn-sm px-4">Créer un compte</a>
          </div>
          <div class="mt-3 p-3 rounded-3 bg-body-secondary small text-muted">
            <strong>Comptes démo :</strong><br>
            🔴 Admin : admin@carloc.sn / <code>Admin@2025</code><br>
            🟡 Agent : agent@carloc.sn / <code>Agent@2025</code><br>
            🔵 Client : client@carloc.sn / <code>Client@2025</code>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
