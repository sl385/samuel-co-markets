# Samuel & Co 2026 — component spec

The single list of what needs designing. Sources: the reference homepage
(`reference/PHOTO-2026-09-10-11-51-31.jpg`), the AI theme's hub pages, and every
custom template on the live site (home, about, contact, service, get-funded ×2,
WooCommerce, My Account, course player, events).

Legend — **Slots**: what content goes in. **Variants**: visual alternatives.
**States**: interactive states to design. **Source**: where content comes from.
"Port" = exists on the live site and must survive; "Ref" = visible in the photo;
"Hub" = from the AI-built hub pages; "New" = needed, not designed anywhere yet.

---

## A. Foundations

| # | Component | Slots / details | Variants | States |
|---|---|---|---|---|
| A1 | **Button** | label, optional leading icon, optional trailing arrow | primary (gold fill), secondary (white, hairline border), on-dark (gold on navy), text/ghost, block (full width); sizes 46px default, 40px small | hover, focus-visible, disabled, loading |
| A2 | **Text link** | label + arrow | underlined gold rule ("View all →"), inline body link, on-dark | hover, focus |
| A3 | **Eyebrow label** | 9px uppercase tracked | with/without trailing 28px gold dash; on-dark (gold-soft) | — |
| A4 | **Meta label** | category · read time · date | on light / on dark | — |
| A5 | **Section heading row** | eyebrow, h2, optional inline sub-line ("Go beyond the headline."), optional "View all →" right | left-only; with link; on-dark | — |
| A6 | **Form controls** | text, email, tel, textarea, select, checkbox, radio, consent checkbox with inline privacy link, error message, help text | default, on-dark (mailing list band), inline (input + button in one row, hero signup), stacked (CF7 forms) | focus, filled, error, disabled, success message |
| A7 | **Pill / tab** | label | filter tab row (All + categories), single tag | default, hover, active (navy fill) |
| A8 | **Status chip** | value + direction | positive (green), negative (red), neutral | — |
| A9 | **Numbered marker** | 1–6 | gold circle, white numeral (Morning Brief key points; process steps) | — |
| A10 | **Icon** | line icon | gold 22px (proof bar, learn topics), navy 16px (UI), social 16px | — |
| A11 | **Image treatment** | image + optional caption block (navy, uppercase, gold dash) + optional quote overlay + optional gradient | hero, editorial card, qualification card | — |
| A12 | **Hairline / divider** | — | section border, card border, vertical column rule | — |
| A13 | **Container** | 1450px shell, 820px prose measure, 1180px article hero | — | — |

## B. Global shell

| # | Component | Slots / details | Variants | States | Source |
|---|---|---|---|---|---|
| B1 | **Site header** | wordmark (SAMUEL & CO / TRADING), "EST. 2012" divider, primary nav (5 items, 2-level dropdown), search icon, cart (basket + total, only when non-empty), Log in / Account, "Join Free" gold button, mobile toggle | default light; sticky/condensed on scroll (optional); logged-in (Account instead of Log in) | dropdown open, mobile open | Ref + Port |
| B2 | **Mobile nav drawer** | full-width panel: nav items stacked, sub-items indented, Log in, Join Free | — | open/closed | Ref |
| B3 | **Footer** | wordmark + tagline "Knowledge. Discipline. Opportunity. A Brighter Tomorrow.", nav row 1 (Markets…Membership), nav row 2 (About, Contact, Terms, Privacy, Risk Disclosure), socials (X, LinkedIn, YouTube, Instagram), copyright, VAT/company line, phone CTA | — | — | Ref + Port |
| B4 | **Risk warning block** | the two regulatory paragraphs (spread betting / FCA partner). Must sit above the footer on every page | — | — | Port (legal) |
| B5 | **Mailing-list band** | eyebrow, h2, one line, email + consent checkbox + Subscribe (CF7 id 493) | navy band (pre-footer); inline in hero (see C1) | error/success | Port |
| B6 | **Press logo strip** | "Samuel & Co in the news" + 6 grey logos (TEDx, Forbes, Entrepreneur, Metro, Express, Yahoo) | strip; inline in masthead (about/home) | — | Port (ACF option) |
| B7 | **Cookie banner** | message, policy link, "Got it" | bottom bar, on-brand | — | Port (replace Silktide look) |
| B8 | **Page masthead (small)** | h1, optional intro line, optional button ("Sign up for news alerts"), optional child-page sub-nav pills | light; dark (navy) with gfx; with sub-nav | active pill | Port (`createMiniHeader`) |
| B9 | **Hub sub-nav strip** | horizontal scrolling links (Latest, Morning Brief, US Open, Macro…) | navy | active, scroll fade | Hub |
| B10 | **Breadcrumb** | Home / Shop / Category / Product | — | — | Port (Woo) |
| B11 | **Pagination** | prev, numbers, next | blog, Woo | current, disabled | Port |
| B12 | **Modal** | title, close, body | join-form modal (CF7 815), disclaimer modal (funded), team-member modal (photo, name, role, socials, bio) | open/closed, scroll-lock | Port |
| B13 | **Notice / alert** | icon, message, optional action | info, success, error (Woo messages), inline form error | — | Port (Woo) |
| B14 | **Loader** | spinner + "Loading lesson…" | full-panel overlay; skeleton card | — | Port (course) |
| B15 | **Empty state** | message + CTA ("No events scheduled… sign up to our mailing list") | events, no courses, no orders, 404 | — | Port |

