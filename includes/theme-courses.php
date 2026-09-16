<?php 

// media/course/-/asset/x123

add_filter( 'query_vars', 'addnew_query_vars', 10, 1 );
function addnew_query_vars($vars) {   
    $vars[] = 'key'; // c is the name of variable you want to add     
    return $vars;
}



function custom_rewrite_basic() {
    add_rewrite_rule('^-/media/courses/asset/([^/]+)/load/?', get_stylesheet_directory_uri( ) . '/lib/asset-load.php?key=$matches[1]', 'top');
}
add_action('init', 'custom_rewrite_basic');


function fetch_course_asset() {


    // https://stackoverflow.com/questions/9756837/prevent-html5-video-from-being-downloaded-right-click-saved

    global $wpdb;

    if( !is_user_logged_in( ) ) return;

    $key = get_query_var( 'key' );
    //$key = "633b0136d7545_633b0136d7548";
    if( !$key ) return;

    $asset = $wpdb->get_results("SELECT * FROM " . $wpdb->posts . " WHERE guid LIKE '%" . $key . "%'");
    if( $asset ) {
        $attachment = get_attached_file($asset[0]->ID);
        $meta = wp_get_attachment_metadata($asset[0]->ID);
        header('X-Sendfile: ' . $video_file);
        header('Content-Type: video/mp4');
        header('Content-Length: '.$meta['filesize']);
        header("Expires: -1");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        readfile($attachment);
        exit;
    }
   

}
//add_action('wp', 'fetch_course_asset');


function add_course_progress_vars() {



    // -- This is no defunked - we will now have to get the course and query the users progress the first video they have not watched in order is loaded



    /*

    if( is_singular('course') ) {
        global $post;
        $module = 1;
        $lesson = 1;
        if( is_singular('course') ) {
            $user_progress = SCO_Helpers::getCourseProgress( $post->ID );
        }
        ?>
        <script>
            var my_module=<?php echo $user_progress['module']; ?>; 
            var my_lesson=<?php echo $user_progress['lesson']; ?>; 
            var lesson_guid='<?php echo $user_progress['guid']; ?>';  
        </script>
        <?php 
    }


    */

    if( is_singular('course') ) {
        global $post;
        $module = 1;
        $lesson = 1;
        // -- lets get this users course progression
        $course_manager = new User_Course_Manager;
        $progress = $course_manager->get_user_course_progress( $post->ID, get_current_user_id() );
       
        // -- get course modules
        $modules = get_field('modules', $post->ID);
        $m=1;
        foreach( $modules as $module ) {

     

            

            if( $module['module_lessons'] ) {

                $l=1; foreach( $module['module_lessons'] as $lesson ) {

                  
                  
                    if( !user_has_completed_lesson( $m, $l, false, $progress ) ) {
                    
                        ?>
                            <script>

                                // Next video <?php echo $m; ?> - <?php echo $l; ?>

                                var my_module=<?php echo ($m); ?>; 
                                var my_lesson=<?php echo ($l); ?>; 
                                var lesson_guid='<?php echo $lesson['lesson_guid']; ?>';  
                            </script>
                        <?php 
                        exit;
                    }

                    $l++;
                }

             
            }

            $m++;
        }


    }

    
}


add_action('wp_footer', 'add_course_progress_vars');



add_action( 'admin_menu', function() {

    add_submenu_page(
        'edit.php?post_type=course',
        __( 'User Progression', 'menu-test' ),
        __( 'User Progression', 'menu-test' ),
        'manage_options',
        '_sco_course_progression',
        '_sco_course_progression'
    );


    add_submenu_page(
        'edit.php?post_type=course',
        __( 'Course Media Location', 'menu-test' ),
        __( 'Course Media Location', 'menu-test' ),
        'manage_options',
        '_sco_course_media_location',
        '_sco_course_media_location'
    );
});

