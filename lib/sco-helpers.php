<?php 

Class SCO_Helpers {

    public static $progress;
    
    public static function createMiniHeader ($title, $addButton = false, $showSubNav = false ) {

        global $post;

        $pages = "";

        if( $showSubNav ) {

            $parent = ( $post->post_parent ) ? $post->post_parent : $post->ID;

            $pages = get_pages( array(
                'parent'      =>  $parent,
                'sort_column' => 'menu_order',
            ) );
        }

        ?>
        <div class="masthead masthead--small <?php if( $pages ) : ?> masthead--hasnav <?php endif; ?>">
            <div class="container">
                <div>
                    <h1><?php echo  $title; ?></h1>
                    <?php if( $addButton ) : ?>
                    <a href="#maillist" class="button">Sign up for news alerts</a>
                    <?php endif ?>
                    <?php if( $pages ) : ?>
                        <a href="#" class="m-cat-toggle button"><span>Categories <i class="fas fa-angle-down"></i></span></a>
                        <ul class="filter-list m-nav-toggle">
                           
                                <?php foreach( $pages as $page ) : ?>
                                    <li><a class="button <?php if( $page->ID == $post->ID ) : ?> button--white <?php endif; ?>" href="<?php echo get_permalink($page->ID); ?>"><?php echo $page->post_title; ?></a></li>
                                <?php endforeach; ?>
                           
                        </ul>
                    <?php endif; ?>
                </div>
            </div>

            <div class="gfx gfx--arrow"></div>
            <div class="gfx gfx--grid gfx--grid-b"></div>
            <div class="gfx gfx--circle"></div>

        </div>
        <?php 
    }


    public static function getBusinessReviews() {

       $transient = get_transient( 'sco_reviews_2023x' );
       if( $transient ) {
         return $transient;
       }

       $url = 'https://maps.googleapis.com/maps/api/place/details/json?reference=ChIJw7ZnGL9qdkgReoL8F5J3Jl8&key=AIzaSyANgjVrhY4pB7r9LA4HzXvJOFh0lOXoLuw';

       $curl = curl_init();
       curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);

      $response = json_decode( $response, true );

 

     
      
      $reviews = array_reverse($response['result']['reviews']);
      set_transient( 'sco_reviews_2023x', $reviews, DAY_IN_SECONDS );

      if( $response ) {
         return $reviews;
      }


    }


    public static function getCourseProgress( $course_id, $user_id = "" ) {

        if( !$course_id ) {
            global $post;
            $course_id = $post->ID;
        }

        if( !$user_id ) {
            $user_id =  get_current_user_id();
        }

        self::$progress = get_user_meta( $user_id , 'course_progress', true );

        if( self::$progress ) {
            if( array_key_exists($course_id, self::$progress) ) {
                self::$progress[$course_id]['guid']=getLessonGUID( $course_id, self::$progress[$course_id]['module'],self::$progress[$course_id]['lesson'] );
                return self::$progress[$course_id];
            } 
        } 
        
        return ["module"=>1,"lesson"=>1, "guid"=>getLessonGUID( $course_id, 1,1) ];
     


    }


    public static function userCanAccessCourse( $course_id ) {
        return true;
    }


    public static function getUserPreviousPurchases( $user_id ) {

        global $wpdb;

        $results = $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . 'platform_orders WHERE wp_user_id = ' . $user_id);

       return $results;

    }



    

}


function getLessonGUID( $course_id, $module_id, $lesson_id ) {

    $_course = get_post($course_id);
    if( $_course ) {
        $modules = get_field('modules', $_course->ID);
       
        if( $modules ) {
            $c=0; foreach( $modules as $module ) {
                if( ($module_id-1) == $c ) {
                    $x=0;
                    foreach( $module['module_lessons'] as $lesson ) {
                       
                       if( $x==($lesson_id-1) ) {
                         return $lesson['lesson_guid'];
                       }
                        $x++;
                    }
                }
            }
        }
    }

}
