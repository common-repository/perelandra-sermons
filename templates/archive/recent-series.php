<?php
$series = get_terms( 'perelandra_sermon_series', array(
    'number'   => 3
) );
?>

<?php if ( $series ): ?>

    <div class="pl-sermons-series">

        <header class="pl-sermons-section__header">
            <h4>Latest Sermon Series</h4>
        </header>

        <div class="pl-sermons-series-grid">

            <?php foreach ( $series as $single_series ): ?>

                <?php
                $term_image_id = get_term_meta( $single_series->term_id, 'pl_series_image_id', true );
                $term_image_array = wp_get_attachment_image_src( $term_image_id, 'pl_grid_thumb' );
                $term_image_src = $term_image_array[0];
                ?>

                <div class="pl-sermons-series-grid__item">

                    <a href="<?php echo get_term_link( $single_series->term_id ); ?>" style="background-image:url('<?php echo $term_image_src ?>');"></a>

                    <div class="pl-sermons-series-grid-item__content">

                        <header class="pl-sermons-series-header">

                            <h3><a href="<?php get_the_permalink( $single_series->ID ); ?>"><?php echo $single_series->name; ?></a></h3>

                        </header>

                        <section class="pl-sermons-series-content">

                            <p>
                                <?php echo $single_series->description; ?>
                            </p>

                        </section>

                        <footer class="pl-sermons-series-footer">

                            <a href="<?php echo get_term_link( $single_series->term_id ); ?>" class="pl-sermons-series-footer__link">View</a>

                        </footer>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </div>

<?php endif; ?>
