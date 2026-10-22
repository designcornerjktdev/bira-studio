import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// [data-count]: numbers count up from 0 when entering the viewport (once).
// Prefix/suffix are kept: "50M+" → 0M+ … 50M+, "1,000+" keeps its thousands separator.
export function initCount() {
  const els = gsap.utils.toArray('[data-count]');
  if (!els.length) return;

  gsap.matchMedia().add('(prefers-reduced-motion: no-preference)', () => {
    const tweens = els.map((el) => {
      const text = el.textContent.trim();
      const match = text.match(/^(\D*)([\d.,]+)(.*)$/);
      if (!match) return null;

      const [, prefix, raw, suffix] = match;
      const grouped = raw.includes(',');
      const target = parseFloat(raw.replace(/,/g, ''));
      const decimals = (raw.split('.')[1] || '').length;
      const format = (v) =>
        prefix +
        v.toLocaleString('en-US', {
          minimumFractionDigits: decimals,
          maximumFractionDigits: decimals,
          useGrouping: grouped,
        }) +
        suffix;

      const state = { v: 0 };
      el.textContent = format(0);
      const tween = gsap.to(state, {
        v: target,
        duration: 1.8,
        ease: 'power3.out',
        onUpdate: () => (el.textContent = format(state.v)),
        scrollTrigger: { trigger: el, start: 'top 85%', once: true },
      });
      return { tween, el, text };
    });
    // When the media query changes (e.g. reduced motion turned on): stop and restore the original text.
    return () =>
      tweens.forEach((t) => {
        if (!t) return;
        t.tween.scrollTrigger?.kill();
        t.tween.kill();
        t.el.textContent = t.text;
      });
  });
}
