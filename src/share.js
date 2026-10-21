// Tombol "Copy link" di detail post ([data-copy-link]): salin URL ke clipboard, label sementara jadi "Copied!".
export function initShare() {
  document.querySelectorAll('[data-copy-link]').forEach((button) => {
    const label = button.querySelector('[data-copy-label]');
    const status = button.querySelector('[data-copy-status]');
    const original = label.textContent;

    button.addEventListener('click', async () => {
      const url = button.dataset.copyLink;
      try {
        await navigator.clipboard.writeText(url);
      } catch {
        // Clipboard API butuh HTTPS / localhost; fallback lama untuk http (mis. bira.local).
        const field = Object.assign(document.createElement('textarea'), { value: url });
        field.style.cssText = 'position:fixed;opacity:0';
        document.body.append(field);
        field.select();
        document.execCommand('copy');
        field.remove();
      }
      label.textContent = 'Copied!';
      status.textContent = 'Link copied to clipboard';
      setTimeout(() => {
        label.textContent = original;
        status.textContent = '';
      }, 2000);
    });
  });
}
