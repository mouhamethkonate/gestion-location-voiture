</main>

<!-- FOOTER -->
<footer class="carloc-footer mt-5 pt-5 pb-3">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <h5 class="fw-bold mb-3"><i class="bi bi-car-front-fill text-primary me-1"></i> Car<span class="text-primary">Loc</span></h5>
        <p class="text-muted small">Votre agence de location de voitures à Saint-Louis/Ndar. Qualité, fiabilité et prix compétitifs en FCFA.</p>
      </div>
      <div class="col-md-2">
        <h6 class="fw-semibold mb-3">Navigation</h6>
        <ul class="list-unstyled small">
          <li><a href="<?= BASE_URL ?>" class="footer-link">Accueil</a></li>
          <li><a href="<?= BASE_URL ?>home/contact" class="footer-link">Contact</a></li>
          <li><a href="<?= BASE_URL ?>home/contact#about" class="footer-link">À propos</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h6 class="fw-semibold mb-3">Contact</h6>
        <ul class="list-unstyled small text-muted">
          <li><i class="bi bi-geo-alt me-2"></i>Ngallel, Saint-Louis, Sénégal</li>
          <li><i class="bi bi-telephone me-2"></i>+221 77 000 00 00</li>
          <li><i class="bi bi-envelope me-2"></i>contact@carloc.sn</li>
        </ul>
      </div>
      <div class="col-md-3">
        <h6 class="fw-semibold mb-3">Paiement accepté</h6>
        <div class="d-flex gap-2 flex-wrap">
          <span class="badge bg-primary px-3 py-2"><i class="bi bi-phone me-1"></i>Wave</span>
          <span class="badge bg-warning text-dark px-3 py-2"><i class="bi bi-phone me-1"></i>Orange Money</span>
          <span class="badge bg-secondary px-3 py-2"><i class="bi bi-cash me-1"></i>Espèces</span>
        </div>
      </div>
    </div>
    <hr class="my-4">
    <div class="row align-items-center">
      <div class="col-md-6 text-muted small">© 2025 CarLoc — Projet L3 UNCHK</div>
      <div class="col-md-6 text-end text-muted small">Développé avec PHP MVC · Bootstrap 5</div>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= BASE_URL ?>js/app.js"></script>
</body>
</html>
