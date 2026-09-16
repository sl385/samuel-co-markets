<?php
/**
 * Single product (spec D3) — reskinned wrapper only: real WooCommerce hooks
 * and logic untouched (gallery, price, add-to-cart, tabs, related products),
 * just wrapped in .scm-shell + a two-column grid instead of the legacy
 * `.section.section--product-head` markup, matching the rest of the site's
 * component-level reskin (see docs/BUILD-NOTES.md).
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined('ABSPATH') || exit;

global $product;

do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form();
    return;
}
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class('scm-product', $product); ?>>

  <div class="scm-product__gallery">
    <?php do_action('woocommerce_before_single_product_summary'); ?>
  </div>

  <div class="scm-product__summary entry-summary">
    <?php do_action('woocommerce_single_product_summary'); ?>
  </div>

</div>

<?php do_action('woocommerce_after_single_product_summary'); ?>
<?php do_action('woocommerce_after_single_product'); ?>

<?php wc_get_template_part('content', 'product-extras'); ?>
