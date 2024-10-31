<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Twenty Seventeen suport.
 *
 * @class   PL_Twenty_Seventeen
 * @since   1.0
 * @version 1.0
 * @package PerelandraSermons/Classes
 */
class PL_Twenty_Seventeen {

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action( 'pl_sermons_before_main_content', array( $this, 'output_content_wrapper' ), 10 );
		add_action( 'pl_sermons_after_main_content', array( $this, 'output_content_wrapper_end' ), 10 );
		
	}

	/**
	 * Open the Twenty Seventeen wrapper.
	 */
	public function output_content_wrapper() { ?>
		<div class="wrap">
			<div id="primary" class="content-area twentyseventeen">
				<main id="main" class="site-main" role="main">
		<?php
	}

	/**
	 * Close the Twenty Seventeen wrapper.
	 */
	public function output_content_wrapper_end() { ?>
				</main>
			</div>
		</div>
		<?php
	}
}

new PL_Twenty_Seventeen();
