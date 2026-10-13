import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Section muncul saat di-scroll. Elemen disembunyikan oleh JS (bukan CSS),
// jadi konten tetap terlihat bila script gagal dimuat.
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
    // Reset setelah elemen keluar dari bawah layar (scroll ke atas), supaya reveal
    // diputar ulang saat di-scroll turun lagi tanpa terlihat "hilang" di layar.
    // Elemen di dalam [data-reveal-once] (mis. The Good Stuff) cukup sekali main.
    const replay = items.filter((el) => !el.closest('[data-reveal-once]'));
    ScrollTrigger.batch(replay, {
      start: 'top bottom',
      onLeaveBack: (batch) => gsap.set(batch, { autoAlpha: 0, y: 32, overwrite: true }),
    });

    return () => gsap.set(items, { clearProps: 'opacity,visibility,transform' });
  });
}
