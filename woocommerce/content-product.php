<?php
/**
 * Product loop card (spec D1) — reskinned onto the same .scm-card used for
 * post cards everywhere else on the site (see docs/BUILD-NOTES.md: reskin
 * real WooCommerce output at the component level instead of a bespoke
 * per-page design). WooCommerce hooks/logic untouched — only the wrapper
 * markup and classes changed, from the legacy `.card.card--product` (with
 * decorative :before/:after shapes) to `.scm-card`.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined('ABSPATH') || exit;

global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('scm-card', $product); ?>>
  <a class="scm-card__image" href="<?php the_permalink(); ?>"><?php echo $product->get_image('medium_large'); ?></a>
  <div class="scm-card__body">
    <?php $cats = wc_get_product_category_list($product->get_id()); if ($cats): ?><span class="scm-meta"><?php echo wp_kses_post($cats); ?></span><?php endif; ?>
    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 16)); ?></p>
    <div class="scm-card__product-footer">
      <?php woocommerce_template_loop_price(); ?>
      <?php woocommerce_template_loop_add_to_cart(['class' => 'scm-button scm-button--gold scm-button--sm']); ?>
    </div>
  </div>
</li>
