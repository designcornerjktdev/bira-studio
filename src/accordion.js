import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Services accordion (What We Do): one item open at a time.
export function initAccordion() {
  document.querySelectorAll('[data-accordion]').forEach((root) => {
    const items = [...root.querySelectorAll('.bira-acc')];

    const setOpen = (item, open) => {
      const btn = item.querySelector('button[aria-controls]');
      const panel = document.getElementById(btn.getAttribute('aria-controls'));
      item.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', String(open));
      panel.inert = !open; // closed content cannot be tabbed into
    };

    // Page height changes once the transition ends; refresh only once.
    let refreshTimer;
    const openOnly = (item, open = true) => {
      items.forEach((other) => setOpen(other, other === item && open));
      clearTimeout(refreshTimer);
      refreshTimer = setTimeout(() => ScrollTrigger.refresh(), 550);
    };

    // Mouse devices: hovering opens the details directly.
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)');

    items.forEach((item) => {
      item.addEventListener('mouseenter', () => {
        if (canHover.matches && !item.classList.contains('is-open')) openOnly(item);
      });
      // Clicking still works for touch screens and keyboards.
      item.querySelector('button[aria-controls]').addEventListener('click', () => {
        if (canHover.matches && item.classList.contains('is-open')) return; // already opened by hover
        openOnly(item, !item.classList.contains('is-open'));
      });
    });
  });
}
