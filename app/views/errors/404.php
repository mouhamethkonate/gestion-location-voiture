<?php $pageTitle = 'Page introuvable – CarLoc'; ?>

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-7 text-center">

      <!-- Illustration SVG -->
      <div class="mb-4" style="font-size:1px;">
        <svg viewBox="0 0 500 280" xmlns="http://www.w3.org/2000/svg" style="width:100%;max-width:420px;">
          <!-- Route -->
          <rect x="0" y="220" width="500" height="60" fill="#1e2d3d" rx="0"/>
          <rect x="230" y="235" width="40" height="12" rx="6" fill="#4a7ab0" opacity="0.4"/>
          <!-- Voiture body -->
          <rect x="130" y="170" width="240" height="60" rx="14" fill="#2a4060"/>
          <rect x="160" y="135" width="175" height="60" rx="12" fill="#2a4060"/>
          <!-- Vitres -->
          <rect x="170" y="143" width="72" height="44" rx="8" fill="#3a6090" opacity="0.8"/>
          <rect x="255" y="143" width="68" height="44" rx="8" fill="#3a6090" opacity="0.8"/>
          <!-- Roues -->
          <circle cx="185" cy="235" r="28" fill="#111" stroke="#4a7ab0" stroke-width="5"/>
          <circle cx="185" cy="235" r="13" fill="#1e2d3d"/>
          <circle cx="315" cy="235" r="28" fill="#111" stroke="#4a7ab0" stroke-width="5"/>
          <circle cx="315" cy="235" r="13" fill="#1e2d3d"/>
          <!-- Phares -->
          <rect x="130" y="192" width="28" height="16" rx="4" fill="#60a5fa" opacity="0.8"/>
          <rect x="342" y="192" width="28" height="16" rx="4" fill="#e85c30" opacity="0.7"/>
          <!-- 404 flottant -->
          <text x="250" y="90" font-family="'Segoe UI',sans-serif" font-size="88" font-weight="900"
                fill="none" stroke="#1a6bc8" stroke-width="2" text-anchor="middle" opacity="0.25">404</text>
          <text x="250" y="90" font-family="'Segoe UI',sans-serif" font-size="88" font-weight="900"
                fill="#1a6bc8" text-anchor="middle" opacity="0.15">404</text>
          <!-- Panneau stop -->
          <polygon points="440,80 460,60 480,60 500,80 500,100 480,120 460,120 440,100"
                   fill="#e85c30" opacity="0.9"/>
          <text x="470" y="99" font-family="sans-serif" font-size="14" font-weight="900"
                fill="white" text-anchor="middle">404</text>
        </svg>
      </div>

      <h1 class="fw-bold mb-2" style="font-size:3rem;">Oops !</h1>
      <h2 class="fw-semibold text-muted mb-3">Page introuvable</h2>
      <p class="text-muted mb-5 lead">
        La page que vous cherchez n'existe pas ou a été déplacée.<br>
        Pas d'inquiétude, vous pouvez repartir d'ici.
      </p>

      <!-- Actions -->
      <div class="d-flex flex-wrap justify-content-center gap-3 mb-5">
        <a href="<?= BASE_URL ?>" class="btn btn-primary btn-lg rounded-pill px-5">
          <i class="bi bi-house me-2"></i>Retour à l'accueil
        </a>
        <button onclick="history.back()" class="btn btn-outline-secondary btn-lg rounded-pill px-4">
          <i class="bi bi-arrow-left me-2"></i>Page précédente
        </button>
      </div>

      <!-- Liens rapides -->
      <div class="row g-3 text-start">
        <div class="col-12"><p class="text-muted small fw-semibold text-center mb-2">Liens utiles</p></div>
        <?php
        $links = [
          [BASE_URL,                    'bi-car-front',    'Voir les véhicules',       'Parcourir notre parc'],
          [BASE_URL.'auth/login',       'bi-box-arrow-in-right', 'Se connecter',       'Accéder à mon compte'],
          [BASE_URL.'auth/register',    'bi-person-plus',  'S\'inscrire',             'Créer un compte gratuit'],
          [BASE_URL.'home/contact',     'bi-envelope',     'Nous contacter',           'Une question ? Écrivez-nous'],
        ];
        foreach ($links as [$url, $icon, $titre, $desc]): ?>
        <div class="col-sm-6">
          <a href="<?= $url ?>" class="text-decoration-none">
            <div class="d-flex align-items-center gap-3 p-3 rounded-3 border h-100 quick-link-card">
              <i class="bi <?= $icon ?> text-primary fs-4 flex-shrink-0"></i>
              <div>
                <div class="fw-semibold small"><?= $titre ?></div>
                <div class="text-muted" style="font-size:0.75rem;"><?= $desc ?></div>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>

    </div>
  </div>
</div>

<style>
.quick-link-card {
  background: var(--bs-body-secondary);
  transition: transform 0.15s, box-shadow 0.15s;
}
.quick-link-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 16px rgba(0,0,0,0.1);
  border-color: var(--bs-primary) !important;
}
</style>
