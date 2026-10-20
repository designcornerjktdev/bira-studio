import { gsap } from 'gsap';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(SplitText);

// [data-flip]: saat di-hover, huruf-huruf berputar 360° pada sumbu horizontal satu per satu (flip).
// Hanya untuk perangkat dengan mouse dan tanpa reduce motion.
export function initFlip() {
  const els = gsap.utils.toArray('[data-flip]');
  if (!els.length) return;

  gsap.matchMedia().add('(hover: hover) and (prefers-reduced-motion: no-preference)', () => {
    const cleanups = els.map((el) => {
      let chars = [];
      let tween;
      const split = SplitText.create(el, {
        type: 'words,chars', // words tetap dipecah supaya baris tidak putus di tengah kata
        charsClass: 'split-char',
        autoSplit: true,
        onSplit(self) {
          chars = self.chars;
          gsap.set(chars, { transformPerspective: 400, transformOrigin: '50% 50%' });
        },
      });

      const flip = () => {
        if (tween?.isActive()) return; // biarkan flip yang sedang jalan selesai
        tween = gsap.fromTo(
          chars,
          { rotationX: 0 },
          { rotationX: 360, duration: 0.7, ease: 'power3.inOut', stagger: 0.018 },
        );
      };
      el.addEventListener('pointerenter', flip);
      return () => {
        el.removeEventListener('pointerenter', flip);
        tween?.kill();
        split.revert();
      };
    });
    return () => cleanups.forEach((off) => off());
  });
}
