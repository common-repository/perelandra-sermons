<footer class="pl-sermons-grid-item__footer">

    <?php if ( get_post_meta( get_the_ID(), 'pl_sermon_date', true ) ): ?>

        <span class="pl-sermons-grid-item__footer-date">

            <?php echo get_post_meta( get_the_ID(), 'pl_sermon_date', true ); ?>

        </span>

    <?php endif; ?>

    <?php if ( has_term( '', 'perelandra_sermon_series', get_the_ID() ) ): ?>

        <span class="pl-sermons-grid-item__footer-series">

            <span class="pl-sermons-series__label">Series:</span>

            <?php echo get_the_term_list( get_the_ID(), 'perelandra_sermon_series', '', ', ', '' ); ?>

        </span>

    <?php endif; ?>

</footer>
