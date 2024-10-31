<header class="pl-sermons-grid-item__header">

    <?php if ( has_term( '', 'perelandra_sermon_topics', get_the_ID() ) ): ?>

        <span class="pl-sermons-grid-item__header-topics">

            <span class="pl-sermons-series__label">Topics:</span>
            <?php echo get_the_term_list( get_the_ID(), 'perelandra_sermon_topics', '', ', ', '' ); ?>

        </span>

    <?php endif; ?>

    <?php if ( has_term( '', 'perelandra_sermon_books', get_the_ID() ) ): ?>

        <span class="pl-sermons-grid-item__header-books">

            <span class="pl-sermons-series__label">Books:</span>
            <?php echo get_the_term_list( get_the_ID(), 'perelandra_sermon_books', '', ', ', '' ); ?>

        </span>

    <?php endif; ?>

</header>
