<?php $pageTitle = 'Contact & À propos – CarLoc'; ?>

<!-- HERO CONTACT -->
<section style="background: linear-gradient(135deg, #0f1923, #1a2c3d); color:white; padding: 4rem 0 3rem;">
  <div class="container text-center">
    <span class="badge mb-3 px-3 py-2" style="background:rgba(255,255,255,0.15); font-size:0.8rem;">
      <i class="bi bi-geo-alt me-1"></i> Saint-Louis, Sénégal
    </span>
    <h1 class="fw-bold display-5 mb-3">Contactez-nous</h1>
    <p class="lead mb-0" style="color:rgba(255,255,255,0.7);">
      Notre équipe est disponible 6j/7 pour répondre à toutes vos questions.
    </p>
  </div>
</section>

<div class="container py-5">
  <div class="row g-5">

    <!-- FORMULAIRE DE CONTACT -->
    <div class="col-lg-7">
      <h3 class="fw-bold mb-1">Envoyez-nous un message</h3>
      <p class="text-muted mb-4">Nous vous répondons dans un délai de 24h.</p>

      <?php if (!empty($_GET['sent'])): ?>
      <div class="alert alert-success">
        <i class="bi bi-check-circle-fill me-2"></i>
        Message envoyé ! Nous vous répondrons bientôt.
      </div>
      <?php endif; ?>

      <form method="POST" action="<?= BASE_URL ?>home/sendContact" class="card border-0 shadow-sm p-4 rounded-4">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label fw-semibold small">Prénom & Nom *</label>
            <input type="text" name="nom" class="form-control" placeholder="Mamadou Diallo" required
                   value="<?= isset($_SESSION['user_nom']) ? htmlspecialchars($_SESSION['user_nom']) : '' ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small">Email *</label>
            <input type="email" name="email" class="form-control" placeholder="vous@email.com" required
                   value="<?= isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : '' ?>">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small">Téléphone</label>
            <input type="tel" name="telephone" class="form-control" placeholder="7X XXX XX XX">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold small">Sujet *</label>
            <select name="sujet" class="form-select" required>
              <option value="">-- Choisir --</option>
              <option>Demande de réservation</option>
              <option>Tarifs & disponibilités</option>
              <option>Problème avec une réservation</option>
              <option>Partenariat / Pro</option>
              <option>Autre</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label fw-semibold small">Message *</label>
            <textarea name="message" class="form-control" rows="5"
                      placeholder="Décrivez votre demande..." required></textarea>
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary px-5 py-2 rounded-pill fw-semibold">
              <i class="bi bi-send me-2"></i>Envoyer le message
            </button>
          </div>
        </div>
      </form>
    </div>

    <!-- INFOS PRATIQUES -->
    <div class="col-lg-5">
      <h3 class="fw-bold mb-4">Nos coordonnées</h3>

      <div class="d-flex flex-column gap-3 mb-5">
        <?php
        $coords = [
          ['bi-geo-alt-fill',   'Adresse',    'Ngallel<br>Saint-Louis, Sénégal',    '#'],
          ['bi-telephone-fill', 'Téléphone',  '+221 77 000 00 00<br>+221 76 000 00 01',          'tel:+221770000000'],
          ['bi-envelope-fill',  'Email',      'contact@carloc.sn<br>resa@carloc.sn',             'mailto:contact@carloc.sn'],
          ['bi-clock-fill',     'Horaires',   'Lun – Sam : 8h00 – 19h00<br>Dim : 9h00 – 14h00', '#'],
        ];
        foreach ($coords as [$icon, $label, $val, $href]): ?>
        <div class="d-flex gap-3 align-items-start p-3 rounded-3 border contact-card">
          <div class="contact-icon-wrap rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
               style="width:44px;height:44px;background:rgba(26,107,200,0.1);">
            <i class="bi <?= $icon ?> text-primary"></i>
          </div>
          <div>
            <div class="fw-semibold small text-muted mb-1"><?= $label ?></div>
            <div><?= $val ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Paiements -->
      <div class="p-4 rounded-4 border mb-4">
        <h6 class="fw-bold mb-3"><i class="bi bi-credit-card me-2 text-primary"></i>Modes de paiement</h6>
        <div class="d-flex flex-wrap gap-2">
          <span class="badge bg-primary px-3 py-2"><i class="bi bi-phone me-1"></i>Wave</span>
          <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-phone me-1"></i>Orange Money</span>
          <span class="badge bg-success px-3 py-2"><i class="bi bi-cash me-1"></i>Espèces</span>
          <span class="badge bg-secondary px-3 py-2"><i class="bi bi-bank me-1"></i>Virement</span>
        </div>
      </div>

      <!-- Réseaux sociaux -->
      <div class="d-flex gap-2">
        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
          <i class="bi bi-facebook me-1"></i>Facebook
        </a>
        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
          <i class="bi bi-whatsapp me-1"></i>WhatsApp
        </a>
        <a href="#" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
          <i class="bi bi-instagram me-1"></i>Instagram
        </a>
      </div>
    </div>
  </div>

  <!-- SÉPARATEUR -->
  <hr class="my-5">

  <!-- À PROPOS -->
  <div id="about">
    <div class="row g-5 align-items-center">
      <div class="col-lg-6">
        <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2">À propos de nous</span>
        <h2 class="fw-bold mb-3">CarLoc, votre agence de confiance à Saint-Louis/Ndar</h2>
        <p class="text-muted mb-3">
          Fondée à Saint-Louis/Ndar, CarLoc est une agence de location de voitures qui met la simplicité et la fiabilité au cœur de ses services. Nous proposons un parc diversifié adapté à tous les besoins : déplacements professionnels, voyages en famille, tourisme ou événements spéciaux.
        </p>
        <p class="text-muted mb-4">
          Notre plateforme en ligne vous permet de réserver en quelques clics, avec paiement via Wave ou Orange Money. Nos véhicules sont régulièrement entretenus et assurés pour votre tranquillité.
        </p>
        <div class="row g-3 text-center">
          <?php
          $chiffres = [['50+','Véhicules'],['500+','Clients satisfaits'],['3+','Années d\'expérience'],['6j/7','Disponibilité']];
          foreach ($chiffres as [$nb, $label]): ?>
          <div class="col-6 col-sm-3">
            <div class="p-3 rounded-3 border">
              <div class="fs-4 fw-bold text-primary"><?= $nb ?></div>
              <div class="text-muted small"><?= $label ?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="row g-3">
          <?php
          $valeurs = [
            ['bi-shield-check','Fiabilité','Véhicules contrôlés et assurés avant chaque location.'],
            ['bi-currency-exchange','Prix juste','Tarifs transparents en FCFA, sans frais cachés.'],
            ['bi-headset','Support réactif','Joignable par téléphone, Wave et WhatsApp.'],
            ['bi-geo-alt','Localisation','Basés à Saint-Louis/Ndar, livraison possible sur place.'],
          ];
          foreach ($valeurs as [$icon, $titre, $desc]): ?>
          <div class="col-sm-6">
            <div class="p-4 rounded-4 border h-100 valeur-card">
              <i class="bi <?= $icon ?> text-primary fs-3 mb-3 d-block"></i>
              <h6 class="fw-bold mb-2"><?= $titre ?></h6>
              <p class="text-muted small mb-0"><?= $desc ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </div>

  <!-- CTA FINAL -->
  <div class="text-center mt-5 py-5 rounded-4" style="background: linear-gradient(135deg, #0f1923, #1a2c3d);">
    <h3 class="fw-bold text-white mb-2">Prêt à louer un véhicule ?</h3>
    <p style="color:rgba(255,255,255,0.65);" class="mb-4">Consultez notre parc et réservez en ligne en 2 minutes.</p>
    <a href="<?= BASE_URL ?>" class="btn btn-primary btn-lg rounded-pill px-5 me-2">
      <i class="bi bi-car-front me-2"></i>Voir les véhicules
    </a>
    <a href="<?= BASE_URL ?>auth/register" class="btn btn-outline-light btn-lg rounded-pill px-4">
      Créer un compte
    </a>
  </div>
</div>

<style>
.contact-card {
  background: var(--bs-body-secondary);
  transition: transform 0.15s;
}
.contact-card:hover { transform: translateX(4px); }
.valeur-card {
  background: var(--bs-body-secondary);
  transition: transform 0.15s, box-shadow 0.15s;
}
.valeur-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.08); }
</style>
