<?php 


Class WC_SAM_CO {

    public function __construct() {

        $this->singleProductActions();
    

    }


    public function singleProductActions() {

        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
        remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
        remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb',  20);
        add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 6 );
        add_action( 'woocommerce_single_product_summary', [$this,'addDescriptionToSingleInstance'], 20 );
        add_action( 'woocommerce_template_single_add_to_cart', [$this, 'addNBText'], 40);
       // add_filter( 'woocommerce_is_sold_individually', [$this,'removeQtyFields'], 9999, 2 );
        add_filter( 'woocommerce_product_data_tabs', [$this, 'add_custom_data_tabs'] , 99 , 1 );
        add_action( 'woocommerce_product_data_panels', [$this,'add_my_custom_product_data_fields'] );
        add_action( 'woocommerce_process_product_meta', [$this, 'woocommerce_process_product_meta_fields_save'] );
        add_action('admin_head', [$this,'add_custom_css']);

        add_filter('woocommerce_product_single_add_to_cart_text', [$this,'change_add_to_cart_text'] );
        add_filter('woocommerce_product_add_to_cart_text', [$this,'change_add_to_cart_text'] );
       
        add_filter( 'woocommerce_get_price_html', [$this,'sco_price_free_zero'], 9999, 2 );

        add_action( 'woocommerce_single_product_summary', [$this,'addNBText'], 40);

	    add_action( 'woocommerce_before_single_product_summary',  [$this,'addDisclaimerText'], 40 );
      
        //add_filter('woocommerce_order_is_download_permitted', [$this,'add_processing_status_to_download_permission'] , 10, 2);
    
        add_filter( 'body_class', [$this,'addBodyCLasses']);

        add_action( 'template_redirect', [$this, 'redirectHiddenProduct'] );
        add_filter( 'woocommerce_add_to_cart_validation', [$this, 'filter_wc_add_to_cart_validation'], 10, 3 );
       // add_action( 'woocommerce_email_after_order_table', [$this, 'add_customer_message_for_algo_products'], 10, 4);



        $this->setupHooks();
    }

    public function setupHooks() {

       
    }

    public function addDescriptionToSingleInstance() {
        global $product;
        return '<div class="product_description">' . the_content() . '</div>';
    }

    public function removeQtyFields( $individually, $product ) {
        return true;
    }


  
    public function add_custom_data_tabs( $product_data_tabs ) {

        $product_data_tabs['sc_courses'] = array(
            'label' => __( 'Courses', 'my_text_domain' ),
            'target' => 'sc_courses',
        );

        $product_data_tabs['sc_other'] = array(
            'label' => __( 'Other', 'my_text_domain' ),
            'target' => 'sc_cother',
        );

        return $product_data_tabs;
    }

    public function add_my_custom_product_data_fields() {
        global $woocommerce, $post;

        $courses = get_posts('post_type=course&posts_per_page=-1');

        $meta = get_post_meta( $post->ID, '_sc_courses', true );

       

        $meta = is_array($meta) ? $meta : [];

        ?>
        <!-- id below must match target registered in above add_my_custom_product_data_tab function -->
        <div id="sc_courses" class="panel woocommerce_options_panel">

            <div class="sc-form-field _sc_courses_field sc_cother_field">
		        <label for="_sc_courses"><b>Select Course(s) attached to this product:</b></label>
                <hr style="display:block" style="width:100%"/>
                <div>
                    <?php foreach( $courses as $course ) : ?>
                        <input <?php if( in_array( $course->ID, $meta ) ) : ?> checked="checked" <?php endif; ?> type="checkbox" class="checkbox" style="" name="_sc_courses[]" id="_sc_courses_<?php echo $course->ID; ?>" value="<?php echo $course->ID; ?>"> - <?php echo $course->post_title; ?><br/> 
                        <hr style="display:block"/>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div id="sc_cother" class="panel woocommerce_options_panel">

            <div class="sc-form-field sc_cother_field ">
		        <label for="_sc_cother">Custom Add To Cart Button Label</label>
                <div>
                    <input type="text" name="_custom_button_label" value="<?php echo  get_post_meta( $post->ID, '_custom_button_label', true ); ?>" />
                </div>
            </div>

            <hr style="display:block"/>

            <div class="sc-form-field sc_cother_field ">
		        <label for="_sc_cother">External URL for button</label>
                <div>
                    <input type="text" name="_custom_button_url" value="<?php echo  get_post_meta( $post->ID, '_custom_button_url', true ); ?>" />
                </div>
            </div>

            <hr style="display:block"/>

            <div class="sc-form-field sc_cother_field ">
		        <label for="_sc_cother">Product Disclaimer Text ( Appears underneath product image on product detail page )</label>
                <div>
                    <input type="text" name="_disclaimer" value="<?php echo  get_post_meta( $post->ID, '_disclaimer', true ); ?>" />
                </div>
            </div>

            <hr style="display:block"/>

            <div class="sc-form-field sc_cother_field ">
		        <label for="_sc_cother">Product NB Text ( Appears underneath Add To Cart button on product detail page )
                    <input type="text" name="_nb_text" value="<?php echo  get_post_meta( $post->ID, '_nb_text', true ); ?>" />
                </label>
            </div>

            <hr style="display:block"/>

            <div class="sc-form-field sc_cother_field ">
		        <label for="_sc_cother">Show Enrol now pop up on when button is clicked?
                    <input type="checkbox" value="1" name="_nb_enrol" <?php if(  get_post_meta( $post->ID, '_nb_enrol', true ) ) : ?> checked="checked" <?php endif; ?> />
                </label>
            </div>

            <hr style="display:block"/>

            <div class="sc-form-field sc_cother_field ">
		        <label for="_sc_cother">Hide subscription description ( i.e. every 4 weeks )
                    <input type="checkbox" value="1" name="_nb_hide_sub_details" <?php if(  get_post_meta( $post->ID, '_nb_hide_sub_details', true ) ) : ?> checked="checked" <?php endif; ?> />
                 </label>
            </div>

            <hr style="display:block"/>

            <div class="sc-form-field sc_cother_field ">
		        <label for="_sc_cother">Product requires submisson of serial/product key
                    <input type="checkbox" value="1" name="_nb_needs_key" <?php if(  get_post_meta( $post->ID, '_nb_needs_key', true ) ) : ?> checked="checked" <?php endif; ?> />
                 </label>
            </div>

            <hr style="display:block"/>

            <div class="sc-form-field sc_cother_field ">
		        <label for="_sc_cother">Product requires a course attendance date
                    <input type="checkbox" value="1" name="_nb_needs_attendance_date" <?php if(  get_post_meta( $post->ID, '_nb_needs_attendance_date', true ) ) : ?> checked="checked" <?php endif; ?> />
                 </label>
            </div>

           

            <!--                
            <div class="sc-form-field sc_cother_field ">
		        <label for="_sc_cother">Requires Contracts before download is released?</label>
                <div>
                    <input type="checkbox" name="_nb_allow_processing_download" value="1" <?php if( get_post_meta( $post->ID, '_nb_allow_processing_download', true ) ) : ?> checked="checked" <?php endif; ?> />
                </div>
            </div>
                    -->

        </div>
        <?php
    }

    public function woocommerce_process_product_meta_fields_save( $post_id ){
        // This is the case to save custom field data of checkbox. You have to do it as per your custom fields
        update_post_meta( $post_id, '_sc_courses', $_POST['_sc_courses'] );
        update_post_meta( $post_id, '_custom_button_label', $_POST['_custom_button_label'] );
        update_post_meta( $post_id, '_disclaimer', $_POST['_disclaimer'] );
        update_post_meta( $post_id, '_nb_text', $_POST['_nb_text'] );
        update_post_meta( $post_id, '_nb_enrol', $_POST['_nb_enrol'] );
        update_post_meta( $post_id, '_nb_allow_processing_download', $_POST['_nb_allow_processing_download'] );
        update_post_meta( $post_id, '_nb_hide_sub_details', $_POST['_nb_hide_sub_details'] );
        update_post_meta( $post_id, '_nb_needs_key', $_POST['_nb_needs_key'] );
        update_post_meta( $post_id, '_custom_button_url', $_POST['_custom_button_url'] );
        update_post_meta( $post_id, '_nb_needs_attendance_date', $_POST['_nb_needs_attendance_date'] );

        // -- To DO [ My assumption this does not need to happen until later ]
        
        // -- right we need to check the courses attached to this product if they have removed some then we need to remove access to this course for any user.

        // -- Likewise if a course has been added to a product we need to give anyone who has bought this product access to this new course.
        
        
    }

    public function addDisclaimerText() {
        global $product;
        if( get_post_meta( $product->get_id(), '_disclaimer', true)) {
            ?>
            <small class="disclaimer">*<?php echo get_post_meta( $product->get_id(), '_disclaimer', true); ?></small>
            <?php 
        }
    }

    public function addNBText() {
        global $product;
        if( get_post_meta( $product->get_id(), '_nb_text', true)) {
            ?>
            <small class="disclaimer">*<?php echo get_post_meta( $product->get_id(), '_nb_text', true); ?></small>
            <?php 
        }
    }

    public function add_processing_status_to_download_permission($data, $order) {

        print_r( $order );
        die();

        if ( $order->has_status( 'on-hold' ) ) { return true; }
        return $data;
    }

    public function addBodyCLasses( $classes ) {

        if( is_account_page() && !is_user_logged_in() ) {
            $classes[] = "woo-login";
        }

        return $classes;

    }


    public function change_add_to_cart_text() {
        global $product;
        if ( get_post_meta(get_the_ID(), '_custom_button_label', true ) ) {
            return get_post_meta( get_the_ID(), '_custom_button_label', true );
        } else {
            return "Add To cart";
        }

        /* 
        global $product;
	
        $product_type = $product->product_type;
        
        switch ( $product_type ) {
            case 'external':
                return __( 'Take me to their site!', 'woocommerce' );
            break;
            case 'grouped':
                return __( 'VIEW THE GOOD STUFF', 'woocommerce' );
            break;
            case 'simple':
                return __( 'WANT. NEED. ADD!', 'woocommerce' );
            break;
            case 'variable':
                return __( 'Select the variations, yo!', 'woocommerce' );
            break;
            default:
                return __( 'Read more', 'woocommerce' );
        }
        */
    }

    public function sco_price_free_zero( $price, $product ) {
        if ( $product->is_type( 'variable' ) ) {
            $prices = $product->get_variation_prices( true );
            $min_price = current( $prices['price'] );
            if ( 0 == $min_price ) {
                $max_price = end( $prices['price'] );
                $min_reg_price = current( $prices['regular_price'] );
                $max_reg_price = end( $prices['regular_price'] );
                if ( $min_price !== $max_price ) {
                    $price = wc_format_price_range( 'FREE', $max_price );
                    $price .= $product->get_price_suffix();
                } elseif ( $product->is_on_sale() && $min_reg_price === $max_reg_price ) {
                    $price = wc_format_sale_price( wc_price( $max_reg_price ), 'FREE' );
                    $price .= $product->get_price_suffix();
                } else {
                    $price = 'FREE';
                }
            }
        } elseif ( 0 == $product->get_price() ) {
            $price = '<span class="woocommerce-Price-amount amount">FREE</span>';
        }  
        return $price;
    }

    public function add_custom_css() {
        ?>
        <style>
            .sc_cother_field {
                display: flex;
                align-items: flex-start;
                flex-wrap: wrap;
            }
            .sc_cother_field label {
                margin-right: 1em;
                margin-left: 0;
                display: block;
                flex-basis: 100%;
            }
            .sc_cother_field div {
                flex-basis: 100%;
                margin-top: 5x;
            }
            .sc-form-field  {
                padding: 15px 15px 0 15px;
                margin-bottom: 15px;
            }

        </style>
        <?php 
    }

    public function redirectHiddenProduct() {
        global $post;
        if( is_singular( 'product' ) ) {
            if( $post->post_status == "private" ) {
                wp_redirect( site_url() );
                die();
            }
            $product = wc_get_product($post->ID);
            if( $product->get_catalog_visibility() == "hidden" ) {
                wp_redirect( site_url() );
                die();
            }
        }
    }


    public function filter_wc_add_to_cart_validation( $passed, $product_id, $quantity ) {
   
        $product = wc_get_product( $product_id );

        if( $product->get_catalog_visibility() == "hidden" ) {
            wc_add_notice( __( $product->get_title() . " cannot be added to your cart.", "woocommerce" ), 'error' );
            return false;
        }

        return $passed;
    }


    public function add_customer_message_for_algo_products( $_order, $sent_to_admin, $plain_text, $email ) {

        $order = wc_get_order( $_order );

        if( !$order ) return;

        if( $sent_to_admin ) return;

        $products = [];
        $reqiures_attendance_date = false;

        // -- Go through order items and see if any product is an algoritim - if so we need to flag what software needs keys supplied:
        foreach ($order->get_items() as $item_id => $item ) {
           // -- get the product
           $product = $item->get_product();
           // -- Is it an algoritihim
           if( get_post_meta( $product->get_id(), '_nb_needs_key', true) ) {
               $products[] = $product->get_name();
           }
           if( get_post_meta( $product->get_id(), '_nb_needs_attendance_date', true) ) {
            $reqiures_attendance_date = true;
            }
        }



        if( $products ) {
            ?>
            <table width="100%" cellpadding="25" cellspacing="0" border="0" style="background-color:#CBAC6A;margin-bottom:15px;">
                <tr>
                    <td>
                        <p style="color:#ffffff;line-height:1.2;font-size:14px;">As a part of your purchase, you will be required to supply us with your software product key and other details as below.</p>
                        <ul style="color:#ffffff;padding-left:15px;font-size:14px;">
                            <li>Order number</li>
                            <li>Name</li>
                            <li>Email</li>
                            <li>Trading account number</li>
                            <li>MT4 or MT5</li>
                            <li>Name of broker</li>
                        </ul>
                        <p  style="color:#ffffff;font-size:14px;">Once we have this we will supply you with your Algorithm.</p>
                    </td>
                </tr>
            </table>
            <?php 
        }

   
        
    }

    

}
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );


