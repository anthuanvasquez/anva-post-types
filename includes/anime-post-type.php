<?php

class Anva_Post_Types_anime
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
    private $post_type  = array( 'anime', 'anime_episode' );

    /**
     * Taxonomies slug.
     * 
     * @var array
     */
    private $taxonomies = array( 'anime_genre', 'anime_type' );

    /**
     * Thumbnail size.
     * 
     * @var array
     */
    private $size       = array( 140, 200 );

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
        if ( anva_post_types_is_active( $this->post_type[0] ) ) {
            add_action( 'init', array( $this, 'register' ) );
            add_action( 'init', array( $this, 'taxonomy' ) );
            add_action( 'manage_' . $this->post_type[0] . '_posts_custom_column', array( $this, 'add_columns_anime' ), 10, 2 );
            add_filter( 'manage_edit-' . $this->post_type[0] . '_columns', array( $this, 'columns_anime' ) );
            add_action( 'manage_' . $this->post_type[1] . '_posts_custom_column', array( $this, 'add_columns_episode' ), 10, 2 );
            add_filter( 'manage_edit-' . $this->post_type[1] . '_columns', array( $this, 'columns_episode' ) );
        }
        
    }

    /**
     * Add columns to post type admin
     * 
     * @since 1.0.0
     */
    public function add_columns_anime( $column, $post_id )
    {   
        switch ( $column ) {
            
            case 'image':
                
                $post = get_post( $post_id );
                $post_title = $post->post_title;
                $edit_link = get_edit_post_link( $post->ID );

                echo '<a href="' . esc_url( $edit_link ) . '" title="' . esc_attr( $post_title ) . '">' . get_the_post_thumbnail( $post->ID, $this->size, array( 'alt' => $post_title  )  ) . '</a>';
                
                break;

            case 'anime_genre':
                
                $terms = get_the_terms( $post_id, $this->taxonomies[0] );

                if ( ! empty( $terms ) ) {

                    $output = array();

                    foreach ( $terms as $term ) {
                        $output[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $this->post_type[0], $this->taxonomies[0] => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $this->taxonomies[0], 'display' ) )
                        );
                    }

                    echo join( ', ', $output );
                } else {
                    _e( 'No Genres', 'anva-post-types' );
                }

                break;

            case 'anime_type':
                $terms = get_the_terms( $post_id, $this->taxonomies[1] );

                if ( ! empty( $terms ) ) {

                    $output = array();

                    foreach ( $terms as $term ) {
                        $output[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $this->post_type[0], $this->post_type[0] => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $this->post_type[0], 'display' ) )
                        );
                    }

                    echo join( ', ', $output );
                } else {
                    _e( 'No Types', 'anva-post-types' );
                }

                break;
        }
        
    }

    /**
     * Create columns for post type admin
     * 
     * @since 1.0.0
     */
    public function columns_anime( $columns )
    {
        $columns = array(
            'cb'          => '<input type="checkbox" />',
            'image'       => __( 'Featured Image', 'anva-post-types' ),
            'title'       => __( 'Title', 'anva-post-types' ),
            'anime_genre' => __( 'Genre' ),
            'anime_type'  => __( 'Type' ),
            'date'        => __( 'Date', 'anva-post-types' )
        );

        return $columns;
    }

    /**
     * Add columns to post type admin
     * 
     * @since 1.0.0
     */
    public function add_columns_episode( $column, $post_id )
    {   
        switch ( $column ) {
            
            case 'image':
                
                $post = get_post( $post_id );
                $post_title = $post->post_title;
                $edit_link = get_edit_post_link( $post->ID );

                echo '<a href="' . esc_url( $edit_link ) . '" title="' . esc_attr( $post_title ) . '">' . get_the_post_thumbnail( $post->ID, $this->size, array( 'alt' => $post_title  )  ) . '</a>';
                
                break;

            case 'anime':
                
                $anime_id = get_post_meta( $post_id, '_anime', true );
                
                if ( ! empty( $anime_id ) ) {

                    $anime = get_post( $anime_id );
                    $anime_title = $anime->post_title;

                    $url = add_query_arg(
                        array(
                            'post' => $anime_id,
                            'action' => 'edit'
                        ),
                        'post.php'
                    );

                    printf( '<a href="%s">%s</a>',
                        esc_url( $url ),
                        esc_html( $anime_title )
                    );
                    
                } else {
                    _e( 'No Anime Assigned', 'anva-post-types' );
                }

                break;

            case 'anime_type':
                $terms = get_the_terms( $post_id, $this->taxonomies[1] );

                if ( ! empty( $terms ) ) {

                    $output = array();

                    foreach ( $terms as $term ) {
                        $output[] = sprintf( '<a href="%s">%s</a>',
                            esc_url( add_query_arg( array( 'post_type' => $this->post_type[0], $this->post_type[0] => $term->slug ), 'edit.php' ) ),
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $this->post_type[0], 'display' ) )
                        );
                    }

                    echo join( ', ', $output );
                } else {
                    _e( 'No Types', 'anva-post-types' );
                }

                break;
        }
        
    }

        /**
     * Create columns for post type admin
     * 
     * @since 1.0.0
     */
    public function columns_episode( $columns )
    {
        $columns = array(
            'cb'          => '<input type="checkbox" />',
            'image'       => __( 'Featured Image', 'anva-post-types' ),
            'title'       => __( 'Title', 'anva-post-types' ),
            'anime'       => __( 'Anime' ),
            'date'        => __( 'Date', 'anva-post-types' )
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
        $anime_labels = array(
            'name'               => __( 'Anime',                        'anva-post-types' ),
            'singular_name'      => __( 'Anime',                        'anva-post-types' ),
            'menu_name'          => __( 'Anime',                        'anva-post-types' ),
            'name_admin_bar'     => __( 'Anime',                        'anva-post-types' ),
            'add_new'            => __( 'Add New',                      'anva-post-types' ),
            'add_new_item'       => __( 'Add New Anime',                'anva-post-types' ),
            'edit_item'          => __( 'Edit Anime',                   'anva-post-types' ),
            'new_item'           => __( 'New Anime',                    'anva-post-types' ),
            'view_item'          => __( 'View Anime',                   'anva-post-types' ),
            'search_items'       => __( 'Search anime',                 'anva-post-types' ),
            'not_found'          => __( 'No anime found',               'anva-post-types' ),
            'not_found_in_trash' => __( 'No anime found in trash',      'anva-post-types' ),
            'all_items'          => __( 'All Anime',                    'anva-post-types' ),
        );

        $anime_args = array(
            'labels'              => $anime_labels,
            'description'         => '',
            'public'              => true,
            'publicly_queryable'  => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'exclude_from_search' => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-format-video',
            'can_export'          => true,
            'delete_with_user'    => false,
            'hierarchical'        => false,
            'has_archive'         => 'anime',
            'query_var'           => 'anime',
            'rewrite'             => array(
                'slug'            => apply_filters( 'anva_post_types_anime_slug', 'anime' ),
                'with_front'      => false,
                'pages'           => true,
                'feeds'           => true,
                'ep_mask'         => EP_PERMALINK,
            ),
            'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
        );

        $episode_labels = array(
            'name'               => __( 'Episodes',                       'anva-post-types' ),
            'singular_name'      => __( 'Episode',                        'anva-post-types' ),
            'menu_name'          => __( 'Episodes',                       'anva-post-types' ),
            'name_admin_bar'     => __( 'Episodes',                       'anva-post-types' ),
            'add_new'            => __( 'Add New',                        'anva-post-types' ),
            'add_new_item'       => __( 'Add New Episode',                'anva-post-types' ),
            'edit_item'          => __( 'Edit Episode',                   'anva-post-types' ),
            'new_item'           => __( 'New Episode',                    'anva-post-types' ),
            'view_item'          => __( 'View Episode',                   'anva-post-types' ),
            'search_items'       => __( 'Search episode',                 'anva-post-types' ),
            'not_found'          => __( 'No episode found',               'anva-post-types' ),
            'not_found_in_trash' => __( 'No episode found in trash',      'anva-post-types' ),
            'all_items'          => __( 'All Episodes',                   'anva-post-types' ),
        );

        $episode_args = array(
            'labels'              => $episode_labels,
            'description'         => '',
            'public'              => true,
            'publicly_queryable'  => true,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => true,
            'exclude_from_search' => false,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-format-video',
            'can_export'          => true,
            'delete_with_user'    => false,
            'hierarchical'        => false,
            'has_archive'         => 'anime_episode',
            'query_var'           => 'anime_episode',
            'rewrite'             => array(
                'slug'            => apply_filters( 'anva_post_types_anime_episode_slug', '%anime%/episode' ),
                'with_front'      => false,
                'pages'           => true,
                'feeds'           => true,
                'ep_mask'         => EP_PERMALINK,
            ),
            'supports'            => array( 'title', 'editor', 'thumbnail', 'author' ),
        );

        register_post_type( $this->post_type[0], $anime_args );
        register_post_type( $this->post_type[1], $episode_args );

    }

    /**
     * Register taxonomy
     * 
     * @since 1.0.0
     */
    function taxonomy()
    {

        $type_labels = array(
            'name'                => __( 'Anime Types',     'anva-post-types' ),
            'singular_name'       => __( 'Anime Type',      'anva-post-types' ),
            'menu_name'           => __( 'Types',           'anva-post-types' ),
            'name_admin_bar'      => __( 'Type',            'anva-post-types' ),
            'search_items'        => __( 'Search Type',     'anva-post-types' ),
            'popular_items'       => __( 'Popular Types',   'anva-post-types' ),
            'all_items'           => __( 'All Types',       'anva-post-types' ),
            'edit_item'           => __( 'Edit Type',       'anva-post-types' ),
            'view_item'           => __( 'View Type',       'anva-post-types' ),
            'update_item'         => __( 'Update Type',     'anva-post-types' ),
            'add_new_item'        => __( 'Add New Type',    'anva-post-types' ),
            'new_item_name'       => __( 'New Type Name',   'anva-post-types' ),
            'parent_item'         => __( 'Parent Type',     'anva-post-types' ),
            'parent_item_colon'   => __( 'Parent Type:',    'anva-post-types' ),
            'add_or_remove_items' => NULL,
            'not_found'           => NULL,
        );

        $type_args = array(
            'labels'                => $type_labels,        
            'public'                => false,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'show_tagcloud'         => true,
            'show_admin_column'     => true,
            'hierarchical'          => true,
            'query_var'             => 'anime_type',
        );

        $genre_labels = array(
            'name'                => __( 'Genres',              'anva-post-types' ),
            'singular_name'       => __( 'Genre',               'anva-post-types' ),
            'menu_name'           => __( 'Genres',              'anva-post-types' ),
            'name_admin_bar'      => __( 'Genre',               'anva-post-types' ),
            'search_items'        => __( 'Search Genres',       'anva-post-types' ),
            'popular_items'       => __( 'Popular Genres',      'anva-post-types' ),
            'all_items'           => __( 'All Genres',          'anva-post-types' ),
            'edit_item'           => __( 'Edit Genre',          'anva-post-types' ),
            'view_item'           => __( 'View Genre',          'anva-post-types' ),
            'update_item'         => __( 'Update Genre',        'anva-post-types' ),
            'add_new_item'        => __( 'Add New Genre',       'anva-post-types' ),
            'new_item_name'       => __( 'New Genre Name',      'anva-post-types' ),
            'parent_item'         => __( 'Parent Genre',        'anva-post-types' ),
            'parent_item_colon'   => __( 'Parent Genre:',       'anva-post-types' ),
            'add_or_remove_items' => NULL,
            'not_found'           => NULL,
        );

        $genre_args = array(
            'labels'                => $genre_labels,
            'public'                => true,
            'show_ui'               => true,
            'show_in_nav_menus'     => true,
            'show_tagcloud'         => true,
            'show_admin_column'     => true,
            'hierarchical'          => true,
            'query_var'             => 'anime_genre',
        );

        register_taxonomy(
            $this->taxonomies[0],
            $this->post_type[0],
            $type_args
        );
        
        register_taxonomy(
            $this->taxonomies[1],
            $this->post_type[0],
            $genre_args
        );

    }

}
