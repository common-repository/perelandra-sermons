<?php if ( get_post_meta( get_the_ID(), 'pl_sermon_transcript', true ) ): ?>

    <div class="pl-sermons-single-accordion">

        <div class="pl-sermons-single-transcript pl-sermons-single-accordion-row">

            <div class="pl-sermons-single-accordion-title">
                <span class="pl-sermons-single-accordion-title-click">
                    Transcript
                    <span class="pl-sermons-caret"></span>
                </span>
                <?php if ( get_post_meta( get_the_ID(), 'pl_sermon_transcript_file', true ) ): ?>
                    <div class="pl-sermons-single-accordion-actions">
                        <a class="pl-sermons-single-button" href="<?php echo get_post_meta( get_the_ID(), 'pl_sermon_transcript_file', true ); ?>" download>Download</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="pl-sermons-single-accordion-content">

                <?php echo wpautop( get_post_meta( get_the_ID(), 'pl_sermon_transcript', true ) ); ?>

            </div>

        </div>

    </div>

<?php endif; ?>
