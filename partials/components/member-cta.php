<?php
/**
 * "Trader Hub" full-width dark band. @dynamic
 * Price, URL and benefits come from the real Trader Hub WooCommerce
 * subscription product (slug trader-hub) wherever possible — this used to
 * hardcode a fictional "£99/month" price and a benefits list (Morning
 * Brief, weekly market reports...) that doesn't match what the real
 * product actually offers (a Discord community, trade ideas, market news,
 * Samuel Leach's stock watchlist report). ACF options can still override
 * title/text/benefits for editorial copy, but price/URL now default to the
 * real product rather than a separate invented number. See docs/BUILD-NOTES.md.
 */
$product = function_exists('wc_get_product') ? wc_get_product(get_page_by_path('trader-hub', OBJECT, 'product')) : null;
$title = scm_opt('scm_member_title', 'Join Trader Hub');
$text  = scm_opt('scm_member_text', "Become part of Samuel & Co's professional traders network — trade ideas, market news and Samuel Leach's stock watchlist report, in one Discord community.");
$url   = $product ? get_permalink($product->get_id()) : home_url('/product/trader-hub/');
// Built by hand rather than $product->get_price_html() — that pulls in
// WooCommerce Subscriptions' own "...and a £0.00 sign-up fee" clause
// whenever the real _subscription_sign_up_fee meta is an empty string
// rather than truly absent (a WC Subscriptions quirk, not a theme bug),
// which reads as a rendering glitch on a plain CTA button.
$period = ($product && class_exists('WC_Subscriptions_Product')) ? WC_Subscriptions_Product::get_period($product) : '';
$price_str = $product ? wp_strip_all_tags(wc_price($product->get_price())) . ($period === 'month' ? '/month' : '/' . $period) : '';
$cta   = scm_opt('scm_member_cta', $product ? 'Join for ' . $price_str : 'Join Trader Hub');
$benefits = scm_opt_lines('scm_member_benefits', ['Trader Discord community', 'Real-time trade ideas', 'Relevant market news', "Samuel Leach's stock watchlist"]);
?>
<section class="scm-section scm-section--dark scm-on-dark scm-hub">
  <div class="scm-shell scm-hub__grid">
    <div class="scm-hub__copy">
      <span class="scm-eyebrow">Trader Hub</span>
      <h2><?php echo esc_html($title); ?></h2>
      <p><?php echo esc_html($text); ?></p>
      <ul class="scm-hub__list">
        <?php foreach ($benefits as $b): ?><li><?php echo esc_html($b); ?></li><?php endforeach; ?>
      </ul>
      <a class="scm-button scm-button--gold" href="<?php echo esc_url($url); ?>"><?php echo esc_html($cta); ?></a>
    </div>
    <?php
    // Real Trader Hub product photo (ID 501) isn't synced to this local
    // media library (common gap in this DB copy) — temp stock photo here
    // until the real file/screenshot is available.
    $hub_img = ($product && scm_has_real_thumbnail($product->get_id())) ? get_the_post_thumbnail_url($product->get_id(), 'large') : _i('editorial/london-ny-overlap.webp');
    ?>
    <div class="scm-hub__art" style="background-image:url('<?php echo esc_url($hub_img); ?>')" aria-hidden="true"></div>
  </div>
</section>
