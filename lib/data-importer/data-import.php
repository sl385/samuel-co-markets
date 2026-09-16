<?php

require 'providers/provider.class.php';
require 'providers/provider.gocardless.class.php';
require 'providers/provider.podia.class.php';
require 'providers/provider.shopify.class.php';
require 'providers/provider.zoho-user.class.php';
require 'providers/provider.zoho-subscriptions.class.php';

Class SCO_Error_Bag {

   var $errors;
   var $startBag = false;
   var $internal_key = 1;
   var $type = "errors";

   public function __construct() {
       $this->errors = [];
   }

   public function add( $msg, $key = "" ) {
    $this->addError(  $msg, $key );
   }

   public function addError( $msg, $key = "" ) {
       $this->addMessage( $msg, "", $key );
   }

   public function addMessage( $message, $type = "error", $key = "" ) {

       if( !$key ) $key = "error-" . $this->internal_key;

       if( $key ) {
           $this->errors[$key] = [
               "type" => $type,
               "msg" => $message
           ];
       }

       $this->internal_key++;
   }

   public function hasError( $key ) {
       return $this->errors[$key];
   }

   public function hasErrors(  ) {
       return sizeof($this->errors);
   }

   public function error( $key ) {
       if( $this->hasError($key) ) {
           ?>
           <span class="error"><?php echo $this->errors[$key]['msg']; ?></span>
           <?php 
       }
   }


}
$import = "";
$import_errors = new SCO_Error_Bag;

// Samuel Co - Data imports
function wpdocs_register_my_custom_menu_page(){
	add_menu_page( 
		__( 'Data Importer', 'textdomain' ),
		'Data Importer',
		'manage_options',
		'dataimporter',
		'data_importer_ui',
		plugins_url( 'myplugin/images/icon.png' ),
		500
	); 
}
add_action( 'admin_menu', 'wpdocs_register_my_custom_menu_page' );

/**
 * Display a custom menu page
 */
function data_importer_ui(){
    global $import_errors;
    global $import;

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => array('publish', 'pending', 'private' )    
    );
    $products = new WP_Query($args)


    ?>
        <h1>Samuel Co Data Importer</h1>

        <?php if( $import_errors->hasErrors() ) : ?>
            <div class="import-log import-<?php $import_errors->$type; ?>">
            <?php foreach( $import_errors->errors as $error ) : ?>
                <p><?php echo $error['msg']; ?></p>
            <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if( $import->logger ) : ?>
        <?php //if( isset($_POST['dryrun']) ) : ?>
            <div class="import-log import-stats">
            <?php foreach( $import->logger as $log ) : ?>
                <p><strong><?php echo $log['title']; ?></strong> <?php echo $log['content']; ?></p>
            <?php endforeach; ?>
            </div>
        <?php// endif; ?>
        <?php endif; ?>

        <div class="import_ui">

            <ul class="importer__platforms">
                <li data-index="1" class="active"><a href="#">Shopify</a></li>
                <li data-index="2"><a href="#">Podia</a></li>
                <!-- <li data-index="3"><a href="#">Zoho Customers</a></li>
                <li data-index="3"><a href="#">Zoho Sales</a></li>
                <li data-index="4"><a href="#">GoCardless</a></li> -->
                <li data-index="3"><a href="#">Tools</a></li>
            </ul>

           
            <div class="platform active">
                <h2>Shopify</h2>
                <p>Shopify import will import customers and orders from the orders CSV file.</p>
                <form  method="post" enctype="multipart/form-data">
                    <p>Please select a CSV File</p>
                    <input type="file" name="_csv" value="CSV to upload" />
                    <p>Run as dry run? <input type="checkbox" name="dryrun" value="1" /></p>
                    <input type="hidden" name="platform" value="shopify" />
                    <?php submit_button('Start Import'); ?>
                </form>
            </div>

            <div class="platform">
                <h2>Podia</h2>
                <form  method="post" enctype="multipart/form-data">
                    <p>Select a product to assign these users too</p>
                    <select name="_csv_product">
                        <option value="">Please Select</option>
                        <?php if( $products->have_posts() ) : ?>
                            <?php foreach( $products->posts as $product ) : ?>
                                <option value="<?php echo $product->ID; ?>"><?php echo $product->post_title; ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <p>Please select a CSV File</p>
                    <input type="file" name="_csv" value="CSV to upload" />
                    <p>Run as dry run? <input type="checkbox" name="dryrun" value="1" /></p>
                    <input type="hidden" name="platform" value="podia" />
                    <?php submit_button('Start Import'); ?>
                </form>
            </div>


            <div class="platform">
                <h2>Tools</h2>
                <h3>Email Unobsification Check</h3>
                <p>Check which if any users are using obsifcation emails</p>
                <form  method="post" enctype="multipart/form-data">
                    <input type="hidden" name="unbofiscate_emails" value="1" />
                    <?php submit_button('Check Email Obsifcation', '', 'email_obs_check'); ?>
                </form>
                <hr/>
                <h3>Email Unobsification Update</h3>
                <p>To avoid auto emails being sent to users on import emails are obsifcated. Click the below to update those values</p>
                <form  method="post" enctype="multipart/form-data">
                    <input type="hidden" name="unbofiscate_emails" value="1" />
                    <?php submit_button('Update all obsifcated emails', '', 'email_obs_update'); ?>
                </form>
                <hr/>
            </div>

            <?php /*
            <div class="platform">
                <h2>Zoho</h2>
                <form  method="post" enctype="multipart/form-data">
                    <p>Add Contacts CSV File</p>
                    <input type="file" name="_csv" value="CSV to upload" />

                   <!-- <p>2. Add Subscriptions CSV File</p>
                    <input type="file" name="_csv2" value="CSV to upload" /> -->

                    <p>Run as dry run? <input type="checkbox" name="dryrun" value="1" /></p>
                    <input type="hidden" name="platform" value="zoho" />
                    <?php submit_button('Start Import'); ?>
                </form>
            </div>

            <div class="platform">
                <h2>Zoho Sales</h2>
                <form  method="post" enctype="multipart/form-data">
                    <p>Add Subscriptions CSV File</p>
                    <input type="file" name="_csv" value="CSV to upload" />

                   <!-- <p>2. Add Subscriptions CSV File</p>
                    <input type="file" name="_csv2" value="CSV to upload" /> -->

                    <p>Run as dry run? <input type="checkbox" name="dryrun" value="1" /></p>
                    <input type="hidden" name="platform" value="zoho_sales" />
                    <?php submit_button('Start Import'); ?>
                </form>
            </div>

            <div class="platform">
                <h2>GoCardless</h2>
                <form  method="post" enctype="multipart/form-data">
                     <p>Please select a CSV File</p>
                    <input type="file" name="_csv" value="CSV to upload" />
                    <p>Run as dry run? <input type="checkbox" name="dryrun" value="1" /></p>
                    <input type="hidden" name="platform" value="gocardless" />
                    <?php submit_button('Start Import'); ?>
                </form>
            </div>
            */ ?>
            </form>


            
        </div>

        <?php importer_scripts_styles(); ?>

    <?php
}

