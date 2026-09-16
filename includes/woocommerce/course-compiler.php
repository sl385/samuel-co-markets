<?php 

Class WC_SAM_CO_COURSE_COMPILER {

    public function __construct() {
        $this->init();
        //$this->compile();
        
    }

    public function compile() {
        add_action('woocommerce_after_register_post_type', [$this,'compiler']);
    }

    public function init() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        $table_name = $wpdb->prefix . 'user_courses';

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id mediumint(9) NOT NULL,
            course_id mediumint(9) NOT NULL,
            order_id mediumint(9) NOT NULL,
            suspend  mediumint(1) NOT NULL,
            PRIMARY KEY  (id)
          ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }   

    public function compiler() {
        $orders = wc_get_orders( array('numberposts' => -1) );
      
        foreach( $orders as $order ) {
           
            if( $order->get_status() == "completed" || $order->get_status() == "processing"  ) {
                // -- loop through all orders complete or processing
                foreach ($order->get_items() as $item_id => $item ) {
                    // -- Lets get the product
                    $product = $item->get_product();
                    // -- Check to see if the product has courses
                    $courses = $this->get_product_courses( $product, $order->get_id(), $order->get_user_id() );
                    // -- if it does then we add the product ID, Order ID and User ID to the table
                    if( $courses ) {
                        $this->addToTable( $product->get_id(), $order->get_id(),  $order->get_user_id() );
                    }
                }
    
            }
        }
        die("End compile");
    }

    public function get_product_courses( $product, $order_id, $user_id ) {
        // -- get courses associated with this product
        /*$courses = get_post_meta( $product->get_id(), '_sc_courses', true );
        if( $courses ) {
            foreach( $courses as $course_id ) {
                $this->addToTable( $course_id, $order_id, $user_id );
            } 
        }*/
        return  get_post_meta( $product->get_id(), '_sc_courses', true );
    }

    public function getOrdersByDay( $date = "" ) {
        // https://stackoverflow.com/questions/31672200/how-to-get-all-order-details-in-wordpress-woocommerce
    }

    public function getAllOrders() {
        return wc_get_orders( array('numberposts' => -1) );
    }

    public function addToTable( $product_id, $order_id, $user_id ) {

        global $wpdb;
        // check to see if user is already in the database
        $result = $wpdb->get_row("SELECT * FROM " .  $wpdb->prefix . 'user_courses' . " WHERE user_id=" . $user_id . " AND product_id=" . $product_id);
        // if not we need to add them
        if( !$result ) {
            $wpdb->insert( 
                $wpdb->prefix . 'user_courses', 
                array( 
                    'product_id' => $product_id,
                    'user_id' => $user_id, 
                    'order_id' => $order_id, 
                ) 
            );
        } 

    }

    public function user_has_access( $course_id, $user_id ) {
        global $wpdb;

        if( current_user_can( 'administrator' ) ) {
            return true;
        }

        $result = $wpdb->get_row("SELECT * FROM " .  $wpdb->prefix . 'user_courses' . " WHERE user_id=" . $user_id . " AND course_id=" . $course_id);
        if( $result ) {
            return true;
        } else {
            return false;
        }
    }

    public function addByOrder( $woo_order ) {
        $order = wc_get_order( $woo_order );
        foreach ($order->get_items() as $item_id => $item ) {
            // -- get the product
            $product = $item->get_product();
            // -- Is it an algoritihim
            $courses = $this->get_product_courses( $product, $order->get_id(), $order->get_user_id() );
            // -- if it does then we add the product ID, Order ID and User ID to the table
            if( $courses ) {
                $this->addToTable( $product->get_id(), $order->get_id(), $order->get_user_id() );
            }
         }
    }

    public function getUserCoursesForSubscriptions() {
        // -- check user subscriptions 

        // -- apply courses to the user array
    }

    public function getAllSubscriptionCourses() {
        // -- get all subscription products

        // -- get all of the courses for these products

        // -- remove them from the array of courses
    }

}

$compiler = new WC_SAM_CO_COURSE_COMPILER;

/* === Notes 

Need to sort this class out

Needs a method of get all user courses:
This method does the following

1. Queries the database for a user and all products purchased with courses
2. From that array we then go through each one and then get associated courses
2a. As a part of this process we need to check if a product is a subscription. If it is we then need to see if the user has that subscription active.
3. This array can then be queried to see if a user can access a course.


*/


