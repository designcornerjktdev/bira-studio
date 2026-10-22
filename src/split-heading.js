import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { SplitText } from "gsap/SplitText";

gsap.registerPlugin(ScrollTrigger, SplitText);

// data-split="loop": after the reveal, every few seconds 1–3 random characters slide
// up and come back from below. Only runs while the heading is visible.
function startCharLoop(heading, chars) {
  const letters = chars.filter((c) => /\p{L}/u.test(c.textContent));
  const star = heading.querySelector("svg"); // the asterisk "reacts" each time characters slide
  let dir = 1;
  let active; // the swipe timeline currently running
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

      // The asterisk rotates slightly, alternating right/left each round.
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

// Play the animation when the element enters from below; reset once it leaves through the bottom
// (scrolling up), so the animation replays every time you scroll down again.
function replayOnEnter(trigger, anim, onReset) {
  // Inside [data-reveal-once] (e.g. The Good Stuff): play once, no reset.
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
  // Already scrolled past on page load (e.g. reload mid-page): show immediately.
  if (play.progress === 1 || window.scrollY > play.start) anim.progress(1);
  return () => {
    play.kill();
    reset?.kill();
  };
}

// Section headings (.bira-h2) reveal character by character when entering the viewport.
// Split by word first so words don't break mid-word when wrapping.
export function initSplitHeadings() {
  const headings = gsap.utils.toArray(
    '.bira-h2:not([data-split="center"]):not(.bira-h2--static)',
  );
  const centered = gsap.utils.toArray('[data-split="center"]');

  const mm = gsap.matchMedia();
  mm.add("(prefers-reduced-motion: no-preference)", () => {
    // Two lines split from the center line: the top line rises, the bottom line drops.
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
        mask: loop ? "chars" : undefined, // loop characters need a mask to slide in and out
        autoSplit: true, // re-split when fonts finish loading / on resize
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
