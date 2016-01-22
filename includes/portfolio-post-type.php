<?php

add_action( 'init', 'anva_portfolio_register' );
add_action( 'init', 'anva_portfolio_taxonomy' );
add_action( 'manage_portfolio_posts_custom_column', 'anva_portfolio_add_columns', 10, 2 );
add_filter( 'manage_edit-portfolio_columns', 'anva_portfolio_columns' );

/**
 * Add columns to post type admin
 * 
 * @since 1.0.0
 */
function anva_portfolio_add_columns( $column, $post_id ) {

	global $post;
	
	switch ( $column ) {
		case 'image':
			$edit_link = get_edit_post_link( $post->ID );
			echo '<a href="' . esc_url( $edit_link ) . '" title="' . esc_attr( $post->post_title ) . '">' . get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'alt' => $post->post_title  )  ) . '</a>';
			break;

		case 'portfolio_type':
			$terms = get_the_terms( $post->ID, 'portfolio_type' );

			if ( ! empty( $terms ) ) {

				$output = array();

				foreach ( $terms as $term ) {
					$output[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => 'portfolio', 'portfolio_type' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'portfolio_type', 'display' ) )
					);
				}

				echo join( ', ', $output );
			} else {
				_e( 'No Types', 'anva' );
			}

			break;

			case 'portfolio_skill':
			$terms = get_the_terms( $post->ID, 'portfolio_skill' );

			if ( ! empty( $terms ) ) {

				$output = array();

				foreach ( $terms as $term ) {
					$output[] = sprintf( '<a href="%s">%s</a>',
						esc_url( add_query_arg( array( 'post_type' => 'portfolio', 'portfolio_skill' => $term->slug ), 'edit.php' ) ),
						esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'portfolio_type', 'display' ) )
					);
				}

				echo join( ', ', $output );
			} else {
				_e( 'No Skills', 'anva' );
			}

			break;
	}
	
}

/**
 * Create columns for post type admin
 * 
 * @since 1.0.0
 */
function anva_portfolio_columns( $columns ) {

	$columns = array(
		'cb'    						=> '<input type="checkbox" />',
		'image' 						=> __( 'Featured Image', 'anva' ),
		'title' 						=> __( 'Title', 'anva' ),
		'portfolio_type' 		=> __( 'Types' ),
		'portfolio_skill' 	=> __( 'Skills' ),
		'date'  						=> __( 'Date', 'anva' )
	);

	return $columns;
}

/**
 * Register post type
 * 
 * @since 1.0.0
 */
function anva_portfolio_register() {

	$labels = array(
		'name'               	 => __( 'Portfolio',                   			'anva' ),
		'singular_name'      	 => __( 'Portfolio',                    		'anva' ),
		'menu_name'          	 => __( 'Portfolio',                   			'anva' ),
		'name_admin_bar'     	 => __( 'Portfolio',                    		'anva' ),
		'add_new'            	 => __( 'Add New',                     			'anva' ),
		'add_new_item'       	 => __( 'Add New Item',            					'anva' ),
		'edit_item'          	 => __( 'Edit Item',               					'anva' ),
		'new_item'           	 => __( 'New Item',                					'anva' ),
		'view_item'          	 => __( 'View Item',               					'anva' ),
		'search_items'       	 => __( 'Search portfolio items',           'anva' ),
		'not_found'          	 => __( 'No portfolio item found',          'anva' ),
		'not_found_in_trash' 	 => __( 'No portfolio item found in trash', 'anva' ),
		'all_items'          	 => __( 'All Items',               					'anva' ),
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
		'menu_icon'            => 'dashicons-portfolio',
		'can_export'           => true,
		'delete_with_user'     => false,
		'hierarchical'         => false,
		'has_archive'          => 'portfolio',
		'query_var'            => 'portfolio',
		'rewrite' 						 => array(
			'slug'       			 	 => 'portfolio',
			'with_front' 			 	 => false,
			'pages'      			 	 => true,
			'feeds'      			 	 => true,
			'ep_mask'    			 	 => EP_PERMALINK,
		),
		'supports' 						 => array( 'title', 'editor', 'thumbnail' ),
	);

	register_post_type( 'portfolio', $args );

}

/**
 * Register taxonomy
 * 
 * @since 1.0.0
 */
function anva_portfolio_taxonomy() {

	$type_labels = array(
		'name'                => __( 'Portfolio Types', 		'anva' ),
		'singular_name'       => __( 'Portfolio Type',   		'anva' ),
		'menu_name'           => __( 'Types',             	'anva' ),
		'name_admin_bar'      => __( 'Type',               	'anva' ),
		'search_items'        => __( 'Search Type',      		'anva' ),
		'popular_items'       => __( 'Popular Types',     	'anva' ),
		'all_items'           => __( 'All Types',         	'anva' ),
		'edit_item'           => __( 'Edit Type',          	'anva' ),
		'view_item'           => __( 'View Type',          	'anva' ),
		'update_item'         => __( 'Update Type',        	'anva' ),
		'add_new_item'        => __( 'Add New Type',       	'anva' ),
		'new_item_name'       => __( 'New Type Name',      	'anva' ),
		'parent_item'         => __( 'Parent Type',        	'anva' ),
		'parent_item_colon'   => __( 'Parent Type:',       	'anva' ),
		'add_or_remove_items' => null,
		'not_found'           => null,
	);

	$type_args = array(
		'labels'							=> $type_labels,		
		'public'            	=> false,
		'show_ui'           	=> true,
		'show_in_nav_menus' 	=> true,
		'show_tagcloud'     	=> true,
		'show_admin_column' 	=> true,
		'hierarchical'      	=> true,
		'query_var'         	=> 'portfolio_type',
	);
		
	register_taxonomy(
		'portfolio_type',
		'portfolio',
		$type_args
	);

	$skill_labels = array(
		'name'                => __( 'Portfolio Skills', 			'anva' ),
		'singular_name'       => __( 'Portfolio Skill',   		'anva' ),
		'menu_name'           => __( 'Skills',             		'anva' ),
		'name_admin_bar'      => __( 'Skill',               	'anva' ),
		'search_items'        => __( 'Search Skill',      		'anva' ),
		'popular_items'       => __( 'Popular Skills',     		'anva' ),
		'all_items'           => __( 'All Skills',         		'anva' ),
		'edit_item'           => __( 'Edit Skill',          	'anva' ),
		'view_item'           => __( 'View Skill',          	'anva' ),
		'update_item'         => __( 'Update Skill',        	'anva' ),
		'add_new_item'        => __( 'Add New Skill',       	'anva' ),
		'new_item_name'       => __( 'New Skill Name',      	'anva' ),
		'parent_item'         => __( 'Parent Skill',        	'anva' ),
		'parent_item_colon'   => __( 'Parent Skill:',       	'anva' ),
		'add_or_remove_items' => null,
		'not_found'           => null,
	);

	$skill_args = array(
		'labels'							=> $skill_labels,		
		'public'            	=> false,
		'show_ui'           	=> true,
		'show_in_nav_menus' 	=> true,
		'show_tagcloud'     	=> true,
		'show_admin_column' 	=> true,
		'hierarchical'      	=> false,
		'query_var'         	=> 'portfolio_skill',
	);
		
	register_taxonomy(
		'portfolio_skill',
		'portfolio',
		$skill_args
	);

}