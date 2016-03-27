<?php
/**
* Plugin Name: Woocommerce SKU Variations Cleaner SWS
* Description: Removal old product SKUs or configure automatic deletion when changing the product type.
* Version: 1.0
* Author: Alio Master (SWS - SpaceWorkSpace)
* Author URI: aliowebdeveloper@gmail.com
*
* Text Domain: wordpress-plugin-template
* Domain Path: /lang/
*
* @package WordPress
* @author Alio Master (SWS - SpaceWorkSpace)
* @since 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// Load plugin libraries
require_once( 'includes/lib/class-sku-variations-cleaner-admin-api.php' );

define( 'SWS_VAR_CLEANER_PLUGIN_NAME', 'Woocommerce SKU Variations Cleaner SWS' );
define( 'SWS_VAR_CLEANER_PLUGIN_URL', __FILE__ );
define( 'SWS_VAR_CLEANER_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'SWS_VAR_CLEANER_TEMPLATE_PATH', plugin_dir_path( __FILE__ ) . "templates/" );
define( 'SWS_VAR_CLEANER_AUTO', 'auto_clean' );
define( 'SWS_VAR_CLEANER_SLUG', 'sku_vars_cleaner' );

// helper function
if ( ! function_exists( 'pr' ) ) {
	function pr($val) {
		echo '<pre>';
		print_r($val);
		echo '</pre>';
	}
}


/**
 * SKU Variations Cleaner class
 *
 * class used as a namespace
 *
 * @package SKU Variations Cleaner
 */
class SKU_Variations_Cleaner {

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Get hooked into init
	 *
	 * @return void
	 */
	function sku_variations_cleaner() {
		//add_action( 'admin_init', array( $this, 'sku_variations_cleaner_register_settings' ) );

		$this->base = 'alio_';

		// Load API for generic admin functions
		if ( is_admin() ) {
			$this->admin = new SKU_Variations_Cleaner_Admin_API();
		}

		// Load admin JS & CSS
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 10, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_styles' ), 10, 1 );

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu', array( $this, 'setting_page_menu' ) );

		// Add settings link to plugins page
		add_filter( "plugin_action_links", array( $this, 'settings_plugin_action_links' ), 10, 2 );
	}

	/**
	 * Load admin CSS.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_styles () {
		wp_register_style( 'css-sku-vars-cleaner-admin', plugins_url( 'css/sku-vars-cleaner-admin.css', __FILE__ ) );
		wp_enqueue_style( 'css-sku-vars-cleaner-admin' );
	}

	/**
	 * Load admin Javascript.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function admin_enqueue_scripts () {
		wp_register_script( 'js-sku-vars-cleaner-admin', plugins_url( 'js/sku-vars-cleaner-admin.js', __FILE__ ), array( 'jquery' ) );
		wp_enqueue_script( 'js-sku-vars-cleaner-admin' );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings = array(
			'title' => SWS_VAR_CLEANER_PLUGIN_NAME,
			'description' => __( 'Here you can clear...' ),
			'fields' => array(
				'id' => SWS_VAR_CLEANER_AUTO,
				'label' => __( 'Automatically' ),
				'description' => __( 'When I change the product type to Simple from Variable:' ),
				'type' => 'radio',
				'options' => array(
					'default' => 'Default (not clean)',
					'auto_del_fully' => 'Automatically delete old variations',
					'auto_del_sku' => 'Automatically clean old skus of variables'
				),
				'default' => 'default',
			),
		);

/*		$settings['standard'] = array(
			'title'					=> __( 'Standard' ),
			'description'			=> __( 'These are fairly standard form input fields.' ),
			'fields'				=> array(
				array(
					'id' 			=> 'text_field',
					'label'			=> __( 'Some Text'  ),
					'description'	=> __( 'This is a standard text field.' ),
					'type'			=> 'text',
					'default'		=> '',
					'placeholder'	=> __( 'Placeholder text' )
				),
				array(
					'id' 			=> 'password_field',
					'label'			=> __( 'A Password'  ),
					'description'	=> __( 'This is a standard password field.' ),
					'type'			=> 'password',
					'default'		=> '',
					'placeholder'	=> __( 'Placeholder text' )
				),
				array(
					'id' 			=> 'secret_text_field',
					'label'			=> __( 'Some Secret Text'  ),
					'description'	=> __( 'This is a secret text field - any data saved here will not be displayed after the page has reloaded, but it will be saved.' ),
					'type'			=> 'text_secret',
					'default'		=> '',
					'placeholder'	=> __( 'Placeholder text' )
				),
				array(
					'id' 			=> 'text_block',
					'label'			=> __( 'A Text Block'  ),
					'description'	=> __( 'This is a standard text area.' ),
					'type'			=> 'textarea',
					'default'		=> '',
					'placeholder'	=> __( 'Placeholder text for this textarea' )
				),
				array(
					'id' 			=> 'single_checkbox',
					'label'			=> __( 'An Option' ),
					'description'	=> __( 'A standard checkbox - if you save this option as checked then it will store the option as \'on\', otherwise it will be an empty string.' ),
					'type'			=> 'checkbox',
					'default'		=> ''
				),
				array(
					'id' 			=> 'select_box',
					'label'			=> __( 'A Select Box' ),
					'description'	=> __( 'A standard select box.' ),
					'type'			=> 'select',
					'options'		=> array( 'drupal' => 'Drupal', 'joomla' => 'Joomla', 'wordpress' => 'WordPress' ),
					'default'		=> 'wordpress'
				),
				array(
					'id' 			=> 'radio_buttons',
					'label'			=> __( 'Some Options' ),
					'description'	=> __( 'A standard set of radio buttons.' ),
					'type'			=> 'radio',
					'options'		=> array( 'superman' => 'Superman', 'batman' => 'Batman', 'ironman' => 'Iron Man' ),
					'default'		=> 'batman'
				),
				array(
					'id' 			=> 'multiple_checkboxes',
					'label'			=> __( 'Some Items' ),
					'description'	=> __( 'You can select multiple items and they will be stored as an array.' ),
					'type'			=> 'checkbox_multi',
					'options'		=> array( 'square' => 'Square', 'circle' => 'Circle', 'rectangle' => 'Rectangle', 'triangle' => 'Triangle' ),
					'default'		=> array( 'circle', 'triangle' )
				)
			)
		);

		$settings['extra'] = array(
			'title'					=> __( 'Extra' ),
			'description'			=> __( 'These are some extra input fields that maybe aren\'t as common as the others.' ),
			'fields'				=> array(
				array(
					'id' 			=> 'number_field',
					'label'			=> __( 'A Number'  ),
					'description'	=> __( 'This is a standard number field - if this field contains anything other than numbers then the form will not be submitted.' ),
					'type'			=> 'number',
					'default'		=> '',
					'placeholder'	=> __( '42' )
				),
				array(
					'id' 			=> 'colour_picker',
					'label'			=> __( 'Pick a colour' ),
					'description'	=> __( 'This uses WordPress\' built-in colour picker - the option is stored as the colour\'s hex code.' ),
					'type'			=> 'color',
					'default'		=> '#21759B'
				),
				array(
					'id' 			=> 'an_image',
					'label'			=> __( 'An Image'  ),
					'description'	=> __( 'This will upload an image to your media library and store the attachment ID in the option field. Once you have uploaded an imge the thumbnail will display above these buttons.' ),
					'type'			=> 'image',
					'default'		=> '',
					'placeholder'	=> ''
				),
				array(
					'id' 			=> 'multi_select_box',
					'label'			=> __( 'A Multi-Select Box' ),
					'description'	=> __( 'A standard multi-select box - the saved data is stored as an array.' ),
					'type'			=> 'select_multi',
					'options'		=> array( 'linux' => 'Linux', 'mac' => 'Mac', 'windows' => 'Windows' ),
					'default'		=> array( 'linux' )
				)
			)
		);*/

