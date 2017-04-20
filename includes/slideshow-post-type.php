<?php

class Anva_Post_Types_Slideshow
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
	private $post_type = 'slideshows';

	/**
	 * Taxonomy slug.
	 * 
	 * @var string
	 */
	private $taxonomy = 'slideshow_group';

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
			add_filter( 'post_updated_messages', array( $this, 'update_messages' ) );
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
				
				$post       = get_post( $post_id );
				$post_title = $post->post_title;
				$edit_link  = get_edit_post_link( $post_id );
				
				echo '<a href="' . esc_url( $edit_link ) . '" title="' . esc_attr( $post_title ) . '">' . get_the_post_thumbnail( $post_id, 'thumbnail', array( 'alt' => $post_title  )  ) . '</a>';
				
				break;

			case 'slideshow_group':
				
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
					_e( 'No slideshow Groups', 'anva-post-types' );
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
			'cb'              => '<input type="checkbox" />',
			'image'           => __( 'Featured Image', 'anva-post-types' ),
			'title'           => __( 'Title', 'anva-post-types' ),
			'slideshow_group' => __( 'Groups', 'anva-post-types' ),
			'date'            => __( 'Date', 'anva-post-types' )
		);

		return $columns;
	}

	/**
	 * Slideshow messages
	 *
	 * @since 1.0.0
	 */
	function update_messages( $messages )
	{
		global $post;
			
		$messages['slideshows'] = array(
			0 	=> '', // Unused. Messages start at index 1.
			1 	=> __( 'Slide updated.', 'anva-post-types' ),
			2 	=> __( 'Slide field updated.', 'anva-post-types' ),
			3 	=> __( 'Slide field deleted.', 'anva-post-types' ),
			4 	=> __( 'Slide updated.', 'anva-post-types' ),
			5 	=> isset( $_GET['revision'] ) ? sprintf( __( 'Slide restored to revision from %s', 'anva-post-types' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 	=> __( 'Slide published.', 'anva-post-types' ),
			7 	=> __( 'Slide saved.', 'anva-post-types' ),
			8 	=> __( 'Slide submitted.', 'anva-post-types' ),
			9 	=> sprintf( __( 'Slide scheduled for: <strong>%1$s</strong>.' ), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ) ),
			10 	=> __( 'Slide draft updated.', 'anva-post-types' ),
		);
		
		return $messages;
	}


	/**
	 * Register post type
	 * 
	 * @since 1.0.0
	 */
	public function register()
	{
		$labels = array(
			'name'               => __( 'Slideshows',                   'anva-post-types' ),
			'singular_name'      => __( 'Slideshow',                    'anva-post-types' ),
			'menu_name'          => __( 'Slideshows',                   'anva-post-types' ),
			'name_admin_bar'     => __( 'Slideshow',                    'anva-post-types' ),
			'add_new'            => __( 'Add New',                     	'anva-post-types' ),
			'add_new_item'       => __( 'Add New Slide',            	'anva-post-types' ),
			'edit_item'          => __( 'Edit Slide',               	'anva-post-types' ),
			'new_item'           => __( 'New Slide',                	'anva-post-types' ),
			'view_item'          => __( 'View Slide',               	'anva-post-types' ),
			'search_items'       => __( 'Search slides',            	'anva-post-types' ),
			'not_found'          => __( 'No slides found',          	'anva-post-types' ),
			'not_found_in_trash' => __( 'No slides found in trash', 	'anva-post-types' ),
			'all_items'          => __( 'All Slides',               	'anva-post-types' ),
		);

		$args = array(
			'labels'              => $labels,
			'description'         => '',
			'public'              => false,
			'publicly_queryable'  => false,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => true,
			'exclude_from_search' => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 20,
			'menu_icon'           => 'dashicons-images-alt2',
			'can_export'          => true,
			'delete_with_user'    => false,
			'hierarchical'        => false,
			'has_archive'         => false,
			'query_var'           => 'slideshow',
			'supports'            => array( 'title', 'thumbnail' ),
		);

		register_post_type( $this->post_type, $args );

	}

	/**
	 * Register taxonomy
	 * 
	 * @since 1.0.0
	 */
	public function taxonomy()
	{
		$labels = array(
			'name'                => __( 'Slideshow Groups', 	'anva-post-types' ),
			'singular_name'       => __( 'Slideshow Group',   	'anva-post-types' ),
			'menu_name'           => __( 'Groups',             	'anva-post-types' ),
			'name_admin_bar'      => __( 'Group',               'anva-post-types' ),
			'search_items'        => __( 'Search Group',      	'anva-post-types' ),
			'popular_items'       => __( 'Popular Groups',     	'anva-post-types' ),
			'all_items'           => __( 'All Groups',         	'anva-post-types' ),
			'edit_item'           => __( 'Edit Group',          'anva-post-types' ),
			'view_item'           => __( 'View Group',          'anva-post-types' ),
			'update_item'         => __( 'Update Group',        'anva-post-types' ),
			'add_new_item'        => __( 'Add New Group',       'anva-post-types' ),
			'new_item_name'       => __( 'New Group Name',      'anva-post-types' ),
			'parent_item'         => __( 'Parent Group',        'anva-post-types' ),
			'parent_item_colon'   => __( 'Parent Group:',       'anva-post-types' ),
			'add_or_remove_items' => NULL,
			'not_found'           => NULL,
		);

		$args = array(
			'labels'				=> $labels,	
			'public'            	=> false,
			'show_ui'           	=> true,
			'show_in_nav_menus' 	=> false,
			'show_tagcloud'     	=> true,
			'show_admin_column' 	=> true,
			'hierarchical'      	=> true,
			'query_var'         	=> 'slideshow_group',
		);
			
		register_taxonomy(
			$this->taxonomy,
			$this->post_type,
			$args
		);

	}

}
