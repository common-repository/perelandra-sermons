<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main admin class
 *
 * @author      Wes Cole
 * @category    Class
 * @package     PerelandraSermons/Classes
 * @since       1.0
 */
class PL_Sermons_Admin {

    public function __construct( $file, $version ) {

        $this->file = $file;
		$this->version = $version;
        $this->posttype = 'perelandra_sermon';

        add_action( 'init', array( $this, 'register_post_type' ), 1 );
		add_action( 'cmb2_init', array( $this, 'register_metaboxes' ) );
		add_action( 'cmb2_init', array( $this, 'register_taxonomy_metaboxes' ) );

        if ( is_admin() ) {
            add_filter( 'enter_title_here', array( $this, 'enter_title_here' ) );
			add_filter( 'post_updated_messages', array( $this, 'updated_messages' ) );
			add_filter( 'manage_perelandra_sermon_posts_columns', array( $this, 'add_sermon_columns' ) );
			add_action( 'manage_posts_custom_column', array( $this, 'custom_columns' ), 10, 2 );
			add_filter( 'manage_edit-perelandra_sermon_series_columns', array( $this, 'series_custom_columns' ) );
			add_filter( 'manage_perelandra_sermon_series_custom_column', array( $this, 'series_custom_columns_content' ), 10, 3 );
			add_action ( 'admin_enqueue_scripts', array( $this, 'admin_styles' ) );
			add_action( 'admin_menu', array( $this, 'welcome_screens' ) );
			add_action( 'admin_init', array( $this, 'do_welcome_screen_redirect' ), 11 );
			// add_action( 'admin_notices', array( $this, 'theme_support_notice' ) );
        }

		add_action( 'init', array( $this, 'add_rss_feed' ), 1 );

		register_activation_hook( $file, array( $this, 'activate_plugin' ) );
		register_deactivation_hook( $file, array( $this, 'deactivate_plugin' ) );

    }

    /**
     * Register the sermon post type
     *
     * @since 1.0
     */
    public function register_post_type() {
        $labels = array(
            'name'               => esc_attr__( 'Sermons', 'perelandra-sermons' ),
            'singular_name'      => esc_attr__( 'Sermon', 'perelandra-sermons' ),
            'add_new'            => esc_attr__( 'Add New', 'perelandra-sermons' ),
            'add_new_item'       => esc_attr__( 'Add New Sermon', 'perelandra-sermons' ),
            'edit_item'          => esc_attr__( 'Edit Sermon', 'perelandra-sermons' ),
            'new_item'           => esc_attr__( 'New Sermon', 'perelandra-sermons' ),
            'view_item'          => esc_attr__( 'View Sermon', 'perelandra-sermons' ),
            'search_items'       => esc_attr__( 'Search Sermons', 'perelandra-sermons' ),
            'not_found'          => esc_attr__( 'No sermons found.', 'perelandra-sermons' ),
            'not_found_in_trash' => esc_attr__( 'No sermons found in trash.', 'perelandra-sermons' ),
            'featured_image'     => esc_attr__( 'Sermon Image', 'perelandra-sermons' ),
            'set_featured_image' => esc_attr__( 'Set Sermon Image', 'perelandra-sermons' ),
            'remove_featured_image' => esc_attr__( 'Remove Sermon Image', 'perelandra-sermons' ),
            'use_featured_image' => esc_attr__( 'Use Sermon Image', 'perelandra-sermons' ),
			'all_items'			 => esc_attr__( 'All Sermons', 'perelandra-sermons' ),
            'parent_item_colon'  => '',
            'menu_name'          => esc_attr__( 'Sermons', 'perelandra-sermons' )
        );

        // Build out the post type arguments.
        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'exclude_from_search' => false,
            'show_ui'             => true,
            'show_in_admin_bar'   => true,
            'rewrite'             => array( 'slug' => 'sermons', 'feeds' => true ),
            'query_var'           => false,
            'hierarchical'        => false,
            'has_archive'         => true,
            'menu_position'       => apply_filters( 'pl_sermons_post_type_menu_position', 25 ),
            'menu_icon'           => 'dashicons-microphone',
            'show_in_rest'        => true,
            'rest_base'           => 'sermons',
            'rest_controller_class' => 'WP_REST_Posts_Controller',
            'supports'            => array( 'title', 'thumbnail' )
        );

