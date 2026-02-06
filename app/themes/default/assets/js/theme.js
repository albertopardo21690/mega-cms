(function () {
  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function () {
    // Panel principal (hamburguesa)
    var toggle = document.querySelector('.nav-toggle');
    var panel = document.querySelector('[data-nav-panel]');

    if (toggle && panel) {
      toggle.addEventListener('click', function () {
        var isOpen = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!isOpen));
        panel.hidden = isOpen;
      });
    }

    // Submenús
    document.querySelectorAll('[data-submenu-toggle]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();

        var id = btn.getAttribute('data-submenu-toggle');
        var box = document.getElementById(id);
        if (!box) return;

        var expanded = btn.getAttribute('aria-expanded') === 'true';
        btn.setAttribute('aria-expanded', String(!expanded));
        box.hidden = expanded;
      });
    });
  });
})();
