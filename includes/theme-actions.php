<?php 

// Asset enqueueing now lives in includes/theme-enqueue.php




function sco_my_menus() {
  register_nav_menus(
      array(
          'header' => __( 'Header Menu' ),
          'footer' => __( 'Footer Menu' )
      )
  );
}
add_action( 'init', 'sco_my_menus' );



if( function_exists('acf_add_options_page') ) {

  acf_add_options_page(array(
      'page_title' 	=> 'S&Co Settings',
      'menu_title'	=> 'S&Co Settings',
      'menu_slug' 	=> 'sco-general-settings',
      'capability'	=> 'edit_posts',
      'redirect'		=> false
  ));


  acf_add_options_sub_page(array(
		'page_title' 	=> 'Data Migration Settings',
		'menu_title'	=> 'Migration Settings',
		'parent_slug'	=> 'sco-general-settings',
	));
  
}
   

/**
* FUNCTION wrapper_content()
*
* @param $input_string (string) String To Wrap Div Class
* @return string 
**/
function wrapper_content($content) {
  global $post;

  $original = $content;
  $content = "<div class=\"wp-content wp-content-" . get_post_type() . " \">";
  $content .= $original;
  $content .= "</div>";
  return $content;
}
add_filter( 'the_content', 'wrapper_content' );

/**
 * Filter to replace the [caption] shortcode text with HTML5 compliant code
 *
 * @return text HTML content describing embedded figure
 **/
add_filter('img_caption_shortcode', 'sco_img_caption_shortcode_filter',10,3);
function sco_img_caption_shortcode_filter($val, $attr, $content = null)
{
    extract(shortcode_atts(array(
        'id'    => '',
        'align' => '',
        'width' => '',
        'caption' => ''
    ), $attr));

    if ( 1 > (int) $width || empty($caption) )
        return $val;

    $capid = '';
    if ( $id ) {
        $id = esc_attr($id);
        $capid = 'id="figcaption_'. $id . '" ';
        $id = 'id="' . $id . '" aria-labelledby="figcaption_' . $id . '" ';
    }

    return '<figure ' . $id . 'class="wp-caption ' . esc_attr($align) . '" >'
    . do_shortcode( $content ) . '<figcaption ' . $capid 
    . 'class="wp-caption-text">' . $caption . '</figcaption></figure>';
}


/**
 * Filter to replace the [caption] shortcode text with HTML5 compliant code
 *
 * @return text HTML content describing embedded figure
 **/
function div_iframe_wrapper($content) {
    // match any iframes
    $pattern = '~<iframe.*</iframe>|<embed.*</embed>~';
    preg_match_all($pattern, $content, $matches);

    foreach ($matches[0] as $match) {
        // wrap matched iframe with div
        $wrappedframe = '<div class="video-embed">' . $match . '</div>';

        //replace original iframe with new in content
        $content = str_replace($match, $wrappedframe, $content);
    }

    return $content;    
}


add_filter( 'embed_oembed_html', 'wrap_embed_with_div', 10, 3 );

function wrap_embed_with_div( $html ) {
    return '<div class="video-embed">' . $html . '</div>';
}

/**
 * Filter to only show events which are in the future or today
 *
 * @return text HTML content describing embedded figure
 **/

/*
 $date_now = date('Y-m-d H:i:s');
            $args = [
                "post_type" => "event",
                "posts_per_page" => 6,
                'meta_query' 		=> [
                    [
                        'key'			=> 'event_end_date',
                        'compare'		=> '>=',
                        'value'			=> $date_now,
                        'type'			=> 'DATETIME'
                    ]
                ],
                'order'				=> 'ASC',
                'orderby'			=> 'date',
                'meta_key'			=> 'event_end_date',
                'meta_type'			=> 'DATE'
            ];
*/

// check if our query var is set in any query
function sco_pre_get_posts_events( $query ){
  // $query->is_singular guard added: without it this also matched a single
  // event's own main query (its post_type query var is 'event' too), so a
  // past event's own permalink 404'd — the "future events only" filter
  // should only narrow the archive listing, never make a real post
  // unreachable at its own URL. Note: the global is_singular('event') template
  // tag doesn't work here — it depends on queried_object, which isn't
  // resolved yet this early in the query lifecycle — but the query object's
  // own is_singular property is already set correctly by this point.
  if( !is_admin() && $query->is_main_query() && !$query->is_singular && $query->get( 'post_type' ) === 'event'   ) {

    $date_now = date('Y-m-d H:i:s');

    $meta_query =  [
      [
          'key'			=> 'event_date',
          'compare'		=> '>=',
          'value'			=> $date_now,
          'type'			=> 'DATETIME'
      ]
    ];
    
     $query->set('meta_query',$meta_query);
     $query->set('order', 'ASC');
     $query->set('orderby', 'meta_value'); // was 'date' — sorted by publish date, not event_date
     $query->set('meta_key', 'event_date');
     $query->set('meta_type', 'DATE');


  }
  return $query;
}
add_action( 'pre_get_posts', 'sco_pre_get_posts_events' );




