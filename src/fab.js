// Back-to-top FAB: shown once the page scrolls past a threshold; click → smooth scroll to top.
export function initFab() {
  const fab = document.querySelector('[data-fab]');
  if (!fab) return;

  const update = () => fab.classList.toggle('is-visible', window.scrollY > 240);
  update();
  window.addEventListener('scroll', update, { passive: true });

  fab.addEventListener('click', (e) => {
    e.preventDefault();
    const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    window.scrollTo({ top: 0, behavior: reduce ? 'auto' : 'smooth' });
  });
}