## C. Editorial

| # | Component | Slots / details | Variants | States | Source |
|---|---|---|---|---|---|
| C1 | **Home hero** | 3-line serif h1, lead, sub, inline signup (email + gold button), footnote, right: full-bleed photo, vertical values list with gold rule, quote card (quote, name, title) | — | signup error/success | Ref |
| C2 | **Hub hero** | eyebrow, h1, lead; right: photo with quote card (navy, gold left rule) | markets / research / learn / education / membership photo | — | Hub |
| C3 | **Proof bar** | 4 × (icon, title, one-liner) with vertical rules | 4-up; 2-up mobile | — | Ref |
| C4 | **Market ticker** | n × (label, price, change chip) + "View full markets →" | navy strip; scrolling marquee (mobile) | live-updating | Ref (needs data feed) |
| C5 | **Lead article (Morning Brief)** | eyebrow + dash, 48px h2, standfirst, primary button + archive link, "Key points this morning" numbered list, large image with caption block | — | — | Ref |
| C6 | **Secondary article (US Open)** | eyebrow, h3, standfirst, button, archive link, thumbnail with play badge | with/without video | — | Ref |
| C7 | **Post card** | image (16:10), meta (category · read time), serif title, 2-line excerpt | default (grid), horizontal/list (image left), compact (no image, hairline top), featured (large image, bigger title), on-dark | hover (title underline / image scale) | Ref + Port |
| C8 | **Card grid** | cards | 2 / 3 / 4 columns; with sidebar (3 + sticky CTA) | — | Ref |
| C9 | **Filter tabs** | All + category pills | above grid; blog category button list (legacy) | active | Ref + Port |
| C10 | **Membership CTA panel** | eyebrow, serif h3, text, gold block button ("Join for £99/month"), tick list, small note | sticky sidebar (navy); full-width band | — | Ref |
| C11 | **Learn topic item** | icon, title, one-liner, hairline top | 2-col grid (home), sidebar list (learn hub) | hover, active (hub sidebar) | Ref + Hub |
| C12 | **Lesson card** | "LESSON 01" meta, title, text | grid of 6 | locked (member-only), completed | Hub |
| C13 | **Education band** | navy: eyebrow, h2, one-liner; 2 × qualification tile (photo, meta, title, "Explore →") | — | hover | Ref |
| C14 | **Qualification card (large)** | "LEVEL 5" meta, title, text, 2×2 fact grid, button | 2-up | — | Hub |
| C15 | **Pricing card** | eyebrow, name, price, period, feature list, button | default, featured (navy, gold), annual | — | Hub |
| C16 | **Feature grid** | 4 × (01–04 numeral, title, text) | on light (soft), on dark | — | Hub |
| C17 | **CTA band** | eyebrow, h2, text, button | light, navy | — | Hub |
| C18 | **Article header** | category eyebrow, 58px h1, meta line (date · read time · author), optional hero image | post, page (title only) | — | Ref + Port |
| C19 | **Prose / wp-content** | h2–h4, p, lists, blockquote (pull quote with gold rule), figure + caption, table, embed/video container, gallery, code, hr, buttons inside content | article (17px serif), page, on-dark | — | Port (needs full type spec) |
| C20 | **Author byline** | avatar, name, role | inline; end-of-article box | — | New |
| C21 | **Prev / next post** | two links with titles | — | hover | Port |
| C22 | **Related posts** | 3 × post card | — | — | New |
| C23 | **Event card** | image, date, cost, title, "Read more" | grid | past event | Port |
| C24 | **Testimonial / story card** | avatar/photo, 5 stars, quote, name, course taken, payout (funded) | slider (Slick), static grid, Google reviews (rating + count) | — | Port |
| C25 | **Team member card** | photo, name, role, socials; opens modal | grid; featured member (video) | hover | Port (about) |
| C26 | **Process steps** | n × (number marker, title, text) | horizontal, vertical | — | Port (about) |
| C27 | **Content strip** | heading, rich text, image, optional button; alternating sides | light, dark, with gfx | — | Port (ACF `content_strip`) |
| C28 | **Logo cloud** | logos row | in masthead, in strip | — | Port |
| C29 | **Stats counters** | big number, label (animated count) | 3-up | — | Port (about "social boxes") |
| C30 | **Video block** | 16:9 embed, poster, play | YouTube, Vimeo | — | Port |
| C31 | **FAQ accordion** | question, answer | single-open | open/closed | Port (funded, product) |
| C32 | **Map + contact block** | map with markers + key, address, phone, email, opening lines | — | — | Port (contact) |
| C33 | **Comparison / pricing matrix** | filter buttons (online 10/25/50, in-person), table of rows (balance, cost, setup fee, max loss, daily loss, payout %), tooltips, "requires pop-up notice" disclaimer, CTA per column | funded 2025 | filter active, tooltip open | Port (complex; design deliberately) |
| C34 | **Mini option card** | title, price, bullet, button | funded options grid | selected | Port |
| C35 | **Gallery** | images grid + lightbox | — | — | Port (shortcode) |
| C36 | **404** | h1, message, search, button | — | — | Port |

