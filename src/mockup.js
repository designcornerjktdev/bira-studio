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

      // Hero di-pin (seperti Team): halaman tertahan sampai kipas terbuka penuh, baru lanjut
      // ke section berikutnya. Dibuat sekarang (bukan setelah intro) supaya pin-spacer sudah
      // ada saat trigger lain di halaman diukur.
      const HOLD = '+=80%';
      // Yang di-pin: pembungkus hero + trust (front-page.php), supaya layar tidak kosong di bawah hero.
      // Mulai dari scroll 0 (bukan 'top top') karena header sticky; bila pembungkus lebih tinggi
      // dari layar, scroll dulu sampai bagian bawahnya (angka trust) terlihat, baru ditahan.
      const block = root.closest('[data-hero-pin]') || root;
      const pin = ScrollTrigger.create({
        id: 'hero-pin',
        trigger: block,
        start: () => Math.max(0, block.offsetTop + block.offsetHeight - window.innerHeight),
        end: HOLD,
        pin: true,
        invalidateOnRefresh: true,
      });
      ScrollTrigger.sort();

      // ---------- Intro ----------
      const tl = gsap.timeline({ delay: 0.4 });
      tl.fromTo(frame, { opacity: 0 }, { opacity: 1, duration: 0.8, ease: 'power2.out' }).fromTo(
        items,
        { y: 48, scale: 0.85, opacity: 0 },
        { y: 0, scale: 1, opacity: 1, duration: 0.9, ease: 'back.out(1.6)', stagger: 0.12 },
        0.1
      );

      // ---------- Kipas kartu (aktif setelah intro) ----------
      // Kipas berpencar mengikuti scroll; mouse menambah parallax kursor.
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

        // Kipas mengikuti scroll selama hero di-pin: tertutup di awal, terbuka penuh di ujung pin,
        // menutup lagi saat di-scroll kembali ke atas.
        const scroll = gsap.to(state, {
          spread: 1,
          ease: 'none',
          onUpdate: apply,
          scrollTrigger: { start: () => pin.start, end: () => pin.end, scrub: 0.6 },
        });

        if (!fine) {
          cleanup = () => scroll.scrollTrigger?.kill();
          return;
        }

        // Mouse: parallax kursor tetap aktif di atas kipas scroll.
        const onMove = (e) => {
          const r = root.getBoundingClientRect();
          nx = ((e.clientX - r.left) / r.width) * 2 - 1;
          ny = ((e.clientY - r.top) / r.height) * 2 - 1;
          apply();
        };
        const onLeave = () => { nx = 0; ny = 0; apply(); };

        root.addEventListener('pointermove', onMove);
        root.addEventListener('pointerleave', onLeave);

        cleanup = () => {
          scroll.scrollTrigger?.kill();
          root.removeEventListener('pointermove', onMove);
          root.removeEventListener('pointerleave', onLeave);
        };
      });

      // ---------- Parallax scroll (naik dan turun) ----------
      gsap.to(mock, {
        yPercent: -6,
        ease: 'none',
        // Baru jalan setelah pin selesai, supaya mockup diam selama kipas terbuka.
        scrollTrigger: { start: () => pin.end, end: () => pin.end + root.offsetHeight, scrub: true },
      });

      return () => {
        cleanup();
        pin.kill();
      };
    }
  );
}