function _sco_course_progression() {

    $courses = getAllPurchasedCourses();

    if( isset($_POST['recompile'] ) ) {

        if( $_POST['recomp_action'] == "d9udod9" ) {
            $compiler = new WC_SAM_CO_COURSE_COMPILER;
            $compiler->compiler();
        
        }
    }

?>

    <?php if( $courses ) : ?>

        <h2>Course Progression</h2>

        <form method="post" onsubmit="return confirm('Are you sure you want to do this?')">
            <input type="submit" name="recompile" value="Recompile Courses"  />
            <input type="hidden" name="recomp_action" value="d9udod9"  />
        </form>


        <?php  foreach( $courses as $k => $v ) : ?>



           
            <h2><?php echo get_the_title($k);?></h2>

            <table class="table">
             <tr>
                <th>Customer</th>
                <th>Current Progression</th>
                <th></th>
            </tr>
    
            <?php foreach($courses[$k] as $user_progress ) : ?>
                <tr>
                    <td><?php echo $user_progress['details']['first_name']; ?> <?php echo $user_progress['details']['last_name']; ?></td>
                    <td>
                        <?php //$progess = SCO_Helpers::getCourseProgress( $k, $user_progress['details']['user_id']); ?>
                        <?php //$course_data = get_field('modules', $k);  ?>
                       

                    </td>
                    <td>
                        <button class="view_progression_data_toggle" data-user="<?php echo $user_progress['details']['user_id']; ?>" data-course="<?php echo $k; ?>">View Lesson Data</button>
                    </td>
                </tr>
                <tr class="course_progression_data"></tr>
            <?php endforeach; ?>
            </table>

        <?php endforeach; ?>

   
       

    <?php endif; ?>

    <style>
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 10px;
            text-align: center;
        }

        .table td  {
            border-bottom: 1px solid #eee;
            background: #fff;
        }

        .table th {
            background: #000;
            color: #fff;
        }
        
        .table--sub {
           
           
        }

        .status-lesson-box {
            max-height: 300px;
            overflow: auto;
            max-width: 95%;
            margin: 2em auto;
            border: 1px solid #000;
            box-shadow: 0px 5px 5px rgba(0,0,0,.3);
            border-radius: 5px;
        }

        .table--sub td {
            background: #fff;
            color: #333;
        }

        .table--sub th,
        .table--sub td {
            padding: 10px;
            text-align: left;
        }

        .table--sub th {
            background: #eee;
            color: #333;
        }


        .table th.table-sub {
            background: #333;
            padding: 10px;
            color: #fff;
        }

        .usx-progress {
            display: inline-block;
            padding: 5px;
            border-radius: 5px;
            color: #fff;
        }

        .usx-progress--complete {
            background: green;
        }

        .usx-progress--pending {
            background: orange;
        }

        .usx-progress--no {
            background: red;
        }

    </style>
<?php 
}


function course_admin_enqueue($hook) {

    // Only add to the edit.php admin page.
    // See WP docs.
    if ('course_page__sco_course_progression' !== $hook) {
      
        return;
    }
    wp_enqueue_script('course_admin_js', get_stylesheet_directory_uri() . '/assets/js/course-admin.js');
}

add_action('admin_enqueue_scripts', 'course_admin_enqueue');



function getAllPurchasedCourses( ) {

    global $wpdb;

    $user_courses = [];

    $exclude_products = [1550,568];

    $product_to_users = [];

    
    $args = array(
       'posts_per_page' => '-1',
       'post_status' => ['processing','completed'],
       'type' => 'shop_order'
    );
    $orders = wc_get_orders($args);
    $c=0;
    foreach( $orders as $order ) {   
    
        foreach ($order->get_items() as $item_id => $item ) {
            $user = $order->get_user();
            // -- Lets get the product
            $product = $item->get_product();
            // -- Is it a virtual Product?
            //$course_id = get_product_courses( $product );
            if( !in_array($product->get_id(), $exclude_products) ) {

                $product_to_users[$product->get_id()][] = $user->ID;

                $courses_for_product = get_post_meta( $product->get_id(), '_sc_courses', true );
                if( $courses_for_product ) {
                    foreach( $courses_for_product as $course_for_product ) {
                        $user_courses[$course_for_product][$c]['details'] = [
                            "first_name" => $order->get_billing_first_name(),
                            "last_name" => $order->get_billing_last_name(),
                            "user_id" => $user->ID
                        ];
                        $c++;
                    }
                }  

            }
            
        }
    }

    // -- Get All Access to courses from database table
    $users_for_course = $wpdb->get_results("SELECT * FROM " .  $wpdb->prefix . "user_courses WHERE product_id NOT IN (1550,568)");
    if( $users_for_course ) {
        foreach( $users_for_course as $course_access ) {

            if( !in_array( $course_access->product_id, $product_to_users ) ) {

                $courses_for_product = get_post_meta( $course_access->product_id, '_sc_courses', true );

                // -- get user meta
                if( $courses_for_product ) {
                    $data = get_userdata( $course_access->user_id );

                    $product_to_users[$course_access->product_id][] = $course_access->user_id;

                    foreach( $courses_for_product as $course_for_product ) {

                        $last_name = "";
                        $first_name = $data->first_name;
                        $last_name = $data->last_name . ' ( ' . $data->user_email . ' )';
                    
                        $user_courses[$course_for_product][]['details'] = [
                            "first_name" => $first_name,
                            "last_name" =>  $last_name,
                            "user_id" => $course_access->user_id
                        ];
                    }
                }


            }


        }
    }


    return $user_courses;

}


