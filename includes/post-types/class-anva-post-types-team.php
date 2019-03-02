<?php

class Anva_Post_Types_Team
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
	private $post_type = 'team';

	/**
	 * Taxonomy slug.
	 * 
	 * @var string
	 */
	private $taxonomy = 'team_cat';

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
		if ( anva_post_types_is_active( $this->post_type ) ) {
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

			case 'team_cat':

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
					_e( 'No Team Positions', 'anva-post-types' );
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
			'cb'       => '<input type="checkbox" />',
			'image'    => __( 'Featured Image', 'anva-post-types' ),
			'title'    => __( 'Title', 'anva-post-types' ),
			'team_cat' => __( 'Positions', 'anva-post-types' ),
			'date'     => __( 'Date', 'anva-post-types' )
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
			'name'                 => __( 'Team',                   		'anva-post-types' ),
			'singular_name'        => __( 'Team',                     		'anva-post-types' ),
			'menu_name'            => __( 'Team',                   		'anva-post-types' ),
			'name_admin_bar'       => __( 'Team',                     		'anva-post-types' ),
			'add_new'              => __( 'Add New',                     	'anva-post-types' ),
			'add_new_item'         => __( 'Add New Member',             	'anva-post-types' ),
			'edit_item'            => __( 'Edit Member',                	'anva-post-types' ),
			'new_item'             => __( 'New Member',                 	'anva-post-types' ),
			'view_item'            => __( 'View Member',                	'anva-post-types' ),
			'search_items'         => __( 'Search Team Members',            'anva-post-types' ),
			'not_found'            => __( 'No team members found',          'anva-post-types' ),
			'not_found_in_trash'   => __( 'No team members found in trash', 'anva-post-types' ),
			'all_items'            => __( 'All Members',               		'anva-post-types' ),
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
			'menu_position'        => 25,
			'menu_icon'            => 'dashicons-groups',
			'can_export'           => true,
			'delete_with_user'     => false,
			'hierarchical'         => false,
			'has_archive'          => 'team',
			'query_var'            => 'team',
			'rewrite' 			   => array(
				'slug'       	   => 'team',
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
			'name'                 => __( 'Team Positions', 		'anva-post-types' ),
			'singular_name'        => __( 'Team Position',   		'anva-post-types' ),
			'menu_name'            => __( 'Positions',              'anva-post-types' ),
			'name_admin_bar'       => __( 'Position',               'anva-post-types' ),
			'search_items'         => __( 'Search Positions',       'anva-post-types' ),
			'popular_items'        => __( 'Popular Positions',      'anva-post-types' ),
			'all_items'            => __( 'All Positions',          'anva-post-types' ),
			'edit_item'            => __( 'Edit Position',          'anva-post-types' ),
			'view_item'            => __( 'View Position',          'anva-post-types' ),
			'update_item'          => __( 'Update Position',        'anva-post-types' ),
			'add_new_item'         => __( 'Add New Position',       'anva-post-types' ),
			'new_item_name'        => __( 'New Position Name',      'anva-post-types' ),
			'parent_item'          => __( 'Parent Position',        'anva-post-types' ),
			'parent_item_colon'    => __( 'Parent Position:',       'anva-post-types' ),
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
			'query_var'            => 'team_cat',
			'rewrite' 			   => array(
				'slug'         	   => 'team-cat',
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
