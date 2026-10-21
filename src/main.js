import './main.css';
import { initHero } from './hero.js';
import { initMockup } from './mockup.js';
import { initReveal } from './reveal.js';
import { initNav } from './nav.js';
import { initScrollLine } from './scroll-line.js';
import { initWork } from './work.js';
import { initSplitHeadings } from './split-heading.js';
import { initAccordion } from './accordion.js';
import { initSteps } from './steps.js';
import { initFeed } from './feed.js';
import { initFab } from './fab.js';
import { initIntroHeadings } from './intro-heading.js';
import { initScrollText } from './scroll-text.js';
import { initTilt } from './tilt.js';
import { initFlip } from './flip.js';
import { initCount } from './count.js';
import { initSmoothAnchors } from './smooth-anchor.js';

document.addEventListener('DOMContentLoaded', () => {
  initHero();
  initIntroHeadings();
  initScrollText();
  initTilt();
  initFlip();
  initCount();
  initSmoothAnchors();
  initMockup();
  initReveal();
  initNav();
  initWork(); // sebelum scroll line: pin-spacer harus ada saat garis diukur
  initSplitHeadings();
  initAccordion();
  initSteps();
  initFeed();
  initFab();
  initScrollLine();
});
