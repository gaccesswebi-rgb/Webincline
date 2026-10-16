/* ==========================================================================
   Webincline — interactions & animations (GSAP + ScrollTrigger)
   ========================================================================== */
(function () {
  'use strict';

  const root = document.documentElement;
  const hasGSAP = typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined';
  const motion = root.classList.contains('motion') && hasGSAP;
  window.wiReady = true;
  if (!motion) root.classList.remove('motion');
  if (hasGSAP) gsap.registerPlugin(ScrollTrigger);

  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));
  const fmt = (n) => Math.round(n).toLocaleString('en-US');
  const isDesktop = () => window.matchMedia('(min-width: 961px)').matches;
  const BRAND = [[255, 122, 0], [255, 61, 129], [123, 77, 255], [58, 141, 255]];

  /* ---------- Intro: 0 → 100% ---------- */
  function runIntro(done) {
    const intro = $('.intro');
    if (!intro || root.classList.contains('intro-seen') || !motion) { root.classList.add('intro-seen'); done(0); return; }
    const out = $('[data-intro-count]', intro);
    let finished = false;
    const finish = () => {
      if (finished) return;
      finished = true;
      try { sessionStorage.setItem('wi-intro', '1'); } catch (e) { /* storage blocked */ }
      gsap.to(intro, { clipPath: 'inset(0 0 100% 0)', duration: 0.9, ease: 'expo.inOut', onComplete: () => root.classList.add('intro-seen') });
      done(0.4);
    };
    const n = { v: 0 };
    const tl = gsap.timeline({ onComplete: finish });
    tl.from('.intro__logo', { scale: 0.7, opacity: 0, duration: 0.6, ease: 'back.out(1.7)' })
      .to(n, { v: 100, duration: 1.3, ease: 'power2.inOut', onUpdate: () => { out.textContent = Math.round(n.v); } }, 0.1);
    const skip = () => { tl.kill(); out.textContent = '100'; finish(); };
    intro.addEventListener('click', skip, { once: true });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') skip(); }, { once: true });
  }

  function heroIn(delay) {
    if (!motion) { odometer(); return; }
    gsap.timeline({ delay })
      .to('.hero__title .line__inner', { y: 0, duration: 1.2, ease: 'expo.out', stagger: 0.12 })
      .to('[data-hero-item]', { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out', stagger: 0.09 }, 0.25)
      .add(odometer, 0.3);
  }

  /* ---------- Rolling odometer ---------- */
  function odometer() {
    const el = $('[data-odometer]');
    if (!el) return;
    const value = String(el.dataset.odometer);
    const box = $('.odometer__digits', el);
    if (!motion) { box.textContent = value; return; }
    box.innerHTML = value.split('').map(() => `<span class="odometer__col">${'0123456789'.split('').map((d) => `<span>${d}</span>`).join('')}</span>`).join('');
    $$('.odometer__col', box).forEach((col, i) => {
      gsap.fromTo(col, { yPercent: 0 }, { yPercent: -10 * Number(value[i]), duration: 1 + i * 0.15, ease: 'power3.out' });
    });
  }

  /* ---------- Header ---------- */
  function initHeader() {
    const header = $('[data-header]');
    if (!header) return;
    let lastY = window.scrollY;
    window.addEventListener('scroll', () => {
      const y = window.scrollY;
      const open = header.classList.contains('menu-open');
      header.classList.toggle('is-scrolled', y > 20);
      if (!open) header.classList.toggle('is-hidden', y > 600 && y > lastY + 4);
      if (y < lastY - 4) header.classList.remove('is-hidden');
      lastY = y;
    }, { passive: true });

    const toggle = $('[data-menu-toggle]');
    const menu = $('[data-mobile-menu]');
    if (!toggle || !menu) return;
    const setOpen = (open) => {
      toggle.setAttribute('aria-expanded', String(open));
      $('.sr-only', toggle).textContent = open ? 'Close menu' : 'Open menu';
      menu.hidden = !open;
      header.classList.toggle('menu-open', open);
      document.body.style.overflow = open ? 'hidden' : '';
      if (open && motion) gsap.fromTo($$('li', menu), { y: 30, opacity: 0 }, { y: 0, opacity: 1, stagger: 0.05, duration: 0.6, ease: 'power3.out' });
    };
    toggle.addEventListener('click', () => setOpen(toggle.getAttribute('aria-expanded') !== 'true'));
    $$('a', menu).forEach((a) => a.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') { setOpen(false); toggle.focus(); } });
    window.matchMedia('(min-width: 961px)').addEventListener('change', (e) => { if (e.matches) setOpen(false); });
  }

  function initActiveNav() {
    const links = $$('.site-nav a[href^="#"]');
    const sections = $$('main section[id]');
    if (!links.length) return;
    let ticking = false;
    const update = () => {
      ticking = false;
      let current = null;
      for (const s of sections) if (s.getBoundingClientRect().top <= window.innerHeight * 0.4) current = s.id;
      links.forEach((a) => a.classList.toggle('is-active', a.getAttribute('href') === '#' + current));
    };
    window.addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
    update();
  }

  /* ---------- Shared: run a canvas loop only while visible ---------- */
  function loopWhileVisible(el, frame) {
    let raf = 0, on = false;
    const tick = (t) => { frame(t); raf = requestAnimationFrame(tick); };
    const start = () => { if (!on) { on = true; raf = requestAnimationFrame(tick); } };
    const stop = () => { on = false; cancelAnimationFrame(raf); };
    new IntersectionObserver(([e]) => (e.isIntersecting && !document.hidden ? start() : stop())).observe(el);
    document.addEventListener('visibilitychange', () => (document.hidden ? stop() : start()));
  }

  function fitCanvas(canvas, host) {
    const dpr = Math.min(window.devicePixelRatio || 1, 2);
    const w = host.clientWidth, h = host.clientHeight;
    canvas.width = w * dpr; canvas.height = h * dpr;
    canvas.getContext('2d').setTransform(dpr, 0, 0, dpr, 0, 0);
    return { w, h };
  }

  /* ---------- Hero: a crowd seen from above, with a reach ring ---------- */
  function initHeroCrowd() {
    const hero = $('[data-hero]');
    const canvas = $('[data-crowd]');
    const pops = $('[data-pops]');
    if (!hero || !canvas || !motion) return;
    const ctx = canvas.getContext('2d');
    const RING = 150;
    const labels = ['New lead', 'Call booked', '#1 on Google', 'New follower', 'WhatsApp enquiry', 'Form filled', 'Top-10 keyword', 'Repeat order'];
    let size = { w: 0, h: 0 }, people = [], waves = [];
    const pointer = { x: 0, y: 0, tx: 0, ty: 0, active: false };

    const build = () => {
      size = fitCanvas(canvas, hero);
      const count = Math.round(Math.min(420, Math.max(140, (size.w * size.h) / 4200)));
      people = Array.from({ length: count }, () => {
        const a = Math.random() * Math.PI * 2;
        const s = 0.12 + Math.random() * 0.3;
        return { x: Math.random() * size.w, y: Math.random() * size.h, vx: Math.cos(a) * s, vy: Math.sin(a) * s, r: 2.2 + Math.random() * 1.6, lit: 0, c: BRAND[Math.floor(Math.random() * 4)] };
      });
      pointer.x = pointer.tx = size.w * 0.7; pointer.y = pointer.ty = size.h * 0.55;
    };

    const pop = (x, y) => {
      const el = document.createElement('span');
      el.className = 'pop';
      el.textContent = labels[Math.floor(Math.random() * labels.length)];
      el.style.left = x + 'px'; el.style.top = y + 'px';
      pops.appendChild(el);
      gsap.fromTo(el, { opacity: 0, y: 10, scale: 0.8 }, { opacity: 1, y: -30, scale: 1, duration: 0.5, ease: 'back.out(2)' });
      gsap.to(el, { opacity: 0, y: -60, delay: 1.6, duration: 0.6, onComplete: () => el.remove() });
    };

    const frame = (t) => {
      const { w, h } = size;
      ctx.clearRect(0, 0, w, h);
      // Idle: the ring drifts on its own until the pointer arrives.
      if (!pointer.active) { pointer.tx = w * (0.5 + Math.sin(t / 2600) * 0.28); pointer.ty = h * (0.55 + Math.cos(t / 3100) * 0.18); }
      pointer.x += (pointer.tx - pointer.x) * 0.08;
      pointer.y += (pointer.ty - pointer.y) * 0.08;

      for (const p of people) {
        p.x += p.vx; p.y += p.vy;
        if (p.x < -10) p.x = w + 10; if (p.x > w + 10) p.x = -10;
        if (p.y < -10) p.y = h + 10; if (p.y > h + 10) p.y = -10;
        const d = Math.hypot(p.x - pointer.x, p.y - pointer.y);
        let target = d < RING ? 1 : 0;
        for (const wv of waves) if (Math.abs(d - wv.r) < 26) { target = 1; p.lit = 1; }
        p.lit += (target - p.lit) * 0.08;
        const [r, g, b] = p.c;
        const lr = 120 + (r - 120) * p.lit, lg = 118 + (g - 118) * p.lit, lb = 128 + (b - 128) * p.lit;
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r + p.lit * 1.2, 0, Math.PI * 2);
        ctx.fillStyle = `rgba(${lr | 0},${lg | 0},${lb | 0},${0.35 + p.lit * 0.65})`;
        ctx.fill();
      }

      // Reach ring
      const grad = ctx.createLinearGradient(pointer.x - RING, pointer.y - RING, pointer.x + RING, pointer.y + RING);
      grad.addColorStop(0, 'rgba(255,122,0,.9)'); grad.addColorStop(0.5, 'rgba(255,61,129,.9)'); grad.addColorStop(1, 'rgba(123,77,255,.9)');
      ctx.lineWidth = 10;
      ctx.strokeStyle = grad;
      ctx.globalAlpha = 0.18;
      ctx.beginPath(); ctx.arc(pointer.x, pointer.y, RING, 0, Math.PI * 2); ctx.stroke();
      ctx.globalAlpha = 1; ctx.lineWidth = 2;
      ctx.beginPath(); ctx.arc(pointer.x, pointer.y, RING, 0, Math.PI * 2); ctx.stroke();

      // Campaign waves
      waves = waves.filter((wv) => wv.r < Math.max(w, h));
      for (const wv of waves) {
        wv.r += 9;
        ctx.globalAlpha = Math.max(0, 1 - wv.r / Math.max(w, h));
        ctx.lineWidth = 2; ctx.strokeStyle = grad;
        ctx.beginPath(); ctx.arc(wv.x, wv.y, wv.r, 0, Math.PI * 2); ctx.stroke();
        ctx.globalAlpha = 1;
      }
    };

    build();
    let rt;
    window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(build, 200); });
    hero.addEventListener('pointermove', (e) => {
      const r = hero.getBoundingClientRect();
      pointer.active = true; pointer.tx = e.clientX - r.left; pointer.ty = e.clientY - r.top;
    });
    hero.addEventListener('pointerleave', () => { pointer.active = false; });
    hero.addEventListener('click', (e) => {
      if (e.target.closest('a, button')) return;
      const r = hero.getBoundingClientRect();
      const x = e.clientX - r.left, y = e.clientY - r.top;
      waves.push({ x, y, r: RING });
      for (let i = 0; i < 3; i++) setTimeout(() => pop(x + (Math.random() - 0.5) * 360, y + (Math.random() - 0.5) * 220), i * 260);
    });
    loopWhileVisible(hero, frame);
  }

  /* ---------- Clients radar ---------- */
  function initRadar() {
    const radar = $('[data-radar]');
    if (!radar) return;
    const sweep = $('[data-radar-sweep]', radar);
    const last = $('[data-radar-last]');
    const dots = $$('.radar__dot', radar).map((el) => {
      if (parseFloat(el.style.left) > 62) el.classList.add('is-left');
      return { el, angle: Number(el.dataset.angle), label: el.dataset.label };
    });
    if (!motion) { dots.forEach((d) => d.el.classList.add('is-ping')); return; }
    let prev = 0;
    loopWhileVisible(radar, (t) => {
      const a = (t / 1000 * 40) % 360;
      sweep.style.setProperty('--sweep', a + 'deg');
      for (const d of dots) {
        const crossed = prev <= a ? d.angle > prev && d.angle <= a : d.angle > prev || d.angle <= a;
        if (crossed) {
          d.el.classList.add('is-ping');
          if (last) last.textContent = d.label;
          clearTimeout(d.timer);
          d.timer = setTimeout(() => d.el.classList.remove('is-ping'), 1800);
        }
      }
      prev = a;
    });
  }

  /* ---------- Why: the crowd sorts itself into buyer clusters ---------- */
  function initCrowdPanel() {
    const panel = $('[data-crowd-panel]');
    const canvas = $('[data-crowd2]');
    if (!panel || !canvas) return;
    const ctx = canvas.getContext('2d');
    const anchors = { search: [0.22, 0.56], social: [0.5, 0.36], ads: [0.78, 0.54], ai: [0.8, 0.28] };
    const keys = Object.keys(anchors);
    let size = { w: 0, h: 0 }, dots = [], gather = motion ? 0 : 1;

    const build = () => {
      size = fitCanvas(canvas, panel);
      const count = Math.round(Math.min(1100, (size.w * size.h) / 420));
      dots = Array.from({ length: count }, (_, i) => {
        const buyer = i % 9 === 0;
        const k = keys[i % keys.length];
        const ang = Math.random() * Math.PI * 2, rad = Math.sqrt(Math.random()) * Math.min(size.w, size.h) * 0.09;
        return {
          x: Math.random() * size.w, y: Math.random() * size.h,
          hx: 0, hy: 0, a: Math.random() * Math.PI * 2, s: 0.15 + Math.random() * 0.25,
          buyer, c: BRAND[keys.indexOf(k)],
          gx: anchors[k][0] * size.w + Math.cos(ang) * rad, gy: anchors[k][1] * size.h + Math.sin(ang) * rad,
        };
      });
      dots.forEach((d) => { d.hx = d.x; d.hy = d.y; });
    };

    const frame = () => {
      const { w, h } = size;
      ctx.clearRect(0, 0, w, h);
      for (const d of dots) {
        d.a += (Math.random() - 0.5) * 0.3;
        d.hx += Math.cos(d.a) * d.s; d.hy += Math.sin(d.a) * d.s;
        if (d.hx < 0 || d.hx > w) d.a = Math.PI - d.a;
        if (d.hy < 0 || d.hy > h) d.a = -d.a;
        const g = d.buyer ? gather : 0;
        const tx = d.hx + (d.gx - d.hx) * g, ty = d.hy + (d.gy - d.hy) * g;
        d.x += (tx - d.x) * 0.06; d.y += (ty - d.y) * 0.06;
        ctx.beginPath();
        ctx.arc(d.x, d.y, d.buyer ? 2.2 : 1.3, 0, Math.PI * 2);
        ctx.fillStyle = d.buyer ? `rgba(${d.c[0]},${d.c[1]},${d.c[2]},${0.4 + gather * 0.6})` : 'rgba(180,179,187,.28)';
        ctx.fill();
      }
    };

    build();
    let rt;
    window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(build, 200); });
    if (motion) {
      ScrollTrigger.create({
        trigger: panel, start: 'top 75%', end: 'center 45%', scrub: true,
        onUpdate: (self) => { gather = self.progress; },
      });
      gsap.from($$('.crowd__label', panel), { opacity: 0, y: 14, stagger: 0.12, duration: 0.8, ease: 'power3.out', scrollTrigger: { trigger: panel, start: 'center 70%', once: true } });
      loopWhileVisible(panel, frame);
    } else {
      frame();
    }
  }

  /* ---------- Services: six channels ---------- */
  function initChannels() {
    const wrap = $('[data-channels]');
    if (!wrap) return;
    const items = $$('[data-channel]', wrap);
    const bars = $$('.channels__progress span');
    const idx = $('[data-channel-index]');
    const name = $('[data-channel-name]');
    let current = 0, pin = null;

    const open = (i) => {
      current = i;
      items.forEach((el, k) => {
        el.classList.toggle('is-open', k === i);
        $('.channel__tab', el).setAttribute('aria-expanded', String(k === i));
      });
      bars.forEach((b, k) => b.classList.toggle('is-on', k === i));
      if (i < 0) return;
      if (idx) idx.textContent = String(i + 1).padStart(2, '0');
      if (name) name.textContent = $('.channel__info h3', items[i]).textContent;
    };

    const goTo = (i) => {
      if (pin && isDesktop()) {
        const y = pin.start + (pin.end - pin.start) * (i / (items.length - 1)) + 2;
        window.scrollTo({ top: y, behavior: motion ? 'smooth' : 'auto' });
      } else {
        open(current === i && !isDesktop() ? -1 : i);
      }
    };

    items.forEach((el, i) => $('.channel__tab', el).addEventListener('click', () => goTo(i)));
    $$('[data-goto-channel]').forEach((a) => a.addEventListener('click', (e) => {
      const i = Number(a.dataset.gotoChannel);
      if (!pin || !isDesktop()) { open(i); return; }
      e.preventDefault();
      goTo(i);
    }));

    if (motion) {
      gsap.matchMedia().add('(min-width: 961px)', () => {
        pin = ScrollTrigger.create({
          trigger: '[data-channels-pin]',
          start: 'center center',
          end: () => '+=' + window.innerHeight * 0.55 * (items.length - 1),
          pin: true,
          anticipatePin: 1,
          onUpdate: (self) => {
            const i = Math.round(self.progress * (items.length - 1));
            if (i !== current) open(i);
          },
        });
        return () => { pin = null; };
      });
    }
  }

  /* ---------- Case studies: tabs + before/after slider ---------- */
  function initCases() {
    const tabs = $$('.case-tab');
    if (!tabs.length) return;
    const select = (tab, focus) => {
      tabs.forEach((t) => {
        const on = t === tab;
        t.setAttribute('aria-selected', String(on));
        t.tabIndex = on ? 0 : -1;
        document.getElementById(t.getAttribute('aria-controls')).hidden = !on;
      });
      if (focus) tab.focus();
      const panel = document.getElementById(tab.getAttribute('aria-controls'));
      const range = $('.compare__range', panel);
      const compare = $('[data-compare]', panel);
      const num = $('[data-count]', panel);
      if (motion) {
        const s = { v: 10 };
        gsap.to(s, { v: 58, duration: 1.2, ease: 'power3.inOut', onUpdate: () => { compare.style.setProperty('--pos', s.v + '%'); range.value = s.v; } });
        if (num) countUp(num, 1.4);
      }
    };
    tabs.forEach((tab, i) => {
      tab.addEventListener('click', () => select(tab));
      tab.addEventListener('keydown', (e) => {
        const dir = { ArrowRight: 1, ArrowLeft: -1 }[e.key];
        if (dir) { e.preventDefault(); select(tabs[(i + dir + tabs.length) % tabs.length], true); }
      });
    });
    $$('[data-compare]').forEach((c) => {
      const range = $('.compare__range', c);
      range.addEventListener('input', () => c.style.setProperty('--pos', range.value + '%'));
    });
    if (motion) {
      ScrollTrigger.create({ trigger: '.work', start: 'top 60%', once: true, onEnter: () => select(tabs.find((t) => t.getAttribute('aria-selected') === 'true')) });
    }
  }

  function countUp(el, duration) {
    const end = Number(el.dataset.count || 0);
    if (!motion) { el.textContent = fmt(end); return; }
    const o = { v: 0 };
    gsap.to(o, { v: end, duration, ease: 'power2.out', onUpdate: () => { el.textContent = fmt(o.v); } });
  }

  /* ---------- Process steps + funnel ---------- */
  function initProcess() {
    const section = $('[data-process]');
    if (!section) return;
    const steps = $$('[data-step]', section);
    const stages = $$('[data-stage]', section);
    const set = (i) => {
      steps.forEach((s, k) => s.classList.toggle('is-active', k <= i));
      stages.forEach((s, k) => s.classList.toggle('is-filled', k < i));
    };
    if (!motion) { set(steps.length - 1); return; }
    steps.forEach((s, i) => {
      ScrollTrigger.create({ trigger: s, start: 'top 62%', end: 'bottom 62%', onEnter: () => set(i), onEnterBack: () => set(i) });
    });
    ScrollTrigger.create({ trigger: section, start: 'top 60%', onLeaveBack: () => set(0) });
  }

  /* ---------- Results board ---------- */
  function initBoard() {
    const board = $('[data-board]');
    if (!board) return;
    const tabs = $$('[data-board-tab]', board);
    const grow = (panel) => {
      if (motion) gsap.fromTo($$('.tile__bars span', panel), { scaleY: 0 }, { scaleY: 1, duration: 1.1, ease: 'expo.out', stagger: 0.06 });
    };
    tabs.forEach((t) => t.addEventListener('click', () => {
      tabs.forEach((x) => { const on = x === t; x.classList.toggle('is-on', on); x.setAttribute('aria-selected', String(on)); });
      $$('[data-board-panel]', board).forEach((p) => { p.hidden = p.dataset.boardPanel !== t.dataset.boardTab; if (!p.hidden) grow(p); });
    }));
    if (motion) ScrollTrigger.create({ trigger: board, start: 'top 75%', once: true, onEnter: () => grow($('[data-board-panel]:not([hidden])', board)) });
  }

  /* ---------- Video reels ---------- */
  function initVideos() {
    const reels = $$('.reel');
    const stopOthers = (keep) => reels.forEach((r) => { const v = $('video', r); if (r !== keep && !v.paused) v.pause(); });
    reels.forEach((reel) => {
      const video = $('[data-video]', reel);
      $('[data-video-play]', reel).addEventListener('click', () => { stopOthers(reel); video.controls = true; video.play().catch(() => {}); });
      video.addEventListener('play', () => { stopOthers(reel); reel.classList.add('is-playing'); });
      video.addEventListener('pause', () => reel.classList.remove('is-playing'));
      video.addEventListener('ended', () => { video.controls = false; reel.classList.remove('is-playing'); });
    });
  }

  /* ---------- Team counter ---------- */
  function initTeam() {
    const members = $$('[data-member]');
    const out = $('[data-team-count]');
    if (!members.length || !out) return;
    const seen = new Set([0]);
    const mark = (i) => { seen.add(i); members[i].classList.add('is-seen'); out.textContent = String(seen.size).padStart(2, '0'); };
    mark(0);
    members.forEach((m, i) => m.addEventListener('pointerenter', () => mark(i)));
    if ('IntersectionObserver' in window && !window.matchMedia('(hover: hover)').matches) {
      const io = new IntersectionObserver((es) => es.forEach((e) => { if (e.isIntersecting) mark(members.indexOf(e.target)); }), { threshold: 0.7 });
      members.forEach((m) => io.observe(m));
    }
  }

  /* ---------- FAQ rail ---------- */
  function initFaq() {
    const list = $('[data-faq]');
    const rail = $('[data-faq-rail]');
    if (!list || !rail || !motion) return;
    gsap.to(rail, { scaleY: 1, ease: 'none', scrollTrigger: { trigger: list, start: 'top 70%', end: 'bottom 60%', scrub: true } });
  }

  /* ---------- Clocks (Chandigarh, IST) ---------- */
  function initClocks() {
    const big = $('[data-clock]');
    const small = $('[data-clock-short]');
    const opts = { timeZone: 'Asia/Kolkata', hour12: false };
    const tick = () => {
      const now = new Date();
      if (big) big.textContent = now.toLocaleTimeString('en-GB', { ...opts, hour: '2-digit', minute: '2-digit', second: '2-digit' });
      if (small) small.textContent = now.toLocaleTimeString('en-GB', { ...opts, hour: '2-digit', minute: '2-digit' });
    };
    tick();
    setInterval(tick, 1000);
  }

  /* ---------- Footer wordmark ---------- */
  function initWordmark() {
    const wm = $('[data-wordmark]');
    if (!wm) return;
    const text = $('.wordmark__text', wm);
    wm.addEventListener('pointermove', (e) => {
      const r = text.getBoundingClientRect();
      text.style.setProperty('--x', e.clientX - r.left + 'px');
      text.style.setProperty('--y', e.clientY - r.top + 'px');
    });
    if (!window.matchMedia('(hover: hover)').matches) wm.classList.add('is-lit');
  }

  /* ---------- Contact form ---------- */
  function initForm() {
    const form = $('[data-contact-form]');
    if (!form) return;
    const status = $('[data-form-status]', form);
    const button = $('button[type="submit"]', form);
    const say = (msg, type) => { status.textContent = msg; status.classList.toggle('is-ok', type === 'ok'); status.classList.toggle('is-error', type === 'error'); };

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      let first = null;
      $$('[required]', form).forEach((input) => {
        const ok = input.type === 'email' ? /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value.trim()) : input.value.trim() !== '';
        input.closest('.field').classList.toggle('is-invalid', !ok);
        input.setAttribute('aria-invalid', String(!ok));
        if (!ok && !first) first = input;
      });
      if (first) { say('Add your name, a valid email and a short message.', 'error'); first.focus(); return; }
      button.disabled = true;
      say('Sending…');
      try {
        const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } });
        const data = await res.json();
        say(data.message, data.ok ? 'ok' : 'error');
        if (data.ok) form.reset();
      } catch (err) {
        say('Could not send right now. Please email or WhatsApp us instead.', 'error');
      } finally {
        button.disabled = false;
      }
    });
    $$('[required]', form).forEach((input) => input.addEventListener('input', () => { input.closest('.field').classList.remove('is-invalid'); input.removeAttribute('aria-invalid'); }));
  }

  /* ---------- Scroll reveals ---------- */
  function initReveals() {
    if (!motion) return;
    ScrollTrigger.batch('[data-reveal]', {
      start: 'top 88%',
      once: true,
      onEnter: (batch) => gsap.to(batch, { opacity: 1, y: 0, duration: 1, ease: 'power3.out', stagger: 0.08, overwrite: true }),
    });
  }

  /* ---------- Boot ---------- */
  initHeader();
  initActiveNav();
  initRadar();
  initCrowdPanel();
  initChannels();
  initCases();
  initProcess();
  initBoard();
  initVideos();
  initTeam();
  initFaq();
  initClocks();
  initWordmark();
  initForm();
  if (motion) {
    initHeroCrowd();
    initReveals();
  }
  runIntro((delay) => heroIn(delay));
})();