Class User_Course_Manager {

    var $_user_courses = [];
    var $hide_duplicates = false;

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        
    }

    public function get_users_for_course( $course_id ) {
        return $this->wpdb->get_results("SELECT * FROM " .  $this->wpdb->prefix . "user_courses WHERE product_id=" . $course_id);
    }


    public function add_user_to_course( $course_id, $user_id ) {
        $this->wpdb->insert( 
            $this->wpdb->prefix . 'user_courses', 
            array( 
                'product_id' => $course_id,
                'user_id' => $user_id, 
                'order_id' => "999999999", 
            ) 
        );
    }

    

   

    public function get_user_courses( $user_id, $return_courses = true ) {

        // -- get the products purchased for a user
        $results = $this->wpdb->get_results("SELECT * FROM " .  $this->wpdb->prefix . 'user_courses' . " WHERE user_id=" . $user_id . " AND suspend = 0");

        if( !$return_courses ) {
            return $results;
        }

        // -- Lets get a list of subscriptions for this user
        $user_subscriptions = wcs_get_users_subscriptions( $user_id );

        // -- create an empty course array
        $courses = [];

        // -- loop through the products
        if( $results ) {

            foreach( $results as $result ) {
                // -- check it has courses
                $product_courses = get_post_meta( $result->product_id, '_sc_courses', true );

                // -- check subscription status
                $wc_product = wc_get_product( $result->product_id );
                if( $wc_product ) { 
                    if( $wc_product->is_type('subscription') ) {
                        // -- Check this user has this subscription active

                        foreach($user_subscriptions as $subscription){
                        
                            // -- get subscription data
                            $subscription_data = $subscription->get_data();

                            // -- Is subscription Actice [ To Do ]
                            if( $subscription_data['status'] == "active") {
                                // -- check the line items
                                if( $subscription_data['line_items'] ) {
                                    // -- check if a subscription belongs to this product
                                    foreach( $subscription_data['line_items'] as $sub_details ) {
                                        if( $sub_details->get_product_id() == $wc_product->get_id() ) {
                                            // -- if it does then get its details
                                            foreach( $product_courses as $course ) {
                                                $courses[] = get_post( $course );
                                            }
                                        }
                                    }
                                }
                            }

                        }

                    } else {
                        if( $product_courses ) {
                            // -- if it does then get its details
                            foreach( $product_courses as $course ) {
                                $courses[] = get_post( $course );
                            }
                        }
                    }
                }

            }
        }


        if( $this->hide_duplicates ) {
            $_courses_new = [];

      

            foreach( $courses as $course ) {

                $match = false;

                if( $_courses_new ) {
                    foreach( $_courses_new as $_course ) {
                        if( $_course->ID == $course->ID ) {
                            $match = true;
                        }
                    }
                } 

               

                if( !$match ) {
                    $_courses_new[] = $course;
                }
            }

            $courses = $_courses_new;
        }

        return $courses;

    }

    public function user_can_access_course( $user_id, $course_id ) {

        if( current_user_can('administrator') ) {
            return true;
        }


        // -- cache it
        if( !isset($this->_user_courses[ $user_id ]) ) {
            $this->_user_courses[ $user_id ] = $this->get_user_courses( $user_id );
        }

        if( !$this->_user_courses[ $user_id ] ) {
            return false;
        }

        foreach( $this->_user_courses[ $user_id ] as $course ) {
            if( $course->ID == $course_id ) {
                return true;
            }
        }

   

        return false;

        //return $this->wpdb->get_row("SELECT * FROM " .  $this->wpdb->prefix . 'user_courses' . " WHERE user_id=" . $user_id . " AND product_id=" . $course_id . " AND suspend=0");
    }

    /* === Admin function to revoke user */

    public function update_status( $status, $user_id, $course_id ) {


        $this->wpdb->update($this->wpdb->prefix . "user_courses" , ["suspend"=>$status], ["user_id"=>$user_id,"product_id"=>$course_id]);

        /*
        $this->wpdb->query( 
            "DELETE FROM " . $this->wpdb->prefix . "user_courses WHERE user_id = " . $user_id . " AND course_id= " . $course_id
        );
        */

    }

    /* === Admin function to remove user  */

    public function remove_user_to_course( $course_id, $user_id ) {

        $this->wpdb->query( 
            "DELETE FROM " . $this->wpdb->prefix . "user_courses WHERE user_id = " . $user_id . " AND product_id= " . $course_id
        );

    }



    public function get_user_course_progress( $course_id, $user_id ) {

        global $wpdb;

        $user_progress = [];

        $results = $wpdb->get_results("SELECT * FROM " .  $wpdb->prefix . 'user_lesson_progress' . " WHERE user_id = " . $user_id  . " AND course_id = " . $course_id . " ORDER BY module_id, lesson");

        if( $results ) {

            foreach( $results as $result ) {
    
                $user_progress[$result->module_id][$result->lesson] = $result->progress;
    
            }
    
        } 

        return $user_progress;


    }

}