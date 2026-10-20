import { gsap } from 'gsap';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(SplitText);

// [data-shine]: saat di-hover, satu kilau diagonal berjalan huruf demi huruf —
// dari huruf pertama baris 1 sampai huruf terakhir baris paling bawah.
// Pecah huruf di JS supaya teks dari Customizer (dengan kata aksen hijau) tetap jalan.
export function initShine() {
  const headings = gsap.utils.toArray('[data-shine]');
  if (!headings.length) return;

  gsap.matchMedia().add('(hover: hover) and (prefers-reduced-motion: no-preference)', () => {
    const splits = headings.map((heading) =>
      SplitText.create(heading, {
        type: 'words,chars', // words tetap dipecah supaya baris tidak putus di tengah kata
        charsClass: 'bira-shine__c',
        autoSplit: true,
        onSplit(self) {
          self.chars.forEach((char, i) => char.style.setProperty('--i', i));
        },
      }),
    );
    return () => splits.forEach((s) => s.revert());
  });
}
