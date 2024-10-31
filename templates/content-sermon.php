<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * pl_sermons_before_loop_item hook
 *
 * @hook pl_sermons_output_loop_item_wrapper - 10
 */
do_action( 'pl_sermons_before_loop_item' );

/**
 * pl_sermons_loop_item_header hook
 *
 * @hook pl_sermons_output_loop_item_header - 10
 */
do_action( 'pl_sermons_loop_item_header' );

/**
 * pl_sermons_loop_item_content hook
 *
 * @hook pl_sermons_output_loop_item_title - 10
 * @hook pl_sermosn_output_loop_item_meta - 20
 */
do_action( 'pl_sermons_loop_item_content' );

/**
 * pl_sermons_loop_item_footer hook
 *
 * @hook pl_sermons_output_loop_item_footer - 10
 */
do_action( 'pl_sermons_loop_item_footer' );

/**
 * pl_sermons_after_loop_item hook
 *
 * @hook pl_sermons_output_loop_item_wrapper_end
 */
do_action( 'pl_sermons_after_loop_item' );
