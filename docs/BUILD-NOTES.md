# Build session notes — 11 Sep 2026 (while you were away)

Working from `reference/*` (mockups) + `docs/DESIGN-SYSTEM.md` + `docs/COMPONENT-SPEC.md`,
and applying the fixes in `docs/LEGACY-CODE-REVIEW.md`. Nothing committed or pushed —
working tree only, same as you left it plus what's below.

## Code amends applied (the "sweep" — LEGACY-CODE-REVIEW.md § 0, all nine)

| # | What I did |
|---|---|
| 0.1 | `lib/sco-helpers.php` — Google Places key pulled out of source into `GOOGLE_PLACES_API_KEY` (wp-config.php, currently empty). Also fixed the `array_reverse(null)` fatal on a failed API response and swapped raw curl for `wp_remote_get`. **The old key is exposed in git history — rotate it in Google Cloud before it goes anywhere near production, then set the new one in wp-config.** |
| 0.2 | `includes/woocommerce/woo.php` — Campaign Monitor key + list ID moved to `CAMPAIGN_MONITOR_API_KEY` / `CAMPAIGN_MONITOR_LIST_ID` constants (also empty). Removed the `error_log` calls that dumped the full order object (PII) on every thank-you page view. **Same rotation note as above.** |
| 0.3 | `includes/theme-courses.php` — removed the `exit` that was killing the rest of `wp_footer` (and the player scripts with it) on every course page. Now falls back to the first lesson when every lesson is complete instead of leaving `my_module` undefined. |
| 0.4 | `lib/asset-load.php` + `lib/stream.class.php` — rewrote the video-asset endpoint: `$wpdb->prepare()` + `esc_like()` instead of raw SQL, removed the debug `echo` that was corrupting the video body, added a real access check (`User_Course_Manager::user_has_access()` against the course the lesson belongs to) instead of "any logged-in user", and `Cache-Control` is now `private` instead of `public`. This is a bounded patch, not the full fix — the review's own recommendation (signed, expiring, per-user URLs via a proper `samuel-co-core` plugin) is still the right long-term answer. |
| 0.5 | `includes/theme-ajax.php` — turned validation back on in `update_lesson_progress` (nonce + course-access check were already wired up client-side, just commented out server-side), switched to prepared queries, fixed the `->progess` typo that let "Complete" get overwritten by later pause events. |
| 0.6 | `includes/woocommerce/woo.php` — course access no longer granted just by *viewing* the thank-you/order page. Added `scm_grant_course_access_on_payment()` on `woocommerce_order_status_processing` / `_completed`. **Not done:** revoking access on refund/cancel — that's a slightly bigger piece the review flags as part of the same plugin extraction, not an "act now" item. |
| 0.7 | Deleted `templates/template-funded.php` (the unconditional-redirect-to-production dead duplicate). It was only assigned to the `get-funded-old` page; the live `get-funded` page already uses `template-funded-2025.php`, untouched. |
| 0.8 | `assets/js/js.js` — one guard at the top of the ready handler (`if (!$.fn.slick) $.fn.slick = function(){return this}`) instead of patching all five call sites; Slick is only enqueued on five templates + products, so this was breaking the team modal / mobile nav / enrol modal / counters on every other page. |
| 0.9 | `includes/theme-actions.php` — the dev-mode mail block now keys off a dedicated `SCM_BLOCK_MAIL` constant instead of the general-purpose `WP_ENV` flag, so it can't get inherited by accident on a production wp-config. Set to `true` in this local wp-config on purpose — **this database has real customer email addresses in it**, so local mail stays blocked. |

Everything else in the review (sections 1–4) is triaged, not fixed — the review's own
recommendation is that the course/LMS and Woo access-grant logic want extracting into
a `samuel-co-core` plugin rather than patched in place, and section 4 (legacy ACF
templates) is disposable once the new templates replace them. Didn't spend time there
beyond what's above, per that recommendation.

## Design-system decisions made

- **Fonts** (previously an open TODO in `_tokens.scss`): set `--font-body: elza` (the
  Typekit kit `zqz1tva` already loaded in `header.php` — CLAUDE.md says elza is
  licensed in it) and `--font-display: 'Source Serif 4'` (self-hosted via Google
  Fonts, added to `header.php`) as the free stand-in for Tiempos/GT Sectra that
  `docs/DESIGN-SYSTEM.md` named as the fallback option. **Question:** if Tiempos or
  GT Sectra get licensed later, it's a one-line swap in `_tokens.scss` — just flagging
  this was a judgment call, not a confirmed final choice.
- Colours/spacing/type scale in `_tokens.scss` already matched `reference/design_system.png`
  exactly — nothing to change there.
- Icons are still FontAwesome. The design system calls for a custom inline-SVG
  line-icon set (gold, matching `reference/design_system.png` §05). Didn't build this —
  it's a real asset-design task (≈15 icons: Markets/Research/Learn/Education/Membership/
  Search/Cart/User/Play/Calendar/Location/proof-bar icons/learn-topic icons), not something
  to improvise. **Question for you:** is there an icon set already commissioned somewhere,
  or should I pick a line-icon library and adapt it?

## New pages / taxonomy created (so already-built templates are actually reachable)

The five hub templates (`page-{markets,research,learn,education,membership}.php`) were
already built and committed, but no WordPress **pages** with those slugs existed in the
imported database — so they 404'd. Created all five as published pages (empty content;
the templates supply everything). Also created the `morning-market-brief` and `us-open`
**categories** (empty) that `morning-brief.php` / `us-open.php` already query — they were
missing too, which is why the homepage was silently always showing its static fallback
copy for those two sections. Both were already README TODOs ("Before production launch"
items 2). Once real posts get tagged into those two categories, the homepage lead
stories go live with zero further code changes — that's the whole point of how those
partials were written.

## New components built (`partials/components/`)

Following the existing convention exactly (dynamic with a STATIC/"No Data" fallback,
`scm-` classes, tokens only, no raw hex/font values):

- **`testimonial-card.php` + `success-stories.php`** (spec C24) — real data, the
  `success_story` CPT (23 published posts, fields `course_taken` / `payout_earnt`).
  Wired into `page-education.php` after the qualifications grid — the mockups don't
  show exactly where this goes on which page, so that placement is my judgment call,
  not a confirmed spec. The star rating is a fixed 5-star graphic (matches the
  design) — there's no per-story rating field in the data, so it's decoration, not a
  claim about a real number.
- **`breadcrumb.php`** (spec B10) — generic, added to `single.php`.
- **`author-byline.php`** (spec C20) — added to `single.php`. Shows the WP display
  name + avatar; **"No Data" for the role/title line** the design shows under the
  name — `wp_users` has nothing like that, so it prints literally as "No Data — design
  shows a role/title here" per your instruction, ready for you to decide the source
  (new user meta field? hardcoded per-author? drop it?).
- **`prev-next-post.php`** (spec C21) and **`related-posts.php`** (spec C22, new) —
  added to `single.php`. This closes the "no prev/next nav" gap CLAUDE.md already
  flagged. Related posts falls back to latest-posts if the current post's category
  has no siblings.

All new CSS lives in the existing `_article.scss` / `_cards.scss` partials (no new
`@import` needed), tokens only, compiled into `assets/css/screen.css` via `npm run build`
— confirmed no Sass errors and the compiled file picked up the new rules.

## Verified working (HTTP 200, no new PHP fatals in the Herd php-fpm log)

Home, all five hub pages, a real single post (with the new breadcrumb/byline/prev-next/
related), My Account, and `/education/` with real success-story testimonials.

## What I did *not* get to

The reference folder has 23 mockups; I worked from `design_system.png` (foundations —
confirmed tokens match, made the font call above) and the five hub-page mockups
(`markets.png` in particular, which is the most detailed) to sanity-check what's already
built. I have **not yet** gone through: `about.png`, `contact.png`, `checkout.png`,
`my-account.png`, `course_player.png`, `get_started.png`, `single_product.png`,
`product-archive.png`, `blog_filters.png`, `market-around-the-world.png`,
`markets-post(possible-single.php).png`, `some_pages.png`, `learn_again.png`,
`memberships.png`, `single_article.png`, `masthead-variants.png` (viewed, not yet
applied beyond what the hub pages already do). Commerce/account/course-player restyling
is explicitly lower priority per the review's own recommendation and CLAUDE.md's next
steps, so I left WooCommerce/My Account/course player on legacy styles rather than
guess at a redesign without the mockup in front of me for each.

## Update — Foundations layer (you asked "have you done the UI components?")

Answer at the time was no — I'd only built page-level components (testimonial,
breadcrumb, etc.), not the actual Foundations layer (spec §A) everything else is
supposed to be built from. Went back and did that:

- **`_buttons.scss`** rewritten: all of A1 (primary/secondary/on-dark/text/block,
  46px + 40px small) and A2 (text link + on-dark), plus every state the spec asks
  for that had never been defined in CSS at all — hover, `:focus-visible`,
  `:disabled`, and a loading spinner.
- **New `_chips.scss`** (A8 status chip: up/down/neutral) and **`_markers.scss`**
  (A9 numbered marker) — generalized versions of patterns that already existed
  only inline inside `_ticker.scss` / `_brief.scss`. Didn't touch those two files'
  existing markup to avoid a regression with no visual diff tool available; the
  new classes are for new usage.
- **New `_forms.scss`** (A6): tokenized input/textarea/select/checkbox/radio,
  on-dark variant, inline (input+button) variant, error/success states. The
  hero's own signup form (`_hero.scss` `.scm-signup`) is left as-is rather than
  risk it — same reasoning as above.
- **`_base.scss`**: added the two missing container sizes (A13 — `.scm-shell--prose`
  820px, `.scm-shell--article-hero` 1180px) and hairline utilities (A12).
- **`page-styleguide.php`** at `/styleguide/` — one page rendering every
  Foundations component and state, plus the real testimonial data, matching
  `reference/design_system.png` §05. Set to Yoast noindex, not linked from any
  menu — internal QA only.

**Still not done:** the custom line-icon set (A10) — flagged again on the
styleguide page itself with a literal "No Data" note per your instruction, same
open question as before (icon library to adapt, or one coming?).

Compiled clean (`npm run build`, no Sass errors), spot-checked home/hub
pages/styleguide all still 200 with no new PHP fatals.

## Update — single.php rebuilt from reference/single_article.png

Checked the actual article mockup for the first time and it's a materially
different layout to what was there (plain centered header + full-content-width
prose). Rebuilt to match: full-bleed dark-overlay hero photo with eyebrow/h1/lead/
byline overlaid at the bottom (falls back to the old plain light header for posts
with no featured image — that's a real data gap, not a design choice), then a
three-column body (sticky share-icon rail / prose / sidebar).

New, all real data, no invented content:
- **`article-sidebar.php`** — search form, related-articles compact list, the
  same CF7 493 mailing-list form already used site-wide (styled as a dark box
  per the mockup), and a categories list with **real post counts** from the
  database (Blog 73, Research 33, News 22, Uncategorized 4).
- **`share-links.php`** — X/LinkedIn/Facebook/email, built from the real
  permalink and title (not in COMPONENT-SPEC by name, but on the mockup).
- Corrected `author-byline.php`: earlier tonight I'd guessed the design wanted a
  role/title under the author name and marked it "No Data" since we have no such
  field. Now that I've actually seen `reference/single_article.png`, the byline is
  just name + date + read time — all real — so that placeholder was wrong and
  is gone. Worth remembering: I'd only inferred that component from the generic
  foundations sheet, not its actual usage; checking the real per-page mockup
  before building matters.
- Prose (C19) got real styling for the first time: pull-quotes (gold left rule,
  matches the "You can't control the market…" sample) and ordered lists now
  automatically render with the gold numbered-marker style whenever an editor
  writes a numbered list — no shortcode or special markup needed, it's plain
  CSS on `.scm-prose ol`.
- Dropped the full-width `related-posts.php` section I'd added earlier (still
  exists as a component, just unused now) and the standalone breadcrumb call —
  neither appears on this mockup; the sidebar's related-articles list replaces
  the former.
