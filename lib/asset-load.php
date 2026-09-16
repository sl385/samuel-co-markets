<?php

define( 'WP_USE_THEMES', false ); // Don't load theme support functionality
require( '../../../../wp-load.php' );

// Act-now fix (docs/LEGACY-CODE-REVIEW.md 0.4). Full remediation (signed, expiring,
// per-user URLs) belongs in the samuel-co-core plugin per the review's recommendation;
// this is the bounded fix: no more raw SQL, no more corrupting the video body with
// debug output, and an actual "does this user have access to the course this lesson
// belongs to" check instead of "any logged-in user".

global $wpdb;

if ( ! is_user_logged_in() ) {
    status_header( 403 );
    exit;
}

$key = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
if ( ! $key ) {
    status_header( 400 );
    exit;
}

require( TEMPLATEPATH . '/lib/stream.class.php' );
require_once( TEMPLATEPATH . '/includes/woocommerce/course-compiler.php' );

function scm_find_asset_by_key( $key ) {
    global $wpdb;
    $like = '%' . $wpdb->esc_like( $key ) . '%';
    $asset = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE guid LIKE %s LIMIT 1", $like ) );
    if ( $asset ) {
        return $asset;
    }
    // WordPress sometimes appends "-1" etc. to a duplicate filename; retry one segment shorter.
    $key_parts = explode( '-', $key );
    array_pop( $key_parts );
    if ( ! $key_parts ) {
        return null;
    }
    $like = '%' . $wpdb->esc_like( implode( '-', $key_parts ) ) . '%';
    return $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE guid LIKE %s LIMIT 1", $like ) );
}

// Find which course (if any) owns a lesson with this guid, so access can be checked.
function scm_course_id_for_lesson_guid( $guid ) {
    $courses = get_posts( [ 'post_type' => 'course', 'post_status' => 'publish', 'numberposts' => -1, 'fields' => 'ids' ] );
    foreach ( $courses as $course_id ) {
        $modules = get_field( 'modules', $course_id ) ?: [];
        foreach ( $modules as $module ) {
            foreach ( (array) ( $module['module_lessons'] ?? [] ) as $lesson ) {
                if ( ! empty( $lesson['lesson_guid'] ) && $lesson['lesson_guid'] === $guid ) {
                    return $course_id;
                }
            }
        }
    }
    return 0;
}

$asset = scm_find_asset_by_key( $key );
if ( ! $asset ) {
    status_header( 404 );
    exit;
}

$course_id = scm_course_id_for_lesson_guid( $key );
$manager = new User_Course_Manager();
if ( ! $course_id || ! $manager->user_has_access( $course_id, get_current_user_id() ) ) {
    status_header( 403 );
    exit;
}

$attachment = get_attached_file( $asset[0]->ID );
$video = new VideoStream( $attachment );
$video->start();
