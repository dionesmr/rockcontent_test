<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/**
 * Plugin Name/Slug
 */
$option_name = 'rock-content-gdpr';

/**
 * Function to delete all options
 */
delete_option($option_name);