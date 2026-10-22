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
      if (!motion) return; // reduced motion: all elements stay static

      let cleanup = () => {};

      // Hero is pinned (like Team): the page holds until the fan is fully open, then moves on
      // to the next section. Created now (not after the intro) so the pin-spacer exists
      // when other triggers on the page are measured.
      const HOLD = '+=80%';
      // Pinned element: the hero + trust wrapper (front-page.php), so the screen below the hero isn't empty.
      // Starts at scroll 0 (not 'top top') because of the sticky header; if the wrapper is taller
      // than the viewport, scroll until its bottom (trust numbers) is visible, then hold.
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

      // ---------- Card fan (active after the intro) ----------
      // The fan spreads with scroll; mice add cursor parallax.
      // Everything feeds the same x/y/rotation setters, since GSAP owns the element transforms.
      tl.call(() => {
        const state = { spread: 0 };
        let nx = 0;
        let ny = 0;

        const movers = [frame, ...items].filter(Boolean).map((el) => {
          const [sx = 0, sy = 0, sr = 0] = (el.dataset.spread || '').split(' ').map(Number);
          return {
            depth: el.hasAttribute('data-mock-frame') ? 0 : parseFloat(el.dataset.depth || '0.5'),
            sx, sy, sr,
            rot: gsap.getProperty(el, 'rotation'), // base rotation from CSS
            x: gsap.quickTo(el, 'x', { duration: 0.6, ease: 'power3.out' }),
            y: gsap.quickTo(el, 'y', { duration: 0.6, ease: 'power3.out' }),
            r: gsap.quickTo(el, 'rotation', { duration: 0.6, ease: 'power3.out' }),
          };
        });

        const apply = () => {
          const w = mock.offsetWidth / 100; // spread values are % of mockup width
          movers.forEach((m) => {
            m.x(nx * 18 * m.depth + state.spread * m.sx * w);
            m.y(ny * 14 * m.depth + state.spread * m.sy * w);
            m.r(m.rot + state.spread * m.sr);
          });
        };

        // The fan follows scroll while the hero is pinned: closed at the start, fully open at the pin end,
        // closing again when scrolling back up.
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

        // Mouse: cursor parallax stays active on top of the scroll fan.
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

      // ---------- Scroll parallax (up and down) ----------
      gsap.to(mock, {
        yPercent: -6,
        ease: 'none',
        // Only runs after the pin ends, so the mockup stays still while the fan opens.
        scrollTrigger: { start: () => pin.end, end: () => pin.end + root.offsetHeight, scrub: true },
      });

      return () => {
        cleanup();
        pin.kill();
      };
    }
  );
}