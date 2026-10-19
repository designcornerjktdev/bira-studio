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
      }),
    );
  });
}
