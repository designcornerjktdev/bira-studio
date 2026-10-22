import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Team (desktop): the section is pinned, cards rise from below in a stack,
// then spread into a semicircular arc (center point below the screen).
// Mobile / reduced motion: plain swipe (CSS).
export function initFeed() {
  const section = document.querySelector('#team');
  const list = section?.querySelector('.bira-feed-cards');
  if (!list) return;
  const cards = gsap.utils.toArray('.bira-feed-card', list);

  gsap.matchMedia().add('(min-width: 48rem) and (prefers-reduced-motion: no-preference)', () => {
    list.classList.add('is-arc');

    const n = cards.length;
    const spread = Math.min(70, 18 * (n - 1)); // total arc angle (degrees)
    const radius = () => Math.min(window.innerWidth * 0.75, 1100);

    // Each card's final position on the arc.
    const arc = (i) => {
      const deg = n > 1 ? -spread / 2 + (spread / (n - 1)) * i : 0;
      const rad = (deg * Math.PI) / 180;
      return { x: Math.sin(rad) * radius(), y: (1 - Math.cos(rad)) * radius(), rotation: deg };
    };

    // Start: stacked below, slightly randomized so it reads as a pile.
    gsap.set(cards, {
      xPercent: -50,
      yPercent: -50,
      x: 0,
      y: () => window.innerHeight * 0.6,
      rotation: (i) => (i - (n - 1) / 2) * 3,
      opacity: 0,
    });

    const tl = gsap.timeline({
      defaults: { ease: 'power3.out' },
      scrollTrigger: {
        trigger: section,
        start: () => (section.offsetHeight > window.innerHeight ? 'bottom bottom' : 'top top'),
        end: '+=120%',
        pin: true,
        scrub: 1,
        invalidateOnRefresh: true,
      },
    });

    tl.to(cards, { y: 0, opacity: 1, duration: 1, stagger: 0.08 }) // rise as a stack
      .to(cards, {
        x: (i) => arc(i).x,
        y: (i) => arc(i).y,
        rotation: (i) => arc(i).rotation,
        duration: 1.4,
        ease: 'power2.inOut',
      }); // spread into an arc

    // ---------- Wave hover (only once the arc has formed) ----------
    // The card nearest the mouse comes forward + grows; its neighbors lift too with
    // falloff, and rotate (rotateY) to face the mouse.
    // Lift & scale go through CSS vars so they don't clash with the timeline's x/y/rotation.
    gsap.set(cards, { transformPerspective: 900, '--lift': 0, '--grow': 1 });
    const quick = cards.map((card) => ({
      lift: gsap.quickTo(card, '--lift', { duration: 0.5, ease: 'power3.out' }),
      grow: gsap.quickTo(card, '--grow', { duration: 0.5, ease: 'power3.out' }),
      turn: gsap.quickTo(card, 'rotationY', { duration: 0.6, ease: 'power3.out' }),
    }));
    const ready = () => tl.scrollTrigger && tl.progress() > 0.97;

    const onMove = (e) => {
      if (!ready()) return;
      const sigma = window.innerWidth * 0.12; // "wave" width
      cards.forEach((card, i) => {
        const r = card.getBoundingClientRect();
        const dx = e.clientX - (r.left + r.width / 2);
        const near = Math.exp(-(dx * dx) / (2 * sigma * sigma)); // 1 under the mouse → 0 farther away
        quick[i].lift(-60 * near);
        quick[i].grow(1 + 0.12 * near);
        // Neighbors face the mouse; the card right under it stays straight.
        quick[i].turn(gsap.utils.clamp(-28, 28, dx / 12) * (1 - near * 0.8));
        card.style.zIndex = String(10 + Math.round(near * 20));
      });
    };
    const onLeave = () => {
      cards.forEach((card, i) => {
        quick[i].lift(0);
        quick[i].grow(1);
        quick[i].turn(0);
        card.style.zIndex = '';
      });
    };
    list.addEventListener('mousemove', onMove);
    list.addEventListener('mouseleave', onLeave);
    tl.eventCallback('onUpdate', () => { if (!ready()) onLeave(); });

    return () => {
      list.removeEventListener('mousemove', onMove);
      list.removeEventListener('mouseleave', onLeave);
      list.classList.remove('is-arc');
      gsap.set(cards, { clearProps: 'all' });
    };
  });

  ScrollTrigger.sort(); // the pin is created after other triggers; re-sort by page position
}
