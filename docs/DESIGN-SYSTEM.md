# Samuel & Co 2026 — design system plan

Source of truth for the look: `reference/PHOTO-2026-09-10-11-51-31.jpg` (full homepage).
Implementation: Sass for structure, **CSS custom properties for every value**
(`assets/sass/scm/_tokens.scss`). Change a token, everything follows; no rebuild
needed for runtime theming (dark bands re-map the semantic tokens).

## 1. Tokens (what the image tells us)

| Group | Tokens | Read from the reference |
|---|---|---|
| Brand colour | `--navy` `--navy-2` `--gold` `--gold-deep` `--gold-soft` | Navy ticker/education band/CTA panel; gold buttons, hairline dashes, eyebrow text |
| Neutrals | `--ink` `--ink-2` `--muted` `--muted-2` `--paper` `--white` `--sand` `--line` `--line-strong` | Near-black serif headings, grey lead copy, off-white page, hairline card borders |
| On dark | `--on-dark` `--on-dark-muted` `--line-on-dark` | Ticker and education band text |
| Data | `--up` `--down` | Green / red ticker changes |
| Type | `--font-display` (serif) `--font-body` (sans) `--font-mono` (ticker) + `--text-*` `--display-*` scale | Big transitional serif for headlines, compact grotesk sans for UI, tabular figures in the ticker |
| Space | `--space-1…20` (4px base) | Tight card padding, generous section padding |
| Layout | `--shell-max` `--shell-gutter` `--prose-max` `--header-h` `--control-h` | 1450px shell, 84px header, inputs and buttons share a 46px height |
| Shape | `--radius: 0` `--radius-pill` | Everything square except the filter pills |

**Fonts are the one open decision.** The reference uses a serif close to Tiempos /
Source Serif and a sans close to Inter. Tokens currently fall back to Georgia / Arial.
The Typekit kit already licensed (`zqz1tva`) carries `elza` (sans) which could be
`--font-body`; a serif still needs choosing. Swap in `_tokens.scss` only.

## 2. Type scale

| Token | Size | Used by |
|---|---|---|
| `--display-xl` | 68px | Hero h1 |
| `--display-lg` | 48px | Morning Brief headline |
| `--display-md` | 34px | Section h2 |
| `--display-sm` | 27px | US Open h3, member CTA h3 |
| `--text-xl` | 20px | Hero lead |
| `--text-lg` | 17px | Article prose |
| `--text-base` | 13px | Card / brief copy |
| `--text-sm` | 11px | Nav, meta |
| `--text-xs` | 9px | Eyebrows, footnotes, ticker labels |

Display headings: weight 500, `letter-spacing: -1.8px`, `line-height ≈ 1`.
Eyebrows: 9px, uppercase, 2.7px tracking, `--gold-deep`, often followed by a 28px gold dash.

## 3. Component inventory (reference → partial → style)

| # | On the reference | Partial (`partials/components/`) | Status | Sass |
|---|---|---|---|---|
| 1 | Header: wordmark, EST. 2012, nav, search, Log in, Join Free | `header.php` (theme root) | dynamic (menu `header`, Woo cart/account) | `_header` |
| 2 | Hero: 3-line serif h1, lead, sub, email + gold button, St Paul's image, values column, quote | `hero.php` | dynamic via ACF options, defaults match reference | `_hero` |
| 3 | Proof bar: 4 icon + title + line | `static-proof-bar.php` | **static** | `_proof` |
| 4 | Ticker: 9 instruments with green/red change + "View full markets" | `static-ticker.php` | **static** (needs data feed) | `_ticker` |
| 5 | Morning Brief: eyebrow, h2, copy, gold button + archive link, numbered key points, image with caption | `morning-brief.php` | dynamic (category `morning-market-brief`, ACF `scm_key_points`) | `_brief` |
| 6 | US Open: eyebrow, h3, copy, button, archive link, video thumb with play | `us-open.php` | dynamic (category `us-open`, video post format) | `_brief` |
| 7 | What's Moving Markets: heading, "View all news", pill tabs, 4 cards | `moving-markets.php` + `card-post.php` | dynamic (latest posts, tabs from categories, JS filter) | `_tabs` `_cards` |
| 8 | Join Samuel & Co Markets: navy sticky panel, price button, tick list | `member-cta.php` | dynamic via ACF options, defaults | `_member` |
| 9 | Research: heading + sub, "View all research", 3 cards | `research.php` | dynamic (category `research`, static fallback cards) | `_cards` `_member` |
| 10 | Learn: heading, "Explore all topics", 6 icon topics in 2 cols | `static-learn.php` | **static** | `_learn` |
| 11 | Education band: navy, h2, Level 5 / Level 7 image cards | `education.php` | dynamic (ACF product or Woo product by slug; static fallback) | `_education` |
| 12 | Footer: wordmark, tagline, two link rows, socials, copyright | `footer.php` (theme root) | dynamic (menu `footer`, ACF socials) | `_footer` |
| — | Section heading row with "View all →" | `section-head.php` | args | `_typography` |
| — | Post card | `card-post.php` | dynamic | `_cards` |

Convention: `static-*.php` = hardcoded for now; the file header says what it needs to
become dynamic. Inside dynamic partials, any hardcoded fallback is marked `STATIC` in a
comment.

## 4. Sass structure

```
assets/sass/
  screen.scss            entry — legacy layer, then scm layer
  legacy/                ported 2022 partials (Woo, course player, forms, ACF templates)
  scm/
    _tokens.scss         all values (CSS custom properties) + dark-surface remaps
    _mixins.scss         structure-only mixins: bp-down/up, display(), eyebrow, gold-dash
    _base, _typography, _buttons
    _header, _hero, _proof, _ticker, _tabs, _brief, _cards, _member, _learn,
    _education, _footer, _article, _hub-pages, _site
    _responsive.scss     breakpoints (1050 / 680) — TODO fold into each partial
```

Rules: one partial per component; class prefix `scm-`, BEM-ish (`block__elem--mod`);
never write a raw hex or font stack in a component — add a token.

## 5. Next steps, in order

1. Decide fonts and load them (Typekit kit or self-hosted); update `--font-*`.
2. Move breakpoints out of `_responsive.scss` into the partials that own them, using `bp-down()`.
3. Wire the hero form (CF7 shortcode via the ACF option, or the CRM's embed).
4. Ticker: choose a data provider; keep `static-ticker.php` markup, feed `$ticks` from a transient-cached fetch.
5. Learn: model learning paths (taxonomy or child pages) and replace `static-learn.php`.
6. Proof bar: ACF repeater on the options page, then rename to `proof-bar.php`.
7. Hub pages (`page-*.php`) should use `card-post.php` and `section-head.php` rather than inline markup.
8. Restyle WooCommerce / My Account / course player with the tokens (they run on legacy styles today).
9. Single post: add prev/next nav and related cards using `card-post.php`.
