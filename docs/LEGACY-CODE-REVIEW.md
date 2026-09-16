# Legacy code review — ported 2022 code in samuel-co-2026

Reviewed 11 Sep 2026. Scope: everything ported verbatim from the 2022 theme
(`includes/`, `lib/`, `woocommerce/`, `templates/`, `partials/`, `single-course.php`,
`archive-event.php`, `assets/js/js.js`, `courses.js`, `player.js`, `course-admin.js`).
Environment: WooCommerce 10.5, WC Subscriptions 7.0, PHP 8.1. Line numbers are as of
this commit. Items marked ✔ were verified by hand, not just by the reviewers.

Severity: **S** security · **B** bug · **P** performance · **H** hygiene · **R** regression
introduced by the port.

---

## 0. Act now (before this theme goes anywhere near staging)

| # | Sev | Where | What |
|---|---|---|---|
| 0.1 | S ✔ | `lib/sco-helpers.php:60` | Google Places API key committed in source (different from the one in wp-config). **Rotate it.** |
| 0.2 | S ✔ | `includes/woocommerce/woo.php:953-954` | Campaign Monitor API key + list ID committed in source. **Rotate it.** Same function `error_log`s the full order object (PII) on every thank-you page view. |
| 0.3 | R ✔ | `includes/theme-courses.php:115` + `includes/theme-enqueue.php:44-46` | `add_course_progress_vars()` calls `exit` inside `wp_footer` (priority 10). The 2026 enqueue correctly puts `player.js` / `courses.js` in the footer (priority 20), so on any course page with an unfinished lesson **the scripts never print and the player is dead**. The 2022 theme only worked because it passed `true` as `$ver` not `$in_footer`, so scripts went in the head. Fix: remove the `exit` (and handle "all lessons complete" / no modules, which currently leave `my_module` undefined → JS ReferenceError). |
| 0.4 | S ✔ | `lib/asset-load.php:9-26` | The "secure" video endpoint: any logged-in user (every Woo customer), raw `$_GET['key']` in `LIKE '%…%'` SQL (injection), substring match on `guid` across **all** posts (so `?key=mp4` serves the first mp4 attachment), no purchase check, `echo` of debug text into the video body, and `stream.class.php:41` sets `Cache-Control: public` so a CDN would cache it for anonymous users. Every range request also boots full WordPress. |
| 0.5 | S ✔ | `includes/theme-ajax.php:213-224` | `update_lesson_progress`: nonce/access validation is commented out; three raw `$_POST` values interpolated into SQL; reachable by any logged-in user via admin-ajax. Also `->progess` typo (:228) means "Complete" is silently overwritten by later pause events. |
| 0.6 | S ✔ | `woocommerce/checkout/thankyou.php:81` → `woo.php:612-624` → `course-compiler.php:111-123` | Course access is granted as a side-effect of **rendering** the thank-you / view-order page, with no payment-status check. The custom action fires outside the `if (!failed)` block, so a failed/pending order unlocks the course when the customer opens the order page. Conversely a paid order that never hits those pages never gets access. No `woocommerce_order_status_completed` / `payment_complete` hook exists anywhere. |
| 0.7 | B ✔ | `templates/template-funded.php:10-11` | Unconditional 301 to the **production** URL at the top of the template. Any local/staging page using it bounces to live (and browsers cache 301s). The remaining 645 lines are dead and duplicated in `template-funded-2025.php`. Delete the file. |
| 0.8 | B ✔ | `assets/js/js.js:90` (and :111, :177, :210, :276) | `$('.stories-slider').slick()` runs unguarded; Slick is only enqueued on five templates + products, so **on every other page the ready handler throws and nothing after line 90 binds** (team modal, mobile nav, enrol modal, matrix buttons, counters). |
| 0.9 | B ✔ | `includes/theme-actions.php:268-286` + `wp-config.php:82` | Two filters black-hole all mail when `WP_ENV == 'development'`, which this install sets. No order mails, no password resets, no log. If a prod wp-config ever carries the constant, mail dies silently. |

---

## 1. Course / LMS