		$settings = apply_filters( SWS_VAR_CLEANER_SLUG . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		//pr($this->settings); exit;
		if ( is_array( $this->settings ) ) {

			// Check posted/selected tab
			/*$current_section = '';
			if ( isset( $_POST['tab'] ) && $_POST['tab'] ) {
				$current_section = $_POST['tab'];
			} else {
				if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
					$current_section = $_GET['tab'];
				}
			}*/

			foreach ( $this->settings as /*$section =>*/ $data ) {

				/*if ( $current_section && $current_section != $section ) continue;*/

				// Add section to page
				/*add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), SWS_VAR_CLEANER_SLUG . '_settings' );*/

				foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( SWS_VAR_CLEANER_SLUG . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->admin, 'display_field' ), SWS_VAR_CLEANER_SLUG . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}

				/*if ( ! $current_section ) break;*/
			}
		}
	}

	public function settings_section ( $section ) {
		$html = '<p> ' . $this->settings[ $section['id'] ]['description'] . '</p>' . "\n";
		echo $html;
	}

	/**
	 * Load settings page content
	 * @return void
	 */
	public function settings_page () {

		// Build page HTML
		$html = '<div class="wrap" id="' . SWS_VAR_CLEANER_SLUG . '_settings">' . "\n";
			$html .= '<h2>' . __( SWS_VAR_CLEANER_PLUGIN_NAME ) . '</h2>' . "\n";

			/*$tab = '';
			if ( isset( $_GET['tab'] ) && $_GET['tab'] ) {
				$tab .= $_GET['tab'];
			}*/

			// Show page tabs
/*			if ( is_array( $this->settings ) && 1 < count( $this->settings ) ) {

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
					$html .= '<a href="' . $tab_link . '" class="' . esc_attr( $class ) . '">' . esc_html( $data['title'] ) . '</a>' . "\n";

					++$c;
				}

				$html .= '</h2>' . "\n";
			}*/

			$html .= '<form method="post" action="options.php" enctype="multipart/form-data">' . "\n";

				// Get settings fields
				ob_start();
				settings_fields( SWS_VAR_CLEANER_SLUG . '_settings' );
				do_settings_sections( SWS_VAR_CLEANER_SLUG . '_settings' );
				$html .= ob_get_clean();

				$html .= '<p class="submit">' . "\n";
/*					$html .= '<input type="hidden" name="tab" value="' . esc_attr( $tab ) . '" />' . "\n";*/
					$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings'  ) ) . '" />' . "\n";
				$html .= '</p>' . "\n";
			$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	function settings_plugin_action_links( $links, $file ) {
		if ( $file == plugin_basename( SWS_VAR_CLEANER_PLUGIN_PATH . 'woocommerce-sku-variations-cleaner-sws.php' ) ) {
			$settings_link = '<a href="admin.php?page="' . SWS_VAR_CLEANER_SLUG . '>'
			. _e( 'Plugin Settings' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	/**
	 * Create admin pages in menu
	 *
	 * @return void
	 */
	function setting_page_menu() {
		add_submenu_page( 'woocommerce', SWS_VAR_CLEANER_PLUGIN_NAME, 'Clear Variations', 'manage_options', SWS_VAR_CLEANER_SLUG . '_settings', array( $this, 'settings_page' ) );
	}

	/**
	 * Register plugin settings
	 *
	 * @return void
	 */
	/*function sku_variations_cleaner_register_settings() {
		register_setting( SWS_VAR_CLEANER_OPTIONS_GROUP, SWS_VAR_CLEANER_OPTIONS_GROUP/*, array( $this, 'sku_vars_cleaner_sanitize_options' ) );
	}*/

	/**
	 * Sanitize plugin settings
	 *
	 * @return void
	 */
	/*function sku_vars_cleaner_sanitize_options( $options ) {
		$clean_options = array();
		$old_options = get_option( SWS_VAR_CLEANER_OPTIONS_GROUP );
		pr($old_options); exit;
		if ( ! empty( $_FILES['sws-preloader-custom-file']['tmp_name'] ) ) {
			$overrides = array( 'test_form' => false );
			$file = wp_handle_upload( $_FILES['sws-preloader-custom-file'], $overrides );
			$clean_options['url'] = $file['url'];
			$clean_options['file'] = $file['file'];

		} else {
			
			
			if ( ! empty( $old_options['url'] ) && ! empty( $old_options['file'] ) ) {
				$clean_options['url'] = $old_options['url'];
				$clean_options['file'] = $old_options['file'];
			}

		}

		foreach ( $options as $key => $value ) {
			$clean_options[$key] = strip_tags( $value );
		}

		if ( $clean_options['selected-tab'] !== 'upload_a_custom' && ! empty( $clean_options['file'] ) ) {
			unlink( $clean_options['file'] );
			unset( $clean_options['url'] );
			unset( $clean_options['file'] );
		} elseif ( isset( $file ) && isset( $old_options['url'] ) && $file['url'] !== $old_options['url'] ) {
			unlink( $old_options['file'] );
		}

		return $clean_options;
	}*/

	/**
	 * Add the new tab to the woocommerce pop-ip
	 *
	 * @return void
	 */
	/*function sku_variations_cleaner_page() {
		include SWS_VAR_CLEANER_TEMPLATE_PATH . "main_settings.php";
	}*/
	/**
	 * Move unzipped content from temp folder to media library
	 *
	 * @param string $dir Directory to loop through
	 * @param integer $parent Page ID to be used as attachment parent
	 * @param string $return String to append results to
	 * @return string Results as <li> items
	 */
	/*function move_from_dir( $dir, $parent, $return = '' ) {}*/
	/**
	 * Handle the initial zip upload
	 *
	 * @return string HTML Results or Error message
	 */
	/*function handler() {}*/

} // end class

$sku_variations_cleaner = new SKU_Variations_Cleaner();


/* removing sku functions */
global $wpdb;
$wpdb->show_errors( true );
$simple_term_id = $wpdb->get_var( $wpdb->prepare( "SELECT term_id FROM $wpdb->terms WHERE slug='%s' LIMIT 1", 'simple' ) );
$simples = array();
if ( $simple_term_id ) {
	$all_simples = $wpdb->get_results( $wpdb->prepare( "SELECT object_id FROM $wpdb->term_relationships WHERE term_taxonomy_id='%s'", $simple_term_id ) );
	if ( $all_simples && is_array( $all_simples ) ) {
		foreach ( $all_simples as $simp_item ) {
			$simples[] = $simp_item->object_id;
		}
	}
}

$needless_childs = array();
if ( $simples ) {
	foreach ( $simples as $simple_id ) {
		$childs = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type='%s' AND post_parent='%s'", 'product_variation', $simple_id ) );
		if ( $childs && is_array( $childs ) ) {
			foreach ( $childs as $child_item ) {
				$needless_childs[$child_item->ID] = get_the_title( $child_item->ID );
			}
		}
	}
}
//pr($needless_childs); exit;
/* removing fully post */
//$del = wp_delete_post( 14471, true );
//pr($del->ID); exit;

/* removing sku post */
$del_sku = $wpdb->delete( 'wp_postmeta', array( 'meta_key' => '_sku', 'post_id' => 14573 ) );
//pr($del_sku); exit;

//pr($needless_childs); exit;
$products_id = $wpdb->get_results( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='%s'", '_sku' ) );