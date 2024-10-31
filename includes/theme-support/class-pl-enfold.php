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
class PL_Enfold {

	/**
	 * Constructor.
	 */
	public function __construct() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enfold_styles' ) );
	}

    public function enfold_styles() {
        wp_enqueue_style( 'enfold', PL_PLUGINS_DIR_URI . 'assets/theme-support/enfold.css', false, PL_VERSION );
    }

}

new PL_Enfold();
