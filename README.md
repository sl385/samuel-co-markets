# Samuel & Co Markets

WordPress theme and frontend rebuild for Samuel & Co Trading's markets, research and education-first redesign.

## Current branch
Development work is on `samuel-markets-redesign` before anything is merged into `main`.

## Theme structure
- `front-page.php` — dynamic Markets / Research / Education homepage
- `header.php` and `footer.php` — new editorial site shell
- `single.php` — research/news article template
- `page.php` — standard page template
- `functions.php` — theme setup, navigation and content-query helpers
- `assets/css/markets.css` — full visual system and responsive layout
- `assets/js/markets.js` — mobile navigation behaviour

## Homepage content mapping
The homepage is designed to pull content automatically from WordPress:
- `morning-market-brief` category → Morning Brief lead
- `us-open` category → US Open module
- `research` category → Research cards
- latest posts excluding the two daily briefs → What's Moving Markets

## Before production launch
1. Deploy this branch to staging first.
2. Assign the Primary and Footer WordPress menus.
3. Confirm the actual Level 5 and Level 7 URLs.
4. Connect the Morning Brief email form to the chosen CRM/email system.
5. Connect the market ticker to a licensed market-data source.
6. Create or map Research, Learn and Membership landing pages.
7. Review financial-promotion, research and disclosure wording before launch.
8. Test WooCommerce, existing customer logins, products and member flows.
9. Optimise images, caching and Core Web Vitals.