function my_acf_google_map_api( $api ){

	if ( defined( 'GOOGLE_MAPS_API_KEY' ) && GOOGLE_MAPS_API_KEY ) {
		$api['key'] = GOOGLE_MAPS_API_KEY;
	}

	return $api;

}

add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');




function add_menu_attributes( $atts, $item, $args ) {
    $atts['itemprop'] = 'url';
    return $atts;
}
 add_filter( 'nav_menu_link_attributes', 'add_menu_attributes', 10, 3 );


 add_action( 'wp_footer', 'sc_theme_scripts' );
function sc_theme_scripts() {
  if( get_field('external_scripts', 'option') ) {
    echo get_field('external_scripts', 'option');
  }

  // Site-wide right-click disabling turned off (14 Sep 2026, at the
  // user's request — "we will add it back later", so left in place
  // commented out rather than deleted) — this blocked every normal
  // browser action (copy text, save/open image in new tab, inspect) for
  // every non-admin visitor on every page, not just protecting course
  // video content. To restore, uncomment the block below. The narrower,
  // legitimate protection on the actual course video player
  // (assets/js/player.js's oncontextmenu on #video) is untouched.
  /*
  if( !current_user_can( 'administrator' ) ) {
    ?>
    <script>
  document.addEventListener('contextmenu', event => event.preventDefault());</script>
    <?php
  }
  */
}



/**
* Filter the upload size limit for non-administrators.
*
* @param string $size Upload size limit (in bytes).
* @return int (maybe) Filtered size limit.
*/
function filter_site_upload_size_limit( $size ) {
    // Set the upload size limit to 10 MB for users lacking the 'manage_options' capability.
    if ( ! current_user_can( 'manage_options' ) ) {
    // 10 MB.
    $size = 1024 * 1000000;
    }
    return $size;
    }
    add_filter( 'upload_size_limit', 'filter_site_upload_size_limit', 20 );





// define the wp_nav_menu_objects callback 
function filter_wp_nav_menu_objects( $sorted_menu_items, $args ) { 

    if( !is_user_logged_in( ) ) {
        return $sorted_menu_items;
    }

    foreach( $sorted_menu_items as $k => $item ) {
 
        if(  $sorted_menu_items[$k]->title == "Login") {
            $sorted_menu_items[$k]->title = "Account";
        }
    }
    // make filter magic happen here... 
    return $sorted_menu_items; 
}; 

// add the filter 
add_filter( 'wp_nav_menu_objects', 'filter_wp_nav_menu_objects', 10, 2 ); 


function crm_redirect(){
    wp_redirect( home_url() . '/crm/'); 
    exit;
}
add_menu_page( 'redirecting', 'JTP', 'read', 'my-top-level-handle', 'crm_redirect');



// -- redirect to cart if url has redirect_to_cart in it
add_filter('woocommerce_add_to_cart_redirect', 'change_woocommerce_add_to_cart_redirect_url');
function change_woocommerce_add_to_cart_redirect_url($url){
    if( isset($_GET['redirect_to_cart'] ) ) {
        $url = wc_get_cart_url();
    }
    
    return $url;
}

// -- prevent any and all of wordpress emails from being sent when in development
//
// Act-now fix (docs/LEGACY-CODE-REVIEW.md 0.9): this used to key off WP_ENV ==
// 'development'. WP_ENV is a general-purpose flag other code may reasonably expect
// to be 'development' outside production (this local install included) without
// intending "and also silently eat every email" — and if it were ever left set to
// 'development' on the live server, order mail, password resets, everything, would
// die with no log and no error. SCM_BLOCK_MAIL is a dedicated, opt-in constant for
// exactly this behaviour so it can never be inherited by accident. Not defined here
// by default; set `define('SCM_BLOCK_MAIL', true);` in wp-config.php on installs
// (like local dev) where real mail sending should be suppressed.
add_filter( 'wp_mail', 'wp_mail_block', 10, 1 );
function wp_mail_block( $args ) {

    if( defined('SCM_BLOCK_MAIL') && SCM_BLOCK_MAIL ) {
       $args['to'] = 'devnull@example.com';
    }

    return $args;

}

add_filter( 'pre_wp_mail', function( $null, $args ) {
    if ( defined( 'SCM_BLOCK_MAIL' ) && SCM_BLOCK_MAIL ) {
        return true;
    }

    return $null;
}, 10, 2 );


add_filter( 'woocommerce_add_to_cart_redirect', function ( $url ) {
    if ( isset($_GET['buy_now']) ) {
        return wc_get_checkout_url();
    }
    return $url;
});