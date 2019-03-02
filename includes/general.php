<?php

/**
 * Post types list to register.
 *
 * @since  1.0.0
 * @return array $post_types
 */
function anva_post_types_list() {
	$post_types = array();
	return apply_filters( 'anva_post_types_list', $post_types );
}

/**
 * Post types check.
 *
 * @since  1.0.0
 * @param  string  $post_type
 * @return boolean
 */
function anva_post_types_is_active( $post_type ) {

	$post_types = anva_post_types_list();

	// Check if there is post types to register.
	if ( empty( $post_types ) ) {
		return false;
	}

	// Register post types in the list.
	if ( in_array( $post_type, $post_types ) ) {
		return true;
	}
}

/**
 * Anva Post types textdomain.
 *
 * @since 1.0.0
 */
function anva_post_types_textdomain() {
	load_plugin_textdomain(
		'anva-post-types',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'init', 'anva_post_types_textdomain' );

