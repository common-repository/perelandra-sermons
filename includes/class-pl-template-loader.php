<?php
/**
 * PL Sermons Template Loader
 *
 *
 * @author 		Wes Cole
 * @category 	Core
 * @package 	PL_Sermons/Classes
 * @since       1.0
 * @version     1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class PL_Sermons_Template_loader {

	/**
	 * Hook in methods.
	 */
	public function __construct( $file ) {
        $this->file = $file;
		add_filter( 'template_include', array( $this, 'template_loader' ) );
	}

	/**
	 * Load a template.
	 */
	public function template_loader( $template ) {

		if ( $default_file = $this->get_template_loader_default_file() ) {
			/**
			 * Filter hook to choose which files to find before WooCommerce does it's own logic.
			 *
			 * @since 1.0
			 * @var array
			 */
			$search_files = $this->get_template_loader_files( $default_file );
			$template = locate_template( $search_files );

			if ( ! $template ) {
                $template = plugin_dir_path( $this->file ) . '/templates/' . $default_file;
			}
		}

		return $template;
	}

	/**
	 * Get the default filename for a template.
	 *
	 * @since  1.0.0
	 */
	private function get_template_loader_default_file() {

        if ( is_singular( 'perelandra_sermon' ) ) {

        	$default_file = 'single-sermon.php';

        } elseif ( is_sermon_taxonomy() ) {

        	$term = get_queried_object();

			if ( is_tax( 'perelandra_sermon_series' ) ) {

        		$default_file = 'taxonomy-series.php';

        	} elseif ( is_tax() ) {

        		$default_file = 'taxonomy.php';

        	} else {

				$default_file = 'archive-sermons.php';

			}

        } elseif ( is_post_type_archive( 'perelandra_sermon' ) ) {

        	$default_file = 'archive-sermons.php';

        } else {

        	$default_file = '';

        }

        return $default_file;
	}

	/**
	 * Get an array of filenames to search for a given template.
	 *
	 * @since  1.0.0
	 */
	private function get_template_loader_files( $default_file ) {

        $search_files   = apply_filters( 'pl_sermons_template_loader_files', array(), $default_file );
		$search_files[] = 'sermons.php';

		if ( is_sermon_taxonomy() ) {

        	$term   = get_queried_object();
			$find[] = 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = template_path() . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
			$find[] = 'taxonomy-' . $term->taxonomy . '.php';
			$find[] = template_path() . 'taxonomy-' . $term->taxonomy . '.php';

        }

		$search_files[] = $default_file;
		$search_files[] = template_path() . $default_file;

		return array_unique( $search_files );
	}
}
