<?php
/*
Plugin Name: Anva Post Types
Description: This plugin works in conjuction with the Anva Framework to create Custom Post Types for use with the framework to generate content.
Version: 1.0.0
Author: Anthuan Vásquez
Author URI: http://anthuanvasquez.net
License: GPL2

	Copyright 2016  Anva Framework

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License version 2,
	as published by the Free Software Foundation.

	You may NOT assume that you can use any other version of the GPL.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	The license for this software can likely be found here:
	http://www.gnu.org/licenses/gpl-2.0.html

*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ANVA_POST_TYPES_PLUGIN_VERSION', '1.0.0' );
define( 'ANVA_POST_TYPES_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'ANVA_POST_TYPES_PLUGIN_URI', plugins_url( '' , __FILE__ ) );

/**
 * Get Custom Post Types
 *
 * @since 1.0.0
 */
function anva_get_post_types() {
	$post_types = array(
		'galleries',
		'portfolio',
		'slideshows',
	);
	return $post_types;
}

/**
 * Run Post Types
 *
 * @since 1.0.0
 */
include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/portfolio-post-type.php' );
include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/gallery-post-type.php' 	 );
include_once( ANVA_POST_TYPES_PLUGIN_DIR . '/includes/slideshow-post-type.php' );


/**
 * Clear the permalinks
 *
 * @since  1.0.0
 */
function anva_post_types_rules() {
	anva_gallery_register();
	anva_slideshow_register();
	anva_portfolio_register();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'anva_post_types_rules' );
register_deactivation_hook( __FILE__, 'anva_post_types_rules' );

