<?php

/**
 * Taxonomy Template
 *
 * @package pl_sermons
 */

?>

<?php get_header( 'pl_sermons' ); ?>

<?php do_action( 'pl_sermons_before_main_content' ); ?>

    <?php
    $queried_object = get_queried_object();
    ?>

    <div class="pl-sermons-page-header">

        <h1><?php echo $queried_object->name; ?></h1>

    </div>

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
             * @hooked pl_sermons_output_loop_footer - 10
             */
            do_action( 'pl_sermons_loop_footer' );
        ?>

    <?php endif; ?>

<?php do_action( 'pl_sermons_after_main_content' ); ?>

<?php do_action( 'pl_sermons_sidebar' ); ?>

<?php get_footer( 'pl_sermons' ); ?>
