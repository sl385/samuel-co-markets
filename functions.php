<?php
/**
 * Samuel & Co 2026 theme.
 *
 * Standalone theme. Business logic (CPTs, courses/LMS, WooCommerce customisations,
 * ACF options, shortcodes, AJAX) is ported from the 2022 "samuel-co" theme and lives
 * in includes/ and lib/. Presentation is the scm-* design system in assets/sass/scm.
 */
if (!defined('ABSPATH')) exit;

/* Theme setup
---------------------------------------------------- */
function scm_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('woocommerce');
    // Menu locations ('header', 'footer') are registered in includes/theme-actions.php.
}
add_action('after_setup_theme', 'scm_setup');

/* Core libs (ported)
---------------------------------------------------- */
include(TEMPLATEPATH . '/lib/sco-helpers.php');

/* Core includes (ported)
---------------------------------------------------- */
include(TEMPLATEPATH . '/includes/woocommerce/woo.php');
include(TEMPLATEPATH . '/includes/theme-init.php');
include(TEMPLATEPATH . '/includes/theme-branding.php');
include(TEMPLATEPATH . '/includes/theme-defaults.php');
include(TEMPLATEPATH . '/includes/theme-clean.php');
include(TEMPLATEPATH . '/includes/theme-actions.php');
include(TEMPLATEPATH . '/includes/theme-filters.php');
include(TEMPLATEPATH . '/includes/theme-cpt.php');
include(TEMPLATEPATH . '/includes/theme-settings.php');
include(TEMPLATEPATH . '/includes/theme-functions.php');
include(TEMPLATEPATH . '/includes/theme-shortcodes.php');
include(TEMPLATEPATH . '/includes/theme-ajax.php');
include(TEMPLATEPATH . '/includes/theme-courses.php');
//include(TEMPLATEPATH . '/includes/theme-shopify.php');
//include(TEMPLATEPATH . '/includes/theme-db.php');
include(TEMPLATEPATH . '/includes/woocommerce/user.php');
include(TEMPLATEPATH . '/includes/woocommerce/course-compiler.php');

/* 2026 theme: assets + template helpers
---------------------------------------------------- */
include(TEMPLATEPATH . '/includes/theme-enqueue.php');
include(TEMPLATEPATH . '/includes/theme-components.php');
include(TEMPLATEPATH . '/includes/theme-acf.php');

/* Importer (disabled, ported for reference)
---------------------------------------------------- */
//include(TEMPLATEPATH . '/lib/data-importer/data-import.php');

/* Template helpers used by the scm-* templates
---------------------------------------------------- */
function scm_read_time($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $content = get_post_field('post_content', $post_id);
    $words = str_word_count(wp_strip_all_tags($content));
    return max(1, (int) ceil($words / 220));
}

function scm_posts_by_category_slug($slug, $count = 4) {
    return new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => $count,
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
        'category_name' => $slug,
    ]);
}

function scm_latest_excluding($exclude_ids = [], $count = 4) {
    return new WP_Query([
        'post_type' => 'post',
        'posts_per_page' => $count,
        'post_status' => 'publish',
        'ignore_sticky_posts' => true,
        'post__not_in' => array_map('intval', $exclude_ids),
    ]);
}
