import { gsap } from 'gsap';

// [data-tilt]: elemen miring 3D mengikuti kursor saat hover.
// Hanya untuk perangkat dengan mouse dan tanpa reduce motion. data-tilt="10" = sudut maksimal (default 10°).
export function initTilt() {
  const els = gsap.utils.toArray('[data-tilt]');
  if (!els.length) return;

  gsap.matchMedia().add('(hover: hover) and (prefers-reduced-motion: no-preference)', () => {
    const offs = els.map((el) => {
      const max = Number(el.dataset.tilt) || 10;
      gsap.set(el, { transformPerspective: 900, transformOrigin: '50% 50%' });
      const rx = gsap.quickTo(el, 'rotationX', { duration: 0.5, ease: 'power3.out' });
      const ry = gsap.quickTo(el, 'rotationY', { duration: 0.5, ease: 'power3.out' });
      const sc = gsap.quickTo(el, 'scale', { duration: 0.5, ease: 'power3.out' });

      const move = (e) => {
        const r = el.getBoundingClientRect();
        const nx = (e.clientX - r.left) / r.width - 0.5; // -0.5 … 0.5
        const ny = (e.clientY - r.top) / r.height - 0.5;
        rx(-ny * max * 2);
        ry(nx * max * 2);
        sc(1.03);
      };
      const leave = () => {
        rx(0);
        ry(0);
        sc(1);
      };
      el.addEventListener('pointermove', move);
      el.addEventListener('pointerleave', leave);
      return () => {
        el.removeEventListener('pointermove', move);
        el.removeEventListener('pointerleave', leave);
        gsap.set(el, { clearProps: 'transform' });
      };
    });
    return () => offs.forEach((off) => off());
  });
}
