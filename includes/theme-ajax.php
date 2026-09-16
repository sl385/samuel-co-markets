<?php 

Class SCO_THEME_AJAX {

    public function __construct() {

        add_action('wp_ajax_get_course_details' , [$this,'get_course_data']);
        
        add_action('wp_ajax_update_course_progress' , [$this,'update_course_progress']);

        add_action('wp_ajax_update_lesson_progress' , [$this,'update_lesson_progress']);

       // add_action('wp_ajax_get_user_lesson_progress' , [$this,'ajax_get_user_lesson_progress']);
       

        //add_action('wp_ajax_nopriv_get_course_details', [$this,'get_course_data']);

    }

    public function get_course_data() {

        $course_id = intval( $_POST['course'] );
        $vote_nonce_name = 'course-nonce-' . $course_id;

        $module_key = (int)($_POST['module']-1);
        $module_lesson = (int)($_POST['lesson']-1);

        $this->validateCourseRequest( $course_id, $vote_nonce_name );

      
        $course_data = get_field( 'modules', $course_id );

        if( !$course_data ) {
            wp_send_json_error( array( 'msg' => 'No data' ) );
        }

        // Handle if module lesson is greater than the size of the current module.
        if( $_POST['lesson'] > sizeof($course_data[$module_key]['module_lessons']) ) {

            // -- so if the lesson is greater than the size of lessons for the module then we need to move them to the next module
            $next_moudle_key = (int)($module_key+1);
            
            if( isset($course_data[$next_moudle_key]['module_lessons']) ) {

                $lesson_data =  $course_data[$next_moudle_key]['module_lessons'][0];
                $module_to_return = $next_moudle_key;
                $lesson_to_return = 0;

            } else {
                // -- if not for now just show them the last video
                $lessons_in_module = sizeof($course_data[$module_key]['module_lessons']);
                $lesson_data =  $course_data[$module_key]['module_lessons'][($lessons_in_module-1)];

                $module_to_return = $module_key;
                $lesson_to_return = ($lessons_in_module-1);
            }

           
        } else {
            $lesson_data =  $course_data[$module_key]['module_lessons'][$module_lesson];

            $module_to_return = $module_key;
            $lesson_to_return = ($module_lesson+1);

           
        }

        $module_name = $course_data[$module_to_return];

        if( $lesson_data['video_upload_type'] == 2 ) {

            // -- Work out if it is a shortcode and extract the ID
            $video_src = $this->getS3Source( $lesson_data['external_video_url'] );
            if( !$video_src ) {
                $video_src = $lesson_data['external_video_url'];
            }
           
        } elseif(  $lesson_data['video_upload_type'] == 1 || !$lesson_data['video_upload_type'] ) {
            if( $lesson_data['lesson_video_asset']['name'] ) {
                $video_src = get_stylesheet_directory_uri() . '/lib/asset-load.php?key=' .  $lesson_data['lesson_video_asset']['name'];
            } else {
                $video_src = "";
            }
            
        } else {
            $video_src = $lesson_data['vimeo_source'];
        }
 
 
         $data = [
             "lesson_title" => $module_name['module_name'] . ': ' . $lesson_data['lesson_name'],
             "lesson_description" => $lesson_data['lesson_description'],
             "video_src" => $lesson_data['lesson_video_asset']['name'],
             "video_url" => $video_src,
             "assets" => $lesson_data['lesson_downloads'],
             "module" => $module_to_return,
             "lesson" => $lesson_to_return,
             "guid"=> $lesson_data['lesson_guid'],
             "type"=> ( $lesson_data['video_upload_type'] ) ? $lesson_data['video_upload_type'] : 1
         ];

        wp_send_json_success( array('msg'=>'Success', 'data'=>json_encode($data)));
        

        
    }

    public function update_course_progress() {

        // -- Extract Course Parameters
        $course_id = intval( $_POST['course'] );
        $vote_nonce_name = 'course-nonce-' . $course_id;

        // -- Validate Request
        $this->validateCourseRequest( $course_id, $vote_nonce_name );

        // -- Current Module Key
        $module_key = ($_POST['module']-1);
        $module_lesson = ($_POST['lesson']-1);

        // -- Get The Course Data
        $course_data = get_field( 'modules', $course_id );

        if( !$course_data ) {
            wp_send_json_error( array( 'msg' => 'No data' ) );
        }

        // -- Get User Progress
        $user_proogress = get_user_meta( get_current_user_id(), 'course_progress', true );

        if( !$user_proogress ) {
            $user_proogress = [];
        }

        // -- Ok Work out if we where on the last module
        $return_lesson = 1;

        // -- If our lesson count exceeds the current module lessons or is equal then we need to move on to the next module
        if( sizeof( $course_data[$module_key]['module_lessons'] ) == ($module_lesson+1) || 
            ($module_lesson+1) > sizeof($course_data[$module_key]['module_lessons']) ) {

            $module_key = $module_key++;

            // -- we are on the next Module
            $module_data = [
                'module' => $module_key,
                'lesson' => 1
            ];

            $user_proogress[$course_id]['module'] = ($_POST['module']+1);
            $user_proogress[$course_id]['lesson'] = 1;

            $return_module = $_POST['module']+1;

        } else {

            $next_lesson = ($module_lesson+1);

            $module_data = [
                'module' => $module_key,
                'lesson' => $next_lesson 
            ];

            $user_proogress[$course_id]['module'] = $_POST['module'];
            $user_proogress[$course_id]['lesson'] = ($_POST['lesson']+1);

            $return_lesson = ($_POST['lesson']+1);
            $return_module = $_POST['module'];

        }

        // -- update course progress
        update_user_meta( get_current_user_id(), 'course_progress', $user_proogress );

        // -- send the response back
        wp_send_json_success( array('msg'=>'Success', 'data'=>json_encode(['module'=>$return_module,'lesson'=>$return_lesson]) ) );

    }

    public function validateCourseRequest( $course_id, $vote_nonce_name  ) {
        if( ! is_user_logged_in( ) ) {
            wp_send_json_error( array( 'msg' => 'Not logged in' ) );
        }

        if( ! check_ajax_referer( $vote_nonce_name, 'course_nonce', false ) ) {
            wp_send_json_error( array( 'msg' => 'Security breach' ) );
        }

        $user = wp_get_current_user();
        $new_usx = new User_Course_Manager;

        if( !$new_usx->user_can_access_course( $user->ID, $course_id ) ) {
            wp_send_json_error( array( 'msg' => 'Invalid Permissons' ) );
        }

    
    }


    public function update_lesson_progress() {

        global $wpdb;

        // -- Extract Course Parameters
        $result = "";
        $course_id = intval( $_POST['course'] );
        $vote_nonce_name = 'course-nonce-' . $course_id;
        $guid = $_POST['lesson_guid'];
        if( !$guid ) {
            $guid = getLessonGUID( $_POST['course'], $_POST['module'], $_POST['lesson'] );
        }
        // -- Validate Request
        //$this->validateCourseRequest( $course_id, $vote_nonce_name );

        // -- Do we have some user progression already
        if( $_POST['lesson_guid'] != "" ) {
            $result = $wpdb->get_row("SELECT * FROM " .  $wpdb->prefix . "user_lesson_progress WHERE user_id=" . get_current_user_id() . " AND guid='" . $_POST['lesson_guid'] . "'");
        } 
    

        if( !$result ) {
            $result = $wpdb->get_row("SELECT * FROM " .  $wpdb->prefix . 'user_lesson_progress' . " 
            WHERE user_id=" . get_current_user_id() . " AND course_id=" . $_POST['course'] . " 
            AND lesson=" . $_POST['lesson'] . " and module_id=" . $_POST['module']);
        }
      

        if( $result->progess == "Complete" ) { die(); }

        if( $result ) {
            $wpdb->update( 
                $wpdb->prefix . 'user_lesson_progress', 
                array( 
                    'course_id' => $_POST['course'],
                    'user_id' => get_current_user_id(), 
                    'module_id' =>  $_POST['module'],
                    'lesson' => $_POST['lesson'], 
                    'progress' => $_POST['time'],
                    'guid' => $guid
                ),
                array('ID'=>$result->ID)
            );
        } else {
            $wpdb->insert( 
                $wpdb->prefix . 'user_lesson_progress', 
                array( 
                    'course_id' => $_POST['course'],
                    'user_id' => get_current_user_id(), 
                    'module_id' =>  $_POST['module'],
                    'lesson' => $_POST['lesson'], 
                    'progress' => $_POST['time'],
                    'guid' => $guid
                ) 
            );
        }

    }


    

    public function ajax_get_user_lesson_progress() {

        global $wpdb;

        $progress = [];

        if( !current_user_can( 'administrator' ) ) {
            wp_send_json_error( array( 'msg' => 'Invalid Permissons' ) );
        }

        $results = $wpdb->get_results("SELECT * FROM " .  $wpdb->prefix . 'user_lesson_progress' . " WHERE user_id = " . $_POST['user'] . " AND course_id = " . $_POST['course']);
       
        
        
        if( $results ) {
            $html = "<table><tr><th>Lesson</th><th>Progress</th></tr>";
            foreach( $results as $result ) {
                $html .= "<tr><td>Lesson</td>";
                if( $result->progess == "complete" ) {
                    $html .= "<td><span class='stauts-video'> Watched up to: " . $result->progress . "</span></td>";
                } else {
                    $html .= "<td><span class='stauts-video status-video--complete'>Completed</span></td>";
                }
            
            }   
            $html .= "</table>";
        } else {
            $html = "No Data Available for this user on this lesson";
        }

        echo $html;
        die();

    }

    public function getS3Source( $shortcode ) {   

     $has_shortcode = has_shortcode($shortcode, "s3mvp");
    // -- If we have a shortcode then we need to pull the ID out
    if( $has_shortcode ) {

       

        // -- get shortcode params ( single quote )
        $params = $this->eri_shortcode_parse_atts( $shortcode );
        // -- If we have An ID lets Use inbuilt function to grab what we need
       
            $s3mvShortcodes = new S3MVShortCodes();
	        $s3_shortcode = $s3mvShortcodes->getShortcode($params['id']);
            // -- Get some globals
            $accessKey  = S3MVConfig::get("accessKey");
            $secretKey = S3MVConfig::get("secretKey");
            $cfregion = S3MVConfig::get("cfregion");

            // -- dump shortcode properties

            // -- Filename
            $source = $s3_shortcode[0]->source;
	        $filename = $s3_shortcode[0]->filename;

            // -- We need Bucket Name
            $bucketname = $s3_shortcode[0]->bucketname;
            if(empty($bucketname)) {
                $bucketname = S3MVConfig::get("bucketName");
            } else { //There's something in bucketname
                if(stripos($bucketname,"/") !== false) { //there's a slash somewhere, so it's a sub-folder
                    $prefix = trim($bucketname,"/");
                    $prefix = $prefix . "/";
                    //echo $prefix; exit;
                    $bucketname = S3MVConfig::get("bucketName");
                } else {
                    //otherwise keep what's in bucketname, because it's an actual different bucket name
                    $difftBucket = true;
                }
            }

            // -- How Many Seconds
            $howManySeconds = S3MVConfig::get('howManySeconds');
        
            if( !empty($expirationCustom) && (intval($expirationCustom) > 0) )  { //use custom expiration
                $s3mv_howManySeconds = $expirationCustom;
            } else {
                $s3mv_howManySeconds = empty($howManySeconds) ? 1000 : $howManySeconds;
            }

            // -- Lets get the URL now
            if($source == "s3") {
                //$file = getAuthenticatedURL($bucketname, $filename, $s3mv_howManySeconds, $accessKey, $secretKey);
                $file = getAuthenticatedURLS3MV($bucketname, $filename, $s3mv_howManySeconds, $accessKey, $secretKey, $cfregion);
            } else if($source == "cf") { //If CloudFront
                //error_log("in source = cf", 0);
                if( ($filetype == "streaming") || ($filetype == "streamingaudio") ) {
                    //error_log("inside streaming", 0);
                    //$posmp4 = stripos($filename,".mp4");
                    if ( (substr($filename, -4) != ".mp4") && (substr($filename, -4) != ".mp3") ) {
                        return "Sorry, your file must be a .mp4 video or a mp3 audio that has already been prepared for streaming (via S3MediaVault Media page) if you wish to use the Streaming Player.";
                    } else {
                        $filenameExploded = explode('/',$filename);
                        $filename = end($filenameExploded);
                        //error_log("filename in streaming: $filename", 0);
                        //echo $filename; exit;
                        if($filetype == "streaming") $filename = "streaming/" . str_replace(".mp4",".m3u8",$filename);
                        if($filetype == "streamingaudio") $filename = "streaming/" . str_replace(".mp3",".m3u8",$filename);
                    }
                }
                $file = processCloudFront($filename);
                //echo $file; exit;
            } //End If CloudFront

            
           /* echo "Source:" . $source . "<br/>";
            echo "Bucket Name:" . $bucketname . "<br/>";
            echo "Filename:" . $filename . "<br/>";
            echo "How Many Seconds:" . $s3mv_howManySeconds . "<br/>";
            echo "Region" . $cfregion . "<br/>";
            echo "url" . $file . "<br/>";*/
            return $file;
       
    } else {
        return false;
    }


    }



    public function eri_shortcode_parse_atts( $shortcode ) {
        // Store the shortcode attributes in an array here
        $attributes = [];

        // Get all attributes
        if (preg_match_all('/\w+\=\'.*?\'/', $shortcode, $key_value_pairs)) {

            // Now split up the key value pairs
            foreach($key_value_pairs[0] as $kvp) {
                $kvp = str_replace("'", "", $kvp);
                $pair = explode('=', $kvp);
                $attributes[$pair[0]] = $pair[1];
            }
        }

        // Return the array
        return $attributes;
    }



}

new SCO_THEME_AJAX;
