<?php
/**
 * PL Sermons Template
 *
 * Functions for the templating system.
 *
 * @author   Wes Cole
 * @category Core
 * @package  PL_Sermons/Functions
 * @since 1.0
 * @version  1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Get the template path.
 * @return string
 */
function template_path() {
	return apply_filters( 'pl_sermons_template_path', 'sermons/' );
}

/**
 * Locate a template and return the path for inclusion.
 *
 * This is the load order:
 *
 *		yourtheme		/	$template_path	/	$template_name
 *		yourtheme		/	$template_name
 *		$default_path	/	$template_name
 *
 * @access public
 * @param string $template_name
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 * @return string
 */
function pl_sermons_locate_template( $template_name, $template_path = '', $default_path = '' ) {
	if ( ! $template_path ) {
		$template_path = template_path();
	}
	if ( ! $default_path ) {
		$default_path = PL_PLUGINS_DIR . '/templates/';
	}
	// Look within passed path within the theme - this is priority.
	$template = locate_template(
		array(
			trailingslashit( $template_path ) . $template_name,
			$template_name,
		)
	);
	// Get default template/
	if ( ! $template ) {
		$template = $default_path . $template_name;
	}
	// Return what we found.
	return apply_filters( 'pl_sermons_locate_template', $template, $template_name, $template_path );
}

/**
 * Get template part
 *
 * @access public
 * @param mixed $slug
 * @param string $name (default: '')
 */
function pl_sermons_get_template_part( $slug, $name = '' ) {
	$template = '';
	// Look in yourtheme/slug-name.php and yourtheme/woocommerce/slug-name.php
	if ( $name ) {
		$template = locate_template( array( "{$slug}-{$name}.php", template_path() . "{$slug}-{$name}.php" ) );
	}
	// Get default slug-name.php
	if ( ! $template && $name && file_exists( PL_PLUGINS_DIR . "/templates/{$slug}-{$name}.php" ) ) {
		$template = PL_PLUGINS_DIR . "/templates/{$slug}-{$name}.php";
	}
	// If template file doesn't exist, look in yourtheme/slug.php and yourtheme/woocommerce/slug.php
	if ( ! $template ) {
		$template = locate_template( array( "{$slug}.php", template_path() . "{$slug}.php" ) );
	}
	// Allow 3rd party plugins to filter template file from their plugin.
	$template = apply_filters( 'pl_sermons_get_template_part', $template, $slug, $name );
	if ( $template ) {
		load_template( $template, false );
	}
}

/**
 * Get other templates (e.g. product attributes) passing attributes and including the file.
 *
 * @access public
 * @param string $template_name
 * @param array $args (default: array())
 * @param string $template_path (default: '')
 * @param string $default_path (default: '')
 */
function pl_sermons_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
	if ( ! empty( $args ) && is_array( $args ) ) {
		extract( $args );
	}
	$located = pl_sermons_locate_template( $template_name, $template_path, $default_path );
	if ( ! file_exists( $located ) ) {
		// TODO: Look into creating a logging function like this
		// wc_doing_it_wrong( __FUNCTION__, sprintf( __( '%s does not exist.', 'woocommerce' ), '<code>' . $located . '</code>' ), '2.1' );
		echo 'file does not exist';
		return;
	}
	// Allow 3rd party plugin filter template file from their plugin.
	$located = apply_filters( 'pl_sermons_get_template', $located, $template_name, $args, $template_path, $default_path );
	do_action( 'woocommerce_before_template_part', $template_name, $template_path, $located, $args );
	include( $located );
	do_action( 'woocommerce_after_template_part', $template_name, $template_path, $located, $args );
}

/**
 * Add body classes for perelandra pages.
 *
 * @param  array $classes
 * @return array
 */
function pl_sermons_body_class( $classes ) {
	$classes = (array) $classes;

	if ( is_pl_sermons() ) {

		$classes[] = 'pl-sermons';
		$classes[] = 'pl-sermons-page';

	}

	return array_unique( $classes );

}

/**
 * Attachment File Icons
 */
function pl_sermons_get_file_icon( $attachment_id ) {

	$base = PL_PLUGINS_DIR_URI . "assets/icons/";
 	$type = get_post_mime_type( $attachment_id );

	switch ($type) {
	 	case 'image/jpeg':
		case 'image/png':
	 	case 'image/gif':
		 	return $base . "picture.svg"; break;
		case 'video/mpeg':
		case 'video/mp4':
		case 'video/quicktime':
		 	return $base . "video.svg"; break;
		case 'text/csv':
		case 'text/plain':
		case 'text/xml':
		 	return $base . "txt.svg"; break;
		case 'application/pdf':
			return $base . "pdf.svg"; break;
		case 'application/msword':
		case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
			return $base . "doc.svg"; break;
		case 'application/vnd.ms-powerpointtd>':
		case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
			return $base . "powerpoint.svg";
		case 'text/plain':
			return $base . "txt.svg";
		case 'application/vnd.ms-excel':
			return $base . "excel.svg";
		default:
		 	return $base . "file.svg";
	}

}

/**
 * Get sidebar template
 */
if ( ! function_exists( 'pl_sermons_get_sidebar' ) ) {
	function pl_sermons_get_sidebar() {
		pl_sermons_get_template( 'global/sidebar.php' );
	}
}

/**
 * Content Wrappers
 */
if ( ! function_exists( 'pl_sermons_output_content_wrapper' ) ) {
	function pl_sermons_output_content_wrapper() {
		pl_sermons_get_template( 'global/wrapper-start.php' );
	}
}

