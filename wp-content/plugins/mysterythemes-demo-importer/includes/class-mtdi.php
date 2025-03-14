<?php
defined('ABSPATH') or die("No script kiddies please!");
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://mysterythemes.com/
 * @since      1.0.0
 *
 * @package    Mystery Themes Demo Importer
 * @subpackage /includes
 */

if( ! class_exists( 'MTDI' ) ) :

	class MTDI extends MTDI_Library {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      MTDI_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected $version;

		public $theme_name 			= null; // For storing Theme Name
		public $theme_slug 			= null; // For storing Theme Slug
		public $theme_version 		= null; // For Storing Theme Current Version Information
		public $theme_author 		= null; // For Storing Theme Author
		public $theme_author_url 	= null;  // For Storing Theme Author URL
		public $theme_desc 			= null; // For Storing Theme Description

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			if ( defined( 'MTDI_VERSION' ) ) {
				$this->version = MTDI_VERSION;
			} else {
				$this->version = '1.2.0';
			}
			$this->plugin_name = 'mysterythemes-demo-importer';

			$this->load_dependencies();
			$this->set_locale();
			$this->define_admin_hooks();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - MTDI_Loader. Orchestrates the hooks of the plugin.
		 * - MTDI_i18n. Defines internationalization functionality.
		 * - MTDI_Admin. Defines all hooks for the admin area.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies() {

			//The class responsible for orchestrating the actions and filters of the core plugin.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mtdi-loader.php';

			//The class responsible for defining internationalization functionality of the plugin.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mtdi-i18n.php';

			//The class responsible for defining all actions that occur in the admin area.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-mtdi-admin.php';
			
			$this->loader = new MTDI_Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the MTDI_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale() {

			$plugin_i18n = new MTDI_i18n();
			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
		}

		/**
		 * Register all of the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks() {

			$plugin_admin = new MTDI_Admin( $this->get_plugin_name(), $this->get_version() );
			
			// Check with Official Mystery theme is installed.
			if ( in_array( get_option( 'template' ), $this->get_supported_themes(), true ) ) {

				$this->loader->add_action( 'init', $plugin_admin, 'plugin_setup' );
				$this->loader->add_action( 'init', $plugin_admin, 'include_files' );
				$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
				$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

				// Open PopUp For Import Using Ajax
				$this->loader->add_action( 'wp_ajax_mtdi_ajax_onclick_import_button', $plugin_admin, 'displayPopupImportForm' );

				// Add pop main wrapper for inserting pop content
				$this->loader->add_action( 'admin_footer', $plugin_admin, 'append_popup_form' );

				// ajax process for installing required plugins
				$this->loader->add_action( 'wp_ajax_mtdi_requried_plugin_install', $plugin_admin, 'install_required_plugins' );
				// ajax process demo import
				$this->loader->add_action( 'wp_ajax_mtdi_import_demo', $plugin_admin, 'import_all_demo' );
				// custom code for updating widget data with json data value
				$this->loader->add_filter( 'mtdi_widget_demo_import_settings', $plugin_admin, 'mtdi_update_widget_data', 10, 4 );
				// custom code for updating customizer field data with json data value
				$this->loader->add_filter( 'mtdi_customizer_demo_import_settings', $plugin_admin, 'mtdi_update_customizer_data' , 10 ,2 );

				// Update custom nav menu items, elementor and siteorigin panel data.
				$this->loader->add_action( 'mtdi_ajax_imported', $plugin_admin, 'update_nav_menu_items' );
				$this->loader->add_action( 'mtdi_ajax_imported', $plugin_admin, 'update_elementor_data' , 10, 2 );
				$this->loader->add_action( 'mtdi_ajax_imported', $plugin_admin, 'update_siteorigin_data', 10, 2 );
				$this->loader->add_action( 'mtdi_ajax_imported', $plugin_admin, 'delete_post_import', 10, 2 );

				/**
				 * After demo imported AJAX action.
				 *
				 * @see mt_set_woo_pages()
				 */
				if ( class_exists( 'WooCommerce' ) ) {
					$this->loader->add_action( 'mtdi_ajax_imported', $plugin_admin, 'mt_set_woo_pages' );
				}

				/**
				 * Clear data before demo import AJAX action.
				 *
				 * @see mt_reset_widgets()
				 * @see mt_delete_nav_menus()
				 * @see mt_remove_theme_mods()
				 */
				if ( apply_filters( 'mtdi_clear_data_before_demo_import', true ) ) {

					$this->loader->add_action( 'mtdi_ajax_before_demo_import', $plugin_admin, 'mt_reset_widgets', 10 );
					$this->loader->add_action( 'mtdi_ajax_before_demo_import', $plugin_admin, 'mt_delete_nav_menus', 20 );
					$this->loader->add_action( 'mtdi_ajax_before_demo_import', $plugin_admin, 'mt_remove_theme_mods', 30 );

				}
				$this->loader->add_filter( 'plugin_action_links_', $plugin_admin, 'plugin_action_links' );
			} else {
				$this->loader->add_action( 'admin_notices', $plugin_admin, 'missing_notice' );
			}
		}

		/**
		 * All supported theme of this plugin
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_supported_themes() {

			// Set the argument array with author name.
			$args = array(
				'author' => 'mysterythemes',
				'per_page' => 100
			);
			// Set the $request array.
			$request = array(
				'body' => array(
					'action'  => 'query_themes',
					'request' => serialize( (object)$args )
				)
			);

			$response = wp_remote_post( 'http://api.wordpress.org/themes/info/1.0/', $request );

			// Check for the error.
			if ( !is_wp_error( $response ) ) {
				$themes = unserialize( wp_remote_retrieve_body( $response ) );
				if ( !is_object( $themes ) && !is_array( $themes ) ) {
					// Response body does not contain an object/array
					return new WP_Error( 'theme_api_error', 'An unexpected error has occurred' );
				}
			} else {
				// Error object returned
				return array();
			}


			$mt_free_themes = $mt_parent_free_themes = array();

			foreach ( $themes->themes as $theme ) {
				if ( empty( $theme->template ) ) {
					$mt_parent_free_themes[] = $theme->slug;
				}
				$mt_free_themes[] = $theme->slug;
			}

			$mt_diff_theme_name = array( 'swipe', 'flexible', 'ogma', 'newshive', 'newsfeed' );
			$mt_parent_free_themes = array_merge( $mt_parent_free_themes, $mt_diff_theme_name );

			$namge_change_for_pro = array( 'news-portal-elementrix', 'color-blog-dark', 'matina-news', 'swipewp', 'flexible-lite' );

			$mt_free_themes_to_pro = array_diff( $mt_parent_free_themes, $namge_change_for_pro );

			if ( ! empty( $mt_free_themes_to_pro ) ) {
				$mt_pro_themes = preg_replace( '/$/', '-pro', $mt_free_themes_to_pro );
			}

			$mt_all_free_pro_themes = array_merge( $mt_parent_free_themes, $mt_pro_themes );
			
			return $mt_all_free_pro_themes;
		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run() {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name() {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    MTDI_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader() {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version() {
			return $this->version;
		}
	}

endif;