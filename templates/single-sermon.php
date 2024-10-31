<?php

/**
 * Single Sermon Template
 *
 * @package pl_sermons
 */

?>

<?php get_header( 'pl_sermons' ); ?>

<?php do_action( 'pl_sermons_before_main_content' ); ?>

    <?php while( have_posts() ): the_post(); ?>

        <?php pl_sermons_get_template_part( 'content', 'single-sermon' ); ?>

    <?php endwhile; ?>

<?php do_action( 'pl_sermons_after_main_content' ); ?>

<?php do_action( 'pl_sermons_sidebar' ); ?>

<?php get_footer( 'pl_sermons' ); ?>
