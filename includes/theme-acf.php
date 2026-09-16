<?php
/**
 * ACF field groups for the 2026 templates, registered in PHP so they travel with
 * the theme. Fields hang off the existing "S&Co Settings" options page
 * (slug sco-general-settings, registered in theme-actions.php).
 *
 * Everything here has a default in the partial, so the site renders with no
 * fields filled in.
 */
if (!defined('ABSPATH')) exit;

add_action('acf/init', function () {
    if (!function_exists('acf_add_local_field_group')) return;

    acf_add_local_field_group([
        'key' => 'group_scm_home',
        'title' => 'Homepage (2026)',
        'location' => [[['param' => 'options_page', 'operator' => '==', 'value' => 'sco-general-settings']]],
        'menu_order' => 0,
        'fields' => [
            ['key' => 'field_scm_hero_tab', 'label' => 'Hero', 'type' => 'tab'],
            ['key' => 'field_scm_hero_title', 'label' => 'Title', 'name' => 'scm_hero_title', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'One line per row. Default: Markets. / Research. / Education.'],
            ['key' => 'field_scm_hero_lead', 'label' => 'Lead', 'name' => 'scm_hero_lead', 'type' => 'text'],
            ['key' => 'field_scm_hero_sub', 'label' => 'Sub line', 'name' => 'scm_hero_sub', 'type' => 'text'],
            ['key' => 'field_scm_hero_image', 'label' => 'Image', 'name' => 'scm_hero_image', 'type' => 'image', 'return_format' => 'url', 'preview_size' => 'medium'],
            ['key' => 'field_scm_hero_values', 'label' => 'Values (right column)', 'name' => 'scm_hero_values', 'type' => 'textarea', 'rows' => 5, 'instructions' => 'One word/phrase per line.'],
            ['key' => 'field_scm_hero_quote', 'label' => 'Quote', 'name' => 'scm_hero_quote', 'type' => 'text'],
            ['key' => 'field_scm_hero_quote_cite', 'label' => 'Quote attribution', 'name' => 'scm_hero_quote_cite', 'type' => 'text'],
            ['key' => 'field_scm_signup_form', 'label' => 'Signup form shortcode', 'name' => 'scm_signup_form', 'type' => 'text', 'instructions' => 'e.g. [contact-form-7 id="493"]. Leave empty to show the plain (non-functional) email field.'],

            ['key' => 'field_scm_member_tab', 'label' => 'Membership CTA', 'type' => 'tab'],
            ['key' => 'field_scm_member_title', 'label' => 'Title', 'name' => 'scm_member_title', 'type' => 'text'],
            ['key' => 'field_scm_member_text', 'label' => 'Text', 'name' => 'scm_member_text', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_scm_member_cta', 'label' => 'Button label', 'name' => 'scm_member_cta', 'type' => 'text', 'instructions' => 'Default: Join for £99/month'],
            ['key' => 'field_scm_member_url', 'label' => 'Button URL', 'name' => 'scm_member_url', 'type' => 'url'],
            ['key' => 'field_scm_member_benefits', 'label' => 'Benefits', 'name' => 'scm_member_benefits', 'type' => 'textarea', 'rows' => 6, 'instructions' => 'One per line.'],

            ['key' => 'field_scm_edu_tab', 'label' => 'Education band', 'type' => 'tab'],
            ['key' => 'field_scm_edu_title', 'label' => 'Title', 'name' => 'scm_edu_title', 'type' => 'text'],
            ['key' => 'field_scm_edu_text', 'label' => 'Text', 'name' => 'scm_edu_text', 'type' => 'text'],
            ['key' => 'field_scm_edu_l5', 'label' => 'Level 5 product', 'name' => 'scm_edu_level5', 'type' => 'post_object', 'post_type' => ['product'], 'return_format' => 'id', 'allow_null' => 1],
            ['key' => 'field_scm_edu_l7', 'label' => 'Level 7 product', 'name' => 'scm_edu_level7', 'type' => 'post_object', 'post_type' => ['product'], 'return_format' => 'id', 'allow_null' => 1],

            ['key' => 'field_scm_proof_tab', 'label' => 'Proof bar', 'type' => 'tab'],
            ['key' => 'field_scm_proof_items', 'label' => 'Items', 'name' => 'scm_proof_items', 'type' => 'repeater', 'button_label' => 'Add item', 'max' => 4, 'min' => 0,
             'instructions' => 'The four-up strip under the hero. Icon is a Font Awesome class, e.g. fa-solid fa-shield.',
             'sub_fields' => [
                 ['key' => 'field_scm_proof_icon', 'label' => 'Icon (Font Awesome class)', 'name' => 'icon', 'type' => 'text'],
                 ['key' => 'field_scm_proof_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                 ['key' => 'field_scm_proof_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text'],
             ]],

            ['key' => 'field_scm_learn_tab', 'label' => 'Learn topics', 'type' => 'tab'],
            ['key' => 'field_scm_learn_topics', 'label' => 'Topics', 'name' => 'scm_learn_topics', 'type' => 'repeater', 'button_label' => 'Add topic', 'min' => 0,
             'instructions' => 'The topic grid in the homepage Learn band. Icon is a Font Awesome class, e.g. fa-solid fa-chart-line.',
             'sub_fields' => [
                 ['key' => 'field_scm_learn_icon', 'label' => 'Icon (Font Awesome class)', 'name' => 'icon', 'type' => 'text'],
                 ['key' => 'field_scm_learn_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                 ['key' => 'field_scm_learn_text', 'label' => 'Text', 'name' => 'text', 'type' => 'text'],
             ]],
        ],
    ]);

    // Morning Brief posts: the numbered "Key points this morning" list.
    acf_add_local_field_group([
        'key' => 'group_scm_brief',
        'title' => 'Morning Brief — key points',
        'location' => [[['param' => 'post_category', 'operator' => '==', 'value' => 'category:morning-market-brief']]],
        'fields' => [
            ['key' => 'field_scm_key_points', 'label' => 'Key points', 'name' => 'scm_key_points', 'type' => 'repeater', 'button_label' => 'Add point', 'max' => 6,
             'sub_fields' => [['key' => 'field_scm_key_point', 'label' => 'Point', 'name' => 'point', 'type' => 'text']]],
        ],
    ]);

    // Event posts: two new fields for the single-event info panel
    // (partials/components/event-info.php) — event_date/event_cost/event_url
    // already exist as plain postmeta from the ported legacy code, these are
    // additive. Both are optional and simply don't render a row when empty.
    acf_add_local_field_group([
        'key' => 'group_scm_event',
        'title' => 'Event details',
        'location' => [[['param' => 'post_type', 'operator' => '==', 'value' => 'event']]],
        'fields' => [
            ['key' => 'field_scm_event_venue', 'label' => 'Venue', 'name' => 'event_venue', 'type' => 'text', 'instructions' => 'e.g. London, or a full address. Leave empty to hide this row.'],
            ['key' => 'field_scm_event_sold_out', 'label' => 'Sold out', 'name' => 'event_sold_out', 'type' => 'true_false', 'ui' => 1, 'instructions' => 'Shows "Places available" when unchecked.'],
        ],
    ]);
});
