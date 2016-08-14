<?php

/**
 * Anva Post types textdomain.
 *
 * @since  1.0.0
 */
function anva_post_types_textdomain() {
	load_plugin_textdomain(
		'anva-post-types',
		false,
		dirname( plugin_basename( __FILE__ ) ) . '/languages'
	);
}
add_action( 'plugins_loaded', 'anva_post_types_textdomain' );

