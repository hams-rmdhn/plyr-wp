<?php

/*
 * Plugin Name:       Inspirasi Player
 * Plugin URI:        https://studioinspirasi.com/product/inspirasi-player
 * Description:       Better player for Youtube videos
 * Version:           2.0
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
register_activation_hook( __FILE__, 'plyr_wp_activation' );
register_deactivation_hook( __FILE__, 'plyr_wp_deactivation' );

/** Action Links */
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'add_plyr_wp_action_links' );

/** Check Update */
add_filter( 'pre_set_site_transient_update_plugins', 'plyr_wp_check_update' );
add_filter( 'plugins_api', 'plyr_wp_info_screen', 10, 3 );