function subscriptions_custom_price_string( $pricestring ) { 


    $pricestring = str_replace( '/ month', 'a month', $pricestring );

  
    return $pricestring;
}

add_filter( 'woocommerce_subscriptions_product_price_string', 'subscriptions_custom_price_string' );
add_filter( 'woocommerce_subscription_price_string', 'subscriptions_custom_price_string' );



new WC_SAM_CO;




add_filter('acf/upload_prefilter/key=field_633a169a4e520', 'lesson_downloads_upload_prefilter');
add_filter('acf/upload_prefilter/key=field_633a164f4e51e', 'lesson_downloads_upload_prefilter');
function lesson_downloads_upload_prefilter($errors) {


   // die('123');

  // in this filter we add a WP filter that alters the upload path
  add_filter('wp_handle_upload_prefilter', 'rename_current_acf_upload', 11, 1);
  add_filter('upload_dir', 'field_name_upload_dir');
  return $errors;
}

// second filter
function field_name_upload_dir($uploads) {
  // here is where we later the path
  $uploads['path'] = $uploads['basedir'].'/course_assets';
  $uploads['url'] = $uploads['baseurl'].'/course_assets';
  $uploads['subdir'] = '';
  return $uploads;
}

function rename_current_acf_upload( $file ) {

    $info = pathinfo($file['name']);
    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    $name = basename($file['name'], $ext);
    $file['name'] = uniqid() . '_' . uniqid() . $ext; // uniqid method

	return $file;
} 




