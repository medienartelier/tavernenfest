document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('[data-fest-program]').forEach(function (program) {
    const tabs = Array.from(program.querySelectorAll('[data-day-tab]'));
    const panels = Array.from(program.querySelectorAll('[data-day-panel]'));
    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        tabs.forEach(function (item) {
          const active = item === tab;
          item.classList.toggle('is-active', active);
          item.setAttribute('aria-selected', active ? 'true' : 'false');
        });
        panels.forEach(function (panel) { panel.classList.toggle('is-active', panel.dataset.dayPanel === tab.dataset.dayTab); });
      });
    });
  });

  const revealSections = Array.from(document.querySelectorAll('main > .section, main > .program-block'));
  if (revealSections.length && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    document.body.classList.add('reveal-enabled');
    revealSections.forEach(function (section, index) {
      section.classList.add(index % 2 === 0 ? 'reveal-from-left' : 'reveal-from-right');
    });
    if ('IntersectionObserver' in window) {
      const revealObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('is-revealed');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: .16, rootMargin: '0px 0px -8% 0px' });
      revealSections.forEach(function (section) { revealObserver.observe(section); });
    } else {
      revealSections.forEach(function (section) { section.classList.add('is-revealed'); });
    }
  }

  const siteHeader = document.querySelector('[data-site-header]');
  const hero = document.querySelector('.hero');
  if (siteHeader && hero) {
    function updateStickyHeader(useHashFallback) {
      const hashTarget = useHashFallback && window.location.hash && window.location.hash !== '#top';
      siteHeader.classList.toggle('is-sticky', hashTarget || hero.getBoundingClientRect().bottom <= 1 || window.scrollY > 20);
    }
    updateStickyHeader(true);
    window.setTimeout(function () { updateStickyHeader(true); }, 80);
    window.setTimeout(function () { updateStickyHeader(true); }, 350);
    window.addEventListener('load', function () { updateStickyHeader(true); });
    window.addEventListener('pageshow', function () { updateStickyHeader(true); });
    window.addEventListener('hashchange', function () { updateStickyHeader(true); });
    window.addEventListener('scroll', function () { updateStickyHeader(false); }, { passive: true });
    if ('IntersectionObserver' in window) {
      const heroObserver = new IntersectionObserver(function (entries) {
        siteHeader.classList.toggle('is-sticky', !entries[0].isIntersecting);
      }, { threshold: 0, rootMargin: '-1px 0px 0px' });
      heroObserver.observe(hero);
    }
  }

  const navigationLinks = Array.from(document.querySelectorAll('.main-nav a'));
  const backToTop = document.querySelector('[data-back-to-top]');
  if (backToTop) {
    function updateBackToTop() {
      backToTop.classList.toggle('is-visible', window.scrollY > window.innerHeight * 0.6);
    }
    updateBackToTop();
    window.addEventListener('scroll', updateBackToTop, { passive: true });
  }

  const menuToggle = document.querySelector('[data-menu-toggle]');
  if (menuToggle) {
    function setMenuOpen(isOpen) {
      document.body.classList.toggle('menu-open', isOpen);
      menuToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      const label = menuToggle.querySelector('.screen-reader-text');
      if (label) label.textContent = isOpen ? 'Menü schließen' : 'Menü öffnen';
    }
    menuToggle.addEventListener('click', function () {
      setMenuOpen(!document.body.classList.contains('menu-open'));
    });
    navigationLinks.forEach(function (link) {
      link.addEventListener('click', function () { setMenuOpen(false); });
    });
    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') setMenuOpen(false);
    });
  }

  const navigationSections = navigationLinks
    .map(function (link) {
      const href = link.getAttribute('href');
      return href === '#top' ? hero : document.querySelector(href);
    })
    .filter(Boolean);
  if (navigationSections.length && 'IntersectionObserver' in window) {
    const sectionObserver = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          navigationLinks.forEach(function (link) {
            const href = link.getAttribute('href');
            const targetHash = entry.target === hero ? '#top' : '#' + entry.target.id;
            link.classList.toggle('is-active', href === targetHash);
          });
        }
      });
    }, { rootMargin: '-25% 0px -60% 0px' });
    navigationSections.forEach(function (section) { sectionObserver.observe(section); });
  }

  const parallaxLayers = [
    { element: document.querySelector('[data-parallax-figure]'), depth: 1 },
    { element: document.querySelector('[data-parallax-heart]'), depth: .65 }
  ].filter(function (layer) { return layer.element; });
  if (parallaxLayers.length && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    let frame;
    window.addEventListener('mousemove', function (event) {
      const x = (event.clientX / window.innerWidth - 0.5) * 18;
      const y = (event.clientY / window.innerHeight - 0.5) * 12;
      window.cancelAnimationFrame(frame);
      frame = window.requestAnimationFrame(function () {
        parallaxLayers.forEach(function (layer) {
          layer.element.style.setProperty('--' + (layer.element.hasAttribute('data-parallax-heart') ? 'heart-' : '') + 'parallax-x', `${x * layer.depth}px`);
          layer.element.style.setProperty('--' + (layer.element.hasAttribute('data-parallax-heart') ? 'heart-' : '') + 'parallax-y', `${y * layer.depth}px`);
        });
      });
    }, { passive: true });
  }

  const countdown = document.querySelector('[data-countdown]');
  if (!countdown) return;
  const target = new Date(countdown.dataset.countdown).getTime();
  const values = {
    days: countdown.querySelector('[data-unit="days"]'),
    hours: countdown.querySelector('[data-unit="hours"]'),
    minutes: countdown.querySelector('[data-unit="minutes"]'),
    seconds: countdown.querySelector('[data-unit="seconds"]')
  };
  function update() {
    const distance = Math.max(0, target - Date.now());
    values.days.textContent = Math.floor(distance / 86400000);
    values.hours.textContent = String(Math.floor(distance / 3600000) % 24).padStart(2, '0');
    values.minutes.textContent = String(Math.floor(distance / 60000) % 60).padStart(2, '0');
    values.seconds.textContent = String(Math.floor(distance / 1000) % 60).padStart(2, '0');
  }
  update();
  window.setInterval(update, 1000);
});
