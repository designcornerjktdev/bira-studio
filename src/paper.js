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
  });
}