- **S** `theme-courses.php:776-788` — `wp_ajax_user_lesson_progress`: no nonce, raw `$_POST['user']`/`['course']` in SQL; role check `current_user_can('administrator')`. `course-admin.js:22-29` sends no nonce → CSRF-able against an admin.
- **S** `theme-courses.php:677` → `course-compiler.php:171` — `get_users_for_course($_GET['post'])` concatenated into SQL on the product edit screen (admin-only, but GET-triggerable).
- **S** `theme-courses.php:723` — reflected XSS: `value="<?php echo $_GET['post']; ?>"`.
- **S** `theme-courses.php:164-170, 456-462, 669-671` — state-changing actions on GET with no nonce: recompile guarded by magic string `d9udod9`, `?asset_id=` / `?move_all_assets=1` file moves, `?status_update=` grant/revoke inside a metabox render.
- **B** `course-compiler.php:22-29` vs `:81/:87/:171/:179/:193` — `CREATE TABLE` declares `course_id`; every insert/select uses `product_id`. Works on live only because the table was made some other way; a fresh install can never store access. `user_lesson_progress` is never created anywhere.
- **P** `course-compiler.php:5-8, 141` — `new WC_SAM_CO_COURSE_COMPILER` at include time runs `dbDelta()` on **every request**.
- **B** `course-compiler.php:200` — `wcs_get_users_subscriptions()` unguarded; fatal if Subscriptions is deactivated. `:224` only `active` counts, so `pending-cancel` (paid to period end) loses access immediately. `:99/:288` checks the *current* user's admin role, not `$user_id`.
- **P** `course-compiler.php:190-284` — `get_user_courses` N+1 (`wc_get_product` + `get_data()` per subscription per item) on every course view and ajax call; the internal cache never hits because every caller news up a manager.
- **B** `theme-courses.php:14` — rewrite rule targets a full URL; WP wrote a broken external rule into root `.htaccess` (old host, literal `$matches[1]`). Only the direct theme-file URL from `theme-ajax.php:80` works. `:5-9, :19-48` dead (`fetch_course_asset`).
- **B** `theme-courses.php:338-348, course-compiler.php:44-46, 113-117` — `$item->get_product()` false (deleted product) / `$order->get_user()` false (guest) → fatals. `:374` `in_array` on an array-of-arrays → always false. `:833` `number_format()` on non-numeric progress (written verbatim from `$_POST['time']`) → TypeError for admins.
- **B** `theme-ajax.php:25-60, 118-140` — `module=99` or non-numeric → `sizeof(null)` / `"abc" - 1` TypeError → 500. `:108-178 update_course_progress` dead (second, unused progress store). `:297-385 getS3Source` unguarded S3MediaVaultPro classes; `$expirationCustom`/`$filetype` undefined so the CloudFront branch is unreachable.
- **B** `lib/stream.class.php:60-77` — suffix byte-ranges (`bytes=-500`) → `int - ""` TypeError and `fseek()` string offset (confirmed on PHP 8.1). Content-Type hardcoded mp4.
- **B** `lib/sco-helpers.php:151` — `getLessonGUID` never increments `$c`; only module 1 can match → progress rows with empty guid for modules ≥ 2 → duplicates. `:122-124 userCanAccessCourse()` is `return true;` (unused, footgun).
- **B** `single-course.php:35` `$module` undefined; `:52` `data-lesson` missing `echo`; `:78` `[sc_player src="#"]` makes the browser request the page as a video; progress queried twice per page.
- **B** `courses.js:86-146` — Vimeo handlers re-bound per lesson → N progress posts per pause after N lessons. `:54-56` implicit globals. `:104/128` `dataType:'json'` on an empty-body endpoint → parseerror every call. `player.js:2-3` throws if `#video` absent; `:297-321` global `k/m/f/p` shortcuts fire while typing in inputs; `:351` `DOMContentLoaded` registered after it fired.
- **S** `theme-shortcodes.php:74` — `sc_player` `src` attribute unescaped (Author-level content can break the attribute).
- **H** `current_user_can('administrator')` used as a capability in ~8 places; hardcoded product IDs `1550`, `568`; `999999999` sentinel `order_id` into `mediumint`.

## 2. WooCommerce

