<?php

/**
 * Add columns to post type admin
 * 
 * @since 1.0.0
 */
function anva_gallery_add_columns( $column, $post_id ) {

	global $post;
	
	switch ( $column ) {
		case 'image':
			$edit_link = get_edit_post_link( $post->ID );
			echo '<a href="' . esc_url( $edit_link ) . '" title="' . esc_attr( $post->post_title ) . '">' . get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'alt' => $post->post_title  )  ) . '</a>';
			break;

		case 'gallery_cat':
			$terms = get_the_terms( $post->ID, 'gallery_cat' );

			if ( ! empty( $terms ) ) {

				$output = array();

				foreach ( $terms as $term ) {
					$output[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => 'galleries', 'gallery_cat' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'gallery_cat', 'display' ) )
					);
				}

				echo join( ', ', $output );
			} else {
				_e( 'No Gallery Categories', 'anva' );
			}

			break;
	}
	
}

/**
 * Create columns for post type admin
 * 
 * @since 1.0.0
 */
function anva_gallery_columns( $columns ) {

	$columns = array(
		'cb'    			=> '<input type="checkbox" />',
		'image' 			=> __( 'Featured Image', 'anva' ),
		'title' 			=> __( 'Title', 'anva' ),
		'gallery_cat' => __( 'Groups' ),
		'date'  			=> __( 'Date', 'anva' )
	);

	return $columns;
}

/**
 * Register post type
 * 
 * @since 1.0.0
 */
function anva_gallery_register() {

	$labels = array(
		'name' 								 => __( 'Galleries', 'anva' ),
		'singular_name' 			 => __( 'Gallery', 'anva' ),
		'all_items' 					 => __( 'All Galleries', 'anva' ),
		'add_new' 						 => __( 'Add New Gallery', 'anva' ),
		'add_new_item' 				 => __( 'Add New Gallery', 'anva' ),
		'edit_item' 					 => __( 'Edit Gallery', 'anva' ),
		'new_item' 						 => __( 'New Gallery', 'anva' ),
		'view_item' 					 => __( 'View Gallery', 'anva' ),
		'search_items' 				 => __( 'Search Galleries', 'anva' ),
		'not_found' 					 => __( 'No Gallery found', 'anva' ),
		'not_found_in_trash' 	 => __( 'No Gallery found in Trash', 'anva' )
	);
	
	$args = array(
		'public'               => true,
		'labels'               => $labels,
		'can_export' 					 => true,
		'show_ui'              => true,
		'show_in_nav_menus'    => true,
		'publicly_queryable' 	 => true,
		'exclude_from_search'  => false,
		'rewrite'              => true,
		'capability_type'      => 'post',
		'hierarchical'         => false,
		'menu_position'        => 20,
		'menu_icon'						 => 'dashicons-format-gallery',
		'supports'             => array( 'title', 'editor', 'thumbnail' ),
		'rewrite' 						 => array( 'slug' => 'galleries', 'with_front' => true ),
	);

	register_post_type( 'galleries', $args );

}

/**
 * Register taxonomy
 * 
 * @since 1.0.0
 */
function anva_gallery_taxonomy() {

	$labels = array(
		'name' 								=> __( 'Gallery Categories', 'anva' ),
		'singular_name' 			=> __( 'Gallery Category', 'anva' ),
		'search_items' 				=> __( 'Search Gallery Categories', 'anva' ),
		'all_items' 					=> __( 'All Gallery Categorys', 'anva' ),
		'parent_item' 				=> __( 'Parent Gallery Category', 'anva' ),
		'parent_item_colon' 	=> __( 'Parent Gallery Category:', 'anva' ),
		'edit_item' 					=> __( 'Edit Gallery Category', 'anva' ), 
		'update_item' 				=> __( 'Update Gallery Category', 'anva' ),
		'add_new_item' 				=> __( 'Add New Gallery Category', 'anva' ),
		'new_item_name' 			=> __( 'New Gallery Category Name', 'anva' ),
	); 							  
		
	register_taxonomy(
		'gallery_cat',
		'galleries',
		array(
			'public'            => true,
			'labels'						=> $labels,
			'hierarchical' 			=> true,
			'show_ui' 					=> true,
			'query_var'         => true,
			'rewrite' 					=> array( 'slug' => 'gallery_cat' ),
		)
	);

}