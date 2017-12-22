<?php

/**
 * Display warning telling the user they must have a
 * theme with Theme Blvd framework v2.2+ installed in
 * order to run this plugin.
 *
 * @global $current_user
 *
 * @since 1.0.0
 */
function anva_post_types_warning() {

	global $current_user;

	if ( ! get_user_meta( $current_user->ID, 'anva-nag-post-types-no-framework' ) ) {
		printf(
			'<div class="updated">%s %s</div>',
			sprintf(
				'<p><strong>Anva Post Types: </strong>%s</p>',
				esc_html__( 'You are not using a theme with the Anva Framework, and so this plugin will not do anything.', 'anva-post-types' )
			),
			sprintf(
				'<p><a href="%s">%s</a> | <a href="https://anthuanvasquez.net" target="_blank">%s</a></p>',
				esc_url( anva_post_types_disable_url( 'post-types-no-framework' ) ),
				esc_html__( 'Dismiss this notice', 'anva-post-types' ),
				esc_html__( 'Visit Anthuanvasquez.net', 'anva-post-types' )
			)
		);
	}
}

/**
 * Dismiss an admin notice.
 *
 * @global $current_user
 *
 * @since 1.0.0
 */
function anva_post_types_disable_nag() {

	global $current_user;

	// WPCS: input var okay.
	if ( ! isset( $_GET['nag-ignore'] ) ) {
		return;
	}

	// WPCS: input var okay. sanitization ok.
	if ( strpos( wp_unslash( $_GET['nag-ignore'] ), 'anva-nag-' ) !== 0 ) {
		return;
	}

	// WPCS: input var okay. sanitization ok.
	if ( isset( $_GET['security'] ) && wp_verify_nonce( wp_unslash( $_GET['security'] ), 'anva-post-types-nag' ) ) {
		// WPCS: input var okay. sanitization ok.
		add_user_meta( $current_user->ID, wp_unslash( $_GET['nag-ignore'] ), 'true', true );
	}
}

/**
 * Disable admin notice URL.
 *
 * @since 1.0.0
 * @param string $id ID of nag to disable.
 */
function anva_post_types_disable_url( $id ) {

	global $pagenow;

	$url = admin_url( $pagenow );

	// WPCS: input var okay.
	if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
		// WPCS: input var okay. sanitization ok.
		$url .= sprintf( '?%s&nag-ignore=%s', wp_unslash( $_SERVER['QUERY_STRING'] ), 'anva-nag-' . $id ); 
	} else {
		$url .= sprintf( '?nag-ignore=%s', 'anva-nag-' . $id );
	}

	$url .= sprintf( '&security=%s', wp_create_nonce( 'anva-post-types-nag' ) );

	return $url;
}

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

