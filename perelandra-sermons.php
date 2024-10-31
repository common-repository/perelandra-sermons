<?php
/* Plugin Name: Perelandra Sermons
 * Plugin URI:  http://perelandrawp.com
 * Description: Create and manage your church sermons
 * Version:     1.1.0
 * Author:      Perelandra WP
 * Author URI:  http://perelandrawp.com
 * Text Domain: pl-sermons
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


define( 'PL_PLUGINS_DIR', plugin_dir_path( __FILE__ ) );
define( 'PL_PLUGINS_DIR_URI', plugin_dir_url( __FILE__ ) );
define( 'PL_VERSION', '1.1.0' );

/**
 * Check the active theme.
 *
 * @since  1.0
 * @param  string $theme Theme slug to check
 * @return bool
 */
function is_active_theme( $theme ) {
	return get_template() === $theme;
}

if ( file_exists(  plugin_dir_path( __FILE__ ) . '/cmb2/init.php' ) ) {
	require_once  plugin_dir_path( __FILE__ ) . '/cmb2/init.php';
	require_once plugin_dir_path( __FILE__ ) . '/includes/cmb2-functions.php';
} elseif ( file_exists(  plugin_dir_path( __FILE__ ) . '/CMB2/init.php' ) ) {
	require_once  plugin_dir_path( __FILE__ ) . '/CMB2/init.php';
	require_once plugin_dir_path( __FILE__ ) . '/includes/cmb2-functions.php';
}

require_once( plugin_dir_path( __FILE__ ) . '/includes/class-pl-admin.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/class-pl-settings.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/class-pl-ajax.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/class-pl-public.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/class-pl-template-loader.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/pl-functions.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/pl-conditional-functions.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/pl-template-functions.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/pl-template-hooks.php' );

if ( is_active_theme( 'twentyseventeen' ) ) {
	include_once( plugin_dir_path( __FILE__ ) . '/includes/theme-support/class-pl-twenty-seventeen.php' );
} else if ( is_active_theme( 'enfold' ) ) {
	include_once( plugin_dir_path( __FILE__ ) . '/includes/theme-support/class-pl-enfold.php' );
}

global $pl_admin, $pl_public, $pl_settings, $pl_template_loader, $pl_welcome;
$pl_template_loader = new PL_Sermons_Template_Loader( __FILE__ );
$pl_admin = new PL_Sermons_Admin( __FILE__, PL_VERSION );
$pl_public = new PL_Sermons_Public( __FILE__, PL_VERSION );
$pl_settings = new PL_Sermons_Settings( __FILE__, PL_VERSION );