function importer_scripts_styles() {
?>
    <script>

            jQuery( document ).ready(function( $ ) {
                
                $('.importer__platforms a').bind('click', function(e){
                    
                    var index = $(this).parent().data('index');

                    if( !$(this).hasClass('active') ) {
                        $('.importer__platforms li').removeClass('active');
                        $(this).parent().addClass('active');
                    }

                    $('.platform').removeClass('active');
                    $('.platform').eq( (index-1) ).addClass('active');

                    return false;
                })

            });

    </script>


    <style>

        .importer__platforms {
            display: flex;
            margin: 20px 0;
            background: #fff;
            padding: 15px;
        }

        .importer__platforms li {
            margin-right: 15px;
        }

        .importer__platforms a {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            border: 1px solid #000;
            color: #000;
        }

        .importer__platforms a:hover {
            background: #ccc;
        }

        .importer__platforms li.active a {
            background: #333;
            color: #fff;
        }

        .platform {
            padding: 25px;
            background: #fff;
            border: 1px solid #ccc;
        }

        .platform {
            display: none;
        }

        .platform.active {
            display: block;
        }

        .import-errors {
            background: tomato;
            padding: 10px;
            color: #fff;
            font-size: 12px;
        }

        .import-msg {
            background: #88e9fc;
            padding: 10px;
            color: #fff;
            font-size: 12px;
        }

        .import-log {
            height: 200px;
            overflow-y: auto;
            padding: 15px;
            background: #78CAFF;
        }

        

    </style>

<?php 
}

