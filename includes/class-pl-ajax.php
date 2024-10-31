<?php
/**
 * PL_Ajax
 *
 * AJAX Event Handler
 *
 * @class PL_Ajax
 * @package PL_Sermons/Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class PL_Ajax {

    public function __construct() {

        add_action( 'init', array( $this, 'define_ajax' ), 0 );
        add_action( 'template_redirect', array( $this, 'do_pl_sermons_ajax' ), 0 );
        $this->add_events();

    }

    /**
     * Set WC AJAX constant and headers.
     */
    public static function define_ajax() {

        if ( ! empty( $_GET['pl-sermons-ajax'] ) ) {

            pl_define_constant( 'DOING_AJAX', true );
            pl_define_constant( 'PL_SERMONS_DOING_AJAX', true );

            if ( ! WP_DEBUG || ( WP_DEBUG && ! WP_DEBUG_DISPLAY ) ) {
                @ini_set( 'display_errors', 0 ); // Turn off display_errors during AJAX events to prevent malformed JSON
            }

            $GLOBALS['wpdb']->hide_errors();

        }

    }

    /**
     * Ajax Headers
     *
     * @since 1.0
     */
    public function pl_sermons_ajax_headers() {

        send_origin_headers();
		@header( 'Content-Type: text/html; charset=' . get_option( 'blog_charset' ) );
		@header( 'X-Robots-Tag: noindex' );
		send_nosniff_header();
		nocache_headers();
		status_header( 200 );

    }

    /**
     * Check for request and fire action
     *
     * @since 1.0
     */
    public function do_pl_sermons_ajax() {

        global $wp_query;

        if ( ! empty( $_GET['pl-sermons-ajax'] ) ) {
			$wp_query->set( 'pl-sermons-ajax', sanitize_text_field( $_GET['pl-sermons-ajax'] ) );
		}

        if ( $action = $wp_query->get( 'pl-sermons-ajax' ) ) {
			$this->pl_sermons_ajax_headers();
			do_action( 'pl_sermons_ajax_' . sanitize_text_field( $action ) );
			die();
		}

    }

    /**
     * Add all events to the proper hooks
     *
     * @since 1.0
     */
    public function add_events() {

        $events = array(
            'feature_sermon'    => false,
			'feature_series'	=> false
        );

        foreach( $events as $event => $nopriv ) {

            add_action( 'wp_ajax_pl_sermons_' . $event, array( $this, $event ) );

            if ( $nopriv ) {

                add_action( 'wp_ajax_nopriv_pl_sermons_' . $event, array( $this, $event ) );

            }

        }

    }

    /**
     * Featured status for sermons
     *
     * @since 1.0
     */
    public function feature_sermon() {

        if ( current_user_can( 'edit_posts' ) && check_admin_referer( 'pl-sermons-featured-sermon' ) ) {

            if ( get_post_meta( absint( $_GET['sermon_id'] ), 'pl_sermon_featured', true ) == 'on' ) {
                update_post_meta( absint( $_GET['sermon_id'] ), 'pl_sermon_featured', '' );
            } else {
                update_post_meta( absint( $_GET['sermon_id'] ), 'pl_sermon_featured', 'on' );
            }

        }

        wp_safe_redirect( wp_get_referer() ? remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() ) : admin_url( 'edit.php?post_type=perelandra_sermon' ) );

        die();

    }

	/**
     * Featured status for sermons
     *
     * @since 1.0
     */
    public function feature_series() {

        if ( current_user_can( 'edit_posts' ) && check_admin_referer( 'pl-sermons-featured-series' ) ) {

            if ( get_term_meta( absint( $_GET['series_id'] ), 'pl_series_featured', true ) == 'on' ) {
                update_term_meta( absint( $_GET['series_id'] ), 'pl_series_featured', '' );
            } else {
                update_term_meta( absint( $_GET['series_id'] ), 'pl_series_featured', 'on' );
            }

        }

        wp_safe_redirect( wp_get_referer() ? remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'ids' ), wp_get_referer() ) : admin_url( 'edit-tags.php?taxonomy=perelandra_sermon_series&post_type=perelandra_sermon' ) );

        die();

    }

}

new PL_Ajax();
