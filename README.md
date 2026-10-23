# BIRA WordPress Theme

Standalone classic WordPress theme (no parent theme) for the BIRA website. PHP templates, Tailwind CSS v4 and GSAP, bundled with Vite.

- **Requires:** WordPress 6.5+ (script modules), PHP 8.0+
- **Optional plugin:** Advanced Custom Fields (free), for the Project detail fields
- **Design source:** the `bira-preview` prototype; colors and fonts follow the BIRA Website Guideline

## Getting started

```bash
cd wp-content/themes/bira
npm install
npm run build   # one-off build → assets/dist/
npm run dev     # rebuild on every change (vite build --watch)
```

Then activate **BIRA** in *Appearance → Themes*.

The theme loads `assets/dist/main.js` (as a script module) and `main.css` from the build output. If `assets/dist/` is missing, the site renders without styles or animation, so **run the build before deploying**, or deploy `assets/dist/` with the theme.

Fonts (Hanken Grotesk + Caveat) load from the Google Fonts CDN.

## Folder structure

```
bira/
├── style.css               Theme header
├── theme.json              Color/font tokens (source of truth)
├── functions.php           Theme supports, menus, asset loading, GIF handling, /about redirect
├── front-page.php          Homepage: section order
├── header.php / footer.php
├── index.php               Blog archive (blog, category, tag, author, date, search)
├── single.php              Blog post detail
├── single-bira_project.php Project case study (/work/{slug})
├── singular.php            Regular pages
├── 404.php
├── inc/
│   ├── customizer.php      Homepage content schema (Customizer)
│   ├── projects.php        Project post type, ACF fields, Work lazy-load REST endpoint
│   └── template-tags.php   Template helpers (bira_text, bira_items, bira_button, doodles…)
├── template-parts/         One file per homepage section (file name = section id)
├── admin/                  Admin-only JS (Customizer gallery control, Stats tag input)
├── src/                    Front-end source (Vite entry: src/main.js, styles: src/main.css)
└── assets/dist/            Build output (generated, do not edit)
```

## Homepage

Sections render in this order (see `front-page.php`):

| Section | Template | Behavior |
|---|---|---|
| Hero | `hero.php` | Pinned; the mockup cards fan out across the full hero width with scroll (`src/mockup.js`) while the headline shrinks and the text fades away. Title letters react to the cursor (`src/hero.js`). |
| About | `about.php` | Bira Island image + map marker |
| Services (What We Do) | `services.php` | Accordion (`src/accordion.js`) |
| Process (How We Work) | `process.php` | Sticky step timeline (`src/steps.js`) + scroll-drawn line (`src/scroll-line.js`) |
| Trust | `trust.php` | Numbers count up (`src/count.js`) |
| Clients | `clients.php` | Logo marquee |
| Team | `team.php` | Desktop: pinned, cards spread into an arc (`src/feed.js`) |
| Work (The Good Stuff) | `work.php` | Desktop: pinned horizontal scroll; mobile: native swipe. Lazy-loads more projects (`src/work.js`) |
| POV | `pov.php` | Manifesto |
| CTA | `cta.php` | Final call to action |

The contact pop-up (`template-parts/contact-modal.php`, `src/contact.js`) is loaded on every page and opens from any `#contact` link, the header "Let's talk" button and the CTA button.

### Editing content

All homepage copy and images are in **Appearance → Customize → Homepage**, one Customizer section per homepage section (plus Contact pop-up and Footer). An empty field falls back to the default text in the template.

To add or change a field, edit the schema at the top of `inc/customizer.php`. The same schema generates the Customizer controls, sanitization and the values read by `bira_text()` / `bira_items()` / `bira_gallery()`.

### Menus

*Appearance → Menus* has two locations, **Primary Menu** (header) and **Footer Menu**. If a location is not assigned, it falls back to links to the homepage sections.

## Projects

The **Projects** post type (`inc/projects.php`):

- Detail URL `/work/{slug}`. There is no archive: `/work` redirects to `/#work`.
- Every published project appears in the homepage Work section, ordered by *Order* (page attribute) then newest. The first batch is server-rendered; the rest load from `GET /wp-json/bira/v1/work?offset=N` as the row nears its end.
- Project categories are tag-style (type new ones or pick existing).
- Case study content (Challenge, Insight, Idea, Execution, Content, People, Results) comes from ACF field groups registered in code. Install the free ACF plugin to edit them. Empty parts are skipped on the detail page.
- The Stats field uses a tag input (`admin/stats-tags.js`). Values are stored as comma-separated text, e.g. `20M+ Views, 150+ Content pieces`.

While no project is published, the Work section shows sample cards.

## Front-end notes

- **Animation:** GSAP + ScrollTrigger + SplitText. Each module in `src/` exports an `init*()` function called from `src/main.js`. **The init order matters:** pinned sections must be created before triggers that measure the page (see the comments in `main.js`).
- **Pinned sections:** Hero, Work and Team. After adding or resizing a pin, check that the triggers below it still line up. `ScrollTrigger.sort()` / `refresh()` are already called where pins are created late.
- **Reduced motion:** every animation module checks `prefers-reduced-motion` and falls back to static content or native scrolling.
- **No-JS:** content is hidden by JS, not CSS, so pages stay readable if the bundle fails to load.
- **Styling:** Tailwind utilities in the templates (scanned via `@source` in `src/main.css`) plus hand-written component CSS in `src/main.css`. Use the color tokens (`bg-cream`, `text-green`, `text-ink`…) rather than raw hex values.
- **Layout:** `.bira-container` + `.bira-grid` (12 columns on desktop, 1 on mobile).
- **Animated GIFs:** always served at full size without `srcset` so they keep animating (`functions.php`).