Class WC_SAM_CO_COURSES {

    public function __construct() {
        add_rewrite_endpoint('my-courses', EP_ROOT | EP_PAGES);
        add_rewrite_endpoint('my-previous-purchases', EP_ROOT | EP_PAGES);
        add_filter('woocommerce_account_menu_items', [$this, 'add_course_menu_items']);
        add_action('woocommerce_account_my-courses_endpoint', [$this, 'add_course_tab_content']);
        add_action('woocommerce_account_my-previous-purchases_endpoint', [$this, 'add_previous_content']);
    }

    public function add_course_menu_items( $items ) {


        $new_order = [
            "dashboard" => "Dashboard",
            "my-courses" => "My Courses",
            "subscriptions" => "My Subscriptions",
            "orders" => "Orders",
            //"downloads" => "Downloads",
            "edit-address" => "Addresses",
            "edit-account" => "Edit Account",
            "my-previous-purchases" => "Previous Purchases",
            "customer-logout" => "Logout",
        ];


        return $new_order;
    }

    public function add_course_tab_content() {
        wc_get_template_part( 'account/courses' );
    }

    public function add_previous_content() {
        wc_get_template_part( 'account/previous_purchases' );    
    }

}


new WC_SAM_CO_COURSES;


/* === Global Function s========== */ 
$added = false;
function check_order_for_subscription_keys( $woo_order, $echo = true ) {
    global $added;

    if( $added ) return;
    if( !$woo_order ) {
        return;
    }

    $products = [];

    if( is_object( $woo_order ) ) {
        $order = $woo_order;
    } else {
        $order = wc_get_order( $woo_order );
    }
    

    // -- Go through order items and see if any product is an algoritim - if so we need to flag what software needs keys supplied:
    foreach ($order->get_items() as $item_id => $item ) {
       // -- get the product
       $product = $item->get_product();
       // -- Is it an algoritihim
       if( get_post_meta( $product->get_id(), '_nb_needs_key', true) ) {
           $products[] = $product->get_name();
       }
       if( get_post_meta( $product->get_id(), '_nb_needs_attendance_date', true) ) {
        $reqiures_attendance_date = true;
        }
    }


  
  
    

    if( $echo ) {

        if( !is_account_page() ) {
            ?>
                <div class="aftersales-panel aftersales-panel--alt">
                    <header>
                        <h2><i class="fa-solid fa-user"></i> Your User Account</h2>
                    </header>
                    <p>If you do not already have a user account one has been created for you. You will receive an email with your login instructions so you can access your courses and subscriptions.</p>
                    </p>
                </div>
            <?php
        }

       
        if( $reqiures_attendance_date && $order->get_status() != "completed"  ) {
            ?>
            <div class="aftersales-panel aftersales-panel--alt">
                    <header>
                        <h2><i class="fa-solid fa-calendar"></i> Course Date Required</h2>
                    </header>
                    <p>We will be in touch very soon to book a convenient date for you to attend your course. We look forward to meeting you!</p>
                    </p>
                </div>
            <?php 
        }
   

        foreach( $products as $_product ) {
            if(  $order->get_status() != "completed" ) {
            ?>
                <div class="aftersales-panel">
                    <header>
                        <h2><i class="fa-solid fa-triangle-exclamation"></i> Trading Account Information Required</h2>
                    </header>
                    <p>As a part of your purchase, you are required to supply us with your Trading Account information and other details as below:</p>
                        <ul style="color:#fff">
                            <li>Order number (See Above)</li>
                            <li>Name</li>
                            <li>Email</li>
                            <li>Trading account number</li>
                            <li>MT4 or MT5</li>
                            <li>Name of broker</li>
                        </ul>
                    
                        <p><a href="mailto:sales@samuelandcotrading.com?subject=Trading Account Information For Order <?php echo $order->get_id(); ?>&body=Dear Samuel %26 Co Trading,%0D%0A%0D%0APlease find my trading account information below...%0D%0A%0D%0AName: [insert full name]%0D%0AEmail: [insert email]%0D%0ATrading account number: [insert account number]%0D%0AMT4 or MT5: [insert answer]%0D%0AName of broker: [insert full name of broker]%0D%0A%0D%0A">This link</a> will open a pre populated email to complete and send to us. Once we have this we will supply you with your Algorithm.</p>
                    </p>
                </div>
            <?php 
            }
        }
        $added = true;
    } else {
        return $products;
    }

    // Act-now fix (docs/LEGACY-CODE-REVIEW.md 0.6): this used to compile course access
    // as a side effect of *rendering* the thank-you / order-details page, with no
    // payment-status check — so opening a failed/pending order's page granted access,
    // and a paid order that never hit those pages never did. Gated on real payment
    // status now; see scm_grant_course_access_on_payment() below for the real hook.
    if( $echo && $order->has_status( [ 'processing', 'completed' ] ) ) {

        $compiler = new WC_SAM_CO_COURSE_COMPILER;
        $compiler->addByOrder( $woo_order );

    }



}
add_action('woo_needs_software_keys', 'check_order_for_subscription_keys', 100, 1);
add_action('woocommerce_order_details_before_order_table', 'check_order_for_subscription_keys', 10, 1);

