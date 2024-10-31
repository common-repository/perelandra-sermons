<?php
$paged = ( get_query_var('page') ) ? get_query_var('page') : 1;
$args = array(
    'post_type'	=> 'perelandra_sermon',
    'posts_per_page'	=> get_option( 'posts_per_page' ),
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
