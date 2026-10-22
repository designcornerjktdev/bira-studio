import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Clicking an anchor link to a section on the same page (navbar, hero button, "Let’s talk") → smooth scroll.
// Done in JS on purpose, not CSS `scroll-behavior: smooth`: that CSS rule also animates
// GSAP ScrollTrigger scroll restoration (pinned sections), which can make the page glide on its own.
//
// Sections with data-anchor-end (e.g. #team) pinned by GSAP: scroll to the END of the pin,
// so the animation is finished (all team cards open) on arrival.

const headerOffset = () => parseFloat(getComputedStyle(document.documentElement).scrollPaddingTop) || 0;

// Target scroll position for a section.
function targetTop(target) {
  if (target.hasAttribute('data-anchor-end')) {
    const pin = ScrollTrigger.getAll().find((st) => st.pin === target); // only the trigger that pins this section
    if (pin) return pin.end; // pin end: the scrub animation is at 100%
  }
  return target.getBoundingClientRect().top + window.scrollY - headerOffset();
}

export function initSmoothAnchors() {
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)');

  document.addEventListener('click', (e) => {
    if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
    const link = e.target.closest('a[href*="#"]');
    if (!link) return;

    const url = new URL(link.href, location.href);
    // Only anchors on this page ("/#services" links from other pages still navigate normally).
    if (url.origin !== location.origin || url.pathname !== location.pathname || !url.hash) return;

    const target = document.getElementById(decodeURIComponent(url.hash.slice(1)));
    if (!target) return;

    e.preventDefault();
    window.scrollTo({ top: targetTop(target), behavior: reduce.matches ? 'auto' : 'smooth' });
    history.pushState(null, '', url.hash); // the URL still updates (#team) and the back button works
  });

  // Arriving from another page at "/#team": the browser stops at the section start; once all
  // ScrollTriggers are ready (window load), jump to the pin end so the cards are already open.
  window.addEventListener('load', () => {
    const target = location.hash && document.getElementById(decodeURIComponent(location.hash.slice(1)));
    if (!target?.hasAttribute('data-anchor-end')) return;
    ScrollTrigger.refresh();
    window.scrollTo({ top: targetTop(target), behavior: 'auto' });
  });
}
