import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

// Work (The Good Stuff): di desktop section di-pin, scroll vertikal menggeser kartu ke kiri.
// Di mobile / reduced motion tetap swipe native (lihat .bira-work__viewport di CSS).
//
// Lazy load: server hanya merender beberapa kartu pertama. Saat kartu penutup ([data-work-outro])
// mendekati layar, kartu berikutnya diambil dari endpoint REST dan disisipkan sebelum penutup,
// lalu ScrollTrigger dihitung ulang supaya jarak pin ikut memanjang.
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

      ScrollTrigger.refresh(); // lebar baris bertambah → jarak pin & posisi section di bawahnya diperbarui
    } catch {
      // Gagal (offline dsb.): biarkan, dicoba lagi saat penutup terlihat berikutnya.
    } finally {
      loading = false;
    }
  };

  // Pantau kartu penutup. Root berbeda per mode: saat di-pin, baris digeser dengan transform
  // (root = layar); saat swipe native, kartu terpotong oleh viewport yang bisa di-scroll (root = viewport).
  const watch = (root) => {
    if (track.dataset.workNext === undefined || !outro) return () => {};
    const io = new IntersectionObserver(
      async (entries) => {
        if (!entries.some((e) => e.isIntersecting)) return;
        await loadMore();
        if (track.dataset.workNext === undefined) io.disconnect();
        else {
          // Penutup bisa masih terlihat setelah kartu baru masuk: observe ulang supaya dicek lagi.
          io.unobserve(outro);
          io.observe(outro);
        }
      },
      { root, rootMargin: '0px 100% 0px 0px' }, // mulai memuat satu layar sebelum ujung baris
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
