<div class="pl-sermons-grid-item__content">

    <h2 class="pl-sermons-grid-item__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

    <p class="pl-sermons-grid-item__meta">

        <?php echo get_the_term_list( get_the_ID(), 'perelandra_sermon_speakers', '', ', ', '' ); ?>

    </p>

</div>
