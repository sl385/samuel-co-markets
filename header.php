<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="format-detection" content="telephone=no">
<link rel="stylesheet" href="https://use.typekit.net/zqz1tva.css">
<?php wp_head(); ?>
<!-- Cookie Consent (Silktide) -->
<script>
window.cookieconsent_options = {"message":"Our website uses cookies to enhance your browsing experience. You can learn more about cookies and how to disable them by reading our ","dismiss":"Got it!","learnMore":"Cookie Policy","link":"<?php echo esc_url(home_url('/cookie-policy/')); ?>","theme":"dark-bottom"};
</script>
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/1.0.9/cookieconsent.min.js"></script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="scm-header">
  <div class="scm-shell scm-header__inner">
    <div class="scm-identity">
      <a class="scm-brand" href="<?php echo esc_url(home_url('/')); ?>">
        <span class="scm-brand__main">SAMUEL &amp; CO</span>
        <span class="scm-brand__sub">TRADING</span>
      </a>
      <span class="scm-established">EST. 2012</span>
    </div>
    <nav class="scm-nav" aria-label="Primary">
      <?php
      wp_nav_menu([
        'theme_location' => 'header',
        'container' => false,
        'fallback_cb' => false,
        'depth' => 2,
        'items_wrap' => '<ul class="scm-nav__list">%3$s</ul>',
      ]);
      ?>
    </nav>
    <div class="scm-header__actions">
      <a class="scm-search-link" href="<?php echo esc_url(home_url('/?s=')); ?>" aria-label="Search"><span aria-hidden="true"></span></a>
      <?php if (function_exists('wc_get_cart_url') && WC()->cart && WC()->cart->get_cart_contents_count() > 0): ?>
        <a class="scm-cart" href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php esc_attr_e('View your bag'); ?>">
          <i class="fa-solid fa-shopping-basket"></i>
          <span><?php echo wp_kses_post(WC()->cart->get_cart_total()); ?></span>
        </a>
      <?php endif; ?>
      <?php if (is_user_logged_in()): ?>
        <a class="scm-text-link" href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : admin_url()); ?>">Account</a>
      <?php else: ?>
        <a class="scm-text-link" href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url()); ?>">Log in</a>
      <?php endif; ?>
      <a class="scm-button scm-button--gold" href="#morning-brief-signup">Join Free</a>
      <button class="scm-menu-toggle" aria-label="Open menu" aria-expanded="false">☰</button>
    </div>
  </div>
</header>
<main id="content">
<?php if (is_singular('product') || get_page_template_slug() == 'templates/template-service.php'): ?>
  <div class="modal">
    <div class="modal__box">
      <div class="modal__content">
        <header>
          <h2>Join Now <span id="apply_title"></span></h2><i class="fas fa-times"></i>
        </header>
        <?php echo do_shortcode('[contact-form-7 id="815" title="Free Training - Online Enrolment"]'); ?>
      </div>
    </div>
  </div>
<?php endif; ?>