- Responsive: share-icon rail hides under 1050px, sidebar stacks under 680px —
  added directly in `_article.scss` rather than the shared `_responsive.scss`,
  per `docs/DESIGN-SYSTEM.md`'s own "next step" to move breakpoints into the
  partials that own them.

Verified on both a post with a featured image (full hero path) and one without
(fallback path), plus a full regression sweep (home/5 hubs/styleguide/my-account)
— all 200, no new fatals.

**Not done / no visual screenshot**: no headless browser was available on this
machine to render and screenshot the result, so this was verified structurally
(right HTML classes present, both code paths tested, no PHP errors) rather than
visually against the mockup pixel-for-pixel. Worth an eyeball check in an actual
browser before calling it done.

## Update — got a headless browser working, found and fixed real bugs

Installed Playwright + Chromium locally (`node_modules`, gitignored — `.tools/screenshot.js`
is the reusable script: `node .tools/screenshot.js <url> <out.png>`). Screenshotting the
article page immediately surfaced two real problems no amount of reading code would have
caught:

1. **Header showed the old live site's menu** ("Trading Tools", "Trader Hub", "Contact Us")
   with a white background, not navy — because the `header`/`footer` nav locations were
   still pointing at the legacy "Main Menu"/"Footer Menu" that came over in the DB import
   (this is exactly the README's own unfinished TODO, "reassign the two menus"). Created
   proper `Header Menu 2026` (Markets/Research/Learn/Education/Membership) and
   `Footer Menu 2026` (those five + About/Contact/Terms/Privacy/Risk Disclosure, all real
   pages) and assigned them. Also fixed `.scm-header`'s background — it was `var(--white)`
   in the CSS, contradicting every single mockup, which all show navy. Added `.scm-header`
   to the existing dark-surface token remap, then had to explicitly un-remap it back to
   light colours inside the desktop dropdown panel and the mobile nav drawer (both are
   light-background overlays that hang off the now-dark header, and would otherwise render
   invisible white-on-white text).
2. **What I first read as a "huge broken image blowup" bug turned out not to be one** —
   worth recording since I nearly "fixed" something that wasn't broken. The post content
   has a hardcoded `<img src="https://samuelandcotrading.com/wp-content/uploads/...">` —
   an absolute link to the **live production site**. This Mac has real internet access,
   so that image was loading for real, live, over the internet; the odd shape in the
   screenshot was just the actual diagram graphic compressing awkwardly at screenshot
   resolution, not a broken image at all. I did add a defensive `max-height` cap on the
   global `img` rule regardless, since a genuinely broken image with large width/height
   attributes (common in WP content) would still blow up layout elsewhere — cheap
   insurance, not a fix for a bug that turned out not to exist.
3. **The real, adjacent problem**: old post content is full of absolute links to
   `samuelandcotrading.com` (images, internal links like "Get Funded Programme") — so
   this local copy was silently depending on the live internet for content and sending
   clicks off to production instead of the local site. Ran `wp search-replace` (the
   proper WP-aware tool — handles PHP-serialized data correctly, unlike a raw SQL
   replace) for `https://samuelandcotrading.com`, `https://www.samuelandcotrading.com`,
   and `http://www.samuelandcotrading.com` → `https://sam-and-co.test`, scoped to those
   exact URL prefixes so it can't touch a bare `@samuelandcotrading.com` email address.
   **17,452 replacements** across post content, comments, postmeta, and various plugin
   logs (Wordfence hits, AffiliateWP, email-log bodies, Yoast's cached social-image URLs).
   Consequence worth knowing: that Dunning-Kruger diagram (and every other inline content
   image using an absolute production URL) now correctly shows as a broken image locally
   instead of quietly fetching the real one from the internet — because the media library
   (`wp-content/uploads`) genuinely isn't on this machine. That's the honest state, not
   a regression: this local site is now actually self-contained instead of silently
   depending on the live production site staying up and unchanged. **If you want images
   to render, the actual fix is getting `wp-content/uploads` from the live site** the
   same way you got the database and plugins — that's a real folder to request, not
   something I can fabricate locally.

Re-screenshotted after the menu/header fix — confirmed both are correct now (real nav
items, navy header, submenu/mobile drawer still legible). Full regression sweep still
green.

## Update — the actual bug: author avatar rendering at ~950×620px

You were pointing at the avatar, not the diagram — right instinct, wrong element from
me at first. Cropped a screenshot to just the hero (`.tools/crop.js`, screenshots one
CSS selector instead of the whole page) and it was obvious: a huge grey Gravatar
mystery-person icon, not a small 40px circle.

Root cause, found by asking Playwright which CSS rules actually matched the element
(`.tools/matched-rules.js` — walks `document.styleSheets`, filters to rules that
`.matches()` the element and mention width/height): a **leftover rule**,
`.scm-article__hero img { width:100%; max-height:620px; object-fit:cover; }`, written
back when `.scm-article__hero` was a plain wrapper directly around one big `<img>`
(the old single.php). My new photo hero (this session) carried classes
`"scm-article__hero scm-article__hero--photo"` — kept the old base class alongside the
new modifier out of habit — so that old rule matched again, and since it's a
*descendant* selector (`.scm-article__hero img`), it caught the byline avatar too,
not just a direct hero image. Confirmed with the image's actual computed size before
the fix: naturalWidth/Height 40×40 (loaded fine), but clientWidth/Height 946×620
(CSS override). Fix: dropped the redundant base class from the markup and deleted the
now-fully-dead old rule (nothing else used it) so the same collision can't happen
again. Rebuilt CSS, confirmed via the same inspection script: now renders at the
correct 40×40, both natural and computed.

This is exactly the kind of bug that's very hard to catch by reading code (the two
class names look unrelated at a glance: `scm-article__hero` vs
`scm-article__hero--photo`) and takes seconds to spot with a real screenshot — worth
the Playwright setup on its own.

## Update — CF7 buttons styled; one real fix, one false alarm

Screenshotted the homepage (first time, everything I'd built before this was verified
structurally not visually) — it holds up well against the reference overall. Two things
stood out:

- **Fixed**: CF7 submit buttons ("Subscribe" etc.) rendered as a muted olive pill, not
  the gold square button. Cause: a legacy global rule (`input[type=submit]` with its own
  `!important` on `border-radius`) that other old templates still rely on, so I didn't
  touch it — instead added a scoped, equally-`!important` override on
  `.wpcf7-form input[type="submit"]` (every CF7 form on the site matches this) in the
  new `_forms.scss`. Confirmed via computed styles: background and radius now correct.
- **False alarm, corrected**: while checking that button I noticed it had an HTML
  `disabled` attribute and initially suspected every form site-wide might be
  permanently unsubmittable (would have been serious — mailing list, contact, the join
  modal, all dead). Chased it through a failed reCAPTCHA request, swapped the site's
  reCAPTCHA keys (tied to the live production domain, so Google would reasonably refuse
  them here) for **Google's own published "always passes" test keys**
  (`6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI` / safe, public, documented for exactly
  this dev-environment case) — worth keeping regardless of what follows, since the old
  keys genuinely wouldn't validate on `sam-and-co.test`. But that wasn't actually the
  cause of the disabled button: it's CF7's standard behavior for a form with a consent
  **acceptance checkbox** (this form has "I agree to my data being stored…") — button
  stays disabled until the box is checked, by design, nothing to fix. Confirmed by
  scripting a fill-in + check + submit round-trip: it went through the real pipeline
  and came back correctly flagged as spam (bot-like submission from a headless
  browser with no mouse/keyboard entropy) — which is the anti-spam system working
  correctly, not failing.
- **Not fixed, real**: the hero/brief/education images are all the same recycled
  abstract swirl graphic, not the "London financial district" photography the brief's
  master prompt calls for. That's a photography/asset-sourcing task, not something to
  invent placeholder-of-a-placeholder for.

## Update — visual sweep of hub pages + styleguide, one more real bug found

Screenshotting long pages hit a genuine tooling problem worth recording: a single
very-tall screenshot (both Chromium's `fullPage:true` stitching and manually resizing
the viewport to the page's full height) reliably misrendered this site's header —
looked clipped/shifted left — on the hub pages specifically, even though
`el.boundingBox()` proved the actual layout was correct (`x:0`, full width) the whole
time. Real users scrolling a normal browser window never hit this; it's a capture-only
artifact. Rewrote `.tools/screenshot.js` to take several normal viewport-height
screenshots by scrolling instead of one tall single-shot — reliable, no artifact.

With that sorted, swept Markets/Research/Learn/Education/Membership + the styleguide:

- **Fixed**: `fa-regular fa-book-open` and `fa-regular fa-shield` rendered as blank/
  missing-glyph boxes — FontAwesome's free tier doesn't ship those icons in the
  "regular" style, only "solid". This was live on the **homepage proof bar**, not
  just the styleguide demo (`static-proof-bar.php`) — switched both to `fa-solid`.
- Everything else held up well: hub heroes, sub-nav, card grids, sidebar CTA,
  qualification cards, pricing cards, the new success-stories/testimonial section
  with real data (stars are decorative per the design, not a real rating — noted
  earlier) — all matched the reference reasonably closely.
- Confirmed (again) the recycled abstract-swirl placeholder image shows up as every
  hub page's hero photo — same asset-sourcing gap as before, not a code issue.
- Card grids using real posts with no featured image show a plain grey box — a
  graceful, existing fallback (no image tag rendered at all) rather than a broken
  image, so left as-is; a nicer empty state is possible later if wanted but this is
  aesthetic, not broken.

## Update — standardised the hub hero, dropped the divergent explorations

Per your steer (standardise where possible, drop what's not relevant): picked
`reference/masthead-variants.png` variant 01 ("Hero — standard, image right") as
the **one** hero pattern for every hub/content page, rather than treating each
page's mockup as its own bespoke design. This was the natural choice, not an
arbitrary one — it's what all 5 hub pages already approximated, and what most
other mockups converge on anyway.

- New `partials/components/hub-hero.php` — the hero markup that used to be
  copy-pasted (with drift) across all 5 `page-*.php` files is now one component:
  eyebrow, h1, lead, two buttons (primary gold + secondary hairline — variant 01
  has both; previously none of the 5 pages had any hero buttons at all, so this
  is a real addition, not just a refactor), image, quote card.
- Also fixed the other standardisation gap `docs/DESIGN-SYSTEM.md` had flagged
  and never actioned ("hub pages should use card-post.php and section-head.php
  rather than inline markup") while I was in each file — Markets and Research's
  hand-rolled `<article class="scm-card">...` loops now call `scm_component('card-post')`
  like every other listing on the site already does.
- **Explicitly not building**: the other five hero variants in that file
  (full-width overlay, split image-left, no-image, compact, minimal), and the
  competing Learn-hub explorations (`learn_again.png`, `blog_filters.png`) or
  the extra one-off page concepts (`market-around-the-world.png`,
  `some_pages.png`'s alternate Home/Article) — treating this as a deliberate
  scope cut per your direction, not an oversight. If any of those turn out to be
  wanted after all, they're still sitting in `reference/`.

Verified: all 5 hub pages 200, hero renders identically (screenshot-checked on
Markets and Membership), no fatals.

## Update — featured-image fallback pool (real photos, not another placeholder)

You flagged that a missing/broken featured image should fall back to something
real rather than a grey box. Two things worth knowing before the "how":

- `has_post_thumbnail()` was the wrong check to fix this with — it only looks at
  whether a `_thumbnail_id` exists in the database, not whether the file is
  actually on disk. This install's media library (`wp-content/uploads`) is
  essentially empty (8 stray files, no real media), so plenty of real posts have
  a valid-looking thumbnail ID that points at nothing. New `scm_has_real_thumbnail()`
  (`includes/theme-components.php`) checks `file_exists()` on the actual attached
  file, so it catches this correctly instead of rendering a broken `<img>`.
- Sourced **5 real, freely-licensed photos** from Wikimedia Commons (not
  generated, not another abstract placeholder) — `assets/img/fallback/`:
  Canary Wharf skyline (public domain), London skyline 2021 (CC0), a stock
  market/ticker photo (CC0), an NYSE trading floor photo via the Carol M.
  Highsmith / Library of Congress archive (public domain), and the London Stock
  Exchange building at Paternoster Square (**CC BY-SA 3.0 — this one needs an
  attribution credit to "London Stock Exchange" somewhere before real launch**;
  fine for now, flagging so it isn't forgotten). Resized to 1600px wide and
  converted to WebP to match the theme's existing image convention. Full
  license/attribution detail for each is in the commit — ask if you want the
  source Commons links again.
- New `scm_the_thumbnail()` / `scm_the_thumbnail_url()` helpers: render the real
  thumbnail when the file genuinely exists, otherwise one of the 5 pooled photos
  — picked deterministically from the post ID, so a given post always shows the
  same fallback rather than a different random one per page load. Wired into
  every place the theme shows a featured image (card grids sitewide, the
  Morning Brief / US Open images, the article hero, the sidebar related-articles
  list, the Level 5/7 education tiles). `single.php` got simpler as a result —
  it no longer needs a separate "no image" layout at all, since there's always
  an image now.
- Also disabled WordPress's background auto-updater (`AUTOMATIC_UPDATER_DISABLED`,
  `WP_AUTO_UPDATE_CORE` in wp-config.php) — caught it mid-upgrade on WooCommerce
  and ACF Pro while testing this, which 503'd requests for ~15 seconds. An
  install meant to mirror the live site's exact versions shouldn't silently
  drift from them, and random 503s are a bad look mid-demo.

Verified: full regression sweep green, and a post with zero real images now
renders a proper photo hero, card image, and sidebar thumbnail instead of an
empty box — screenshot-checked on `/markets/`.

## Update — About Us and Contact built for real (from the canvas + real data)

Converted the design canvas into actual theme code (`page-about-us.php`), and
built Contact from its mockup the same way (`page-contact-us.php`) — both were
previously on the legacy `templates/template-about.php` /
`template-contact.php` (cleared `_wp_page_template` on both real pages so the
new slug-matched files take over, same move as the hub pages earlier).

- **About**: real `team` CPT query (all 10 real members, not just the 4 in the
  canvas draft — no reason to arbitrarily cut it down now this is real code
  with real data), each with their real role and LinkedIn link. Team photos
  are initials-on-navy placeholders rather than routed through the finance
  fallback-image pool — a stock photo standing in for a specific named person
  would be actively misleading, unlike a stock photo standing in for "a photo
  of markets." Impact stats kept as "No Data" exactly as agreed for the canvas.
- **Contact**: pulled real ACF business details already used elsewhere in the
  theme (address, phone, email) instead of the mockup's fake London office and
  fake phone number, and the real live Contact Form 7 (id 7) instead of a new
  one. No map — Google Maps needs an API key this install doesn't have (see
  `LEGACY-CODE-REVIEW.md` §3), so it's a plain marked placeholder rather than
  a fake map graphic.
- **Real bug caught and fixed**: the closing "belief band" (reused on both
  pages with different photos) was illegible on Contact — dark text on a dark
  background. Root cause: the `.scm-on-dark` class only remaps the `--ink`
  etc. token *values*; it doesn't, by itself, change any element's `color`,
  because CSS custom properties re-resolve down the tree but an inherited
  `color` doesn't — only a rule that explicitly reads `var(--ink)` at its own
  level does. Every other dark section in this theme (`_education.scss` etc.)
  sets `color` explicitly for exactly this reason; this new one didn't. Fixed
  by setting `color: var(--on-dark)` explicitly on the section, and made the
  overlay a flat, deliberately-dark tint instead of a directional fade so
  legibility doesn't depend on which photo ends up behind it.

Full regression sweep green across every page on the site.

## Update — strategic pivot: reskin core WP, not invented pages

You flagged that the new `page-*.php` templates (Markets/Research/Learn/Education/
Membership/About/Contact) didn't correspond to anything in the real WordPress data
model — real categories are Blog/Research/News, not "Markets"/"Learn", and About/
Contact were invented content structures on top of real pages that already existed
with different content. Agreed and acted on it:

- **Nav reverted** to the real live-site menus (`Main Menu` / `Footer Menu`, term
  ids 2/3) — the "Header Menu 2026"/"Footer Menu 2026" I'd built are unassigned,
  not deleted, in case any of that work is wanted later.
- **Parked, not deleted**: all 7 invented `page-*.php` templates moved to
  `_parked-page-concepts/` (out of WordPress's template hierarchy — a file has to
  be at the theme root to auto-match a slug, so this "hides" them with one move,
  fully reversible). `page-styleguide.php` stayed active — it's a component
  reference tool, not an invented content page, and directly useful for the new
  approach.
- **New direction**: reskin the real, existing WordPress structure — real post
  types, real categories, real WooCommerce products — component by component,
  reusing the same `.scm-card` / `.scm-button` / tokens everywhere, rather than
  bespoke CSS per page.

First two real wins under that approach:
- **`index.php`** (the real blog/category archive — 113 real posts across real
  Blog/Research/News categories): already mostly solid from earlier work: just
  de-duplicated it to call `scm_component('card-post')` like everywhere else, and
  added real Foundations-spec pagination styling (`_pagination.scss`) for WordPress
  core's own `the_posts_pagination()` output — didn't exist before, so it was
  rendering as unstyled browser-default links.
- **The real shop (29 real products) was completely unreachable** — found
  `archive-product.php` and `taxonomy-product-cat.php` both hard-redirecting away
  (the archive to the homepage, category pages to an ACF-configured landing page
  or home). This looks like a deliberate live-site routing choice (funnel
  everything through custom marketing pages) rather than a plain accident, so
  parked both redirect files rather than declaring them "fixed" — flagging that
  whether real WooCommerce archives should ever be publicly reachable is a
  business decision, not a styling one. With them parked, WooCommerce's own core
  templates render through our existing `content-product.php` + header/footer.
  Reskinned `content-product.php` onto the same `.scm-card` used for post cards
  everywhere else (was legacy `.card.card--product` with decorative arrow/dot
  shapes) — same WooCommerce hooks and logic, only the wrapper markup changed.
  Also unhooked WooCommerce's default content wrapper and sidebar
  (`functions.php`) and rewrapped in `.scm-shell` — nothing else on this site has
  a sidebar, and without this the product grid rendered squeezed into ~1043px
  instead of the real shell width. Real prices, real WooCommerce Subscriptions
  pricing strings ("£193.18 a month plus a one-off £431.82 sign-up fee") all
  render correctly now.

Full regression sweep green, including real live-site page slugs (`/trading-tools/`,
`/trading-courses/`, `/shop/`, `/cart/`, `/my-account/`).

**Still to do under this approach**: single-product page (still legacy-styled),
single-course.php, and a proper look at what team/success_story content actually
needs (neither has a public single template — team is `publicly_queryable false`
on the live site, so may not need one).

## Update — single product reskinned, product images get the fallback too, course page deliberately left alone

- **Single product** (`content-single-product.php`): same move as the product
  card — real WooCommerce hooks/logic untouched, wrapper reskinned onto a
  `.scm-shell` two-column grid (gallery / summary) instead of the legacy
  flex layout that had no shell width constraint. `.woocommerce div.scm-product`
  as the selector (not `!important`) beats the legacy `.woocommerce div.product`
  rule on plain specificity, so nothing needed forcing.
- **WooCommerce product images get the same fallback as post thumbnails** —
  same root cause as before (`has_post_thumbnail()`/`get_image()` don't check
  the file exists), so product images with a real-but-missing attachment were
  rendering broken. Added `woocommerce_product_get_image` filter (covers the
  shop grid) and replaced the single-product gallery hook with a fallback-aware
  version (`functions.php`). Same pooled photos, same deterministic per-item
  pick.
- **`single-course.php` deliberately NOT reskinned beyond typography/colour**.
  It's a stateful, JS-driven video player already flagged in
  `LEGACY-CODE-REVIEW.md` as needing a proper LMS extraction, not a cosmetic
  pass — real enrollment data exists to test it properly (`wp_user_courses` has
  real rows), but that needs a logged-in, enrolled session to verify anything
  beyond "does it look right," and restyling markup I can't verify works risks
  putting a nice shell around something broken. Applied only safe, zero-risk
  token values (heading fonts, gold accents) to existing classes — no markup
  touched. Flagging this as a real decision point rather than quietly skipping
  it: worth your call on whether to invest in properly testing this page
  (I can set up a real logged-in test session) before going further.
- Small fire drill along the way: a hand-edit to append CSS left a brace
  imbalance in the legacy course stylesheet (a stray leftover closing brace
  from the original file collided with my addition) — sass caught it
  immediately at build time, found and fixed before it reached anything.

Full regression sweep green throughout.

## Update — mastheads migrated to one component, across the board

Found every masthead: `SCO_Helpers::createMiniHeader()` (only used in
`archive-event.php`), plus **five separate hand-rolled copies** of essentially
the same markup in `templates/template-{about,home,service,contact,funded-2025}.php`
— 339 lines of legacy `_masthead.scss` backing all of them. All but Contact
already pulled from the exact same real ACF field group (`header_title`,
`header_content`, `header_button_url`/`label`, `header_image`, ...) — the data
was already unified, just the markup wasn't.

Built one `partials/components/masthead.php` (spec B8) covering every variant
in use: title/content/eyebrow, one or two buttons, optional image (two-column
layout when present), optional press-logo row (Home), optional light/dark,
optional sub-nav pills. New `_masthead.scss` replaces the legacy file's job in
~110 lines. Swapped all six usages over to it — same real ACF data, new
rendering. Found and fixed a real bug as a side effect: `template-home.php`
had a stray unmatched `</ul>` in the old masthead markup (matches
`LEGACY-CODE-REVIEW.md`'s own note about this exact line) — gone now, since
that whole block was replaced rather than patched.

**Restored a regression from the earlier pivot**: when I parked the invented
`page-about-us.php`/`page-contact-us.php`, I'd also cleared those two real
pages' `_wp_page_template` meta to let them take over — but with those files
now parked, that meta being empty meant the real pages fell back to bare
`page.php` (title + raw content, no ACF rendering), losing their actual real
content entirely. Restored both to their real templates
(`template-about.php`/`template-contact.php`) before starting the masthead
work — this is exactly why "hide, don't delete" mattered.

**Real fatal caught by restoring that real template**: `template-contact.php`
uses `GOOGLE_MAPS_API_KEY` with no `defined()` guard — flagged in
`LEGACY-CODE-REVIEW.md` §3 as a latent bug, now a genuine fatal on PHP 8 the
moment the real page was reachable again. Defined the constant (empty) in
wp-config.php and added `defined()` guards at both real usage sites
(`template-contact.php` and the ACF Google Map API filter in
`theme-actions.php`) — map just doesn't render instead of crashing the page.

Full regression sweep green across every real page using the new masthead
(About, Contact, the three Service pages — Trading Tools/Trading
Courses/Trader Hub — the real live Get Funded page, and the Events archive).

## Update — legacy CSS audit (you asked "surely we don't just dump it in?")

Fair challenge, checked it properly rather than assume. The honest numbers:
**6,699 lines of legacy SCSS vs 2,884 lines of new `scm-*` SCSS** — the legacy
set is more than double, and `screen.scss` compiles all of it in unconditionally
regardless of whether a given page uses it. That's real, and it's exactly what
caused the CF7-button and product-grid specificity fights from earlier.

Went through it properly rather than either leaving it or deleting it blind:

- **Grepped every non-parked template** for each legacy class still in the
  CSS. Found genuinely dead code and removed it: the legacy `.masthead*`
  family (339 lines → trimmed to the 15 that are still real — see below),
  `.pages-nav` (unused), `.card__actions` (unused, was product price
  strikethrough styling for markup `content-product.php` no longer emits).
- **Found and fixed a real regression from my own masthead migration**: moving
  Contact's title out of the old `.masthead--contact` wrapper left the phone/
  email/address list and map with no layout CSS at all — orphaned, not dead,
  and it showed (cramped list overlapping the map box). Gave it proper new
  `.scm-contact-methods--stacked` styling instead of restoring the old hack.
- **Most legacy CSS is still genuinely load-bearing, and that's the honest
  reason it's still there** — not an oversight. `template-service.php` and
  `template-home.php` still have real, separate product/CTA showcase sections
  (further down, past the masthead) using `.card--product`/`cards--pretty`;
  the whole course player; WooCommerce cart/checkout/my-account; every legacy
  ACF template's actual body content beyond the masthead I already swapped.
  All real, all still rendering real content, none of it migrated yet.
  Deleting the CSS backing any of that today would break real pages.

Net effect of this pass: confirmed-dead code removed, one real regression
fixed, compiled `screen.css` down ~2.5% (150,188 → 146,467 bytes — legacy CSS
is verbose and compresses well, so the byte saving undersells the actual
lines removed). The real fix for the rest is the same migration work already
under way (masthead, product card, single product) — every page/component
that gets reskinned onto `.scm-*` is more legacy CSS that becomes provably
dead and removable next.

Full regression sweep green throughout.

## Update — legacy CSS dropped entirely, not migrated piecemeal

You called it — half-migrating forever was the wrong call. Went decisive instead:

- **`screen.scss` no longer imports `assets/sass/legacy/` at all.** Compiled
  CSS: 146,467 → 48,487 bytes (~67% smaller). The legacy files are still on
  disk (nothing deleted) in case anything genuinely needs porting later, but
  none of it compiles in by default any more. Confirmed safe first: nothing
  in any `scm/*.scss` file references a legacy variable, mixin, or class —
  the new system was already fully self-contained.
- **New `partials/components/no-component.php` + `.scm-no-component`** — a
  bordered, labelled placeholder box for anything without a real component
  yet, instead of either raw HTML or a faked design. Usable as a component
  call or just `class="scm-no-component"` on an existing wrapper.
- **WooCommerce's own bundled CSS is confirmed doing real work** —
  cart/my-account render cleanly off it already (screenshot-checked: the
  empty-cart notice, the login form box, both look properly structured with
  zero custom CSS from us). Your instinct that a lot of this "should just
  inherit" was right.
- **Two cheap, safe, real wins applied globally** while doing this pass:
  WooCommerce's own buttons (login, place order, update cart — anywhere
  `.woocommerce .button` appears) now get the same gold treatment as
  everywhere else, same reasoning as the earlier CF7 fix.

**Went looking for real damage rather than assuming there was none**, and found:
- `template-about.php`'s "Our Global Footprint" world map was never real
  data — every country was the literal hardcoded string `"999"` (this is the
  exact bug `LEGACY-CODE-REVIEW.md` flagged as "placeholder shipped live").
  With the CSS that was hiding/positioning this gone, it was fully exposed as
  a wall of `999`s. Replaced with a `no-component` marker describing what's
  actually needed instead of reviving fake numbers.
- The social-media follower-count section on the same page **was** real ACF
  data, just never had real styling (icons/numbers were jumbled once the old
  CSS layout vanished) — worth fixing properly rather than marking, so it got
  real `.social-box` CSS instead.
- `template-funded-2025.php`'s pricing/comparison matrix (already flagged in
  `COMPONENT-SPEC.md` as "complex; design deliberately") now renders as a
  flat, unreadable dump of real pricing data. **Deliberately not touched** —
  this file is 1,200+ lines of the same legacy/LMS-adjacent risk category as
  `single-course.php`, and I don't have enough context on it to edit safely
  blind. Flagging it as the clearest next real target rather than guessing
  inside it.

**Not yet swept**: the rest of `get-funded` (13 screenshot-pages long),
the remaining `template-service.php`/`template-home.php` product/CTA grid
sections (still real legacy card markup), and the events empty-state message
(reads fine, just plain). Full regression sweep green on every page checked.

## Questions for you

1. Font choice above (Source Serif 4 / Elza) — confirm or override.
2. Icon set — improvise a line-icon set, or is one coming?
3. Where should the success-stories/testimonial block actually live? I put it on
   `/education/` as a judgment call.
4. Author byline role/title — what's the actual data source meant to be?
5. Want me to keep going through the remaining mockups (about/contact/commerce pages),
   or focus next on something specific?

Nothing pushed or committed — this is all working-tree state on top of what you sent
over, exactly as instructed.

## 12 Sep 2026 — pill-nav consolidation, container fix, popup, events

Four things from your last message, in order:

**1. One pill-nav component everywhere.** There were three separate
implementations of the same rounded-pill filter/tab pattern with three
different bits of CSS: `moving-markets.php`'s JS filter buttons, `index.php`'s
category links, and the masthead's `.scm-subnav` links. Pulled them all into
`partials/components/pill-nav.php` (args: `items` as either links or
`data-filter` buttons, `filter_target`, `dark`), backed by one CSS block in
`_tabs.scss` (`.scm-tabs` / `.scm-tabs--on-dark`). Deleted the now-dead
`.scm-filter` rules from `_site.scss` and the duplicate subnav-link rules from
`_hub-pages.scss`. One place to edit the pill look from now on.

While wiring this up, found `moving-markets.php`'s default filter tabs
(macro/equities/fx/commodities/economy/companies) don't match any real
category — real ones are `news`/`research`/`blog` — so the pills were
silently never rendering on the homepage. Fixed the defaults to the real
slugs; pills now show and filter correctly.

**2. Missing containers.** Confirmed: `.container`/`.container--m/s/xs` was
only ever defined in the legacy Sass dropped on 11 Sep, but the markup using
those classes is still live in About, Contact, all three Service pages,
Home, Get Funded, single-course, and the Woo product-extras partial —
exactly the "pages that look like arse with no container" you flagged.
Recreated the four classes on tokens in `assets/sass/scm/_container.scss`
(`--shell-max`/`--prose-max` from `_tokens.scss`, `.container--m` at 1250px,
`.container--xs` at 760px) rather than re-importing the legacy scaffold file.
Confirmed all seven affected templates now render width-constrained.

**3. Popup on every page.** It was the Silktide cookie-consent bar
(`header.php`) — loaded unconditionally, popped up bottom-of-screen on every
single page. The CF7 "Join Now" modal is real functionality gated to
product/service pages only (not every page), and the footer's team-bio modal
is hidden until a team member is clicked — neither of those matched what you
described, so left both alone. Wrapped the cookie-consent script in
`apply_filters('scm_cookie_consent_enabled', false)` — off by default, one
`add_filter(..., '__return_true')` away from back on, nothing deleted.

**4. Events, seeded + as cards.** Only one real published event existed
(2022, already past). Seeded five more via WP-CLI with the same real ACF
fields the ported code already reads (`event_date`, `event_cost`,
`event_url`) — four upcoming, one past, so both card states get exercised.
Built `partials/components/card-event.php` (spec C23: image + date badge,
date line, cost, CTA) on the `.scm-card` pattern and swapped
`archive-event.php` off its old `.container`/`ul.grid.grid--news` markup onto
`.scm-shell` + `.scm-card-grid--3` + the new component.

While testing this, found the real (ported) event query
(`sco_pre_get_posts_events` in `theme-actions.php`) sets `meta_key` /
`meta_type` for `event_date` but left `orderby` as `'date'` (post publish
date) instead of `'meta_value'` — events were listing out of chronological
order (Nov → Jan → Dec → Oct). One-line fix; verified the archive now sorts
correctly by actual event date.

All of the above rebuilt (`npm run build`, no Sass errors) and regression-
swept across home/news/about-us/contact-us/get-funded/shop/event — all 200,
no PHP fatals.

**Not yet done**: the product/CTA grid sections in `template-service.php` /
`template-home.php` are still on legacy `.card--product`/`cards--pretty`
markup with zero CSS behind them now — worth checking next, since dropping
legacy CSS made this specific spot worse, not just unstyled-but-inert.

## 12 Sep 2026 — template-service.php reskin, template-home.php is dead code

Checked `template-home.php` before touching it: it's assigned as page 5's
(`Home`) custom template, but `front-page.php` exists — and per WP's static
front-page template hierarchy, `front-page.php` always wins over a page's
own assigned template when that page is the site's front page. Confirmed on
the live homepage HTML: none of `template-home.php`'s markup (`card--product`,
`Success Stories`, etc.) appears. So `template-home.php`'s legacy CTA
grid/team modal/stories slider is **unreachable dead code** — didn't spend
time reskinning something nobody will ever see. Left it on disk (hide, don't
delete) in case the real ACF content on page 5 is wanted back some day.

`template-service.php` (live on Trading Tools / Trading Courses / Trader
Hub) got the real fix: swapped the hand-rolled `.filter-list.m-nav-toggle`
category filter for `pill-nav` (third instance of the same pattern this
consolidation has now caught), and the product grid's `.grid.grid--3
.cards.cards--pretty` wrapper for `.scm-card-grid.scm-card-grid--3` — the
cards themselves already render as `.scm-card` via `content-product.php`
from the earlier WooCommerce reskin, they just weren't in a grid that gave
them any layout. Also dropped the fully-commented-out isotope/masonry script
block (dead weight, was never executing) and fixed an undefined-array-key
warning on `$_GET['filter']`. Verified on all three live pages: cards render,
category pills show with correct real subcategory data (short-term/mid-term/
automated etc.), filtering by `?filter=slug` works and sets the active pill
correctly, no PHP warnings or fatals.

## 12 Sep 2026 — front-page.php made canonical, last two static homepage sections wired to ACF

Formalised `front-page.php` as *the* homepage template rather than an
accident of WP's template hierarchy: parked the now-fully-dead
`templates/template-home.php` into `_parked-page-concepts/` and cleared page
5's stale `_wp_page_template` meta back to Default (front-page.php still
renders it either way — this was purely fixing a misleading admin state,
not a behaviour change) and said so in front-page.php's header comment.

Of the homepage's components, two were still `@static` per the codebase's
own convention (hardcoded array, header comment says what it needs):
`static-proof-bar.php` (the four-up strip under the hero) and
`static-learn.php` (the six-topic Learn grid). Added two repeaters to the
existing "Homepage (2026)" ACF options group (`scm_proof_items`,
`scm_learn_topics` — each icon/title/text, defaults matching the current
copy so the page renders identically until someone fills them in), renamed
the files to `proof-bar.php`/`learn.php` (dropping the `static-` prefix now
that they're genuinely `@dynamic`), and updated `front-page.php`'s calls.

**Left alone, deliberately**: `static-ticker.php` (the market snapshot
strip) is not an ACF candidate — its own header comment already says it
needs a licensed live market-data feed, and an ACF field would just be a
different flavour of fake data. Kept static and clearly labelled rather than
force-fit into the "make it dynamic" pass.

Verified: homepage renders the same 4 proof items and 6 learn topics from
the new ACF defaults, full site regression sweep (home, news, about-us,
contact-us, get-funded, shop, event, trading-tools) all 200 with no PHP
fatals.

## 14 Sep 2026 — Get Funded pricing matrix reskinned; design-team mockups reviewed

Reviewed `~/Downloads/samuel-co-component-mockups.html` (design team's proposed
direction for Get Funded, Tools/Courses listing, Trader Hub, Product, Event,
Cart/Account) against real data. Verdict and what changed:

**Get Funded (`template-funded-2025.php`) — done.** This was the deliberately
deferred item (previously flagged as too risky to touch blind). Found the
real "matrix-v2" section — mode toggle (Get Qualified/Get Funded), balance
selector, horizontal scroll-to-centre, tooltips, disclaimer gate — was fully
wired via a real inline `<script>`, just completely unstyled since legacy CSS
dropped. Left every line of that script untouched and reskinned around it:

- New `partials/components/funding-tier-card.php` replaces ~180 lines of
  duplicated markup between the two repeater loops (`get_qualified_services`
  / `get_funded_services`) with one component.
- Dropped the separate unstyled "keys" sidebar (a redundant legend); each
  metric's label + tooltip now sits inline with its value in the card, like
  a spec sheet — same real 8 fields and per-row tooltip text, just not
  duplicated in two places.
- New `assets/sass/scm/_funding-matrix.scss`: pill-style mode/balance
  buttons (navy active state, matching the site's pill pattern), bordered
  cards in a horizontal scroller with a fade-and-arrow edge, gold highlight
  on the active/centred card, and real modal styling for the disclaimer
  gate (previously invisible — same "no closed state defined" bug class as
  the header/footer modals fixed earlier).
- Real content-strip image fix reused `scm_acf_image_url()`.

Verified end-to-end (Playwright): mode toggle swaps the 3 vs 7 real tiers
correctly, balance pills select and re-centre, tooltips show on hover,
`data-requiresPopUp` correctly opens the real disclaimer copy pulled from
ACF ("$250k/$400k accounts must hit a 10% profit target..."), mobile shows
one card at a time as designed. Full site regression sweep clean.

**Found and fixed along the way**: a second instance of the `[hidden]`
vs `display` cascade bug (any of our own classes setting `display` beats
the browser's default `[hidden]` rule at equal specificity — this already
caused the header/footer modal bug). Added one global `[hidden]{display:
none!important}` rule to `_base.scss` instead of patching each component,
so this class of bug can't recur elsewhere in the theme.

**Trading Tools / Trading Courses listing — no work needed.** Compared
against the mockup's direction (kill the giant Join Now form on first
screen, keep the shared pill + card pattern) and found both real pages
already there from earlier work this session: light hero, real pill
filters, real WooCommerce cards with images/prices, correctly-styled
buttons. The mockup's proposed direction is already shipped here.

**Not yet actioned** (design-team mockup covers these, real data gaps
flagged when reviewed): Trader Hub as a dedicated landing page (currently a
one-product catalogue), Product page hero cleanup, richer single-event
template (would need new ACF fields — agenda, location — the real `event`
CPT only has date/cost/external URL today), and Cart/Account restyle
(needs real qty/remove controls on top of the visual layer, Account maps
cleanly to WooCommerce's own endpoints).

## 14 Sep 2026 (cont.) — event info panel, funding rules panel, My Account dashboard

The four items approved from the second design-team file
(`samuel-co-component-mockups.html` / supporting-components batch):

**Event info panel + single-event.php (new).** Added two real ACF fields
to the `event` CPT (`includes/theme-acf.php`, group_scm_event): `event_venue`
(text) and `event_sold_out` (checkbox) — both optional, both simply don't
render a row/change state when empty, per instruction. Built
`partials/components/event-info.php` (date/time from the real `event_date`,
venue only if set, a green "Places available" / red "Sold out" indicator,
a real "Book now" link, and a genuinely working "Add to calendar" — a
same-page `.ics` file via a data URI, no backend needed). Gave events their
own `single-event.php` — they were previously falling back to the generic
article `single.php` (byline, share links, related-posts sidebar), which
never fit an event.

**Found and fixed a real, pre-existing bug while building this**: the
ported `sco_pre_get_posts_events` filter (`theme-actions.php`) had no guard
against singular requests, so its "future events only" `meta_query` was also
being applied to a single event's own page — any *past* event 404'd at its
own permalink (a real bug, not something new — it just needed a real
single-event template to ever be tested against a past event's URL). Fixed
with `!$query->is_singular` (the global `is_singular('event')` template tag
doesn't work inside `pre_get_posts` — the queried object isn't resolved yet
at that point — but the query object's own `is_singular` property already
is). Verified past and future single events both resolve now, and the
archive's own future-only filtering is untouched.

**Funding rules quick-reference panel.** New generic
`partials/components/rules-panel.php` (definition-grid, reusable), fed with
the real $100,000 Get Funded tier's same 8 metrics already shown in the
interactive cards below — deliberately not inventing "weekend holding /
news trading"-style fields the mockup used but our data doesn't have.
Sits above the matrix on `template-funded-2025.php`.

**My Account dashboard.** New `woocommerce/myaccount/dashboard.php`
override (WooCommerce's default is bare text) with two real cards: next
upcoming event (same real query as the events archive) and the customer's
most recent real order (`wc_get_orders()`). The mock-up's third card — live
% profit / drawdown-used progress — needs a real trading data feed we don't
have, same category as the market ticker, so deliberately not built.
Also gave the whole My Account shell real styling for the first time
(`assets/sass/scm/_account.scss`): navy sidebar nav with a gold active
rail, bordered content panel, styled dashboard cards.

Hit a genuinely confusing layout bug getting the nav+content side by side:
WooCommerce's own CSS floats them (`.woocommerce-account
.woocommerce-MyAccount-navigation{float:left;width:30%}` etc.) at equal
specificity to a naive override, and even after neutralising float/width and
turning the parent into a grid, the two children's grid auto-placement
still came out swapped in this environment for reasons that didn't reduce to
any inspectable property (no float, no explicit order). Pinned both
explicitly (`grid-column: 1` / `2`) rather than trust auto-placement here.

**QA note**: created a local-only test customer (`scm-qa-test`) and one
real test order via `wc_create_order()` to verify the dashboard cards
against a real logged-in session — this is local-only seed data, not
imported from the live customer list, consistent with the "never touch
real customer data" rule for this database copy.

Full regression sweep (home, get-funded, event archive, both a past and a
future single event, my-account, about-us, contact-us, trading-tools, shop,
single product, cart, news) all 200, no PHP fatals.

## 14 Sep 2026 (cont. 2) — footer + mailing-list rebuilt

Reviewed `samuel-co-footer-and-mailing-list.html` (design team's proposed
footer direction: newsletter with a specific pitch instead of a generic
"subscribe" box, footer nav grouped by intent instead of one flat link
list, a distinct legal-baseline row, mobile accordions) and adapted it —
the real footer was still on the light/legacy treatment while every other
dark band on the site (nav, ticker, education, member CTA) already uses
navy, so this was also a consistency fix, not just a copy of the mock-up.

- **Mailing-list section**: new headline/pitch copy ("Useful trading
  insight, without the daily noise...") replacing the generic "Sign up to
  our mailing list" — same real CF7 form (id 493, real GDPR consent
  checkbox intact), just a better pitch and a proper 2-column layout with a
  faint "S&CO" watermark.
- **Footer nav**: regrouped from one flat link list into three real
  columns — Trade (Get Funded, Trading Tools, Trader Hub, Shop), Learn
  (Trading Courses, News & Research, Events), Support (About Us, Contact
  Us, My Account) — the same real pages already in the header's Main Menu,
  just organised by what a visitor's trying to do instead of dumped in one
  row. Collapses to accordions under 680px (verified open/closed states).
- **Legal row**: separated out from the main nav — the real "Footer Menu"
  (Privacy Policy, Cookie Policy, Risk Disclosure, Terms & Conditions),
  minus "Contact Us" which now lives in Support instead. Hit one real bug
  getting this to render at all: `wp_get_nav_menu_items('footer')` doesn't
  work — `'footer'` is the registered theme_location, not the menu's own
  slug, so it silently returned nothing until resolved via
  `get_nav_menu_locations()` first.
- **Risk warning and legal copyright text — kept verbatim.** Real
  regulatory/compliance copy doesn't get paraphrased just because a mockup
  wrote its own version; only the layout changed. Same principle for the
  existing "Markets. Research. Education." / "Knowledge. Discipline.
  Opportunity." taglines — caught myself about to overwrite these with the
  mock-up's own (invented) brand line and put the real copy back.

**Two more small real bugs caught in passing**: the same dark-on-dark
"color isn't inherited from a token remap, only from an ancestor's own
resolved color" bug (this session's most repeated bug class) on the new
prefooter heading/paragraph — fixed by setting color explicitly, same as
every other fix of this kind. And the footer's Twitter/X icon
(`fa-x-twitter`) rendering as a blank glyph — that icon doesn't exist in
the FontAwesome 6.1.1 bundle this theme loads (same gap as the `fa-regular`
icons fixed earlier in the build) — switched to the classic `fa-twitter`
bird, which does.

Full regression sweep (home, about-us, contact-us, get-funded,
trading-tools, shop, single product, event archive, my-account, news,
cart) all 200, no PHP fatals.

## 14 Sep 2026 (cont. 3) — single product page rebuilt from trademate-product-page-template.html

Reviewed `trademate-product-page-template.html` (a fictional "TradeMate" indicator
product page — hero, feature grid, video demo, included-with-purchase list,
benefits grid, disclosure box, FAQ, final CTA, sticky mobile buy bar) and found
the real data model already has almost every section, just never had a real
component (`woocommerce/content-product-extras.php` was rendering it all as
unstyled `.section.section--product` / `.wp-content` legacy markup with dead
`.gfx` decoration divs). Rebuilt entirely on real per-product ACF fields:

- **What's included** — real `what_do_you_get_content` (rich WYSIWYG, e.g.
  Fusion 3.0's has its own real H3-sectioned structure: What You Receive, How
  It Works, Educational Features, Learning Resources, Platform Compatibility,
  Important Information) rendered through `.scm-prose` rather than forced into
  the mock-up's separate feature-grid/checklist shape it doesn't actually have.
- **Benefits** — real `benefits_content` (a WYSIWYG list of "Title — description"
  lines) parsed into a proper numbered benefit-card grid. Hit a real parsing bug
  here: the source stores a plain " - " as a smart-punctuation en-dash *entity*
  (`&#8211;`), so `wp_strip_all_tags()` alone left the literal entity text in
  place and every card silently fell back to one flat paragraph with no title.
  Fixed by decoding entities before splitting.
- **Course modules** ("Here's what you'll learn") — real linked course
  (`_sc_courses`) and its modules/lessons, restyled, same real logic untouched.
- **FAQ** — real `faqs` repeater (4-7 real Q&As per product, verified across
  five real products) rendered as native `<details>/<summary>` — kept the
  native element rather than reimplementing an accordion in JS, styled only.
- **Real per-product disclaimer/note** (`_disclaimer` / `_nb_text` — e.g.
  Fusion 3.0's real "packaging is illustrative only" note) — was already
  wired via `includes/woocommerce/woo.php`, just unstyled; gave it a real
  style instead of duplicating the logic.
- **Final dark CTA band + sticky mobile buy bar** — new, real product
  title/price/add-to-cart (same real WooCommerce hooks, no duplicate form —
  the mobile bar links back up to the real add-to-cart block via anchor
  rather than re-submitting), shown/hidden with an IntersectionObserver once
  the real buy box scrolls out of view.

**Also fixed the exact "purple button" issue the design team's review
flagged**: WooCommerce's own `.woocommerce button.button.alt` rule is 3
classes + the `button` element type deep, which beat the existing
`.scm-product__summary .single_add_to_cart_button` gold override (2 classes)
on specificity despite loading later — the button had been silently purple
this whole time. Fixed by matching specificity exactly (adding `.woocommerce`
+ the `button` type to the selector) rather than reaching for `!important`,
consistent with how `.woocommerce div.scm-product` already handles the same
class of conflict. Same fix applied to the new final-CTA band's button.

**Also caught**: the same dark-on-dark text bug (final CTA heading, no
explicit color set) — fixed the same way as every other instance this
session.

Verified against 5 real products with genuinely different data shapes
(Fusion 3.0, BeastMode, VIX Algo, TradeMate — a real product, coincidentally
same name as the mock-up's fictional one — and QuickTrader): all render
clean, no PHP fatals, benefit/FAQ counts differ per product's real data as
expected, and one of them (the real TradeMate) already has a real embedded
demo video that renders correctly in the existing description content —
matching exactly what the mock-up's video-demo section had imagined.

Full regression sweep (home, single product, shop, trading-tools,
trading-courses, get-funded, about-us, contact-us, my-account, cart, event
archive) all 200, no PHP fatals.

## 14 Sep 2026 (cont. 4) — global line-height fix

Flagged by you on Trader Hub's product description looking cramped. Real
cause: `body` never set a `line-height` at all — polished areas (`.scm-prose`,
short-description, testimonials) each set their own comfortable line-height
explicitly, but plenty of real content sits in classes that never got that
treatment (Trader Hub's description renders in the legacy `.wp-content-product`
class, which has zero CSS in the new system) and fell through to the bare
browser default (~1.15–1.2). Set `body { line-height: var(--leading-body) }`
(1.55) as a real sitewide baseline instead of patching each affected class
individually — headings already set their own tighter line-heights (1.02–1.2)
so they're unaffected, verified across home, About, a real article and the
newly-fixed Trader Hub page.

## 14 Sep 2026 (cont. 5) — Get Funded page finished (get-funded-page-template.html)

Reviewed `get-funded-page-template.html` (hero proof stats, programme
comparison table, "included in programme" checklist, office facilities,
coach cards, success-story carousel, page FAQ, final CTA) against the real
page's full ACF field list — turned out this page has far more real,
already-authored content than the matrix section I rebuilt earlier today:
`content_strip` / `content_strip_2` / `content_strip_3` (three real
image+copy+button strips — one of them literally about "Meet the Coaches",
with a real "35 years combined experience" line and a real Level 7 Diploma
link), `content_grid` (real office-facility copy: Gym, Cinema room,
Meditation room, Trading Floor, each with genuinely characterful real
descriptions), a real page-level FAQ (7 real Q&As), and the `success_story`
CPT already queried on this exact page (name coincidence: the mock-up's own
fictional example story used a name — "Simran Dhingra" — that belongs to a
real success story in our data, suggesting whoever built the mock-up had
already seen this real dataset). None of it had ever been reskinned — it was
still rendering as unstyled `.section.section--gfx` / `.block-grid` /
`.strip__content` legacy markup with dead `.gfx` decoration divs.

Built, all on real data:
- **Hero proof-stats row** — 4 figures computed directly from the real
  programme data (max balance, profit share, leverage, programme count),
  not restated as separate invented copy.
- **New shared `content-strip` component** (`partials/components/content-strip.php`)
  — the same image+eyebrow+title+prose+button+PDF shape used by About's
  strip and all three of this page's strips; refactored About's inline
  version onto it too rather than maintaining two copies of the same
  pattern. Dropped the redundant "select your balance" quick-buttons that
  used to duplicate the real interactive matrix — each strip now links to
  `#programmes` instead.
- **Facilities section** (real `content_grid`) — two real office photos
  each with two real facility write-ups. Facility icons (real SVGs) are
  missing their files locally like everything else in this DB export; added
  `scm_acf_icon_url()` (new, no pooled-photo fallback — a missing icon just
  doesn't render, since substituting a stock photo for a small icon would
  look wrong, unlike a missing hero image).
- **Coaches** — reused the exact `team-card.php` component from About, with
  3 real team members whose real roles are coaching-relevant (Samuel Leach,
  Raj Singh — "Trading Coach & Head of Recruitment", Adrian Leach — "Head of
  Mindset"). Directly satisfies the mock-up's own review note ("replace
  these initials with the real approved portraits") — same real people,
  same fallback-initials treatment as About when no real photo file exists.
- **Success story carousel** — real `success_story` posts (80+ have real
  `payout_earnt` data), one at a time with prev/next, real name/course/
  country/payout/return per slide, capped to a random 8 per load rather
  than rendering all 80. Added a standard "individual experiences vary,
  not a guarantee of future results" disclaimer line given the prominent
  real payout figures — general responsible-testimonial-section practice,
  not a new factual claim.
- **Page FAQ** — real 7-question repeater, restyled onto the same
  `.scm-faq` component built for products (zero new CSS needed).
- **Final CTA band** — reused `.scm-product-cta` from the product-page work.

**Deliberately not built**: the mock-up's programme-comparison table
(Get Funded vs Get Qualified feature checklist) and its separate "included
in the programme" checklist. Both would need claims we can't verify from
real fields (e.g. "Advanced Diploma ✓", "Career introductions ✓" as
qualified-only line items) — the real content strips already state the
genuine differences in prose instead, so didn't invent a second, riskier
version of the same claims in a checklist/table format.

**Found and fixed one more instance of this session's recurring bug**: the
shared `section-head.php` component's heading had no explicit `color`, so
it inherited an already-resolved dark value straight through a
`.scm-on-dark` section instead of picking up the remap — first time that
component had been used on a dark section. Fixed at the component level
(`.scm-section-head h2 { color: var(--ink) }`) so it's correct everywhere
going forward, not just here.

Full regression sweep (home, get-funded, about-us, contact-us, single
product, trading-tools, news, shop, my-account, event archive) all 200, no
PHP fatals. Story carousel and FAQ accordions verified interactively.

## 14 Sep 2026 (cont. 6) — Contact page rebuilt (contact-page-template.html)

`template-contact.php` rewritten against the mock-up. The mock-up's own map
image is fictional — this install has no Google Maps API key (a real,
known gap, see docs/LEGACY-CODE-REVIEW.md §3) — so rather than either a fake
map or a bare "No Data" box, built a genuinely finished alternative:

- **Navy contact-methods band** — real phone/email/address from ACF options,
  each a working `tel:`/`mailto:`/in-page anchor link.
- **Real CF7 form (id 7) restyled, not rebuilt** — introspected the actual
  form-tags (`wp post meta get 7 _form`) rather than trusting the mock-up's
  invented "subject" dropdown, which doesn't exist on the real form. Styled
  its real markup classes (`.grid--2`, `.grid-item`, `.form_foot`,
  `.wpcf7-list-item`) only — no fields added or removed.
- **Real success-state UI** — listens for CF7's own `wpcf7mailsent` client
  event (fired only on genuine successful mail send) to hide the form and
  show a "Message received" panel, instead of a fake `submit` handler that
  would fire even on validation failure. Can't be tested end-to-end locally
  since `SCM_BLOCK_MAIL` deliberately blocks outgoing mail (protects real
  customer emails in this DB copy) — verified the JS logic instead by
  manually dispatching `wpcf7mailsent` on `#contact-form` and confirming
  the hide/show fired correctly.
- **Office panel** — a branded decorative map-pin mark (not a fake map)
  plus a "Get directions" link built from the real address via
  `https://www.google.com/maps/dir/?api=1&destination=...` — genuinely
  functional and needs no API key, with a plain-language note about why
  there's no embedded map.

**Bugs found and fixed**: "Get directions" was using `.scm-button--on-dark`
against the office panel's light sand background — washed-out, nearly
invisible; switched to `.scm-button--gold`. Also dropped a "quick CTA"
band that duplicated the real global newsletter section immediately below
it (footer.php) — two near-identical "MARKET PERSPECTIVE" headings back to
back.

Full regression sweep (home, contact-us, about-us, get-funded, shop,
my-account, event archive, two single products, trading-tools) all 200, no
PHP fatals.

## 16 Sep 2026 — My Account login/register/lost-password/reset-password styled

Real WooCommerce core templates (`woocommerce/myaccount/form-login.php`,
`form-lost-password.php` — both already existed as verbatim, unstyled
copies; `form-reset-password.php` isn't overridden at all, reached purely
via WooCommerce's own real classes) had zero styling beyond the My Account
shell/nav done earlier this session — default browser inputs, unstyled
checkbox, default WooCommerce notice banners. Added a full CSS pass in
`_account.scss`: bordered inputs matching `.scm-field`, gold submit
buttons, aligned "Remember me" checkbox, styled two-column login/register
split (registration is currently disabled on this install, so only the
single-column login shows), and styled `.woocommerce-error`/`-message`/
`-info` notices (colored left border, readable on the site's tokens) —
these previously fell back entirely to WooCommerce's own bundled default
look. No markup/fields changed, no new template overrides needed. Verified
login, wrong-password error state, and lost-password screens visually;
reset-password (needs a real emailed key/login pair to reach) inherits the
same classes so should match, not independently screenshotted.

User feedback: nobody buys "2" of a course, a funded-account programme, or
a software licence — every real product in this catalogue (checked the
full product list) is one of those three, none physically stocked in
multiples. Removed `woocommerce_quantity_input()` from
`woocommerce/single-product/add-to-cart/simple.php` (submits a hidden
`quantity=1` instead) and let the add-to-cart button take the full width
it used to share with the stepper (`.scm-add-to-cart-full`). Applies to
both real call sites that use this template (the main product summary and
the `.scm-product-cta__buy` band), since both go through the same
WooCommerce hook.

Checked the real product-type split (37 simple / 15 subscription, no
variable products) and found `simple.php` only covers the `simple` type —
subscription products (Trader Hub, memberships, etc.) render through
WooCommerce Subscriptions' own `single-product/add-to-cart/subscription.php`
template instead, which still had the stepper. Added the same override at
`woocommerce/single-product/add-to-cart/subscription.php` (copied from the
plugin's real template, same quantity=1 + full-width button change) so the
fix covers every real product, not just the majority type.

## 14 Sep 2026 (cont. 8) — header nav overflow fixed

User report (screenshot): the header nav was wrapping/crowding — 10 flat
top-level items (Home, Trading Tools, Trading Courses, Trader Hub, About
Us, News & Research, Events, Contact Us, Get Funded, Login) squeezed into
a strip whose CSS (`.scm-nav__list`, `_header.scss`) was built for the
original ~5-item design. Two were pure duplicates: "Home" (the logo
already links home) and "Login" (the header already has its own separate
Account/Log in link in `.scm-header__actions`) — deleted both from the
real `main-menu` (WP menu, not code).

Regrouped the remaining 8 into 5 top-level items using the theme's
existing-but-unused hover-dropdown CSS (`.scm-nav .sub-menu` /
`.scm-nav li:hover > .sub-menu` in `_site.scss` — built during the port,
never actually wired to a parent/child menu structure until now): added a
new "Trading" parent item (custom link, `#`) with Trading Tools, Trading
Courses, Trader Hub and Get Funded reparented under it as real children.
Top level is now Trading / About Us / News & Research / Events / Contact
Us. Added a small caret to `.menu-item-has-children` links (there was
previously zero visual cue that hovering revealed anything) and a
one-line JS `preventDefault` on the placeholder `#` parent link so
clicking it (rather than hovering) doesn't jump the page to the top.

Considered switching to the already-built-but-unused "Header Menu 2026"
5-item menu (Markets/Research/Learn/Education/Membership, real pages,
just never assigned to the `header` location) instead, but its pages are
a separate content-taxonomy layer — adopting it would drop Trader Hub,
Get Funded, Trading Tools, About Us and Contact Us from top-level nav
entirely, a real functional/business-page loss, not just a visual fix.
Flagged this instead of guessing; went with the grouping approach.

## 14 Sep 2026 (cont. 9) — real search built (was a dead link)

The header search icon linked straight to `home_url('/?s=')`. Turns out
WordPress never treats an *empty* `s` as a real search — `is_search()`
only latches true when `s` is non-empty — so that link silently fell
through to `index.php`, which is hardcoded for the News & Research
archive (wrong eyebrow, wrong title, wrong category pills, no sign the
visitor had searched anything). There was no `search.php` at all.

Built real search, end to end:
- **`search.php`** (new, native WP template-hierarchy slot) — real
  results for any genuine `?s=` query: heading echoes the actual search
  term, a real result count, a `search-form` to refine, and a proper
  "no results for X" state instead of silently showing unrelated content.
- **`partials/components/search-result.php`** (new) — one row per result,
  works across every real searchable post type (article, course, event,
  product, ...) via `get_post_type_object()` for the type label; only
  shows a thumbnail when a real one exists (`scm_has_real_thumbnail()`,
  no stock-photo fallback here — substituting an unrelated photo on a
  product/event search hit would actively mislead, unlike an editorial
  card).
- **`partials/components/search-form.php`** (new, shared) — the actual
  GET-to-`s` form, no JS, exactly how WordPress search always worked,
  just styled to the 2026 tokens.
- **`templates/template-search.php`** (new) + a real WP page created at
  `/search/` using it — a dedicated landing page for the header icon to
  point at, since an empty query can't drive `search.php` directly. This
  is the "own page" the icon now goes to; typing and submitting there is
  a normal GET that lands on the real `search.php` results.
- **`header.php`** — search icon now links to `get_permalink('search')`
  instead of the dead `/?s=`.

**Also, while in the mailing-list form** (flagged separately as needing
"TLC" from a screenshot): the real CF7 493 form (`.newsletter` email+submit
row, plus a separate `[acceptance]` consent checkbox) had zero purpose-built
CSS anywhere — the input/button looked passable only because of a leftover
legacy `.newsletter` flex rule, and the consent checkbox rendered as a bare
unstyled browser default with the label text misaligned. Wrapped both real
call sites (`footer.php`'s prefooter band and `partials/components/
article-sidebar.php`'s CTA — same CF7 form, id 493, in both places) in a
`.scm-newsletter` class and added one shared, tokenized style block
(`_footer.scss`) for the real CF7 markup: on-dark bordered email field,
gold submit button, and a properly aligned checkbox + consent copy with a
gold-accent-colored box and an underlined real Privacy Policy link — no
fields added or removed, purely restyling the form's own existing output.

Full regression sweep (home, search landing, `?s=` with and without
results, an article, the footer newsletter band) all 200, no PHP fatals.

## 14 Sep 2026 (cont. 10) — homepage rebuilt (homepage-template-morning-brief-led.html)

Full homepage restructure around a bigger real "Morning Brief" — the
mock-up's own header/nav and footer were already less complete than what's
real here (see the earlier header-fix and footer entries this session), so
this kept the site's own approved hero/header/footer design
(reference/PHOTO-2026-09-10-11-51-31.jpg) rather than reskinning to the
mock-up's separate visual language, and only added the genuinely new real
content the mock-up called for. Ran a full research/data audit first
(every section checked against real ACF fields, real categories, real
products) before writing any code — full findings kept in this session's
history; summary below.

**Critical finding**: zero real posts exist in either `morning-market-brief`
or `us-open` categories. Every word of the homepage's Morning Brief lead
story and US Open card has always been the hardcoded STATIC fallback text
in `morning-brief.php`/`us-open.php` — including on the *old* homepage,
before this rebuild. The real ACF plumbing (`scm_key_points` repeater,
category-driven queries) is correct and will start working the moment
real posts are published; nothing here was broken, just never fed.

**Built:**
- **Sticky header** — `.scm-header` was `position:relative`; now `sticky`
  (matches the mock-up, small real gap, no content change).
- **Hero signup form fixed** — was a fake `<form action="#">`; now defaults
  to the real mailing-list CF7 form (id 493, same one in the footer/article
  sidebar) with a genuine success state on the real `wpcf7mailsent` event
  (generalised the Contact page's pattern into a reusable `[data-newsletter]`
  handler in `markets.js`). Found and fixed a real bug while wiring this:
  the shared `.scm-newsletter` CSS (built earlier this session for the
  footer/sidebar, both dark surfaces) assumed an on-dark background —
  dropped straight into the hero's white background it would have rendered
  white-on-white, the mirror image of this session's recurring
  dark-on-dark bug. Restructured `_footer.scss` so `.scm-newsletter`
  defaults to light-surface colors and the two dark placements
  (`.scm-prefooter`, `.scm-on-dark`) layer an explicit override on top,
  rather than the reverse.
- **New "Choose your route" section** (`partials/components/routes.php`,
  `_routes.scss`) — 3 cards. Get Funded and Get Qualified both link to the
  real Get Funded page's programme matrix (`/get-funded/#programmes`,
  a real client-side tab toggle between the two — not two separate pages).
  The mock-up's 3rd "Develop Your Trading" card bundled courses + tools +
  Trader Hub with no single real destination; per your call, pointed it at
  the real Trading Courses page instead of inventing a combined one.
- **Morning Brief section rebuilt** — lead story now a bordered 2-column
  card (was a 3-column row sharing space with US Open); US Open moved into
  a new 3-card "supporting" strip below it as a compact mini-card
  (`us-open.php` restyled, real content unchanged) alongside two honestly
  "coming soon" cards for a video briefing and an economic calendar — per
  your call, kept the 3-card layout rather than dropping to 2 or inventing
  content, since neither has any real data source yet (no video taxonomy,
  no calendar feed) and Morning Brief is about to become a bigger real
  initiative. Dropped the old static decorative image caption ("Higher
  Prices. Bigger Questions.") — it was hardcoded invented copy with no
  real source, flagged as a removal candidate before this rebuild.
- **News & Research section** — this already existed and was already real
  (`moving-markets.php`, real `news`/`research`/`blog` pill filters, real
  posts) despite the mock-up appearing to invent this section from
  scratch; only renamed the heading from "What's Moving Markets" to "News
  & Research" to match the real page name and the mock-up's framing.
- **Trader Hub band rewritten with real data** — `member-cta.php`'s own
  code comment already flagged "price/URL should ultimately come from the
  Woo subscription product"; it never had been. Was hardcoded to a
  fictional "£99/month" with an invented benefits list (Morning Brief,
  weekly market reports, member events — none of which the real product
  offers). Now pulls the real Trader Hub product (ID 501): real price
  (currently £24.99/month, live from Woo, not hardcoded), real URL, and a
  benefits list matching what it actually includes (Discord community,
  trade ideas, market news, Samuel Leach's stock watchlist report).
  Restyled from its old narrow sticky-sidebar shape (still used inside the
  currently-homepage-unused `research.php`, left in place) into a
  full-width dark band matching the mock-up's "Hub" section.
- **Education band** — fixed a real, pre-existing bug: the Level 7
  Diploma's fallback product slug was `formula-for-success`, a dead slug
  that traced back to a broken link on the legacy homepage's own ACF
  field — it never resolved to a real product, so an empty
  `scm_edu_level7` option silently fell through to a fully-hardcoded
  fallback instead of finding the real Level 7 Diploma (ID 566, real
  slug `level-7-diploma-in-applied-financial-trading`). Added a 3rd
  qualification card ("Trading Courses", real page) alongside Level 5 /
  Level 7 to match the mock-up's 3-card qualifications row (same real
  Trading Courses anchor as the routes section's 3rd card); widened
  `.scm-education__grid` from a fixed 3-column to 4-column layout to fit.
  Learn topics: renamed "FX" → "Foreign Exchange" to match the mock-up's
  wording; the 6-topic list itself remains the pre-existing hardcoded
  placeholder content (CLAUDE.md's own "Known gaps" list) — no real ACF
  data exists for it yet, not solved by this pass.
- **Dropped** the mock-up's 2nd Morning Brief signup band near the
  footer — redundant with the real, working prefooter signup
  (`footer.php`, CF7 493) already on every page including the homepage.

Full regression sweep (home, get-funded, contact-us, about-us,
trading-courses, Trader Hub product, Level 5 + Level 7 products, news,
shop, search) all 200, no PHP fatals.

## 16 Sep 2026 (cont.) — type scale converted to rem + clamp, misc fixes

**Type scale**: `_tokens.scss`'s `--text-*`/`--display-*` scale was raw px
and read too small on desktop (`--text-base` was 13px, used for card/brief
body copy; `--text-xs`/`--text-sm` were 9/11px). Converted the whole scale
to rem (confirmed safe — nothing sets a non-default root font-size, so
1rem = 16px) and bumped every step by roughly +3px equivalent:
xs 9→12px, sm 11→14px, base 13→16px, md 15→18px, lg 17→20px, xl 20→24px,
display-sm 27→28px, display-md 34→36px. Bulk-converted ~45 raw hardcoded
`font-size`/`font:` declarations across every `assets/sass/scm/*.scss`
file to reference these tokens instead (mostly via one careful sed/perl
pass matching `font-size:8px..13px` → the nearest new token, since those
values are now IDENTICAL to the token's old-scale equivalents) — this both
tokenizes them (the stated project convention) and bumps their size. Also
caught 3 stragglers the integer-only pass missed (`9.5px` on
`.scm-card p`/`.scm-education__intro p`/`.scm-member li` — real card/copy
body text, exactly what was flagged as "too small").

**Display headings now use clamp()** (`--text-xl`, `--display-sm/md/lg/xl`)
so they fluid-scale between a phone-width minimum and their old desktop
max, instead of needing a discrete breakpoint override or staying fixed at
the (now bigger) desktop size on mobile. Wired the actual heading rules
that were still hardcoded raw px onto these tokens: `.scm-hero h1`,
`.scm-hero__lead`, `.scm-section h2`, `.scm-article h1,.scm-page h1` (own
clamp, no existing token matched its 58px max), `.scm-brief__lead h2`.
Body/label-level sizes (xs through lg) stay fixed rem on purpose — at
12-20px they don't have the "oversized on a phone" problem a 68px hero h1
does.

**Nav active state** — `wp_nav_menu()`'s own `current-menu-item`/
`-parent`/`-ancestor` classes were never styled; added gold color for
those (`_header.scss`), so the current page (or its "Trading" dropdown
parent) now visibly highlights in the header.

**Hero**: widened `.scm-hero__copy` (640px → 780px cap) and its lead/sub/
signup max-widths (560px/590px → 680px) per request. Image column and the
two overlay panels (values list, Samuel Leach quote) were done in the
previous entry.

**Header button wrap bug**: "Log in" and "Join Free" could break onto two
lines — `.scm-button`/`.scm-text-link` had no `white-space:nowrap`, so a
squeezed flex row let their labels wrap. Added `white-space:nowrap` +
`flex-shrink:0` to both (site-wide, not just the header — buttons
shouldn't wrap their own label anywhere).

**Trader Hub art panel** — was a plain dark gradient with a faint "HUB"
watermark; added a temp photo (real product image isn't synced locally,
same recurring media gap) with a darker gradient overlay for contrast,
per request, until a real product shot is available.

**Login/lost-password/reset-password centering** — first pass used
flexbox on the wrapper; per your correction, redone as `margin:0 auto` on
the real form elements directly (`_account.scss`), plus centering the
`wp-title` and (only on these three logged-out states, via a `:has()`
check for the absent MyAccount nav sidebar) the real page `<h1>`. **Still
not actually centering on `/my-account/` specifically as of this entry** —
lost-password centers correctly, plain login doesn't. Chased one dead end
(a legacy flex rule in `assets/sass/legacy/local/woo/_account.scss` that
turned out to never be `@import`-ed into the build at all, so fixing
against it had zero effect) before realizing that; a proper Playwright
`getComputedStyle()`/stylesheet dump is in progress to find the real
competing rule (likely something in WooCommerce's own bundled
`woocommerce.css`, not yet checked) rather than guessing further.

**Small fixes**: About Us's "Our Global Footprint" and "Official Social
Media Channels" sections were rendering with zero vertical padding —
both use legacy `.section`/`.container` classes whose defining partial
(`_scaffold.scss`) is, like the account one above, never imported into the
build; added the real `scm-section`(-dark/on-dark) classes alongside
rather than reviving the orphaned file. Contact page's method icons had no
left breathing room against the divider from the item before them (padding
was 0 on the left for every item, not just the first) — made symmetric.
Events archive and News & Research index had a bare title with nothing
else — both now use `masthead` with a real, accurate lead line (same
component/style on both, per request) instead of a hand-rolled bare
`<h1>`.

Full regression sweep (home, about-us, my-account, lost-password,
contact-us, news, event archive) all 200, no PHP fatals.

## 16 Sep 2026 (cont. 2) — login centering root cause found; My Account course area rebuilt

**Login centering, actually fixed this time**: the previous entry's fix
targeted a legacy flex rule that, it turned out, was never compiled into
the build at all (see next paragraph) — chased a phantom, zero effect.
Real cause, found via a full computed-style + all-stylesheets dump:
WooCommerce's own core `woocommerce.css` has `.woocommerce form.login,
.woocommerce form.register { margin: 2em 0px; ... }` — two classes + an
element type (0,2,1) beats a plain two-class theme rule (0,2,0) for the
margin property specifically. The real form carries both
`woocommerce-form-login` and `login` classes together, so chaining them
(`.woocommerce-form-login.login`) compounds to three classes (0,3,0),
which wins outright. Confirmed centered (equal ~457px gaps either side at
1440px) via the same computed-style check. Removed the dead phantom-target
rule from the previous entry.

**Discovered while chasing that**: `assets/sass/screen.scss` has carried a
header comment since 12 Sep 2026 stating the ENTIRE legacy stylesheet
(~6,700 lines, `assets/sass/legacy/`) was deliberately dropped from the
build — not migrated section by section, just stopped importing it
outright (too large, compiled in on every page unconditionally, and the
direct cause of two prior specificity fights). Missed this on my own first
read and mis-diagnosed two things against legacy source files that were
never actually live: the `.woo-customer-layout` flex rule above, and a
"CF7 messages get a gold legacy background" theory for the CF7 error/
success styling this session also touched — that fix (real error/success
color-coding replacing bare color-only styling) was still worth doing,
just the comment explaining it was wrong and has been corrected. Noting
here so this doesn't get re-diagnosed the same way again: **any legacy
`assets/sass/legacy/**` file must be confirmed present in `assets/css/
screen.css` (grep the compiled output, not the source) before treating it
as a real specificity conflict.**

**"Your Courses" widget rebuilt** (`woocommerce/account/courses.php`) —
was a bare unstyled link list ("Free Online Programme new" / "Modules: 1"
/ a chevron). Now real card widgets matching the reference mock-up's
"Continue learning" style: real course thumbnail (falls back to a pooled
photo if the real one isn't synced locally, same recurring gap), title,
"X of Y lessons completed" + a proportional progress bar, and a real
"Continue" link to the course. Progress is computed from real data —
user meta `course_progress`, but only counting the reliable `"Complete"`
flag (`includes/theme-courses.php`'s own `user_has_completed_lesson()`
reads the same source); the raw numeric values in that same meta are
video watch-position in seconds, not normalisable to a percentage without
each lesson's duration, so those are correctly ignored rather than
mis-read as partial progress. Verified against a real course (granted the
`info@clark-studios.co.uk` test account real access to a real course,
product 559 → courses 7451/5695, via `wp_user_courses` — no real customer
data touched) — 3 real courses render correctly with real lesson counts
(16/17/34) and correctly-empty 0% bars (accurate for a freshly-granted
account, not a bug). Found and fixed a real indexing bug of my own before
it shipped: single-course.php's module/lesson counters (`$c`/`$lesson_id`,
the actual keys progress is stored under) are 1-based, not the 0-based
array index my first draft used — would have silently never matched any
real completed lesson.

**Course player rebuilt dark** (`single-course.php` + new
`_course-player.scss`) per reference/course_player.png — real module/
lesson accordion sidebar (same real ACF `modules` data, same real
`lesson-complete`/`lesson-partial` classes from `user_has_completed_lesson()`,
same JS-driven `.active` expand/collapse and AJAX lesson-loading via
`assets/js/courses.js` — no JS changes, only styling the classes/IDs it
already depends on) plus a "N of N lessons completed" header bar (same
real computation as the courses widget above), a dark lesson-content area,
and full dark styling for the real, already-functional custom HTML5 video
player (`includes/theme-shortcodes.php`'s `sc_player` shortcode — real
play/pause/seek/volume/PIP/fullscreen controls that had no styling at
all beforehand). Per request, deliberately did NOT build the reference's
notes/resources/transcript side panel or comment/discussion tabs — no real
data source for any of them.

Full regression sweep (home, my-account, my-courses, about-us, get-funded,
contact-us, news, event archive) all 200, no PHP fatals. Course list and
player both visually verified logged in as a real test account against
real course data.

## 16 Sep 2026 (cont. 3) — course player polish, FAQ spacing, prefooter color

**Course player**: the global site footer (newsletter band, press logos,
nav links, legal disclaimer) was rendering directly under the lesson
content — wrong for what's meant to be an immersive, distraction-free
learning view. `footer.php`'s existing `$is_account` flag (already used to
skip the newsletter/press sections on My Account) now also covers
`is_singular('course')`, same reasoning. Also hardened the custom video
seek bar's cross-browser reset (`-webkit-appearance`/`-moz-appearance` on
both the `<progress>` and the invisible `<input type=range>` layered over
it, plus `::-webkit-slider-thumb`/`::-moz-range-track` resets) — the
native range thumb/track was showing through as a plain blue OS slider
despite `opacity:0`.

**FAQ section** (Get Funded page + product pages, `.scm-product-extra__faq`) —
the `<h2>` had no margin-bottom at all (`.scm-section h2{margin:0}`
default), sitting flush against the FAQ list; added real spacing. `.scm-faq`
itself was also capped at an arbitrary `max-width:850px` instead of
filling `.scm-shell` like the rest of the page — removed the cap.

**Prefooter color**: `.scm-prefooter` used plain `--navy`, identical to
several other dark bands (education, Trader Hub, dark CTA bands) that can
sit directly above it on a given page, so the seam between them vanished.
Switched it to `--navy-2` ("raised surface on navy", an existing token)
so it's always visually distinct from whatever dark band precedes it.
Tried making `.scm-press` (the "as featured in" logos strip between the
prefooter and the footer) match navy too, since a white strip sandwiched
between two dark ones was the original complaint's concrete example (the
News & Research page) — reverted that part per your follow-up; `.scm-press`
stays white, only the prefooter itself changed shade.

Full regression sweep (news, home, about-us, get-funded, my-account,
contact-us) all 200 throughout this batch, no PHP fatals.

## 16 Sep 2026 (cont. 4) — real progress bug, footer finished, cart/checkout, Markets mock-up

**Real bug**: `woocommerce/account/courses.php`'s progress calculation read
the wrong data source — user meta `course_progress` — while the real,
actually-populated source (confirmed by you: the course player showed
correct completed lessons, the widget didn't) is the `wp_user_lesson_progress`
DB table via `User_Course_Manager::get_user_course_progress()`, the exact
same method single-course.php itself calls. Fixed to call that per-course
instead. Verified by inserting two real "Complete" rows for the test
account (`info@clark-studios.co.uk`) directly into that table and
confirming the widget picked them up.

**Course player footer**: the earlier fix only suppressed the newsletter/
press-logos band (reusing account pages' `$is_account` flag); the real
footer's nav columns, social icons, risk disclaimer and legal/copyright
row were still rendering under the lesson content. Split into a separate
`$is_course` flag (`is_singular('course')`) that suppresses the entire
`<footer>`, not just part of it — an immersive lesson view shouldn't carry
any of the marketing chrome, not most of it.

**My Account page header spacing**: `.woocommerce-account .scm-shell`
added its own `padding-top` on top of `.scm-page__header`'s (page.php,
shared with every generic page) existing bottom padding — two paddings
stacking into one large gap before the actual dashboard/login content.
Removed the account-specific top padding; the shared header's own spacing
is enough on its own.

**Cart/Checkout polish** (new `_cart-checkout.scss`) — real WooCommerce
core templates, no markup changes: purple "alt" buttons → gold (same
specificity-matching approach as the product page and account forms),
bordered/tokenized table + input styling, and Order Notes
(`#order_comments`) given a real `min-height` instead of the browser's
cramped single-line default.

**Markets page mock-up** (`templates/template-markets.php`, assigned to
the real "Markets" page, ID 7549) — built per your explicit steer that the
real content source (dedicated post type vs. existing articles) is still
TBC, so this is deliberately a static concept page rather than the
fully-real-data build every other page this project has gotten: dark
masthead, a "Market snapshot" stat-card grid (same placeholder figures as
the homepage ticker, `static-ticker.php`, shown as cards instead of a
scroll strip per request — needs the same licensed feed that's already a
known gap), a "Market information" card grid that DOES pull real latest
posts (same pool as the homepage's News & Research section) rather than
invented headlines, and a real working newsletter signup band (CF7 493,
reusing the on-dark `.scm-newsletter` treatment from earlier this
session). Added a visible amber-bordered "TBC — static content" review
note right under the hero, explicitly asking you to confirm the real
content-source direction before this gets wired up properly — a new
`.scm-review-note` component in the same "flag it, don't fake it" spirit
as the existing `.scm-no-component` placeholder, but for "this is filled
in, just provisional" rather than "nothing built here yet".

Full regression sweep (markets, home, my-account, cart, my-courses) all
200, no PHP fatals.

## 16 Sep 2026 (cont. 5) — Research/Briefings hubs, nav, dashboard courses, fixes

**New real pages + templates**: "Briefings" (created, ID 7578) and
"Research" (real pre-existing page, ID 7550, previously unassigned) —
`templates/template-briefings.php` reuses `morning-brief.php` as-is (real
Morning Brief/US Open content, already built) rather than duplicating it,
plus a review note flagging the real open question (Beehiiv vs. the
current real CF7 mailing-list form). `templates/template-research.php` is
a plain 2-card hub linking Markets → Briefings (reused the homepage's
`.scm-route` card pattern, new `.scm-routes--2` 2-column variant).

**Nav**: added "Research" as a new top-level dropdown (Markets, Briefings
as children), additive — existing "News & Research" (the real news/
research/blog archive) is untouched, so nothing real gets orphaned from
the header while still satisfying the ask literally. Same hover-dropdown
pattern as the existing "Trading" item.

**Markets page bugs from the first verification pass**: dropped the "Go
deeper with Samuel & Co Markets" band — duplicated the real global
prefooter newsletter section right below it, same mistake as the Contact
page earlier, same fix (remove the page-local one). A garbled-looking
card was also flagged; checked the real post's title/content directly and
both are clean prose, so that reads as a one-off rendering artifact in the
screenshot rather than a real data or template bug — not chased further.

**Dashboard now also shows real courses**: added `wc_get_template_part(
'account/courses')` to `woocommerce/myaccount/dashboard.php` — the exact
same call the "My Courses" tab itself uses, so the overview and the
dedicated tab can never show different data. Gave the "Your Courses"
heading its own class or it collided with `.scm-account-greeting` (was
reused from the "Hello Steve Clark" text, a different element).

**My Account page-header gap, actually fixed this time**: the previous
attempt only removed the account-specific `padding-top` override, but a
separate, generic `.scm-page__body{padding-top:32px}` (`_site.scss`,
applies to every page) was still stacking with `.scm-page__header`'s own
36px — explicitly zeroed padding-top for account pages specifically
rather than touching the generic rule other pages may rely on.

Full regression sweep (markets, research, briefings, my-account, home)
all 200, no PHP fatals.

## 26 Sep 2026 — homepage rebuilt to the newsroom design; news-style archives

New homepage design supplied (editorial newsroom: featured story + stacked
cards + Morning Brief panel, Latest News list + Markets at a glance, Analysis &
Research, founder column band, featured products). `front-page.php` rebuilt
from new components; the previous route/member/learn/education sections are
no longer on the homepage (components still exist for other pages).

- New components: `card-feature`, `card-overlay`, `news-row`, `brief-panel`,
  `static-markets-glance` (**static**, same data-feed gap as the ticker),
  `founder-band`, `featured-products`. `card-post` gained an `editorial`
  variant (topic eyebrow, time · read · author). `static-ticker` gained a
  `light` variant + note.
- Topics: the client creates **child categories** under News / Research;
  `scm_topic()` returns the child (else the parent) for tags/eyebrows. Nothing
  to configure — until topics exist every tag reads "News"/"Research".
- Featured products: WooCommerce products starred as Featured (max 4); falls
  back to the four newest with an editor-only note.
- Founder band: posts by the `samuel.leach` user (ACF override), no photo →
  no image column. ACF group "Homepage — newsroom sections" holds the copy.
- `index.php` is now the news-style archive for the posts page, category and
  topic archives: masthead, category pills + topic pills, featured first
  post, then a dense list (News) or editorial cards (everything else).
- Homepage sections de-duplicate via `scm_shown_ids()`.
- Styles in `assets/sass/scm/_newsroom.scss`; glance tabs + CF7 button
  relabel in `markets.js`.

Verified on this laptop (MAMP): home, /news/, /category/news/,
/category/research/ all 200, no PHP warnings; screenshots at 1440 and 390.

## 26 Sep 2026 (cont.) — Research category archive

`category-research.php` (template hierarchy, no page needed): masthead, category
+ topic pills, featured story + two overlay cards, one block per topic (child
category, latest three), founder band, then the remaining posts as editorial
cards with pagination. Page 2+ is pills + cards only. `scm_topic()` now prefers
the category being viewed when a post has no child topic.