function get_product_courses( $product ) {
    $courses = get_post_meta( $product->get_id(), '_sc_courses', true );
    if( $courses ) {
        foreach( $courses as $course_id ) {
            return $course_id;
        } 
    }
}


/* ==== Admin - Course Asset Locations */ 

function move_video_to_secure_video( $attachment_id ) {

     // -- Load The Asset
     $asset = get_post( $attachment_id );
     // -- Dump META [ DEV ]
     //print_r( get_post_meta($asset->ID) );
     // -- Do we have an Asset
     if( !$asset ) return;
     // -- No we need to grab the path of the asset
     $asset_file = get_attached_file( $asset->ID );
     // -- Lets get the files name to append to our course_assets path
     $filename = basename ( $asset_file );
     // -- Where does it need to go
     $upload_dir = wp_upload_dir();
     $destination_dir = $upload_dir['basedir'] . '/course_assets/';
     // -- Lets copy the file across because that makes sense
     $move = rename( $asset_file, $destination_dir . $filename );
     // -- If we moved it we need to update the post meta
     if( $move ) {
         // -- we need to change the GUID
         update_attached_file( $asset->ID, 'course_assets/' . $filename );
     } else {
         "Failed to move file";
     }

}

function _sco_course_media_location() {

    if( !current_user_can( 'administrator' ) ) {
        return;
    }

    if( isset($_GET['asset_id']) ) {

        move_video_to_secure_video( $_GET['asset_id'] );

    }

    if( isset($_GET['move_all_assets']) ) {


        $courses = get_posts('post_type=course&posts_per_page=-1');
        foreach( $courses as $course ) {
            $modules = get_field( 'modules', $course->ID );
            if( $modules ) {
                foreach( $modules as $module ) {
                    $lessons = $module['module_lessons'];
                    foreach( $lessons as $lesson ) {
                        // -- if we have a video asset attached 
                        if( isset($lesson['lesson_video_asset'])) {
                            if( isset($lesson['lesson_video_asset']['ID']) ) {
                                $media = get_post( $lesson['lesson_video_asset']['ID'] );
                                $path = get_attached_file( $lesson['lesson_video_asset']['ID'] );
                                $pos = strpos($path, 'course_assets');
                                if( $pos === false ) {

                                    move_video_to_secure_video(  $lesson['lesson_video_asset']['ID'] );
                                    
                                } 
                            }
                        }
                    }
                }
            }
        }


    }

    $courses = get_posts('post_type=course&posts_per_page=-1');

    ?>
    <header style="display:flex;justify-content:space-between; align-items:center;">
        <h2>Media Video Location</h2>
        <a class="button" onclick="return confirm('Are you sure you want to action this - we reccomend doing one at a time')" href="<?php echo admin_url('edit.php?post_type=course&page=_sco_course_media_location'); ?>&move_all_assets=1">Move All Videos</a>
    </header>
    <table class="table" style="width:100%">
    <?php foreach( $courses as $course ) : ?>
        <div class="course-block">
            <tr>
                <th colspan="4" class="mainth"><?php echo $course->post_title; ?></th>
            </tr>
       
        <?php $single = get_field('show_as_single_list_of_videos', $course->ID); ?>
        <?php $modules = get_field( 'modules', $course->ID ); ?>
        <?php foreach( $modules as $module ) : ?>
            <?php if( !$single ) : ?>
            <tr>
                <th colspan="4"><?php echo $module['module_name']; ?></th>
            </tr>
            <?php endif; ?>
            <?php $lessons = $module['module_lessons']; ?>
            <?php foreach( $lessons as $lesson ) :

            ?>
            <tr>
                <td><?php echo $lesson['lesson_name']; ?></td>
                <td>
                    <?php if( $lesson['video_upload_type'] == 1 || ! $lesson['video_upload_type'] ) : ?>
                        Upload
                    <?php else: ?>
                        Third Party
                    <?php endif; ?>
                </td>
                
                <?php if(  $lesson['video_upload_type'] == 1 || ! $lesson['video_upload_type'] ) : ?>
                <td>
                    <?php 
                        $pos = true;
                        $media = false;
                        if( isset($lesson['lesson_video_asset'])) {
                            if( isset($lesson['lesson_video_asset']['ID']) ) {
                            $media = get_post( $lesson['lesson_video_asset']['ID'] );
                            $path = get_attached_file( $lesson['lesson_video_asset']['ID'] );
                            $pos = strpos($path, 'course_assets'); 
                            }
                        }
                      
                    ?>

                    <?php if( $media ) : ?>

                        <?php if( !file_exists($path) ) : ?>
                            <span style="background:red;color:#fff;display:inline-block;padding:2px 10px;margin-right:5px;font-size:10px;border-radius:15px;">File Missing</span>
                        <?php endif; ?>


                        <?php if ($pos === false) : ?>
                            <span style="color:red"><?php echo $path; ?></span>
                        <?php else: ?>
                            <span style="color:green"><?php echo $path; ?></span>
                        <?php endif; ?>

                      
                    <?php else : ?>
                         No Video Uploaded
                    <?php endif; ?>

                </td>
                <td>
                    <?php if ($pos === false) : ?>
                        <a href="<?php echo admin_url('edit.php?post_type=course&page=_sco_course_media_location'); ?>&asset_id=<?php echo $lesson['lesson_video_asset']['ID']; ?>">Move Video To Secure Folder</a>
                    <?php endif; ?>
                </td>
                <?php else : ?>
                    <td><?php echo $lesson['external_video_url']; ?></td>
                    <td></td>
                <?php endif; ?>




                
            </tr>
            <?php endforeach; ?>
        <?php endforeach; ?>
                
        </div>
    <?php endforeach; ?>
    </table>

    <style>
        .table {
            border-collapse: separate;
            width: 100%;
        }
        .table th {
            background: #666;
            color: #fff;
            padding: 10px;
        }
        .table th.mainth {
            background: #ddd;
            text-align: left;
            color: #333;
            font-size: 20px;
        }
        .table tr td {
            padding: 5px;
            background: #fff;
            border: none;
        }
    </style>

<?php 
}




