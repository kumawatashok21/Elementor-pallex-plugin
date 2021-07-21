<?php
namespace ElementorControls;

use Elementor;
/**
 * WordPress settings API: Parallax Controls For Elementor
 *
 * @author AK parallax
 */
if ( !class_exists('Parallax_Controls_Settings_API' ) ) {
	class Parallax_Controls_Settings_API {

		private $settings_api;

		function __construct() {
			$this->settings_api = new Parallax_Settings_API;

			add_action( 'admin_init', array($this, 'admin_init') );
			add_action( 'admin_menu', array($this, 'add_admin_menu'), 503 );
		}

		function admin_init() {

			//set the settings
			$this->settings_api->set_sections( $this->get_settings_sections() );
			$this->settings_api->set_fields( $this->get_settings_fields() );

			//initialize settings
			$this->settings_api->admin_init();
		}

		function add_admin_menu() {
			add_submenu_page( 
				Elementor\Settings::PAGE_ID, 
				__( 'Parallax Controls', 'parallax-controls-for-elementor' ), 
				__( 'Parallax Controls', 'parallax-controls-for-elementor' ), 
				'delete_posts', 
				'parallax_controls', 
				array($this, 'parallax_settings_page' ) );
		}

		function get_settings_sections() {
			$sections = array(
				array(
					'id'    => 'parallax_editor_settings',
					'title' => __( 'Editor Options', 'parallax-controls-for-elementor' )
				),
			);
			return $sections;
		}

		/**
		 * Returns all the settings fields
		 *
		 * @return array settings fields
		 */
		function get_settings_fields() {

			$templates = $this->get_templates();
			$options = [
				'' => '— ' . __( 'Select', 'parallax-controls-for-elementor' ) . ' —',
			];
			foreach ( $templates as $template ) {
				$options[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
			}
			$settings_fields = array(
				'parallax_general_settings' => array(
					array(
						'name'    => 'parallax_accordion_off',
						'label'   => __( 'Accordions Closed?', 'parallax-controls-for-elementor' ),
						'desc'    => __( 'Set all accordions\' first tab to be closed on page load.', 'parallax-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'parallax_dashboard_widget_off',
						'label'   => __( 'Remove Dashboard Widget', 'parallax-controls-for-elementor' ),
						'desc'    => __( 'Remove the Elementor\'s dashboard widget.', 'parallax-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'no',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					)
				),
				'parallax_editor_settings' => array(
					array(
						'name'    => 'parallax_editor_parallax_on',
						'label'   => __( 'Enable Parallax', 'parallax-controls-for-elementor' ),
						'desc'    => __( '', 'parallax-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'yes',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
				),
				'parallax_advanced_settings' => array(
					array(
						'name'    => 'parallax_elementor_dashboard_on',
						'label'   => __( 'Elementor In Dashboard', 'parallax-controls-for-elementor' ),
						'desc'    => __( 'Enable use of Elementor content in the Admin Dashboard - below options will not function correctly with this setting turned off!.', 'parallax-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'yes',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'parallax_welcome_on',
						'label'   => __( 'Welcome Panel', 'parallax-controls-for-elementor' ),
						'desc'    => __( 'Enable the custom parallax Welcome Panel in the Admin Dashboard.', 'parallax-controls-for-elementor' ),
						'type'    => 'radio',
						'default' => 'yes',
						'options' => array(
							'yes' => 'Yes',
							'no'  => 'No'
						)
					),
					array(
						'name'    => 'parallax_welcome_template_id',
						'label'   => __( 'Panel Template ID', 'parallax-controls-for-elementor' ),
						'desc'    => __( 'Select the template you\'d like to be used as the Welcome Panel in the Admin Dashboard.', 'parallax-controls-for-elementor' ),
						'type'    => 'select',
						'default' => '',
						'options' => $options,
					),
				)
			);

			return $settings_fields;
		}

		function parallax_settings_page() {
			echo '<div class="wrap">';
				$this->settings_api->show_navigation();
				$this->settings_api->show_forms();
			echo '</div>';
		}

		/**
		 * Get all the pages
		 *
		 * @return array page names with key value pairs
		 */
		function get_pages() {
			$pages = get_pages();
			$pages_options = array();
			if ( $pages ) {
				foreach ($pages as $page) {
					$pages_options[$page->ID] = $page->post_title;
				}
			}

			return $pages_options;
		}
		
		public static function get_templates() {
			return Plugin::elementor()->templates_manager->get_source( 'local' )->get_items();
		}

	}
}