/**
 * Grant course access on the actual payment-confirmed hooks, so it no longer
 * depends on the customer (or an admin) happening to view a particular page.
 * Act-now fix, docs/LEGACY-CODE-REVIEW.md 0.6.
 */
function scm_grant_course_access_on_payment( $order_id ) {
    $compiler = new WC_SAM_CO_COURSE_COMPILER;
    $compiler->addByOrder( $order_id );
}
add_action( 'woocommerce_order_status_processing', 'scm_grant_course_access_on_payment', 10, 1 );
add_action( 'woocommerce_order_status_completed', 'scm_grant_course_access_on_payment', 10, 1 );







/* ==== ADD VAT Field */


add_filter('woocommerce_billing_fields' , 'display_billing_vat_fields');
function display_billing_vat_fields($billing_fields){
    $billing_fields['billing_vat'] = array(
        'type' => 'text',
        'label' =>  __('VAT number',  'woocommerce' ),
        'class' => array('form-row-wide'),
        'required' => false,
        'clear' => true,
        'priority' => 35, // To change the field location increase or decrease this value
    );

    return $billing_fields;
}


// Printing the Billing Address on My Account
add_filter( 'woocommerce_my_account_my_address_formatted_address', 'custom_my_account_my_address_formatted_address', 10, 3 );
function custom_my_account_my_address_formatted_address( $fields, $customer_id, $type ) {

    if ( $type == 'billing' ) {
        $fields['vat'] = get_user_meta( $customer_id, 'billing_vat', true );
    }

    return $fields;
}

