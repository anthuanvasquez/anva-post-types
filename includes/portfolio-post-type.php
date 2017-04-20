<?php

class Anva_Post_Types_Portfolio
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
     * @since  1.0.0
     * @var string
     */
	private $post_type  = 'portfolio';

	/**
	 * Taxonomies slug.
	 * 
	 * @var array
	 */
	private $taxonomies = array( 'portfolio_type', 'portfolio_skill' );

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
	 * Add columns to post type admin
	 * 
	 * @since 1.0.0
	 */
	public function add_columns( $column, $post_id )
	{	
		switch ( $column ) {
			
			case 'image':
				
				$post = get_post( $post_id );
				$post_title = $post->post_title;
				$edit_link = get_edit_post_link( $post->ID );

				echo '<a href="' . esc_url( $edit_link ) . '" title="' . esc_attr( $post_title ) . '">' . get_the_post_thumbnail( $post->ID, 'thumbnail', array( 'alt' => $post_title  )  ) . '</a>';
				
				break;

			case 'portfolio_type':
				
				$terms = get_the_terms( $post_id, $this->taxonomies[0] );

				if ( ! empty( $terms ) ) {

					$output = array();

					foreach ( $terms as $term ) {
						$output[] = sprintf( '<a href="%s">%s</a>',
							esc_url( add_query_arg( array( 'post_type' => $this->post_type, $this->taxonomies[0] => $term->slug ), 'edit.php' ) ),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $this->taxonomies[0], 'display' ) )
						);
					}

					echo join( ', ', $output );
				} else {
					_e( 'No Types', 'anva-post-types' );
				}

				break;

			case 'portfolio_skill':
				$terms = get_the_terms( $post_id, $this->taxonomies[1] );

				if ( ! empty( $terms ) ) {

					$output = array();

					foreach ( $terms as $term ) {
						$output[] = sprintf( '<a href="%s">%s</a>',
							esc_url( add_query_arg( array( 'post_type' => $this->post_type, $this->post_type => $term->slug ), 'edit.php' ) ),
							esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $this->post_type, 'display' ) )
						);
					}

					echo join( ', ', $output );
				} else {
					_e( 'No Skills', 'anva-post-types' );
				}

				break;
		}
		
	}

	/**
	 * Create columns for post type admin
	 * 
	 * @since 1.0.0
	 */
	public function columns( $columns )
	{
		$columns = array(
			'cb'    			=> '<input type="checkbox" />',
			'image' 			=> __( 'Featured Image', 'anva-post-types' ),
			'title' 			=> __( 'Title', 'anva-post-types' ),
			'portfolio_type' 	=> __( 'Types' ),
			'portfolio_skill' 	=> __( 'Skills' ),
			'date'  			=> __( 'Date', 'anva-post-types' )
		);

		return $columns;
	}

	/**
	 * Register post type
	 * 
	 * @since 1.0.0
	 */
	function register()
	{
		$labels = array(
			'name'               => __( 'Portfolio',                   		'anva-post-types' ),
			'singular_name'      => __( 'Portfolio',                    	'anva-post-types' ),
			'menu_name'          => __( 'Portfolio',                   		'anva-post-types' ),
			'name_admin_bar'     => __( 'Portfolio',                    	'anva-post-types' ),
			'add_new'            => __( 'Add New',                     		'anva-post-types' ),
			'add_new_item'       => __( 'Add New Item',            			'anva-post-types' ),
			'edit_item'          => __( 'Edit Item',               			'anva-post-types' ),
			'new_item'           => __( 'New Item',                			'anva-post-types' ),
			'view_item'          => __( 'View Item',               			'anva-post-types' ),
			'search_items'       => __( 'Search portfolio items',           'anva-post-types' ),
			'not_found'          => __( 'No portfolio item found',          'anva-post-types' ),
			'not_found_in_trash' => __( 'No portfolio item found in trash', 'anva-post-types' ),
			'all_items'          => __( 'All Items',               			'anva-post-types' ),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => '',
			'public'              => true,
			'publicly_queryable'  => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-portfolio',
			'can_export'          => true,
			'delete_with_user'    => false,
			'hierarchical'        => false,
			'has_archive'         => 'portfolio',
			'query_var'           => 'portfolio',
			'rewrite'             => array(
				'slug'       	  => 'portfolio',
				'with_front' 	  => false,
				'pages'      	  => true,
				'feeds'      	  => true,
				'ep_mask'    	  => EP_PERMALINK,
			),
			'supports' 			  => array( 'title', 'editor', 'thumbnail' ),
		);

		register_post_type( $this->post_type, $args );

	}

	/**
	 * Register taxonomy
	 * 
	 * @since 1.0.0
	 */
	function taxonomy()
	{

		$type_labels = array(
			'name'                => __( 'Portfolio Types', 	'anva-post-types' ),
			'singular_name'       => __( 'Portfolio Type',   	'anva-post-types' ),
			'menu_name'           => __( 'Types',             	'anva-post-types' ),
			'name_admin_bar'      => __( 'Type',               	'anva-post-types' ),
			'search_items'        => __( 'Search Type',      	'anva-post-types' ),
			'popular_items'       => __( 'Popular Types',     	'anva-post-types' ),
			'all_items'           => __( 'All Types',         	'anva-post-types' ),
			'edit_item'           => __( 'Edit Type',          	'anva-post-types' ),
			'view_item'           => __( 'View Type',          	'anva-post-types' ),
			'update_item'         => __( 'Update Type',        	'anva-post-types' ),
			'add_new_item'        => __( 'Add New Type',       	'anva-post-types' ),
			'new_item_name'       => __( 'New Type Name',      	'anva-post-types' ),
			'parent_item'         => __( 'Parent Type',        	'anva-post-types' ),
			'parent_item_colon'   => __( 'Parent Type:',       	'anva-post-types' ),
			'add_or_remove_items' => NULL,
			'not_found'           => NULL,
		);

		$type_args = array(
			'labels'				=> $type_labels,		
			'public'            	=> false,
			'show_ui'           	=> true,
			'show_in_nav_menus' 	=> true,
			'show_tagcloud'     	=> true,
			'show_admin_column' 	=> true,
			'hierarchical'      	=> true,
			'query_var'         	=> 'portfolio_type',
		);
			
		register_taxonomy(
			$this->taxonomies[0],
			$this->post_type,
			$type_args
		);

		$skill_labels = array(
			'name'                => __( 'Portfolio Skills', 		'anva-post-types' ),
			'singular_name'       => __( 'Portfolio Skill',   		'anva-post-types' ),
			'menu_name'           => __( 'Skills',             		'anva-post-types' ),
			'name_admin_bar'      => __( 'Skill',               	'anva-post-types' ),
			'search_items'        => __( 'Search Skill',      		'anva-post-types' ),
			'popular_items'       => __( 'Popular Skills',     		'anva-post-types' ),
			'all_items'           => __( 'All Skills',         		'anva-post-types' ),
			'edit_item'           => __( 'Edit Skill',          	'anva-post-types' ),
			'view_item'           => __( 'View Skill',          	'anva-post-types' ),
			'update_item'         => __( 'Update Skill',        	'anva-post-types' ),
			'add_new_item'        => __( 'Add New Skill',       	'anva-post-types' ),
			'new_item_name'       => __( 'New Skill Name',      	'anva-post-types' ),
			'parent_item'         => __( 'Parent Skill',        	'anva-post-types' ),
			'parent_item_colon'   => __( 'Parent Skill:',       	'anva-post-types' ),
			'add_or_remove_items' => NULL,
			'not_found'           => NULL,
		);

		$skill_args = array(
			'labels'				=> $skill_labels,		
			'public'            	=> false,
			'show_ui'           	=> true,
			'show_in_nav_menus' 	=> true,
			'show_tagcloud'     	=> true,
			'show_admin_column' 	=> true,
			'hierarchical'      	=> false,
			'query_var'         	=> 'portfolio_skill',
		);
		
		register_taxonomy(
			$this->taxonomies[1],
			$this->post_type,
			$skill_args
		);

	}

}
