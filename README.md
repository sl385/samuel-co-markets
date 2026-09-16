# Samuel & Co 2026 (samuel-co-markets)

Standalone WordPress theme for samuelandcotrading.com. Combines the 2026 editorial
Markets / Research / Education design with all of the business logic ported from the
2022 "samuel-co" theme (courses/LMS, WooCommerce customisations, CPTs, ACF options).

## Build

```
npm install
npm run build     # compiles assets/sass/screen.scss -> assets/css/screen.css
npm run watch
```

`assets/css/screen.css` is committed so the theme works without a build step.

## Structure

- `functions.php` — theme setup and includes
- `includes/theme-enqueue.php` — all front-end asset loading
- `includes/`, `lib/` — ported logic: CPTs, courses, AJAX, WooCommerce, ACF options, shortcodes
- `woocommerce/` — ported WooCommerce template overrides (incl. My Account "courses" tab)
- `templates/` — ported ACF page templates (home, about, contact, service, funded)
- `single-course.php`, `archive-event.php`, `partials/` — ported course player and events
- `front-page.php`, `single.php`, `page.php`, `index.php`, `page-{markets,research,learn,education,membership}.php` — 2026 templates
- `assets/sass/scm/` — the 2026 design system (`scm-*` classes)
- `assets/sass/legacy/` — legacy partials that still style Woo, courses, forms and ACF templates
- `assets/img/editorial/` — local copies of the design's photography
- `preview/index.html` — self-contained copy of the approved mockup; open it in a browser

## Homepage content mapping
- `morning-market-brief` category → Morning Brief lead
- `us-open` category → US Open module
- `research` category → Research cards
- latest posts excluding the two daily briefs → What's Moving Markets

## Menus
Uses the same locations as the old theme: **Header Menu** (`header`) and **Footer Menu**
(`footer`). Reassign them after activating (menu assignments are per theme).

## Before production launch
1. Deploy to staging first and reassign the two menus.
2. Create the `morning-market-brief`, `us-open` and `research` categories and the
   `markets`, `research`, `learn`, `education`, `membership` pages.
3. Replace hardcoded placeholder content: ticker, sub-nav links, Learn lesson list,
   membership pricing, US Open fallback copy, fake research cards, Level 5 / Level 7 URLs.
4. Connect the Morning Brief hero form (currently posts to `#`).
5. Connect the market ticker to a licensed data source.
6. Review financial-promotion, research and disclosure wording.
7. Test WooCommerce checkout, subscriptions, My Account, course player and member flows.
8. Restyle Woo / account / course pages to the new design (they currently use legacy styles).
9. Add analytics (the old UA tag was dropped; UA no longer collects).