// Checkout -- Order Received (printed after having completed checkout)
add_filter( 'woocommerce_order_formatted_billing_address', 'custom_add_vat_formatted_billing_address', 10, 2 );
function custom_add_vat_formatted_billing_address( $fields, $order ) {
    $fields['vat'] = $order->get_meta('billing_vat');

    return $fields;
}

// Creating merger VAT variables for printing formatting
add_filter( 'woocommerce_formatted_address_replacements', 'custom_formatted_address_replacements', 10, 2 );
function custom_formatted_address_replacements( $replacements, $args  ) {
    $replacements['{vat}'] = ! empty($args['vat']) ? $args['vat'] : '';
    $replacements['{vat_upper}'] = ! empty($args['vat']) ? strtoupper($args['vat']) : '';

    return $replacements;
}

//Defining the Spanish formatting to print the address, including VAT.
add_filter( 'woocommerce_localisation_address_formats', 'custom_localisation_address_format' );
function custom_localisation_address_format( $formats ) {
    foreach($formats as $country => $string_address ) {
        $formats[$country] = str_replace('{company}\n', '{company}\n{vat_upper}\n', $string_address);
    }
    return $formats;
}


add_filter( 'woocommerce_customer_meta_fields', 'custom_customer_meta_fields' );
function custom_customer_meta_fields( $fields ) {
    $fields['billing']['fields']['billing_vat'] = array(
        'label'       => __( 'VAT number', 'woocommerce' )
    );

    return $fields;
}


