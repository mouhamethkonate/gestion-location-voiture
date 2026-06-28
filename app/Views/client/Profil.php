<?php $pageTitle = 'Mon profil – CarLoc'; ?>

<div class="container py-4">
  <div class="row justify-content-center g-4">

    <!-- Colonne gauche — avatar + infos rapides -->
    <div class="col-lg-4">
      <div class="card border-0 shadow-sm rounded-4 text-center p-4">
        <div class="avatar-lg mx-auto mb-3">
          <?= strtoupper(substr($_SESSION['user_nom'], 0, 1)) ?>
        </div>
        <h5 class="fw-bold mb-1"><?= htmlspecialchars($_SESSION['user_nom']) ?></h5>
        <div class="text-muted small mb-2"><?= htmlspecialchars($_SESSION['user_email']) ?></div>
        <span class="badge bg-primary px-3 py-2 mb-3"><?= ucfirst($_SESSION['user_role']) ?></span>

        <div class="border-top pt-3 mt-1">
          <?php
          $stats = [
            ['bi-calendar-check','Réservations', $nbReservations ?? 0, 'primary'],
            ['bi-check-circle',  'Confirmées',   $nbConfirmees  ?? 0, 'success'],
            ['bi-cash',          'Total dépensé', number_format($totalDepense ?? 0, 0,',',' ') . ' FCFA', 'warning'],
          ];
          foreach ($stats as [$icon, $label, $val, $color]): ?>
          <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
            <span class="small text-muted"><i class="bi <?= $icon ?> me-2 text-<?= $color ?>"></i><?= $label ?></span>
            <span class="fw-semibold small"><?= $val ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <a href="<?= BASE_URL ?>client/dashboard" class="btn btn-outline-primary rounded-pill mt-3 btn-sm">
          <i class="bi bi-arrow-left me-1"></i>Mes réservations
        </a>
      </div>
    </div>

    <!-- Colonne droite — formulaire modification -->
    <div class="col-lg-8">
      <h4 class="fw-bold mb-4"><i class="bi bi-person-gear me-2 text-primary"></i>Modifier mon profil</h4>

      <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
      </div>
      <?php endif; ?>

      <!-- Infos personnelles -->
      <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-transparent py-3 border-bottom">
          <h6 class="fw-bold mb-0"><i class="bi bi-person me-2 text-primary"></i>Informations personnelles</h6>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="<?= BASE_URL ?>client/updateProfil">
            <input type="hidden" name="action" value="profil">
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Prénom</label>
                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($user['prenom']) ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Nom</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($user['nom']) ?>" required>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Email</label>
                <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled>
                <div class="form-text">L'email ne peut pas être modifié.</div>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-semibold small">Téléphone</label>
                <input type="tel" name="telephone" class="form-control" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" placeholder="7X XXX XX XX">
              </div>
              <div class="col-12">
                <label class="form-label fw-semibold small">Adresse</label>
                <textarea name="adresse" class="form-control" rows="2" placeholder="Votre adresse à Saint-Louis..."><?= htmlspecialchars($user['adresse'] ?? '') ?></textarea>
              </div>
              <div class="col-12 text-end">
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                  <i class="bi bi-save me-2"></i>Enregistrer les modifications
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- Changer le mot de passe -->
      <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-transparent py-3 border-bottom">
          <h6 class="fw-bold mb-0"><i class="bi bi-lock me-2 text-accent" style="color:#E85C30;"></i>Changer le mot de passe</h6>
        </div>
        <div class="card-body p-4">
          <form method="POST" action="<?= BASE_URL ?>client/updateProfil">
            <input type="hidden" name="action" value="password">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label fw-semibold small">Mot de passe actuel</label>
                <input type="password" name="mdp_actuel" class="form-control" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold small">Nouveau mot de passe</label>
                <input type="password" name="mdp_nouveau" class="form-control" minlength="6" required>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-semibold small">Confirmer</label>
                <input type="password" name="mdp_confirm" class="form-control" required>
              </div>
              <div class="col-12 text-end">
                <button type="submit" class="btn btn-outline-danger rounded-pill px-4">
                  <i class="bi bi-key me-2"></i>Changer le mot de passe
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

    </div>
  </div>
</div>

<style>
.avatar-lg {
  width: 80px; height: 80px; border-radius: 50%;
  background: linear-gradient(135deg, #1A6BC8, #0d4a9e);
  color: white; font-size: 2rem; font-weight: 700;
  display: flex; align-items: center; justify-content: center;
  box-shadow: 0 8px 24px rgba(26,107,200,0.3);
}
</style>
