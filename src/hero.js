import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

const RADIUS = 170;   // jangkauan pengaruh kursor (px)
const PUSH = 28;      // dorongan maksimum (px)

export function initHero() {
  const root = document.querySelector('[data-hero]');
  const title = root?.querySelector('[data-hero-title]');
  if (!root || !title) return;

  const desc = root.querySelector('.bira-hero__desc');
  const cta = root.querySelector('.bira-hero__cta');
  const mockup = root.querySelector('[data-hero-mockup]');
  const cards = mockup ? gsap.utils.toArray('[data-mock-card]', mockup) : [];

  const mm = gsap.matchMedia();

  mm.add(
    {
      motion: '(prefers-reduced-motion: no-preference)',
      fine: '(hover: hover) and (pointer: fine)',
    },
    (ctx) => {
      const { motion, fine } = ctx.conditions;

      // Reduced motion: tampilkan judul apa adanya, tanpa animasi.
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

      // Posisi tiap huruf relatif ke judul, dihitung sekali (bukan tiap mouse move).
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

      // ---------- Intro judul (per huruf) ----------
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

            // Jika re-split terjadi setelah intro (resize), ukur ulang segera.
            if (ready) measure();

            const tl = gsap.timeline();
            tl.from(chars, {
              yPercent: 120,
              rotationX: -90,
              opacity: 0,
              filter: 'blur(12px)',
              transformPerspective: 700,
              transformOrigin: '50% 100% -40px',
              duration: 1.1,
              ease: 'back.out(1.6)',
              stagger: 0.035,
              clearProps: 'filter',
            });

            const accentChars = title.querySelectorAll('.bira-hero__accent .split-char');
            if (accentChars.length) {
              tl.from(
                accentChars,
                { color: '#222522', duration: 0.5, stagger: 0.045 },
                '-=0.5'
              );
            }

            tl.call(() => {
              measure();
              ready = true;
            });

            return tl; // dikembalikan agar progres terjaga saat re-split
          },
        });
      });

      // ---------- Elemen pendukung ----------
      const extras = [desc, cta].filter(Boolean);
      if (extras.length) {
        gsap.from(extras, {
          y: 28,
          opacity: 0,
          duration: 0.8,
          delay: 1.1,
          stagger: 0.12,
          ease: 'power3.out',
        });
      }
      if (cards.length) {
        gsap.from(cards, {
          scale: 0.6,
          opacity: 0,
          duration: 0.9,
          delay: 0.9,
          stagger: 0.15,
          ease: 'back.out(1.8)',
        });
      }

      // ---------- Parallax scroll (naik dan turun) ----------
      gsap.to(title, {
        yPercent: -14,
        opacity: 0.15,
        ease: 'none',
        scrollTrigger: {
          trigger: root,
          start: 'top top',
          end: 'bottom top',
          scrub: true,
        },
      });

      // ---------- Interaksi kursor (hanya perangkat dengan mouse) ----------
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

        // Kartu mockup terpencar saat kursor masuk area kanan.
        const onEnter = () => {
          if (!ready) return;
          cards.forEach((c) => {
            const [x, y, r] = c.dataset.scatter.split(',').map(Number);
            gsap.to(c, { x, y, rotation: `+=${r}`, duration: 0.6, ease: 'power3.out', overwrite: 'auto' });
          });
        };
        const onExit = () => {
          cards.forEach((c) =>
            gsap.to(c, { x: 0, y: 0, rotation: 'auto', duration: 0.6, ease: 'power3.out', overwrite: 'auto' })
          );
        };
        mockup?.addEventListener('pointerenter', onEnter);
        mockup?.addEventListener('pointerleave', onExit);

        cleanupPointer = () => {
          root.removeEventListener('pointermove', onMove);
          root.removeEventListener('pointerleave', onLeave);
          mockup?.removeEventListener('pointerenter', onEnter);
          mockup?.removeEventListener('pointerleave', onExit);
        };
      }


let destroy3D = () => {};
let cancelled = false;
const saveData = navigator.connection?.saveData;

if (!saveData) {
  const idle = window.requestIdleCallback || ((fn) => setTimeout(fn, 800));
  idle(() => {
    if (cancelled) return;
    import('./hero3d.js')
      .then(({ initHero3D }) => initHero3D(root))
      .then((destroy) => {
        if (!destroy) return;
        if (cancelled) destroy(); // kondisi media berubah saat 3D masih dimuat
        else destroy3D = destroy;
      })
      .catch(() => {});
  });
}

      // Dibersihkan otomatis saat kondisi media berubah.
      return () => {
  cancelled = true;
  destroy3D();
  cleanupPointer();
  split?.revert();
};
    }
  );
}