add_filter( 'woocommerce_admin_billing_fields', 'custom_admin_billing_fields' );
function custom_admin_billing_fields( $fields ) {
    $fields['vat'] = array(
        'label' => __( 'VAT number', 'woocommerce' ),
        'show'  => true
    );

    return $fields;
}

add_filter( 'woocommerce_found_customer_details', 'custom_found_customer_details' );
function custom_found_customer_details( $customer_data ) {
    $customer_data['billing_vat'] = get_user_meta( $_POST['user_id'], 'billing_vat', true );

    return $customer_data;
}




add_filter( 'woocommerce_subscriptions_product_price_string', 'samco_custom_price_string', 20, 3 );
function samco_custom_price_string( $price_string, $product, $args ) {
    global $post;
    $page_id = get_queried_object_id();
    // Get the trial length to check if it's enabled
    $trial_length = get_post_meta( $product->get_id(), '_subscription_trial_length', true );
    $speriod = get_post_meta( $product->get_id(), '_subscription_period', true );
    $sfee = get_post_meta( $product->get_id(), '_subscription_sign_up_fee', true );
    $sfee = wc_price($sfee);
    $sign_up_fee = "";

    if( !is_singular( 'product' ) && get_page_template_slug( $page_id ) != "templates/template-service.php" ) {
        $sign_up_fee = isset($args['sign_up_fee']) ? __(" plus a one-off $sfee sign-up fee", "woocommerce") : '';
    }

    $price_string = $args['price'] . ' a ' . $speriod . $sign_up_fee;

    return $price_string;
}