/**
* Register Metabox
*/
function prefix_add_meta_boxes(){
	add_meta_box( 'unique_mb_id', __( 'Customers','text-domain' ),'prefix_mb_callback', 'product', 'side', 'high' );
}
add_action('add_meta_boxes', 'prefix_add_meta_boxes' );
	
/**
* Meta field callback function
*/

add_action( 'save_post', 'save_cd_meta_box_data' );
function save_cd_meta_box_data( $post_id ) {

    

    // Autosaving, bail.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if( !current_user_can( 'administrator' ) ) {
        return;
    }

    // @TODO
    // You should add some additional security checks here
    // eg. nonce, user capabilities, etc, to prevent
    // malicious users from doing bad stuff.

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if ( ! isset( $_POST['_course_add_user'] ) ) {
        return;
    }

    $courses = new User_Course_Manager;

    $courses->add_user_to_course( $_POST['_course_add_id'], $_POST['_user_to_add_course'] );
}


function prefix_mb_callback(){ 

    if( !current_user_can( 'administrator' ) ) {
        return;
    }


    $user_id_array = [];
    $users_for_course = "";
    $courses = new User_Course_Manager;

    if( isset($_GET['status_update']) ) {
        $courses->update_status( $_GET['status'], $_GET['user_id'], $_GET['course_id'] );
    }


   
    if( isset($_GET['post']) ) {
        
        $users_for_course = $courses->get_users_for_course( $_GET['post'] );

    }

    ?>
     <p style="font-size:12px">Below are a list of customers who have purchased this product and have access to all the courses selected. 
        You can revoke a users access to this products courses by clicking revoke. You can also Add a User to give them 
        access to all of the courses defined in this product.</p>
     <div class="course_ui_window">
    <?php 
    
    if( $users_for_course ) {
        ?>
        
          
                <ul>
                <?php foreach( $users_for_course as $result ) : $course_user = new WP_User( $result->user_id );  $user_id_array[] = $result->user_id; ?>
                    <li>
                        <span><?php echo get_user_meta( $course_user->ID, 'first_name', true ); ?> <?php echo get_user_meta( $course_user->ID, 'last_name', true ); ?></span>
                        <?php if( $result->suspend == 0 ) : ?>
                            <a href="<?php echo get_edit_post_link(); ?>&status=1&user_id=<?php echo $course_user->ID; ?>&course_id=<?php echo $result->product_id; ?>&status_update=1">Revoke</a>
                        <?php else: ?>
                            <a href="<?php echo get_edit_post_link(); ?>&status=0&user_id=<?php echo $course_user->ID; ?>&course_id=<?php echo $result->product_id; ?>&status_update=1">Allow Access</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            
        <?php 
    } else {
        echo "No customers have bought this product yet.";
    }

   ?>
        </div>
                <div class="course_ui_add">
                
                <select  class="js-example-basic-single" name="_user_to_add_course" style="max-width: 70%">
                    <option value="">Select a user</option>
                    <?php $users_query = new WP_User_Query( ['exclude' => $user_id_array, 'orderby' => 'name', 'order' => 'ASC'] ); ?>
                    <?php if( $users_query ) : ?>
                        <?php foreach( $users_query->get_results() as $user ) : ?>
                            <option value="<?php echo $user->ID; ?>"><?php echo $user->user_email; ?> - <?php echo get_user_meta( $user->ID, 'first_name', true ); ?> <?php echo get_user_meta( $user->ID, 'last_name', true ); ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
                <input type="hidden" name="_course_add_id" value="<?php echo $_GET['post']; ?>" />
                <input type="submit" name="_course_add_user" value="Add User" />
            
        </div>
    <?php 

    ?>

<style>
        .course_ui_window {
            max-height: 250px;
            border: 1px solid #666;
            padding: 5px;
            overflow-y: auto;
            margin-bottom: 10px;
        }

        .course_ui_window li {
            display: flex;
            justify-content: space-between;
            padding-bottom: 5px;
            margin-bottom: 5px;
            border-bottom: 1px solid #eee;
        }

        .course_ui_add  {
            display: flex;
            align-items: stretch;
        }

        .course_ui_add select {
            flex: 1;
        }

    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        // In your Javascript (external .js resource or <script> tag)
        jQuery(document).ready(function($) {
            $('.js-example-basic-single').select2();
        });
    </script>
    <?php 

}




