<?php
/**
 * Template for displaying video embed and audio player
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$thumb_id = get_post_thumbnail_id();
$thumb_url_array = wp_get_attachment_image_src($thumb_id, 'pl_image_wide', true);
$thumb_url = $thumb_url_array[0];
$audio_url = esc_url( get_post_meta( get_the_ID(), 'pl_sermon_audio_file', 1 ) );
$video_url = esc_url( get_post_meta( get_the_ID(), 'pl_sermon_video_embed', 1 ) );

?>
<?php if ( $audio_url || $video_url ): ?>
	<section class="pl-sermons-media-player">
		<?php if ( $video_url ): ?>
		    <div id="pl-sermons-video">
		        <?php echo wp_oembed_get( $video_url ); ?>
		    </div>
		<?php elseif ( $audio_url && ! $video_url ): ?>
		    <div class="pl-sermons-audio-wrap">
		        <audio id="pl-sermon-audio" src="<?php echo $audio_url; ?>"></audio>
		    </div>
		<?php endif; ?>
	</section>
<?php endif; ?>
