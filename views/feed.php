<?php
// If title set, display custom title
if ( ! empty( pl_get_option( 'pl_sermons_podcast_title' ) ) ) {
    $title = pl_get_option( 'pl_sermons_podcast_title' );
} else {
    $title = get_bloginfo( 'name' );
}

// Set subtitle var
if ( ! empty( pl_get_option( 'pl_sermons_podcast_subtitle' ) ) ) {
    $subtitle = pl_get_option( 'pl_sermons_podcast_subtitle' );
} else {
    $subtitle = '';
}

// If description set, display custom desc
if ( ! empty( pl_get_option( 'pl_sermons_podcast_description' ) ) ) {
    $description = pl_get_option( 'pl_sermons_podcast_description' );
} else {
    $description = get_bloginfo( 'description' );
}

// Set author var
if ( ! empty ( pl_get_option( 'pl_sermons_podcast_author' ) ) ) {
    $author = pl_get_option( 'pl_sermons_podcast_author' );
} else {
    $author = get_bloginfo( 'name' );
}

// Set owner name var
if ( ! empty( pl_get_option( 'pl_sermons_podcast_owner_name' ) ) ) {
    $owner_name = pl_get_option( 'pl_sermons_podcast_owner_name' );
} else {
    $owner_name = get_bloginfo( 'name' );
}

// Set owner email var
if ( ! empty ( pl_get_option( 'pl_sermons_podcast_owner_email' ) ) ) {
    $owner_email = pl_get_option( 'pl_sermons_podcast_owner_email' );
} else {
    $owner_email = get_bloginfo( 'admin_email' );
}

// Set language var
if ( ! empty( pl_get_option( 'pl_sermons_podcast_language' ) ) ) {
    $language = pl_get_option( 'pl_sermons_podcast_language' );
} else {
    $language = get_bloginfo( 'language' );
}

// Set copyright var
if ( ! empty( pl_get_option( 'pl_sermons_podcast_copyright' ) ) ) {
    $copyright = pl_get_option( 'pl_sermons_podcast_copyright' );
} else {
    $copyright = 'Copyright ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' );
}

// If website set, display custom website
if ( ! empty( pl_get_option( 'pl_sermons_podcast_website') ) ) {
    $website = pl_get_option( 'pl_sermons_podcast_website' );
} else {
    $website = get_bloginfo( 'url' );
}

// Set the image var
if ( ! empty( pl_get_option( 'pl_sermons_podcast_image' ) ) ) {
    $image = pl_get_option( 'pl_sermons_podcast_image' );
} else {
    $image = '';
}

// Set the primary category var
if ( ! empty( pl_get_option( 'pl_sermons_podcast_primary_category' ) ) ) {
    $p_category = pl_get_option( 'pl_sermons_podcast_primary_category' );
} else {
    $p_category = 'Religion & Spirituality';
}

// Set the secondary category var
if ( ! empty( pl_get_option( 'pl_sermons_podcast_secondary_category' ) ) ) {
    $s_category = esc_attr( pl_get_option( 'pl_sermons_podcast_secondary_category' ) );
} else {
    $s_category = 'Christianity';
}

// Set the explicit var
if ( ! empty( pl_get_option( 'pl_sermons_podcast_explicit' ) ) && pl_get_option( 'pl_sermons_podcast_explicit' ) == 'on' ) {
    $itunes_explicit = 'yes';
    $gp_explicit = 'Yes';
} else {
    $itunes_explicit = 'clean';
    $gp_explicit = 'No';
}

$args = array(
    'post_type' => 'perelandra_sermon',
    'posts_per_page' => -1
);

$query = new WP_Query( $args );

// Output XML Header
header('Content-Type: '.feed_content_type('rss-http').'; charset='.get_option('blog_charset'), true);
echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>';

?>
<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
	xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
	xmlns:googleplay="http://www.google.com/schemas/play-podcasts/1.0"
	<?php do_action( 'rss2_ns' ); ?>
