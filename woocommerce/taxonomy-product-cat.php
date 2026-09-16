<?php 

$term = get_queried_object();

if( $term->term_id ) {

    $args = [
        'post_type'=>'page',
        'meta_key'     => 'woocoomerce_top_level_category',
        'meta_value'   => $term->term_id,
        'meta_compare' => '='
    ];

    $wp = new WP_Query( $args );

    if( $wp->posts ) {
        wp_redirect( get_permalink($wp->posts[0]->ID) );
        die();
    } else {
        wp_redirect( site_url() );
    }   

    
}

wp_redirect( site_url() );
die();


