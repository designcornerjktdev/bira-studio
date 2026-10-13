import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// How We Work: langkah yang melewati tengah layar jadi aktif (dot + judul + progress bar),
// dan garis vertikal terisi mengikuti scroll.
export function initSteps() {
  const root = document.querySelector('[data-steps]');
  if (!root) return;

  const steps = [...root.querySelectorAll('.bira-step')];
  const bars = [...root.querySelectorAll('.bira-steps__bar')];
  const list = root.querySelector('.bira-steps');

  const activate = (index) => {
    steps.forEach((el, i) => el.classList.toggle('is-active', i <= index));
    bars.forEach((el, i) => el.classList.toggle('is-active', i <= index));
  };

  steps.forEach((step, i) => {
    ScrollTrigger.create({
      trigger: step,
      start: 'top 55%',
      onEnter: () => activate(i),
      onLeaveBack: () => activate(Math.max(0, i - 1)),
    });
  });

  gsap.matchMedia().add('(prefers-reduced-motion: no-preference)', () => {
    gsap.fromTo(list, { '--fill': 0 }, {
      '--fill': 1,
      ease: 'none',
      scrollTrigger: { trigger: list, start: 'top 55%', end: 'bottom 55%', scrub: true },
    });
  });
}
