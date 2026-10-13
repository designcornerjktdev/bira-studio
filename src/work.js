import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// The Good Stuff: di desktop section di-pin, scroll vertikal menggeser kartu ke kiri.
// Di mobile / reduced motion tetap swipe native (lihat .bira-work__viewport di CSS).
export function initWork() {
  const section = document.querySelector('[data-work]');
  const track = section?.querySelector('[data-work-track]');
  if (!section || !track) return;

  const mm = gsap.matchMedia();
  mm.add('(min-width: 64rem) and (prefers-reduced-motion: no-preference)', () => {
    section.classList.add('is-pinned');
    const distance = () => Math.max(0, track.scrollWidth - document.documentElement.clientWidth);

    gsap.to(track, {
      x: () => -distance(),
      ease: 'none',
      scrollTrigger: {
        trigger: section,
        start: 'top top',
        end: () => `+=${distance()}`,
        pin: true,
        scrub: 1,
        invalidateOnRefresh: true,
      },
    });

    return () => section.classList.remove('is-pinned');
  });
}