>

    <channel>
        <title><?php echo esc_html( $title ); ?></title>
        <atom:link href="<?php esc_url( self_link() ); ?>" rel="self" type="application/rss+xml" />
        <link><?php echo esc_url( $website ) ?></link>
        <description><?php echo esc_html( $description ); ?></description>
        <lastBuildDate><?php echo esc_html( mysql2date( 'D, d M Y H:i:s +0000', get_lastpostmodified( 'GMT' ), false ) ); ?></lastBuildDate>
        <language><?php echo esc_html( $language ); ?></language>
        <copyright><?php echo esc_html( $copyright ); ?></copyright>
        <itunes:subtitle><?php echo esc_html( $subtitle ); ?></itunes:subtitle>
        <itunes:author><?php echo esc_html( $author ); ?></itunes:author>
        <googleplay:author><?php echo esc_html( $author ); ?></googleplay:author>
        <googleplay:email><?php echo esc_html( $owner_email ); ?></googleplay:email>
        <itunes:summary><?php echo esc_html( $description ); ?></itunes:summary>
        <googleplay:description><?php echo esc_html( $description ); ?></googleplay:description>
        <itunes:owner>
            <itunes:name><?php echo esc_html( $owner_name ); ?></itunes:name>
            <itunes:email><?php echo esc_html( $owner_email ); ?></itunes:email>
        </itunes:owner>
        <itunes:explicit><?php echo $itunes_explicit; ?></itunes:explicit>
        <googleplay:explicit><?php echo $gp_explicit; ?></googleplay:explicit>
        <?php if ( $image ): ?>
            <itunes:image href="<?php echo esc_url( $image ); ?>"></itunes:image>
            <googleplay:image href="<?php echo esc_url( $image ); ?>"></googleplay:image>
            <image>
                <url><?php echo esc_url( $image ); ?></url>
                <title><?php echo esc_html( $title ); ?></title>
                <link><?php echo esc_url( $website ) ?></link>
            </image>
        <?php endif; ?>
        <?php if ( $p_category ): ?>
            <itunes:category text="<?php echo esc_attr( $p_category ); ?>">
                <itunes:category text="<?php echo esc_attr( $s_category ); ?>"></itunes:category>
            </itunes:category>
        <?php endif; ?>
        <?php

        // Prevent WP core from outputting an <image> element
		remove_action( 'rss2_head', 'rss2_site_icon' );

		// Add RSS2 headers
		do_action( 'rss2_head' );

        $args = array(
            'post_type' => 'perelandra_sermon',
            'num_posts' => -1
        );

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                // Get Audio File
                $enclosure = get_post_meta( get_the_ID(), 'pl_sermon_podcast_file', true );

                // Get the MIME type
                $mime_type = '';
                $sermon_type = get_post_meta( get_the_ID(), 'pl_sermon_podcast_type', true );;
                if ( $sermon_type == 'video' ) {
                    $mime_type = 'video/mp4';
                } else if ( $sermon_type == 'audio' ) {
                    $mime_type = 'audio/mpeg';
                }

                // Make sure that we have an enclosure
                if ( ! isset( $enclosure ) || ! $enclosure ) {
                    continue;
                }

                // Get the sermon size
                $size = get_post_meta( get_the_ID(), 'pl_sermon_podcast_size', true );
                if ( ! $size ) {
                    $size = 0;
                }

                // Get the sermon image
                $sermon_image = '';
                $series_terms = get_the_terms( get_the_ID(), 'perelandra_sermon_series' );
                $series_term_id = $series_terms[0]->term_id;
                $series_image = get_term_meta( $series_term_id, 'pl_series_image', true );

                if ( $series_image ) {
                    $sermon_image = $series_image;
                } else {
                    $sermon_image = pl_get_option( 'podcast_image' );
                }

                // Get the sermon duration
                $duration = get_post_meta( get_the_ID(), 'pl_sermon_podcast_duration', true );
                if ( ! $duration ) {
                    $duration = '0:00';
                }

                // Get Explicit Status
                $sermon_explicit = get_post_meta( get_the_ID(), 'pl_sermon_podcast_explicit', true );
                if ( $sermon_explicit == 'on' ) {
                    $itunes_explicit = 'yes';
                	$googleplay_explicit = 'Yes';
                } else {
                    $itunes_explicit = 'clean';
                	$googleplay_explicit = 'No';
                }

                // Get Blocked status
                $sermon_blocked = get_post_meta( get_the_ID(), 'pl_sermon_podcast_block', true );
                if ( $sermon_blocked == 'on' ) {
                    $blocked = 'yes';
                } else {
                    $blocked = 'no';
                }

                // Get the sermon speaker
                $authors = wp_get_post_terms( get_the_ID(), 'perelandra_sermon_speakers' );
                $author = $authors[0]->name;

                // Episode description
                $content = get_post_meta( get_the_ID(), 'pl_sermon_podcast_content', true );

                if ( empty( $content ) ) {
                    $content = get_post_meta( get_the_ID(), 'pl_sermon_description', true );
                }

                $content = preg_replace( '/<\/?iframe(.|\s)*?>/', '', $content );
                $podcast_summary = mb_substr( $content, 0, 3999 );

                $itunes_subtitle = strip_tags( strip_shortcodes( $content ) );
				$itunes_subtitle = str_replace( array( '>', '<', '\'', '"', '`', '[andhellip;]', '[&hellip;]', '[&#8230;]' ), array( '', '', '', '', '', '', '', '' ), $itunes_subtitle );
				$itunes_subtitle = mb_substr( $itunes_subtitle, 0, 254 );

                ?>
                <item>
        			<title><?php esc_html( the_title_rss() ); ?></title>
        			<link><?php esc_url( the_permalink_rss() ); ?></link>
        			<pubDate><?php echo esc_html( mysql2date( 'D, d M Y H:i:s +0000', get_post_time( 'Y-m-d H:i:s', true ), false ) ); ?></pubDate>
        			<dc:creator><?php echo $author; ?></dc:creator>
        			<guid isPermaLink="false"><?php esc_html( the_guid() ); ?></guid>
        			<description><![CDATA[<?php echo $content; ?>]]></description>
        			<itunes:subtitle><![CDATA[<?php echo $itunes_subtitle; ?>]]></itunes:subtitle>
        			<content:encoded><![CDATA[<?php echo $content; ?>]]></content:encoded>
        			<itunes:summary><![CDATA[<?php echo $podcast_summary; ?>]]></itunes:summary>
        			<googleplay:description><![CDATA[<?php echo $podcast_summary; ?>]]></googleplay:description>
                    <?php if ( $sermon_image ): ?>
                        <itunes:image href="<?php echo esc_url( $sermon_image ); ?>"></itunes:image>
        			    <googleplay:image href="<?php echo esc_url( $sermon_image ); ?>"></googleplay:image>
                    <?php endif; ?>
        			<enclosure url="<?php echo esc_url( $enclosure ); ?>" length="<?php echo esc_attr( $size ); ?>" type="<?php echo esc_attr( $mime_type ); ?>"></enclosure>
        			<itunes:explicit><?php echo $itunes_explicit; ?></itunes:explicit>
        			<googleplay:explicit><?php echo $googleplay_explicit; ?></googleplay:explicit>
        			<itunes:block><?php echo esc_html( $blocked ); ?></itunes:block>
        			<googleplay:block><?php echo esc_html( $blocked ); ?></googleplay:block>
        			<itunes:duration><?php echo esc_html( $duration ); ?></itunes:duration>
        			<itunes:author><?php echo $author; ?></itunes:author>
        		</item>
                <?php
            }
        }
        ?>
    </channel>
</rss>
<?php
