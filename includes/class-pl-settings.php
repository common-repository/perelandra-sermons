<?php
/**
 * Settings class
 *
 * Handles plugin settings page
 *
 * @author      Wes Cole
 * @category    Class
 * @package     PL_Sermons/Classes
 * @since       1.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PL_Sermons_Settings {
    private $settings_base;
    private $settings;

    public function __construct( $file, $version ) {
        $this->file = $file;
        $this->version = $version;
        $this->settings_base = 'pl_sermons_';
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) , 10 );
		add_action( 'admin_init', array( $this, 'add_caps' ), 1 );
        add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
        add_action( 'init', array( $this, 'load_settings' ), 11 );
        add_action( 'admin_init' , array( $this, 'register_settings' ) );
    }

    /**
     * Enqueue admin scripts
     *
     * @since 1.0
     */
    public function enqueue_scripts() {
        global $pagenow;
		if ( isset( $_GET['page'] ) && 'sermon_settings' == $_GET['page'] ) {
            wp_enqueue_media();
            wp_enqueue_script( 'main-js', plugins_url( '/assets/src/js/admin.js', $this->file ), array('jquery'), $this->version, true );
		}
    }

    /**
     * Add the menu page
     *
     * @since 1.0
     */
    public function add_menu_page() {
        add_submenu_page(
            'edit.php?post_type=perelandra_sermon',
            __( 'Settings', 'pl-sermons' ),
            __( 'Settings', 'pl-sermons' ),
            'manage_pl_sermons',
            'sermon_settings',
            array( $this , 'settings_page' )
        );
    }

    /**
	 * Check to see if the given role has a cap, and add if it doesn't exist.
	 *
	 * @param  object $role User Cap object, part of WP_User.
	 * @param  string $cap  Cap to test against.
	 * @return void
	 */
	public function maybe_add_cap( $role, $cap ) {
		// Update the roles, if needed.
		if ( ! $role->has_cap( $cap ) ) {
			$role->add_cap( $cap );
		}
	}

    /**
	 * Add cabilities to edit pl-sermons settings to admins, and editors.
	 */
	public function add_caps() {
		$roles = apply_filters( 'pl_sermons_manage_sermons', array( 'administrator', 'editor' ) );
		foreach( $roles as $the_role ) {
			$role = get_role( $the_role );
			$caps = array(
				'manage_pl_sermons',
			);
			foreach ( $caps as $cap ) {
				$this->maybe_add_cap( $role, $cap );
			}
		}
	}

    /**
     * Array of all settings fields
     *
     * @since 1.0
     */
    private function settings_fields() {

        $template_options = array(
            ''  => __( 'Default Perelandra Template', 'pl-sermons' ),
            'default'   => __( 'Default Page Template', 'pl-sermons' ),
        );

        $templates = wp_get_theme()->get_page_templates();
        foreach ( $templates as $key => $template ) {
        	$template_options[$key] = $template;
        }

		$subcategory_options = array(
			'' => __( '-- None --', 'pl-sermons' ),
            'Buddhism' => array( 'label' => __( 'Buddhism', 'pl-sermons' ), 'group' => __( 'Religion & Spirituality', 'pl-sermons' ) ),
			'Christianity' => array( 'label' => __( 'Christianity', 'pl-sermons' ), 'group' => __( 'Religion & Spirituality', 'pl-sermons' ) ),
			'Hinduism' => array( 'label' => __( 'Hinduism', 'pl-sermons' ), 'group' => __( 'Religion & Spirituality', 'pl-sermons' ) ),
			'Islam' => array( 'label' => __( 'Islam', 'pl-sermons' ), 'group' => __( 'Religion & Spirituality', 'pl-sermons' ) ),
			'Judaism' => array( 'label' => __( 'Judaism', 'pl-sermons' ), 'group' => __( 'Religion & Spirituality', 'pl-sermons' ) ),
			'Other' => array( 'label' => __( 'Other', 'pl-sermons' ), 'group' => __( 'Religion & Spirituality', 'pl-sermons' ) ),
			'Spirituality' => array( 'label' => __( 'Spirituality', 'pl-sermons' ), 'group' => __( 'Religion & Spirituality', 'pl-sermons' ) ),
		);

        $settings = array();

        $settings['general'] = array(
            'title' => __( 'General', 'pl-sermons' ),
            'description'   => __( '', 'pl-sermons' ),
            'fields'    => array(
                array(
                    'id'    => 'display_recent_series',
                    'label' => __( 'Display Recent Series', 'pl-sermons' ),
                    'description'   => __( 'Select if you want to display the most recent series on the Sermons archive', 'pl-sermons' ),
                    'type'  => 'checkbox',
                    'default'   => ''
                ),
                array(
                    'id'    => 'archive_layout',
                    'label' => __( 'Sermons Archive Layout', 'pl-sermons' ),
                    'description'   => __( 'Select the type of layout that you would like for your Sermons archive', 'pl-sermons' ),
                    'type'  => 'radio',
                    'options'		=> array(
                        'inline'    => __( 'Inline', 'pl-sermons' ),
                        'grid'  => __( 'Grid', 'pl-sermons' )
                    ),
					'default'		=> 'inline',
                ),
                array(
                    'id'    => 'default_image',
                    'label' => __( 'Default Image', 'pl-sermons' ),
                    'description'   => __( 'The image that will be used if series or featured images are missing.', 'pl-sermons' ),
                    'type'  => 'image',
                    'default'   => '',
                    'placeholder'   => '',
                    'callback'  => 'esc_url_raw'
                ),
            )
        );

        $settings['podcast'] = array(
            'title' => __( 'Podcast', 'pl-sermons' ),
            'description'   => __( '', 'pl-sermons' ),
            'fields'    => array(
                array(
                    'id'    => 'podcast_title',
                    'label' => __( 'Title', 'pl-sermons' ),
                    'description'   => __( 'Enter the title for your Podcast', 'pl-sermons' ),
                    'type'  => 'text',
                    'default'   => get_bloginfo( 'name' ),
                    'placeholder'   => get_bloginfo( 'name' ),
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_subtitle',
                    'label' => __( 'Sub-Title', 'pl-sermons' ),
                    'description'   => __( 'Enter the sub-title for your podcast', 'pl-sermons' ),
                    'type'  => 'text',
                    'default'   => get_bloginfo( 'description' ),
                    'placeholder'   => get_bloginfo( 'description' ),
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_description',
                    'label' => __( 'Description/Summary', 'pl-sermons' ),
                    'description'   => __( 'Enter the description/summary for your podcast', 'pl-sermons' ),
                    'type'  => 'textarea',
                    'default'   => get_bloginfo( 'description' ),
                    'placeholder'   => get_bloginfo( 'description' ),
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_author',
                    'label' => __( 'Author', 'pl-sermons' ),
                    'description'   => __( 'Enter the author of the podcast', 'pl-sermons' ),
                    'type'  => 'text',
                    'default'   => get_bloginfo( 'name' ),
                    'placeholder'   => get_bloginfo( 'name' ),
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_owner_name',
                    'label' => __( 'Owner Name', 'pl-sermons' ),
                    'description'   => __( 'Enter the podcast owner\'s name', 'pl-sermons' ),
                    'type'  => 'text',
                    'default'   => get_bloginfo( 'name' ),
                    'placeholder'   => get_bloginfo( 'name' ),
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_owner_email',
                    'label' => __( 'Owner Email', 'pl-sermons' ),
                    'description'   => __( 'Enter the podcast owner\'s email', 'pl-sermons' ),
                    'type'  => 'text',
                    'default'   => get_bloginfo( 'admin_email' ),
                    'placeholder'   => get_bloginfo( 'admin_email' ),
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_language',
                    'label' => __( 'Language', 'pl-sermons' ),
                    'description'   => sprintf( __( 'The language your podcast is broadcasted in %1$sISO-639-1 format%2$s.', 'pl-sermons' ), '<a href="' . esc_url( 'http://www.loc.gov/standards/iso639-2/php/code_list.php' ) . '" target="' . wp_strip_all_tags( '_blank' ) . '">', '</a>' ),
                    'type'  => 'text',
                    'default'   => get_bloginfo( 'language' ),
                    'placeholder'   => get_bloginfo( 'language' ),
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_copyright',
                    'label' => __( 'Copyright', 'pl-sermons' ),
                    'description'   => __( 'Enter the copyright line for your podcast', 'pl-sermons' ),
                    'type'  => 'text',
                    'default'   => '&#xA9; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ),
                    'placeholder'   => '&#xA9; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ),
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_website',
                    'label' => __( 'Website', 'pl-sermons' ),
                    'description'   => __( 'Enter the website for the podcast', 'pl-sermons' ),
                    'type'  => 'text',
                    'default'   => get_bloginfo( 'url' ),
                    'placeholder'   => get_bloginfo( 'url' ),
                    'callback'  => 'esc_url'
                ),
                array(
                    'id'    => 'podcast_image',
                    'label' => __( 'Cover Image', 'pl-sermons' ),
                    'description'   => __( 'The primary cover image. Must be 1400x1400px.', 'pl-sermons' ),
                    'type'  => 'image',
                    'default'   => '',
                    'placeholder'   => '',
                    'callback'  => 'esc_url_raw'
                ),
                array(
                    'id'    => 'podcast_category',
                    'label' => __( 'Primary Category', 'pl-sermons' ),
                    'description'   => __( 'Select your podcast\'s primary category', 'pl-sermons' ),
                    'type'  => 'text',
                    'default'   => 'Religion & Spirituality',
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_secondary_category',
                    'label' => __( 'Secondary Category', 'pl-sermons' ),
                    'descriptoin'   => __( 'Select your podcast\'s secondary category', 'pl-sermons' ),
                    'type'  => 'select',
                    'options'   => $subcategory_options,
                    'default'   => '',
                    'callback'  => 'wp_strip_all_tags'
                ),
                array(
                    'id'    => 'podcast_explicit',
                    'label' => __( 'Explicit', 'pl-sermons' ),
                    'description' => __( 'We are assuming your podcast isn\'t explicit. We just have to ask.', 'pl-sermons' ),
                    'type'  => 'checkbox',
                    'default' => '',
                )
            )
        );

        $settings = apply_filters( 'pl_sermons_settings_fields', $settings );
        return $settings;
    }

    /**
     * Load settings into variable
     *
     * @since 1.0
     */
    public function load_settings() {
        $this->settings = $this->settings_fields();
    }

    /**
     * Loop through settings and register them
     *
     * @since 1.0
     */
    public function register_settings() {
        if ( is_array( $this->settings ) ) {
            // Check posted/selected tab
			$current_section = 'general';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}

            foreach( $this->settings as $section => $data ) {
                if ( $current_section && $current_section != $section ) {
					continue;
				}

                // Set the settings sections
                $section_title = $data['title'];
                add_settings_section( $section, $section_title, array( $this, 'settings_section' ), 'pl_sermons_settings' );

                // Add all settings fields
                foreach( $data['fields'] as $field ) {
                    $validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

                    $option_name = $this->settings_base . $field['id'];
                    register_setting( 'pl_sermons_settings', $option_name, $validation );

                    // Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this, 'display_field' ), 'pl_sermons_settings', $section, array( 'field' => $field, 'prefix' => $this->settings_base ) );
                }
            }
        }
    }

    /**
     * Settings Section Markup
     *
     * @since 1.0
     */
    public function settings_section( $section ) {
        $html = '<p>' . $this->settings[$section['id']]['description'] . '</p>' . "\n";
        echo $html;
    }

    /**
     * Display all of the fields
     *
     * @since 1.0
     */
    public function display_field( $args ) {
        $field = $args['field'];

        $html = '';
        $option_name = $this->settings_base . $field['id'];

        // Get the default for the field
        $default = '';
        if ( isset( $field['default'] ) ) {
            $default = $field['default'];
        }

        // Get the value for the field
        $value = get_option( $option_name, $default );

        switch( $field['type'] ) {
            case 'text':
                $html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" value="' . esc_attr( $value ) . '" class="large-text"/><span class="description">' . $field['description'] . '</span>' . "\n";
            break;
            case 'textarea':
				$html .= '<textarea id="' . esc_attr( $field['id'] ) . '" rows="5" cols="50" name="' . esc_attr( $option_name ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" class="large-text">' . $value . '</textarea><br/><span class="description">' . $field['description'] . '</span>'. "\n";
			break;
            case 'checkbox':
				$checked = '';
				if ( $value && 'on' == $value ){
					$checked = 'checked="checked"';
				}
				$html .= '<input id="' . esc_attr( $field['id'] ) . '" type="' . $field['type'] . '" name="' . esc_attr( $option_name ) . '" ' . $checked . ' class="' . $class . '"/><span class="description">' . wp_kses_post( $field['description'] ) . '</span>' . "\n";
			break;
            case 'select':
				$html .= '<select name="' . esc_attr( $option_name ) . '" id="' . esc_attr( $field['id'] ) . '">';
				$prev_group = '';
				foreach ( $field['options'] as $k => $v ) {
					$group = '';
					if ( is_array( $v ) ) {
						if ( isset( $v['group'] ) ) {
							$group = $v['group'];
						}
						$v = $v['label'];
					}
					if ( $prev_group && $group != $prev_group ) {
						$html .= '</optgroup>';
					}
					$selected = false;
					if ( $k == $value ) {
						$selected = true;
					}
					if ( $group && $group != $prev_group ) {
						$html .= '<optgroup label="' . esc_attr( $group ) . '">';
					}
					$html .= '<option ' . selected( $selected, true, false ) . ' value="' . esc_attr( $k ) . '">' . esc_html( $v ) . '</option>';
					$prev_group = $group;
				}
				$html .= '</select>';
			break;
            case 'image':
				$html .= '<img id="' . esc_attr( $option_name ) . '_preview" src="' . esc_attr( $value ) . '" style="max-width:400px;height:auto;" /><br/>' . "\n";
				$html .= '<input id="' . esc_attr( $option_name ) . '_button" type="button" class="button" value="'. __( 'Upload new image' , 'pl-sermons' ) . '" />' . "\n";
				$html .= '<input id="' . esc_attr( $option_name ) . '_delete" type="button" class="button" value="'. __( 'Remove image' , 'pl-sermons' ) . '" />' . "\n";
				$html .= '<input id="' . esc_attr( $option_name ) . '" type="hidden" name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $value ) . '"/><br/>' . "\n";
			break;
            case 'radio':
                $html .= '<span class="description">' . $field['description'] . '</span>';
				foreach ( $field['options'] as $k => $v ) {
					$checked = false;
                    $checked_class = '';
					if ( $k == $value ) {
						$checked = true;
                        $checked_class = 'selected';
					}
					$html .= '<label for="' . esc_attr( $field['id'] . '_' . $k ) . '" class="radio-image ' . $checked_class . '"><span class="screen-reader-text">' . $v . '</span><input type="radio" ' . checked( $checked, true, false ) . ' name="' . esc_attr( $option_name ) . '" value="' . esc_attr( $k ) . '" id="' . esc_attr( $field['id'] . '_' . $k ) . '" class="screen-reader-text" /><img src="' . plugins_url( '/assets/images/' . $k . '.png', $this->file ) . '" /></label>';
				}
			break;
        }

        echo $html;
    }

    /**
     * Generate the HTML for the Settings Page
     *
     * @since 1.0
     */
    public function settings_page() {
        $html = '<div id="pl-sermons-settings" class="wrap pl-sermons">' . "\n";
            $html .= '<h1>' . __( 'Perelandra Sermon Settings' , 'pl-sermons' ) . '</h1>' . "\n";

            $tab = 'general';
			if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
				$tab = $_GET['tab'];
			}

            // Show page tabs
			if ( is_array( $this->settings ) ) {
				$html .= '<h2 class="nav-tab-wrapper">' . "\n";
				$c = 0;
				foreach ( $this->settings as $section => $data ) {
					// Set tab class
					$class = 'nav-tab';
					if ( ! isset( $_GET['tab'] ) ) {
						if ( 0 == $c ) {
							$class .= ' nav-tab-active';
						}
					} else {
						if ( isset( $_GET['tab'] ) && $section == $_GET['tab'] ) {
							$class .= ' nav-tab-active';
						}
					}
					// Set tab link
					$tab_link = add_query_arg( array( 'tab' => $section ) );
					if ( isset( $_GET['settings-updated'] ) ) {
						$tab_link = remove_query_arg( 'settings-updated', $tab_link );
					}
					// Output tab
					$html .= '<a href="' . esc_url( $tab_link ) . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";
					++$c;
				}
				$html .= '</h2>' . "\n";
			}

            if ( isset( $_GET['settings-updated'] ) ) {
				$html .= '<br/><div class="updated notice notice-success is-dismissible">
					        <p>' . sprintf( __( '%1$s settings updated.', 'seriously-simple-podcasting' ), '<b>' . str_replace( '-', ' ', ucfirst( $tab ) ) . '</b>' ) . '</p>
					    </div>';
			}

            $html .= '<div id="pl-sermons-main">';
                $html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";
    				// Get settings fields
    				ob_start();
    				settings_fields( 'pl_sermons_settings' );
    				do_settings_sections( 'pl_sermons_settings' );
    				$html .= ob_get_clean();
    				// Submit button
    				$html .= '<p class="submit">' . "\n";
    					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";
    					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings' , 'pl-sermons' ) ) . '" />' . "\n";
    				$html .= '</p>' . "\n";
    			$html .= '</form>' . "\n";
            $html .= '</div>';
        $html .= '</div>';

        echo $html;
    }
}
