<?php
/*
Plugin Name: Anva Post Types
Description: This plugin works in conjuction with the Anva Framework to create Custom Post Types for use with the framework to generate content.
Version: 1.0.0
Author: Anthuan Vásquez
Author URI: http://anthuanvasquez.net
License: GPL2
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Constants
define( 'ANVA_POST_TYPES_PLUGIN_VERSION', '1.0.0' );
define( 'ANVA_POST_TYPES_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ANVA_POST_TYPES_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

/**
 * Init post types plugin.
 * 
 * @since 1.0.0
 */
function anva_post_types_init() {

	// Include helpers
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/helpers.php' );

	// Error handling
	$notices = Anva_Post_Types_Notices::get_instance();

	// Stop plugin from running
	if ( $notices->do_stop() ) {
		return;
	}

	// Chech if post types used is defined
	if ( ! defined( 'ANVA_POST_TYPES_USED' ) ) {
		return;
	}
	
	// Load post types dependencies
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/portfolio-post-type.php' );	
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/gallery-post-type.php'   );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/slideshow-post-type.php' );

	// Instance post types classes
	$portfolio = Anva_Post_Types_Portfolio::get_instance();
	$gallery   = Anva_Post_Types_Gallery::get_instance();
	$slideshow = Anva_Post_Types_Slideshow::get_instance();
	
}
add_action( 'after_setup_theme', 'anva_post_types_init' );

/**
 * Clear the permalinks.
 *
 * @since  1.0.0
 */
function anva_post_types_rules() {
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'anva_post_types_rules' );
register_deactivation_hook( __FILE__, 'anva_post_types_rules' );

