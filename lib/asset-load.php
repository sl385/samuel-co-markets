<?php 

define( 'WP_USE_THEMES', false ); // Don't load theme support functionality
require( '../../../../wp-load.php' );


global $wpdb;

if( !is_user_logged_in( ) ) return;

$key = $_GET['key'];

if( !$key ) return;


require( TEMPLATEPATH . '/lib/stream.class.php');

$asset = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE guid LIKE '%" . $key . "%'");

if( !$asset ) {
    // -- So if the likely hood it did not load it's probably Wordpress add "-x" to the end lets account for that.
    $key_parts = explode( "-", $key );
    array_pop($key_parts);
    echo implode("-", $key_parts);

    $asset = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE guid LIKE '%" . implode("-", $key_parts) . "%'");

  
}


if( $asset ) {

    $attachment = get_attached_file($asset[0]->ID);
//    $meta = wp_get_attachment_metadata($asset[0]->ID);
   // header("X-File: " . $attachment);
    $video = new VideoStream( $attachment );
    $video->start();

    /*
    //header('X-Sendfile: ' . $video_file);
    header('Content-Type: video/mp4');
    header('Content-Length: '.$meta['filesize']);
    header("Expires: -1");
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    readfile($attachment);
    exit;

    */
}


