<?php

add_action( 'init', 'anva_slideshow_register' );
add_action( 'init', 'anva_slideshow_taxonomy' );
add_action( 'manage_slideshows_posts_custom_column', 'anva_slideshow_add_columns', 10, 2 );
add_filter( 'manage_edit-slideshows_columns', 'anva_slideshow_columns' );
add_filter( 'post_updated_messages', 'anva_slideshow_update_messages' );

/**
 * Add columns to post type admin
 * 
 * @since 1.0.0
 */
function anva_slideshow_add_columns( $column, $post_id ) {

	global $post;
	
	switch ( $column ) {
		case 'image':
			$edit_link = get_edit_post_link( $post->ID );
			echo '<a href="' . esc_url( $edit_link ) . '" title="' . esc_attr( $post->post_title ) . '">' . get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'alt' => $post->post_title  )  ) . '</a>';
			break;

		case 'slideshow_group':
			$terms = get_the_terms( $post->ID, 'slideshow_group' );

			if ( ! empty( $terms ) ) {

				$output = array();

				foreach ( $terms as $term ) {
					$output[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => 'slideshows', 'slideshow_group' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'slideshow_group', 'display' ) )
					);
				}

				echo join( ', ', $output );
			} else {
				_e( 'No slideshow Groups', 'anva' );
			}

			break;
	}
	
}

/**
 * Create columns for post type admin
 * 
 * @since 1.0.0
 */
function anva_slideshow_columns( $columns ) {

	$columns = array(
		'cb'    					=> '<input type="checkbox" />',
		'image' 					=> __( 'Featured Image', 'anva' ),
		'title' 					=> __( 'Title', 'anva' ),
		'slideshow_group' => __( 'Groups' ),
		'date'  					=> __( 'Date', 'anva' )
	);

	return $columns;
}

/**
 * Slideshow messages
 *
 * @since 1.0.0
 */
function anva_slideshow_update_messages( $messages ){
				
	global $post;
		
	$messages['slideshows'] = array(
		0 	=> '', // Unused. Messages start at index 1.
		1 	=> __( 'Slide updated.', 'anva' ),
		2 	=> __( 'Slide field updated.', 'anva' ),
		3 	=> __( 'Slide field deleted.', 'anva' ),
		4 	=> __( 'Slide updated.', 'anva' ),
		5 	=> isset( $_GET['revision'] ) ? sprintf( __( 'Slide restored to revision from %s', 'anva' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 	=> __( 'Slide published.', 'anva' ),
		7 	=> __( 'Slide saved.', 'anva' ),
		8 	=> __( 'Slide submitted.', 'anva' ),
		9 	=> sprintf( __( 'Slide scheduled for: <strong>%1$s</strong>.' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
		10 	=> __( 'Slide draft updated.', 'anva' ),
	);
	
	return $messages;
}

/**
 * Register post type
 * 
 * @since 1.0.0
 */
function anva_slideshow_register() {

	$labels = array(
		'name'               	 => __( 'Slideshows',                   'anva' ),
		'singular_name'      	 => __( 'Slideshow',                    'anva' ),
		'menu_name'          	 => __( 'Slideshows',                   'anva' ),
		'name_admin_bar'     	 => __( 'Slideshow',                    'anva' ),
		'add_new'            	 => __( 'Add New',                     	'anva' ),
		'add_new_item'       	 => __( 'Add New Slide',            		'anva' ),
		'edit_item'          	 => __( 'Edit Slide',               		'anva' ),
		'new_item'           	 => __( 'New Slide',                		'anva' ),
		'view_item'          	 => __( 'View Slide',               		'anva' ),
		'search_items'       	 => __( 'Search slides',            		'anva' ),
		'not_found'          	 => __( 'No slides found',          		'anva' ),
		'not_found_in_trash' 	 => __( 'No slides found in trash', 		'anva' ),
		'all_items'          	 => __( 'All Slides',               		'anva' ),
	);

	$args = array(
		'labels'							 => $labels,
		'description'          => '',
		'public'               => false,
		'publicly_queryable'   => false,
		'show_in_nav_menus'    => false,
		'show_in_admin_bar'    => true,
		'exclude_from_search'  => false,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'menu_position'        => 20,
		'menu_icon'            => 'dashicons-images-alt2',
		'can_export'           => true,
		'delete_with_user'     => false,
		'hierarchical'         => false,
		'has_archive'          => false,
		'query_var'            => 'slideshow',
		'supports' 						 => array( 'title', 'thumbnail' ),
	);

	register_post_type( 'slideshows', $args );

}

/**
 * Register taxonomy
 * 
 * @since 1.0.0
 */
function anva_slideshow_taxonomy() {

	$labels = array(
		'name'                => __( 'Slideshow Groups', 		'anva' ),
		'singular_name'       => __( 'Slideshow Group',   	'anva' ),
		'menu_name'           => __( 'Groups',             	'anva' ),
		'name_admin_bar'      => __( 'Group',               'anva' ),
		'search_items'        => __( 'Search Group',      	'anva' ),
		'popular_items'       => __( 'Popular Groups',     	'anva' ),
		'all_items'           => __( 'All Groups',         	'anva' ),
		'edit_item'           => __( 'Edit Group',          'anva' ),
		'view_item'           => __( 'View Group',          'anva' ),
		'update_item'         => __( 'Update Group',        'anva' ),
		'add_new_item'        => __( 'Add New Group',       'anva' ),
		'new_item_name'       => __( 'New Group Name',      'anva' ),
		'parent_item'         => __( 'Parent Group',        'anva' ),
		'parent_item_colon'   => __( 'Parent Group:',       'anva' ),
		'add_or_remove_items' => null,
		'not_found'           => null,
	);

	$args = array(
		'labels'							=> $labels,		
		'public'            	=> false,
		'show_ui'           	=> true,
		'show_in_nav_menus' 	=> true,
		'show_tagcloud'     	=> true,
		'show_admin_column' 	=> true,
		'hierarchical'      	=> true,
		'query_var'         	=> 'slideshow_group',
	);
		
	register_taxonomy(
		'slideshow_group',
		'slideshows',
		$args
	);

}