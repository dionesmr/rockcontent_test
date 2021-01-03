<?php

if ( ! defined( 'WPINC' ) ){
    die('Stop direct access');
}
if ( !class_exists( 'Rock_Content_GDPR' ) ) {

    class Rock_Content_GDPR {
        private $rc_gdpr_slug;
        private $rc_gdpr_version;
        private $rc_gdpr_url;

        /**
         * Rock_Content_GDPR constructor.
         */
        public function __construct(){
            $this->rc_gdpr_version = RC_GDPR_VERSION;
            $this->rc_gdpr_slug = RC_GDPR_NAME;
            $this->rc_gdpr_url = RC_GDPR_URL;

            // Call to init
            $this->rc_gdpr_load_dependencies();
            $this->rc_gdpr_set_locale();
            $this->rc_gdpr_define_admin_hooks();
            $this->rc_gdpr_define_public_hooks();
            $this->rc_gdpr_call_front();
        }

        /**
         * Load all dependencies
         */
        private function rc_gdpr_load_dependencies(){
            require_once RC_GDPR_DIR . 'includes/class-rock-content-gdpr-i18n.php';
            require_once RC_GDPR_DIR . 'includes/class-rock-content-gdpr-seetings.php';
            require_once RC_GDPR_DIR . 'includes/class-rock-content-gdpr-front.php';
        }

        /**
         * Call locale Class
         */
        private function rc_gdpr_set_locale(){
            $rc_gdpr_i18n = new Rock_Content_i18n();

            // Plugin Loaded action to call i18n
            add_action( 'plugin_loaded', array( $rc_gdpr_i18n, 'load_plugin_textdomain' ) );
        }

        /**
         * Public hooks
         */
        private function rc_gdpr_define_public_hooks(){

            //Enqueue Action
            add_action( 'wp_enqueue_scripts', array( $this, 'rc_gdpr_public_enqueue' ) );
        }

        /**
         * Public Enqueues
         */
        public function rc_gdpr_public_enqueue(){

            // Enqueue Front Style
            wp_enqueue_style('rock-content-gdpr', $this->rc_gdpr_url . 'public/css/rock-content-gdpr.min.css', array(), $this->rc_gdpr_version );

            // Enqueue Front Script
            wp_enqueue_script('rock-content-gdpr',$this->rc_gdpr_url . 'public/js/rock-content-gdpr.min.js', array(),$this->rc_gdpr_version ,true );
        }

        /**
         * Admin Hooks
         */
        private function rc_gdpr_define_admin_hooks(){
            $settings = new Rock_Content_GDPR_Settings();

            // Admin Enqueue Action
            add_action( 'admin_enqueue_scripts', array( $this, 'rc_gdpr_admin_enqueue' ) );
        }

        /**
         * @param $hook
         * Admin Enqueues
         */
        public function rc_gdpr_admin_enqueue($hook) {

            // If is not plugin setting page return
            if ( 'settings_page_' . $this->rc_gdpr_slug != $hook ) {
                return;
            }

            // Enque Admin Style
            wp_enqueue_style('rock-content-gdpr-admin', $this->rc_gdpr_url . 'admin/css/rock-content-gdpr-admin.min.css', array(), $this->rc_gdpr_version );
        }

        /**
         * Render front Contents
         */
        public function rc_gdpr_call_front(){
            $render = new Rock_Content_GDPR_Front();

            if(!is_admin()){
                // Render Front Call
                $render->rc_gdpr_hook_render();
            }

        }

        /**
         * @return string
         * Getter Version
         */
        public function get_rc_gdpr_version(){
            return $this->rc_gdpr_version;
        }

        /**
         * @return string
         * Getter Slug
         */
        public function get_rc_gdpr_slug(){
            return $this->rc_gdpr_slug;
        }
    }
}