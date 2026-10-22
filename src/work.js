import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Work (The Good Stuff): on desktop the section is pinned and vertical scroll moves the cards left.
// On mobile / reduced motion it stays a native swipe (see .bira-work__viewport in CSS).
//
// Lazy load: the server only renders the first few cards. When the outro card ([data-work-outro])
// nears the viewport, the next cards are fetched from the REST endpoint and inserted before it,
// then ScrollTrigger is refreshed so the pin distance grows too.
export function initWork() {
  const section = document.querySelector('[data-work]');
  const track = section?.querySelector('[data-work-track]');
  if (!section || !track) return;

  const viewport = section.querySelector('[data-work-viewport]');
  const outro = track.querySelector('[data-work-outro]');
  let loading = false;

  const loadMore = async () => {
    const next = track.dataset.workNext;
    if (loading || next === undefined) return;
    loading = true;
    try {
      const url = new URL(track.dataset.workEndpoint, location.href);
      url.searchParams.set('offset', next);
      const res = await fetch(url, { headers: { Accept: 'application/json' } });
      if (!res.ok) throw new Error(res.status);
      const data = await res.json();

      outro.insertAdjacentHTML('beforebegin', data.html);
      if (data.next === null) delete track.dataset.workNext;
      else track.dataset.workNext = data.next;

      ScrollTrigger.refresh(); // the row got wider → pin distance & positions of sections below are updated
    } catch {
      // Failed (offline etc.): leave it; retry the next time the outro is visible.
    } finally {
      loading = false;
    }
  };

  // Watch the outro card. The root differs per mode: when pinned, the row moves via transform
  // (root = screen); with native swipe, cards are clipped by the scrollable viewport (root = viewport).
  const watch = (root) => {
    if (track.dataset.workNext === undefined || !outro) return () => {};
    const io = new IntersectionObserver(
      async (entries) => {
        if (!entries.some((e) => e.isIntersecting)) return;
        await loadMore();
        if (track.dataset.workNext === undefined) io.disconnect();
        else {
          // The outro may still be visible after new cards arrive: re-observe so it is checked again.
          io.unobserve(outro);
          io.observe(outro);
        }
      },
      { root, rootMargin: '0px 100% 0px 0px' }, // start loading one screen before the end of the row
    );
    io.observe(outro);
    return () => io.disconnect();
  };

  const mm = gsap.matchMedia();
  mm.add({ pinned: '(min-width: 64rem) and (prefers-reduced-motion: no-preference)' }, (ctx) => {
    if (!ctx.conditions.pinned) return watch(viewport);

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

    const stop = watch(null);
    return () => {
      stop();
      section.classList.remove('is-pinned');
    };
  });
}
