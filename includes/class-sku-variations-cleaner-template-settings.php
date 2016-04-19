<?php

if ( ! defined( 'ABSPATH' ) ) exit;

class SKU_Variations_Cleaner_Template_Settings {

	/**
	 * The single instance of SKU_Variations_Cleaner_Template_Settings.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The main plugin object.
	 * @var 	object
	 * @access  public
	 * @since 	1.0.0
	 */
	public $parent = null;

	/**
	 * Prefix for plugin settings.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $base = '';

	/**
	 * Available settings for plugin.
	 * @var     array
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = array();

	public function __construct ( $parent ) {
		$this->parent = $parent;

		$this->base = 'alio_';

		// Initialise settings
		add_action( 'init', array( $this, 'init_settings' ), 11 );

		// Register plugin settings
		add_action( 'admin_init' , array( $this, 'register_settings' ) );

		// Add settings page to menu
		add_action( 'admin_menu' , array( $this, 'add_menu_item' ) );

		// Add settings link to plugins page
		add_filter( 'plugin_action_links_' . plugin_basename( $this->parent->file ) , array( $this, 'add_settings_link' ) );
	}

	/**
	 * Initialise settings
	 * @return void
	 */
	public function init_settings () {
		$this->settings = $this->settings_fields();
	}

	/**
	 * Add settings page to admin menu
	 * @return void
	 */
	public function add_menu_item () {
		add_submenu_page( 'woocommerce', 'WooCommerce SKU Variations Cleaner SWS', __( 'SKU Variations Cleaner' ), 'manage_options' , $this->parent->_token . '_settings' ,  array( $this, 'settings_page' ) );
	}

	/**
	 * Add settings link to plugin list table
	 * @param  array $links Existing links
	 * @return array 		Modified links
	 */
	public function add_settings_link ( $links ) {
		$settings_link = '<a href="admin.php?page=' . $this->parent->_token . '_settings">' . __( 'Settings' ) . '</a>';
  		array_unshift( $links, $settings_link );
  		return $links;
	}

	/**
	 * Build settings fields
	 * @return array Fields to be displayed on settings page
	 */
	private function settings_fields () {

		$settings['main'] = array(
			'title' => 'Automatically deleting',
			'description' => '',
			'fields' => array(
				array(
					'id' => 'auto_clean',
					'label' => 'When I change a product type from Variable to other and Save it:',
					'description' => __( '' ),
					'type' => 'radio',
					'options' => array(
						'default' => 'Default (not clean)',
						'auto_del_fully' => 'Automatically remove old variations of this product',
						'auto_del_sku' => 'Automatically clean SKU fields of old variations of this product'
					),
					'default' => 'default',
				),
				array(
					'id' => 'auto_clean_sku',
					'label' => 'When I fill a SKU field of a product:',
					'description' => __( '' ),
					'type' => 'radio',
					'options' => array(
						'default' => 'Default',
						'clean_sku' => 'Automatically scan & removing existing from old variations SKU fields'
					),
					'default' => 'default',
				),
			),

		);

		$settings = apply_filters( $this->parent->_token . '_settings_fields', $settings );

		return $settings;
	}

	/**
	 * Register plugin settings
	 * @return void
	 */
	public function register_settings () {
		if ( is_array( $this->settings ) ) {

			foreach ( $this->settings as $section => $data ) {


			// Add section to page
			add_settings_section( $section, $data['title'], array( $this, 'settings_section' ), $this->parent->_token . '_settings' );

			foreach ( $data['fields'] as $field ) {

					// Validation callback for field
					$validation = '';
					if ( isset( $field['callback'] ) ) {
						$validation = $field['callback'];
					}

					// Register field
					$option_name = $this->base . $field['id'];
					register_setting( $this->parent->_token . '_settings', $option_name, $validation );

					// Add field to page
					add_settings_field( $field['id'], $field['label'], array( $this->parent->admin, 'display_field' ), $this->parent->_token . '_settings', $section, array( 'field' => $field, 'prefix' => $this->base ) );
				}
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
		$html = '<div class="wrap" id="' . $this->parent->_token . '_settings">' . "\n";

		$html .= '<h2 class="dashicons-before dashicons-admin-generic options_icon">WooCommerce SKU Variations Cleaner SWS Settings</h2>' . "\n";

		// Update message
		if ( isset( $_REQUEST['settings-updated'] ) ) {
			$html .= '<div class="updated"><p><strong>' . __( 'Options successfully updated!' ) . '</strong></p></div>' . "\n";
		}

		$html .= '<form method="post" action="options.php" enctype="multipart/form-data" class="wc_sku_cleaner">' . "\n";

		$html .= '<table class="search_delete_section">' . "\n";

		$html .= '<tr>
			<td scope="row" class="search_td">
				<h2>' . __( 'Search old product variations' ) . '</h2>
				<p class="description">' . __( 'Click Start search button to start the search of old variations.' ) . '</p>
				<a href="" class="button button-primary search_vars">' . __( 'Start search old variations' ) . '</a>
				<img src="' . SWS_VAR_CLEANER_PLUGIN_PATH . '/assets/img/loader.gif" class="loader_img" alt="loading...">
				<div class="search_result"></div>
			</td>
			<td rowspan="3" class="automatically_settings_section"><div>' . "\n";
				// Get settings fields
			ob_start();
			settings_fields( $this->parent->_token . '_settings' );
			do_settings_sections( $this->parent->_token . '_settings' );
			$html .= ob_get_clean();
			$html .= '<p class="submit">' . "\n";
				$html .= '<input name="Submit" type="submit" class="button-primary" value="' . esc_attr( __( 'Save Settings'  ) ) . '" />' . "\n";
			$html .= '</p>' . "\n";
			$html .= '</div></td>
		</tr>' . "\n";

		$html .= '<tr>
			<td scope="row">
				<h2>' . __( 'Bulk delete' ) . '</h2>
				<p class="description">' . __( 'Simultaneous removal of all the old variables.' ) . '</p>
			</td>
		</tr>' . "\n";

		$html .= '<tr>
			<td>
				<a href="" class="button button-primary clean_sku">' . __( 'Delete only SKU fields of old variations' ) . '</a>
				<img src="' . SWS_VAR_CLEANER_PLUGIN_PATH . '/assets/img/loader.gif" class="loader_img" alt="loading...">
				<div class="clean_result"></div>
				<hr class="cleaner_divider">
				<a href="" class="button button-primary removal_vars">' . __( 'Delete old variations fully' ) . '</a><img src="' . SWS_VAR_CLEANER_PLUGIN_PATH . '/assets/img/loader.gif" class="loader_img" alt="loading...">
				<p class="description">' . __( 'This operation cannot be undone. Please backup your database if you are unsure.' ) . '</p>
				<div class="removal_result"></div>
			</td>
		</tr>' . "\n";

		$html .= '</table>' . "\n";
		$html .= '</form>' . "\n";
		$html .= '</div>' . "\n";

		echo $html;
	}

	/**
	 * Main SKU_Variations_Cleaner_Template_Settings Instance
	 *
	 * Ensures only one instance of SKU_Variations_Cleaner_Template_Settings is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see WordPress_Plugin_Template()
	 * @return Main SKU_Variations_Cleaner_Template_Settings instance
	 */
	public static function instance ( $parent ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $parent );
		}
		return self::$_instance;
	} // End instance()

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __clone()

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup () {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), $this->parent->_version );
	} // End __wakeup()

}