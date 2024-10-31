<?php

/**
 * Sermons Template
 *
 * @package pl_sermons
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'pl_sermons' ); ?>

    <?php
        /**
         * pl_sermons_before_main_content hook
         *
         * @hooked pl_sermons_output_content_wrapper - 10 (outputs opening div)
         */
        do_action( 'pl_sermons_before_main_content' );
    ?>

        <?php
            /**
             * pl_sermons_before_loop hook
             *
             * @hooked pl_sermons_output_featured_series - 10
             * @hooked pl_sermons_output_recent_series - 20
             * @hooked pl_sermons_output_featured_sermon - 30
             */
            do_action( 'pl_sermons_before_loop' );
        ?>

        <?php if ( have_posts() ): ?>

            <?php
                /**
                 * pl_sermons_before_loop hook
                 *
                 * @hooked pl_sermons_output_loop_header - 10
                 */
                do_action( 'pl_sermons_loop_header' );
            ?>

            <?php while( have_posts() ): the_post(); ?>

                <?php
                    /**
                     * pl_sermons_loop hook
                     */
                    do_action( 'pl_sermons_loop' );
                ?>

                <?php pl_sermons_get_template_part( 'content', 'sermon' ); ?>

            <?php endwhile; ?>

			<?php
                /**
                 * pl_sermons_after_loop hook
                 *
                 * @hooked pl_sermons_output_loop_pagination - 10
                 */
                do_action( 'pl_sermons_loop_footer' );
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

<?php do_action( 'pl_sermons_after_main_content' ); ?>

<?php do_action( 'pl_sermons_sidebar' ); ?>

<?php get_footer( 'pl_sermons' ); ?>
