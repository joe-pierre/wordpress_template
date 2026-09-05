(function() {
  "use strict";

  /**
   * Apply .scrolled class to the body as the page is scrolled down
   */
  function toggleScrolled() {
    const selectBody = document.querySelector('body');
    const selectHeader = document.querySelector('#header');
    if (!selectHeader.classList.contains('scroll-up-sticky') && !selectHeader.classList.contains('sticky-top') && !selectHeader.classList.contains('fixed-top')) return;
    window.scrollY > 100 ? selectBody.classList.add('scrolled') : selectBody.classList.remove('scrolled');
  }

  document.addEventListener('scroll', toggleScrolled);
  window.addEventListener('load', toggleScrolled);

  /**
   * Mobile nav toggle
   */
  const mobileNavToggleBtn = document.querySelector('.mobile-nav-toggle');

  function mobileNavToogle() {
    document.querySelector('body').classList.toggle('mobile-nav-active');
    mobileNavToggleBtn.classList.toggle('bi-list');
    mobileNavToggleBtn.classList.toggle('bi-x');
  }
  if (mobileNavToggleBtn) {
    mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
  }

  /**
   * Hide mobile nav on same-page/hash links
   *
   * Skips mega-menu trigger links (top-level <a> directly inside a
   * .mega-menu-parent <li>): those get their own click handler below that
   * opens the panel in place instead of navigating, and needs to run
   * without this handler closing the whole mobile nav out from under it
   * first (this one is bound earlier, so it would otherwise fire first —
   * see DECISIONS.md "Mega-menu hybride", PROMPT 17). Links *inside* an
   * open mega-menu panel are unaffected and still close the mobile nav
   * normally once clicked.
   */
  document.querySelectorAll('#navmenu a').forEach(navmenu => {
    if (navmenu.parentElement.classList.contains('mega-menu-parent')) return;

    navmenu.addEventListener('click', () => {
      if (document.querySelector('.mobile-nav-active')) {
        mobileNavToogle();
      }
    });

  });

  /**
   * Toggle mobile nav dropdowns
   */
  document.querySelectorAll('.navmenu .toggle-dropdown').forEach(navmenu => {
    navmenu.addEventListener('click', function(e) {
      e.preventDefault();
      this.parentNode.classList.toggle('active');
      this.parentNode.nextElementSibling.classList.toggle('dropdown-active');
      e.stopImmediatePropagation();
    });
  });

  /**
   * Mega-menu (méga-menu hybride, see DZ_Walker_Nav_Menu / DECISIONS.md
   * "Mega-menu hybride" PROMPT 17): opened by clicking/tapping anywhere on
   * the trigger link, not just its chevron — unlike the simple dropdown
   * above (hover on desktop, chevron-only tap on mobile). Reuses the exact
   * same [.active on the link] / [.dropdown-active on the panel] pair the
   * simple dropdown's own handler already toggles, so both end up in the
   * same state whichever element (chevron or link) is actually clicked —
   * see the CSS comments in main.css for why that pairing matters.
   */
  function closeAllMegaMenus() {
    document.querySelectorAll('.navmenu .mega-menu-parent > a.active').forEach(link => {
      link.classList.remove('active');
      link.nextElementSibling.classList.remove('dropdown-active');
    });
  }

  document.querySelectorAll('.navmenu .mega-menu-parent > a').forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      const isOpen = this.classList.contains('active');
      closeAllMegaMenus();
      if (!isOpen) {
        this.classList.add('active');
        this.nextElementSibling.classList.add('dropdown-active');
      }
      e.stopImmediatePropagation();
    });
  });

  // Escape closes any open mega-menu and returns focus to its trigger.
  document.addEventListener('keydown', function(e) {
    if (e.key !== 'Escape') return;
    const openTrigger = document.querySelector('.navmenu .mega-menu-parent > a.active');
    if (!openTrigger) return;
    closeAllMegaMenus();
    openTrigger.focus();
  });

  // Click outside the nav also closes it — the panel has no other obvious
  // dismiss affordance once opened by click (unlike the hover dropdown).
  document.addEventListener('click', function(e) {
    if (!e.target.closest('#navmenu')) {
      closeAllMegaMenus();
    }
  });

  /**
   * Preloader
   */
  const preloader = document.querySelector('#preloader');
  if (preloader) {
    window.addEventListener('load', () => {
      preloader.remove();
    });
  }

  /**
   * Scroll top button
   */
  let scrollTop = document.querySelector('.scroll-top');

  function toggleScrollTop() {
    if (scrollTop) {
      window.scrollY > 100 ? scrollTop.classList.add('active') : scrollTop.classList.remove('active');
    }
  }
  scrollTop.addEventListener('click', (e) => {
    e.preventDefault();
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  });

  window.addEventListener('load', toggleScrollTop);
  document.addEventListener('scroll', toggleScrollTop);

  /**
   * Animation on scroll function and init
   */
  function aosInit() {
    AOS.init({
      duration: 600,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    });
  }
  window.addEventListener('load', aosInit);

  /**
   * Init swiper sliders
   */
  function initSwiper() {
    document.querySelectorAll(".init-swiper").forEach(function(swiperElement) {
      let config = JSON.parse(
        swiperElement.querySelector(".swiper-config").innerHTML.trim()
      );

      if (swiperElement.classList.contains("swiper-tab")) {
        initSwiperWithCustomPagination(swiperElement, config);
      } else {
        new Swiper(swiperElement, config);
      }
    });
  }

  window.addEventListener("load", initSwiper);

  /**
   * Initiate Pure Counter
   */
  new PureCounter();

})();