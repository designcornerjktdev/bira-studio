import { ScrollTrigger } from 'gsap/ScrollTrigger';

// Filter kategori di halaman /work: sembunyikan kartu yang tidak punya kategori terpilih.
export function initWorkFilter() {
  document.querySelectorAll('[data-work-filter]').forEach((root) => {
    const buttons = root.querySelectorAll('[data-filter]');
    const items = root.querySelectorAll('[data-cats]');
    const count = root.querySelector('[data-filter-count]');

    buttons.forEach((button) =>
      button.addEventListener('click', () => {
        const filter = button.dataset.filter;
        buttons.forEach((b) => b.setAttribute('aria-pressed', String(b === button)));
        let shown = 0;
        items.forEach((item) => {
          const match = filter === 'all' || item.dataset.cats.split(' ').includes(filter);
          item.hidden = !match;
          shown += match;
        });
        if (count) count.textContent = shown;
        // Tinggi grid berubah: hitung ulang posisi animasi scroll di bawahnya (mis. Final CTA),
        // kalau tidak, elemen yang naik ke layar tetap tersembunyi menunggu posisi lama.
        ScrollTrigger.refresh();
      }),
    );
  });
}