## D. Commerce (WooCommerce)

| # | Component | Slots / details | Variants | States | Source |
|---|---|---|---|---|---|
| D1 | **Product card** | image, category term, title, price (incl. subscription string "£99 / month", "Free" for zero), short description, NB line, button ("Enrol" / "Join") | grid; featured; hidden-sub-details variant | hover, sold out | Port |
| D2 | **Product archive** | masthead + category filter pills + product grid | category page | — | Port |
| D3 | **Single product** | gallery, title, price, subscription terms, short desc, add-to-cart / buy-now buttons, disclaimer text, NB text, share | course product, membership product, funded product | added-to-cart | Port |
| D4 | **Product extras** | "What you get" rich text, benefits, "Here's what you'll learn" module list (numbered modules with lessons), FAQ | — | — | Port |
| D5 | **Cart** | line items table, quantity, remove, coupon, totals panel, checkout button | empty cart | — | Port |
| D6 | **Checkout** | billing form (incl. VAT number), account creation, order review table, payment methods, terms consent, place order | logged-in / guest | validation errors | Port |
| D7 | **Thank-you page** | order summary, next steps (access courses) | — | — | Port |
| D8 | **Account layout** | side nav (Dashboard, Orders, Downloads, Subscriptions, Addresses, Courses, Previous purchases, Logout) + content panel | — | active nav | Port |
| D9 | **Account: courses** | course cards (image, title, progress %, "Continue" button), "no subscriptions" notice | — | in-progress, complete | Port |
| D10 | **Account: tables** | orders, downloads, subscriptions, previous purchases (history table with totals col) | — | — | Port |
| D11 | **Auth forms** | login, register (side by side), lost password, reset | login-required nag panel | error | Port |

## E. Course / LMS

