<?php

/**
 * Taxonomy Series Template
 *
 * @package pl_sermons
 */

?>

<?php get_header( 'pl_sermons' ); ?>

<?php do_action( 'pl_sermons_before_main_content' ); ?>

    <?php
    $queried_object = get_queried_object();
    ?>

    <div class="pl-sermons-featured__series">

        <div class="pl-sermons-featured__box">

            <?php
            $term_image_id = get_term_meta( $queried_object->term_id, 'pl_series_image_id', true );
            $term_image_array = wp_get_attachment_image_src( $term_image_id, 'pl_grid_thumb' );
            $term_image_src = $term_image_array[0];
            ?>

            <?php if ( $term_image_src ): ?>
                <div class="pl-sermons-featured-box__media featured-box-image">

                    <a href="<?php echo get_term_link( $queried_object->term_id ); ?>" class="featured-box-image" style="background-image: url('<?php echo $term_image_src; ?>');"></a>

                </div>
            <?php endif; ?>

            <div class="pl-sermons-featured-box__content">

                <header class="pl-sermons-box-content__header">

                    <h2><?php echo $queried_object->name; ?></h2>

                </header>

                <section class="pl-sermons-box-content__info">

                    <p>
                        <?php echo $queried_object->description; ?>
                    </p>

                </section>

            </div>

        </div>

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
