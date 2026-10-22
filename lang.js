// نظام تبديل اللغة (عربي/إنجليزي) — يشتغل على أي عنصر فيه data-en و data-ar
(function () {
  function currentLang() {
    return localStorage.getItem('ts_lang') || 'en';
  }

  function applyLang(lang) {
    document.querySelectorAll('[data-en]').forEach(function (el) {
      var text = lang === 'ar' ? el.getAttribute('data-ar') : el.getAttribute('data-en');
      if (text !== null) el.textContent = text;
    });
    document.querySelectorAll('[data-en-ph]').forEach(function (el) {
      var ph = lang === 'ar' ? el.getAttribute('data-ar-ph') : el.getAttribute('data-en-ph');
      if (ph !== null) el.setAttribute('placeholder', ph);
    });
    var btn = document.getElementById('langToggle');
    if (btn) btn.innerHTML = lang === 'ar' ? '<i class="fa fa-globe"></i> EN' : '<i class="fa fa-globe"></i> عربي';
    localStorage.setItem('ts_lang', lang);
  }

  document.addEventListener('DOMContentLoaded', function () {
    applyLang(currentLang());
    var btn = document.getElementById('langToggle');
    if (btn) {
      btn.addEventListener('click', function () {
        applyLang(currentLang() === 'ar' ? 'en' : 'ar');
      });
    }
  });
})();
