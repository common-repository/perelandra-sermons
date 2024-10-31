<?php

$featured_series = get_terms( 'perelandra_sermon_series', array(
    'numberposts'   => 1,
    'meta_query'    => array(
        array(
            'key'   => 'pl_series_featured',
            'value' => 'on',
            'compare'   => '='
        )
    )
) );

?>

<?php if ( $featured_series ): ?>

    <?php foreach( $featured_series as $featured ): ?>

        <div class="pl-sermons-featured__series">

            <div class="pl-sermons-featured__box">

                <div class="pl-sermons-featured-box__media featured-box-image">

                    <?php
                    $term_image_id = get_term_meta( $featured->term_id, 'pl_series_image_id', true );
                    $term_image_array = wp_get_attachment_image_src( $term_image_id, 'pl_grid_thumb' );
                    $term_image_src = $term_image_array[0];
                    ?>

                    <a href="<?php echo get_term_link( $featured->term_id ); ?>" class="featured-box-image" style="background-image: url('<?php echo $term_image_src; ?>');"></a>

                </div>

                <div class="pl-sermons-featured-box__content">

                    <header class="pl-sermons-box-content__header">

                        <p class="pl-sermons-featured-box__label">Featured Series</p>

                        <h2><a href="<?php echo get_term_link( $featured->term_id ); ?>"><?php echo $featured->name; ?></a></h2>

                    </header>

                    <section class="pl-sermons-box-content__info">

                        <p>
                            <?php echo $featured->description; ?>
                        </p>

                    </section>

                    <footer class="pl-sermons-box-content__footer">

                        <span class="pl-sermons-box-content__link"><a href="<?php echo get_term_link( $featured->term_id ); ?>">View Series</a></span>

                    </footer>

                </div>

            </div>

        </div>

    <?php endforeach; ?>

<?php endif; ?>