| # | Component | Slots / details | Variants | States | Source |
|---|---|---|---|---|---|
| E1 | **Course layout** | h1, left sidebar (modules), right player + lesson | single-list mode (no modules) | — | Port |
| E2 | **Module accordion** | number, module name, chevron; lesson list | — | open, active, disabled |
| E3 | **Lesson row** | "Lesson n:" + name | — | active, completed (tick), locked | Port |
| E4 | **Video player** | 16:9 player, custom controls (play/pause, scrub, time, volume, fullscreen), poster | HTML5 (`sc_player`), Vimeo | playing, buffering | Port |
| E5 | **Lesson content** | lesson title, rich text, assets/download list (icon, name, size), "Next lesson" button | — | — | Port |
| E6 | **Progress bar** | % complete + label | course card, course header | — | Port |

---

## Core design tokens

Already implemented as CSS custom properties in `assets/sass/scm/_tokens.scss`.

**Colour — brand**
- `--navy #061a25` primary dark (ticker, education band, CTA panel, sub-nav)
- `--navy-2 #0d2a36` raised surface on navy
- `--gold #c7a16a` primary accent (buttons, rules, dashes)
- `--gold-deep #937046` eyebrow/meta text on light
- `--gold-soft #dfbe88` gold on dark

**Colour — neutrals**
- `--ink #0c1d28` headings/body · `--ink-2 #30434f` lead copy · `--muted #68747b` secondary · `--muted-2 #8a9094` captions
- `--paper #fbfaf7` page · `--white #fff` cards · `--sand #f4f1ea` soft section
- `--line #ddd8ce` hairlines · `--line-strong #bcb5aa` button borders

**Colour — on dark**: `--on-dark #fff`, `--on-dark-muted #cbd6da`, `--line-on-dark rgba(255,255,255,.12)`

**Colour — data**: `--up #2e9e5b`, `--down #d64545` (plus a neutral)

**Type**
- `--font-display` serif, weight 500, tracking −1.8px, leading ≈1.0 (reference: Tiempos / Source Serif class). **Not chosen yet.**
- `--font-body` sans (reference: Inter class; Typekit `elza` already licensed). **Not chosen yet.**
- `--font-mono` for ticker figures (tabular)
- Scale: display 68 / 48 / 34 / 27 · text 20 / 17 / 15 / 13 / 11 / 9
- Eyebrow: 9px, uppercase, +2.7px tracking, 700

**Space**: 4px base → 4 8 12 16 20 24 32 40 48 56 64 80. Section padding 54px (42px mobile). Card padding 13px. Grid gap 14px.

**Layout**: shell 1450px with 43px gutters (17px mobile); prose 820px; header 84px (70 mobile); control height 46px (40 mobile). Breakpoints 1050 and 680.

**Shape & motion**: radius 0 everywhere except pills; no shadows except text-shadow on photo overlays; transitions 180ms ease.

---

## Master prompt (for the UX tool, attach the reference photo)

See the block at the end of this file; it is also in the chat transcript.