/* === Redirect after add to cart fix */ 

//redirect to the same page
  /**
 * Set a custom add to cart URL to redirect to
 * @return string
 */
function custom_add_to_cart_redirect() { 

    $url = wp_get_referer();
    return $url;

  
}
add_filter( 'woocommerce_add_to_cart_redirect', 'custom_add_to_cart_redirect' );



// field_63c07eaaa5050

//add_action('acf/save_post', 'my_acf_save_post', 5);
function my_acf_save_post( $post_id ) {

    // Get newly saved values.
    $values = get_fields( $post_id );

    if( isset($values['modules']) ) {
      
        $c=0; foreach( $values['modules'] as $module ) {

            if( $module['module_lessons'] ) {

                $l=0; foreach($module['module_lessons'] as $lesson) {

                    

                    if( !$lesson['lesson_guid'] ) {
                        $values['modules'][$c]['module_lessons'][$l]['lesson_guid'] = uniqid();
                    }
    
                    $l++;
                }

            }


            $c++;
        }

    }

  
}


// -- GUID Field

add_filter('acf/update_value/key=field_63c07eaaa5050', 'my_acf_update_value', 10, 4);

add_filter('acf/update_value/key=field_63c0858d236d0', 'my_acf_update_value', 10, 4);

function my_acf_update_value( $value, $post_id, $field ) {

    if( !$value ) {
        return uniqid();
    } else {
        return $value;
    }
	//$value .= ' added by filter';
	//return $value;
}


// -  Podia and Shopify notices
add_action( 'woocommerce_login_form_start', 'sc_woocommerce_auth_page_header_action' );

/**
 * Function for `woocommerce_auth_page_header` action-hook.
 * 
 * @return void
 */
function sc_woocommerce_auth_page_header_action(){
    
    ?>
    <div class="auth-nag">
        <p><strong>Please Note:</strong> If you were previously registered with us on either Podia or Shopify platforms you will need to <a href="<?php echo wp_lostpassword_url(); ?>">reset your password</a>.</p>
    </div>
    <?php 
}




// -- Export Subscriptions
add_action( 'manage_posts_extra_tablenav', 'subs_export_button' );
function subs_export_button() {

    if( $_GET['post_type'] != "shop_subscription" ) return;

	?>
	

	<input type="submit" name="export_subs" class="button" value="Export Subscriptions">
	

	<?php
}


