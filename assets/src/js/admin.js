jQuery(document).ready(function($) {
    var fileFrame;

    jQuery.fn.pl_sermons_upload_media = function(button) {
        var buttonId = button.attr('id');

        if (fileFrame) {
            fileFrame.open();
            return;
        }

        fileFrame = wp.media.frames.file_frame = wp.media({
            multiple: false
        });

        fileFrame.on( 'select', function() {
            var attachment = fileFrame.state().get('selection').first().toJSON();
            jQuery("#pl_sermons_podcast_image, #pl_sermons_default_image").val(attachment.url);
            jQuery("#pl_sermons_podcast_image_preview, #pl_sermons_default_image_preview").attr('src',attachment.url);
        });

        // Finally, open the modal
        fileFrame.open();
    };

    jQuery('#pl_sermons_podcast_image_button, #pl_sermons_default_image_button').click(function() {
		jQuery.fn.pl_sermons_upload_media( jQuery(this), true );
	});

	jQuery('#pl_sermons_podcast_image_delete, #pl_sermons_default_image_delete').click(function() {
		jQuery( '#pl_sermons_podcast_image, #pl_sermons_default_image' ).val( '' );
		jQuery( '#pl_sermons_podcast_image_preview, #pl_sermons_default_image_preview' ).remove();
		return false;
	});
});
