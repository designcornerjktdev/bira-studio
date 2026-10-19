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
import { initPaper } from './paper.js';
import { initFab } from './fab.js';
import { initWorkFilter } from './work-filter.js';

document.addEventListener('DOMContentLoaded', () => {
  initHero();
  initMockup();
  initReveal();
  initNav();
  initWork(); // sebelum scroll line: pin-spacer harus ada saat garis diukur
  initSplitHeadings();
  initAccordion();
  initSteps();
  initFeed();
  initPaper();
  initFab();
  initWorkFilter();
  initScrollLine();
});
