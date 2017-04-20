<?php
/*
Plugin Name: Anva Post Types
Description: This plugin works in conjuction with the Anva Framework to create Custom Post Types for use with the framework to generate content.
Version: 1.0.0
Author: Anthuan Vásquez
Author URI: http://anthuanvasquez.net
License: GPL2
Text Domain: anva-post-types
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

	// Include helpers.
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/helpers.php' );

	// Error handling.
	$notices = Anva_Post_Types_Notices::get_instance();

	// Stop plugin from running.
	if ( $notices->do_stop() ) {
		return;
	}

	// Chech if post types list is defined.
	$post_types = anva_post_types_list();

	if ( empty( $post_types ) ) {
		return;
	}

	// General
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/general.php' );
	
	// Load post types dependencies
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/portfolio-post-type.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/gallery-post-type.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/event-post-type.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/team-post-type.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/client-post-type.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/service-post-type.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/slideshow-post-type.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/anime-post-type.php' );

	// Instance post types classes
	$portfolio = Anva_Post_Types_Portfolio::get_instance();
	$gallery   = Anva_Post_Types_Gallery::get_instance();
	$event     = Anva_Post_Types_Event::get_instance();
	$team      = Anva_Post_Types_Team::get_instance();
	$client    = Anva_Post_Types_Client::get_instance();
	$service   = Anva_Post_Types_Service::get_instance();
	$slideshow = Anva_Post_Types_Slideshow::get_instance();
	$slideshow = Anva_Post_Types_Anime::get_instance();
	
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