// -- AJAX Handler for Admin 


function ajax_get_user_lesson_progress() {

    $user_progress = [];


    global $wpdb;

    if( !current_user_can( 'administrator' ) ) {
        wp_send_json_error( array( 'msg' => 'Invalid Permissons' ) );
    }

    // -- Get the data from the database for this user
    $results = $wpdb->get_results("SELECT * FROM " .  $wpdb->prefix . 'user_lesson_progress' . " WHERE user_id = " . $_POST['user'] . " AND course_id = " . $_POST['course']);

    // -- lets get the modules and lessons for this course.
    $course_data = get_field( 'modules', $_POST['course'] );

    // -- Loop through the course data 
    if( $results ) {

        foreach( $results as $result ) {

            $user_progress[$result->module_id][$result->lesson] = $result->progress;

        }

        /*$m=0; foreach( $course_data as $module ) {
            $l=0; foreach( $module['module_lessons'] as $lesson ) {

                foreach($result as $user_course_progress ) {
                    if( $user_course_progress->module_id = $m && $user_course_progress->lesson == $l ) {
                        $course_data[$m]['module_lessons'][$l]['progress'] = $user_course_progress->progress;
                    } else {
                        $course_data[$m]['module_lessons'][$l]['progress'] = "Not Started";
                    }
                }

                $l++;
            }
            $m++;
        }*/
    } 



   

    $html = "<div class='status-lesson-box'><table class='table table--sub'>";
    $m=1; foreach( $course_data as $module ) {

        $html .= "<tr><th class='table-sub' colspan='2'>Module " . $module['module_name'] . "</th></tr><tr><th>Lesson</th><th>Progress</th></tr>";
        $l=1; foreach( $module['module_lessons'] as $lesson ) {
            $html .= "<tr><td>" . $lesson['lesson_name'] . "</td><td>";
            if( isset($user_progress[$m][$l]) ) {
                if( $user_progress[$m][$l] == "Complete" ) {
                    $html .=  "<span class='usx-progress usx-progress--complete'>Complete</span></td></tr>";  
                } else {
                    $html .=   "<span class='usx-progress usx-progress--pending'>Partially Views ( " . number_format($user_progress[$m][$l],2) . "s ) </span></td></tr>";  
                }
               
            } else {
                $html .=   "<span class='usx-progress usx-progress--no'>Not Started</span>";
            }
            $l++;
        }
        $m++;
    }   
    $html .= "</table></div>";
  
    echo $html;
    die();

}