function do_import() {

    global $import_errors;
    global $import;
    global $wpdb;

    // -- Handle CSV Import
    if( isset($_POST['submit'])) {

        $platform = $_POST['platform'];
        if( !$platform ) {
            $import_errors->add("Invalid Platform selected");
            return;
        }

        if( !isset($_FILES['_csv']) ) {
            $import_errors->add("No CSV selected");
            return;
        }

        // -- Parse The CSV
        $csv_data = new Import_CSV_handle( "_csv", $import_errors, $platform );

        if( !$import_errors->hasErrors() ) {
            if( $platform == "shopify" ) {
                // -- Format CSV into JSON with customer order collection
                $import = new Import_Provider_Shopify( $csv_data->_data );
                // -- Save Items / Do Dry Run
                $import->saveToWoo( isset($_POST['dryrun']) );
            }
            if( $platform == "podia" ) {
                // -- Format CSV into JSON with customer order collection
                $import = new Import_Provider_Podia( $csv_data->_data );
                // -- Save Items / Do Dry Run
                $import->createCustomers( isset($_POST['dryrun']) );
            }
            if( $platform == "zoho" ) {
                  // -- Redundant Provider removed
                // -- Format CSV into JSON with customer order collection
                $import = new Import_Provider_Zoho_User( $csv_data->_data );
                // -- Save Items / Do Dry Run
                $import->createCustomers( isset($_POST['dryrun']) );
            }
            if( $platform == "gocardless" ) {
                // -- Redundant Provider removed
            }
        }

        $csv_data->deleteCsv();

    }

    // -- Handle Email Obsifcation Check
    if( isset($_POST['email_obs_check']) ) {
        // -- Query any imported user who has an obsficated email field

        $import_errors->type = "msg";

        $args  = array(
            'posts_per_page' => -1
        );

        $obs_count = 0;
        $user_query = new WP_User_Query( $args );
        if ( ! empty( $user_query->get_results() ) ) {
            foreach ( $user_query->get_results() as $user ) {

                if( get_user_meta($user->ID, '_mail_obsifcation', true) ) {
                     // -- compare email to obsfication email
                    if( $user->user_email != get_user_meta($user->ID, '_mail_obsifcation', true) ) {
                        // if they are different then flag it
                        $import_errors->add( $user->user_email . " Is obsifcated it should be " . get_user_meta($user->ID, '_mail_obsifcation', true) );
                        $obs_count++;
                    }
                }

            }
            if( $obs_count == 0 ) {
                $import_errors->add( "You're Good - There are no Obsifcated emails to change" );
            }
        } else {
            $import_errors->add( "You're Good - There are no Obsifcated emails to change" );
        }

    }

    // -- Handle Email Obsifcation Update
    if( isset($_POST['email_obs_update']) ) {

        $import_errors->type = "msg";

        $args  = array(
            'posts_per_page' => -1
        );

        $obs_count = 0;
        $user_query = new WP_User_Query( $args );
        if ( ! empty( $user_query->get_results() ) ) {
            foreach ( $user_query->get_results() as $user ) {

                if( get_user_meta($user->ID, '_mail_obsifcation', true) ) {
                     // -- compare email to obsfication email
                    if( $user->user_email != get_user_meta($user->ID, '_mail_obsifcation', true) ) {

                        // -- update user email here
                        $wpdb->update($wpdb->users, array('user_email' => get_user_meta($user->ID, '_mail_obsifcation', true)), array('ID' => $user->ID));

                        //update_user_meta( $user->ID, $meta_key:string, $meta_value:mixed, $prev_value:mixed );

                        // if they are different then flag it
                        $import_errors->add( $user->ID . " email changed from " . $user->user_email . " to " . get_user_meta($user->ID, '_mail_obsifcation', true) );
                        $obs_count++;
                    }
                }

            }
            if( $obs_count == 0 ) {
                $import_errors->add( "You're Good - There are no Obsifcated emails to change" );
            } else {
                $import_errors->add( $obs_count . " obsifcated emails changed" );
            }
        } else {
            $import_errors->add( "You're Good - There are no Obsifcated emails to change" );
        }
        
    }

}
add_action('init', 'do_import');



Class Import_CSV_handle {

    var $error_bag;
    var $csv_path;
    var $_data;
    var $_batch;

    public function __construct( $file_index, $error_bag, $platform ) {

        $this->_batch = get_option('samco_import_batch_number') + 0;

        $upload_dir = wp_upload_dir();
        $new_file_dir = $upload_dir['basedir'].'/csv-import/' . $platform;

        if ($_FILES[$file_index]["error"] > 0) {
             $error_bag->add( "File Upload Error: " . $_FILES[$file_index]["error"]);
             return;
        }

        // -- rename and move the CSV
        $filename = strtolower(str_replace(" ", "_", $_FILES[$file_index]["name"]));
        move_uploaded_file($_FILES[$file_index]["tmp_name"], $new_file_dir . "/" . $filename);
        $this->csv_path = $new_file_dir. "/" . $filename;

        // -- Load and return
        

        if (($handle = fopen($this->csv_path, "r")) !== FALSE) {
            $c=0;
            while ( ($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
                if( $c==0 ) {
            
                    $this->_data[$c] = $data;

                } else {

                    $i=0; foreach( $this->_data[0] as $header ) {
                        $this->_data[$c][$header] = $data[$i];
                        $i++;
                    }

                }
                $c++;


            }

            unset( $this->_data[0] );

            $this->_batch = $this->_batch+1;
            update_option('samco_import_batch_number', $this->_batch);
   

        } else {
            $error_bag->add( "Failed to open CSV");
            return;
        }


    }

    public function deleteCsv() {
        unlink( $this->csv_path );
    }

}

