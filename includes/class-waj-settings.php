<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    waj
 * @subpackage waj/includes
 */
/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    waj
 * @subpackage waj/includes
 * @author     Your Name <email@example.com>
 */
if (!defined('ABSPATH'))
	exit;

class WAJ_Settings {

	private $name;
	private $settings_base;
	private $settings;
        private static $settings_vals;        

	public function __construct($name) {
		$this->name = $name;
		$this->settings_base = $name . '_';
		$this->settings = $this->settings_fields();
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public static function get_settings($name = '') {
		return get_option(WAJ::NAME . '_settings');
	}
        
        /**
         * return setting
         * @return void
         */
        public static function get_setting($option, $default = false) {
            if (empty(self::$settings_vals)) {
                self::$settings_vals = get_option(WAJ::NAME . '_settings');
            }

            if ( !empty(self::$settings_vals[$option]) ) {
                return self::$settings_vals[$option];
            } else {
                return $default;
            }
        }        

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields() {

		$settings['general'] = array(
			 'title' => __('General settings', 'waj'),
			 'description' => __('These are fairly standard form input fields.', 'waj'),
			 'fields' => array(
				  // USER NOTIFY
				  array(
						'id' => 'order_dropbdown',
						'label' => __('Show products order dropbdown?', 'waj'),
						'description' => __('Yes.', 'waj'),
						'type' => 'checkbox',
						'default' => true,
				  ),
				  array(
						'id' => 'category_dropbdown',
						'label' => __('Show category dropbdown?', 'waj'),
						'description' => __('Yes.', 'waj'),
						'type' => 'checkbox',
						'default' => true,
				  ),
				  // Next / Prev
				  array(
						'id' => 'next_text',
						'label' => __('Next link text', 'waj'),
						'description' => '',
						'type' => 'text',
						'default' => 'Next',
						'placeholder' => ''
				  ),
                        
				  array(
						'id' => 'prev_text',
						'label' => __('Prev link text', 'waj'),
						'description' => '',
						'type' => 'text',
						'default' => 'Prev',
                                                'placeholder' => ''
				  ),
				  array(
						'id' => 'color',
						'label' => __('Links color', 'waj'),
						'description' => __('Default link color', 'waj'),
						'type' => 'color',
						'default' => '#7899AE',
						'placeholder' => ''
				  ),
				  // ADMIN PRE PAYMENT NOTIFY                                                        
				  array(
						'id' => 'active_color',
						'label' => __('Active links color', 'waj'),
						'description' => __('Link color on hover.', 'waj'),
						'type' => 'color',
						'default' => '#faa700',
                                                'placeholder' => '',
				  ),
				  array(
						'id' => 'inactive_color',
						'label' => __('Inactive links color', 'waj'),
						'description' => __('Disabled link color.', 'waj'),
						'type' => 'color',
						'default' => '#C4C4C4',
                                                'placeholder' => '',
				  ),
				  array(
						'id' => 'pagination_backgroud_color',
						'label' => __('Pagination backgroud color', 'waj'),
						'description' => __('Pagination block backgroud color', 'waj'),
						'type' => 'color',
						'default' => '#ebebeb',
                                                'placeholder' => '',
				  ),
			 )
		);

		$settings = apply_filters('plugin_settings_fields', $settings);

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function save_settings() {
		if (is_array($this->settings)) {
			$options = array();
			$field_id = '';
			foreach ($this->settings as $section => $data) {

				// Add section to page
				//add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), 'plugin_settings' );

				foreach ($data['fields'] as $field) {
					$field_id = $this->settings_base . $field['id'];

					// Register field
					if (isset($_POST[$field_id])) {
						if ($field['type'] == 'textarea') {
							$options[$field['id']] = $_POST[$field_id];
						} elseif ($field['type'] !== 'checkbox') {
							//$options[ $field['id'] ] = $this->validate_field( sanitize_title( $_POST[ $field_id ] ) );
							$options[$field['id']] = sanitize_text_field($_POST[$field_id]);
						} else {
							$options[$field['id']] = ($_POST[$field_id]) ? 1 : 0;
						}
					} else {
						$options[$field['id']] = '';
					}
				}
			}
			if (is_array($options)) {
				update_option($this->settings_base . 'settings', $options);
				return $options;
			}
		}

		//register_setting( 'wsds_settings', $data, $validation );
	}

	public function settings_section($section) {
		$html = '<p> ' . $this->settings[$section['id']]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Generate HTML for displaying fields
	 * @param  array $args Field data
	 * @return void
	 */
	public function display_field($field) {

		//$field = $args['field'];

		$html = '';

		$option_name = $this->settings_base . $field['id'];
		$settings = self::get_settings($this->name);

		$data = '';
		if (isset($settings[$field['id']])) {
			$data = $settings[$field['id']];
		} elseif (isset($field['default'])) {
			$data = $field['default'];
		}

		switch ($field['type']) {

			case 'text':
			case 'password':
			case 'number':
				$html .= '<input id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '" value="' . $data . '"/>' . "\n";
				break;

			case 'text_secret':
				$html .= '<input id="' . esc_attr($field['id']) . '" type="text" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '" value=""/>' . "\n";
				break;

			case 'textarea':
				$html .= '<textarea id="' . esc_attr($field['id']) . '" rows="5" cols="50" name="' . esc_attr($option_name) . '" placeholder="' . esc_attr($field['placeholder']) . '">' . esc_attr($data) . '</textarea><br/>' . "\n";
				break;

			case 'checkbox':
				$checked = '';
				if ($data) {
					$checked = 'checked="checked"';
				}
				$html .= '<input id="' . esc_attr($field['id']) . '" type="' . $field['type'] . '" name="' . esc_attr($option_name) . '" ' . $checked . '/>' . "\n";
				break;

			case 'checkbox_multi':
				foreach ($field['options'] as $k => $v) {
					$checked = false;
					if (in_array($k, $data)) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr($field['id'] . '_' . $k) . '"><input type="checkbox" ' . checked($checked, true, false) . ' name="' . esc_attr($option_name) . '[]" value="' . esc_attr($k) . '" id="' . esc_attr($field['id'] . '_' . $k) . '" /> ' . $v . '</label> ';
				}
				break;

			case 'radio':
				foreach ($field['options'] as $k => $v) {
					$checked = false;
					if ($k == $data) {
						$checked = true;
					}
					$html .= '<label for="' . esc_attr($field['id'] . '_' . $k) . '"><input type="radio" ' . checked($checked, true, false) . ' name="' . esc_attr($option_name) . '" value="' . esc_attr($k) . '" id="' . esc_attr($field['id'] . '_' . $k) . '" /> ' . $v . '</label> ';
				}
				break;

			case 'select':
				$html .= '<select name="' . esc_attr($option_name) . '" id="' . esc_attr($field['id']) . '">';
				foreach ($field['options'] as $k => $v) {
					$selected = false;
					if ($k == $data) {
						$selected = true;
					}
					$html .= '<option ' . selected($selected, true, false) . ' value="' . esc_attr($k) . '">' . $v . '</option>';
				}
				$html .= '</select> ';
				break;

			case 'select_multi':
				$html .= '<select name="' . esc_attr($option_name) . '[]" id="' . esc_attr($field['id']) . '" multiple="multiple">';
				foreach ($field['options'] as $k => $v) {
					$selected = false;
					if (in_array($k, $data)) {
						$selected = true;
					}
					$html .= '<option ' . selected($selected, true, false) . ' value="' . esc_attr($k) . '" />' . $v . '</label> ';
				}
				$html .= '</select> ';
				break;

			case 'image':
				$image_thumb = '';
				if ($data) {
					$image_thumb = wp_get_attachment_thumb_url($data);
				}
				$html .= '<img id="' . $option_name . '_preview" class="image_preview" src="' . $image_thumb . '" /><br/>' . "\n";
				$html .= '<input id="' . $option_name . '_button" type="button" data-uploader_title="' . __('Upload an image', 'waj') . '" data-uploader_button_text="' . __('Use image', 'waj') . '" class="image_upload_button button" value="' . __('Upload new image', 'waj') . '" />' . "\n";
				$html .= '<input id="' . $option_name . '_delete" type="button" class="image_delete_button button" value="' . __('Remove image', 'waj') . '" />' . "\n";
				$html .= '<input id="' . $option_name . '" class="image_data_field" type="hidden" name="' . $option_name . '" value="' . $data . '"/><br/>' . "\n";
				break;

			case 'color':
				$html .= '<div class="color-picker ' . esc_attr($option_name) . '-class" style="position:relative;">';
				$html .= '<input type="text" name="' . esc_attr($option_name) . '" class="color" value="' . esc_attr($data) . '" />';
				$html .= '<div style="background:#FFF;z-index:99;border-radius:100%;" class="colorpicker"></div>';
				$html .= '</div>';
				break;
                            //position:absolute;
		}

		switch ($field['type']) {

			case 'checkbox_multi':
			case 'radio':
			case 'select_multi':
				$html .= '<br/><span class="description">' . $field['description'] . '</span>';
				break;

			default:
				$html .= '<label for="' . esc_attr($field['id']) . '"><span class="description">' . $field['description'] . '</span></label>' . "\n";
				break;
		}

		return $html;
	}

	/**
	 * Validate individual settings field
	 * @param  string $data Inputted value
	 * @return string       Validated value
	 */
	/* public function validate_field( $data ) {
	  if( $data && strlen( $data ) > 0 && $data != '' ) {
	  $data = urlencode( strtolower( str_replace( ' ' , '-' , $data ) ) );
	  }
	  return $data;
	  }* */

	/**
	 * Load settings page content
	 * @return void
	 */
	public function render_page($save) {
		$eol = "\n";
		// Build page HTML
		$html = '<form method="post" action="' . add_query_arg(array('action' => 'save')) . '" enctype="multipart/form-data" class="setings-form">' . "\n";

                if ( count($this->settings) > 1 ) {
                    // Setup navigation
                    $html .= '<ul id="settings-sections" class="subsubsub hide-if-no-js">' . "\n";
                    $html .= '<li><a class="tab all current" href="#all">' . __('All', 'waj') . '</a></li>' . "\n";
                    foreach ($this->settings as $section => $data) {
                            $html .= '<li>| <a class="tab" href="#' . $section . '">' . $data['title'] . '</a></li>' . "\n";
                    }
                    $html .= '</ul>' . "\n";

                    $html .= '<div class="clear"></div>' . "\n";
                }


		foreach ($this->settings as $section => $data) :

			$html .= '<a name="' . $section . '"></a><h3>' . $data['title'] . '</h3>' . $eol;

			$html .= '<table class="form-table">' . $eol;
			foreach ($data['fields'] as $filed) {
				$html .= '<tr valign="top" >' . $eol;
				$html .= '<th scope="row">' . $filed['label'] . '</th>' . $eol;
				$html .= '<td>' . $eol;
				$html .= $this->display_field($filed);
				$html .= '</td>' . $eol;
				$html .= '</tr>' . $eol;
			}
			$html .= '</table>' . $eol;
		endforeach;



		$html .= '<p class="submit">' . "\n";
		$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr(__('Save Settings', 'waj')) . '" />' . "\n";
		$html .= '</p>' . "\n";
		$html .= '</form>' . "\n";

		echo $html;
	}

}
