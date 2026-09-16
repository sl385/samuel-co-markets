<?php
/**
 * Component helpers for the 2026 templates.
 *
 * Partials live in partials/components/. Naming convention:
 *   name.php         dynamic — content comes from posts, terms, Woo or ACF
 *   static-name.php  static  — hardcoded for now; the header comment says what
 *                              it needs to become dynamic
 */
if (!defined('ABSPATH')) exit;

/** Render a component partial with args (available as $args inside). */
function scm_component($name, array $args = []) {
    get_template_part('partials/components/' . $name, null, $args);
}

/** ACF option with a default (safe when ACF is inactive or the field is empty). */
function scm_opt($name, $default = '') {
    if (!function_exists('get_field')) return $default;
    $v = get_field($name, 'option');
    return ($v === null || $v === '' || $v === false || $v === []) ? $default : $v;
}

/** Split a textarea option into trimmed non-empty lines. */
function scm_opt_lines($name, array $default = []) {
    $raw = scm_opt($name, '');
    if (!$raw) return $default;
    $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $raw)));
    return $lines ?: $default;
}

/** Category link by slug, falling back to a URL. */
function scm_cat_link($slug, $fallback = '') {
    $term = get_term_by('slug', $slug, 'category');
    return $term && !is_wp_error($term) ? get_term_link($term) : ($fallback ?: home_url('/'));
}

/** First post in a category (or null). Caller must wp_reset_postdata() after using it. */
function scm_first_in_category($slug) {
    $q = scm_posts_by_category_slug($slug, 1);
    return $q->have_posts() ? $q : null;
}
