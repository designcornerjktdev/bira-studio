import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Made for the Feed (desktop): section di-pin, kartu naik dari bawah dalam tumpukan,
// lalu menyebar membentuk busur setengah lingkaran (titik pusat di bawah layar).
// Mobile / reduced motion: tetap swipe biasa (CSS).
export function initFeed() {
  const section = document.querySelector('#team');
  const list = section?.querySelector('.bira-feed-cards');
  if (!list) return;
  const cards = gsap.utils.toArray('.bira-feed-card', list);

  gsap.matchMedia().add('(min-width: 48rem) and (prefers-reduced-motion: no-preference)', () => {
    list.classList.add('is-arc');

    const n = cards.length;
    const spread = Math.min(70, 18 * (n - 1)); // total sudut busur (derajat)
    const radius = () => Math.min(window.innerWidth * 0.75, 1100);

    // Posisi akhir tiap kartu di busur.
    const arc = (i) => {
      const deg = n > 1 ? -spread / 2 + (spread / (n - 1)) * i : 0;
      const rad = (deg * Math.PI) / 180;
      return { x: Math.sin(rad) * radius(), y: (1 - Math.cos(rad)) * radius(), rotation: deg };
    };

    // Awal: tumpukan di bawah, sedikit acak supaya terlihat bertumpuk.
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

    tl.to(cards, { y: 0, opacity: 1, duration: 1, stagger: 0.08 }) // naik bertumpuk
      .to(cards, {
        x: (i) => arc(i).x,
        y: (i) => arc(i).y,
        rotation: (i) => arc(i).rotation,
        duration: 1.4,
        ease: 'power2.inOut',
      }); // menyebar jadi busur

    // ---------- Wave hover (hanya setelah busur terbentuk) ----------
    // Kartu terdekat ke mouse maju + membesar; tetangganya ikut terangkat dengan
    // falloff, dan berputar (rotateY) menghadap ke arah mouse.
    // Lift & scale lewat CSS var supaya tidak bentrok dengan x/y/rotation milik timeline.
    gsap.set(cards, { transformPerspective: 900, '--lift': 0, '--grow': 1 });
    const quick = cards.map((card) => ({
      lift: gsap.quickTo(card, '--lift', { duration: 0.5, ease: 'power3.out' }),
      grow: gsap.quickTo(card, '--grow', { duration: 0.5, ease: 'power3.out' }),
      turn: gsap.quickTo(card, 'rotationY', { duration: 0.6, ease: 'power3.out' }),
    }));
    const ready = () => tl.scrollTrigger && tl.progress() > 0.97;

    const onMove = (e) => {
      if (!ready()) return;
      const sigma = window.innerWidth * 0.12; // lebar "gelombang"
      cards.forEach((card, i) => {
        const r = card.getBoundingClientRect();
        const dx = e.clientX - (r.left + r.width / 2);
        const near = Math.exp(-(dx * dx) / (2 * sigma * sigma)); // 1 di bawah mouse → 0 menjauh
        quick[i].lift(-60 * near);
        quick[i].grow(1 + 0.12 * near);
        // Tetangga menghadap mouse; kartu tepat di bawah mouse tetap lurus.
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

  ScrollTrigger.sort(); // pin dibuat setelah trigger lain; urutkan ulang sesuai posisi di halaman
}
