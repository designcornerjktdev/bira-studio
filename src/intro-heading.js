import { gsap } from 'gsap';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(SplitText);

// [data-intro]: judul halaman masuk per huruf saat halaman dibuka (gaya hero gsap.com).
// Huruf naik dari balik garis (mask per baris) sambil sedikit berputar, berurutan kiri → kanan.
export function initIntroHeadings() {
  const headings = gsap.utils.toArray('[data-intro]');
  if (!headings.length) return;

  gsap.matchMedia().add(
    { motion: '(prefers-reduced-motion: no-preference)' },
    ({ conditions }) => {
      if (!conditions.motion) {
        gsap.set(headings, { visibility: 'visible' });
        return;
      }

      const splits = [];
      document.fonts.ready.then(() => {
        headings.forEach((heading) => {
          splits.push(
            SplitText.create(heading, {
              type: 'lines,words,chars',
              mask: 'lines', // huruf tersembunyi di bawah garis sampai naik
              charsClass: 'split-char',
              onSplit(self) {
                gsap.set(heading, { visibility: 'visible' });
                return gsap.from(self.chars, {
                  yPercent: 115,
                  rotation: 14,
                  transformOrigin: '0% 100%',
                  duration: 1.1,
                  ease: 'expo.out',
                  stagger: 0.028,
                  delay: 0.15,
                });
              },
            }),
          );
        });
      });

      return () => splits.forEach((s) => s.revert());
    },
  );
}
