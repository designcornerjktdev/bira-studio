// "Copy link" button on post detail ([data-copy-link]): copies the URL to the clipboard, label briefly becomes "Copied!".
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
        // The Clipboard API needs HTTPS / localhost; legacy fallback for http (e.g. bira.local).
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
