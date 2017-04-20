<?php
/**
 * Display plugin notices to the user.
 */
class Anva_Post_Types_Notices
{
    /**
     * A single instance of this class.
     *
     * @since 1.0.0
     */
    private static $instance = null;

    /**
     * The type of error.
     *
     * @since 1.0.0
     */
    private $error = array();

    /**
     * Whether the plugin should completely stop running.
     *
     * @since 1.0.0
     */
    private $stop = false;

    /**
     * Creates or returns an instance of this class.
     *
     * @since 1.0.0
     * @return A single instance of this class.
     */
    public static function get_instance()
    {
        if ( self::$instance == null ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Constructor. Hook everything in.
     *
     * @since 1.0.0
     */
    private function __construct()
    {
        $v = 0;

        if ( defined( 'ANVA_FRAMEWORK_VERSION' ) ) {
            $v = ANVA_FRAMEWORK_VERSION;
        }

        if ( ! $v ) {
            $this->error[] = 'framework';
            $this->stop = true;
        }

        if ( $this->error ) {
            add_action( 'admin_notices', array( $this, 'show' ) );
            add_action( 'admin_init', array( $this, 'disable' ) );
        }

    }

    /**
     * Show error message
     *
     * @since 1.0.0
     */
    public function show()
    {
        global $current_user;

        if ( $this->error ) {

            $theme = wp_get_theme( get_template() );
            $changelog = '<a href="https://themes.anthuanvasquez.net/anva/changelog/?theme=' . get_template() . '" target="_blank">' . esc_html__( 'theme\'s changelog', 'anva-post-types' ) . '</a>';

            foreach ( $this->error as $error ) {
                if ( ! get_user_meta( $current_user->ID, 'anva-nag-'.$error, true ) ) {

                    echo '<div class="updated">';
                    echo '<p><strong>Anva Post Types</strong>: '.esc_html( $this->get_message($error) ).'</p>';

                    // Dismiss link
                    echo '<p><a href="'.esc_url( $this->disable_url($error) ).'">'.esc_html__('Dismiss this notice', 'anva-post-types').'</a> | <a href="http://anthuanvasquez.net" target="_blank">'.esc_html__('Visit AnthuanVasquez.net', 'anva-post-types').'</a></p>';

                    echo '</div>';
                }
            }
        }
    }

    /**
     * Disable error message
     *
     * @since 1.0.0
     */
    public function disable()
    {
        global $current_user;

        if ( ! isset($_GET['nag-ignore']) ) {
            return;
        }

        if ( strpos($_GET['nag-ignore'], 'anva-nag-') !== 0 ) {
            return;
        }

        if ( isset($_GET['security']) && wp_verify_nonce( $_GET['security'], 'anva-post-types-nag' ) ) {
            add_user_meta( $current_user->ID, $_GET['nag-ignore'], 'true', true );
        }
    }

    /**
     * Disable a nag message URL.
     *
     * @since 1.0.0
     */
    private function disable_url( $id )
    {
        global $pagenow;

        $url = admin_url( $pagenow );

        if ( ! empty( $_SERVER['QUERY_STRING'] ) ) {
            $url .= sprintf( '?%s&nag-ignore=%s', $_SERVER['QUERY_STRING'], 'anva-nag-'.$id );
        } else {
            $url .= sprintf( '?nag-ignore=%s', 'anva-nag-'.$id );
        }

        $url .= sprintf( '&security=%s', wp_create_nonce('anva-post-types-nag') );

        return $url;
    }

    /**
     * Get individual error message
     *
     * @since 1.0.0
     */
    private function get_message( $type )
    {
        $message = '';

        $messages = array(
            'framework' => __('You are not using a theme with the Anva Framework, and so this plugin will not do anything.', 'anva-post-types'),
        );

        if ( isset( $messages[$type] ) ) {
            $message = $messages[$type];
        }

        return $message;
    }

    /**
     * Determine if plugin should stop
     *
     * @since 1.0.0
     */
    public function do_stop()
    {
        return $this->stop;
    }

}

/**
 * Anva post types list.
 * 
 * @return array
 */
function anva_post_types_list() {
    $post_types = array();
    return apply_filters( 'anva_post_types_list', $post_types );
}

/**
 * Anva post types check.
 *
 * @since  1.0.0
 *
 * @param  string $post_type
 * @return boolean
 */
function anva_post_types_is_active( $post_type ) {

    $post_types = anva_post_types_list();

    // Check if there is post types to register.
    if ( empty( $post_types ) ) {
        return false;
    }

    if ( in_array( $post_type, $post_types ) ) {
        return true;
    }
}

function anva_get_post_id_by_meta( $key, $value ) {
    global $wpdb;
    
    $meta = $wpdb->get_results( "SELECT * FROM `" . $wpdb->postmeta . "` WHERE meta_key='" . esc_sql( $key ) . "' AND meta_value='" . esc_sql( $value ) . "'" );
    
    if ( is_array( $meta ) && ! empty( $meta ) && isset( $meta[0] ) ) {
        $meta = $meta[0];
    }
    
    if ( is_object( $meta ) ) {
        return $meta->post_id;
    }
    
    return false;
}
