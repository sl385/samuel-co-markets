<?php
/**
 * My Account dashboard — overrides WooCommerce's plain-text default.
 * Real data only: next upcoming event (same query shape as
 * archive-event.php's real event_date filter) and the customer's most
 * recent real order. The "live programme progress" card from the
 * supporting-components mock-up (% profit, drawdown used) needs a live
 * trading data feed we don't have — same category as the market ticker —
 * so it's deliberately not built here.
 *
 * @see woocommerce/templates/myaccount/dashboard.php (the file this replaces)
 */
defined('ABSPATH') || exit;
?>
<p class="scm-account-greeting">
  <?php printf(
    /* translators: 1: user display name 2: logout url */
    wp_kses(__('Hello %1$s (not %1$s? <a href="%2$s">Log out</a>)', 'woocommerce'), ['a' => ['href' => []]]),
    '<strong>' . esc_html($current_user->display_name) . '</strong>',
    esc_url(wc_logout_url())
  ); ?>
</p>

<div class="scm-account-cards">
  <?php
  $next_event = new WP_Query([
    'post_type' => 'event',
    'posts_per_page' => 1,
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_key' => 'event_date',
    'meta_type' => 'DATE',
    'meta_query' => [['key' => 'event_date', 'compare' => '>=', 'value' => date('Y-m-d H:i:s'), 'type' => 'DATETIME']],
  ]);
  if ($next_event->have_posts()): $next_event->the_post();
    $ts = strtotime(get_field('event_date'));
  ?>
    <section class="scm-dash-card">
      <span class="scm-meta">Next event</span>
      <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
      <?php if ($ts): ?><p><?php echo esc_html(date_i18n('j F Y', $ts) . ' · ' . date_i18n('g:ia', $ts)); ?></p><?php endif; ?>
      <a class="scm-underlink" href="<?php the_permalink(); ?>">View details →</a>
    </section>
  <?php endif; wp_reset_postdata(); ?>

  <?php
  $recent_orders = wc_get_orders(['customer_id' => get_current_user_id(), 'limit' => 1, 'orderby' => 'date', 'order' => 'DESC']);
  if ($recent_orders):
    $order = $recent_orders[0];
    $items = $order->get_items();
    $first_item = $items ? reset($items) : null;
  ?>
    <section class="scm-dash-card">
      <span class="scm-meta">Recent order</span>
      <h3><?php echo $first_item ? esc_html($first_item->get_name()) : esc_html('Order #' . $order->get_order_number()); ?></h3>
      <p>Order #<?php echo esc_html($order->get_order_number()); ?> · <?php echo wp_kses_post($order->get_formatted_order_total()); ?></p>
      <a class="scm-underlink" href="<?php echo esc_url($order->get_view_order_url()); ?>">View order →</a>
    </section>
  <?php endif; ?>
</div>

<?php
// Real "My Courses" content, reused as-is (wc_get_template_part resolves
// to this same woocommerce/account/courses.php the "My Courses" tab uses)
// rather than a second copy of the same real course-card logic, so the
// dashboard overview and the dedicated tab can never drift apart.
wc_get_template_part('account/courses');
?>

<p class="scm-account-links">
  <?php
  $dashboard_desc = __('From your account dashboard you can view your <a href="%1$s">recent orders</a>, manage your <a href="%2$s">billing address</a>, and <a href="%3$s">edit your password and account details</a>.', 'woocommerce');
  printf(
    wp_kses($dashboard_desc, ['a' => ['href' => []]]),
    esc_url(wc_get_endpoint_url('orders')),
    esc_url(wc_get_endpoint_url('edit-address')),
    esc_url(wc_get_endpoint_url('edit-account'))
  );
  ?>
</p>

<?php do_action('woocommerce_account_dashboard'); ?>
