<?php

add_action( 'init', 'anva_gallery_register' );
add_action( 'init', 'anva_gallery_taxonomy' );
add_action( 'manage_galleries_posts_custom_column', 'anva_gallery_add_columns', 10, 2 );
add_filter( 'manage_edit-galleries_columns', 'anva_gallery_columns' );

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
		'name'               	 => __( 'Galleries',                   'anva' ),
		'singular_name'      	 => __( 'Gallery',                     'anva' ),
		'menu_name'          	 => __( 'Galleries',                   'anva' ),
		'name_admin_bar'     	 => __( 'Gallery',                     'anva' ),
		'add_new'            	 => __( 'Add New',                     'anva' ),
		'add_new_item'       	 => __( 'Add New Gallery',             'anva' ),
		'edit_item'          	 => __( 'Edit Gallery',                'anva' ),
		'new_item'           	 => __( 'New Gallery',                 'anva' ),
		'view_item'          	 => __( 'View Gallery',                'anva' ),
		'search_items'       	 => __( 'Search Galleries',            'anva' ),
		'not_found'          	 => __( 'No galleries found',          'anva' ),
		'not_found_in_trash' 	 => __( 'No galleries found in trash', 'anva' ),
		'all_items'          	 => __( 'All Galleries',               'anva' ),
	);

	$args = array(
		'labels'							 => $labels,
		'description'          => '',
		'public'               => true,
		'publicly_queryable'   => true,
		'show_in_nav_menus'    => false,
		'show_in_admin_bar'    => true,
		'exclude_from_search'  => false,
		'show_ui'              => true,
		'show_in_menu'         => true,
		'menu_position'        => 20,
		'menu_icon'            => 'dashicons-format-gallery',
		'can_export'           => true,
		'delete_with_user'     => false,
		'hierarchical'         => false,
		'has_archive'          => 'galleries',
		'query_var'            => 'gallery',
		'rewrite' 						 => array(
			'slug'       			 	 => 'galleries',
			'with_front' 			 	 => false,
			'pages'      			 	 => true,
			'feeds'      			 	 => true,
			'ep_mask'    			 	 => EP_PERMALINK,
		),
		'supports' 						 => array( 'title', 'editor', 'thumbnail' ),
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
		'name'                => __( 'Gallery Categories', 		 'anva' ),
		'singular_name'       => __( 'Gallery Category',   		 'anva' ),
		'menu_name'           => __( 'Categories',             'anva' ),
		'name_admin_bar'      => __( 'Category',               'anva' ),
		'search_items'        => __( 'Search Categories',      'anva' ),
		'popular_items'       => __( 'Popular Categories',     'anva' ),
		'all_items'           => __( 'All Categories',         'anva' ),
		'edit_item'           => __( 'Edit Category',          'anva' ),
		'view_item'           => __( 'View Category',          'anva' ),
		'update_item'         => __( 'Update Category',        'anva' ),
		'add_new_item'        => __( 'Add New Category',       'anva' ),
		'new_item_name'       => __( 'New Category Name',      'anva' ),
		'parent_item'         => __( 'Parent Category',        'anva' ),
		'parent_item_colon'   => __( 'Parent Category:',       'anva' ),
		'add_or_remove_items' => null,
		'not_found'           => null,
	);

	$args = array(
		'labels'							=> $labels,		
		'public'            	=> true,
		'show_ui'           	=> true,
		'show_in_nav_menus' 	=> true,
		'show_tagcloud'     	=> true,
		'show_admin_column' 	=> true,
		'hierarchical'      	=> true,
		'query_var'         	=> 'gallery_cat',
		'rewrite' 						=> array(
			'slug'         			=> 'gallery/category',
			'with_front'   			=> false,
			'hierarchical' 			=> true,
			'ep_mask'      			=> EP_NONE
		),
	);
		
	register_taxonomy(
		'gallery_cat',
		'galleries',
		$args
	);

}