function sc_export_subscriptions() {


    if( !current_user_can( 'administrator' ) ) return;

    $data = [];

    if( isset($_GET['export_subs']) ) {

        $subscriptions = wcs_get_subscriptions(['subscriptions_per_page' => -1]);

        $data[0][0] = "Subscription ID";
        $data[0][1] = "Customer ID";
        $data[0][2] = "Customer Name";
        $data[0][3] = "Email";
        $data[0][4] = "Subscription Type";
        $data[0][5] = "Created";
        $data[0][6] = "Next Payment Date";
        $data[0][7] = "Status";

        $c=1; foreach($subscriptions as $subscription) {

            $sub_data = $subscription->get_data();


            // -- Get Line Items
            if(  $sub_data['line_items'] ) {
                foreach(  $sub_data['line_items'] as $subscription_product ) {
                    
                    $sub_product_data = $subscription_product->get_data();

                    $data[$c][0] = $sub_data['id']; 
                    $data[$c][1] = $sub_data['customer_id'];
                    $data[$c][2] = $sub_data['billing']['first_name'] . ' ' . $sub_data['billing']['last_name']; 
                    $data[$c][3] = $sub_data['billing']['email']; 
                    $data[$c][4] = $sub_product_data['name'];
                    $data[$c][6] = $sub_data['date_created']->date('d-m-Y H:i:s');
                    if(  $sub_data['schedule_next_payment'] ) {
                        $data[$c][5] = $sub_data['schedule_next_payment']->date('d-m-Y H:i:s'); 
                    }
                   
                    $data[$c][7] = $sub_data['status']; 
        
                    $c++;
                }
            }

          
           
        }


        if( $data ) {
            $filename = "export-subscriptions-" . time() . '.csv';

            header('Content-Type: application/csv');
            header('Content-Disposition: attachment; filename="'.$filename.'";');

            // open the "output" stream
            // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
            $f = fopen('php://output', 'w');

            foreach ($data as $line) {
                fputcsv($f, $line);
            }

            die();
        }

       
      

    }

}
add_action( 'admin_init', 'sc_export_subscriptions' );




add_filter( 'woocommerce_order_actions', 'bbloomer_show_thank_you_page_order_admin_actions', 9999, 2 );
 
function bbloomer_show_thank_you_page_order_admin_actions( $actions, $order ) {
   if ( $order->has_status( wc_get_is_paid_statuses() ) ) {
      $actions['view_thankyou'] = 'Display thank you page';
   }
   return $actions;
}
 
add_action( 'woocommerce_order_action_view_thankyou', 'bbloomer_redirect_thank_you_page_order_admin_actions' );
 
function bbloomer_redirect_thank_you_page_order_admin_actions( $order ) {
   $url = $order->get_checkout_order_received_url();
   add_filter( 'redirect_post_location', function() use ( $url ) {
      return $url;
   });
}





add_action('woocommerce_thankyou', 'add_email_to_cm_if_free_product', 10);

function add_email_to_cm_if_free_product($order_id) {
    $target_product_id = 568; // Replace with your free product ID

    // Act-now fix (docs/LEGACY-CODE-REVIEW.md 0.2): key + list id used to be hardcoded
    // here. Define both in wp-config.php (rotate the old exposed key first).
    if ( ! defined( 'CAMPAIGN_MONITOR_LIST_ID' ) || ! defined( 'CAMPAIGN_MONITOR_API_KEY' )
        || ! CAMPAIGN_MONITOR_LIST_ID || ! CAMPAIGN_MONITOR_API_KEY ) {
        return;
    }
    $list_id = CAMPAIGN_MONITOR_LIST_ID;
    $api_key = CAMPAIGN_MONITOR_API_KEY;

    $order = wc_get_order($order_id);
    if (!$order) return;

    $has_target_product = false;

    // Loop through order items to check for the product ID
    foreach ($order->get_items() as $item) {
        if ((int) $item->get_product_id() === $target_product_id) {
            $has_target_product = true;
            break;
        }
    }

    if (!$has_target_product) return;

    // Get customer's email
    $email = $order->get_billing_email();
    if (!$email) return;

    // Prepare API data
    $data = [
        'Name' => $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(),
        'EmailAddress' => $email,
        'Resubscribe' => true,
        'ConsentToTrack' => 'Yes',
    ];

    $url = "https://api.createsend.com/api/v3.3/subscribers/{$list_id}.json";

    $response = wp_remote_post($url, [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($api_key . ':x'),
            'Content-Type' => 'application/json'
        ],
        'body' => json_encode($data)
    ]);

    // Failures land in the normal PHP error log without dumping the order/PII.
    if (is_wp_error($response)) {
        error_log('Campaign Monitor subscribe failed for order ' . $order_id . ': ' . $response->get_error_message());
    } elseif ( (int) wp_remote_retrieve_response_code($response) >= 300 ) {
        error_log('Campaign Monitor subscribe failed for order ' . $order_id . ' with HTTP ' . wp_remote_retrieve_response_code($response));
    }
}




