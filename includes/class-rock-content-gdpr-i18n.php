<?php

if ( ! defined( 'WPINC' ) ){
    die('Stop direct access');
}
if ( !class_exists( 'Rock_Content_i18n' ) ) {

    class Rock_Content_i18n{

        /**
         * Load Text Domain
         * https://developer.wordpress.org/reference/functions/load_plugin_textdomain/
         */
        public function load_plugin_textdomain(){
            load_plugin_textdomain( RC_GDPR_NAME,false,dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
        }

    }
}