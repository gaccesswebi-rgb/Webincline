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
    if (motion && window.Lenis && document.compatMode === 'CSS1Compat') {
      lenis = new Lenis({ lerp: 0.1, smoothWheel: true, wheelMultiplier: 1, syncTouch: false, gestureOrientation: 'vertical' });
      window.__lenis = lenis;
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
      if (lenis) lenis.scrollTo(target, { offset: -20, duration: 1.4 });
      else target.scrollIntoView({ behavior: motion ? 'smooth' : 'auto' });
      if (!target.hasAttribute('tabindex')) target.setAttribute('tabindex', '-1');
      target.focus({ preventScroll: true });
      history.replaceState(null, '', id);
    });
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
    'vec3 grey=mix(vec3(l)*vec3(.95,.95,1.),vec3(.043),.66);',
    'float reach=0.;float rim=0.;vec3 rc=vec3(0.);',
    'for(int i=0;i<8;i++){vec4 P=uP[i];if(P.w<=0.)continue;float age=uTime-P.z;if(age<0.)continue;',
    'float R=age*uDiag*(.2+.07*P.w);vec2 dv=fc-P.xy;float d=length(dv);float life=2.4+2.6*P.w;',
    'float fade=1.-smoothstep(life*.4,life,age);float band=uDiag*(.008+.006*P.w);',
    'float r=exp(-pow((d-R)/band,2.))*(1.-smoothstep(0.,life*.75,age));',
    'rc+=brand(atan(dv.y,dv.x)/6.2831+.5+uTime*.06)*r;rim+=r;',
    'reach=max(reach,(1.-smoothstep(R-band*5.,R+band,d))*fade);}',
    'float sp=(1.-smoothstep(uDiag*.035,uDiag*.12,distance(fc,uPtr.xy)))*uPtr.z;reach=max(reach,sp*.85);',
    'vec3 c=mix(grey,col*.58,reach);vec2 q=(s-vec2(.5,.46))*vec2(1.,1.35);c*=1.-.38*(1.-smoothstep(.12,.62,length(q)));c=mix(c,rc/max(rim,.001),clamp(rim,0.,1.)*.85);',
    'float vg=smoothstep(.55,1.,s.y)*.55+(1.-smoothstep(0.,.22,s.y))*.4;c*=1.-vg;',
    'gl_FragColor=vec4(c,1.);}',
  ].join('\n');

  // Spots (0–1 of the hero) where notification chips can pop.
  const SPOTS = [[.12, .2], [.2, .34], [.08, .5], [.16, .62], [.26, .74], [.1, .8], [.36, .2], [.62, .2], [.78, .14], [.9, .26], [.86, .4], [.92, .54], [.8, .66], [.72, .8], [.9, .78], [.3, .5], [.7, .5], [.5, .14], [.44, .82], [.58, .84]];

  function initHero() {
    const hero = $('[data-hero]');
    if (!hero) return;
    setTimeout(() => hero.classList.add('is-in'), 60);
    $$('[data-odo]', hero).forEach(odometer);
    if (!motion) return;

    const cv = $('[data-hero-gl]', hero);
    const img = $('.hero__img', hero);
    const pops = $('[data-hero-pops]', hero);
    const cur = $('[data-hero-cur]', hero);
    const texts = JSON.parse(hero.dataset.chips || '[]');
    const avoid = [$('.hero__in .note', hero), $('.hero__h', hero), $('.hero__sub', hero), $('.hero__cta', hero), $('.hero__foot', hero), $('.hero__hint', hero)].filter(Boolean);

    let gl = null;
    let W = 1, H = 1, dpr = 1, diag = 1, imgRatio = 1.5, ready = false;
    const t0 = performance.now();
    const now = () => (performance.now() - t0) / 1000;
    const pulses = [];
    const spotT = SPOTS.map(() => -99);
    let chipN = 0, chipLive = 0;
    const live = [];
    let px = 0, py = 0, tx = 0, ty = 0, hov = 0, lastMove = -99, lastPulse = -99, inside = false, scrollP = 0;
    let rects = [];

    const measure = () => {
      const r = hero.getBoundingClientRect();
      // phones draw the (soft, darkened) effect at 1x; it is not worth 3x the pixels
      W = r.width; H = r.height; dpr = Math.min(window.devicePixelRatio || 1, fine ? 1.5 : 1);
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
      // never stack a chip on one that is still showing
      if (live.some((c) => Math.abs(c[0] - x) < 150 && Math.abs(c[1] - y) < 44)) return;
      const spot = [x, y];
      live.push(spot);
      chipLive++;
      const el = document.createElement('span');
      el.className = 'pop';
      el.textContent = texts[chipN++ % texts.length];
      el.style.left = x + 'px'; el.style.top = y + 'px';
      pops.appendChild(el);
      requestAnimationFrame(() => requestAnimationFrame(() => el.classList.add('is-up')));
      setTimeout(() => el.classList.remove('is-up'), 2600);
      setTimeout(() => { el.remove(); chipLive--; live.splice(live.indexOf(spot), 1); }, 3200);
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

    // WebGL starts once the page has loaded and the browser is idle, so it never
    // competes with the first paint; until then the static photo is shown.
    const U = {};
    const startGL = () => {
      gl = cv.getContext('webgl', { antialias: false, alpha: false, premultipliedAlpha: false });
      if (!gl) return;
      measure();
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
    };
    const saveData = navigator.connection && navigator.connection.saveData;
    if (!saveData) {
      const idle = window.requestIdleCallback || ((f) => setTimeout(f, 200));
      const go = () => idle(startGL, { timeout: 1500 });
      if (document.readyState === 'complete') go(); else window.addEventListener('load', go, { once: true });
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
    // Only the latest blip shows its badge, so two badges never stack. The badge sits
    // above its dot unless that would cover another client's name; then it drops below.
    const overlaps = (a, c) => a.left < c.right && c.left < a.right && a.top < c.bottom && c.top < a.bottom;
    const place = (b) => {
      const bdg = $('.blip__bdg', b.el);
      const p = b.el.getBoundingClientRect();
      const w = bdg.offsetWidth, h = bdg.offsetHeight;
      const left = b.el.classList.contains('is-l') ? p.left + 14 - w : p.left - 14;
      const up = { left, right: left + w, top: p.top - 16 - h, bottom: p.top - 16 };
      b.el.classList.toggle('is-dn', blips.some((o) => o !== b && overlaps(up, $('.blip__nm', o.el).getBoundingClientRect())));
    };
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
          place(b);
          blips.forEach((o) => o.el.classList.toggle('is-top', o === b));
        }
        b.el.classList.toggle('is-hit', ts - b.t < 1600);
      });
      prev = a;
    });
  }

  /* ---------------- crowd panel ---------------- */
  // A grey crowd; as the panel scrolls in, 1 in 12 people leave it and pack
  // into four rings (search / social / lookalikes / AI), each in its brand colour.
  // Anchor positions come from CSS (--x / --y on each .crowd__tag) so the labels
  // and the clusters always line up, on every breakpoint.
  function initCrowd() {
    const panel = $('[data-crowd]');
    const cv = $('[data-crowd-cv]');
    if (!panel || !cv) return;
    const ctx = cv.getContext('2d');
    const ratio = $('[data-crowd-ratio]', panel);
    const total = $('[data-crowd-n]', panel);
    const tags = $$('[data-cluster]', panel);
    const COL = { search: '255,122,0', social: '255,61,129', ads: '123,77,255', ai: '58,141,255' };
    const SHARE = { search: 0.3, social: 0.3, ads: 0.2, ai: 0.2 };
    const ONE_IN = 12;
    const ease = (t) => { t = clamp(t); return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; };
    let W = 1, H = 1, N = 0, unit = 10, groups = [], crowd = [], people = [], G = motion ? 0 : 1, goal = G, mx = -1e4, my = -1e4, lastTxt = '';

    const build = () => {
      const dpr = Math.min(window.devicePixelRatio || 1, 2);
      W = Math.max(1, panel.clientWidth); H = Math.max(1, panel.clientHeight);
      cv.width = Math.round(W * dpr); cv.height = Math.round(H * dpr);
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      N = W < 560 ? 600 : W < 1000 ? 900 : 1200;
      unit = Math.min(W, H) * (W < 560 ? 0.021 : 0.019) + 2.2;
      let seed = 1207;
      const rnd = () => (seed = (seed * 16807) % 2147483647) / 2147483647;
      // people stand alone or in little groups of 2-4
      const all = [];
      while (all.length < N) {
        const x = rnd(), y = rnd(), k = rnd() < 0.3 ? 2 + Math.floor(rnd() * 3) : 1;
        for (let i = 0; i < k && all.length < N; i++) {
          all.push({ hx: clamp(x + (i ? (rnd() - 0.5) * 0.018 : 0), 0.01, 0.99) * W, hy: clamp(y + (i ? (rnd() - 0.5) * 0.03 : 0), 0.02, 0.98) * H, r: 1.25 + rnd() * 0.6, ph: rnd() * 6.283, ox: 0, oy: 0, lit: 0 });
        }
      }
      // every ONE_IN-th person is a buyer; buyers are dealt to the four groups by share
      groups = tags.map((t) => {
        const cs = getComputedStyle(t);
        const key = t.dataset.cluster;
        return { key, el: t, col: COL[key] || '255,255,255', ax: (parseFloat(cs.getPropertyValue('--x')) || 0.5) * W, ay: (parseFloat(cs.getPropertyValue('--y')) || 0.5) * H, m: [] };
      });
      const buyers = all.filter((_, i) => i % ONE_IN === 0);
      let acc = 0;
      const cuts = groups.map((g) => (acc += SHARE[g.key] || 1 / groups.length));
      buyers.forEach((b, i) => {
        const f = (i + 0.5) / buyers.length * acc;
        const g = groups[cuts.findIndex((c) => f <= c)];
        b.lag = rnd() * 0.42; b.bend = (rnd() - 0.5) * 0.5; b.j = g.m.length;
        g.m.push(b);
      });
      crowd = all.filter((_, i) => i % ONE_IN !== 0);
      people = all;
      if (total) total.textContent = fmt(N);
      lastTxt = '';
    };

    const text = () => {
      const e = ease(G);
      const n = fmt(N - (N - ONE_IN) * Math.pow(e, 0.6));
      if (ratio && n !== lastTxt) { ratio.textContent = n; lastTxt = n; }
      panel.style.setProperty('--o', clamp((G - 0.45) * 3).toFixed(3));
    };

    const draw = (ts) => {
      const t = (ts || 0) / 1000;
      const live = motion ? 1 : 0;
      if (motion) {
        const r = panel.getBoundingClientRect(), vh = window.innerHeight;
        goal = clamp(((vh - r.top) / (vh + r.height) - 0.14) / 0.36);
        G += (goal - G) * 0.12;
        if (Math.abs(goal - G) < 5e-4) G = goal;
      }
      const e = ease(G), span = Math.min(W, H);
      ctx.clearRect(0, 0, W, H);

      // 1. the crowd: dims and steps back from the rings
      const greyA = 0.62 - 0.4 * e;
      ctx.fillStyle = `rgba(225,225,235,${greyA.toFixed(3)})`;
      ctx.beginPath();
      const lit = [];
      for (const d of crowd) {
        let x = d.hx + Math.sin(t * 0.6 + d.ph) * 1.4 * live, y = d.hy + Math.cos(t * 0.5 + d.ph * 1.3) * 1.2 * live;
        let best = null, bd = 1e12;
        for (const g of groups) { const q = (x - g.ax) ** 2 + (y - g.ay) ** 2; if (q < bd) { bd = q; best = g; } }
        const dist = Math.sqrt(bd) || 1, push = e * Math.max(0, 1 - dist / (span * 0.55)) * span * 0.12;
        x += (x - best.ax) / dist * push + d.ox; y += (y - best.ay) / dist * push + d.oy;
        if (d.lit > 0.05) { lit.push(d, x, y); continue; }
        ctx.moveTo(x + d.r, y); ctx.arc(x, y, d.r, 0, 6.2832);
      }
      ctx.fill();
      for (let i = 0; i < lit.length; i += 3) {
        const d = lit[i];
        ctx.fillStyle = `rgba(255,255,255,${Math.min(1, greyA + d.lit * 0.7).toFixed(3)})`;
        ctx.beginPath(); ctx.arc(lit[i + 1], lit[i + 2], d.r * (1 + d.lit * 0.6), 0, 6.2832); ctx.fill();
      }

      // 2. a soft ring per group, drawn in once most buyers have arrived
      const k = ease((G - 0.55) / 0.45);
      if (k > 0) for (const g of groups) {
        if (!g.m.length) continue;
        const rr = unit * Math.sqrt(g.m.length) * 0.5 + 14 + (1 - k) * 30;
        ctx.beginPath(); ctx.arc(g.ax, g.ay, rr, 0, 6.2832);
        ctx.fillStyle = `rgba(${g.col},${(0.08 * k).toFixed(3)})`; ctx.fill();
        ctx.strokeStyle = `rgba(${g.col},${(0.6 * k).toFixed(3)})`; ctx.lineWidth = 1; ctx.stroke();
      }

      // 3. buyers: each leaves on its own beat, curves in, packs on a sunflower spiral
      for (const g of groups) for (const b of g.m) {
        const p = ease((G - b.lag) / 0.58);
        const sx = b.hx + Math.sin(t * 0.6 + b.ph) * 1.4 * live * (1 - p), sy = b.hy + Math.cos(t * 0.5 + b.ph * 1.3) * 1.2 * live * (1 - p);
        const a = b.j * 2.39996, rad = unit * 0.5 * Math.sqrt(b.j + 0.5);
        const tx = g.ax + Math.cos(a) * rad + Math.sin(t * 1.3 + b.ph) * 0.6 * live, ty = g.ay + Math.sin(a) * rad + Math.cos(t * 1.1 + b.ph) * 0.6 * live;
        const dx = tx - sx, dy = ty - sy, w = Math.sin(p * Math.PI) * b.bend;
        const x = sx + dx * p - dy * w + b.ox, y = sy + dy * p + dx * w + b.oy, r = b.r * (1 + 0.6 * p) * (1 + b.lit * 0.5);
        if (p < 0.999) { ctx.fillStyle = `rgba(225,225,235,${(0.62 * (1 - p)).toFixed(3)})`; ctx.beginPath(); ctx.arc(x, y, r, 0, 6.2832); ctx.fill(); }
        if (p > 0.001) {
          if (p > 0.05 && p < 0.95) { ctx.fillStyle = `rgba(${g.col},${(0.2 * p).toFixed(3)})`; ctx.beginPath(); ctx.arc(x, y, r * 2.6, 0, 6.2832); ctx.fill(); }
          ctx.fillStyle = `rgba(${g.col},${p.toFixed(3)})`; ctx.beginPath(); ctx.arc(x, y, r, 0, 6.2832); ctx.fill();
        }
      }
      text();
    };

    // pointer: people within 70px step aside and light up
    const nudge = () => {
      for (const d of people) {
        let tx = 0, ty = 0, tl = 0;
        const free = d.lag === undefined || G <= 0.5;
        if (free && mx > -1e3) {
          const dx = d.hx - mx, dy = d.hy - my, q = dx * dx + dy * dy;
          if (q < 4900) { const dd = Math.sqrt(q) || 1, f = 1 - dd / 70; tx = dx / dd * f * 16; ty = dy / dd * f * 16; tl = f; }
        }
        d.ox += (tx - d.ox) * 0.14; d.oy += (ty - d.oy) * 0.14; d.lit += (tl - d.lit) * (tl > d.lit ? 0.3 : 0.05);
      }
    };
    if (fine && motion) {
      panel.addEventListener('pointermove', (ev) => { const r = cv.getBoundingClientRect(); mx = ev.clientX - r.left; my = ev.clientY - r.top; });
      panel.addEventListener('pointerleave', () => { mx = my = -1e4; });
    }

    build();
    let rt;
    new ResizeObserver(() => { clearTimeout(rt); rt = setTimeout(() => { build(); if (!motion) draw(0); }, 150); }).observe(panel);
    if (motion) whileVisible(panel, (ts) => { nudge(); draw(ts); });
    else draw(0);
  }

  /* ---------------- services accordion ---------------- */
  /* The search-results mock: "your" result climbs from #5 to #3 to #1.
     Returns a function that stops the climb. */
  function serpClimb(box, play) {
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
    if (!play) return () => {};
    const timers = [setTimeout(() => moveTo(2), 1200), setTimeout(() => moveTo(0), 2500)];
    return () => timers.forEach(clearTimeout);
  }

  function initServices() {
    // the side-by-side accordion needs 1280px; narrower screens get stacked panels (see CSS)
    const wide = () => window.matchMedia('(min-width: 1280px)').matches;
    const acc = $('[data-acc]');
    if (!acc) return;
    const pns = $$('[data-pn]', acc);
    const segs = $$('.sv__seg i');
    const idx = $('[data-sv-idx]');
    const name = $('[data-sv-name]');
    let cur = 0, hoverT = 0, pauseUntil = 0, visible = false, stopSerp = () => {};

    const setWidth = () => {
      if (!wide()) return;
      const cw = parseFloat(getComputedStyle(acc).getPropertyValue('--cw')) || 92;
      acc.style.setProperty('--ow', Math.max(320, acc.clientWidth - (pns.length - 1) * (cw + 8) - cw) + 'px');
    };

    const serp = (pn) => {
      stopSerp();
      const box = $('[data-serp]', pn);
      if (box) stopSerp = serpClimb(box, pn.classList.contains('is-open'));
    };

    const open = (i, user) => {
      if (i < 0 || i >= pns.length) return;
      if (!wide() && user && i === cur && pns[i].classList.contains('is-open')) {
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
      if (!wide()) setTimeout(refresh, 850);
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
        if (!fine || !wide() || (!e.movementX && !e.movementY)) return;
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
        if (!visible || !wide() || document.hidden || performance.now() < pauseUntil || acc.matches(':hover')) return;
        open((cur + 1) % pns.length);
      }, 4800);
    }
  }

  /* ---------------- inner pages ---------------- */
  // Services menu in the header: opens on hover with a mouse, or with the arrow button.
  function initMega() {
    const li = $('[data-mega]');
    if (!li) return;
    const btn = $('[data-mega-toggle]', li);
    let t = 0;
    const isOpen = () => li.classList.contains('is-open');
    const set = (open) => {
      clearTimeout(t);
      li.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', String(open));
    };
    btn.addEventListener('click', () => set(!isOpen()));
    if (fine) {
      li.addEventListener('pointerenter', () => { clearTimeout(t); t = setTimeout(() => set(true), 90); });
      li.addEventListener('pointerleave', () => { clearTimeout(t); t = setTimeout(() => set(false), 220); });
    }
    li.addEventListener('focusout', (e) => { if (!li.contains(e.relatedTarget)) set(false); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && isOpen()) { set(false); btn.focus(); } });
    document.addEventListener('click', (e) => { if (isOpen() && !li.contains(e.target)) set(false); });
    window.addEventListener('scroll', () => { if (isOpen() && !li.matches(':hover')) set(false); }, { passive: true });
  }

  // Page hero search card: types a search, then "your" result climbs to #1. Loops while on screen.
  function initPageHero() {
    const card = $('[data-ph-serp]');
    if (!card || !motion) return;
    const q = $('[data-ph-q]', card);
    const step = $('[data-ph-step]', card);
    const rows = $$('[data-ph-row]', card);
    const you = $('[data-ph-you]', card);
    const rank = $('[data-ph-rank]', card);
    const others = rows.filter((r) => r !== you);
    const SEARCHES = [
      ['plumber near me', ['homeadvisor.com', 'yelp.com › plumbers', 'angi.com', 'reddit.com › r/homeowners']],
      ['study abroad consultants', ['idp.com', 'leverageedu.com', 'shiksha.com', 'quora.com › study-abroad']],
      ['robot lawn mower australia', ['bunnings.com.au', 'productreview.com.au', 'choice.com.au', 'amazon.com.au']],
    ];
    let k = 0, timers = [], on = false;
    const later = (f, ms) => timers.push(setTimeout(f, ms));
    const placeYou = (pos) => {
      let j = 0;
      others.forEach((r) => { if (j === pos) j++; r.style.setProperty('--i', j); j++; });
      you.style.setProperty('--i', pos);
      rank.textContent = '#' + (pos + 1);
    };
    const run = () => {
      timers.forEach(clearTimeout); timers = [];
      const [text, domains] = SEARCHES[k % SEARCHES.length];
      step.textContent = 'Search ' + (k % SEARCHES.length + 1) + ' of ' + SEARCHES.length;
      card.classList.remove('is-done');
      others.forEach((r, i) => { $('[data-ph-d]', r).textContent = domains[i]; });
      placeYou(4);
      q.textContent = '';
      [...text].forEach((ch, i) => later(() => { q.textContent += ch; }, 300 + i * 55));
      const typed = 300 + text.length * 55;
      later(() => placeYou(2), typed + 900);
      later(() => placeYou(0), typed + 2000);
      later(() => card.classList.add('is-done'), typed + 2600);
      later(() => { k++; run(); }, typed + 6200);
    };
    new IntersectionObserver(([e]) => {
      if (e.isIntersecting && !on) { on = true; run(); }
      else if (!e.isIntersecting && on) { on = false; timers.forEach(clearTimeout); timers = []; }
    }).observe(card);
  }

  // Services hub cards: each mock-up plays when its card scrolls in, and again on hover.
  function initSvcGrid() {
    const cards = $$('[data-sc]');
    if (!cards.length) return;
    cards.forEach((card) => {
      let stop = () => {};
      const play = () => {
        stop();
        card.classList.remove('is-live');
        void card.offsetWidth; // restart the CSS animations
        card.classList.add('is-live');
        const box = $('[data-serp]', card);
        if (box) stop = serpClimb(box, motion);
      };
      inView(card, play, { threshold: 0.35 });
      if (fine) card.addEventListener('pointerenter', play);
    });
    // "Ask about …" buttons pick that service in the contact form
    $$('[data-pick]').forEach((a) => a.addEventListener('click', () => {
      const sel = $('#f-need');
      if (sel) sel.value = a.dataset.pick;
    }));
  }

  // Markets list: local time in each market, with a lit dot during office hours there.
  function initMarkets() {
    const box = $('[data-mk]');
    if (!box) return;
    const fmt = (tz, o) => new Intl.DateTimeFormat('en-GB', Object.assign({ timeZone: tz }, o));
    const hm = { hour: '2-digit', minute: '2-digit', hour12: false };
    const rows = $$('[data-tz]', box).map((li) => ({ li, out: $('[data-mk-t]', li), time: fmt(li.dataset.tz, hm), day: fmt(li.dataset.tz, { weekday: 'short' }) }));
    const home = $('[data-mk-home]', box);
    const homeTime = fmt('Asia/Kolkata', hm);
    const tick = () => {
      const d = new Date();
      if (home) home.textContent = homeTime.format(d);
      rows.forEach((r) => {
        const t = r.time.format(d);
        const h = parseInt(t, 10);
        r.out.textContent = t;
        r.li.classList.toggle('is-open', h >= 9 && h < 18 && !/Sat|Sun/.test(r.day.format(d)));
      });
    };
    tick();
    setInterval(tick, 20000);
  }

  /* ---------------- case studies ---------------- */
  // Scrolling moves the divider from "mostly before" to "mostly after"; the wave
  // chart grows and the AFTER number counts from the before value to the after value
  // with it. Changing tab replays the sweep. Dragging takes over until the case
  // leaves the screen.
  function initWork() {
    const sec = $('[data-wk]');
    if (!sec) return;
    const tabs = $$('.wk__tab', sec);
    const cases = $$('.wk__case', sec);
    const stage = $('.wk__stage', sec);
    const FROM = 0.86, TO = 0.14; // share of the frame still showing BEFORE
    const S = { mode: 'scroll', dv: FROM, man: 0.5, p: 0 };
    let cur = 0, near = false, visible = false, hovering = false, dragging = false, last = -1;

    const parts = cases.map((c) => {
      const cmp = $('[data-cmp]', c), num = $('.cmp__a b', cmp);
      return { cmp, num, range: $('.cmp__range', cmp), from: Number(num.dataset.from) || 0, to: Number(num.dataset.count) || 0 };
    });
    const paint = (dv, force) => {
      if (!force && Math.abs(dv - last) < 4e-4) return;
      last = dv;
      const e = parts[cur], dt = clamp((FROM - dv) / (FROM - TO));
      e.cmp.style.setProperty('--dv', dv.toFixed(4));
      e.cmp.style.setProperty('--dt', dt.toFixed(4));
      e.range.value = Math.round(dv * 100);
      e.num.textContent = fmt(e.from + (e.to - e.from) * dt);
    };
    // the 7s auto-advance only runs once the sweep has been seen
    const run = () => sec.classList.toggle('is-run', motion && visible && !hovering && !dragging && (S.mode !== 'scroll' || S.p > 0.94));

    const select = (i, focus) => {
      if (i === cur) return;
      cur = i;
      tabs.forEach((t, k) => {
        const on = k === i;
        t.setAttribute('aria-selected', String(on));
        t.tabIndex = on ? 0 : -1;
        const pr = $('.wk__prog', t);
        pr.style.animation = 'none'; void pr.offsetWidth; pr.style.animation = '';
      });
      cases.forEach((c, k) => { const on = k === i; c.hidden = !on; c.classList.toggle('is-on', on); });
      if (focus) tabs[i].focus();
      if (!motion) { paint(0.5, true); return; }
      gsap.killTweensOf(S);
      S.mode = 'replay';
      paint(FROM, true);
      gsap.fromTo(S, { dv: FROM }, { dv: TO, duration: 1.7, ease: 'power2.inOut', onComplete: () => { if (S.mode === 'replay') { S.mode = 'hold'; run(); } } });
      run();
    };

    tabs.forEach((t, i) => {
      t.addEventListener('click', () => select(i));
      t.addEventListener('keydown', (e) => {
        const k = { ArrowRight: 1, ArrowLeft: -1 }[e.key];
        if (k) { e.preventDefault(); select((i + k + tabs.length) % tabs.length, true); }
      });
      $('.wk__prog', t).addEventListener('animationend', () => { if (i === cur) select((cur + 1) % tabs.length); });
    });

    parts.forEach(({ cmp, range }) => {
      range.addEventListener('input', () => {
        if (hasGSAP) gsap.killTweensOf(S);
        S.mode = 'manual'; S.man = range.value / 100; S.dv = S.man; paint(S.dv); run();
      });
      // Pointer dragging. A mouse grabs straight away; a finger only takes over once it
      // clearly moves sideways, so a vertical swipe on the frame still scrolls the page.
      let ptr = null;
      const setFromX = (x) => {
        const r = cmp.getBoundingClientRect();
        S.man = clamp((x - r.left) / r.width, 0.03, 0.97);
        S.dv = S.man;
        paint(S.dv);
      };
      const grab = (e) => {
        ptr.on = true;
        dragging = true;
        cmp.classList.add('is-drag');
        try { cmp.setPointerCapture(e.pointerId); } catch (err) { /* pointer already gone */ }
        if (hasGSAP) gsap.killTweensOf(S);
        S.mode = 'manual';
        setFromX(e.clientX);
        run();
      };
      const release = () => {
        ptr = null;
        if (!dragging) return;
        dragging = false;
        cmp.classList.remove('is-drag');
        run();
      };
      cmp.addEventListener('pointerdown', (e) => {
        if (e.button !== 0) return;
        ptr = { id: e.pointerId, x: e.clientX, y: e.clientY, on: false };
        if (e.pointerType === 'mouse') grab(e);
      });
      cmp.addEventListener('pointermove', (e) => {
        const r = cmp.getBoundingClientRect();
        cmp.style.setProperty('--cx', e.clientX - r.left + 'px');
        cmp.style.setProperty('--cy', e.clientY - r.top + 'px');
        if (!ptr || e.pointerId !== ptr.id) return;
        if (!ptr.on) {
          const dx = e.clientX - ptr.x, dy = e.clientY - ptr.y;
          if (Math.abs(dx) > 8 && Math.abs(dx) > Math.abs(dy)) grab(e);
          else if (Math.abs(dy) > 8) ptr = null;
          return;
        }
        setFromX(e.clientX);
      });
      window.addEventListener('pointerup', release);
      window.addEventListener('pointercancel', release);
    });
    stage.addEventListener('pointerenter', (e) => { if (e.pointerType === 'mouse') { hovering = true; run(); } });
    stage.addEventListener('pointerleave', () => { hovering = false; run(); });

    if (!motion) { paint(0.5, true); return; }

    // The sweep starts when the frame's top reaches 88% of the screen and ends one
    // "frame height + 18vh" later (at most 76vh).
    ScrollTrigger.create({
      trigger: stage,
      start: 'top 88%',
      end: () => '+=' + Math.max(80, Math.min(innerHeight * 0.76, innerHeight * 0.18 + parts[cur].cmp.offsetHeight)),
      onUpdate: (st) => { S.p = st.progress; run(); },
      invalidateOnRefresh: true,
    });
    new IntersectionObserver(([e]) => {
      near = e.isIntersecting;
      visible = e.intersectionRatio >= 0.35;
      if (!near && S.mode !== 'scroll') { gsap.killTweensOf(S); S.mode = 'scroll'; }
      run();
    }, { threshold: [0, 0.35, 0.6], rootMargin: '10% 0px 10% 0px' }).observe(stage);

    gsap.ticker.add(() => {
      if (!near && S.mode === 'scroll' && last >= 0) return;
      if (S.mode === 'scroll') {
        const goal = FROM + (TO - FROM) * S.p;
        S.dv += (goal - S.dv) * 0.2;
        if (Math.abs(goal - S.dv) < 6e-4) S.dv = goal;
      } else if (S.mode === 'hold') S.dv = TO;
      else if (S.mode === 'manual') S.dv = S.man;
      paint(S.dv);
    });
    paint(FROM, true);
  }

  /* ---------------- process + funnel ---------------- */
  // Scroll fills the funnel one stage at a time. Particles pour in at the top and
  // fall through the stages that are open so far; most drop out at each stage, so
  // Reach is crowded, Clicks thinner, Leads sparse and only a few reach Customers.
  function initFunnel() {
    const sec = $('[data-fn]');
    if (!sec) return;
    const steps = $$('[data-step]', sec);
    const bands = $$('[data-band]', sec);
    const fun = $('[data-funnel]', sec);
    const cv = $('[data-fn-cv]', sec);
    const N = bands.length;
    const PASS = [0.34, 0.3, 0.36];
    const inset = bands.map((b) => parseFloat(b.style.getPropertyValue('--a')) / 100);
    inset.push(parseFloat(bands[N - 1].style.getPropertyValue('--b')) / 100);
    const ctr = bands.map(() => ({ v: 0, tw: null }));
    const num = (i) => { $('[data-band-v]', bands[i]).textContent = fmt(ctr[i].v); };
    let n = 0, act = -2;

    const set = (p) => {
      n = clamp((p - 0.04) / 0.86) * N;
      bands.forEach((b, i) => b.style.setProperty('--f', clamp((n - i) * 1.25).toFixed(4)));
      const a = n <= 0.02 ? -1 : Math.min(N - 1, Math.floor(n));
      if (a !== act) {
        act = a;
        bands.forEach((b, i) => {
          const on = i <= a, c = ctr[i];
          if (on !== b.classList.contains('is-on')) {
            if (c.tw) c.tw.kill();
            if (on) c.tw = gsap.to(c, { v: Number(b.dataset.val), duration: 1.4, ease: 'power3.out', onUpdate: () => num(i) });
            else { c.v = 0; num(i); }
          }
          b.classList.toggle('is-on', on);
          b.classList.toggle('is-act', i === a);
        });
      }
      const sa = Math.min(steps.length - 1, Math.floor((Math.max(0, n) / N) * steps.length));
      steps.forEach((s, i) => { s.classList.toggle('is-act', i === sa); s.classList.toggle('is-done', i < sa); });
    };

    if (!motion) {
      bands.forEach((b) => { b.classList.add('is-on'); b.style.setProperty('--f', 1); });
      steps.forEach((s) => s.classList.add('is-done'));
      if (cv) cv.hidden = true;
      return;
    }
    bands.forEach((b, i) => { ctr[i].v = 0; num(i); });

    // particles
    const ctx = cv.getContext('2d');
    let W = 1, H = 1, dpr = 1, rows = [], parts = [], acc = 0, seen = false;
    const measure = () => {
      dpr = Math.min(window.devicePixelRatio || 1, 2);
      W = fun.clientWidth; H = fun.clientHeight;
      cv.width = Math.round(W * dpr); cv.height = Math.round(H * dpr);
      const r0 = fun.getBoundingClientRect();
      rows = bands.map((b) => { const r = b.getBoundingClientRect(); return [r.top - r0.top, r.bottom - r0.top]; });
    };
    // funnel wall (fraction of the width) at height y
    const edge = (y) => {
      for (let i = 0; i < rows.length; i++) {
        const [t, bt] = rows[i];
        if (y <= bt) return y < t ? inset[i] : inset[i] + (inset[i + 1] - inset[i]) * ((y - t) / Math.max(1, bt - t));
      }
      return inset[rows.length];
    };
    const tick = (time, deltaMs) => {
      if (!seen || !rows.length) return;
      const dt = Math.min(0.05, deltaMs / 1000);
      const gate = Math.min(N - 1, Math.floor(Math.max(0, n)));
      const full = n >= N - 0.02;
      for (acc += dt * (26 + 30 * clamp(n)); acc > 1; acc--) {
        parts.push({ u: 0.04 + Math.random() * 0.92, y: rows[0][0] - 6, v: 70 + Math.random() * 60, k: 0, die: 0, r: 1.6 + Math.random() * 1.2, ph: Math.random() * 6.283 });
      }
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      ctx.clearRect(0, 0, W, H);
      for (let j = parts.length - 1; j >= 0; j--) {
        const q = parts[j];
        q.y += q.v * dt * (1 + q.k * 0.25);
        q.u += Math.sin(time * 2 + q.ph) * 0.054 * dt + (0.5 - q.u) * dt * 0.08 * Math.min(q.k, 3);
        const row = rows[Math.min(q.k, N - 1)];
        if (!q.die && q.y > row[1]) {
          // a particle only moves on into a stage that is already open, and most drop out
          if (q.k >= (full ? N - 1 : gate)) q.die = 0.001;
          else if (Math.random() < PASS[q.k]) q.k++;
          else q.die = 0.001;
        }
        if (q.die) q.die += dt * 2.2;
        if (q.die > 1 || q.y > H + 10) { parts.splice(j, 1); continue; }
        const e = edge(q.y);
        const x = W * (e + q.u * (1 - 2 * e));
        const al = (1 - q.die) * (0.35 + 0.15 * Math.min(3, q.k));
        ctx.beginPath();
        if (q.k >= N - 1) {
          const o = Math.min(1, al + 0.4);
          // pink with a dark edge so it reads on the gradient fill
          ctx.arc(x, q.y, q.r + 1.6, 0, 6.283);
          ctx.fillStyle = 'rgba(255,61,129,' + o + ')'; ctx.fill();
          ctx.lineWidth = 1.5; ctx.strokeStyle = 'rgba(17,17,17,' + o * 0.85 + ')'; ctx.stroke();
        } else {
          ctx.arc(x, q.y, q.r + q.k * 0.3, 0, 6.283);
          ctx.fillStyle = 'rgba(17,17,17,' + al + ')'; ctx.fill();
        }
      }
      if (parts.length > 420) parts.splice(0, parts.length - 420);
    };
    measure();
    new ResizeObserver(measure).observe(fun);
    new IntersectionObserver(([en]) => {
      seen = en.isIntersecting;
      if (!seen) { parts = []; ctx.clearRect(0, 0, W, H); }
    }, { rootMargin: '10% 0px' }).observe(fun);
    gsap.ticker.add(tick);

    gsap.matchMedia()
      .add('(min-width: 1100px) and (min-height: 760px)', () => {
        ScrollTrigger.create({ trigger: sec, start: 'top top', end: 'bottom bottom', invalidateOnRefresh: true, onUpdate: (st) => set(st.progress), onRefresh: (st) => { measure(); set(st.progress); } });
      })
      .add('(max-width: 1099px), (max-height: 759px)', () => {
        ScrollTrigger.create({ trigger: fun, start: 'top 85%', end: 'bottom 45%', invalidateOnRefresh: true, onUpdate: (st) => set(st.progress), onRefresh: (st) => { measure(); set(st.progress); } });
      });
    set(0);
  }

  /* ---------------- results board ---------------- */
  // Sparklines draw on with scroll (both ways), like the reference. Hovering snaps a
  // dot to the nearest month; only the two end points carry real numbers.
  function initBoard() {
    const board = $('[data-board]');
    if (!board) return;
    const sec = board.closest('section') || board;
    const tabs = $$('[data-board-tab]', board);
    const show = (panel) => {
      panel.classList.remove('is-in'); void panel.offsetWidth; panel.classList.add('is-in');
      $$('.tile__v', panel).forEach((v) => countText(v, 1.6));
    };
    tabs.forEach((t) => t.addEventListener('click', () => {
      tabs.forEach((x) => { const on = x === t; x.classList.toggle('is-act', on); x.setAttribute('aria-selected', String(on)); });
      $$('[data-board-panel]', board).forEach((p) => {
        p.hidden = p.dataset.boardPanel !== t.dataset.boardTab;
        if (p.hidden) return;
        show(p);
        refresh();
        if (motion) {
          // draw the new tab's lines in, then hand back to the scroll-driven value
          const to = parseFloat(board.style.getPropertyValue('--sp')) || 1;
          const o = { v: 0.2 };
          gsap.to(o, { v: to, duration: 1.4, ease: 'power2.out', onUpdate: () => p.style.setProperty('--sp', o.v.toFixed(4)), onComplete: () => p.style.removeProperty('--sp') });
        }
      });
    }));
    inView(board, () => show($('[data-board-panel]:not([hidden])', board)), { threshold: 0.3 });

    if (motion) {
      // --sp: 0 when the section's top meets the bottom of the screen, 1 when its bottom leaves the top
      let goal = 0, cur = -1, near = false;
      ScrollTrigger.create({ trigger: sec, start: 'top bottom', end: 'bottom top', onUpdate: (st) => { goal = st.progress; }, onToggle: (st) => { near = st.isActive; } });
      gsap.ticker.add(() => {
        if (!near && cur === goal) return;
        cur = cur < 0 ? goal : cur + (goal - cur) * 0.18;
        if (Math.abs(goal - cur) < 5e-4) cur = goal;
        board.style.setProperty('--sp', cur.toFixed(4));
      });
      // phones stack the tiles 2x2, so each sparkline follows its own position instead
      gsap.matchMedia().add('(max-width: 640px)', () => {
        const undo = $$('.tile__sp', board).map((sp) => {
          const i0 = sp.style.getPropertyValue('--i');
          sp.style.setProperty('--i', 0);
          const put = (st) => sp.style.setProperty('--sp', (0.2 + st.progress * 0.6).toFixed(4));
          ScrollTrigger.create({ trigger: sp, start: 'top 95%', end: 'top 55%', onUpdate: put, onRefresh: put });
          return () => { sp.style.removeProperty('--sp'); sp.style.setProperty('--i', i0); };
        });
        return () => undo.forEach((f) => f());
      });
    }

    $$('.tile__sp', board).forEach((sp) => {
      const path = $('.tile__ln', sp), svg = $('svg', sp), pt = $('.tile__pt', sp), tt = $('.tile__tt', sp);
      const ys = (path.getAttribute('d').match(/-?[\d.]+ -?[\d.]+/g) || []).map((q) => Number(q.split(' ')[1]));
      const n = ys.length;
      if (n < 2) return;
      svg.addEventListener('pointermove', (e) => {
        const r = svg.getBoundingClientRect();
        const i = clamp(Math.round(((e.clientX - r.left) / r.width) * (n - 1)), 0, n - 1);
        pt.hidden = tt.hidden = false;
        pt.style.left = tt.style.left = (i / (n - 1)) * 100 + '%';
        pt.style.top = tt.style.top = ((ys[i] + 4) / 88) * 100 + '%';
        tt.textContent = i === 0 ? 'Before · ' + sp.dataset.from : i === n - 1 ? 'After · ' + sp.dataset.to : 'Month ' + (i + 1);
      });
      svg.addEventListener('pointerleave', () => { pt.hidden = tt.hidden = true; });
    });
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

  /* ---------------- client stories: lock-screen notifications ---------------- */
  // As the phone scrolls up the screen, reviews arrive one by one on top and push the
  // older ones down; after three, the oldest fold into a pile. Scrolling back removes them.
  function initReviews() {
    const lock = $('[data-reviews]');
    if (!lock) return;
    const cards = $$('[data-tn]', lock);
    const N = cards.length;
    const nEl = $('[data-lk-n]', lock), lbl = $('[data-lk-label]', lock);
    const AGO = ['now', '2m ago', '9m ago', '26m ago', '1h ago', '3h ago'];
    const stack = $('[data-lk-stack]', lock);
    const small = window.matchMedia('(max-width: 809px)');
    let count = -1, want = 0, frozen = false, step = 168;

    // every card gets the height of the tallest one, so the stack steps evenly
    const measure = () => {
      cards.forEach((el) => { el.style.height = ''; });
      const h = Math.max(...cards.map((el) => el.offsetHeight));
      cards.forEach((el) => { el.style.height = h + 'px'; });
      step = h + (small.matches ? 8 : 18);
      stack.style.height = step * 2 + 30 + h + 'px';
    };

    const set = (c, force) => {
      want = c;
      if (frozen || (c === count && !force)) return;
      const grew = c > count;
      count = c;
      cards.forEach((el, i) => {
        let y = -70, sc = 0.9, op = 0, z = 20, r = -1;
        if (i < c) {
          r = c - 1 - i;
          const k = Math.max(0, r - 2);
          y = Math.min(r, 2) * step + Math.min(k, 3) * 12;
          sc = 1 - Math.min(k, 3) * 0.045;
          op = k > 2 ? 0 : 1 - k * 0.28;
          z = 20 - r;
        }
        el.style.transform = 'translate3d(0,' + y + 'px,0) scale(' + sc.toFixed(3) + ')';
        el.style.opacity = String(op);
        el.style.zIndex = String(z);
        el.style.pointerEvents = op === 0 ? 'none' : '';
        el.inert = op === 0;
        const live = r >= 0 && r <= 2;
        el.tabIndex = live ? 0 : -1;
        el.setAttribute('aria-hidden', String(!live));
        const t = $('[data-tn-t]', el);
        if (t && r >= 0) t.textContent = AGO[Math.min(r, AGO.length - 1)];
        if (r === 0 && grew) { el.classList.remove('is-new'); void el.offsetWidth; el.classList.add('is-new'); }
        else if (r !== 0) el.classList.remove('is-new');
      });
      if (nEl) nEl.textContent = String(c);
      if (lbl) lbl.textContent = c === 1 ? 'New message' : 'New messages';
    };

    // the lock screen shows the time and date in Chandigarh
    const clock = () => {
      const now = new Date();
      const t = new Intl.DateTimeFormat('en-GB', { timeZone: 'Asia/Kolkata', hour: '2-digit', minute: '2-digit', hour12: false }).format(now);
      const d = new Intl.DateTimeFormat('en-GB', { timeZone: 'Asia/Kolkata', weekday: 'long', day: 'numeric', month: 'long' }).format(now).replace(',', '');
      $$('[data-lk-time], [data-lk-clock]', lock).forEach((el) => { el.textContent = t; });
      const de = $('[data-lk-date]', lock);
      if (de) de.textContent = d;
    };
    clock();
    setInterval(clock, 30000);

    // keyboard users can read a card without the stack moving under them
    lock.addEventListener('focusin', (e) => { if (e.target.matches(':focus-visible')) frozen = true; });
    lock.addEventListener('focusout', (e) => { if (!lock.contains(e.relatedTarget)) { frozen = false; set(want, true); } });

    measure();
    if (!motion) { set(N); return; }
    set(0);
    // measured from the section (the lock itself slides in on reveal, which would skew the start)
    const sec = lock.closest('section') || lock.parentElement;
    const at = (pc) => () => 'top+=' + Math.round(lock.offsetTop) + ' ' + pc;
    const fromScroll = (st) => set(clamp(Math.floor(st.progress * N + 0.35), 0, N));
    ScrollTrigger.create({ trigger: sec, start: at('90%'), end: at('28%'), invalidateOnRefresh: true, onUpdate: fromScroll, onRefresh: fromScroll });
    const remeasure = () => { measure(); set(want, true); };
    small.addEventListener('change', remeasure);
    let rt = 0;
    window.addEventListener('resize', () => { clearTimeout(rt); rt = setTimeout(remeasure, 150); });
    if (document.fonts) document.fonts.ready.then(remeasure);
  }

  /* ---------------- video stories: 3D coverflow ---------------- */
  function initVideos() {
    const sec = $('[data-vs]');
    if (!sec) return;
    const stage = $('[data-vs-stage]', sec);
    const ring = $('[data-vs-ring]', sec);
    const cards = $$('[data-vc]', sec);
    const vids = cards.map((c) => $('video', c));
    const idx = $('[data-vs-idx]', sec);
    const N = cards.length;
    if (!N) return;
    const small = window.matchMedia('(max-width: 640px)');
    // pos is a float index: 0 = first card in front. spread fans the cards out on scroll.
    const st = { pos: 0, spread: motion ? 0 : 1, sy: 0, tx: 0, ty: 0 };
    let active = 0, visible = false, sound = false, drag = null, raf = 0;

    let lastKey = '';
    const render = (force) => {
      const key = [st.pos, st.spread, st.sy, st.tx, st.ty, small.matches].join();
      if (force !== true && key === lastKey) return;
      lastKey = key;
      const w = cards[0].offsetWidth || 260;
      const m = small.matches, k = m ? 0.6 : 0.8;
      const vm = Math.min(2.4, N / 2 - 0.05);
      cards.forEach((c, i) => {
        let o = ((i - st.pos) % N + N) % N;
        if (o > N / 2) o -= N;
        const a = Math.abs(o), sg = Math.sign(o);
        const x = sg * (Math.min(a, 1) * w * k + Math.max(a - 1, 0) * w * k * 0.55) * st.spread;
        const z = -a * (m ? 150 : 230) * st.spread - a * 6;
        const ry = -clamp(o, -2, 2) * (m ? 32 : 28) * st.spread;
        const sc = 1 - Math.min(a, 2) * 0.05;
        const op = a <= vm - 0.35 ? 1 : clamp((vm - a) / 0.35);
        c.style.transform = 'translate3d(' + x.toFixed(1) + 'px,0,' + z.toFixed(1) + 'px) rotateY(' + ry.toFixed(2) + 'deg) scale(' + sc.toFixed(3) + ')';
        c.style.opacity = op.toFixed(3);
        c.style.visibility = op < 0.01 ? 'hidden' : '';
        c.style.zIndex = String(100 - Math.round(a * 10));
        c.style.setProperty('--dim', (Math.min(a, 1.2) * 0.5).toFixed(3));
      });
      ring.style.transform = 'rotateX(' + st.tx.toFixed(2) + 'deg) rotateY(' + (st.ty + st.sy).toFixed(2) + 'deg)';
    };

    const tick = () => {
      const v = vids[active];
      cards[active].style.setProperty('--p', v.duration ? (v.currentTime / v.duration).toFixed(4) : 0);
      raf = requestAnimationFrame(tick);
    };
    const play = () => {
      const v = vids[active];
      v.muted = !sound;
      if (v.preload === 'none') { v.preload = 'auto'; v.load(); }
      const p = v.play();
      if (p) p.catch(() => {});
      cancelAnimationFrame(raf); raf = requestAnimationFrame(tick);
    };
    const stop = () => { vids[active].pause(); cancelAnimationFrame(raf); };
    vids.forEach((v, i) => {
      v.addEventListener('playing', () => cards[i].classList.add('is-playing'));
      v.addEventListener('pause', () => cards[i].classList.remove('is-playing'));
      v.addEventListener('ended', () => go(active + 1));
    });

    const setActive = (n) => {
      const next = ((n % N) + N) % N;
      if (next !== active) {
        stop();
        vids[active].currentTime = 0;
        cards[active].style.setProperty('--p', 0);
      }
      active = next;
      cards.forEach((c, i) => {
        c.classList.toggle('is-on', i === active);
        c.classList.toggle('is-sound', i === active && sound);
      });
      if (idx) idx.textContent = String(active + 1).padStart(2, '0');
      if (visible) play();
    };
    // Move to card n along the shortest way round the loop.
    const go = (n) => {
      let target = n;
      const base = Math.round(st.pos);
      const want = ((n % N) + N) % N;
      let d = want - (((base % N) + N) % N);
      if (d > N / 2) d -= N;
      if (d < -N / 2) d += N;
      target = base + d;
      setActive(want);
      if (!motion) { st.pos = target; render(); return; }
      gsap.to(st, { pos: target, duration: 1, ease: 'expo.out', onUpdate: render, overwrite: 'auto' });
    };

    $('[data-vs-next]', sec).addEventListener('click', () => go(active + 1));
    sec.addEventListener('keydown', (e) => {
      const k = { ArrowRight: 1, ArrowLeft: -1 }[e.key];
      if (!k || !sec.contains(document.activeElement)) return;
      e.preventDefault();
      go(active + k);
      if (document.activeElement.matches('[data-vc-hit]')) $('[data-vc-hit]', cards[active]).focus({ preventScroll: true });
    });
    $('[data-vs-prev]', sec).addEventListener('click', () => go(active - 1));
    cards.forEach((c, i) => {
      $('[data-vc-hit]', c).addEventListener('click', () => {
        if (drag && drag.moved) return;
        if (i !== active) { go(i); return; }
        const v = vids[active];
        if (v.paused) play(); else if (!sound) toggleSound(); else v.pause();
      });
      $('[data-vc-sound]', c).addEventListener('click', () => toggleSound());
    });
    const toggleSound = () => {
      sound = !sound;
      cards.forEach((c, i) => c.classList.toggle('is-sound', i === active && sound));
      $$('[data-vc-sound]', sec).forEach((b) => b.setAttribute('aria-label', sound ? 'Mute' : 'Turn sound on'));
      if (visible) play();
    };

    // drag / swipe
    stage.addEventListener('pointerdown', (e) => {
      if (e.button !== 0 || e.target.closest('[data-vc-sound]')) return;
      drag = { x: e.clientX, pos: st.pos, moved: false, id: e.pointerId };
    });
    window.addEventListener('pointermove', (e) => {
      if (!drag || e.pointerId !== drag.id) return;
      const dx = e.clientX - drag.x;
      if (!drag.moved && Math.abs(dx) > 6) {
        // only a real drag interrupts a running slide
        drag.moved = true;
        if (motion) gsap.killTweensOf(st, 'pos');
        drag.pos = st.pos;
        stage.classList.add('is-drag');
      }
      if (!drag.moved) return;
      const w = cards[0].offsetWidth * (small.matches ? 0.6 : 0.8);
      st.pos = drag.pos - dx / w;
      render();
    });
    const endDrag = () => {
      if (!drag) return;
      const d = drag;
      stage.classList.remove('is-drag');
      if (d.moved) {
        const delta = st.pos - d.pos;
        const steps = Math.abs(delta) < 0.15 ? 0 : Math.sign(delta) * Math.max(1, Math.round(Math.abs(delta)));
        go(Math.round(d.pos) + steps);
        setTimeout(() => { drag = null; }, 0);
      } else drag = null;
    };
    window.addEventListener('pointerup', endDrag);
    window.addEventListener('pointercancel', endDrag);

    // slight 3D tilt toward the pointer
    if (motion && fine) {
      const tx = gsap.quickTo(st, 'tx', { duration: 0.8, ease: 'power3.out', onUpdate: render });
      const ty = gsap.quickTo(st, 'ty', { duration: 0.8, ease: 'power3.out', onUpdate: render });
      stage.addEventListener('pointermove', (e) => {
        if (drag && drag.moved) return;
        const r = stage.getBoundingClientRect();
        tx(((e.clientY - r.top) / r.height - 0.5) * -6);
        ty(((e.clientX - r.left) / r.width - 0.5) * 8);
      });
      stage.addEventListener('pointerleave', () => { tx(0); ty(0); });
    }

    // fan out as the section scrolls in, and turn a little while it scrolls past
    if (motion) {
      gsap.to(st, { spread: 1, ease: 'none', onUpdate: render, scrollTrigger: { trigger: stage, start: 'top 95%', end: 'top 35%', scrub: 0.8 } });
      gsap.fromTo(st, { sy: 10 }, { sy: -10, ease: 'none', onUpdate: render, scrollTrigger: { trigger: sec, start: 'top bottom', end: 'bottom top', scrub: 1 } });
    }

    // autoplay (muted, with captions) only when motion is allowed; otherwise tap to play
    new IntersectionObserver(([e]) => {
      visible = e.isIntersecting && motion;
      if (visible) play(); else stop();
    }, { threshold: 0.4 }).observe(stage);
    small.addEventListener('change', () => render(true));
    window.addEventListener('resize', () => render(true));
    setActive(0);
    render();
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
  // The first screen is set up straight away. Everything below it is set up in idle
  // time, one section per task, so the browser can paint between them.
  initSmooth();
  initHeader();
  initMagnetic();
  initReveals();
  initHero();
  initMega();
  initPageHero();
  const later = [initForm, initServices, initSvcGrid, initMarkets, initRadar, initCrowd, initWork, initFunnel, initBoard, initTeam, initReviews, initVideos, initFaq, initClocks, initWordmark];
  const idle = window.requestIdleCallback || ((f) => setTimeout(f, 16));
  const next = () => {
    const f = later.shift();
    if (!f) { refresh(); return; }
    f();
    idle(next, { timeout: 400 });
  };
  idle(next, { timeout: 400 });
  window.addEventListener('load', refresh);
})();
