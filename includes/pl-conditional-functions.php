<?php
/**
 * PL Sermons Conditional Functions
 *
 * Functions for determining the current query/page.
 *
 * @author      Wes Cole
 * @category    Core
 * @package     PL_Sermons/Functions
 * @since       1.0
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check to see if we are on a perelandra page
 */
if ( ! function_exists( 'is_pl_sermons' ) ) {

	function is_pl_sermons() {
		return apply_filters( 'is_pl_sermons', ( is_sermon_taxonomy() || is_sermon() || is_sermon_archive() ) ? true : false );
	}

}

/**
 * Check to see if we are on a sermon taxonomy
 */
if ( ! function_exists( 'is_sermon_taxonomy' ) ) {

    function is_sermon_taxonomy() {
		return is_tax( get_object_taxonomies( 'perelandra_sermon' ) );
	}

}

/**
 * Check to see if we are on a sermon
 */
if ( ! function_exists( 'is_sermon' ) ) {

	function is_sermon() {
		return is_singular( array( 'perelandra_sermon' ) );
	}

}

/**
 * Check to see if we are on a sermon archive
 */
if ( ! function_exists( 'is_sermon_archive' ) ) {

	function is_sermon_archive() {
		return ( is_post_type_archive( 'perelandra_sermon' ) );
	}

}
