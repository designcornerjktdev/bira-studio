import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Klik link anchor ke section di halaman yang sama (navbar, tombol hero, "Let’s talk") → scroll halus.
// Sengaja lewat JS, bukan CSS `scroll-behavior: smooth`: aturan CSS itu ikut menganimasikan
// pengembalian posisi scroll oleh GSAP ScrollTrigger (section yang di-pin) sehingga halaman bisa meluncur sendiri.
//
// Section dengan data-anchor-end (mis. #team) yang di-pin GSAP: scroll ke UJUNG pin,
// jadi animasinya sudah selesai (kartu tim sudah terbuka semua) saat tiba.

const headerOffset = () => parseFloat(getComputedStyle(document.documentElement).scrollPaddingTop) || 0;

// Posisi scroll tujuan untuk sebuah section.
function targetTop(target) {
  if (target.hasAttribute('data-anchor-end')) {
    const pin = ScrollTrigger.getAll().find((st) => st.pin === target); // hanya trigger yang mem-pin section ini
    if (pin) return pin.end; // ujung pin: animasi scrub sudah 100%
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
    // Hanya anchor di halaman ini (link "/#services" dari halaman lain tetap pindah halaman biasa).
    if (url.origin !== location.origin || url.pathname !== location.pathname || !url.hash) return;

    const target = document.getElementById(decodeURIComponent(url.hash.slice(1)));
    if (!target) return;

    e.preventDefault();
    window.scrollTo({ top: targetTop(target), behavior: reduce.matches ? 'auto' : 'smooth' });
    history.pushState(null, '', url.hash); // URL tetap ikut berubah (#team) dan tombol back jalan
  });

  // Datang dari halaman lain ke "/#team": browser berhenti di awal section; setelah semua
  // ScrollTrigger siap (window load), loncat ke ujung pin supaya kartu sudah terbuka.
  window.addEventListener('load', () => {
    const target = location.hash && document.getElementById(decodeURIComponent(location.hash.slice(1)));
    if (!target?.hasAttribute('data-anchor-end')) return;
    ScrollTrigger.refresh();
    window.scrollTo({ top: targetTop(target), behavior: 'auto' });
  });
}
