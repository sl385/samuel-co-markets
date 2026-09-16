<?php

$__agency = array(
    'name' => 'Brandtastic',
    'url' => 'https://brandtastic.co.uk',
    'brand_color' => '#e6007e',
    'guide_url' => 'https://www.hostingtastic.co.uk/guides/samuel-co/',
    'staff' => array(
        0 => array(
            'name' => 'Dan Campling',
            'email' => 'dan@brandtastic.co.uk',
            'title' => 'Owner & Create Director'
        ),   
        1 => array(
            'name' => 'Andrea Rumsey',
            'email' => 'andrea@brandtastic.co.uk',
            'title' => 'Andrea Rumsey - Studio &amp; Account Manager'
        ),  
      
    )
); 

function cs_admin_bar_remove() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
}

function cs_theme_login_logo() { ?>
    <style type="text/css">
    body.login {
        background-color: #000;
    }
    body.login div#login h1 a {
        background-image: url(<?php echo get_bloginfo( 'template_directory' ) ?>/assets/img/logo.png);
        background-position: 50% 50%;
        display: block;
        width: 212px;
        height: 60px;
		background-size: contain;
    }

    body.login .privacy-policy-page-link a,
    body.login p a {
        color: #fff !important;
    }
    </style>
<?php }

function cs_theme_login_logo_url() {
    return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', 'cs_theme_login_logo_url' );

function add_favicon() {
  	$favicon_url = get_stylesheet_directory_uri() . '/images/favicons/favicon.ico';
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}

function cs_footer() {
    global $__agency;
	echo 'Fueled by <a href="http://www.wordpress.org" target="_blank" style="color: ' . $__agency['brand_color'] . '; font-weight: bold;">WordPress</a> | Designed, Built & Maintained by <a href="' . $__agency['url'] . '" style="color:' . $__agency['brand_color'] . '; font-weight: bold;" target="_blank">' . $__agency['name'] . '</a>';
}

function cs_dashboard() {
	global $wp_meta_boxes;
	wp_add_dashboard_widget('custom_help_widget', 'Welcome!', 'cs_custom_dashboard_help');
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
	unset($wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
	unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

	$normal_dashboard = $wp_meta_boxes['dashboard']['normal']['core'];

 	// Backup and delete our new dashboard widget from the end of the array	 
 	$example_widget_backup = array( 'custom_help_widget' => $normal_dashboard['custom_help_widget'] );
 	unset( $normal_dashboard['custom_help_widget'] );

 	// Merge the two arrays together so our widget is at the beginning	 
 	$sorted_dashboard = array_merge( $example_widget_backup, $normal_dashboard );

 	// Save the sorted array back into the original metaboxes 
 	$wp_meta_boxes['dashboard']['normal']['core'] = $sorted_dashboard;
}

function cs_custom_dashboard_help() { 
    global $__agency;
    ?>
    <a href="<?php echo $__agency['url']; ?>" target="_blank"><img  src="https://brandtastic.co.uk/branding/tastic-logo.png"  style="float: right; margin: 0 0 20px 20px;max-width:150px;"></a>
    <p>Welcome to your new website, built by <a href="<?php echo $__agency['url']; ?>" style="color: <?php echo $__agency['brand_color']; ?>; font-weight: bold;" target="_blank"><?php echo $__agency['name']; ?>.</a></p>
    <?php if( $__agency['guide_url'] ) : ?>
    <h4><strong>Your Wordpress Guide</strong></h4>
   
    <p>We've produced a handy guide to help you manage your website, including writing blog posts, editing pages, adding users and all manner of handy hints. It will have been emailed to you, but we've also included it here just in case you've misplaced it.
    <p><a href="<?php echo $__agency['guide_url']; ?>" style="color: <?php echo $__agency['brand_color']; ?>; font-weight: bold;" target="_blank">View your Wordpress guide.</a></p>
    <?php endif; ?>
    <h4><strong>Need Help?</strong></h4>
    <p>We're here to help you at any time. If there's something you need that isn't covered in your guide, you can either phone the office on 01252 627653, or email one of the team:</p>
    <ul>
         <?php foreach($__agency['staff'] as $staff ) : ?>
        <li><a href="mailto:<?php echo $staff['email']; ?>" style="color: <?php echo $__agency['brand_color']; ?>; font-weight: bold;"><?php echo $staff['name']; ?> - <?php echo $staff['title']; ?></a></li>
        
        <?php endforeach; ?>
    </ul>
    <div style="clear: both;"></div>
<?php }

add_action( 'after_setup_theme', 'tastic_theme_setup' );

function tastic_theme_setup() {
    add_action( 'login_enqueue_scripts', 'cs_theme_login_logo' );
    add_action('login_head', 'add_favicon');
    add_action('admin_head', 'add_favicon');
    add_filter('admin_footer_text', 'cs_footer');
    add_action('wp_dashboard_setup', 'cs_dashboard');
    add_action('wp_before_admin_bar_render', 'cs_admin_bar_remove', 0);
    add_filter( 'wpseo_metabox_prio', function() { return 'low';});
}
