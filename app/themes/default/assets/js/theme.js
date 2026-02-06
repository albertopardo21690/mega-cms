(function () {
  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  function isMobile() {
    return window.matchMedia && window.matchMedia('(max-width: 768px)').matches;
  }

  function closePanel(toggle, panel) {
    toggle.setAttribute('aria-expanded', 'false');
    panel.hidden = true;
  }

  function openPanel(toggle, panel) {
    toggle.setAttribute('aria-expanded', 'true');
    panel.hidden = false;
  }

  function closeSubmenu(btn) {
    var id = btn.getAttribute('data-submenu-toggle');
    var box = document.getElementById(id);
    if (!box) return;
    btn.setAttribute('aria-expanded', 'false');
    box.hidden = true;
  }

  function openSubmenu(btn) {
    var id = btn.getAttribute('data-submenu-toggle');
    var box = document.getElementById(id);
    if (!box) return;
    btn.setAttribute('aria-expanded', 'true');
    box.hidden = false;
  }

  // Cierra submenús “hermanos” al abrir uno (acordeón por nivel)
  function closeSiblingSubmenus(btn) {
    var li = btn.closest('.menu-item');
    if (!li) return;

    // El UL actual (nivel) donde están los hermanos
    var containerUl = li.closest('ul.menu');
    if (!containerUl) return;

    containerUl.querySelectorAll('[data-submenu-toggle]').forEach(function (otherBtn) {
      if (otherBtn !== btn) closeSubmenu(otherBtn);
    });
  }

  ready(function () {
    var toggle = document.querySelector('.nav-toggle');
    var panel = document.querySelector('[data-nav-panel]');
    var rootNav = document.querySelector('.nav');

    if (toggle && panel) {
      // Estado inicial en desktop: panel siempre visible
      if (!isMobile()) {
        panel.hidden = false;
        toggle.setAttribute('aria-expanded', 'true');
      } else {
        panel.hidden = true;
        toggle.setAttribute('aria-expanded', 'false');
      }

      // Toggle panel
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        var isOpen = toggle.getAttribute('aria-expanded') === 'true';
        if (isOpen) closePanel(toggle, panel);
        else openPanel(toggle, panel);
      });

      // Click fuera (solo móvil / cuando está abierto)
      document.addEventListener('click', function (e) {
        if (!isMobile()) return;
        if (panel.hidden) return;
        if (!rootNav) return;

        var inside = rootNav.contains(e.target);
        if (!inside) closePanel(toggle, panel);
      });

      // Escape cierra panel + submenús
      document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;

        // Cerrar submenús
        document.querySelectorAll('[data-submenu-toggle]').forEach(function (btn) {
          closeSubmenu(btn);
        });

        // Cerrar panel en móvil
        if (isMobile() && !panel.hidden) closePanel(toggle, panel);
      });

      // Si cambias a desktop, abre panel; si vuelves a móvil, ciérralo
      window.addEventListener('resize', function () {
        if (!toggle || !panel) return;
        if (!isMobile()) {
          panel.hidden = false;
          toggle.setAttribute('aria-expanded', 'true');
        } else {
          // En móvil no forzamos cerrar si ya está abierto; pero si estaba “auto-open” por resize, lo cerramos:
          closePanel(toggle, panel);
          // Y cerramos submenús también
          document.querySelectorAll('[data-submenu-toggle]').forEach(function (btn) {
            closeSubmenu(btn);
          });
        }
      });
    }

    // Submenús (tap to open) + acordeón por nivel
    document.querySelectorAll('[data-submenu-toggle]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();

        var expanded = btn.getAttribute('aria-expanded') === 'true';
        if (expanded) {
          closeSubmenu(btn);
          return;
        }

        // En móvil: acordeón por nivel
        if (isMobile()) closeSiblingSubmenus(btn);

        openSubmenu(btn);
      });
    });

    // En móvil: al clickar un enlace, cerramos el panel (y submenús)
    document.querySelectorAll('.menu-link').forEach(function (a) {
      a.addEventListener('click', function () {
        if (!isMobile()) return;
        if (!toggle || !panel) return;

        // si es un link con url "#", no cierres (es padre típico)
        var href = a.getAttribute('href') || '';
        if (href === '#' || href.trim() === '') return;

        // cerrar todo
        document.querySelectorAll('[data-submenu-toggle]').forEach(function (btn) {
          closeSubmenu(btn);
        });
        closePanel(toggle, panel);
      });
    });
  });
})();
