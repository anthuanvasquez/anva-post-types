<?php

class Anva_Post_Types_Event
{
	/**
     * A single instance of this class.
     *
     * @since 1.0.0
     */
    private static $instance = NULL;
	
	/**
	 * Post type slug.
	 * 
	 * @var string
	 */
	private $post_type = 'events';

	/**
	 * Taxonomy slug.
	 * 
	 * @var string
	 */
	private $taxonomy = 'event_cat';

	/**
     * Creates or returns an instance of this class.
     *
     * @since 1.0.0
     * @return A single instance of this class.
     */
    public static function get_instance()
    {
        if ( self::$instance == NULL ) {
            self::$instance = new self;
        }

        return self::$instance;
    }
	
	private function __construct()
	{
		if ( anva_post_types_is_used( $this->post_type ) ) {
			add_action( 'init', array( $this, 'register' ) );
			add_action( 'init', array( $this, 'taxonomy' ) );
			add_action( 'manage_' . $this->post_type . '_posts_custom_column', array( $this, 'add_columns' ), 10, 2 );
			add_filter( 'manage_edit-' . $this->post_type . '_columns', array( $this, 'columns' ) );
		}
		
	}

	/**
	 * Add columns to post type admin.
	 * 
	 * @since  1.0.0
	 * @param  string  $column
	 * @param  integer $post_id
	 * @return void
	 */
	public function add_columns( $column, $post_id )
	{	
		switch ( $column ) {
			
			case 'image':
				
				$post       = get_post( $post_id );
				$post_title = $post->post_title;
				$edit_link  = get_edit_post_link( $post_id );
				
				echo '<a href="' . esc_url( $edit_link ) . '" title="' . esc_attr( $post_title ) . '">' . get_the_post_thumbnail( $post_id, 'thumbnail', array( 'alt' => $post_title  )  ) . '</a>';
				
				break;

			case 'event_cat':

				$terms = get_the_terms( $post_id, $this->taxonomy );

				if ( ! empty( $terms ) ) {

					$output = array();

					foreach ( $terms as $term ) {
						$output[] = sprintf( '<a href="%s">%s</a>',
							esc_url( add_query_arg( array( 'post_type' => $this->post_type, $this->taxonomy => $term->slug ), 'edit.php' ) ),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $this->taxonomy, 'display' ) )
						);
					}

					echo join( ', ', $output );
				} else {
					_e( 'No Event Categories', 'anva-post-types' );
				}

				break;
		}
		
	}

	/**
	 * Create columns for post type admin.
	 * 
	 * @since  1.0.0
	 * @param  array $columns
	 * @return array $columns
	 */
	public function columns( $columns )
	{
		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'image'     => __( 'Featured Image', 'anva-post-types' ),
			'title'     => __( 'Title', 'anva-post-types' ),
			'event_cat' => __( 'Categories', 'anva-post-types' ),
			'date'      => __( 'Date', 'anva-post-types' )
		);

		return $columns;
	}

	/**
	 * Register post type.
	 * 
	 * @since  1.0.0
	 * @return void
	 */
	public function register()
	{
		$labels = array(
			'name'               => __( 'Events',                   	 'anva-post-types' ),
			'singular_name'      => __( 'Event',                     	 'anva-post-types' ),
			'menu_name'          => __( 'Events',                   	 'anva-post-types' ),
			'name_admin_bar'     => __( 'Events',                     	 'anva-post-types' ),
			'add_new'            => __( 'Add New',                     	 'anva-post-types' ),
			'add_new_item'       => __( 'Add New Event',             	 'anva-post-types' ),
			'edit_item'          => __( 'Edit Event',                	 'anva-post-types' ),
			'new_item'           => __( 'New Event',                 	 'anva-post-types' ),
			'view_item'          => __( 'View Event',                	 'anva-post-types' ),
			'search_items'       => __( 'Search Team Events',            'anva-post-types' ),
			'not_found'          => __( 'No team Events found',          'anva-post-types' ),
			'not_found_in_trash' => __( 'No team Events found in trash', 'anva-post-types' ),
			'all_items'          => __( 'All Events',               	 'anva-post-types' ),
		);

		$args = array(
			'labels'			   => $labels,
			'description'          => '',
			'public'               => true,
			'publicly_queryable'   => true,
			'show_in_nav_menus'    => true,
			'show_in_admin_bar'    => true,
			'exclude_from_search'  => false,
			'show_ui'              => true,
			'show_in_menu'         => true,
			'menu_position'        => 20,
			'menu_icon'            => 'dashicons-calendar',
			'can_export'           => true,
			'delete_with_user'     => false,
			'hierarchical'         => false,
			'has_archive'          => 'events',
			'query_var'            => 'event',
			'rewrite' 			   => array(
				'slug'       	   => 'events',
				'with_front' 	   => false,
				'pages'      	   => true,
				'feeds'      	   => true,
				'ep_mask'    	   => EP_PERMALINK,
			),
			'supports' 			   => array( 'title', 'editor', 'thumbnail' ),
		);

		register_post_type( $this->post_type, $args );

	}

	/**
	 * Register taxonomy.
	 * 
	 * @since  1.0.0
	 * @return void
	 */
	public function taxonomy()
	{
		$labels = array(
			'name'                 => __( 'Categories', 		    'anva-post-types' ),
			'singular_name'        => __( 'Categories',   		    'anva-post-types' ),
			'menu_name'            => __( 'Categories',             'anva-post-types' ),
			'name_admin_bar'       => __( 'Categories',             'anva-post-types' ),
			'search_items'         => __( 'Search Categories',      'anva-post-types' ),
			'popular_items'        => __( 'Popular Categories',     'anva-post-types' ),
			'all_items'            => __( 'All Categories',         'anva-post-types' ),
			'edit_item'            => __( 'Edit Category',          'anva-post-types' ),
			'view_item'            => __( 'View Category',          'anva-post-types' ),
			'update_item'          => __( 'Update Category',        'anva-post-types' ),
			'add_new_item'         => __( 'Add New Category',       'anva-post-types' ),
			'new_item_name'        => __( 'New Category Name',      'anva-post-types' ),
			'parent_item'          => __( 'Parent Category',        'anva-post-types' ),
			'parent_item_colon'    => __( 'Parent Category:',       'anva-post-types' ),
			'add_or_remove_items'  => NULL,
			'not_found'            => NULL,
		);

		$args = array(
			'labels'			   => $labels,		
			'public'               => true,
			'show_ui'              => true,
			'show_in_nav_menus'    => true,
			'show_tagcloud'        => true,
			'show_admin_column'    => true,
			'hierarchical'         => true,
			'query_var'            => 'event_cat',
			'rewrite' 			   => array(
				'slug'         	   => 'events-cat',
				'with_front'   	   => false,
				'hierarchical' 	   => true,
				'ep_mask'      	   => EP_NONE
			),
		);
			
		register_taxonomy(
			$this->taxonomy,
			$this->post_type,
			$args
		);

	}

}
