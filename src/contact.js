// Contact pop-up (template-parts/contact-modal.php). Opened by .bira-contact-open
// ("Let's talk" in the header, "Talk to BIRA" in the CTA) or any link to #contact.
// Without JS those buttons still work as plain links (to the CTA section / button URL).
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
    document.documentElement.classList.add('is-contact-open'); // lock page scroll
    dialog.showModal();
  });

  dialog.addEventListener('click', (e) => {
    // Close button click, or a click outside the panel (backdrop = the dialog element itself).
    if (e.target.closest('[data-contact-close]') || e.target === dialog) close();
  });

  dialog.addEventListener('close', () => {
    document.documentElement.classList.remove('is-contact-open');
    opener?.focus({ preventScroll: true }); // return focus to the opener
  });
}
