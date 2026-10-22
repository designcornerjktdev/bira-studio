import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Sections reveal on scroll. Elements are hidden by JS (not CSS),
// so content stays visible if the script fails to load.
export function initReveal() {
  const items = gsap.utils.toArray('[data-reveal]');
  if (!items.length) return;

  const mm = gsap.matchMedia();

  mm.add('(prefers-reduced-motion: no-preference)', () => {
    gsap.set(items, { autoAlpha: 0, y: 32 });

    ScrollTrigger.batch(items, {
      start: 'top 88%',
      onEnter: (batch) =>
        gsap.to(batch, { autoAlpha: 1, y: 0, duration: 0.8, ease: 'power3.out', stagger: 0.08, overwrite: true }),
    });
    // Reset after the element leaves through the bottom (scrolling up), so the reveal
    // replays when scrolling down again without visibly "vanishing" on screen.
    // Elements inside [data-reveal-once] (e.g. The Good Stuff) play only once.
    const replay = items.filter((el) => !el.closest('[data-reveal-once]'));
    ScrollTrigger.batch(replay, {
      start: 'top bottom',
      onLeaveBack: (batch) => gsap.set(batch, { autoAlpha: 0, y: 32, overwrite: true }),
    });

    return () => gsap.set(items, { clearProps: 'opacity,visibility,transform' });
  });
}
