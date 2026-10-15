/* ==========================================================================
   Webincline — interactions & animations (GSAP + ScrollTrigger + MotionPath)
   ========================================================================== */
(function () {
  'use strict';

  const root = document.documentElement;
  const hasGSAP = typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined';
  const motion = root.classList.contains('motion') && hasGSAP;

  window.wiReady = true;
  if (!motion) root.classList.remove('motion');
  if (hasGSAP) {
    gsap.registerPlugin(ScrollTrigger);
    if (window.MotionPathPlugin) gsap.registerPlugin(MotionPathPlugin);
  }

  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
  const formatNumber = (n) => Math.round(n).toLocaleString('en-US');
  const escapeHTML = (str) => str.replace(/[&<>"']/g, (c) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

  /* ---------- Intro screen ---------- */
  function runIntro(done) {
    const intro = $('.intro');
    const seen = root.classList.contains('intro-seen');
    if (!intro || seen || !motion) {
      root.classList.add('intro-seen');
      done();
      return;
    }

    let finished = false;
    const finish = () => {
      if (finished) return;
      finished = true;
      try { sessionStorage.setItem('wi-intro', '1'); } catch (e) { /* storage blocked */ }
      gsap.to(intro, {
        yPercent: -100,
        duration: 0.9,
        ease: 'expo.inOut',
        onComplete: () => root.classList.add('intro-seen'),
      });
      done(0.35);
    };

    const tl = gsap.timeline({ onComplete: finish });
    tl.from('.intro__logo', { scale: 0.6, opacity: 0, rotate: -12, duration: 0.8, ease: 'back.out(1.6)' })
      .from('.intro__tagline', { opacity: 0, y: 12, duration: 0.5 }, '-=0.35')
      .to('.intro__bar span', { scaleX: 1, duration: 0.9, ease: 'power2.inOut' }, '-=0.3');

    const skip = () => { tl.kill(); finish(); };
    intro.addEventListener('click', skip, { once: true });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') skip(); }, { once: true });
  }

  /* ---------- Hero entrance ---------- */
  function heroIn(delay = 0) {
    if (!motion) return;
    const tl = gsap.timeline({ delay });
    tl.to('.hero__title .line__inner', { y: 0, duration: 1.2, ease: 'expo.out', stagger: 0.12 })
      .to('[data-hero-item]', { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out', stagger: 0.1 }, 0.25)
      .add(() => $$('.hero__stats [data-count]').forEach((el) => countUp(el, 1.6)), 0.6);
  }

  /* ---------- Counters ---------- */
  function countUp(el, duration = 1.8) {
    const end = parseFloat(el.dataset.count || '0');
    if (!motion) { el.textContent = formatNumber(end); return; }
    const obj = { v: 0 };
    gsap.to(obj, {
      v: end,
      duration,
      ease: 'power2.out',
      onUpdate: () => { el.textContent = formatNumber(obj.v); },
    });
  }

  /* ---------- Header: scrolled state, hide on scroll down, mobile menu ---------- */
  function initHeader() {
    const header = $('[data-header]');
    if (!header) return;
    let lastY = window.scrollY;

    const onScroll = () => {
      const y = window.scrollY;
      header.classList.toggle('is-scrolled', y > 24);
      const menuOpen = header.classList.contains('menu-open');
      header.classList.toggle('is-hidden', !menuOpen && y > 480 && y > lastY + 4);
      if (y < lastY - 4 || y < 480) header.classList.remove('is-hidden');
      lastY = y;
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    const toggle = $('[data-menu-toggle]');
    const menu = $('[data-mobile-menu]');
    if (!toggle || !menu) return;

    const setOpen = (open) => {
      toggle.setAttribute('aria-expanded', String(open));
      toggle.querySelector('.sr-only').textContent = open ? 'Close menu' : 'Open menu';
      menu.hidden = !open;
      header.classList.toggle('menu-open', open);
      header.classList.toggle('is-scrolled', open || window.scrollY > 24);
      document.body.style.overflow = open ? 'hidden' : '';
      if (open && motion) {
        gsap.fromTo($$('li', menu), { y: 30, opacity: 0 }, { y: 0, opacity: 1, stagger: 0.05, duration: 0.6, ease: 'power3.out' });
      }
    };
    toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
    $$('a', menu).forEach((a) => a.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') { setOpen(false); toggle.focus(); }
    });
    window.matchMedia('(min-width: 901px)').addEventListener('change', (e) => { if (e.matches) setOpen(false); });
  }

  /* ---------- Active nav link ---------- */
  function initActiveNav() {
    const links = $$('.site-nav a[href^="#"]');
    if (!links.length) return;
    const sections = $$('main section[id]');
    let ticking = false;

    const update = () => {
      ticking = false;
      const line = window.innerHeight * 0.4;
      let current = null;
      for (const s of sections) {
        if (s.getBoundingClientRect().top <= line) current = s.id;
      }
      links.forEach((a) => a.classList.toggle('is-active', a.getAttribute('href') === '#' + current));
    };
    window.addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
    update();
  }

  /* ---------- Hero particle network ---------- */
  function initParticles() {
    const canvas = $('[data-particles]');
    const hero = $('[data-hero]');
    if (!canvas || !hero || !motion) return;

    const ctx = canvas.getContext('2d');
    const tints = ['245,245,247', '245,245,247', '245,245,247', '255,122,0', '255,61,129', '58,141,255'];
    const LINK = 130;
    const MOUSE = 150;
    let w = 0, h = 0, dpr = 1, particles = [], raf = 0, running = false;
    const mouse = { x: -9999, y: -9999 };

    const resize = () => {
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      w = hero.offsetWidth; h = hero.offsetHeight;
      canvas.width = w * dpr; canvas.height = h * dpr;
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      const count = Math.round(Math.min(80, Math.max(30, (w * h) / 18000)));
      particles = Array.from({ length: count }, () => ({
        x: Math.random() * w,
        y: Math.random() * h,
        vx: (Math.random() - 0.5) * 0.28,
        vy: (Math.random() - 0.5) * 0.28,
        r: Math.random() * 1.6 + 0.7,
        c: tints[Math.floor(Math.random() * tints.length)],
      }));
    };

    const draw = () => {
      ctx.clearRect(0, 0, w, h);
      for (const p of particles) {
        p.x += p.vx; p.y += p.vy;
        if (p.x < 0 || p.x > w) p.vx *= -1;
        if (p.y < 0 || p.y > h) p.vy *= -1;
        const dx = mouse.x - p.x, dy = mouse.y - p.y;
        const d = Math.hypot(dx, dy);
        if (d < MOUSE) { p.x -= dx * 0.012; p.y -= dy * 0.012; }
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(${p.c},0.55)`;
        ctx.fill();
      }
      for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
          const a = particles[i], b = particles[j];
          const d = Math.hypot(a.x - b.x, a.y - b.y);
          if (d < LINK) {
            ctx.beginPath();
            ctx.moveTo(a.x, a.y); ctx.lineTo(b.x, b.y);
            ctx.strokeStyle = `rgba(245,245,247,${0.13 * (1 - d / LINK)})`;
            ctx.lineWidth = 1;
            ctx.stroke();
          }
        }
      }
      raf = requestAnimationFrame(draw);
    };

    const start = () => { if (!running) { running = true; raf = requestAnimationFrame(draw); } };
    const stop = () => { running = false; cancelAnimationFrame(raf); };

    resize();
    let t;
    window.addEventListener('resize', () => { clearTimeout(t); t = setTimeout(resize, 200); });
    hero.addEventListener('pointermove', (e) => {
      const r = hero.getBoundingClientRect();
      mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top;
    });
    hero.addEventListener('pointerleave', () => { mouse.x = mouse.y = -9999; });

    new IntersectionObserver(([entry]) => (entry.isIntersecting ? start() : stop())).observe(hero);
    document.addEventListener('visibilitychange', () => (document.hidden ? stop() : start()));
  }

  /* ---------- Hero paper-plane flight (loop + incline, like the logo) ---------- */
  function initFlight() {
    const plane = $('[data-flight-plane]');
    const path = $('[data-flight-path]');
    const reveal = $('[data-flight-reveal]');
    if (!plane || !path || !reveal || !motion || !window.MotionPathPlugin) return;

    const len = reveal.getTotalLength();
    gsap.set(reveal, { strokeDasharray: len, strokeDashoffset: len });

    const tl = gsap.timeline({ repeat: -1, repeatDelay: 1.5, delay: 1.2 });
    tl.set(plane, { opacity: 1 })
      .to(plane, {
        duration: 7,
        ease: 'power1.inOut',
        motionPath: { path, align: path, alignOrigin: [0.5, 0.5], autoRotate: true },
      }, 0)
      .to(reveal, { strokeDashoffset: 0, duration: 7, ease: 'power1.inOut' }, 0)
      .to([plane, '.flight-trail'], { opacity: 0, duration: 0.8 }, 6.4)
      .set(reveal, { strokeDashoffset: len })
      .set('.flight-trail', { opacity: 0.75 });

    ScrollTrigger.create({
      trigger: '[data-hero]',
      start: 'top top',
      end: 'bottom top',
      onLeave: () => tl.pause(),
      onEnterBack: () => tl.resume(),
    });
  }

  /* ---------- Scroll reveals ---------- */
  function initReveals() {
    if (!motion) return;

    ScrollTrigger.batch('[data-reveal]', {
      start: 'top 88%',
      once: true,
      onEnter: (batch) => gsap.to(batch, { opacity: 1, y: 0, duration: 1, ease: 'power3.out', stagger: 0.08, overwrite: true }),
    });

    // Headings: words rise in.
    $$('[data-split]').forEach((el) => {
      const words = el.textContent.trim().split(/\s+/);
      el.setAttribute('aria-label', el.textContent.trim());
      el.innerHTML = words.map((w) => `<span class="word" aria-hidden="true"><span class="word__inner">${escapeHTML(w)}</span></span>`).join(' ');
      gsap.from($$('.word__inner', el), {
        yPercent: 110,
        duration: 1.1,
        ease: 'expo.out',
        stagger: 0.06,
        scrollTrigger: { trigger: el, start: 'top 85%', once: true },
      });
    });

    // Statement paragraph: words light up as you scroll.
    $$('[data-scrub-words]').forEach((el) => {
      const words = el.textContent.trim().split(/\s+/);
      el.setAttribute('aria-label', el.textContent.trim());
      el.innerHTML = words.map((w) => `<span class="sw" aria-hidden="true">${escapeHTML(w)}</span>`).join(' ');
      gsap.fromTo($$('.sw', el), { opacity: 0.16 }, {
        opacity: 1,
        stagger: 0.1,
        ease: 'none',
        scrollTrigger: { trigger: el, start: 'top 80%', end: 'bottom 45%', scrub: true },
      });
    });

    // Case-study numbers count up when they appear.
    $$('.case-card [data-count]').forEach((el) => {
      ScrollTrigger.create({ trigger: el, start: 'top 90%', once: true, onEnter: () => countUp(el, 1.8) });
    });

    // Footer wordmark drifts up.
    gsap.from('.site-footer__wordmark', {
      yPercent: 40,
      ease: 'none',
      scrollTrigger: { trigger: '.site-footer', start: 'top bottom', end: 'bottom bottom', scrub: true },
    });
  }

  /* ---------- Service tabs ---------- */
  function initTabs() {
    const wrap = $('[data-tabs]');
    if (!wrap) return;
    const tabs = $$('[role="tab"]', wrap);
    const indicator = $('.tabs__indicator', wrap);

    const moveIndicator = (tab) => {
      if (!indicator) return;
      indicator.style.width = tab.offsetWidth + 'px';
      indicator.style.height = tab.offsetHeight + 'px';
      indicator.style.top = tab.offsetTop + 'px';
      indicator.style.transform = `translateX(${tab.offsetLeft}px)`;
    };

    const select = (tab, focus) => {
      tabs.forEach((t) => {
        const on = t === tab;
        t.setAttribute('aria-selected', String(on));
        t.tabIndex = on ? 0 : -1;
        const panel = document.getElementById(t.getAttribute('aria-controls'));
        if (panel) panel.hidden = !on;
      });
      if (focus) tab.focus();
      moveIndicator(tab);
      const panel = document.getElementById(tab.getAttribute('aria-controls'));
      if (motion && panel) {
        gsap.fromTo($$('.service-card', panel), { y: 30, opacity: 0 }, { y: 0, opacity: 1, duration: 0.7, ease: 'power3.out', stagger: 0.07 });
      }
    };

    tabs.forEach((tab, i) => {
      tab.addEventListener('click', () => select(tab));
      tab.addEventListener('keydown', (e) => {
        const dir = { ArrowRight: 1, ArrowLeft: -1 }[e.key];
        if (dir) { e.preventDefault(); select(tabs[(i + dir + tabs.length) % tabs.length], true); }
        if (e.key === 'Home') { e.preventDefault(); select(tabs[0], true); }
        if (e.key === 'End') { e.preventDefault(); select(tabs[tabs.length - 1], true); }
      });
    });

    const current = () => tabs.find((t) => t.getAttribute('aria-selected') === 'true') || tabs[0];
    moveIndicator(current());
    window.addEventListener('resize', () => moveIndicator(current()));
    if (document.fonts && document.fonts.ready) document.fonts.ready.then(() => moveIndicator(current()));

    // Cursor-following glow on cards.
    $$('.service-card').forEach((card) => {
      card.addEventListener('pointermove', (e) => {
        const r = card.getBoundingClientRect();
        card.style.setProperty('--mx', `${e.clientX - r.left}px`);
        card.style.setProperty('--my', `${e.clientY - r.top}px`);
      });
    });
  }

  /* ---------- Case-study filters ---------- */
  function initFilters() {
    const wrap = $('[data-filters]');
    const grid = $('[data-case-grid]');
    if (!wrap || !grid) return;
    const buttons = $$('[data-filter]', wrap);
    const cards = $$('.case-card', grid);

    buttons.forEach((btn) => btn.addEventListener('click', () => {
      const f = btn.dataset.filter;
      buttons.forEach((b) => { const on = b === btn; b.classList.toggle('is-active', on); b.setAttribute('aria-pressed', String(on)); });
      const shown = [];
      cards.forEach((c) => {
        const match = f === 'all' || c.dataset.service === f;
        c.classList.toggle('is-hidden', !match);
        if (match) shown.push(c);
      });
      if (motion) {
        gsap.fromTo(shown, { y: 24, opacity: 0 }, { y: 0, opacity: 1, duration: 0.6, ease: 'power3.out', stagger: 0.06 });
      }
      ScrollTrigger.refresh();
    }));
  }

  /* ---------- Process steps ---------- */
  function initProcess() {
    const section = $('[data-process]');
    if (!section) return;
    const steps = $$('[data-process-step]', section);
    const fill = $('[data-process-fill]', section);

    if (!motion) { steps.forEach((s) => s.classList.add('is-active')); return; }

    const mm = gsap.matchMedia();
    mm.add('(min-width: 1101px)', () => {
      gsap.from(steps, {
        y: 50, opacity: 0, duration: 0.9, ease: 'power3.out', stagger: 0.1,
        scrollTrigger: { trigger: section, start: 'top 65%', once: true },
      });
      ScrollTrigger.create({
        trigger: '.process__steps',
        start: 'top 75%',
        end: 'bottom 40%',
        scrub: true,
        onUpdate: (self) => {
          gsap.set(fill, { scaleX: self.progress });
          const active = Math.min(steps.length - 1, Math.floor(self.progress * steps.length));
          steps.forEach((s, i) => s.classList.toggle('is-active', i <= active));
        },
      });
    });
    mm.add('(max-width: 1100px)', () => {
      steps.forEach((s) => {
        gsap.from(s, { y: 40, opacity: 0, duration: 0.8, ease: 'power3.out', scrollTrigger: { trigger: s, start: 'top 88%', once: true } });
        ScrollTrigger.create({ trigger: s, start: 'top 65%', onEnter: () => s.classList.add('is-active') });
      });
    });
  }

  /* ---------- Video testimonials ---------- */
  function initVideos() {
    const cards = $$('.video-card');
    const stopAll = (except) => cards.forEach((c) => {
      const v = $('video', c);
      if (c !== except && !v.paused) v.pause();
    });

    cards.forEach((card) => {
      const video = $('[data-video]', card);
      const btn = $('[data-video-play]', card);
      if (!video || !btn) return;
      btn.addEventListener('click', () => {
        stopAll(card);
        video.controls = true;
        video.play().catch(() => {});
      });
      video.addEventListener('play', () => { stopAll(card); card.classList.add('is-playing'); });
      video.addEventListener('pause', () => card.classList.remove('is-playing'));
      video.addEventListener('ended', () => { video.controls = false; card.classList.remove('is-playing'); });
    });
  }

  /* ---------- Contact form ---------- */
  function initForm() {
    const form = $('[data-contact-form]');
    if (!form) return;
    const status = $('[data-form-status]', form);
    const button = $('button[type="submit"]', form);

    const setStatus = (msg, type) => {
      status.textContent = msg;
      status.classList.toggle('is-ok', type === 'ok');
      status.classList.toggle('is-error', type === 'error');
    };

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      let firstInvalid = null;
      $$('[required]', form).forEach((input) => {
        const field = input.closest('.field');
        const ok = input.type === 'email' ? /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim()) : input.value.trim() !== '';
        field.classList.toggle('is-invalid', !ok);
        input.setAttribute('aria-invalid', String(!ok));
        if (!ok && !firstInvalid) firstInvalid = input;
      });
      if (firstInvalid) {
        setStatus('Please fill in your name, a valid email and a short message.', 'error');
        firstInvalid.focus();
        return;
      }

      button.disabled = true;
      setStatus('Sending…');
      try {
        const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } });
        const data = await res.json();
        setStatus(data.message, data.ok ? 'ok' : 'error');
        if (data.ok) form.reset();
      } catch (err) {
        setStatus('Could not send right now. Please email or WhatsApp us instead.', 'error');
      } finally {
        button.disabled = false;
      }
    });

    $$('[required]', form).forEach((input) => input.addEventListener('input', () => {
      input.closest('.field').classList.remove('is-invalid');
      input.removeAttribute('aria-invalid');
    }));
  }

  /* ---------- Boot ---------- */
  initHeader();
  initActiveNav();
  initTabs();
  initFilters();
  initVideos();
  initForm();

  if (motion) {
    initParticles();
    initReveals();
    initProcess();
    runIntro((delay = 0) => { heroIn(delay); initFlight(); });
  } else {
    root.classList.add('intro-seen');
    initProcess();
  }
})();
