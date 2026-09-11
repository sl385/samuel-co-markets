<?php
if (!defined('ABSPATH')) exit;

function scm_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','gallery','caption','style','script']);
    add_theme_support('woocommerce');
    register_nav_menus([
        'primary' => __('Primary Navigation', 'samuel-co-markets'),
        'footer'  => __('Footer Navigation', 'samuel-co-markets'),
    ]);
}
add_action('after_setup_theme', 'scm_setup');

function scm_assets() {
    $ver = wp_get_theme()->get('Version');
    wp_enqueue_style('scm-main', get_template_directory_uri() . '/assets/css/markets.css', [], $ver);
    wp_enqueue_style('scm-hub-pages', get_template_directory_uri() . '/assets/css/hub-pages.css', ['scm-main'], $ver);
    wp_enqueue_script('scm-main', get_template_directory_uri() . '/assets/js/markets.js', [], $ver, true);
}
add_action('wp_enqueue_scripts', 'scm_assets');

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
