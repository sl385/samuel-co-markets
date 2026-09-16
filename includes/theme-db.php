<?php 



// -- DB Work 

if( $_GET['xyz'] == "test" ) {
    


    // -- Free course Product ID

    $free_product_id = 1550;  // imported product
    

    // -- get all imported users from Podia
    //A lot of arguments can be passed
    //Here we get all users with the "developer" occupation
    $args  = array(
        'meta_key' => 'imported_platform', //any custom field name
        'meta_value' => 'podia', //the value to compare against
        'posts_per_page' => -1
    );

    $users_array = [];
    $delete_user_for_this_course = [];
    
    $user_query = new WP_User_Query( $args );
    if ( ! empty( $user_query->get_results() ) ) {
        foreach ( $user_query->get_results() as $user ) {

            //echo "<li>Checking " . $user->ID . " - " . $user->user_email . " - was imported from Podia and has access to Free Course";

            // -- loop through PODIA Imported users and check if they have access to the free product ID ( Imported )
            $result = $wpdb->get_row("SELECT * FROM " .  $wpdb->prefix . "user_courses WHERE user_id=" . $user->ID . " AND product_id=" . $free_product_id);
            if( $result ) {

                // -- Does this user have multiple courses
                $courses = $wpdb->get_results("SELECT * FROM " .  $wpdb->prefix . "user_courses WHERE user_id=" . $user->ID);
 
                if( sizeof($courses) > 1 ) {
                    echo "<li style='color:orange'>" . $user->ID . " - " . $user->user_email . " - Imported from Podia with access to the Free Trader Course and other courses";
                    $delete_user_for_this_course[] = $user->ID;
                } else {
                     // -- Let me know and 
                    echo "<li style='color:green'>" . $user->ID . " - " . $user->user_email . " - Imported from Podia with access to the Free Trader Course";
                    // -- Add to a users array
                    $users_array[] = $user->ID;
                }

               
            } else {
                echo "<li style='color:red'>" . $user->ID . " - " . $user->user_email . " No access";
            }
           
            
        }
    }

    if( $users_array ) {
        foreach( $users_array as $_user ) {
            // -- loop through each of these users and then remove them from Wordpress and remove them from user_courses
            // wp_delete_user(  $_user->ID );
            // $wpdb->query( "DELETE FROM " .  $wpdb->prefix . "user_courses WHERE user_id = ' . $_user->ID . " AND product_id = " . $free_product_id);
        }
    }

    if(  $delete_user_for_this_course ) {
        foreach( $delete_user_for_this_course as $_user ) {
            // -- Loop through and remove their entry from from user_courses for this course
            //$wpdb->query( "DELETE FROM  " .  $wpdb->prefix . "user_courses WHERE user_id = ' . $_user->ID . " AND product_id = " . $free_product_id);
        }
    }

    die();

}