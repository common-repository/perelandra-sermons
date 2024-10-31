<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main admin class
 *
 * @author      Wes Cole
 * @category    Class
 * @package     PerelandraSermons/Classes
 * @since       1.0
 */
class PL_Sermons_Public {

    public function __construct( $file, $version ) {
		$this->file = $file;
		$this->version = $version;

		add_action( 'wp_enqueue_scripts', array( $this, 'scripts_and_styles' ) );
		add_image_size( 'pl_grid_thumb', 800, 600, true );
		add_image_size( 'pl_image_wide', 1200, 600, true );
		add_shortcode( 'pl_all_sermons', array( $this, 'all_sermons_shortcode' ) );
		add_shortcode( 'pl_featured_sermon', array( $this, 'featured_sermon_shortcode' ) );
		add_shortcode( 'pl_featured_series', array( $this, 'featured_series_shortcode' ) );
		add_shortcode( 'pl_recent_series', array( $this, 'recent_series_shortcode' ) );
		add_action( 'wp_footer', array( $this, 'reftagger' ) );
    }

	/**
	 * Add scripts and styles
	 *
	 * @since 1.0
	 */
	public function scripts_and_styles() {
		wp_enqueue_style( 'main', plugins_url( '/assets/dist/css/main.css', $this->file ), false, $this->version );
		wp_register_script( 'fitVids', plugins_url( '/vendor/fitVids/jquery.fitvids.js', $this->file ), array(), $this->version, true );
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script( 'wp-mediaelement' );
		wp_enqueue_script( 'main-js', plugins_url( '/assets/src/js/main.js', $this->file ), array('jquery', 'fitVids', 'wp-mediaelement'), $this->version, true );
	}

	/**
	 * Alphabatize Terms
	 *
	 * @since 1.0
	 */
	private function alphabetize_terms( $tax ) {
		$args = array( 'orderby' => 'name', 'order' => 'asc' );
		$terms = get_terms( $tax, $args );
		$by_letter = array();

		foreach( $terms as $term ):
			$letter = substr( $term->name, 0, 1 );
			if ( ! isset( $by_letter[$letter] ) ) $by_letter[$letter] = array();
			$by_letter[$letter][] = $term;
		endforeach;

		return $by_letter;
	}

	/**
	 * Add reftagger for links
	 *
	 * @since 1.0
	 */
	public function reftagger() {
		?>
		<?php if ( is_singular( 'perelandra_sermon' ) ): ?>
			<script>
				var refTagger = {
					settings: {
						bibleVersion: "ESV",
						roundCorners: true,
						socialSharing: [],
						tooltipStyle: "dark",
						tagChapters: true
					}
				};
				(function(d, t) {
					var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
					g.src = "//api.reftagger.com/v2/RefTagger.js";
					s.parentNode.insertBefore(g, s);
				}(document, "script"));
			</script>
		<?php endif; ?>
		<?php
	}


	/**
	 * All Sermons Shortcode
	 *
	 * @since 1.0.2
	 */
	function all_sermons_shortcode( $atts ) {
		$a = shortcode_atts( array(
			'number_of_sermons'	=> get_option( 'posts_per_page' )
		), $atts );

		$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
		$args = array(
		    'post_type'	=> 'perelandra_sermon',
		    'posts_per_page'	=> $a['number_of_sermons'],
		    'paged'	=> $paged,
		    'page'	=> $paged
		);

		$query = new WP_Query( $args );

		?>
		<?php
			/**
			 * pl_sermons_before_main_content hook
			 *
			 * @hooked pl_sermons_output_content_wrapper - 10 (outputs opening div)
			 */
			do_action( 'pl_sermons_before_main_content' );
		?>


			<?php if ( $query->have_posts() ): ?>

				<?php
					/**
					 * pl_sermons_before_loop hook
					 *
					 * @hooked pl_sermons_output_loop_header - 10
					 */
					do_action( 'pl_sermons_loop_header' );
				?>

				<?php while( $query->have_posts() ): $query->the_post(); ?>

					<?php
						/**
						 * pl_sermons_loop hook
						 */
						do_action( 'pl_sermons_loop' );
					?>

					<?php pl_sermons_get_template_part( 'content', 'sermon' ); ?>


				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>

				<?php
				if (function_exists(sermon_pagination)) {
					sermon_pagination($query->max_num_pages,"",$paged);
				}
				?>


			<?php endif; ?>

			<?php
				/**
				 * pl_sermons_after_loop hook
				 *
				 * @hooked pl_sermons_output_pagination - 10
				 */
				do_action( 'pl_sermons_after_loop' );
			?>

		<?php
			/**
			 * pl_sermons_after_main_content hook
			 *
			 * @hooked pl_sermons_output_content_wrapper_end - 10 (outputs opening div)
			 */
			do_action( 'pl_sermons_after_main_content' );
		?>

		<?php
	}

	/**
	 * Featured Sermon Shortcode
	 *
	 * @since 1.0.2
	 */
	function featured_sermon_shortcode() {
		pl_sermons_get_template( 'archive/featured-sermon.php' );
	}

	/**
	 * Featured Series Shortcode
	 *
	 * @since 1.0.2
	 */
	function featured_series_shortcode() {
		pl_sermons_get_template( 'archive/featured-series.php' );
	}

	/**
	 * Recent Series
	 *
	 * @since 1.0.2
	 */
	function recent_series_shortcode() {
		pl_sermons_get_template( 'archive/recent-series.php' );
	}
}