- **S** `woo.php:847-922 sc_export_subscriptions` — CSV injection (billing name/email/product straight into `fputcsv`), no nonce (`?export_subs` on `admin_init`), role-name check, columns 5/6 swapped and misaligned when next-payment is empty, `->date()` on null fatal, loads every subscription. `:832-845` button: undefined `$_GET['post_type']` warning; with HPOS the hook never fires so the button disappears.
- **S/B** `woo.php:193-204` — all product meta saved raw from `$_POST`, no `isset` (warnings on every save). `_custom_button_url` unescaped in `single-product/add-to-cart/simple.php:68` → `javascript:` URL by anyone with `edit_products`. `:104-142` admin values unescaped in `value=""`.
- **B** `woo.php:632-713` — VAT field chain is dead: reads `billing_vat` where WC stores `_billing_vat` (:664); `str_replace('{company}\n'…)` single-quoted so `\n` is literal and never matches (:682). VAT never appears on orders, emails or account.
- **B** `woo.php:718-736` — subscription price string ignores interval ("every 2 months" prints "a month"), trial, sync; overrides the earlier `:422-432` filter. `:58-61` `the_content()` echo inside a return → wrapper div never renders. `:24` hooks a function name as an action (never fires). `:233-240` `print_r; die()` one uncomment from breaking downloads. `:343` `wc_get_product()` false → fatal. `:571` undefined variable on every order.
- **B** `woocommerce/account/courses.php:29` — `sizeof(get_field('modules'))` fatals (PHP 8) for any course with no module rows → white-screens My Courses.
- **B** `woocommerce/myaccount/my-orders.php` — not a core template since WC 2.6, HPOS-incompatible, would fatal if loaded. Delete.
- **B** `woocommerce/content-product-extras.php` — `$bg` has no key 3; `$i` reused as two counters; `woocommerce_template_single_add_to_cart()` called 4× per page (4 cart forms). `content-product.php:54` calls the *single*-product add-to-cart per loop card and drops all `shop_loop_item` hooks.
- **B** `user.php:243` / `account/previous_purchases.php:12` — `->ordered_at` read before null check; Shopify `order_json` echoed unescaped in admin and front end. `user.php:3-85` class never instantiated (`'adminstrator'` typo inside). `:109-117` N+1 per Users-list row. `:189-205` hot-links logos from wikimedia/shopify/zoho on every admin page.
- **H** Template overrides behind core: `emails/customer-new-account.php` (6.0 vs 10.4) and `customer-reset-password.php` (4.0 vs 10.4) lack the `email_improvements` branches → legacy body inside new header/footer if that feature flag is on; `checkout/thankyou.php` 3.7 vs 8.1; `order/order-details.php` 4.6 vs 10.1 (no actions row); `content-product.php` 3.6 vs 9.4. `archive-product.php` hard-redirects the shop to home, so every "Return to shop" link bounces to the homepage. `taxonomy-product-cat.php:20` `wp_redirect` without `exit`.
- **H** `mailto:sales@` / `info@samuelandcotrading.com` hardcoded in three files.

## 3. Theme core

