<?php 

Class Import_Provider_Shopify extends Data_Importer_Class {

    var $data_to_import = [];
    var $encode;
 
    public function __construct( $csv_data, $encode = true ) {

        parent::__construct( $csv_data );

        $this->encode = $encode;

        $this->format_for_woo();
        if( $this->encode ) {
            $this->data_to_import = json_encode( $this->data_to_import );
        }
   
    }

    public function format_for_woo() {

        foreach( $this->$data_from_csv as $data ) {

            // -- All Records should have an email?
            if( $data['Email']) {
                if( !$this->customerExsits( $data['Email'] ) ) {
                    $this->addCustomer( $data );
                } 

                if( !$this->hasOrder( $data ) ) {
                    $this->addOrder($data);
                    $this->addOrderLineItem($data);
                } else {
                    $this->addOrderLineItem($data);
                }       

            }
           
        }

    }

    public function customerExsits( $email ) {
        return array_key_exists( $email, $this->data_to_import );
    }

    public function addCustomer( $data ) {

        $billing_name = explode(" ", $data['Billing Name']);

        if( sizeof($billing_name) > 0 ) {
            $raw = str_replace( $billing_name[0], "", $data['Billing Name']);
            $billing_name[0] = $raw;
            $billing_name[1] = "";
        }

        $this->data_to_import[$data['Email']] = [
            'email' => $data['Email'],
            'name' => [
                'first_name' => $billing_name[0],
                'second_name' => $billing_name[1],
                'email' => $data['Email']
            ],
            'billing' => [
                'street' => $data["Billing Street"],
                'address1' =>  $data["Billing Address1"],
                'address2' =>  $data["Billing Address2"],
                'company' => $data["Billing Company"],
                'city' => $data["Billing City"],
                'zip' =>  $data["Billing Zip"],
                'state' =>  $data["Billing Province"],
                'country' =>  $data["Billing Country"],
                'phone' => $data["Billing Phone"],
            ],
            'shipping' => [
                'street' => $data["Shipping Street"],
                'address1' =>  $data["Shipping Address1"],
                'address2' =>  $data["Shipping Address2"],
                'company' => $data["Shipping Company"],
                'city' => $data["Shipping City"],
                'zip' =>  $data["Shipping Zip"],
                'state' =>  $data["Shipping Province"],
                'country' =>  $data["Shipping Country"],
                'phone' => $data["Shipping Phone"],
            ],
            'marketing' => [
                'accepts_marketing' => $data["Accepts Marketing"]
            ]
        ];
    }

    public function hasOrder( $data ) {

        if( !isset($this->data_to_import[$data['Email']]['orders'][$data['Name']]) ) {
            return false;
        }

        return array_key_exists( $data['Name'], $this->data_to_import[$data['Email']]['orders'] );
    }

    public function addOrder( $data ) {
        $this->data_to_import[$data['Email']]['orders'][$data['Name']] = [
            'line_items' => [],
            'subtotal' => $data['Subtotal'],
            'currency' => $data['Currency'],
            'shipping' => $data['Shipping'],
            'discount_code' => $data['Discount Code'],
            'discount_amount' => $data['Discount Amount'],
            'shipping' => $data['Shipping'],
            'total' => $data['Total'],
            'taxes' => $data['Taxes'],
            'paid_at' => $data['Paid at'],
            'status' => $data['Financial Status'],
            'ordered_at' => $data['Created at'],
            'fufilled_at' => $data['Fulfilled at'],
            'notes' => $data['Notes'],
            'cancelled_at' => $data['Cancelled at']
        ];
    }

    public function addOrderLineItem( $data ) {

        $line_item = [
            'product' => $data['Lineitem name'],
            'price' => $data['Lineitem price'],
            'qty' => $data['Lineitem quantity'],
            'sku' => $data['Lineitem sku'],
            'fufilled' =>  $data['Lineitem fulfillment status'],
        ];

        $this->data_to_import[$data['Email']]['orders'][$data['Name']]['line_items'][] = $line_item;
    }

    public function saveToWoo( $test_mode ) {

        global $wpdb;


        if( $this->encode ) {
            $data = json_decode( $this->data_to_import );
        } else {
            $data = $this->data_to_import;
        }



            foreach( $data as $import_row ) {

  

                // -- Check if is a user
                $user = get_user_by( 'email', $import_row->email );
                if( !$user ) {

                   $user = $this->userObsifcationEmailCheck( $import_row->email );
                   
                }
               

                if( !$user ) {

                    $this->log("Creating",  "a new customer for: " . $import_row->email );

                    if( !$test_mode ) {
                        $user = $this->createWooCustomer( $import_row );
                        if ( is_wp_error( $user ) ) {
                            $this->log("Error",  "Failed to create new user : " . $user->get_error_message() );
                            $user = "";
                        } else {
                            $user_id = $user;
                        }
                      
                    }
                  
                 
                } else {
                    $user_id = $user->ID;
                }

                if( $user_id ) {
                    
                    foreach( $import_row->orders as $order_id => $value ) {

                        $find = $wpdb->get_row("SELECT id FROM " . $wpdb->prefix . "platform_orders WHERE wp_user_id = " . $user_id . " AND platform_order_id=" . str_replace("#","",$order_id) );
    
                        if( !$find ) {
    
                            if( !$test_mode ) {
                            // -- If not then we need to insert it
                                $wpdb->insert($wpdb->prefix . 'platform_orders', array(
                                    'platform_order_id' => str_replace("#","",$order_id),
                                    'platform' => 'shopify',
                                    'order_json' => json_encode($value),
                                    'order_date' => date("Y-m-d", strtotime($value->ordered_at)),
                                    'order_complete' => date("Y-m-d", strtotime($value->fufilled_at)),
                                    'order_status' => $value->status,
                                    'wp_user_id' =>  $user_id,
                                    'email' => $import_row->email,
                                    'batch' => $this->_batch
                                ));
                             }

                             $this->log("Importing", "Shopify Order Data for Order ID: " . $order_id );
    
                        }
                       
    
                    }
                }

             

            }

          
           
        }
        
    


    public function createWooCustomer( $import) {

        $user_id = $this->createNewWPUser( $import->email, $import->name->first_name, $import->name->second_name );
        
        if( is_wp_error( $user_id  ) ) {
            $this->log("Failure", "Failed to import user: " . $user_id->get_error_message() );
            return false;
        } 

        //$user_id = wc_create_new_customer( $import->email, wc_create_new_customer_username( $import->email, [$import->name->first_name,$import->name->second_name] ), $this->randomPassword() );
        if( $user_id ) {
            update_user_meta( $user_id, "_mail_obsifcation", $import->email );
            update_user_meta( $user_id, "first_name",  $import->name->first_name );
            update_user_meta( $user_id, "last_name", $import->name->second_name );
            update_user_meta( $user_id, "billing_first_name",  $import->name->first_name );
            update_user_meta( $user_id, "billing_last_name", $import->name->second_name );
           // update_user_meta( $user_id, "billing_company", $import->billing->second_name );
            update_user_meta( $user_id, "billing_address_1", $import->billing->address1 );
            update_user_meta( $user_id, "billing_address_2", $import->billing->address2 );
            update_user_meta( $user_id, "billing_city", $import->billing->city );
            update_user_meta( $user_id, "billing_postcode", $import->billing->zip );
            update_user_meta( $user_id, "billing_country", $import->billing->country );
            update_user_meta( $user_id, "billing_state", $import->billing->state );
            update_user_meta( $user_id, "billing_email", $import->email );
            update_user_meta( $user_id, "billing_phone", $import->billing->phone );
            
            update_user_meta( $user_id, "shipping_first_name",  $import->name->first_name );
            update_user_meta( $user_id, "shipping_last_name", $import->name->second_name );
            update_user_meta( $user_id, "shipping_company", $import->shipping->company );
            update_user_meta( $user_id, "shipping_address_1", $import->shipping->address1 );
            update_user_meta( $user_id, "shipping_address_2", $import->shipping->address2 );
            update_user_meta( $user_id, "shipping_city", $import->shipping->city );
            update_user_meta( $user_id, "billing_postcode", $import->shipping->zip );
            update_user_meta( $user_id, "shipping_country", $import->shipping->country );
            update_user_meta( $user_id, "shipping_state", $import->shipping->state );

            update_user_meta( $user_id, "imported", 1 );
            update_user_meta( $user_id, "imported_platform", 'shopify' );
            update_user_meta( $user_id, "imported_onboard", 1 );
            update_user_meta( $user_id, "imported_batch", $this->_batch );
            update_user_meta( $user_id, "sopify_marketing", $import->marketing->accepts_marketing);
        }

        return $user_id;

    }

}

