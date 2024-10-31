<div class="pl-sermons-single-meta">

    <?php if ( get_post_meta( get_the_ID(), 'pl_sermon_date', true ) ): ?>

        <span class="pl-sermons-single-date">

            <?php echo get_post_meta( get_the_ID(), 'pl_sermon_date', true ); ?>

        </span>

    <?php endif; ?>

    <?php if ( get_post_meta( get_the_ID(), 'pl_sermon_passage', true ) ): ?>

        &middot;

        <span class="pl-sermons-single-passage">

            <?php echo get_post_meta( get_the_ID(), 'pl_sermon_passage', true ); ?>

        </span>

    <?php endif; ?>

</div>
