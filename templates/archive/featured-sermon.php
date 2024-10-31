<?php

$featured_sermon = get_posts( array(
    'posts_per_page'    => 1,
    'post_type' => 'perelandra_sermon',
    'post_status'   => 'publish',
    'meta_query'    => array(
        array(
            'key'   => 'pl_sermon_featured',
            'value' => 'on'
        )
    )
) );

?>

<?php if ( $featured_sermon ): ?>

    <div class="pl-sermons-featured__sermon">

        <header class="pl-sermons-section__header">
            <h4>Featured Sermon</h4>
        </header>

        <?php foreach( $featured_sermon as $sermon ): ?>

            <div class="pl-sermons-featured__box">

                <div class="pl-sermons-featured-box__media pl-sermons-featured-box-embed">

                    <?php

                    $url = esc_url( get_post_meta( $sermon->ID, 'pl_sermon_video_embed', 1 ) );
                    $thumb_image_id = get_post_thumbnail_id( $sermon->ID );
                    $thumb_image_array = wp_get_attachment_image_src( $thumb_image_id, 'pl_grid_thumb' );
                    $thumb_image_src = $thumb_image_array[0];

                    $default_image = get_option( 'pl_sermons_default_image' );;

                    if ( $url ) {
                        echo wp_oembed_get( $url );
                    } else if ( $thumb_image_src ) {
                        echo '<a href="' . get_the_permalink( $sermon->ID ) . '" class="featured-box-image" style="background-image: url(' . $thumb_image_src . ');"></a>';
                    } else if ( ! $thumb_image_src && $default_image ) {
                        echo '<a href="' . get_the_permalink( $sermon->ID ) . '" class="featured-box-image" style="background-image: url(' . $default_image . ');"></a>';
                    }

                    ?>

                </div>

                <div class="pl-sermons-featured-box__content">

                    <header class="pl-sermons-box-content__header">

                        <p class="pl-sermons-featured-box__label">Featured Sermon</p>

                        <h2><a href="<?php echo get_the_permalink( $sermon->ID ); ?>"><?php echo $sermon->post_title; ?></a></h2>

                        <p class="pl-sermons-box-content__header-meta">

                            <span class="pl-sermons-box-content__speaker"><?php echo get_the_term_list( $sermon->ID, 'perelandra_sermon_speakers', '', ', ', '' ); ?></span>

                        </p>

                    </header>

                    <footer class="pl-sermons-box-content__footer">

                        <span class="pl-sermons-box-content__date"><?php echo get_post_meta( $sermon->ID, 'pl_sermon_date', true ); ?></span>

                        <span class="pl-sermons-box-content__link"><a href="<?php echo get_the_permalink( $sermon->ID ); ?>">View Sermon</a></span>

                    </footer>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>
