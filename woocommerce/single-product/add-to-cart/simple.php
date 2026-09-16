


    <?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

echo wc_get_stock_html( $product ); // WPCS: XSS ok.


if ( $product->is_in_stock() ) : 

    if( !get_post_meta( $product->get_id(), '_nb_enrol', true) && !get_post_meta( $product->get_id(), '_custom_button_url', true )) :
    ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<?php
		// Every real product here is a one-off course, programme, funding
		// account or software licence — nobody buys "2" of one, so no
		// quantity stepper; always submit qty 1 rather than showing a
		// choice that doesn't exist.
		?>
		<input type="hidden" name="quantity" value="1" />

		<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt scm-add-to-cart-full"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>
	</form>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

    <?php else : ?>
		<?php if( get_post_meta( $product->get_id(), '_nb_enrol', true) ) : ?>
        <span data-action="enrol" class="button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></span>
		<?php else : ?>

		<a class="button" target="_blank" href="<?php echo get_post_meta( $product->get_id(), '_custom_button_url', true ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></a>
		<?php endif; ?>
    <?php endif; ?>

<?php endif; ?>
