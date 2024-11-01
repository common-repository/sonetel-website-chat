<?php
/*
Plugin Name: Sonetel website chat
Plugin URI: https://sonetel.com/wordpress
Description: Sonetel's free AI assisted website chat and call service for your business saves time, makes your business look professional and increases sales.
Version: 1.0.7
Author: Sonetel
Author URI: https://sonetel.com
License: GPLv2 or later
*/

define( 'WP_SONETEL_VERSION', '1.0.7' );
define( 'WP_SONETEL__MINIMUM_WP_VERSION', '4.0' );
define( 'WP_SONETEL__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WP_SONETEL__URL_SONETEL_LOGIN', 'https://app.sonetel.com' );

require_once( WP_SONETEL__PLUGIN_DIR . 'class.wp_sonetel.php' );

$wp_sonetel = new Wp_sonetel();

register_activation_hook( __FILE__, array( 'Wp_sonetel', 'activate_wp_sonetel' ) );
register_deactivation_hook( __FILE__, array( 'Wp_sonetel', 'deactivate_wp_sonetel' ) );


