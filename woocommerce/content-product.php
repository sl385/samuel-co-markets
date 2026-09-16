<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

$term_classes = [];
$terms = wp_get_object_terms( $product->get_id(), 'product_cat' );
if( $terms ) {
    foreach( $terms as $term ) {
        $term_classes[] = "term--" . $term->slug;
    }
}


?>
<li class="grid--item term--all <?php echo implode(" ", $term_classes); ?>">
    <div class="card card--product <?php if( get_post_meta($product->get_id(), '_nb_hide_sub_details', true) ) : ?>no-sub-desc<?php endif; ?>">
        <div>
            <figure>
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
            </figure>
            <header class="card__header">
                <h3><?php the_title(); ?></h3>
            </header>
            <?php the_excerpt(); ?>
        </div>
        <div class="card__actions">
            <div class="price">
                <?php woocommerce_template_loop_price(); ?>
            </div>
            <div class="button-group">
                <a href="<?php the_permalink(); ?>" class="button">More Info</a>
                <?php woocommerce_template_single_add_to_cart(); ?>
            
            </div>
        </div>
    </div>
</li>