        // Register the post type with WordPress.
        register_post_type( 'perelandra_sermon', $args );

        $this->register_tax();
    }

    /**
     * Register taxonomies
     *
     * @since 1.0
     */
    public function register_tax() {
        $series_labels = array(
            'name' => 'Series',
            'singular_name' => 'Series',
            'menu_name' => 'Series',
            'all_items' => 'All Series',
            'edit_item' => 'Edit Series',
            'view_item' => 'View Series',
            'update_item' => 'Update Series',
            'add_new_item'  => 'Add New Series',
            'new_item_name' => 'New Series',
            'parent_item' => 'Parent Series',
            'parent_item_colon' => 'Parent Series:',
            'search_items' => 'Search Series',
            'popular_items' => 'Popular Series',
            'seperate_items_with_commas' => 'Seperate series with commas',
            'add_or_remove_items' => 'Add or remove series',
            'choose_from_most_used' => 'Choose from the most used series',
            'not_found' => 'No series found'
        );

        $series_args = array(
            'label' => 'Series',
            'labels' => $series_labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'description' => 'Associate the sermon to a series.',
            'hierarchical' => true,
            'rewrite' => array(
                'slug' => 'series'
            ),
            'show_in_rest'       => true,
            'rest_base'          => 'series',
            'rest_controller_class' => 'WP_REST_Terms_Controller'
        );

        register_taxonomy( 'perelandra_sermon_series', 'perelandra_sermon', $series_args );
        register_taxonomy_for_object_type( 'perelandra_sermon_series', 'perelandra_sermon' );

        $speak_labels = array(
            'name' => 'Speakers',
            'singular_name' => 'Speaker',
            'menu_name' => 'Speakers',
            'all_items' => 'All Speakers',
            'edit_item' => 'Edit Speaker',
            'view_item' => 'View Speaker',
            'update_item' => 'Update Speaker',
            'add_new_item'  => 'Add New Speaker',
            'new_item_name' => 'New Speaker',
            'parent_item' => 'Parent Speaker',
            'parent_item_colon' => 'Parent Speaker:',
            'search_items' => 'Search Speakers',
            'popular_items' => 'Popular Speakers',
            'seperate_items_with_commas' => 'Seperate speakers with commas',
            'add_or_remove_items' => 'Add or remove speakers',
            'choose_from_most_used' => 'Choose from the most used speakers',
            'not_found' => 'No speakers found'
        );

        $speak_args = array(
            'label' => 'Speakers',
            'labels' => $speak_labels,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud' => false,
            'show_in_quick_edit' => false,
            'description' => 'Associate the sermon to a speaker.',
            'hierarchical' => true,
            'rewrite' => array(
                'slug' => 'speakers',
                'with_front' => false
            ),
            'show_in_rest'       => true,
            'rest_base'          => 'speakers',
            'rest_controller_class' => 'WP_REST_Terms_Controller'
        );

        register_taxonomy( 'perelandra_sermon_speakers', 'perelandra_sermon', $speak_args );
        register_taxonomy_for_object_type( 'perelandra_sermon_speakers', 'perelandra_sermon' );

		$topic_labels = array(
			'name' => 'Topics',
			'singular_name' => 'Topic',
			'menu_name' => 'Topics',
			'all_items' => 'All Topics',
			'edit_item' => 'Edit Topics',
			'view_item' => 'View Topics',
			'update_item' => 'Update Topics',
			'add_new_item'  => 'Add New Topics',
			'new_item_name' => 'New Topics',
			'parent_item' => 'Parent Topics',
			'parent_item_colon' => 'Parent Topics:',
			'search_items' => 'Search Topics',
			'popular_items' => 'Popular Topics',
			'seperate_items_with_commas' => 'Seperate topics with commas',
			'add_or_remove_items' => 'Add or remove topics',
			'choose_from_most_used' => 'Choose from the most used topics',
			'not_found' => 'No topics found'
		);

		$topic_args = array(
			'label' => 'Topics',
			'labels' => $topic_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => false,
			'show_in_quick_edit' => false,
			'description' => 'Associate the sermon to a topic.',
			'hierarchical' => true,
			'rewrite' => array(
				'slug' => 'topics',
				'with_front' => false
			),
			'show_in_rest'       => true,
			'rest_base'          => 'topics',
			'rest_controller_class' => 'WP_REST_Terms_Controller'
		);

		register_taxonomy( 'perelandra_sermon_topics', 'perelandra_sermon', $topic_args );
		register_taxonomy_for_object_type( 'perelandra_sermon_topics', 'perelandra_sermon' );

		$book_labels = array(
			'name' => 'Books',
			'singular_name' => 'Book',
			'menu_name' => 'Books of the Bible',
			'all_items' => 'All Books',
			'edit_item' => 'Edit Books',
			'view_item' => 'View Books',
			'update_item' => 'Update Books',
			'add_new_item'  => 'Add New Books',
			'new_item_name' => 'New Books',
			'parent_item' => 'Parent Books',
			'parent_item_colon' => 'Parent Books:',
			'search_items' => 'Search Books',
			'popular_items' => 'Popular Books',
			'seperate_items_with_commas' => 'Seperate books with commas',
			'add_or_remove_items' => 'Add or remove books',
			'choose_from_most_used' => 'Choose from the most used books',
			'not_found' => 'No books found'
		);

		$book_args = array(
			'label' => 'Books',
			'labels' => $book_labels,
			'public' => true,
			'show_ui' => true,
			'show_in_menu' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud' => false,
			'show_in_quick_edit' => false,
			'description' => 'Associate the sermon to a book.',
			'hierarchical' => true,
			'rewrite' => array(
				'slug' => 'books',
				'with_front' => false
			),
			'show_in_rest'       => true,
			'rest_base'          => 'books',
			'rest_controller_class' => 'WP_REST_Terms_Controller'
		);

		register_taxonomy( 'perelandra_sermon_books', 'perelandra_sermon', $book_args );
		register_taxonomy_for_object_type( 'perelandra_sermon_books', 'perelandra_sermon' );

    }

    /**
     * Register metaboxes for sermons
     *
     * @since 1.0
     */
    public function register_metaboxes() {
        $settings = new_cmb2_box( array(
    		'id'            => 'pl_sermon_settings',
    		'title'         => __( 'Sermon Details', 'perelandra-sermons' ),
    		'object_types'  => array( 'perelandra_sermon', ),
            'context'       => 'normal',
            'priority'      => 'high',
            'show_in_rest'  => WP_REST_Server::READABLE
    	) );

        $settings->add_field( array(
            'name'  => 'Video Embed',
            'desc'  => 'Add the link to the video from your hosting provider.',
            'id'    => 'pl_sermon_video_embed',
            'type'  => 'oembed'
        ) );

        $settings->add_field( array(
            'name'  => 'Audio File',
            'desc'  => 'If you would like to add an audio file for the sermon, upload it here.',
            'id'    => 'pl_sermon_audio_file',
            'type'  => 'file',
            'options'   => array(
                'url'   => false
            ),
            'text'  => array(
                'add_upload_file_text'  => 'Add Audio'
            )
        ) );

        $settings->add_field( array(
            'name'  => 'Date',
            'desc'  => 'Add the date of the sermon.',
            'id'    => 'pl_sermon_date',
            'type'  => 'text_date'
        ) );

        $settings->add_field( array(
            'name'  => 'Primary Bible Passage',
            'desc'  => 'Add the primary passage for the sermon.',
            'id'    => 'pl_sermon_passage',
            'type'  => 'text'
        ) );

        $settings->add_field( array(
            'name'  => 'Sermon Description',
            'id'    => 'pl_sermon_description',
            'type'  => 'wysiwyg'
        ) );

        $settings->add_field( array(
            'name'  => 'Transcript',
            'id'    => 'pl_sermon_transcript',
            'type'  => 'wysiwyg'
        ) );

        $settings->add_field( array(
            'name'  => 'Transcript File',
            'id'    => 'pl_sermon_transcript_file',
            'type'  => 'file',
            'options'   => array(
                'url'   => false
            ),
            'text' => array(
                'add_upload_file_text'  => 'Add Transcript File'
            )
        ) );

		$attachments = new_cmb2_box( array(
			'id'            => 'pl_sermon_attachments',
			'title'         => __( 'Attachments', 'perelandra-sermons' ),
			'object_types'  => array( 'perelandra_sermon', ),
			'cmb_styles' => false,
			'classes'   => 'pl-attachments-metabox',
			'show_in_rest'  => WP_REST_Server::READABLE
		) );

		$attachments_group_id = $attachments->add_field( array(
			'id'    => 'pl_sermon_attachments_group',
			'type'  => 'group',
			'description'   => 'Add attachments to the sermon.',
			'options'   => array(
				'group_title'   => __( 'Attachment {#}', 'perelandra-sermons' ),
				'add_button'    => __( 'Add Attachment', 'perelandra-sermons' ),
				'remove_button' => __( 'Remove', 'perelandra-sermons' ),
				'sortable'  => false
			)
		) );

		$attachments->add_group_field( $attachments_group_id, array(
			'name'  => 'Name',
			'id'    => 'name',
			'type'  => 'text'
		) );

		$attachments->add_group_field( $attachments_group_id, array(
			'name'  => 'File',
			'id'    => 'file',
			'type'  => 'file',
			'options'   => array(
				'url'   => true
			),
			'test'  => array(
				'add_upload_file_text' => 'Add File'
			)
		) );

		$podcast = new_cmb2_box( array(
			'id'            => 'pl_sermon_podcast',
			'title'         => __( 'Podcast Details', 'perelandra-sermons' ),
			'object_types'  => array( 'perelandra_sermon', ),
			'classes'    => 'pl-metabox',
			'cmb_style' => false,
			'show_in_rest'  => WP_REST_Server::READABLE
		) );

		$podcast->add_field( array(
			'name'    => 'Sermon Type',
			'id'      => 'pl_sermon_podcast_type',
			'type'    => 'radio_inline',
			'options' => array(
				'audio' => __( 'Audio', 'perelandra-sermons' ),
				'video'   => __( 'Video', 'perelandra-sermons' )
			),
			'default' => 'audio',
		) );

		$podcast->add_field( array(
			'name'  => 'Podcast File',
			'id'    => 'pl_sermon_podcast_file',
			'type'  => 'file'
		) );

		$podcast->add_field( array(
			'name'  => 'Duration',
			'id'    => 'pl_sermon_podcast_duration',
			'type'  => 'text',
			'desc'  => 'Add the duration of the media file.'
		) );

		$podcast->add_field( array(
			'name'  => 'Media Size',
			'id'    => 'pl_sermon_podcast_size',
			'type'  => 'text',
			'desc'  => 'Add the file size of the media file.'
		) );

		$podcast->add_field( array(
			'name'  => 'Explicit Status',
			'id'    => 'pl_sermon_podcast_explicit',
			'desc'  => 'Mark this sermon as explicit',
			'type'  => 'checkbox'
		) );

		$podcast->add_field( array(
			'name'  => 'Block Status',
			'id'    => 'pl_sermon_podcast_block',
			'desc'  => 'Block this sermon from appearing in the podcast.',
			'type'  => 'checkbox'
		) );

		$podcast->add_field( array(
			'name'  => 'Podcast Content',
			'id'    => 'pl_sermon_podcast_content',
			'desc'  => 'This content will be used for the longform content for the podcast Sermon and we will automatically truncate it for the short excerpt.',
			'type'  => 'wysiwyg'
		) );
    }

	/**
	 * Register metaboxes for taxonomies
	 *
	 * @since 1.0
	 */
	public function register_taxonomy_metaboxes() {

		$series_term = new_cmb2_box( array(
			'id'               => 'series_settings',
			'title'            => __( 'Sermon Series Settings', 'perelandra-sermons' ),
			'object_types'     => array( 'term' ),
			'taxonomies'       => array( 'perelandra_sermon_series' ),
			'new_term_section' => true,
			'show_in_rest'  => WP_REST_Server::READABLE
		) );

		$series_term->add_field( array(
			'name'     => __( 'Series Image', 'perelandra-sermons' ),
			'id'       => 'pl_series_image',
			'type'     => 'file',
			'options' => array(
				'url' => false,
			),
			'text'    => array(
				'add_upload_file_text' => 'Add Series Cover Image'
			)
		) );
	}

    /**
     * Add the RSS feed
     *
     * @since 1.0
     */
    public function add_rss_feed() {
        add_feed( 'sermons', array( $this, 'create_rss_feed' ) );
    }

    /**
     * Create the feed itself
     *
     * @since 1.0
     */
    public function create_rss_feed() {
        require_once plugin_dir_path( $this->file ) . '/views/feed.php';
    }

    /**
     * Change the enter title text
     *
     * @since 1.0
     */
    public function enter_title_here($title) {
        if ( get_post_type() == $this->posttype ) {
			$title = __( 'Enter sermon title here', 'perelandra-sermons' );
		}
		return $title;
    }

    /**
     * Create custom dashboard message
     * @param  array $messages Default messages
     * @return array           Modified messages
     */
	public function updated_messages( $messages ) {
	  global $post, $post_ID;

	  $messages[ $this->posttype ] = array(
	    0 => '',
	    1 => sprintf( __( 'Sermon updated. %sView Sermon%s.' , 'perelandra-sermons' ), '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    2 => __( 'Custom field updated.' , 'perelandra-sermons' ),
	    3 => __( 'Custom field deleted.' , 'perelandra-sermons' ),
	    4 => __( 'Sermon updated.' , 'perelandra-sermons' ),
	    5 => isset($_GET['revision']) ? sprintf( __( 'Sermon restored to revision from %s.' , 'perelandra-sermons' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
	    6 => sprintf( __( 'Sermon published. %sView sermon%s.' , 'perelandra-sermons' ), '<a href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    7 => __( 'Sermon saved.' , 'perelandra-sermons' ),
	    8 => sprintf( __( 'Sermon submitted. %sPreview sermon%s.' , 'perelandra-sermons' ), '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', '</a>' ),
	    9 => sprintf( __( 'Sermon scheduled for: %1$s. %2$sPreview sermon%3$s.' , 'perelandra-sermons' ), '<strong>' . date_i18n( __( 'M j, Y @ G:i' , 'perelandra-sermons' ), strtotime( $post->post_date ) ) . '</strong>', '<a target="_blank" href="' . esc_url( get_permalink( $post_ID ) ) . '">', '</a>' ),
	    10 => sprintf( __( 'Sermon draft updated. %sPreview sermon%s.' , 'perelandra-sermons' ), '<a target="_blank" href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post_ID ) ) ) . '">', '</a>' ),
	  );

	  return $messages;
	}

	/**
	 * Add custom columns
	 *
	 * @since 1.0
	 */
	public function add_sermon_columns( $columns ) {
		unset($columns['author']);
		unset($columns['date']);
	    return array_merge($columns,
	        array(
				'sermon_date'	=> __( 'Date', 'pl-sermons' ),
				'speaker' => __( 'Speaker', 'pl-sermons' ),
	            'series'	=> __( 'Series', 'pl-sermons' ),
				'featured'	=> __( 'Featured', 'pl-sermons' )
			)
		);
	}

	/**
	 * Custom Columns
	 *
	 * @since 1.0
	 */
	public function custom_columns( $column, $post_id ) {

		switch( $column ) {

			case 'sermon_date':

				$date = get_post_meta( $post_id, 'pl_sermon_date', true );

				if ( ! empty( $date ) ) {

					echo $date;

				}

			break;

			case 'speaker':

				$terms = get_the_term_list( $post_id, 'perelandra_sermon_speakers', '', ',', '' );

				if ( is_string( $terms ) ) {

					echo $terms;

				} else {

					_e( 'Unable to get speaker(s)', 'pl-sermons' );

				}

			break;

			case 'series':

				$terms = get_the_term_list( $post_id, 'perelandra_sermon_series', '', ', ', '' );

				if ( is_string( $terms ) ) {

					echo $terms;

				} else {

					_e( 'Unable to get series', 'pl-sermons' );

				}

			break;

			case 'featured':

				$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=pl_sermons_feature_sermon&sermon_id=' . $post_id ), 'pl-sermons-featured-sermon' );
				echo '<a href="' . esc_url( $url ) . '" aria-label="' . __( 'Toggle featured', 'pl-sermons' ) . '">';
				if ( $this->is_sermon_featured( $post_id ) ) {
					echo '<span class="pl-sermons-featured">' . __( 'Yes', 'pl-sermons' ) . '</span>';
				} else {
					echo '<span class="pl-sermons-featured not-featured">' . __( 'No', 'pl-sermons' ) . '</span>';
				}
				echo '</a>';

			break;

		}

	}

	/**
	 * Series custom columns
	 *
	 * @since 1.0
	 */
	public function series_custom_columns( $columns ) {

		$columns['series_featured'] = __( 'Featured', 'pl-sermons' );

		return $columns;

	}

	/**
	 * Add data to series custom columns
	 *
	 * @since 1.0
	 */
	public function series_custom_columns_content( $content, $column_name, $term_id ) {

		if ( $column_name == 'series_featured' ) {

			$url = wp_nonce_url( admin_url( 'admin-ajax.php?action=pl_sermons_feature_series&series_id=' . $term_id ), 'pl-sermons-featured-series' );

			$content = '<a href="' . esc_url( $url ) . '" aria-label="' . __( 'Toggle featured', 'pl-sermons' ) . '">';

			if ( $this->is_series_featured( $term_id ) ) {
				$content .= '<span class="pl-sermons-featured">' . __( 'Yes', 'pl-sermons' ) . '</span>';
			} else {
				$content .= '<span class="pl-sermons-featured not-featured">' . __( 'No', 'pl-sermons' ) . '</span>';
			}

			$content .= '</a>';

		}

		return $content;

	}

	/**
	 * Enqueue admin styles
	 *
	 * @since 1.0
	 */
	public function admin_styles() {

		wp_enqueue_style( 'admin-css', PL_PLUGINS_DIR_URI . 'assets/dist/css/admin.css', false, $this->version );

	}

	/**
	 * Check if sermon is featured
	 *
	 * @since 1.0
	 */
	static function is_sermon_featured( $post_id ) {

		$featured = get_post_meta( $post_id, 'pl_sermon_featured', true );

		if ( $featured == 'on' ) {
			return true;
		}

		return false;

	}

	/**
	 * Check if series is featured
	 *
	 * @since 1.0
	 */
	static function is_series_featured( $term_id ) {

		$featured = get_term_meta( $term_id, 'pl_series_featured', true );

		if ( $featured == 'on' ) {

			return true;

		}

		return false;

	}

	/**
	 * Activate the plugin
	 *
	 * @since 1.0
	 */
	public function activate_plugin() {

		$options = array();

		$this->register_post_type();

		$this->add_rss_feed();

		flush_rewrite_rules( true );

		set_transient( '_pl_sermons_redirect', true, 30 );

		update_option( 'pl_sermons_version', $this->version );

	}

	/**
	 * Deactivate the plugin
	 *
	 * @since 1.0
	 */
	public function deactivate_plugin() {
		flush_rewrite_rules();
	}

	/**
	 * Theme support notice
	 *
	 * @since 1.0.6
	 */
	public function theme_support_notice() {
		?>
		<div class="notice notice-success is-dismissible">
			<p><strong><?php _e( 'Thank you for installing Perelandra Sermons!', 'pl-sermons' ); ?></strong></p>
			<p><?php _e( 'It looks like your theme doesn\'t support Perelandra Sermons out of the box. Its not a problem, you can easily fix that up by following these instructions.', 'pl-sermons' ); ?></p>
			<p><a href="http://perelandrawp.helpscoutdocs.com/article/15-theme-support?auth=true">View Documentation</a></p>
		</div>
	    <?php
	}

	/**
	 * Do welcome screen redirect
	 *
	 * @since 1.0
	 */
	public function do_welcome_screen_redirect() {

		// Make sure transient exists
		if ( ! get_transient( '_pl_sermons_redirect' ) ) return;

		// Delete the transient so that we won't show this again
		delete_transient( '_pl_sermons_redirect' );

		// Make sure we aren't activating from multisite
		if ( is_network_admin() || isset( $_GET['activate_multi'] ) ) return;

		// Redirect to the page
		wp_safe_redirect( add_query_arg( array(
			'page' => 'pl-sermons-getting-started'
		), admin_url( 'index.php' ) ) );

	}

	/**
	 * Create the welcome screen
	 *
	 * @since 1.0
	 */
	public function welcome_screens() {

		// Add the page
		add_dashboard_page(
			__( 'Welcome to Perelandra Sermons', 'pl-sermons' ),
			__( 'Welcome to Perelandra Sermons', 'pl-sermons' ),
			'read',
			'pl-sermons-about',
			array( $this, 'about_screen' )
		);

		// Getting started page
		add_dashboard_page(
			__( 'Getting Started', 'pl-sermons' ),
			__( 'Getting Started', 'pl-sermons' ),
			'read',
			'pl-sermons-getting-started',
			array( $this, 'getting_started_screen' )
		);

		remove_submenu_page( 'index.php', 'pl-sermons-about' );
		remove_submenu_page( 'index.php', 'pl-sermons-getting-started' );

	}

	/**
	 * Welcome Message
	 *
	 * @since 1.0
	 */
	private function welcome_message() {
		?>
		<div id="pl-sermons-header">
			<img class="pl-sermons-badge" src="<?php echo PL_PLUGINS_DIR_URI . 'assets/images/logo.png'; ?>" alt="<?php _e( 'Perelandra Sermons', 'pl-sermons' ); ?>" />
			<h1><?php echo __( 'Perelandra Sermons', 'pl-sermons' ); ?></h1>
			<p class="about-text">
				<?php _e( 'Perelandra Sermons is here to help you display and manage sermons more easily than ever before.', 'pl-sermons' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Navigation tabs
	 *
	 * @access public
	 * @since 1.9
	 * @return void
	 */
	public function tabs() {
		$selected = isset( $_GET['page'] ) ? $_GET['page'] : 'pl-sermons-getting-started';
		?>
		<h1 class="nav-tab-wrapper">
			<a class="nav-tab <?php echo $selected == 'pl-sermons-getting-started' ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'pl-sermons-getting-started' ), 'index.php' ) ) ); ?>">
				<?php _e( 'Getting Started', 'pl-sermons' ); ?>
			</a>
		</h1>
		<?php
	}

	/**
	 * Welcome Screen Content
	 *
	 * @since 1.0
	 */
	public function about_screen() {
		?>
		<div class="wrap about-wrap pl-sermons-about-wrap">
			<?php $this->welcome_message(); ?>
			<?php $this->tabs(); ?>
		</div>
		<?php
	}

	/**
	 * Getting Started Screen
	 *
	 * @since 1.0
	 */
	public function getting_started_screen() {
		if ( get_option( 'permalink_structure' ) ) {
			$feed_slug = apply_filters( 'pl_sermons_feed_slug', 'sermons' );
			$url = trailingslashit( home_url() ) . 'feed/' . $feed_slug;
		} else {
			$url = trailingslashit( home_url() ) . '?feed=perelandra_sermon';
		}
		?>
		<div class="wrap about-wrap pl-sermons-about-wrap">
			<?php $this->welcome_message(); ?>
			<?php $this->tabs(); ?>
			<p class="about-description">
				<?php _e( 'Learn how to get started with Perelandra Sermons. It will, hopefully, be a simple and rewarding process to create and manage all of your sermons.', 'pl-sermons' ); ?>
			</p>
			<div class="changelog">
				<div class="pl-sermons-step">
					<div class="pl-sermons-step-content">
						<h3><?php _e('1. Creating Your First Sermon', 'pl-sermons' ); ?></h3>
						<h4><a href="<?php echo admin_url( 'post-new.php?post_type=perelandra_sermon' ); ?>"><?php _e( 'Sermons &rarr; Add New', 'pl-sermons' ); ?></a></h4>
						<p>
							<?php _e( 'Everything that you will need to manage your sermons can be found in the "Sermons" menu. To create a sermon, click "Add New" and fill out all of the pertinent information for your sermon.', 'pl-sermons' ); ?>
						</p>
						<h4><?php _e( 'Sermon Audio &amp; Video', 'pl-sermons' ); ?></h4>
						<p>
							<?php _e( 'You choose how you want to host your videos. Perelandra Sermons will automatically embed your videos from Vimeo, YouTube, Wistia, and other services. Upload your audio file and we will automatically create a media player for your audio on the website.', 'pl-sermons' ); ?>
						</p>
					</div>
					<div class="pl-sermons-step-media">
						<img src="<?php echo PL_PLUGINS_DIR_URI . 'assets/images/screenshots/edit-sermon.png'; ?>">
					</div>
				</div>
			</div>
			<div class="changelog">
				<div class="pl-sermons-step">
					<div class="pl-sermons-step-content">
						<h3><?php _e( '2. Categorizing Your Sermons', 'pl-sermons' ); ?></h3>
						<h4><a href="<?php echo admin_url( 'edit-tags.php?taxonomy=perelandra_sermon_series&post_type=perelandra_sermon' ); ?>"><?php _e( 'Series', 'pl-sermons' ); ?></a>, <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=perelandra_sermon_speakers&post_type=perelandra_sermon' ); ?>"><?php _e( 'Speakers', 'pl-sermons' ); ?></a>, <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=perelandra_sermon_topics&post_type=perelandra_sermon' ); ?>"><?php _e( 'Topics', 'pl-sermons' ); ?></a>, <a href="<?php echo admin_url( 'edit-tags.php?taxonomy=perelandra_sermon_books&post_type=perelandra_sermon' ); ?>"><?php _e( 'Books of the Bible', 'pl-sermons' ); ?></a></h4>
						<p>
							<?php _e( 'Categorizing your sermons is as easy as categorizing a blog post. The categories above are already set up for you to easily categorize each sermon while you are creating it. The plugin will automatically display the sermons categorized appropriately on your website.', 'pl-sermons' ); ?>
						</p>
						<p>
							<?php _e( 'Feature your most recent series to display it prominently on the sermons archive. Otherwise, Perelandra Sermons will display the three most recent series.', 'pl-sermons' ); ?>
						</p>
					</div>
					<div class="pl-sermons-step-media">
						<img src="<?php echo PL_PLUGINS_DIR_URI . 'assets/images/screenshots/edit-series.png'; ?>">
					</div>
				</div>
			</div>
			<div class="changelog">
				<div class="pl-sermons-step fifty-fifty">
					<div class="pl-sermons-step-content-fifty">
						<h3><?php _e( '3. Displaying Sermons', 'pl-sermons' ); ?></h3>
						<h4><a href="<?php echo get_post_type_archive_link( 'perelandra_sermon' ); ?>"><?php _e( 'View Sermons Archive', 'pl-sermons' ); ?></a></h4>
						<p>
							<?php _e( 'The sermon will automatically display an archive of all of your sermons. Perelandra Sermons displays your sermons a little differently from other plugins. You don\'t just get a list, you can feature sermons and series on the main archive page and view the most recent sermons and series.', 'pl-sermons' ); ?>
						</p>
					</div>
					<div class="pl-sermons-step-content-fifty">
						<h3><?php _e( '4. Publishing Your Podcast', 'pl-sermons' ); ?></h3>
						<h4><a href="<?php echo esc_url( $url ); ?>" target="_blank"><?php _e( 'View Podcast Feed', 'pl-sermons' ); ?></a> &middot; <a href="<?php echo admin_url( 'edit.php?post_type=perelandra_sermon&page=sermon_settings' ); ?>"><?php _e( 'Podcast Settings', 'pl-sermons' ); ?></a></h4>
						<p>
							<?php _e( 'Want to publish your sermons to a podcast? Perelandra Sermons will take care of that for you. All you will need to do is update the Podcast settings and submit your RSS feed to iTunes or Google.', 'pl-sermons' ); ?>
						</p>
						<p>
							<!-- TODO: Add Link -->
							<a href=""><?php _e( 'Podcast Documentation', 'pl-sermons' ); ?></a>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
