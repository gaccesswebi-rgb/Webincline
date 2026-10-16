/* ==========================================================================
   Webincline — interactions & animations
   GSAP + ScrollTrigger for scroll-driven work, Lenis for smooth scrolling,
   WebGL for the hero, plain DOM for everything else.
   ========================================================================== */
(function () {
  'use strict';

  const root = document.documentElement;
  const hasGSAP = typeof window.gsap !== 'undefined' && typeof window.ScrollTrigger !== 'undefined';
  const motion = root.classList.contains('motion') && hasGSAP;
  window.wiReady = true;
  if (!motion) root.classList.remove('motion');
  if (hasGSAP) gsap.registerPlugin(ScrollTrigger);

  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));
  const clamp = (v, a = 0, b = 1) => Math.min(b, Math.max(a, v));
  const fmt = (n) => Math.round(n).toLocaleString('en-US');
  const fine = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
  const desktop = () => window.matchMedia('(min-width: 961px)').matches;
  const refresh = () => { if (hasGSAP) ScrollTrigger.refresh(); };

  /* Run fn every frame only while el is on screen and the tab is visible. */
  function whileVisible(el, fn, margin = '10%') {
    let raf = 0, on = false, vis = false;
    const loop = (t) => { fn(t); raf = requestAnimationFrame(loop); };
    const sync = () => {
      const want = vis && !document.hidden;
      if (want && !on) { on = true; raf = requestAnimationFrame(loop); }
      if (!want && on) { on = false; cancelAnimationFrame(raf); }
    };
    new IntersectionObserver(([e]) => { vis = e.isIntersecting; sync(); }, { rootMargin: margin }).observe(el);
    document.addEventListener('visibilitychange', sync);
  }

  function inView(el, cb, opts = { threshold: 0.25 }) {
    const io = new IntersectionObserver((es) => es.forEach((e) => { if (e.isIntersecting) { cb(e.target); io.unobserve(e.target); } }), opts);
    io.observe(el);
  }

  /* ---------------- smooth scroll ---------------- */
  let lenis = null;
  function initSmooth() {
    if (motion && window.Lenis) {
      lenis = new Lenis({ lerp: 0.1, smoothWheel: true });
      lenis.on('scroll', ScrollTrigger.update);
      gsap.ticker.add((t) => lenis.raf(t * 1000));
      gsap.ticker.lagSmoothing(0);
    }
    document.addEventListener('click', (e) => {
      const a = e.target.closest('a[href^="#"]');
      if (!a) return;
      const id = a.getAttribute('href');
      if (id.length < 2) return;
      const target = document.querySelector(id);
      if (!target) return;
      e.preventDefault();
      if (lenis) lenis.scrollTo(target, { duration: 1.4 });
      else target.scrollIntoView({ behavior: motion ? 'smooth' : 'auto' });
      history.replaceState(null, '', id);
    });
  }

  /* ---------------- intro ---------------- */
  function runIntro(done) {
    const intro = $('.intro');
    if (!intro || root.classList.contains('intro-seen') || !motion) { root.classList.add('intro-seen'); done(); return; }
    const out = $('[data-intro-count]', intro);
    let finished = false;
    const finish = () => {
      if (finished) return;
      finished = true;
      try { sessionStorage.setItem('wi-intro', '1'); } catch (e) { /* storage blocked */ }
      gsap.to(intro, { clipPath: 'inset(0 0 100% 0)', duration: 0.9, ease: 'expo.inOut', onComplete: () => root.classList.add('intro-seen') });
      setTimeout(done, 380);
    };
    const n = { v: 0 };
    const tl = gsap.timeline({ onComplete: finish });
    tl.from('.intro__logo', { scale: 0.7, opacity: 0, duration: 0.6, ease: 'back.out(1.7)' })
      .to(n, { v: 100, duration: 1.3, ease: 'power2.inOut', onUpdate: () => { out.textContent = Math.round(n.v); } }, 0.1);
    const skip = () => { tl.kill(); out.textContent = '100'; finish(); };
    intro.addEventListener('click', skip, { once: true });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') skip(); }, { once: true });
  }

  /* ---------------- header ---------------- */
  function initHeader() {
    const hd = $('[data-header]');
    if (!hd) return;
    let last = window.scrollY, lastProbe = 0, ticking = false;

    const overLight = () => {
      for (const x of [window.innerWidth * 0.5, window.innerWidth * 0.14]) {
        for (const el of document.elementsFromPoint(x, 44)) {
          if (hd.contains(el)) continue;
          const sec = el.closest('.sec-light, .sec-grey, .sec-grad, .sec-dark, .sec-ink');
          if (sec) return !sec.matches('.sec-dark, .sec-ink');
        }
      }
      return false;
    };

    const update = () => {
      ticking = false;
      const y = window.scrollY;
      const open = hd.classList.contains('menu-open');
      if (Math.abs(y - last) > 6) {
        if (y > last && y > 140 && !open) hd.classList.add('is-hid');
        else if (y < last) hd.classList.remove('is-hid');
        last = y;
      }
      if (hd.matches(':focus-within')) hd.classList.remove('is-hid');
      hd.classList.toggle('is-glass', y > 40);
      const now = performance.now();
      if (now - lastProbe > 120) { lastProbe = now; hd.classList.toggle('is-lt', !open && overLight()); }
    };
    window.addEventListener('scroll', () => { if (!ticking) { ticking = true; requestAnimationFrame(update); } }, { passive: true });
    window.addEventListener('resize', update);
    update();

    const btn = $('[data-menu-toggle]', hd);
    const mm = $('[data-mobile-menu]', hd);
    if (btn && mm) {
      const setOpen = (open) => {
        btn.setAttribute('aria-expanded', String(open));
        $('.sr-only', btn).textContent = open ? 'Close menu' : 'Open menu';
        hd.classList.toggle('menu-open', open);
        if (open) {
          mm.hidden = false;
          requestAnimationFrame(() => requestAnimationFrame(() => mm.classList.add('is-open')));
          hd.classList.remove('is-lt', 'is-hid');
          if (lenis) lenis.stop(); else document.body.style.overflow = 'hidden';
          if (motion) gsap.fromTo($$('li', mm), { y: 40, opacity: 0 }, { y: 0, opacity: 1, stagger: 0.05, duration: 0.7, ease: 'power3.out', delay: 0.15 });
        } else {
          mm.classList.remove('is-open');
          setTimeout(() => { if (!mm.classList.contains('is-open')) mm.hidden = true; }, 700);
          if (lenis) lenis.start(); else document.body.style.overflow = '';
          update();
        }
      };
      btn.addEventListener('click', () => setOpen(btn.getAttribute('aria-expanded') !== 'true'));
      $$('a', mm).forEach((a) => a.addEventListener('click', () => setOpen(false)));
      document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && btn.getAttribute('aria-expanded') === 'true') { setOpen(false); btn.focus(); } });
      window.matchMedia('(min-width: 961px)').addEventListener('change', (e) => { if (e.matches) setOpen(false); });
    }

    // active link dot
    const links = $$('.hd__links a[href^="#"]');
    const secs = links.map((a) => document.querySelector(a.getAttribute('href'))).filter(Boolean);
    const mark = () => {
      let cur = null;
      for (const s of secs) if (s.getBoundingClientRect().top < window.innerHeight * 0.45) cur = s;
      links.forEach((a) => a.classList.toggle('is-act', !!cur && a.getAttribute('href') === '#' + cur.id));
    };
    window.addEventListener('scroll', () => requestAnimationFrame(mark), { passive: true });
  }

  /* ---------------- magnetic buttons ---------------- */
  function initMagnetic() {
    if (!fine || !motion) return;
    let raf = 0, ev = null;
    const run = () => {
      raf = 0;
      for (const el of $$('[data-mag]')) {
        const r = el.getBoundingClientRect();
        if (r.bottom < 0 || r.top > window.innerHeight) continue;
        const dx = ev.clientX - (r.left + r.width / 2), dy = ev.clientY - (r.top + r.height / 2);
        const d = Math.hypot(dx, dy), k = d < 150 ? (1 - d / 150) * 8 : 0;
        el.style.setProperty('--mx', (dx / (d || 1)) * k + 'px');
        el.style.setProperty('--my', (dy / (d || 1)) * k + 'px');
      }
    };
    document.addEventListener('pointermove', (e) => { ev = e; if (!raf) raf = requestAnimationFrame(run); }, { passive: true });
  }

  /* ---------------- reveals ---------------- */
  function initReveals() {
    $$('[data-hd]').forEach((h) => $$('.ln__in', h).forEach((l, i) => l.style.setProperty('--k', i)));
    $$('.hero__h .ln__in').forEach((l, i) => l.style.setProperty('--k', i));
    if (!motion) return;
    const io = new IntersectionObserver((es) => es.forEach((e) => {
      if (!e.isIntersecting) return;
      e.target.classList.add('is-in');
      io.unobserve(e.target);
    }), { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });
    $$('[data-rv], [data-hd]').forEach((el) => io.observe(el));
  }

  /* ---------------- counters ---------------- */
  function countText(el, duration = 1.6) {
    const raw = el.dataset.raw || el.textContent.trim();
    el.dataset.raw = raw;
    const m = raw.match(/^([\d,.]+)(.*)$/);
    if (!m || !motion) { el.textContent = raw; return; }
    const target = parseFloat(m[1].replace(/,/g, ''));
    const dec = (m[1].split('.')[1] || '').length;
    const comma = m[1].includes(',');
    const o = { v: 0 };
    gsap.to(o, {
      v: target, duration, ease: 'power3.out',
      onUpdate: () => {
        const v = dec ? o.v.toFixed(dec) : Math.round(o.v);
        el.textContent = (comma ? Number(v).toLocaleString('en-US') : v) + m[2];
      },
      onComplete: () => { el.textContent = raw; },
    });
  }

  function odometer(el) {
    const value = String(el.dataset.odo);
    if (!motion) { el.textContent = value; return; }
    el.innerHTML = value.split('').map(() => '<span class="odo__c">' + '0123456789'.split('').map((d) => '<span>' + d + '</span>').join('') + '</span>').join('');
    $$('.odo__c', el).forEach((col, i) => gsap.fromTo(col, { yPercent: 0 }, { yPercent: -10 * Number(value[i]), duration: 1.4 + i * 0.25, ease: 'power3.inOut' }));
  }

  /* ---------------- hero ---------------- */
  const VERT = 'attribute vec2 p;void main(){gl_Position=vec4(p,0.,1.);}';
  const FRAG = [
    'precision mediump float;',
    'uniform sampler2D uTex;uniform vec2 uView;uniform float uImg;uniform float uZoom;uniform float uTime;uniform vec4 uP[8];uniform vec3 uPtr;uniform float uDiag;',
    'vec2 cover(vec2 s){float ra=uView.x/uView.y;vec2 uv=s;if(ra>uImg){uv.y=(s.y-.5)*uImg/ra+.5;}else{uv.x=(s.x-.5)*ra/uImg+.5;}return (uv-.5)/uZoom+.5;}',
    'vec3 brand(float a){a=fract(a)*4.;vec3 c1=vec3(1.,.478,0.);vec3 c2=vec3(1.,.239,.506);vec3 c3=vec3(.482,.302,1.);vec3 c4=vec3(.227,.553,1.);',
    'if(a<1.)return mix(c1,c2,a);if(a<2.)return mix(c2,c3,a-1.);if(a<3.)return mix(c3,c4,a-2.);return mix(c4,c1,a-3.);}',
    'void main(){vec2 fc=vec2(gl_FragCoord.x,uView.y-gl_FragCoord.y);vec2 s=fc/uView;',
    'vec3 col=texture2D(uTex,clamp(cover(s),.001,.999)).rgb;float l=dot(col,vec3(.299,.587,.114));',
    'vec3 grey=mix(vec3(l)*vec3(.95,.95,1.),vec3(.067),.52);',
    'float reach=0.;float rim=0.;vec3 rc=vec3(0.);',
    'for(int i=0;i<8;i++){vec4 P=uP[i];if(P.w<=0.)continue;float age=uTime-P.z;if(age<0.)continue;',
    'float R=age*uDiag*(.2+.07*P.w);vec2 dv=fc-P.xy;float d=length(dv);float life=2.4+2.6*P.w;',
    'float fade=1.-smoothstep(life*.4,life,age);float band=uDiag*(.008+.006*P.w);',
    'float r=exp(-pow((d-R)/band,2.))*(1.-smoothstep(0.,life*.75,age));',
    'rc+=brand(atan(dv.y,dv.x)/6.2831+.5+uTime*.06)*r;rim+=r;',
    'reach=max(reach,(1.-smoothstep(R-band*5.,R+band,d))*fade);}',
    'float sp=(1.-smoothstep(uDiag*.035,uDiag*.12,distance(fc,uPtr.xy)))*uPtr.z;reach=max(reach,sp*.85);',
    'vec3 c=mix(grey,col*.92,reach);c=mix(c,rc/max(rim,.001),clamp(rim,0.,1.)*.78);',
    'float vg=smoothstep(.45,1.,s.y)*.6+(1.-smoothstep(0.,.2,s.y))*.3;c*=1.-vg;',
    'gl_FragColor=vec4(c,1.);}',
  ].join('\n');

  // Spots (0–1 of the hero) where notification chips can pop.
  const SPOTS = [[.12, .2], [.2, .34], [.08, .5], [.16, .62], [.26, .74], [.1, .8], [.36, .2], [.62, .2], [.78, .14], [.9, .26], [.86, .4], [.92, .54], [.8, .66], [.72, .8], [.9, .78], [.3, .5], [.7, .5], [.5, .14], [.44, .82], [.58, .84]];

  function initHero(onReady) {
    const hero = $('[data-hero]');
    if (!hero) { onReady(() => {}); return; }
    setTimeout(() => hero.classList.add('is-in'), 60);
    onReady(() => {
      hero.classList.add('is-ready');
      $$('[data-odo]', hero).forEach(odometer);
    });
    if (!motion) return;

    const cv = $('[data-hero-gl]', hero);
    const img = $('.hero__img', hero);
    const pops = $('[data-hero-pops]', hero);
    const cur = $('[data-hero-cur]', hero);
    const texts = JSON.parse(hero.dataset.chips || '[]');
    const avoid = [$('.hero__in .note', hero), $('.hero__h', hero), $('.hero__sub', hero), $('.hero__cta', hero), $('.hero__foot', hero), $('.hero__hint', hero)].filter(Boolean);

    const gl = cv.getContext('webgl', { antialias: false, alpha: false, premultipliedAlpha: false });
    let W = 1, H = 1, dpr = 1, diag = 1, imgRatio = 1.5, ready = false;
    const t0 = performance.now();
    const now = () => (performance.now() - t0) / 1000;
    const pulses = [];
    const spotT = SPOTS.map(() => -99);
    let chipN = 0, chipLive = 0;
    let px = 0, py = 0, tx = 0, ty = 0, hov = 0, lastMove = -99, lastPulse = -99, inside = false, scrollP = 0;
    let rects = [];

    const measure = () => {
      const r = hero.getBoundingClientRect();
      W = r.width; H = r.height; dpr = Math.min(window.devicePixelRatio || 1, 1.6);
      cv.width = Math.round(W * dpr); cv.height = Math.round(H * dpr); diag = Math.hypot(W, H);
      if (gl) gl.viewport(0, 0, cv.width, cv.height);
      rects = avoid.map((el) => { const b = el.getBoundingClientRect(); return [b.left - r.left - 70, b.top - r.top - 30, b.right - r.left + 70, b.bottom - r.top + 24]; });
      if (!px) { px = tx = W / 2; py = ty = H * 0.55; }
    };
    measure();
    window.addEventListener('resize', measure);
    setTimeout(measure, 1500);

    const pulse = (x, y, s) => { pulses.push([x, y, now(), s]); if (pulses.length > 8) pulses.shift(); lastPulse = now(); };

    const pop = (x, y) => {
      if (chipLive >= 4 || !texts.length) return;
      chipLive++;
      const el = document.createElement('span');
      el.className = 'pop';
      el.textContent = texts[chipN++ % texts.length];
      el.style.left = x + 'px'; el.style.top = y + 'px';
      pops.appendChild(el);
      requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('is-up')));
      setTimeout(() => el.classList.remove('is-up'), 2600);
      setTimeout(() => { el.remove(); chipLive--; }, 3200);
    };

    // pointer
    hero.addEventListener('pointermove', (e) => {
      const r = hero.getBoundingClientRect();
      tx = e.clientX - r.left; ty = e.clientY - r.top; lastMove = now(); inside = true;
      if (fine) {
        hero.classList.add('has-cur');
        cur.style.setProperty('--cx', tx + 'px'); cur.style.setProperty('--cy', ty + 'px');
        hero.classList.toggle('is-over', !!e.target.closest('a, button'));
      }
    });
    hero.addEventListener('pointerleave', () => { inside = false; hero.classList.remove('has-cur'); });
    hero.addEventListener('pointerdown', (e) => {
      if (e.target.closest('a, button')) return;
      const r = hero.getBoundingClientRect();
      const x = e.clientX - r.left, y = e.clientY - r.top;
      if (!fine) { px = tx = x; py = ty = y; lastMove = now(); }
      pulse(x, y, 1.6);
    });

    // WebGL setup
    const U = {};
    if (gl) {
      const sh = (type, src) => { const s = gl.createShader(type); gl.shaderSource(s, src); gl.compileShader(s); return s; };
      const prog = gl.createProgram();
      gl.attachShader(prog, sh(gl.VERTEX_SHADER, VERT));
      gl.attachShader(prog, sh(gl.FRAGMENT_SHADER, FRAG));
      gl.linkProgram(prog);
      if (gl.getProgramParameter(prog, gl.LINK_STATUS)) {
        gl.useProgram(prog);
        gl.bindBuffer(gl.ARRAY_BUFFER, gl.createBuffer());
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
        const loc = gl.getAttribLocation(prog, 'p');
        gl.enableVertexAttribArray(loc);
        gl.vertexAttribPointer(loc, 2, gl.FLOAT, false, 0, 0);
        ['uView', 'uImg', 'uZoom', 'uTime', 'uP', 'uPtr', 'uDiag'].forEach((n) => { U[n] = gl.getUniformLocation(prog, n); });
        const tex = gl.createTexture();
        const im = new Image();
        im.decoding = 'async';
        im.onload = () => {
          gl.bindTexture(gl.TEXTURE_2D, tex);
          gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
          gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
          gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
          gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
          gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGB, gl.RGB, gl.UNSIGNED_BYTE, im);
          imgRatio = im.naturalWidth / im.naturalHeight;
          ready = true;
          hero.classList.add('is-gl');
        };
        im.src = img.currentSrc || img.src;
      }
    }

    const P = new Float32Array(32);
    whileVisible(hero, () => {
      const t = now();
      const r = hero.getBoundingClientRect();
      scrollP = clamp(-r.top / (r.height || 1));
      hero.style.setProperty('--sy', scrollP.toFixed(4));

      const idle = !fine || !inside || t - lastMove > 2.6;
      if (idle) { const e = t * 0.23; tx = W * (0.5 + 0.34 * Math.sin(e * 1.3)); ty = H * (0.5 + 0.28 * Math.sin(e * 0.9 + 1.2)); }
      px += (tx - px) * (idle ? 0.03 : 0.16);
      py += (ty - py) * (idle ? 0.03 : 0.16);
      hov += ((fine && !idle ? 1 : 0) - hov) * 0.08;
      if (t > 1.6 && t - lastPulse > (idle ? 1.9 : 1.35)) pulse(px, py, idle ? 0.7 : 0.9);
      if (t > 1.4 && !pulses.length) pulse(W / 2, H * 0.55, 1.4);

      // chips where a ring passes a spot
      for (const p of pulses) {
        const R = (t - p[2]) * diag * (0.2 + 0.07 * p[3]);
        if (R > diag) continue;
        SPOTS.forEach(([sx, sy], i) => {
          if (t - spotT[i] < 7) return;
          const x = sx * W, y = sy * H;
          if (rects.some((b) => x > b[0] && x < b[2] && y > b[1] && y < b[3])) return;
          if (Math.abs(Math.hypot(x - p[0], y - p[1]) - R) > 14) return;
          spotT[i] = t;
          pop(x, y);
        });
      }

      if (!ready) return;
      for (let i = 0; i < 8; i++) {
        const p = pulses[i];
        P[i * 4] = p ? p[0] * dpr : 0; P[i * 4 + 1] = p ? p[1] * dpr : 0; P[i * 4 + 2] = p ? p[2] : 0; P[i * 4 + 3] = p ? p[3] : 0;
      }
      const zoom = 1 + 0.12 * Math.pow(1 - clamp(t / 2.2), 3) + scrollP * 0.08;
      gl.uniform2f(U.uView, cv.width, cv.height);
      gl.uniform1f(U.uImg, imgRatio);
      gl.uniform1f(U.uZoom, zoom);
      gl.uniform1f(U.uTime, t);
      gl.uniform4fv(U.uP, P);
      gl.uniform3f(U.uPtr, px * dpr, py * dpr, hov);
      gl.uniform1f(U.uDiag, diag * dpr);
      gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);
    }, '0px');
  }

  /* ---------------- clients radar ---------------- */
  function initRadar() {
    const sec = $('[data-radar-sec]');
    const radar = $('[data-radar]');
    if (!sec || !radar) return;
    const sweep = $('[data-radar-sweep]', radar);
    const out = $('[data-radar-out]');
    const blips = $$('.blip', radar).map((el) => ({ el, a: Number(el.dataset.angle), label: el.dataset.label, t: -1e9 }));
    if (!motion) { blips.forEach((b) => b.el.classList.add('is-hit')); return; }
    // Only the latest blip shows its badge, so two badges never stack.
    let a = null, prev = 0, clock = 0, last = 0;
    whileVisible(radar, (ts) => {
      const dt = last ? Math.min(0.05, (ts - last) / 1000) : 0;
      last = ts;
      clock += dt * 9;
      const r = sec.getBoundingClientRect();
      const p = clamp((window.innerHeight - r.top) / (window.innerHeight + r.height));
      const target = p * 540 + clock - 120;
      a = a === null ? target : a + (target - a) * 0.2;
      sweep.style.setProperty('--sw', a.toFixed(2) + 'deg');
      const lo = Math.min(prev, a), hi = Math.max(prev, a);
      blips.forEach((b) => {
        let hit = hi - lo >= 360;
        if (!hit) { const k = Math.ceil((lo - b.a) / 360); const x = b.a + 360 * k; hit = x > lo && x <= hi; }
        if (hit) {
          b.t = ts;
          if (out) out.textContent = b.label;
          blips.forEach((o) => o.el.classList.toggle('is-top', o === b));
        }
        b.el.classList.toggle('is-hit', ts - b.t < 1600);
      });
      prev = a;
    });
  }

  /* ---------------- crowd panel ---------------- */
  function initCrowd() {
    const panel = $('[data-crowd]');
    const cv = $('[data-crowd-cv]');
    if (!panel || !cv) return;
    const ctx = cv.getContext('2d');
    const ratio = $('[data-crowd-ratio]', panel);
    const anchors = { search: [0.22, 0.56], social: [0.5, 0.38], ads: [0.78, 0.56], ai: [0.76, 0.2] };
    const keys = Object.keys(anchors);
    const cols = [[255, 122, 0], [255, 61, 129], [123, 77, 255], [58, 141, 255]];
    let W = 0, H = 0, dots = [], gather = motion ? 0 : 1;

    const build = () => {
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      W = panel.clientWidth; H = panel.clientHeight;
      cv.width = W * dpr; cv.height = H * dpr; ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      const n = Math.round(Math.min(1200, (W * H) / 380));
      dots = Array.from({ length: n }, (_, i) => {
        const buyer = i % 12 === 0;
        const k = keys[i % keys.length];
        const ang = Math.random() * Math.PI * 2, rad = Math.sqrt(Math.random()) * Math.min(W, H) * 0.075;
        const x = Math.random() * W, y = Math.random() * H;
        return { x, y, hx: x, hy: y, a: Math.random() * 6.28, s: 0.12 + Math.random() * 0.22, buyer, c: cols[keys.indexOf(k)],
          gx: anchors[k][0] * W + Math.cos(ang) * rad, gy: anchors[k][1] * H + Math.sin(ang) * rad };
      });
    };
    const draw = () => {
      ctx.clearRect(0, 0, W, H);
      for (const d of dots) {
        d.a += (Math.random() - 0.5) * 0.3;
        d.hx += Math.cos(d.a) * d.s; d.hy += Math.sin(d.a) * d.s;
        if (d.hx < 0 || d.hx > W) d.a = Math.PI - d.a;
        if (d.hy < 0 || d.hy > H) d.a = -d.a;
        const g = d.buyer ? gather : 0;
        d.x += (d.hx + (d.gx - d.hx) * g - d.x) * 0.06;
        d.y += (d.hy + (d.gy - d.hy) * g - d.y) * 0.06;
        ctx.beginPath();
        ctx.arc(d.x, d.y, d.buyer ? 2.2 : 1.2, 0, 6.283);
        ctx.fillStyle = d.buyer ? `rgba(${d.c[0]},${d.c[1]},${d.c[2]},${0.45 + gather * 0.55})` : `rgba(190,190,200,${0.32 - gather * 0.12})`;
        ctx.fill();
      }
    };
    build();
    let rt;
    window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(build, 200); });
    if (motion) {
      panel.style.setProperty('--o', 0);
      ScrollTrigger.create({
        trigger: panel, start: 'top 80%', end: 'center 40%', scrub: true,
        onUpdate: (s) => {
          gather = s.progress;
          if (ratio) ratio.textContent = Math.round(138 - (138 - 12) * s.progress);
          panel.style.setProperty('--o', s.progress.toFixed(3));
        },
      });
      whileVisible(panel, draw);
    } else {
      if (ratio) ratio.textContent = '12';
      draw();
    }
  }

  /* ---------------- services accordion ---------------- */
  function initServices() {
    const acc = $('[data-acc]');
    if (!acc) return;
    const pns = $$('[data-pn]', acc);
    const segs = $$('.sv__seg i');
    const idx = $('[data-sv-idx]');
    const name = $('[data-sv-name]');
    let cur = 0, hoverT = 0, pauseUntil = 0, visible = false, serpTimers = [];

    const setWidth = () => {
      if (!desktop()) return;
      const cw = parseFloat(getComputedStyle(acc).getPropertyValue('--cw')) || 92;
      acc.style.setProperty('--ow', Math.max(320, acc.clientWidth - (pns.length - 1) * (cw + 8) - cw) + 'px');
    };

    const serp = (pn) => {
      serpTimers.forEach(clearTimeout); serpTimers = [];
      const box = $('[data-serp]', pn);
      if (!box) return;
      const rows = $$('[data-row]', box);
      const rank = $('[data-rank]', box);
      const order = [0, 1, 2, 3, 4];
      const place = () => rows.forEach((r, i) => r.style.setProperty('--i', order[i]));
      const moveTo = (pos) => {
        const from = order[4];
        rows.forEach((r, i) => { if (i !== 4 && order[i] >= pos && order[i] < from) order[i]++; });
        order[4] = pos;
        place();
        if (rank) rank.textContent = '#' + (pos + 1);
      };
      place();
      if (rank) rank.textContent = '#5';
      if (!pn.classList.contains('is-open')) return;
      serpTimers.push(setTimeout(() => moveTo(2), 1200), setTimeout(() => moveTo(0), 2500));
    };

    const open = (i, user) => {
      if (i < 0 || i >= pns.length) return;
      if (!desktop() && user && i === cur && pns[i].classList.contains('is-open')) {
        pns[i].classList.remove('is-open');
        $('.pn__sp', pns[i]).setAttribute('aria-expanded', 'false');
        return;
      }
      cur = i;
      pns.forEach((p, k) => {
        const on = k === i;
        p.classList.toggle('is-open', on);
        $('.pn__sp', p).setAttribute('aria-expanded', String(on));
        $('.pn__c', p).inert = !on;
      });
      segs.forEach((s, k) => s.classList.toggle('is-on', k === i));
      if (idx) idx.textContent = String(i + 1).padStart(2, '0') + ' / ' + String(pns.length).padStart(2, '0');
      if (name) name.textContent = $('.pn__vt', pns[i]).textContent;
      serp(pns[i]);
      if (!desktop()) setTimeout(refresh, 850);
    };

    pns.forEach((p, i) => {
      const sp = $('.pn__sp', p);
      sp.addEventListener('click', () => { pauseUntil = performance.now() + 9000; open(i, true); });
      sp.addEventListener('keydown', (e) => {
        const k = { ArrowRight: 1, ArrowDown: 1, ArrowLeft: -1, ArrowUp: -1 }[e.key];
        if (!k) return;
        e.preventDefault();
        const n = (i + k + pns.length) % pns.length;
        open(n, true); $('.pn__sp', pns[n]).focus();
      });
      p.addEventListener('pointermove', (e) => {
        if (!fine || !desktop() || (!e.movementX && !e.movementY)) return;
        pauseUntil = performance.now() + 4000;
        if (i === cur) { clearTimeout(hoverT); return; }
        clearTimeout(hoverT);
        hoverT = setTimeout(() => open(i), 140);
      });
    });
    acc.addEventListener('pointerleave', () => clearTimeout(hoverT));

    $$('[data-goto-channel]').forEach((a) => a.addEventListener('click', () => { pauseUntil = performance.now() + 9000; open(Number(a.dataset.gotoChannel)); }));

    setWidth();
    new ResizeObserver(setWidth).observe(acc);
    pns.forEach((p, k) => { $('.pn__c', p).inert = k !== 0; });
    new IntersectionObserver(([e]) => { visible = e.isIntersecting; if (visible) serp(pns[cur]); }, { threshold: 0.4 }).observe(acc);
    if (motion) {
      setInterval(() => {
        if (!visible || !desktop() || document.hidden || performance.now() < pauseUntil || acc.matches(':hover')) return;
        open((cur + 1) % pns.length);
      }, 4800);
    }
  }

  /* ---------------- case studies ---------------- */
  function initWork() {
    const sec = $('[data-wk]');
    if (!sec) return;
    const tabs = $$('.wk__tab', sec);
    const cases = $$('.wk__case', sec);
    let cur = 0, visible = false, hovering = false, dragging = false;

    const run = () => sec.classList.toggle('is-run', motion && visible && !hovering && !dragging);

    const intro = (c) => {
      const cmp = $('[data-cmp]', c);
      const range = $('.cmp__range', cmp);
      const num = $('[data-count]', cmp);
      const line = $('.cmp__line', cmp);
      if (!motion) return;
      const s = { v: 0.08, l: 1 };
      gsap.to(s, {
        v: 0.42, l: 0, duration: 1.4, ease: 'power3.inOut',
        onUpdate: () => {
          cmp.style.setProperty('--dv', s.v.toFixed(4));
          cmp.style.setProperty('--dt', s.v.toFixed(4));
          range.value = Math.round(s.v * 100);
          line.style.setProperty('--ld', s.l.toFixed(4));
        },
      });
      if (num) countText(num, 1.6);
    };

    const select = (i, focus) => {
      cur = i;
      tabs.forEach((t, k) => {
        const on = k === i;
        t.setAttribute('aria-selected', String(on));
        t.tabIndex = on ? 0 : -1;
        const p = $('.wk__prog', t);
        p.style.animation = 'none'; void p.offsetWidth; p.style.animation = '';
      });
      cases.forEach((c, k) => { const on = k === i; c.hidden = !on; c.classList.toggle('is-on', on); });
      if (focus) tabs[i].focus();
      intro(cases[i]);
    };

    tabs.forEach((t, i) => {
      t.addEventListener('click', () => select(i));
      t.addEventListener('keydown', (e) => {
        const k = { ArrowRight: 1, ArrowLeft: -1 }[e.key];
        if (k) { e.preventDefault(); select((i + k + tabs.length) % tabs.length, true); }
      });
      $('.wk__prog', t).addEventListener('animationend', () => { if (i === cur) select((cur + 1) % tabs.length); });
    });

    $$('[data-cmp]', sec).forEach((cmp) => {
      const range = $('.cmp__range', cmp);
      const set = (v) => { cmp.style.setProperty('--dv', (v / 100).toFixed(4)); cmp.style.setProperty('--dt', (v / 100).toFixed(4)); };
      range.addEventListener('input', () => set(range.value));
      cmp.addEventListener('pointermove', (e) => {
        const r = cmp.getBoundingClientRect();
        cmp.style.setProperty('--cx', e.clientX - r.left + 'px');
        cmp.style.setProperty('--cy', e.clientY - r.top + 'px');
      });
      cmp.addEventListener('pointerdown', () => { dragging = true; cmp.classList.add('is-drag'); run(); });
      window.addEventListener('pointerup', () => { if (!dragging) return; dragging = false; cmp.classList.remove('is-drag'); run(); });
    });

    const stage = $('.wk__stage', sec);
    stage.addEventListener('pointerenter', () => { hovering = true; run(); });
    stage.addEventListener('pointerleave', () => { hovering = false; run(); });
    let first = true;
    new IntersectionObserver(([e]) => {
      visible = e.isIntersecting; run();
      if (visible && first) { first = false; intro(cases[cur]); }
    }, { threshold: 0.35 }).observe(stage);
  }

  /* ---------------- process + funnel ---------------- */
  function initFunnel() {
    const sec = $('[data-fn]');
    if (!sec) return;
    const steps = $$('[data-step]', sec);
    const bands = $$('[data-band]', sec);
    const set = (p) => {
      const act = Math.min(steps.length - 1, Math.floor(p * steps.length * 1.02));
      steps.forEach((s, i) => { s.classList.toggle('is-act', i === act); s.classList.toggle('is-done', i < act); });
      bands.forEach((b, i) => {
        const f = clamp((p * 1.3 - i * 0.24) / 0.3);
        b.style.setProperty('--f', f.toFixed(3));
        $('[data-band-v]', b).textContent = fmt(Number(b.dataset.val) * f);
      });
    };
    if (!motion) { set(1); return; }
    gsap.matchMedia()
      .add('(min-width: 961px)', () => { ScrollTrigger.create({ trigger: sec, start: 'top top', end: 'bottom bottom', onUpdate: (s) => set(s.progress) }); set(0); })
      .add('(max-width: 960px)', () => { ScrollTrigger.create({ trigger: sec, start: 'top 75%', end: 'bottom 70%', onUpdate: (s) => set(s.progress) }); set(0); });
  }

  /* ---------------- results board ---------------- */
  function initBoard() {
    const board = $('[data-board]');
    if (!board) return;
    const tabs = $$('[data-board-tab]', board);
    const show = (panel) => {
      panel.classList.remove('is-in'); void panel.offsetWidth; panel.classList.add('is-in');
      $$('.tile__v', panel).forEach((v) => countText(v, 1.6));
      if (motion) $$('.tile__ln', panel).forEach((l) => gsap.fromTo(l, { '--ld': 1 }, { '--ld': 0, duration: 1.6, ease: 'power2.out', delay: 0.2 }));
    };
    tabs.forEach((t) => t.addEventListener('click', () => {
      tabs.forEach((x) => { const on = x === t; x.classList.toggle('is-act', on); x.setAttribute('aria-selected', String(on)); });
      $$('[data-board-panel]', board).forEach((p) => { p.hidden = p.dataset.boardPanel !== t.dataset.boardTab; if (!p.hidden) show(p); });
    }));
    inView(board, () => show($('[data-board-panel]:not([hidden])', board)), { threshold: 0.3 });
  }

  /* ---------------- team ---------------- */
  function initTeam() {
    const sec = $('[data-tm]');
    if (!sec) return;
    const row = $('[data-tm-row]', sec);
    const ring = $('[data-tm-ring]', sec);
    const cards = $$('[data-mb]', sec);
    const out = $('[data-tm-count]', sec);
    const seen = new Set();
    const focus = (i, instant) => {
      if (i < 0) return;
      cards.forEach((c, k) => c.classList.toggle('is-lit', k === i));
      seen.add(i); cards[i].classList.add('is-seen');
      if (out) out.textContent = String(seen.size).padStart(2, '0');
      const ph = $('.mb__ph', cards[i]).getBoundingClientRect(), r = row.getBoundingClientRect();
      const x = ph.left - r.left + ph.width * 0.5, y = ph.top - r.top + ph.height * 0.45;
      if (motion && !instant) gsap.to(ring, { '--rx': x + 'px', '--ry': y + 'px', duration: 0.8, ease: 'power3.out' });
      else { ring.style.setProperty('--rx', x + 'px'); ring.style.setProperty('--ry', y + 'px'); }
    };
    cards.forEach((c, i) => c.addEventListener('pointerenter', () => focus(i)));
    inView(row, () => { sec.classList.add('is-on'); focus(0, true); }, { threshold: 0.3 });
    if (!fine) {
      const io = new IntersectionObserver((es) => es.forEach((e) => { if (e.isIntersecting) focus(cards.indexOf(e.target)); }), { threshold: 0.8 });
      cards.forEach((c) => io.observe(c));
    }
    window.addEventListener('resize', () => focus(cards.findIndex((c) => c.classList.contains('is-lit')), true));
  }

  /* ---------------- client stories phone ---------------- */
  function initStories() {
    const phone = $('[data-phone]');
    if (!phone) return;
    const vids = $$('[data-story]', phone);
    const bars = $$('.phone__bars b', phone);
    const co = $('[data-story-co]', phone);
    const sndBtn = $('[data-story-sound]', phone);
    let cur = 0, visible = false, sound = false, raf = 0;

    const tick = () => {
      const v = vids[cur];
      bars.forEach((b, i) => b.style.setProperty('--p', i < cur ? 1 : i > cur ? 0 : (v.duration ? v.currentTime / v.duration : 0)));
      raf = requestAnimationFrame(tick);
    };
    const play = () => {
      const v = vids[cur];
      v.muted = !sound;
      if (v.preload === 'none') { v.preload = 'auto'; v.load(); }
      const p = v.play();
      if (p) p.catch(() => {});
      cancelAnimationFrame(raf); raf = requestAnimationFrame(tick);
    };
    const go = (i) => {
      vids[cur].pause();
      cur = (i + vids.length) % vids.length;
      vids.forEach((v, k) => { v.classList.toggle('is-on', k === cur); if (k !== cur) v.currentTime = 0; });
      if (co) co.textContent = vids[cur].dataset.company || '';
      if (visible) play();
    };
    vids.forEach((v) => v.addEventListener('ended', () => go(cur + 1)));
    $('[data-story-next]', phone).addEventListener('click', () => go(cur + 1));
    $('[data-story-prev]', phone).addEventListener('click', () => go(cur - 1));
    sndBtn.addEventListener('click', () => {
      sound = !sound;
      phone.classList.toggle('is-sound', sound);
      sndBtn.setAttribute('aria-label', sound ? 'Mute' : 'Turn sound on');
      if (visible) play();
    });
    new IntersectionObserver(([e]) => {
      visible = e.isIntersecting;
      if (visible) play(); else { vids[cur].pause(); cancelAnimationFrame(raf); }
    }, { threshold: 0.45 }).observe(phone);
  }

  /* ---------------- FAQ ---------------- */
  function initFaq() {
    const list = $('[data-faq]');
    if (!list) return;
    const items = $$('[data-qa]', list);
    const rail = $('[data-faq-rail]', list);
    const sync = () => {
      const i = items.findIndex((q) => q.classList.contains('is-open'));
      items.forEach((q, k) => q.classList.toggle('is-past', i >= 0 && k < i));
      if (rail) rail.style.setProperty('--fr', i < 0 ? 0 : ((i + 0.5) / items.length).toFixed(3));
    };
    items.forEach((q) => $('.qa__q', q).addEventListener('click', () => {
      const open = !q.classList.contains('is-open');
      items.forEach((o) => { o.classList.remove('is-open'); $('.qa__q', o).setAttribute('aria-expanded', 'false'); });
      if (open) { q.classList.add('is-open'); $('.qa__q', q).setAttribute('aria-expanded', 'true'); }
      sync();
      setTimeout(refresh, 600);
    }));
    sync();
  }

  /* ---------------- clocks ---------------- */
  function initClocks() {
    const big = $('[data-clock]'), small = $('[data-clock-short]');
    const o = { timeZone: 'Asia/Kolkata', hour12: false };
    const tick = () => {
      const d = new Date();
      if (big) big.textContent = d.toLocaleTimeString('en-GB', { ...o, hour: '2-digit', minute: '2-digit', second: '2-digit' });
      if (small) small.textContent = d.toLocaleTimeString('en-GB', { ...o, hour: '2-digit', minute: '2-digit' });
    };
    tick(); setInterval(tick, 1000);
  }

  /* ---------------- footer wordmark ---------------- */
  function initWordmark() {
    const wm = $('[data-wm]');
    if (!wm) return;
    const t = $('.wm__t', wm);
    if (!fine || !motion) { t.style.setProperty('--fr', '18vw'); return; }
    let fx = 0.34, fy = 0.55, tx = fx, ty = fy, fr = 0, tr = 0;
    wm.addEventListener('pointermove', (e) => {
      const r = t.getBoundingClientRect();
      tx = (e.clientX - r.left) / r.width; ty = (e.clientY - r.top) / r.height; tr = Math.max(160, r.width * 0.14);
    });
    wm.addEventListener('pointerleave', () => { tr = 0; });
    whileVisible(wm, () => {
      fx += (tx - fx) * 0.12; fy += (ty - fy) * 0.12; fr += (tr - fr) * 0.1;
      t.style.setProperty('--fx', (fx * 100).toFixed(2) + '%');
      t.style.setProperty('--fy', (fy * 100).toFixed(2) + '%');
      t.style.setProperty('--fr', fr.toFixed(1) + 'px');
    });
  }

  /* ---------------- contact form ---------------- */
  function initForm() {
    const form = $('[data-contact-form]');
    if (!form) return;
    const st = $('[data-form-status]', form);
    const btn = $('button[type="submit"]', form);
    const say = (m, k) => { st.textContent = m; st.classList.toggle('is-ok', k === 'ok'); st.classList.toggle('is-bad', k === 'bad'); };
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      let first = null;
      $$('[required]', form).forEach((inp) => {
        const ok = inp.type === 'email' ? /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(inp.value.trim()) : inp.value.trim() !== '';
        inp.closest('.fld').classList.toggle('is-bad', !ok);
        inp.setAttribute('aria-invalid', String(!ok));
        if (!ok && !first) first = inp;
      });
      if (first) { say('Add your name, a valid email and a short message.', 'bad'); first.focus(); return; }
      btn.disabled = true; say('Sending…');
      try {
        const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } });
        const data = await res.json();
        say(data.message, data.ok ? 'ok' : 'bad');
        if (data.ok) form.reset();
      } catch (err) {
        say('Could not send right now. Please email or WhatsApp us instead.', 'bad');
      } finally { btn.disabled = false; }
    });
    $$('[required]', form).forEach((inp) => inp.addEventListener('input', () => { inp.closest('.fld').classList.remove('is-bad'); inp.removeAttribute('aria-invalid'); }));
  }

  /* ---------------- boot ---------------- */
  initSmooth();
  initHeader();
  initMagnetic();
  initReveals();
  initRadar();
  initCrowd();
  initServices();
  initWork();
  initFunnel();
  initBoard();
  initTeam();
  initStories();
  initFaq();
  initClocks();
  initWordmark();
  initForm();
  initHero((reveal) => runIntro(reveal));
  window.addEventListener('load', refresh);
})();
