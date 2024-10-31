<?php if ( get_post_meta( get_the_ID(), 'pl_sermon_description', true ) ): ?>

    <div class="pl-sermons-single-description">

        <?php echo wpautop( get_post_meta( get_the_ID(), 'pl_sermon_description', true ) ); ?>

    </div>

<?php endif; ?>
