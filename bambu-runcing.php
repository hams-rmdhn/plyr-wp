<?php

/*
 * Plugin Name:       Bambu Runcing Security
 * Plugin URI:        https://studioinspirasi.com/product/bambu-runcing-security
 * Description:       Activate or deactivate feature for WordPress Security
 * Version:           1.0
 * Author:            Studio Inspirasi
 * Author URI:        https://studioinspirasi.com/
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; 
}

/** Includes */
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/updater.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/hooks.php';
require_once plugin_dir_path( __FILE__ ) . 'libs/options.php';

/** Register Activation & Deactivation Hook */
register_activation_hook( __FILE__, 'bambu_runcing_activation' );
register_deactivation_hook( __FILE__, 'bambu_runcing_deactivation' );

/** Action Links */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'add_bambu_runcing_action_links' );

/** Check Update */
add_filter( 'pre_set_site_transient_update_plugins', 'bambu_runcing_check_update' );
add_filter( 'plugins_api', 'bambu_runcing_info_screen', 10, 3 );

