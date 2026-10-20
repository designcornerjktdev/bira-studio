import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import { SplitText } from 'gsap/SplitText';

gsap.registerPlugin(ScrollTrigger, SplitText);

// [data-scroll-text]: huruf demi huruf berganti dari abu-abu ke hitam mengikuti scroll.
// Tanpa gradasi: tiap huruf hanya punya dua keadaan (class is-on), dipilih dari progres scroll.
export function initScrollText() {
  const headings = gsap.utils.toArray('[data-scroll-text]');
  if (!headings.length) return;

  gsap.matchMedia().add('(prefers-reduced-motion: no-preference)', () => {
    const triggers = new Map();
    const splits = headings.map((heading) =>
      SplitText.create(heading, {
        type: 'words,chars', // words tetap dipecah supaya baris tidak putus di tengah kata
        charsClass: 'bira-scroll-char',
        autoSplit: true, // split ulang saat font dimuat / resize
        onSplit(self) {
          const chars = self.chars;
          const paint = (progress) => {
            const on = Math.round(progress * chars.length);
            chars.forEach((char, i) => char.classList.toggle('is-on', i < on));
          };
          triggers.get(heading)?.kill(); // split ulang (resize): buang trigger lama
          const st = ScrollTrigger.create({
            trigger: heading,
            start: 'top 85%',
            end: 'bottom 45%',
            onUpdate: (s) => paint(s.progress),
            onRefresh: (s) => paint(s.progress),
          });
          triggers.set(heading, st);
        },
      }),
    );
    return () => {
      triggers.forEach((st) => st.kill());
      splits.forEach((s) => s.revert());
    };
  });
}
