import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

export function initMockup() {
  const root = document.querySelector('[data-hero]');
  const mock = root?.querySelector('[data-hero-mockup]');
  if (!root || !mock) return;

  const frame = mock.querySelector('[data-mock-frame]');
  const items = gsap.utils.toArray('[data-mock-item]', mock);

  const mm = gsap.matchMedia();

  mm.add(
    {
      motion: '(prefers-reduced-motion: no-preference)',
      fine: '(hover: hover) and (pointer: fine)',
    },
    (ctx) => {
      const { motion, fine } = ctx.conditions;
      if (!motion) return; // reduced motion: semua elemen tampil statis

      let cleanup = () => {};

      // ---------- Intro ----------
      const tl = gsap.timeline({ delay: 0.4 });
      tl.fromTo(frame, { opacity: 0 }, { opacity: 1, duration: 0.8, ease: 'power2.out' }).fromTo(
        items,
        { y: 48, scale: 0.85, opacity: 0 },
        { y: 0, scale: 1, opacity: 1, duration: 0.9, ease: 'back.out(1.6)', stagger: 0.12 },
        0.1
      );

      // ---------- Kipas kartu (aktif setelah intro) ----------
      // Mouse: parallax kursor + berpencar saat hover. Layar sentuh: berpencar mengikuti scroll.
      // Semua digabung ke setter x/y/rotation yang sama, karena GSAP memegang transform elemen.
      tl.call(() => {
        const state = { spread: 0 };
        let nx = 0;
        let ny = 0;

        const movers = [frame, ...items].filter(Boolean).map((el) => {
          const [sx = 0, sy = 0, sr = 0] = (el.dataset.spread || '').split(' ').map(Number);
          return {
            depth: el.hasAttribute('data-mock-frame') ? 0 : parseFloat(el.dataset.depth || '0.5'),
            sx, sy, sr,
            rot: gsap.getProperty(el, 'rotation'), // rotasi dasar dari CSS
            x: gsap.quickTo(el, 'x', { duration: 0.6, ease: 'power3.out' }),
            y: gsap.quickTo(el, 'y', { duration: 0.6, ease: 'power3.out' }),
            r: gsap.quickTo(el, 'rotation', { duration: 0.6, ease: 'power3.out' }),
          };
        });

        const apply = () => {
          const w = mock.offsetWidth / 100; // nilai spread dalam % lebar mockup
          movers.forEach((m) => {
            m.x(nx * 18 * m.depth + state.spread * m.sx * w);
            m.y(ny * 14 * m.depth + state.spread * m.sy * w);
            m.r(m.rot + state.spread * m.sr);
          });
        };

        // Layar sentuh (tanpa hover): kipas terbuka saat mockup naik ke tengah layar,
        // menutup lagi saat di-scroll kembali ke atas.
        if (!fine) {
          const scroll = gsap.to(state, {
            spread: 1,
            ease: 'none',
            onUpdate: apply,
            scrollTrigger: { trigger: mock, start: 'top 85%', end: 'center 45%', scrub: 0.6 },
          });
          cleanup = () => scroll.scrollTrigger?.kill();
          return;
        }

        const onMove = (e) => {
          const r = root.getBoundingClientRect();
          nx = ((e.clientX - r.left) / r.width) * 2 - 1;
          ny = ((e.clientY - r.top) / r.height) * 2 - 1;
          apply();
        };
        const onLeave = () => { nx = 0; ny = 0; apply(); };
        const spreadTo = (v) => gsap.to(state, { spread: v, duration: 0.7, ease: v ? 'back.out(1.7)' : 'power3.inOut', overwrite: true, onUpdate: apply });
        const onEnterMock = () => spreadTo(1);
        const onLeaveMock = () => spreadTo(0);

        root.addEventListener('pointermove', onMove);
        root.addEventListener('pointerleave', onLeave);
        mock.addEventListener('pointerenter', onEnterMock);
        mock.addEventListener('pointerleave', onLeaveMock);
        if (mock.matches(':hover')) onEnterMock();

        cleanup = () => {
          root.removeEventListener('pointermove', onMove);
          root.removeEventListener('pointerleave', onLeave);
          mock.removeEventListener('pointerenter', onEnterMock);
          mock.removeEventListener('pointerleave', onLeaveMock);
        };
      });

      // ---------- Parallax scroll (naik dan turun) ----------
      gsap.to(mock, {
        yPercent: -6,
        ease: 'none',
        scrollTrigger: { trigger: root, start: 'top top', end: 'bottom top', scrub: true },
      });

      return () => cleanup();
    }
  );
}