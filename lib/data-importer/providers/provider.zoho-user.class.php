<?php 

Class Import_Provider_Zoho_User extends Data_Importer_Class {

    var $data_to_import = [];
    var $encode;
 
    public function __construct( $csv_data, $encode = true ) {

        parent::__construct( $csv_data );
        $this->encode = $encode;   

        $this->formatForWoo();
        if( $this->encode ) {
            $this->data_to_import = json_encode( $this->data_to_import );
        }
   
    
    }

    public function createCustomers( $dry_run ) {

        if( $this->encode ) {
            $data = json_decode( $this->data_to_import );
        } else {
            $data = $this->data_to_import;
        }

        if( $data ) {
            foreach( $data as $import_row ) {

                $user = get_user_by( 'email', $import_row->email );
                if( !$dry_run && !$user ) {
                    $user = $this->createWooCustomer( $import_row, $batch );
                }

                if( $user->ID ) {
                    update_user_meta( $user->ID, "zoho_id", $import_row->zoho->zoho_id );
                    $this->log("Updating",  "customer: " . $import_row->email . " Added Zoho Customer ID");
                } else {
                    $this->log("Creating",  "a new customer for: " . $import_row->email );
                }
              
               

            } 
        }

       /*if( $this->$data_from_csv ) {
        foreach( $this->$data_from_csv as $podia_record ) {

            if( !$this->email_exsits( $podia_record['Email']) ) {

                if( !$dry_run ) {
                    $user_id = wc_create_new_customer( $podia_record['Email'], wc_create_new_customer_username( $podia_record['Email'], $podia_record['Name'] ), $this->randomPassword() );
                    if( $user_id ) {
                        update_user_meta( $user_id, "imported", 1 );
                        update_user_meta( $user_id, "imported_platform", 'podia' );
                        update_user_meta( $user_id, "imported_onboard", 1 );
                        update_user_meta( $user_id, "imported_batch", $this->_batch );
                    } else {
                        $this->log( "Failed to create customer:", $podia_record['Email']);
                    }
                } else {
                    $this->log( "Importing:", $podia_record['Email'] . ' - ' . $podia_record['Name']);
                }
            } else {
                $this->log( "Failed to import:", $podia_record['Email'] . ' user is a customer');
            }


        }
       }*/
    
    }



    public function formatForWoo() {
        foreach( $this->$data_from_csv as $data ) {

            $this->data_to_import[$data['EmailID']] = [
                'email' => $data['EmailID'],
                'name' => [
                    'first_name' => $data['First Name'],
                    'second_name' => $data['Last Name'],
                    'email' => $data['EmailID']
                ],
                'billing' => [
                    'street' => $data["Billing Street2"],
                    'address1' =>  $data["Billing Address"],
                    'address2' =>  $data["Billing Street2"],
                    //'company' => $data["Billing Company"],
                    'city' => $data["Billing City"],
                    'zip' =>  $data["Billing Code"],
                    'state' =>  $data["Billing State"],
                    'country' =>  $data["Billing Country"],
                    'phone' => $data["Billing Phone"],
                ],
                'shipping' => [
                    'street' => $data["Shipping Street2"],
                    'address1' =>  $data["Shipping Address"],
                    'address2' =>  $data["Shipping Street2"],
                    //'company' => $data["Shipping Company"],
                    'city' => $data["Shipping City"],
                    'zip' =>  $data["Shipping Code"],
                    'state' =>  $data["Shipping State"],
                    'country' =>  $data["Shipping Country"],
                    'phone' => $data["Shipping Phone"],
                ],
                'zoho'=> [
                    'zoho_id' => $data['Customer ID'],
                    'currency' =>  $data['Currency Code'],
                ]
            ];

        }
    }


    public function createWooCustomer( $data, $batch ) {

       // $user_id = wc_create_new_customer( $data->email, wc_create_new_customer_username( $data->email, [$data->name->first_name,$data->name->second_name] ), $this->randomPassword() );
        if( $user_id ) {
            update_user_meta( $user_id, "first_name",  $data->name->first_name );
            update_user_meta( $user_id, "last_name", $data->name->second_name );
            update_user_meta( $user_id, "billing_first_name",  $data->name->first_name );
            update_user_meta( $user_id, "billing_last_name", $data->name->second_name );
           // update_user_meta( $user_id, "billing_company", $data->billing->second_name );
            update_user_meta( $user_id, "billing_address_1", $data->billing->address1 );
            update_user_meta( $user_id, "billing_address_2", $data->billing->address2 );
            update_user_meta( $user_id, "billing_city", $data->billing->city );
            update_user_meta( $user_id, "billing_postcode", $data->billing->zip );
            update_user_meta( $user_id, "billing_country", $data->billing->country );
            update_user_meta( $user_id, "billing_state", $data->billing->state );
            update_user_meta( $user_id, "billing_email", $data->email );
            update_user_meta( $user_id, "billing_phone", $data->billing->phone );
            
            update_user_meta( $user_id, "shipping_first_name",  $data->name->first_name );
            update_user_meta( $user_id, "shipping_last_name", $data->name->second_name );
           // update_user_meta( $user_id, "shipping_company", $data->shipping->company );
            update_user_meta( $user_id, "shipping_address_1", $data->shipping->address1 );
            update_user_meta( $user_id, "shipping_address_2", $data->shipping->address2 );
            update_user_meta( $user_id, "shipping_city", $data->shipping->city );
            update_user_meta( $user_id, "billing_postcode", $data->shipping->zip );
            update_user_meta( $user_id, "shipping_country", $data->shipping->country );
            update_user_meta( $user_id, "shipping_state", $data->shipping->state );

            update_user_meta( $user_id, "imported", 1 );
            update_user_meta( $user_id, "imported_platform", 'zoho' );
            update_user_meta( $user_id, "imported_onboard", 1 );
            update_user_meta( $user_id, "imported_batch", $this->_batch );

            update_user_meta( $user_id, "zoho_id", $data->zoho->zoho_id );

        }

        return $user_id;

    }


}
