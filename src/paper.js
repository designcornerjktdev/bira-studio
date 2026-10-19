import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Kartu "ditempel" seperti kertas: mulai miring 3D dari pojok atas, sedikit besar
// dan berputar, lalu ditepuk rata dengan pantulan kecil. Diulang tiap masuk dari atas.
export function initPaper() {
  gsap.utils.toArray('[data-paper]').forEach((list) => {
    const cards = gsap.utils.toArray('.bira-paper__card', list);
    if (!cards.length) return;

    gsap.matchMedia().add('(prefers-reduced-motion: no-preference)', () => {
      const tl = gsap.timeline({ paused: true }).fromTo(
        cards,
        {
          transformPerspective: 900,
          transformOrigin: (i) => (i % 2 ? '100% 0%' : '0% 0%'), // ditempel dari pojok kiri/kanan atas
          rotationX: -65,
          rotationY: (i) => (i % 2 ? -18 : 18),
          rotation: (i) => (i % 2 ? 6 : -6),
          skewX: (i) => (i % 2 ? -8 : 8),
          scale: 1.12,
          y: -40,
          autoAlpha: 0,
        },
        {
          rotationX: 0,
          rotationY: 0,
          rotation: (i) => [-1.5, 1, -0.5, 1.5, -1][i % 5], // sisa miring kecil, seperti kertas tempelan
          skewX: 0,
          scale: 1,
          y: 0,
          autoAlpha: 1,
          duration: 0.9,
          ease: 'back.out(1.6)',
          stagger: 0.12,
        },
      );

      const play = ScrollTrigger.create({ trigger: list, start: 'top 80%', onEnter: () => tl.restart() });
      // Reset setelah keluar dari bawah layar (scroll ke atas), jadi diulang saat turun lagi.
      const reset = ScrollTrigger.create({ trigger: list, start: 'top bottom', onLeaveBack: () => tl.pause(0) });
      if (window.scrollY > play.start) tl.progress(1);

      return () => {
        play.kill();
        reset.kill();
        tl.kill();
        gsap.set(cards, { clearProps: 'all' });
      };
    });

    // Tilt 3D mengikuti kursor saat hover, hanya untuk perangkat dengan mouse.
    gsap.matchMedia().add('(hover: hover) and (prefers-reduced-motion: no-preference)', () => {
      const offs = cards.map((card) => {
        const rx = gsap.quickTo(card, 'rotationX', { duration: 0.5, ease: 'power3.out' });
        const ry = gsap.quickTo(card, 'rotationY', { duration: 0.5, ease: 'power3.out' });
        const sc = gsap.quickTo(card, 'scale', { duration: 0.5, ease: 'power3.out' });
        const move = (e) => {
          const r = card.getBoundingClientRect();
          const nx = (e.clientX - r.left) / r.width - 0.5; // -0.5 … 0.5
          const ny = (e.clientY - r.top) / r.height - 0.5;
          gsap.set(card, { transformPerspective: 900, transformOrigin: '50% 50%' });
          rx(-ny * 14);
          ry(nx * 14);
          sc(1.04);
        };
        const leave = () => {
          rx(0);
          ry(0);
          sc(1);
        };
        card.addEventListener('pointermove', move);
        card.addEventListener('pointerleave', leave);
        return () => {
          card.removeEventListener('pointermove', move);
          card.removeEventListener('pointerleave', leave);
        };
      });
      return () => offs.forEach((off) => off());
    });
  });
}
