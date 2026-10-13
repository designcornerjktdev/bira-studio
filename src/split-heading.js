import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

// data-split="loop": setelah reveal, tiap beberapa detik 1–3 huruf acak tergeser
// ke atas lalu muncul lagi dari bawah. Hanya jalan saat heading terlihat.
function startCharLoop(heading, chars) {
  const letters = chars.filter((c) => /\p{L}/u.test(c.textContent));
  const star = heading.querySelector("svg"); // asterisk ikut "bereaksi" tiap huruf bergeser
  let dir = 1;
  let active; // timeline swipe yang sedang jalan
  let visible = true;
  const watch = ScrollTrigger.create({
    trigger: heading,
    onToggle: (self) => (visible = self.isActive),
  });

  const tick = () => {
    if (visible && letters.length) {
      const picks = gsap.utils
        .shuffle([...letters])
        .slice(0, gsap.utils.random(1, 3, 1));
      active = gsap
        .timeline()
        .to(picks, {
          yPercent: -110,
          duration: 0.35,
          ease: "power2.in",
          stagger: 0.08,
        })
        .set(picks, { yPercent: 110 })
        .to(picks, {
          yPercent: 0,
          duration: 0.5,
          ease: "power3.out",
          stagger: 0.08,
        });

      // Asterisk berputar sedikit, bergantian kanan-kiri tiap putaran.
      if (star) {
        gsap.to(star, {
          rotation: 20 * dir,
          duration: 0.6,
          ease: "back.out(2)",
        });
        dir *= -1;
      }
    }
    call = gsap.delayedCall(gsap.utils.random(1.4, 2.6), tick);
  };
  let call = gsap.delayedCall(1.2, tick);
  return {
    kill: () => {
      call.kill();
      watch.kill();
      active?.kill();
      gsap.set(letters, { yPercent: 0 });
    },
  };
}

// Putar animasi saat elemen masuk dari atas; reset setelah elemen keluar dari bawah
// layar (scroll ke atas), jadi setiap kali di-scroll turun lagi animasi diulang.
function replayOnEnter(trigger, anim, onReset) {
  // Di dalam [data-reveal-once] (mis. The Good Stuff): cukup sekali main, tanpa reset.
  const once = Boolean(trigger.closest("[data-reveal-once]"));
  const play = ScrollTrigger.create({
    trigger,
    start: "top 85%",
    onEnter: () => (once ? anim.play() : anim.restart()),
  });
  const reset = once
    ? null
    : ScrollTrigger.create({
        trigger,
        start: "top bottom",
        onLeaveBack: () => {
          anim.pause(0);
          onReset?.();
        },
      });
  // Sudah lewat saat halaman dimuat (mis. reload di tengah halaman): tampilkan langsung.
  if (play.progress === 1 || window.scrollY > play.start) anim.progress(1);
  return () => {
    play.kill();
    reset?.kill();
  };
}

// Heading section (.bira-h2) muncul per huruf saat masuk viewport.
// Dipecah per kata dulu supaya kata tidak terpotong di tengah saat wrap.
export function initSplitHeadings() {
  const headings = gsap.utils.toArray(
    '.bira-h2:not([data-split="center"]):not(.bira-h2--static)',
  );
  const centered = gsap.utils.toArray('[data-split="center"]');

  const mm = gsap.matchMedia();
  mm.add("(prefers-reduced-motion: no-preference)", () => {
    // Dua baris membelah dari garis tengah: baris atas naik, baris bawah turun.
    centered.forEach((heading) => {
      const tl = gsap
        .timeline({ paused: true })
        .from(heading.querySelector('[data-split-line="up"]'), {
          yPercent: 105,
          duration: 1,
          ease: "expo.out",
        })
        .from(
          heading.querySelector('[data-split-line="down"]'),
          { yPercent: -105, duration: 1, ease: "expo.out" },
          "<",
        );
      replayOnEnter(heading, tl);
    });

    headings.forEach((heading) => {
      const loop = heading.dataset.split === "loop";
      let loopCall;
      let cleanup;

      SplitText.create(heading, {
        type: "words,chars",
        mask: loop ? "chars" : undefined, // huruf loop perlu mask agar bisa keluar-masuk
        autoSplit: true, // split ulang saat font selesai dimuat / resize
        onSplit: (self) => {
          loopCall?.kill();
          cleanup?.();
          const tween = gsap.from(self.chars, {
            opacity: 0,
            y: 30,
            duration: 0.5,
            stagger: 0.03,
            ease: "back.out(1.7)",
            paused: true,
            onStart: () => loopCall?.kill(),
            onComplete: loop
              ? () => (loopCall = startCharLoop(heading, self.chars))
              : undefined,
          });
          cleanup = replayOnEnter(heading, tween, () => loopCall?.kill());
        },
      });
    });
  });
}
