<?php
/**
 * The template for displaying product content in the single-sermon.php template
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * pl_sermons_before_single_sermon hook
 */
do_action( 'pl_sermons_before_single_sermon' );
?>

<div id="sermon-<?php the_ID(); ?>" class="pl-sermons-single-container">

	<?php
	/**
	 * pl_sermons_single_sermon_media hook
	 *
	 * @hook pl_sermons_output_media - 10
	 */
	do_action( 'pl_sermons_single_sermon_before_content' );

	/**
	 * pl_sermons_single_sermon_content hook
	 *
	 * @hook pl_sermons_output_single_sermon_header - 10
	 * @hook pl_sermons_output_single_sermon_meta - 20
	 * @hook pl_sermons_output_single_sermon_description - 30
	 * @hook pl_sermons_output_single_sermon_transcript - 40
	 * @hook pl_sermons_output_single_sermon_attachments - 50
	 */
	do_action( 'pl_sermons_single_sermon_content' );

	/**
	 * pl_sermons_single_sermon_after_content hook
	 */
	do_action( 'pl_sermons_single_sermon_after_content' );
	?>

</div>
