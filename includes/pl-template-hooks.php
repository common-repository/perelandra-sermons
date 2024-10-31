<?php
/**
 * PL Sermons Template Hooks
 *
 * Action/filter hooks used for functions/templates.
 *
 * @author 		Wes Cole
 * @category 	Core
 * @package 	PL_Sermons/Templates
 * @since       1.0
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


add_filter( 'body_class', 'pl_sermons_body_class' );

/**
 * Sidebar.
 *
 * @see pl_sermons_get_sidebar()
 */
add_action( 'pl_sermons_sidebar', 'pl_sermons_get_sidebar', 10 );

/**
 * Content Wrappers
 *
 * @see pl_sermons_output_content_wrapper()
 */
add_action( 'pl_sermons_before_main_content', 'pl_sermons_output_content_wrapper', 10 );
add_action( 'pl_sermons_after_main_content', 'pl_sermons_output_content_wrapper_end', 10 );

/**
 * Featured Series
 *
 * @see pl_sermons_output_featured_series()
 */
add_action( 'pl_sermons_before_loop', 'pl_sermons_output_featured_series', 10 );

/**
 * Recent Series
 *
 * @see pl_sermons_output_recent_series()
 */
add_action( 'pl_sermons_before_loop', 'pl_sermons_output_recent_series', 20 );

/**
 * Featured Sermon
 *
 * @see pl_sermons_output_featured_sermon()
 */
add_action( 'pl_sermons_before_loop', 'pl_sermons_output_featured_sermon', 30 );

/**
 * Loop Header
 *
 * @see pl_sermons_output_loop_header()
 */
add_action( 'pl_sermons_loop_header', 'pl_sermons_output_loop_header', 10 );

/**
 * Loop Footer
 *
 * @see pl_sermons_output_loop_footer()
 */
add_action( 'pl_sermons_loop_footer', 'pl_sermons_output_loop_footer', 10 );

/**
 * Pagination
 *
 * @see pl_sermons_output_loop_pagination()
 */
add_action( 'pl_sermons_after_loop', 'pl_sermons_output_loop_pagination', 10 );

/**
 * Loop Item Wrappers
 *
 * @see pl_sermons_output_loop_item_wrapper()
 * @see pl_sermons_output_loop_item_wrapper_end()
 */
add_action( 'pl_sermons_before_loop_item', 'pl_sermons_output_loop_item_wrapper', 10 );
add_action( 'pl_sermons_after_loop_item', 'pl_sermons_output_loop_item_wrapper_end', 10 );

/**
 * Loop Item Header
 *
 * @see pl_sermons_output_loop_item_header()
 */
add_action( 'pl_sermons_loop_item_header', 'pl_sermons_output_loop_item_header', 10 );

/**
 * Loop Item Content
 *
 * @see pl_sermons_output_loop_item_content()
 */
add_action( 'pl_sermons_loop_item_content', 'pl_sermons_output_loop_item_content', 10 );

/**
 * Loop Item Footer
 *
 * @see pl_sermons_output_loop_item_footer()
 */
add_action( 'pl_sermons_loop_item_footer', 'pl_sermons_output_loop_item_footer', 10 );

/**
 * Single Sermon Media
 *
 * @see pl_sermons_output_media()
 */
add_action( 'pl_sermons_single_sermon_before_content', 'pl_sermons_output_media', 10 );

/**
 * Single Sermon Header
 *
 * @see pl_sermons_output_single_sermon_header()
 */
add_action( 'pl_sermons_single_sermon_content', 'pl_sermons_output_single_sermon_header', 10 );

/**
 * Single Sermon Meta
 *
 * @see pl_sermons_output_single_sermon_meta()
 */
add_action( 'pl_sermons_single_sermon_content', 'pl_sermons_output_single_sermon_meta', 20 );

/**
 * Single Sermon Description
 *
 * @see pl_sermons_output_single_sermon_description()
 */
add_action( 'pl_sermons_single_sermon_content', 'pl_sermons_output_single_sermon_description', 30 );

/**
 * Single Sermon Transcripts
 *
 * @see pl_sermons_output_single_sermon_transcript()
 */
add_action( 'pl_sermons_single_sermon_content', 'pl_sermons_output_single_sermon_transcript', 40 );

/**
 * Single Sermon Attachments
 *
 * @see pl_sermons_output_single_sermon_attachments()
 */
add_action( 'pl_sermons_single_sermon_content', 'pl_sermons_output_single_sermon_attachments', 50 );
