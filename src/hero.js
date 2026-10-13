import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

const RADIUS = 170;
const PUSH = 28;

export function initHero() {
  const root = document.querySelector('[data-hero]');
  const title = root?.querySelector('[data-hero-title]');
  if (!root || !title) return;

  const mm = gsap.matchMedia();

  mm.add(
    {
      motion: '(prefers-reduced-motion: no-preference)',
      fine: '(hover: hover) and (pointer: fine)',
    },
    (ctx) => {
      const { motion, fine } = ctx.conditions;

      if (!motion) {
        gsap.set(title, { visibility: 'visible' });
        return;
      }

      let ready = false;
      let chars = [];
      let centers = [];
      let setters = [];
      let touched = [];
      let split;

      const measure = () => {
        const t = title.getBoundingClientRect();
        centers = chars.map((c) => {
          const r = c.getBoundingClientRect();
          return { x: r.left - t.left + r.width / 2, y: r.top - t.top + r.height / 2 };
        });
      };

      const resetChars = () => {
        setters.forEach((s, i) => {
          if (!touched[i]) return;
          s.x(0); s.y(0); s.rotation(0); s.scale(1);
          touched[i] = false;
        });
      };

      // ---------- Pecah judul per huruf (tanpa animasi masuk) ----------
      document.fonts.ready.then(() => {
        split = SplitText.create(title, {
          type: 'lines,chars',
          charsClass: 'split-char',
          autoSplit: true,
          onSplit(self) {
            chars = self.chars;
            gsap.set(title, { visibility: 'visible' });

            setters = chars.map((c) => ({
              x: gsap.quickTo(c, 'x', { duration: 0.5, ease: 'power3.out' }),
              y: gsap.quickTo(c, 'y', { duration: 0.5, ease: 'power3.out' }),
              rotation: gsap.quickTo(c, 'rotation', { duration: 0.5, ease: 'power3.out' }),
              scale: gsap.quickTo(c, 'scale', { duration: 0.5, ease: 'power3.out' }),
            }));
            touched = chars.map(() => false);

            measure();
            ready = true;
          },
        });
      });

      // ---------- Parallax scroll judul ----------
      gsap.to(title, {
        yPercent: -14,
        opacity: 0.15,
        ease: 'none',
        scrollTrigger: { trigger: root, start: 'top top', end: 'bottom top', scrub: true },
      });

      // ---------- Huruf bereaksi pada kursor ----------
      let cleanupPointer = () => {};
      if (fine) {
        let px = 0, py = 0, queued = false;

        const update = () => {
          queued = false;
          if (!ready) return;
          const t = title.getBoundingClientRect();
          for (let i = 0; i < chars.length; i++) {
            const dx = px - (t.left + centers[i].x);
            const dy = py - (t.top + centers[i].y);
            const d = Math.hypot(dx, dy) || 1;
            const f = d < RADIUS ? (1 - d / RADIUS) ** 2 : 0;
            if (f === 0 && !touched[i]) continue;
            const s = setters[i];
            s.x((-dx / d) * f * PUSH);
            s.y((-dy / d) * f * PUSH);
            s.rotation((-dx / RADIUS) * f * 18);
            s.scale(1 + f * 0.3);
            touched[i] = f > 0;
          }
        };

        const onMove = (e) => {
          px = e.clientX;
          py = e.clientY;
          if (!queued) {
            queued = true;
            requestAnimationFrame(update);
          }
        };
        const onLeave = () => resetChars();

        root.addEventListener('pointermove', onMove);
        root.addEventListener('pointerleave', onLeave);
        cleanupPointer = () => {
          root.removeEventListener('pointermove', onMove);
          root.removeEventListener('pointerleave', onLeave);
        };
      }

      return () => {
        cleanupPointer();
        split?.revert();
      };
    }
  );
}