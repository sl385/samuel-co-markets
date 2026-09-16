# Samuel & Co 2026 theme (samuel-co-markets)

WordPress theme for samuelandcotrading.com. Cloned from
`git@github-work:sl385/samuel-co-markets.git` (push as the **stevebastion** account,
via the `github-work` SSH alias). Lives at
`wp-content/themes/samuel-co-2026/` inside the local WP install at
`~/Sites/Brandtastic-2022/samuel-co/` (DB `samandco26`). The WP root is NOT a git repo;
only this theme folder is.

## What this repo is

A **standalone theme**: the 2026 editorial design (Markets / Research / Education, built
against a Vercel mockup; `preview/index.html` is a self-contained copy of that mockup)
plus all business logic ported verbatim from the 2022 `samuel-co` theme on 11 Sep 2026.

Text domain / prefix: `samuel-co-markets` / `scm_`. Design classes are `scm-*`.

### Layout
- `functions.php` includes `lib/sco-helpers.php`, everything in `includes/` (same list and
  order as the old theme), then `includes/theme-enqueue.php` (the only place assets load).
- `includes/`, `lib/`, `woocommerce/`, `templates/`, `partials/`, `single-course.php`,
  `archive-event.php` are ported as-is from `../samuel-co`. Only edit: `theme-actions.php`
  had its two enqueue functions removed.
- 2026 templates: `front-page.php` (composed from `partials/components/*` via
  `scm_component()`), `single.php`, `page.php`, `index.php`, `header.php`, `footer.php`,
  and slug-matched `page-{markets,research,learn,education,membership}.php`.
- Components: `partials/components/`. `static-*.php` = hardcoded for now (header comment
  says what it needs to become dynamic); everything else is dynamic (posts, terms, Woo,
  ACF). Helpers in `includes/theme-components.php` (`scm_component`, `scm_opt`,
  `scm_opt_lines`, `scm_cat_link`, `scm_first_in_category`).
- ACF: `includes/theme-acf.php` registers a "Homepage (2026)" group on the existing S&Co
  Settings options page (hero, membership CTA, education products) and a key-points
  repeater on Morning Brief posts. Every field has a default in its partial.
- Design system: `docs/DESIGN-SYSTEM.md`. Tokens are CSS custom properties in
  `assets/sass/scm/_tokens.scss`; Sass is structure only. Never write raw hex/font
  values in a component partial — add a token. References in `reference/`:
  `PHOTO-2026-09-10-11-51-31.jpg` (approved homepage) and `design_system.png`
  (foundations + six specified components; its page thumbnails are NOT buildable).
  Full-size page mockups are coming and will land in `reference/` too. Component
  inventory and the UX master prompt: `docs/COMPONENT-SPEC.md`.

## Status (11 Sep 2026)
- Port complete, theme active locally, homepage renders from partials.
- ON HOLD for build work until all page mockups are in. Pending decisions: display
  serif (Tiempos / GT Sectra / free Source Serif 4); body sans is Elza (Typekit) or
  Inter. Next steps agreed: update tokens to the design-system sheet's small-text
  scale (20/17/15/13), build the six specified components on a styleguide page,
  replace FontAwesome with an inline SVG line-icon set, then build page by page.
- CSS: `assets/sass/screen.scss` → `assets/css/screen.css` via `npm run build` (dart-sass,
  `package.json`). Import order: legacy vars/mixins/reset → legacy functional partials
  (`assets/sass/legacy/`, everything except header/footer/home) → `assets/sass/scm/`
  (`_markets`, `_hub-pages` unminified from the original CSS, `_site` for ported shell bits).
  scm loads last so it wins ties. scm is one partial per component (`_tokens`, `_mixins`,
  `_base`, `_typography`, `_buttons`, `_header`, `_hero`, `_proof`, `_ticker`, `_tabs`,
  `_brief`, `_cards`, `_member`, `_learn`, `_education`, `_footer`, `_article`,
  `_hub-pages`, `_site`, `_responsive`). **Commit the compiled `screen.css`.**
- JS: `assets/js/markets.js` (mobile nav + `.scm-tabs` category filter), `js.js` (legacy site JS, jQuery), and on
  course pages `player.js` + `courses.js`. Slick only on legacy ACF templates and products.
- Images: `assets/img/` (legacy) and `assets/img/editorial/` (design photography,
  downloaded from the live site; referenced as `../img/editorial/*.webp` in scss and via
  `_i('editorial/...')` in PHP).
- Menus: `header` and `footer` locations (registered in `includes/theme-actions.php`).
- Header shows a Woo cart link when the cart is non-empty and an Account / Log in link
  pointing at the Woo My Account page. Footer carries the regulatory risk warning, ACF
  social links, phone, VAT/company line, plus the CF7 mailing-list (id 493) and press
  logos (ACF `footer_logos_new`) on non-account pages. Product/service pages get the
  CF7 815 join modal. Typekit (`zqz1tva`), FontAwesome 6 and cookie consent are loaded.

### What the ported logic contains (do not lose)
- `includes/theme-cpt.php` — CPTs: `team`, `success_story`, `mini_review`, `event`, `course`
- `includes/theme-courses.php` + `theme-ajax.php` + `woocommerce/course-compiler.php` +
  `lib/asset-load.php` + `lib/stream.class.php` — course/LMS: ACF module/lesson repeaters,
  per-user progress, Vimeo/HTML5 player, secure video rewrite
  `-/media/courses/asset/{key}/load` (rewrite targets `/lib/asset-load.php`, so `lib/`
  must stay at the theme root), admin meta boxes, user export.
- `includes/woocommerce/woo.php` (class `WC_SAM_CO`) + `user.php` — product hooks,
  custom product tabs, VAT billing, subscription price strings, hidden-product redirects,
  exports, user admin columns. 22 Woo template overrides in `woocommerce/`.
- `includes/theme-actions.php` — menus, ACF options page "S&Co Settings", content
  filters, dev-mode wp_mail blocking, add-to-cart redirects.
- `includes/theme-shortcodes.php` — `[sc_player]`, custom `[gallery]`.
- `lib/sco-helpers.php` — `SCO_Helpers::createMiniHeader()` etc.

Plugins in play: WooCommerce + Subscriptions, ACF Pro, CF7, Yoast, Wordfence, Jetpack,
S3MediaVaultPro, Classic Editor, post/taxonomy ordering.

## Known gaps

- Hardcoded placeholder content: US Open fallback copy, three fake research cards, Learn
  lessons array, membership pricing (£99 / £990), sub-nav links to `#`, ticker, Level 5 /
  Level 7 product URLs. Hero signup form posts to `#`.
- Assumes categories `morning-market-brief`, `us-open`, `research` and pages with the
  five hub slugs exist.
- Woo, My Account, course, event and legacy ACF templates render with **legacy** styles
  inside the new shell. They work but are not yet restyled to the scm design.
- The old Google Analytics UA tag was dropped (UA is dead). No replacement yet.
- `single.php` has no prev/next post navigation (the old one did).

## Local environment notes

- `wp` CLI is installed (`/usr/local/bin/wp`) but currently crashes on this site
  (memory exhaustion + "constant already defined" warnings, i.e. wp-config being loaded
  twice). Don't rely on it until fixed.
- Two GitHub accounts on this machine; see `~/.claude/CLAUDE.md`. This repo → stevebastion.

## Conventions

- Keep the `scm-` class prefix and `scm_` function prefix for new work in this theme.
- Escape output (`esc_html`, `esc_url`, `wp_kses_post`) as the existing templates do.
- Don't commit or push unless asked.
