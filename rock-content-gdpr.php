<?php
    /*
    Plugin Name: Rock Content GDPR
    Plugin URI: https://www.linkedin.com/in/diones-menqui-6a0616128/
    Description: Teste Técnico Desenvolvedor Wordpress
    Version: 1.0
    Author: Diones Ramos
    Text Domain: rock-content-gdpr
    Domain Path: /languages
    Author URI: https://www.linkedin.com/in/diones-menqui-6a0616128/
    License: GPL-2.0+
    License URI: http://www.gnu.org/licenses/gpl-2.0.txt
    */

    if ( ! defined( 'WPINC' ) ){
        die('Stop direct access');
    }

    /**
     *  Prefix of plugin
     */
    define( 'PREFIX', 'rc_gdpr_' );

    /**
     * Version
     */
    define( 'RC_GDPR_VERSION', '1.0.0' );

    /**
     * Dir PATH
     */
    define( 'RC_GDPR_DIR', plugin_dir_path( __FILE__ ) );

    /**
     * Dir URL
     */
    define( 'RC_GDPR_URL', plugin_dir_url(__FILE__) );

    /**
     * Dir Name/Slug used to definition like Domain Text
     */
    define( 'RC_GDPR_NAME', 'rock-content-gdpr' );

    /**
     * Activave Hook
     */
    function activate_rock_content(){
        require_once RC_GDPR_DIR . 'includes/class-rock-content-gdpr-activator.php';
        Rock_Content_GDPR_Activator::activate();
    }

    /**
     * Deactivate Hook
     */
    function deactivate_rock_content(){
        require_once RC_GDPR_DIR . 'includes/class-rock-content-gdpr-deactivator.php';
        Rock_Content_GDPR_Deactivator::deactivate();
    }

    /**
     * Register Hooks
     */
    register_activation_hook( __FILE__, 'activate_rock_content' );
    register_deactivation_hook( __FILE__, 'deactivate_rock_content' );

    /**
     * Require of core plugin class
     * Settings, Hooks, admin, public, render
     */
    require_once RC_GDPR_DIR . 'includes/class-rock-content-gdpr.php';

    /**
     * Run function to start all plugin funcionality
     */
    function run_rock_content_gdpr(){

        $all_action = new Rock_Content_GDPR();

    }
    run_rock_content_gdpr();

?>