if ( ! function_exists( 'pl_sermons_output_content_wrapper_end' ) ) {
	function pl_sermons_output_content_wrapper_end() {
		pl_sermons_get_template( 'global/wrapper-end.php' );
	}
}

/**
 * Feature Series
 */
if ( ! function_exists( 'pl_sermons_output_featured_series' ) ) {
	function pl_sermons_output_featured_series() {
		pl_sermons_get_template( 'archive/featured-series.php' );
	}
}

/**
 * Recent Series
 */
if ( ! function_exists( 'pl_sermons_output_recent_series' ) ) {
	function pl_sermons_output_recent_series() {
		$featured_series = get_option( 'pl_sermons_display_recent_series' );
		if ( $featured_series == 'on' ) {
			pl_sermons_get_template( 'archive/recent-series.php' );
		}
	}
}

/**
 * Featured Sermon
 */
if ( ! function_exists( 'pl_sermons_output_featured_sermon' ) ) {
	function pl_sermons_output_featured_sermon() {
		pl_sermons_get_template( 'archive/featured-sermon.php' );
	}
}

/**
 * Loop Header
 */
if ( ! function_exists( 'pl_sermons_output_loop_header' ) ) {
	function pl_sermons_output_loop_header() {
		pl_sermons_get_template( 'loop/header.php' );
	}
}

/**
 * Loop Footer
 */
if ( ! function_exists( 'pl_sermons_output_loop_footer' ) ) {
	function pl_sermons_output_loop_footer() {
		pl_sermons_get_template( 'loop/footer.php' );
	}
}

/**
 * Loop Item Content Wrappers
 */
if ( ! function_exists( 'pl_sermons_output_loop_item_wrapper' ) ) {
	function pl_sermons_output_loop_item_wrapper() {
		pl_sermons_get_template( 'loop/wrapper-start.php' );
	}
}

if ( ! function_exists( 'pl_sermons_output_loop_item_wrapper_end' ) ) {
	function pl_sermons_output_loop_item_wrapper_end() {
		pl_sermons_get_template( 'loop/wrapper-end.php' );
	}
}

/**
 * Loop Item Header
 */
if ( ! function_exists( 'pl_sermons_output_loop_item_header' ) ) {
	function pl_sermons_output_loop_item_header() {
		pl_sermons_get_template( 'loop/item-header.php' );
	}
}

/**
 * Loop Item Content
 */
if ( ! function_exists( 'pl_sermons_output_loop_item_content' ) ) {
	function pl_sermons_output_loop_item_content() {
		pl_sermons_get_template( 'loop/item-content.php' );
	}
}

/**
 * Loop Item Footer
 */
if ( ! function_exists( 'pl_sermons_output_loop_item_footer' ) ) {
	function pl_sermons_output_loop_item_footer() {
		pl_sermons_get_template( 'loop/item-footer.php' );
	}
}

/**
 * Loop Pagination
 */
if ( ! function_exists( 'pl_sermons_output_loop_pagination' ) ) {
	function pl_sermons_output_loop_pagination() {
		pl_sermons_get_template( 'loop/pagination.php' );
	}
}

/**
 * Single Sermon Media
 */
if ( ! function_exists( 'pl_sermons_output_media' ) ) {
	function pl_sermons_output_media() {
		pl_sermons_get_template( 'single-sermon/media.php' );
	}
}

/**
 * Single Sermon Header
 */
if ( ! function_exists( 'pl_sermons_output_single_sermon_header' ) ) {
	function pl_sermons_output_single_sermon_header() {
		pl_sermons_get_template( 'single-sermon/header.php' );
	}
}

/**
 * Single Sermon Meta
 */
if ( ! function_exists( 'pl_sermons_output_single_sermon_meta' ) ) {
	function pl_sermons_output_single_sermon_meta() {
		pl_sermons_get_template( 'single-sermon/meta.php' );
	}
}

/**
 * Single Sermon Description
 */
if ( ! function_exists( 'pl_sermons_output_single_sermon_description' ) ) {
	function pl_sermons_output_single_sermon_description() {
		pl_sermons_get_template( 'single-sermon/description.php' );
	}
}

/**
 * Single Sermon Transcript
 */
if ( ! function_exists( 'pl_sermons_output_single_sermon_transcript' ) ) {
	function pl_sermons_output_single_sermon_transcript() {
		pl_sermons_get_template( 'single-sermon/transcript.php' );
	}
}

/**
 * Single Sermon Attachments
 */
if ( ! function_exists( 'pl_sermons_output_single_sermon_attachments' ) ) {
	function pl_sermons_output_single_sermon_attachments() {
		pl_sermons_get_template( 'single-sermon/attachments.php' );
	}
}

/**
 * Custom pagination
 *
 * @since 1.0.2
 */
function sermon_pagination( $numpages = '', $pagerange = '', $paged='' ) {
	$pagination_args = array(
		'base'            => get_pagenum_link(1) . '%_%',
		'format'          => 'page/%#%',
		'total'           => $numpages,
		'current'         => $paged,
		'show_all'        => False,
		'end_size'        => 1,
		'mid_size'        => $pagerange,
		'prev_next'       => True,
		'prev_text'       => __('&laquo;'),
		'next_text'       => __('&raquo;'),
		'type'            => 'plain',
		'add_args'        => false,
		'add_fragment'    => ''
	);

	$paginate_links = paginate_links($pagination_args);

	if ($paginate_links) {
		echo "<nav class='custom-pagination'>";
		echo $paginate_links;
		echo "</nav>";
	}
}
