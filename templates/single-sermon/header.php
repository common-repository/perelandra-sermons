<header class="pl-sermons-single-header">

    <div class="pl-sermons-single-title-wrap">

        <?php if ( has_term( '', 'perelandra_sermon_series', get_the_ID() ) ): ?>

            <span class="pl-sermons-single-series">

                <?php echo get_the_term_list( get_the_ID(), 'perelandra_sermon_series', '', ', ', '' ); ?>

            </span>

        <?php endif; ?>

        <h1 class="pl-sermons-single-title"><?php the_title(); ?></h1>

    </div>

</header>
