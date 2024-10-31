<?php $attachments = get_post_meta( get_the_ID(), 'pl_sermon_attachments_group', true ); ?>
<?php if ( $attachments[0]['file_id'] > 0 ): ?>
    <div class="pl-sermons-single-attachments">
        <h4>Attachments</h4>

        <div class="pl-sermons-single-attachments-list">

            <?php foreach( (array) $attachments as $attachment ): ?>

                <a href="<?php echo $attachment['file']; ?>" download class="pl-sermons-single-attachments-list-item">

                    <span class="pl-sermons-single-attachments-list-item-image">

                        <?php echo wp_remote_retrieve_body( wp_remote_get( pl_sermons_get_file_icon( $attachment['file_id'] ) ) ); ?>

                    </span>

                    <span class="pl-sermons-single-attachments-list-item-title">

                        <?php if ( ! empty( $attachment['name'] ) ): ?>

                            <?php echo $attachment['name']; ?>

                        <?php else: ?>

                            <?php echo basename( get_attached_file( $attachment['file_id'] ) ); ?>

                        <?php endif; ?>

                    </span>

                    <span class="pl-sermons-single-attachment-list-item-actions">

                        <button>Download</button>

                    </span>

                </a>

            <?php endforeach; ?>

        </div>

    </div>

<?php endif; ?>