```
You are designing a component-based design system for Samuel & Co Trading, a UK
trading-education and market-research business established in 2012. The attached
image is the approved homepage direction. Treat it as the visual north star and
extend it into a complete, consistent system. Output should be a Figma-style
component library plus page compositions, not just a homepage.

TONE
Editorial, financial-press, restrained. Think FT / Bloomberg Opinion, not fintech
startup. Serif display type, compact sans for UI, hairline rules, square corners,
one accent colour used sparingly. Photography is real-world London / markets
imagery, never abstract 3D or neon. No gradients except subtle dark overlays on
photos. No rounded cards, no drop shadows.

TOKENS (fixed — build everything from these)
Colour: navy #061a25 (primary dark), navy-2 #0d2a36, gold #c7a16a (accent),
gold-deep #937046 (eyebrow text on light), gold-soft #dfbe88 (gold on dark),
ink #0c1d28, ink-2 #30434f, muted #68747b, muted-2 #8a9094, paper #fbfaf7 (page),
white #ffffff (cards), sand #f4f1ea (soft sections), line #ddd8ce, line-strong
#bcb5aa, up #2e9e5b, down #d64545.
Type: display serif (propose two candidates in the Tiempos / Source Serif / GT
Sectra class; weight 500; tracking −1.8px; line-height 1.0), body sans (propose
two in the Inter / Söhne class; Adobe Fonts "Elza" is already licensed and may be
used), mono for figures. Scale: display 68 / 48 / 34 / 27; text 20 / 17 / 15 / 13
/ 11 / 9. Eyebrow style: 9px uppercase, +2.7px tracking, bold, gold-deep,
optionally followed by a 28px gold dash.
Space: 4px base (4 8 12 16 20 24 32 40 48 56 64 80). Section padding 54 desktop /
42 mobile. Card padding 13. Grid gap 14.
Layout: 1450px max shell, 43px gutters (17 mobile); 820px prose measure; header
84px (70 mobile); all controls 46px tall (40 mobile). Breakpoints 1050 and 680.
Shape: 0 radius (pills excepted). Motion: 180ms ease.

DELIVERABLES
1. Foundations sheet: colour, type scale with real text samples, spacing,
   grid, iconography style (thin line icons, gold), image treatments (photo
   with navy caption block, photo with quote overlay, dark gradient overlay).
2. Every component below, with all listed variants and states, on light and
   on navy where marked. Name layers exactly as listed (A1, B3 etc.).
3. Page compositions at 1440 and 390: Home (match the reference), Markets hub,
   Research hub, Learn hub, Education hub, Membership (pricing), Article,
   Blog index with filters, About, Contact, Get Funded (with the pricing
   matrix), Product (course), Product archive, Cart, Checkout, My Account
   (courses tab), Course player, Events, 404.

COMPONENTS
A Foundations: A1 Button (primary gold / secondary hairline / on-dark / text /
block; 46 & 40; hover, focus, disabled, loading). A2 Text link with arrow
(gold underline; on-dark). A3 Eyebrow label (with/without gold dash; on-dark).
A4 Meta label (category · read time · date). A5 Section heading row (eyebrow,
h2, optional inline sub-line, optional "View all →" right-aligned). A6 Form
controls (text, email, textarea, select, checkbox, radio, consent checkbox with
inline link, help/error text; inline input+button variant; on-dark variant;
focus/error/success). A7 Pill tab (default/hover/active navy). A8 Status chip
(up/down/neutral). A9 Numbered marker (gold circle). A10 Icon style. A11 Image
treatments. A12 Hairlines. A13 Containers.

B Global: B1 Site header (wordmark SAMUEL & CO / TRADING, "EST. 2012" divider,
5-item nav with 2-level dropdown, search, cart pill when non-empty, Log in /
Account, gold "Join Free", mobile toggle; logged-in state). B2 Mobile nav
drawer. B3 Footer (wordmark + tagline "Knowledge. Discipline. Opportunity. A
Brighter Tomorrow.", two link rows, social icons X/LinkedIn/YouTube/Instagram,
copyright, VAT/company line, phone CTA). B4 Regulatory risk-warning block
(two paragraphs of legal copy above the footer on every page — must be legible
at small size). B5 Mailing-list band (navy; email + consent checkbox +
Subscribe). B6 Press logo strip ("Samuel & Co in the news", greyscale logos).
B7 Cookie banner. B8 Page masthead (h1, intro, optional button, optional child-
page sub-nav pills; light and navy variants). B9 Hub sub-nav strip (navy,
horizontally scrolling, active state). B10 Breadcrumb. B11 Pagination.
B12 Modal (generic; join-form; disclaimer; team-member bio with photo and
socials). B13 Notices (info/success/error). B14 Loader and skeleton card.
B15 Empty state (message + CTA).

C Editorial: C1 Home hero (as reference: 3-line serif h1, lead, sub, inline
signup, footnote; full-bleed photo with vertical values list on gold rule and a
quote card). C2 Hub hero (eyebrow, h1, lead; photo with navy quote card, gold
left rule). C3 Proof bar (4 × icon/title/line with vertical rules; 2-up mobile).
C4 Market ticker (navy; label, price, change chip; "View full markets →";
marquee on mobile). C5 Lead article block (Morning Brief: eyebrow + dash, 48px
h2, standfirst, button + archive link, "Key points this morning" numbered list,
image with navy caption block). C6 Secondary article block (US Open: eyebrow,
h3, standfirst, button, archive link, thumbnail with play badge). C7 Post card
(image 16:10, meta, serif title, 2-line excerpt; variants: default, horizontal,
compact/no-image, featured/large, on-dark; hover). C8 Card grids 2/3/4 and
3 + sticky sidebar. C9 Filter tabs. C10 Membership CTA panel (navy sticky:
eyebrow, serif h3, text, gold block button "Join for £99/month", tick list,
small note; also as full-width band). C11 Learn topic item (icon, title, line;
2-col grid and sidebar list with active state). C12 Lesson card ("LESSON 01",
title, text; locked and completed states). C13 Education band (navy; eyebrow,
h2, line; two photo tiles with meta, title, "Explore →"). C14 Qualification
card large (LEVEL 5 meta, title, text, 2×2 fact grid, button). C15 Pricing card
(eyebrow, name, price, period, feature list, button; default / featured navy /
annual). C16 Feature grid (01–04 numeral, title, text; light and dark).
C17 CTA band (light and navy). C18 Article header (category eyebrow, 58px h1,
meta line, optional hero image). C19 Prose styles for CMS content: h2–h4, p,
ul/ol, pull-quote with gold rule, figure + caption, table, video embed,
gallery, hr, inline button — at 17px serif, 820px measure. C20 Author byline
(inline and end-of-article). C21 Prev/next post. C22 Related posts (3 cards).
C23 Event card (image, date, cost, title, CTA; past state). C24 Testimonial
card (photo, 5 stars, quote, name, course taken, optional payout figure;
slider and grid; Google-reviews header with rating + count). C25 Team member
card (photo, name, role, socials; opens B12; featured member with video).
C26 Process steps (numbered, horizontal and vertical). C27 Content strip
(heading, rich text, image, button; alternating sides; light/dark). C28 Logo
cloud. C29 Stats counters (3-up big numbers). C30 Video block 16:9.
C31 FAQ accordion. C32 Map + contact details block. C33 Pricing / comparison
matrix (filter buttons for account size and online/in-person; table rows for
starting balance, cost, setup fee, max loss, max daily loss, payout %; tooltip
per row; disclaimer trigger; CTA per column; must collapse sensibly on mobile).
C34 Mini option card (title, price, bullet, button; selected state).
C35 Gallery with lightbox. C36 404 page.

D Commerce: D1 Product card (image, category, title, price incl. subscription
"£99 / month" and "Free", short description, NB line, button; hover, sold out).
D2 Product archive (masthead, category pills, grid). D3 Single product
(gallery, title, price + terms, short description, add-to-cart and buy-now,
disclaimer text, NB text). D4 Product extras ("What you get", benefits,
"What you'll learn" numbered module list, FAQ). D5 Cart (items table, coupon,
totals panel; empty state). D6 Checkout (billing incl. VAT number, order review,
payment methods, consent, place order; validation). D7 Thank-you page.
D8 Account layout (side nav with active state + content panel). D9 Account
courses tab (course cards with progress % and Continue; empty state).
D10 Account tables (orders, downloads, subscriptions, purchase history).
D11 Auth forms (login and register side by side, lost/reset password,
login-required panel).

E Course player: E1 Course layout (h1, module sidebar, player + lesson column;
single-list mode). E2 Module accordion (number, name, chevron; open/active/
disabled). E3 Lesson row (active, completed tick, locked). E4 Video player
(16:9, custom controls: play/pause, scrub, time, volume, fullscreen; poster;
buffering). E5 Lesson content (title, rich text, downloadable assets list,
"Next lesson" button). E6 Progress bar (% and label).

RULES
- Use the tokens; do not introduce new colours, radii or type sizes.
- One gold element per viewport section at most (button or rule), never
  gold text at body size.
- Every component must work on paper (light) and, where marked, on navy.
- Design mobile (390) for every component; ticker becomes a marquee, 4-up
  grids become 1-up, sidebars stack below.
- Photography direction: London financial district, trading floors, real
  commodities, printed press — desaturated slightly, never stock-cheesy.
- Provide redlines (spacing, sizes) on the foundations sheet so the tokens can
  be verified against the build.
```
