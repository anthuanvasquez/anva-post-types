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
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/class-anva-post-types-notices.php' );
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

	// Functions.
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/general.php' );
	
	// Load post types dependencies.
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/class-anva-post-types-portfolio.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/class-anva-post-types-gallery.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/class-anva-post-types-slideshow.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/class-anva-post-types-team.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/class-anva-post-types-event.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/class-anva-post-types-client.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/class-anva-post-types-service.php' );
	include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/class-anva-post-types-anime.php' );

	// Instance post types classes.
	Anva_Post_Types_Portfolio::get_instance();
	Anva_Post_Types_Gallery::get_instance();
	Anva_Post_Types_Event::get_instance();
	Anva_Post_Types_Team::get_instance();
	Anva_Post_Types_Client::get_instance();
	Anva_Post_Types_Service::get_instance();
	Anva_Post_Types_Slideshow::get_instance();
	Anva_Post_Types_Anime::get_instance();
	
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

