<?php
/**
 * Front-end assets for the 2026 theme.
 *
 * One compiled stylesheet (assets/css/screen.css, built from assets/sass/screen.scss)
 * carries both the scm-* design system and the legacy WooCommerce / course / form styles.
 */
if (!defined('ABSPATH')) exit;

function scm_asset_version($relative) {
    $file = get_stylesheet_directory() . $relative;
    return file_exists($file) ? (string) filemtime($file) : wp_get_theme()->get('Version');
}

function scm_enqueue_assets() {
    global $post;

    $uri = get_stylesheet_directory_uri();

    // Styles
    wp_enqueue_style('scm-screen', $uri . '/assets/css/screen.css', [], scm_asset_version('/assets/css/screen.css'));
    wp_enqueue_style('fa', 'https://use.fontawesome.com/releases/v6.1.1/css/all.css', [], null);

    // Scripts
    wp_enqueue_script('scm-main', $uri . '/assets/js/markets.js', [], scm_asset_version('/assets/js/markets.js'), true);
    wp_enqueue_script('script-js', $uri . '/assets/js/js.js', ['jquery'], scm_asset_version('/assets/js/js.js'), true);

    // Slick carousel: only where the legacy ACF templates use it.
    $template = get_page_template_slug();
    $uses_slick = in_array($template, [
        'templates/template-home.php',
        'templates/template-about.php',
        'templates/template-service.php',
        'templates/template-funded.php',
        'templates/template-funded-2025.php',
    ], true) || is_singular('product');
    if ($uses_slick) {
        wp_enqueue_style('slickjs', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css', [], null);
        wp_enqueue_script('slickjs', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', ['jquery'], null, true);
    }

    // Course player
    if (is_singular('course') && $post) {
        wp_enqueue_script('vimeo-js', 'https://player.vimeo.com/api/player.js', [], null, true);
        wp_enqueue_script('player-js', $uri . '/assets/js/player.js', ['jquery'], scm_asset_version('/assets/js/player.js'), true);
        wp_enqueue_script('course-js', $uri . '/assets/js/courses.js', ['jquery'], scm_asset_version('/assets/js/courses.js'), true);
        wp_localize_script('course-js', 'sco', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('course-nonce-' . $post->ID),
            'course'  => $post->ID,
        ]);
    }

    if ($template === 'templates/template-service.php') {
        wp_enqueue_script('isotope', 'https://unpkg.com/isotope-layout@3.0.6/dist/isotope.pkgd.min.js', ['jquery'], null, true);
    }
}
add_action('wp_enqueue_scripts', 'scm_enqueue_assets', 100);
