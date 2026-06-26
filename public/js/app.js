/* ============================================================
   CarLoc – app.js
   Dark mode toggle + utilitaires
   ============================================================ */

(function () {
  'use strict';

  // ── DARK MODE ─────────────────────────────────────────────
  const htmlEl   = document.getElementById('html-root');
  const toggleBtn = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const STORAGE_KEY = 'carloc_theme';

  function applyTheme(theme) {
    htmlEl.setAttribute('data-bs-theme', theme);
    if (themeIcon) {
      themeIcon.className = theme === 'dark'
        ? 'bi bi-sun-fill'
        : 'bi bi-moon-stars-fill';
    }
    localStorage.setItem(STORAGE_KEY, theme);
  }

  function getInitialTheme() {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved) return saved;
    return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  // Appliquer au chargement (évite le flash)
  applyTheme(getInitialTheme());

  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      const current = htmlEl.getAttribute('data-bs-theme');
      applyTheme(current === 'dark' ? 'light' : 'dark');
    });
  }

  // Écouter les changements OS
  window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
    if (!localStorage.getItem(STORAGE_KEY)) {
      applyTheme(e.matches ? 'dark' : 'light');
    }
  });

  // ── AUTO-DISMISS FLASH ────────────────────────────────────
  const alerts = document.querySelectorAll('.alert.alert-dismissible');
  alerts.forEach(alert => {
    setTimeout(() => {
      const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
      if (bsAlert) bsAlert.close();
    }, 4000);
  });

  // ── DATE VALIDATION (réservation) ────────────────────────
  const dateDebut = document.getElementById('dateDebut');
  const dateFin   = document.getElementById('dateFin');

  if (dateDebut && dateFin) {
    dateDebut.addEventListener('change', () => {
      // La date de fin doit être au moins le lendemain
      const min = new Date(dateDebut.value);
      min.setDate(min.getDate() + 1);
      dateFin.min = min.toISOString().split('T')[0];
      if (dateFin.value && dateFin.value <= dateDebut.value) {
        dateFin.value = '';
      }
    });
  }

  // ── CONFIRM DELETE ────────────────────────────────────────
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
      if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
  });

  // ── TOOLTIPS BOOTSTRAP ───────────────────────────────────
  const tooltipEls = document.querySelectorAll('[title]');
  tooltipEls.forEach(el => new bootstrap.Tooltip(el, { trigger: 'hover' }));

  // ── ACTIVE NAV LINK ──────────────────────────────────────
  const currentPath = window.location.href;
  document.querySelectorAll('.nav-link').forEach(link => {
    if (link.href && currentPath.startsWith(link.href) && link.href !== window.location.origin + '/') {
      link.classList.add('active', 'fw-semibold');
    }
  });

})();
