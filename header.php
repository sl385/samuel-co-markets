<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
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
        'theme_location' => 'primary',
        'container' => false,
        'fallback_cb' => false,
        'items_wrap' => '<ul class="scm-nav__list">%3$s</ul>',
      ]);
      ?>
    </nav>
    <div class="scm-header__actions">
      <a class="scm-search-link" href="<?php echo esc_url(home_url('/?s=')); ?>" aria-label="Search"><span aria-hidden="true"></span></a>
      <a class="scm-text-link" href="<?php echo esc_url(wp_login_url()); ?>">Log in</a>
      <a class="scm-button scm-button--gold" href="#morning-brief-signup">Join Free</a>
      <button class="scm-menu-toggle" aria-label="Open menu" aria-expanded="false">☰</button>
    </div>
  </div>
</header>
<main id="content">
