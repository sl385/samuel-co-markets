<?php 

Class Data_Importer_Class {

    var $data_from_csv;
    var $logger = [];
    var $_batch;

    public function __construct( $csv_data ) {
        $this->$data_from_csv = $csv_data;
        $this->batch = get_option('samco_import_batch_number');
        $this->_batch = ($this->batch+1);
    }

    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    public function email_exsits( $email ) {
        return email_exists( $email );
    }

    public function log( $title, $content ) {
        array_push( $this->logger, [
            "title" => $title,
            "content" => $content
        ]);
    }

    public function createNewWPUser( $email, $first_name, $surname = "" ) {




        if(!$surname) {

            // -- explode the name
            $name_parts = explode( " ", $first_name );
            // -- get the first name
            $first_name_part = $name_parts[0];
            // -- Replace the name with the first name
            $surname = str_replace( $first_name_part, "", $first_name);
            if( $first_name_part ) {
                $first_name = $first_name_part;
            }

            if( $first_name == $surname || !$first_name  ) {
                $original_login = strtolower( sanitize_title($first_name) );
            } else {
                $original_login = strtolower( sanitize_title($first_name) . "." . sanitize_title($surname));
            }

           
        } else {
            $original_login = strtolower(sanitize_title($first_name) . "." . sanitize_title($surname));
        }

        
        $user_login = $original_login;
        $i=0;
        do {
            //Check in the database here
            $exists = get_user_by( 'login', $user_login ) !== false;
            if($exists) {
                $i++;
                $user_login = $original_login . $i;
            }
        }  while($exists);

        if( $user_login == "." ) {
            $user_login = "usx-" . time();
        }


        $user_data = array(
            'user_login' => $user_login,
            //'user_email' => $email,
            'user_email' => $this->createRandomEmail(),
            'first_name' => $first_name,
            'last_name' => $surname,
            'display_name' => $first_name,
            'user_pass' => $this->randomPassword(),
            'role' => 'customer'
        );

        return wp_insert_user($user_data);

    }

    public function userObsifcationEmailCheck( $email ) {

        $user = get_users(array(
            'meta_key' => '_mail_obsifcation',
            'meta_value' => $email,
        ));

        if( !$user ) {
            return false;
        } else {
            return $user[0];
        }

    }

    public function createRandomEmail( $n = 20  ) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
    
        for ($i = 0; $i < $n; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
    
        return $randomString . '@test.com';
    }


    

}