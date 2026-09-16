<?php 

function sco_add_team_custom_post() {

        $supports = array(
            'title', // post title
            'editor', // post content
            'thumbnail', // featured images
            'custom-fields', // custom fields
            'revisions', // post revisions
            'post-formats', // post formats
        );

        $labels = array(
            'name' => _x('Team Members', 'plural'),
            'singular_name' => _x('Team Member', 'singular'),
            'menu_name' => _x('Team', 'admin menu'),
            'name_admin_bar' => _x('team', 'admin bar'),
            'add_new' => _x('Add New', 'add new'),
            'add_new_item' => __('Add New Team Member'),
            'new_item' => __('New Team Member'),
            'edit_item' => __('Edit Team Member'),
            'view_item' => __('View Team'),
            'all_items' => __('All Team Members'),
            'search_items' => __('Search Team Members'),
            'not_found' => __('None found.'),
        );

        $args = array(
            'supports' => $supports,
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'publicly_queryable' => false,
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-businessman',
        );

        register_post_type('team', $args);

    }
    add_action('init', 'sco_add_team_custom_post');



    function sco_add_success_stories_custom_post() {

        $supports = array(
            'title', // post title
            'editor', // post content
            'thumbnail', // featured images
            'custom-fields', // custom fields
            'revisions', // post revisions
            'post-formats', // post formats
        );

        $labels = array(
            'name' => _x('Success Stories', 'plural'),
            'singular_name' => _x('Success Story', 'singular'),
            'menu_name' => _x('Success Stories', 'admin menu'),
            'name_admin_bar' => _x('Succeses', 'admin bar'),
            'add_new' => _x('Add New', 'add new'),
            'add_new_item' => __('Add New Story'),
            'new_item' => __('New Story'),
            'edit_item' => __('Edit Story'),
            'view_item' => __('View Story'),
            'all_items' => __('All Stories'),
            'search_items' => __('Search Stories'),
            'not_found' => __('None found.'),
        );

        $args = array(
            'supports' => $supports,
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-awards',
        );

        register_post_type('success_story', $args);

    }
    add_action('init', 'sco_add_success_stories_custom_post');

/*
    function sco_add_reviews_custom_post() {

        $supports = array(
            'title', // post title
            'editor', // post content
            'thumbnail', // featured images
            'custom-fields', // custom fields
            'revisions', // post revisions
            'post-formats', // post formats
        );

        $labels = array(
            'name' => _x('Mini Reviews', 'plural'),
            'singular_name' => _x('Mini Review', 'singular'),
            'menu_name' => _x('Mini Reviews', 'admin menu'),
            'name_admin_bar' => _x('Mini Reviews', 'admin bar'),
            'add_new' => _x('Add New', 'add new'),
            'add_new_item' => __('Add New Mini Review'),
            'new_item' => __('New Mini Review'),
            'edit_item' => __('Edit Mini Review'),
            'view_item' => __('View Mini Review'),
            'all_items' => __('All Mini Reviews'),
            'search_items' => __('Search Mini Reviews'),
            'not_found' => __('None found.'),
        );

        $args = array(
            'supports' => $supports,
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-star-half',
        );

        register_post_type('mini_review', $args);

    }
    add_action('init', 'sco_add_reviews_custom_post');
*/

    function sco_add_events_custom_post() {

        $supports = array(
            'title', // post title
            'editor', // post content
            'thumbnail', // featured images
            'custom-fields', // custom fields
            'revisions', // post revisions
            'post-formats', // post formats
        );

        $labels = array(
            'name' => _x('Events', 'plural'),
            'singular_name' => _x('Event', 'singular'),
            'menu_name' => _x('Events', 'admin menu'),
            'name_admin_bar' => _x('Events', 'admin bar'),
            'add_new' => _x('Add New', 'add new'),
            'add_new_item' => __('Add New Event'),
            'new_item' => __('New  Event'),
            'edit_item' => __('Edit  Event'),
            'view_item' => __('View  Event'),
            'all_items' => __('All  Event'),
            'search_items' => __('Search  Events'),
            'not_found' => __('None found.'),
        );

        $args = array(
            'supports' => $supports,
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'has_archive' => true,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-calendar',
        );

        register_post_type('event', $args);

    }
    add_action('init', 'sco_add_events_custom_post');



    function sco_add_courses_custom_post() {

        $supports = array(
            'title', // post title
            'editor', // post content
            'thumbnail', // featured images
            'custom-fields', // custom fields
            'revisions', // post revisions
            'post-formats', // post formats
        );

        $labels = array(
            'name' => _x('Courses', 'plural'),
            'singular_name' => _x('Course', 'singular'),
            'menu_name' => _x('Courses', 'admin menu'),
            'name_admin_bar' => _x('Courses', 'admin bar'),
            'add_new' => _x('Add New', 'add new'),
            'add_new_item' => __('Add New Course'),
            'new_item' => __('New  Course'),
            'edit_item' => __('Edit  Course'),
            'view_item' => __('View  Courses'),
            'all_items' => __('All  Courses'),
            'search_items' => __('Search  Courses'),
            'not_found' => __('None found.'),
        );

        $args = array(
            'supports' => $supports,
            'labels' => $labels,
            'public' => true,
            'query_var' => true,
            'has_archive' => false,
            'hierarchical' => false,
            'menu_icon' => 'dashicons-book-alt',
        );

        register_post_type('course', $args);

    }
    add_action('init', 'sco_add_courses_custom_post');