add_action('wp_ajax_user_lesson_progress' , 'ajax_get_user_lesson_progress');



// -- only used here so makes sense to have it here
function user_has_completed_lesson( $module_index, $lesson_index, $echo = true, $progressObject = "" ) {

    global $progress;

    if( !$progress ) {

        if( !$progressObject ) return;
        
        $progess = $progressObject;

    }

    
    if( isset($progress[$module_index][$lesson_index])) {
        
        if($progress[$module_index][$lesson_index] == "Complete" ) {
            if( !$echo ) return true;
            echo 'class="lesson-complete"';
        } else {
            if( !$echo ) return false;
            echo 'class="lesson-partial"';
        }
    }

}



/* === Add Export Users of courses */ 

function global_notice_meta_box() {

    add_meta_box(
        'course_export_users',
        __( 'Export', 'sitepoint' ),
        'course_export_users_callback',
        'course',
        'side'
    );
    
}

function course_export_users_callback() {
    ?>
        <a href="<?php echo admin_url(sprintf('admin.php?%s', http_build_query($_GET))); ?>&action=export_users_courses" class="button">Export Users</a>
    <?php 
}

add_action( 'add_meta_boxes', 'global_notice_meta_box' );

function export_course_users_action() {

    global $wpdb;

    if( !current_user_can( 'administrator' ) ) {
        return;
    }

    if( isset($_GET['action']) ) {
        if( $_GET['action'] == "export_users_courses")  {

            // -- get course id
            $course_id = $_GET['post'];
            $course_post = get_post( $course_id );

            $matches = [];
            $users = [];

            if( $course_id ) {
                // -- get all products

                $products = get_posts([
                    'post_type'   => ['product'],
                    'numberposts' => -1,
                    'order'       => 'ASC',
                    'orderby'     => 'title',
                    'post_status' => ['publish','private']
                ]);

                // -- lets loop through them
                if( $products ) {
                    foreach( $products as $product ) {

                        // -- get the product courses
                        $courses_for_product = get_post_meta( $product->ID, '_sc_courses', true );

                       
                      
                        
                        if( $courses_for_product ) {
                            foreach($courses_for_product as $course_product) {
                                if( $course_product == $course_id ) {
                                    $matches[] = $product->ID;
                                }
                            }
                        }

                    }
                }

                // -- Lets get all the users who have access to this product
            
                if( sizeof( $matches ) < 2 ) {
                    $users_for_course = $wpdb->get_results("SELECT * FROM " .  $wpdb->prefix . "user_courses WHERE product_id = " . $matches[0] . " AND suspend = 0");
                } else {
                    $users_for_course = $wpdb->get_results("SELECT * FROM " .  $wpdb->prefix . "user_courses WHERE product_id IN (" . implode(",",$matches) . ") AND suspend = 0");
                }

                $c=0;
                foreach( $users_for_course as $usx_course ) {
                    $wp_user = new WP_User( $usx_course->user_id );
                    $new_user = get_userdata( $usx_course->user_id);
                    # Get the user's first and last name
                    $first_name = $new_user->first_name;
                    $last_name = $new_user->last_name;
                    $users[$c][] = $new_user->user_email;
                    $users[$c][] = $first_name;
                    $users[$c][] = $last_name;
                    $c++;
                }

                $output = fopen("php://output",'w') or die("Can't open php://output");
                header("Content-Type:application/csv"); 
                header("Content-Disposition:attachment;filename=" . $course_post->post_title . "-" . time() . ".csv"); 
            
                foreach($users as $product) {
                    fputcsv($output, $product);
                }
      
                fclose($output) ;
                die();

           

            }


        }
    }

}
add_action( 'admin_init', 'export_course_users_action');

