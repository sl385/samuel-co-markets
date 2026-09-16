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

/**
 * Featured-image fallback pool.
 *
 * This local install's media library (wp-content/uploads) wasn't migrated from
 * the live site, so a post can have a real `_thumbnail_id` in the database
 * (has_post_thumbnail() === true) while the actual file is missing — WordPress
 * doesn't check the file exists, only the database link, so that renders a
 * broken <img>. scm_has_real_thumbnail() checks the file too; scm_the_thumbnail()
 * / scm_the_thumbnail_url() fall back to one of five real, freely-licensed
 * finance/London-skyline photos (assets/img/fallback/, sourced from Wikimedia
 * Commons — see docs/BUILD-NOTES.md for licenses/attribution) instead of a
 * broken image or an empty box. The same post always gets the same fallback
 * photo (picked from its ID), not a random one on every load.
 */
function scm_has_real_thumbnail($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    if (!has_post_thumbnail($post_id)) return false;
    $file = get_attached_file(get_post_thumbnail_id($post_id));
    return $file && file_exists($file);
}

function scm_fallback_image_pool() {
    return [
        'fallback-1-canary-wharf.webp',
        'fallback-2-london-skyline.webp',
        'fallback-3-stock-market.webp',
        'fallback-4-trading-floor.webp',
        'fallback-5-lse.webp',
    ];
}

function scm_fallback_image_url($post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    $pool = scm_fallback_image_pool();
    return _i('fallback/' . $pool[$post_id % count($pool)]);
}

/** Echo the post thumbnail at $size, or a pooled fallback photo if the real file is missing. */
function scm_the_thumbnail($size = 'medium_large', $post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    if (scm_has_real_thumbnail($post_id)) {
        echo get_the_post_thumbnail($post_id, $size, ['class' => 'attachment-' . (is_array($size) ? 'custom' : $size)]);
        return;
    }
    printf('<img src="%s" alt="" class="scm-fallback-img">', esc_url(scm_fallback_image_url($post_id)));
}

/** URL form, for CSS background-image contexts (e.g. the article hero). */
function scm_the_thumbnail_url($size = 'full', $post_id = null) {
    $post_id = $post_id ?: get_the_ID();
    if (scm_has_real_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, $size);
    }
    return scm_fallback_image_url($post_id);
}

/**
 * Resolve an ACF image field to a real URL, or a pooled fallback.
 * ACF image fields on this install return either a raw attachment ID or an
 * array (return_format varies per field) — never assume "url" (see
 * templates/template-about.php's content_strip_3 image, which is an ID
 * pointing at a media-library row whose file was never synced to this local
 * install, so it rendered as a broken image). $seed picks a stable fallback.
 */
function scm_acf_image_url($value, $size = 'large', $seed = 0) {
    $id = 0;
    $url = '';
    if (is_numeric($value)) {
        $id = (int) $value;
    } elseif (is_array($value)) {
        $id = (int) ($value['ID'] ?? $value['id'] ?? 0);
        $url = $value['url'] ?? '';
    } elseif (is_string($value) && $value) {
        $url = $value;
        $id = attachment_url_to_postid($value);
    }

    if ($id) {
        $file = get_attached_file($id);
        if ($file && file_exists($file)) {
            $resolved = wp_get_attachment_image_url($id, $size);
            if ($resolved) return $resolved;
        }
    } elseif ($url) {
        // No attachment record to check — fall back to a direct file check
        // against the uploads dir (handles a bare URL with no ID behind it).
        $uploads = wp_get_upload_dir();
        $path = str_replace($uploads['baseurl'], $uploads['basedir'], $url);
        if (file_exists($path)) return $url;
    }
    return scm_fallback_image_url($seed);
}

/**
 * Resolve an ACF image field to a real URL, or empty string if the file is
 * missing — no pooled fallback. For small icon-shaped images (e.g. Get
 * Funded's content_grid facility icons), substituting a big stock photo
 * would look wrong; better to just not render the icon than fake one.
 */
function scm_acf_icon_url($value, $size = 'thumbnail') {
    $id = is_numeric($value) ? (int) $value : (int) (is_array($value) ? ($value['ID'] ?? $value['id'] ?? 0) : 0);
    if (!$id) return '';
    $file = get_attached_file($id);
    if (!$file || !file_exists($file)) return '';
    return wp_get_attachment_image_url($id, $size) ?: '';
}
