<?php 

Class Import_Provider_Podia extends Data_Importer_Class {

    var $data_to_import = [];
    var $encode;
 
    public function __construct( $csv_data, $encode = true ) {

        parent::__construct( $csv_data );
        $this->encode = $encode;   
        
    }

    public function createCustomers( $dry_run ) {

        global $wpdb;

       if( $this->$data_from_csv ) {
        foreach( $this->$data_from_csv as $podia_record ) {

            $user = get_user_by( 'email', $podia_record['Email'] );


            if( !$user ) {
                    
                $user = $this->userObsifcationEmailCheck( $import_row->email );
                
             }

            // -- If Not A user then we need to add them
            if( !$user ) {

                if( !$dry_run ) {

                   $user_id = $this->createNewWPUser( $podia_record['Email'], $podia_record['Name'] );

                   if ( is_wp_error( $user_id ) ) {
                   // $user_id = wc_create_new_customer( $podia_record['Email'], wc_create_new_customer_username( $podia_record['Email'], $podia_record['Name'] ), $this->randomPassword() );
                        if( $user_id ) {

                            // -- insert into user courses table
                            
                            $wpdb->insert( 
                                $wpdb->prefix . 'user_courses', 
                                array( 
                                    'product_id' => $_POST['_csv_product'],
                                    'user_id' => $user_id, 
                                    'order_id' => 9999999, 
                                ) 
                            );
                            
                            // -- add meta
                            update_user_meta( $user_id, "_mail_obsifcation", $podia_record['Email'] );
                            update_user_meta( $user_id, "imported", 1 );
                            update_user_meta( $user_id, "imported_platform", 'podia' );
                            update_user_meta( $user_id, "imported_onboard", 1 );
                            update_user_meta( $user_id, "imported_batch", $this->_batch );
                        } else {
                            $this->log( "Failed to create customer:", $podia_record['Email']);
                        }
                    }
                } else {
                    $this->log( "Importing:", $podia_record['Email'] . ' - ' . $podia_record['Name']);
                }
            } else {

                // -- If They Are A User we need to check if they can access this course already

                $find = $wpdb->get_row("SELECT id FROM " . $wpdb->prefix . "user_courses WHERE user_id = " . $user_id . " AND product_id=" . $_POST['_csv_product'] );
    
                if( !$find ) {

                    $this->log( "User:", $podia_record['Email'] . ' exsists - giving them access to this course');

                    $wpdb->insert( 
                        $wpdb->prefix . 'user_courses', 
                        array( 
                            'product_id' => $_POST['_csv_product'],
                            'user_id' => $user_id, 
                            'order_id' => 9999999, 
                        ) 
                    );

                } else {
                    $this->log( "User:", $podia_record['Email'] . ' exsists - already has access to this course');
                }
                

               
            }


        }
       }
    
    }

}
