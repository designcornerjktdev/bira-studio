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
import { initCount } from './count.js';
import { initSmoothAnchors } from './smooth-anchor.js';
import { initContact } from './contact.js';
import { initShare } from './share.js';

document.addEventListener('DOMContentLoaded', () => {
  initHero();
  initCount();
  initContact(); // before smooth anchor: clicking "Let’s talk" opens the pop-up instead of scrolling to #cta
  initSmoothAnchors();
  initShare();
  initMockup();
  initReveal();
  initNav();
  initWork(); // before scroll line: the pin-spacer must exist when the line is measured
  initSplitHeadings();
  initAccordion();
  initSteps();
  initFeed();
  initFab();
  initScrollLine();
});