- **B ✔** `theme-actions.php:253` — `add_menu_page()` at file scope, not in `admin_menu`; gives every `read`-capable user (customers) a "JTP" menu that redirects to `/crm/`. Only avoids a front-end fatal because a plugin happens to load the admin plugin API.
- **S** `theme-actions.php:20-37` + `:194-196` — ACF options page capability is `edit_posts`, and `external_scripts` option is echoed raw into `wp_footer`. A Contributor can inject site-wide script.
- **B** `theme-actions.php:214-222` — `upload_size_limit` comment says 10 MB, sets ~1 GB, and applies to **non**-admins.
- **H** `theme-actions.php:198-203` — right-click disabled for every non-admin visitor.
- **B** `theme-actions.php:143-167` — events `pre_get_posts` orders by publish date not event date, mixes `DATE`/`DATETIME`, hides all past events; `archive-event.php` has no pagination.
- **B** `theme-actions.php:172-180`, `template-contact.php:41` — `GOOGLE_MAPS_API_KEY` constant used with no `defined()` guard → fatal if absent.
- **B** `lib/sco-helpers.php:55-89 getBusinessReviews` — on any failed Google response `array_reverse(null)` fatals the homepage template (PHP 8), nothing is cached so it retries every load, `CURLOPT_TIMEOUT 0` = unlimited, raw curl instead of `wp_remote_get`, legacy `reference=` param.
- **B** `theme-shortcodes.php:21-30` — `[gallery]` override uses attachment `guid` as the URL (breaks after domain/https moves), `property_exists('gallery_object', …)` passes a string so is always false, `gallery-thumb` size never registered. `:50-56` Fotorama loaded from cdnjs on **every** page.
- **B** `theme-branding.php:55` favicon path 404s on every admin page; `:34` deprecated `template_directory`; `:7` links to the 2022 hosting guide; agency staff details hardcoded.
- **R** `theme-cpt.php` — all CPTs lack `show_in_rest` and explicit `rewrite`; `success_story` URL is `/success_story/` with underscore and no template; `team` is `publicly_queryable => false`; `'supports' => 'post-formats'` without theme support. Decide these for the redesign.
- **Dead, safe to delete**: `includes/theme-shopify.php`, `includes/theme-db.php` (both un-included; shopify has a `LIMIT` injection if ever revived), `lib/data-importer/**` (un-included; **no credentials inside**, but its `init` handler has no nonce/cap check and it's PHP 8-broken), the five 0-byte includes (`theme-defaults`, `theme-clean`, `theme-filters`, `theme-init`, `theme-settings`), `theme-actions.php:93-107 div_iframe_wrapper`, `theme-cpt.php:88-129` commented `mini_review`. Also `wp-content/uploads/csv-import/` on live (customer CSVs in a web-readable dir).

## 4. Templates + site JS

- **S** Unescaped non-editor data: Google review `author_url`/`profile_photo_url`/`author_name` (`template-home.php:199-204`), YouTube iframe `src` (`template-about.php:237`), `event_url` (`loop-events.php:2`), and every ACF URL in `href`/`src` across all six templates (no `esc_url`/`esc_attr` anywhere except two lines).
- **B** `template-service.php:20-36, 77` — `$_GET['filter']` unvalidated into `tax_query` (accepts arrays / any slug), undefined-key warning on every load, null `$woo_tax` lists the whole catalogue. `:118-205` 90 lines of commented isotope JS while `theme-enqueue.php` still loads isotope for it.
- **B** `template-funded-2025.php` — `$indexes[0..7]` positional repeater access unguarded (:145-174, :223-252); nested `<p>` (:207-213, :257-263); "Total Setup Cost" omits the monthly cost (:208 vs :211); dead modal + CF7 491 rendered every load (:913-922); two `DOMContentLoaded` handlers fight over mobile initial state (:995-1006 vs :1106-1120); 370 lines commented (:291-658); `success_story` fetched with `-1` then filtered in PHP (:828-850). Template name says 2026, file says 2025.
- **B** `template-about.php:105-121` — world-map key renders literal `999` for 18 countries (placeholder shipped live). `:141-197` `data-count` raw ACF ("10,000") → NaN counters.
- **B** `template-home.php:37` stray `</ul>`; `:184` `lazy="true"`; `:148` hardcoded page ID 27; `:90` `href` on a `<span>`; full-size thumbnails in grids (`:119, :184`, `loop-post.php:4`).
- **B** `template-contact.php:41` — Maps script inline in body, no async, `ginit` callback defined in a script enqueued later (race). `:36` hardcoded CF7 id 7.
- **B** `loop-events.php:8-13` — `date()`+`strtotime()` on ACF display format → wrong day/month order, 1970 on empty, ignores WP timezone. `loop-post.php:6` warns on uncategorised posts.
- **B** `js.js:146-165` team card `return false` swallows the social links inside it on mobile. `:506` `.size()` (removed in jQuery 3; alive only via jquery-migrate). `:97` `<i>` never closed. `:29-32, :293-332, :276-289, :445-460` dead (old header/`.logos`/`.button-modal`). `:572` implicit global `map`; `google.maps.Marker` deprecated.
- **B** All six templates: `$_content = get_field('header')` then array access with no guard → PHP 8 warnings if the template is assigned to a page with no ACF data. `woocommerce_output_all_notices()` unguarded in two templates.
- **A11y** icon-only links without names, focusable button inside `aria-hidden`, disclaimer modal with no `role="dialog"`/focus trap/Escape.

---

## Recommendation

The course/LMS and the access-grant path are the parts that carry real risk and real
revenue, and they're also the parts most entangled with the theme. Rather than patch
them in place inside `includes/`, extract them into a `samuel-co-core` plugin with:
proper table creation on activation, access granted on `woocommerce_order_status_completed`
/ `processing` and revoked on refund/cancel/subscription-status change, a signed
expiring media URL bound to user + attachment ID, nonces and `$wpdb->prepare` throughout,
and real capabilities. That gives a clean seam between the redesign and the business
logic, which is what the port was missing.

Everything in section 4 is disposable once the new templates exist; don't spend time
fixing the legacy ACF templates beyond the funded-page redirect and the API keys.
