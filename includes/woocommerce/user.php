<?php 

/** Redundant Class */
Class WC_SAM_CO_USER {

    var $user;
    var $user_courses = [];

    public function __construct( $id = null ) {

        if( $id ) {
            $this->user = get_user_by( 'id', $id );
        } else {
            $this->user = wp_get_current_user();
        }

    }

    /* 
    * Method will likely change in favour of meta against the user rather than looping through the courses 
    */
    public function getUserCourses() {

        // -- Get the customer
        $args = array(
            'customer_id' => $this->user->data->ID,
            'limit' => -1, // to retrieve _all_ orders by this user
        );

        // -- Course is a virtual product
        $orders = wc_get_orders($args);

        if( $orders ) {
            foreach( $orders as $order ) {
                $course = $this->get_course( $order );
            }
        }

        return $this->user_courses;

    }

    public function userCanAccessCourse( $course_id ) {

        if( current_user_can('adminstrator') ) {
            return true;
        }
        
        $courses = $this->getUserCourses();
        return in_array( $course_id, $this->user_courses );

    }

    public function get_course( $order ) {

        if( $order->get_status() == "completed" || $order->get_status() == "processing"  ) {

            foreach ($order->get_items() as $item_id => $item ) {
                // -- Lets get the product
                $product = $item->get_product();
                // -- Is it a virtual Product?
                $this->get_product_courses( $product );
            }

        }

       
    }

    public function get_product_courses( $product ) {
        // -- get courses associated with this product
        $courses = get_post_meta( $product->get_id(), '_sc_courses', true );
        if( $courses ) {
            foreach( $courses as $course_id ) {
                if(!in_array( $course_id, $this->user_courses) ) {
                    $this->user_courses[] = $course_id;
                }
            } 
        }
    }

    


}


// --- Admin Users
add_filter( 'manage_users_columns', 'register_favorite_cms_column' );
function register_favorite_cms_column( $columns ) {

    unset($columns['posts']);
    unset($columns['role']);
    unset($columns['wfls_2fa_status']);
    unset($columns['name']);

    $columns['courses'] = 'Courses';
	$columns['customer_type'] = 'Customer Type';
	return $columns;
}

add_filter( 'manage_users_custom_column', 'render_users_favorite_cms', 10, 3 );
function render_users_favorite_cms( $output, $column_name, $user_id ) {
	if ( 'customer_type' === $column_name ) {
		// Don't forget to escape your output
		$output = "<div class='platform platform--" . get_user_meta( $user_id, 'imported_platform', true ) . "'>" . esc_html( get_user_meta( $user_id, 'imported_platform', true ) ) . "</div>";
	}

    if ( 'courses' === $column_name ) {

        $user_courses = new User_Course_Manager;
        $_user_courses = $user_courses->get_user_courses( $user_id, true );
        

		// Don't forget to escape your output
		$output = "<div style='text-align:center'>" . sizeof( $_user_courses) . "</div>";
	}

	return $output;
}

add_action( 'manage_users_extra_tablenav', 'render_custom_filter_options' );
function render_custom_filter_options() {
	?>
	
	<form method="GET">
		<select name="customer_type">
			<option value="0">Search By Platform</option>
			<option value="podia">Podia</option>
            <option value="shopify">Shopify</option>
           <!-- <option value="zoho">Zoho</option>
            <option value="gocardless">GoCardless</option> -->
		</select>	

		<input type="submit" class="button" value="Filter">
	</form>

	<?php
}

add_action( 'pre_get_users', 'filter_users_by_favorite_cms', 99, 1 );
function filter_users_by_favorite_cms( $query ) {
	// This condition allows us to make sure that we won't modify any query that came from the frontend
	if ( ! is_admin() ) {
		return;
	}
	
	global $pagenow;
	
	// This condition allows us to make sure that we're modifying a query that fires on the wp-admin/users.php page
	if ( 'users.php' === $pagenow ) {
		
		// Let's check if our filter has been used
		if ( isset( $_GET['customer_type'] ) && $_GET['customer_type'] !== '0' ) {
			$meta_query = array(
				array(
					'key' => 'imported_platform',
					'value' => $_GET['customer_type'],
					'compare' => '='
				)
			);
			
			$query->set( 'meta_query', $meta_query );
		}
	}

	return;
}

