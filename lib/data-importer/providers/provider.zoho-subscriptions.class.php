<?php 

Class Import_Provider_Zoho_Subscriptions extends Data_Importer_Class {

    var $data_to_import = [];
    var $encode;

    public function __construct( $csv_data, $encode = true ) {

        parent::__construct( $csv_data );

        $this->encode = $encode;

        print_r( $this->data_to_import );

    }


}
