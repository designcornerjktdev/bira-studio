// Pop-up kontak (template-parts/contact-modal.php). Dibuka oleh .bira-contact-open
// ("Let's talk" di header, "Talk to BIRA" di CTA) atau link apa pun ke #contact.
// Tanpa JS, tombol-tombol itu tetap berfungsi sebagai link biasa (ke section CTA / URL tombol).
export function initContact() {
  const dialog = document.querySelector('[data-contact]');
  if (!dialog || typeof dialog.showModal !== 'function') return;

  let opener = null;
  const close = () => dialog.close();

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest('.bira-contact-open, a[href$="#contact"]');
    if (!trigger || e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
    e.preventDefault();
    opener = trigger;
    document.documentElement.classList.add('is-contact-open'); // kunci scroll halaman
    dialog.showModal();
  });

  dialog.addEventListener('click', (e) => {
    // Klik tombol tutup, atau klik di luar panel (backdrop = elemen dialog itu sendiri).
    if (e.target.closest('[data-contact-close]') || e.target === dialog) close();
  });

  dialog.addEventListener('close', () => {
    document.documentElement.classList.remove('is-contact-open');
    opener?.focus({ preventScroll: true }); // kembalikan fokus ke tombol pembuka
  });
}