add_action('admin_head', 'my_custom_fonts'); // admin_head is a hook my_custom_fonts is a function we are adding it to the hook

function my_custom_fonts() {
  echo '<style>
    [data-colname="Customer Type"] .platform {
        display: block;
       
        width: 20px;
        height: 15px;
        background-size: contain;
        border-radius: 15px;  
        background-position: 50% 50%; 
        text-indent: -10000em;
        background-repeat: no-repeat;
        padding: 5px 20px;
        border: 1px solid #ccc;
    }

    .platform {
        background-image: url("https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/WooCommerce_logo.svg/1200px-WooCommerce_logo.svg.png");
    }

    .platform--shopify {
        background-image: url("https://cdn.shopify.com/assets/images/logos/shopify-bag.png"); 
    }
    
    .platform--podia {
        background-image: url("https://logovtor.com/wp-content/uploads/2020/11/podia-labs-inc-logo-vector.png");
    }

    .platform--gocardless {
        background-image: url("https://upload.wikimedia.org/wikipedia/en/thumb/c/cb/Gocardless-blue.svg/2560px-Gocardless-blue.svg.png");
    }

    .platform--zoho {
        background-image: url("https://www.zoho.com/branding/images/zoho-logo-512px.png");
    }
  </style>';
}


add_action( 'edit_user_profile', 'course_custom_user_profile_fields' );
function course_custom_user_profile_fields( $user ) {

    $user_courses = new User_Course_Manager;
    $_user_courses = $user_courses->get_user_courses( $user->ID, true );
    $purchases = SCO_Helpers::getUserPreviousPurchases( $user->ID ); 
    

   
    
    ?>

    <h3 class="heading">User Courses</h3>

    <?php if( $_user_courses ) : ?>
        <p>This user has purchased and has access to the following courses. Please note if a course does not show then they may have been revoked access from the product.</p>
        <ul style="list-style:disc;margin-left:1em;margin-bottom:2em;padding:2em;background: #fff">
        <?php foreach( $_user_courses as $course ) : ?>
            <li><?php echo $course->post_title; ?></li>
        <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>User has not purchased any courses</p>
    <?php endif; ?>
    
    <?php if( $purchases ) : ?>

        <h3 class="heading">Shopify Purchases</h3>

        <?php if( $purchases ) : ?>
        <?php foreach( $purchases as $purchase ) : ?>
            <?php $order_details = json_decode( $purchase->order_json ); ?>
            <h4>Order Number #<?php echo $purchase->platform_order_id; ?> - <?php echo date('d F Y', strtotime($order_details->ordered_at)); ?> </h4>
        
            <?php if( $order_details ) : ?>
                <?php if( $order_details->line_items ) : ?>
                    <table class="table">
                        <tr>
                            <th>Product</th>
                            <th style="width:130px">Price</th>
                            <th style="width:130px">Qty</th>
                        </tr>
                    <?php foreach( $order_details->line_items as $item ) : ?>
                        <tr>
                            <td><?php echo $item->product; ?></td>
                            <td style="width:130px"><?php echo $item->qty; ?></td>
                            <td style="width:130px"><?php echo $item->price; ?></td>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <td></td>
                            <th class="total-col" >Total</th>
                            <td><?php echo $order_details->total; ?></td>
                            
                        </tr>
                    </table>
                <?php endif; ?>
            <?php endif; ?>
        
        <?php endforeach; ?>

        <style>
            .table {
                width: 100%;
            }
            .table th {
                background: #fff;
                text-align: left;
                padding: 10px;
            }
            .table td {
               padding: 10px;
               background: #fff;
            }
        </style>

    <?php else : ?>
        <p>We have no historical purchase history on Shopify for you.</p>
    <?php endif; ?>

    <?php endif; ?>
   
    
    <?php
}