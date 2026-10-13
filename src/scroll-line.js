import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Kurva dalam koordinat ternormalisasi (0–1). Dibangun ulang ke ukuran piksel
// container saat resize, supaya ketebalan garis tetap konsisten (tidak ikut melar).
const SEGMENTS = [
  ['M', -0.05, 0.04],
  ['C', 0.2, 0.02, 0.42, 0.1, 0.5, 0.22],
  ['C', 0.58, 0.34, 0.4, 0.46, 0.28, 0.42],
  ['C', 0.12, 0.37, 0.18, 0.2, 0.36, 0.24],
  ['C', 0.62, 0.3, 0.98, 0.42, 0.9, 0.62],
  ['C', 0.82, 0.82, 0.3, 0.74, 0.32, 0.9],
  ['C', 0.34, 1.0, 0.7, 0.98, 1.05, 0.96],
];

function buildPath(w, h) {
  return SEGMENTS.map(([cmd, ...pts]) =>
    cmd + pts.map((v, i) => (i % 2 ? v * h : v * w).toFixed(1)).join(' ')
  ).join(' ');
}

// Garis "tergambar" mengikuti scroll di dalam setiap [data-scroll-line] (gaya lusion.co).
export function initScrollLine() {
  document.querySelectorAll('[data-scroll-line]').forEach((wrap) => {
    const svg = wrap.querySelector('.bira-scroll-line');
    const path = svg?.querySelector('path');
    if (!path) return;

    // Progress 0–1 dari ScrollTrigger; offset dihitung dari panjang path asli (px).
    const state = { progress: 1, length: 0 };
    const draw = () => {
      path.style.strokeDasharray = `${state.length} ${state.length}`;
      path.style.strokeDashoffset = `${state.length * (1 - state.progress)}`;
    };

    const layout = () => {
      const w = wrap.offsetWidth;
      const h = wrap.offsetHeight;
      svg.setAttribute('viewBox', `0 0 ${w} ${h}`);
      path.setAttribute('d', buildPath(w, h));
      state.length = path.getTotalLength();
      draw();
    };
    layout();
    ScrollTrigger.addEventListener('refresh', layout);

    gsap.matchMedia().add('(prefers-reduced-motion: no-preference)', () => {
      state.progress = 0;
      draw();
      const tween = gsap.to(state, {
        progress: 1,
        ease: 'none',
        onUpdate: draw,
        scrollTrigger: { trigger: wrap, start: 'top 70%', end: 'bottom 80%', scrub: 1 },
      });
      return () => { tween.kill(); state.progress = 1; draw(); };
    });
  });
}
