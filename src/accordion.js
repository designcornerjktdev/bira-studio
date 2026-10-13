import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Accordion layanan (What We Do): satu item terbuka pada satu waktu.
export function initAccordion() {
  document.querySelectorAll('[data-accordion]').forEach((root) => {
    const items = [...root.querySelectorAll('.bira-acc')];

    const setOpen = (item, open) => {
      const btn = item.querySelector('button[aria-controls]');
      const panel = document.getElementById(btn.getAttribute('aria-controls'));
      item.classList.toggle('is-open', open);
      btn.setAttribute('aria-expanded', String(open));
      panel.inert = !open; // konten tertutup tidak bisa di-tab
    };

    // Tinggi halaman berubah setelah transisi selesai; refresh sekali saja.
    let refreshTimer;
    const openOnly = (item, open = true) => {
      items.forEach((other) => setOpen(other, other === item && open));
      clearTimeout(refreshTimer);
      refreshTimer = setTimeout(() => ScrollTrigger.refresh(), 550);
    };

    // Perangkat dengan mouse: hover langsung membuka detail.
    const canHover = window.matchMedia('(hover: hover) and (pointer: fine)');

    items.forEach((item) => {
      item.addEventListener('mouseenter', () => {
        if (canHover.matches && !item.classList.contains('is-open')) openOnly(item);
      });
      // Klik tetap berfungsi untuk layar sentuh dan keyboard.
      item.querySelector('button[aria-controls]').addEventListener('click', () => {
        if (canHover.matches && item.classList.contains('is-open')) return; // sudah dibuka oleh hover
        openOnly(item, !item.classList.contains('is-open'));
      });
    });
  });
}
