<?php
/**
 * Subscription Product Add to Cart
 *
 * Copied from woocommerce-subscriptions/.../templates/single-product/add-to-cart/subscription.php
 * with the same change as the theme's simple.php: every real subscription
 * product here (Trader Hub, Discord memberships, etc.) is one licence per
 * customer, not something bought in multiples — dropped the quantity
 * stepper, submit qty 1.
 *
 * @package WooCommerce-Subscriptions/Templates
 * @version 1.0.0 - Migrated from WooCommerce Subscriptions v2.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Bail if the product isn't purchasable and that's not because it's limited.
if ( ! $product->is_purchasable() && ( ! is_user_logged_in() || 'no' === wcs_get_product_limitation( $product ) ) ) {
	return;
}

$user_id = get_current_user_id();

echo wp_kses_post( wc_get_stock_html( $product ) );

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<?php if ( ! $product->is_purchasable() && 0 !== $user_id && 'no' !== wcs_get_product_limitation( $product ) && wcs_is_product_limited_for_user( $product, $user_id ) ) : ?>
		<?php $resubscribe_link = wcs_get_users_resubscribe_link_for_product( $product->get_id() ); ?>
		<?php if ( ! empty( $resubscribe_link ) && 'any' === wcs_get_product_limitation( $product ) && wcs_user_has_subscription( $user_id, $product->get_id(), wcs_get_product_limitation( $product ) ) && ! wcs_user_has_subscription( $user_id, $product->get_id(), 'active' ) && ! wcs_user_has_subscription( $user_id, $product->get_id(), 'on-hold' ) ) : // customer has an inactive subscription, maybe offer the renewal button. ?>
			<a href="<?php echo esc_url( $resubscribe_link ); ?>" class="button product-resubscribe-link<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php esc_html_e( 'Resubscribe', 'woocommerce-subscriptions' ); ?></a>
		<?php else : ?>
			<p class="limited-subscription-notice notice"><?php esc_html_e( 'You have an active subscription to this product already.', 'woocommerce-subscriptions' ); ?></p>
		<?php endif; ?>
	<?php else : ?>
	<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<input type="hidden" name="quantity" value="1" />

		<button type="submit" class="single_add_to_cart_button button alt scm-add-to-cart-full<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	</form>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
