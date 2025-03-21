<?php
defined('ABSPATH') or die("No script kiddies please!");
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://mysterythemes.com/
 * @since      1.0.0
 *
 * @package    Mystery Themes Demo Importer
 * @subpackage /admin
 */
if ( !class_exists( 'MTDI_Admin' ) ) :

	class MTDI_Admin extends MTDI_Library{
		/**
		 * The name of this plugin.
		 *
		 * @since	1.0.0
		 * @access	private
		 * @var		string	$plugin_name	The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since   1.0.0
		 * @access	private
		 * @var		string	$version	The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param	string	$plugin_name	The name of this plugin.
		 * @param	string	$version	The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {
			$this->plugin_name 	= $plugin_name;
			$this->version 		= $version;
		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mtdi-admin.css', array(), MTDI_VERSION, 'all' );
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts( $hook_suffix ) {
			/**
			 * Applies condition for theme setting s page only.
			 */
			$activated_theme = get_stylesheet();

			if ( is_child_theme() ) {
				$activated_theme = get_template();
			}

			if ( $hook_suffix == 'appearance_page_'. esc_html( $activated_theme ) .'-settings' || $hook_suffix == 'appearance_page_'. esc_html( $activated_theme ) .'-dashboard' || $hook_suffix == 'appearance_page_mysterythemes-demo-importer' ) {
				wp_enqueue_script( 'mtdi-admin', plugin_dir_url( __FILE__ ) . 'js/mtdi-admin.js', array( 'jquery','wp-util', 'updates' ), esc_attr( $this->version ), false );

				/** Localizing the text to be used in Scripts **/
				wp_localize_script( 'mtdi-admin', 'MTDI_JSObject',
					array(
						'ajaxurl'				=> esc_url( admin_url( 'admin-ajax.php' ) ),
						'wp_customize_on'  		=> apply_filters( 'enable_wp_customize_save_hooks', false ),
						'demo_installing' 		=> esc_html__( 'Installing Demo...', 'mysterythemes-demo-importer' ),
						'demo_installed' 		=> esc_html__( 'Demo Installed', 'mysterythemes-demo-importer' ),
						'plugin_installing' 	=> esc_html__( 'Installing', 'mysterythemes-demo-importer' ),
						'importing_demo' 		=> esc_html__( 'Demo Importing...', 'mysterythemes-demo-importer' ),
						'plugin_activating' 	=> esc_html__( 'Activating', 'mysterythemes-demo-importer' ),
						'activating_installing' => esc_html__( 'Installing & Activating', 'mysterythemes-demo-importer' ),
						'plugin_activated' 		=> esc_html__( 'Activated', 'mysterythemes-demo-importer' ),
						'plugin_activate' 		=> esc_html__( 'Activate Now', 'mysterythemes-demo-importer' ),
						'_wpnonce' 				=> wp_create_nonce( 'mtdi_admin_import_nonce' ),
						'home_url' 				=> get_bloginfo( 'url' ),
						'demo_import_success' 	=> esc_html__( 'Demo has been successfully installed', 'mysterythemes-demo-importer' ),
						'demo_confirm' 			=> esc_html__( 'Are you sure to import demo content?', 'mysterythemes-demo-importer' ),
					)
				);

			}
		}

		/**
		 * Sets up plugin transient cache for further use
		 * 
		 */
		public function plugin_setup() {
			$packages 			= array();
			$xmldemopackages 	= get_transient( 'mtdi_theme_packages' );
			$activated_theme 	= get_template(); //active template slug

			/**
			 * Fixed activated theme demo package's issue
			 * while switching in house theme
			 * 
			 * @since 1.1.1
			 */
			$get_activated_theme = get_option( 'mtdi_activated_theme' );
			if ( empty( $get_activated_theme ) || $activated_theme !== $get_activated_theme ) {
				$packages = $this->retrieve_demo_by_activatetheme( $activated_theme );
				
				if ( $packages ) {
					set_transient( 'mtdi_theme_packages', $packages, WEEK_IN_SECONDS );
				}
				$xmldemopackages = get_transient( 'mtdi_theme_packages' );
				
				update_option( 'mtdi_activated_theme', $activated_theme );
			}

			if ( ! empty( $xmldemopackages[$activated_theme]['theme_slug'] ) ) {
				if ( empty( $xmldemopackages ) || $activated_theme !== $xmldemopackages[$activated_theme]['theme_slug'] ) {
					$packages = $this->retrieve_demo_by_activatetheme( $activated_theme );
					if ( $packages ) {
						set_transient( 'mtdi_theme_packages', $packages, WEEK_IN_SECONDS );
					}
				}
			}

			return apply_filters( 'mtdi_theme_packages_' . $activated_theme, $packages );
		}
		
		/**
		 * Get All demo data from selected demo name Ajax Method
		 *
		 * @since 1.0.0
		 */
		public function displayPopupImportForm() {
			
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'mtdi_admin_import_nonce' ) ) {
				esc_html_e( 'This action was stopped for security purposes.', 'mysterythemes-demo-importer' );
				die();
			}

			$selected_demo 	= get_template();
			$demodata 		= get_transient( 'mtdi_theme_packages' );

			if ( empty( $demodata ) || $demodata == false ) {
				$demodata = $this->retrieve_demo_by_activatetheme( $selected_demo );
			}
			$selected_demo 	= sanitize_text_field( $_POST['plugin_slug'] );

			include( MTDI_ADMIN_DIR. 'partials/mtdi-import-popup.php' );
			wp_die();
		}

		/**
		 * Install required plugins
		 *
		 * @since 1.0.0
		 */
		function install_required_plugins() {
			if ( ! wp_verify_nonce( sanitize_text_field ( wp_unslash( $_POST['_wpnonce'] ) ), 'mtdi_admin_import_nonce' ) ) {
				esc_html_e( 'This action was stopped for security purposes.', 'mysterythemes-demo-importer' );
				die();
			}

			if ( empty( $_POST['plugin_slug'] ) || empty( $_POST['plugin_init'] ) ) {
				wp_send_json_error(
					array(
						'slug'         => '',
						'errorCode'    => 'no_plugin_specified',
						'errorMessage' => esc_html__( 'No plugin specified.', 'mysterythemes-demo-importer'),
					)
				);
			}

			$plugin_slug   	= sanitize_key( wp_unslash( $_POST['plugin_slug'] ) );
			$plugin_init 	= plugin_basename( sanitize_text_field( wp_unslash( $_POST['plugin_init'] ) ) );

			$status = array(
				'install' => 'plugin',
				'slug'    => sanitize_key( wp_unslash( $_POST['plugin_slug'] ) ),
			);

			if ( ! current_user_can( 'install_plugins' ) ) {
				$status['errorMessage'] = esc_html__( 'Sorry, you are not allowed to install plugins on this site.', 'mysterythemes-demo-importer' );
				wp_send_json_error( $status );
			}

			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

			// Looks like a plugin is installed, but not active.
			if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
				$plugin_data          = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_init );
				$status['plugin']     = $plugin_init;
				$status['pluginName'] = $plugin_data['Name'];

				if ( current_user_can( 'activate_plugin', $plugin_init ) && is_plugin_inactive( $plugin_init ) ) {
					$result = activate_plugin( $plugin_init );

					if ( is_wp_error( $result ) ) {
						$status['errorCode']    = $result->get_error_code();
						$status['errorMessage'] = $result->get_error_message();
						wp_send_json_error( $status );
					}

					wp_send_json_success( $status );
				}
			}

			// Install plugin locally from zip file
			if ( isset( $_POST['install'] ) && ( $_POST['install'] === "locally" ) ) {
				$file_location = get_template_directory() . '/inc/plugins/' . esc_html( $plugin_slug ) . '.zip';
				$file = $_POST['file'];
				$plugin_directory = ABSPATH . 'wp-content/plugins/';

				$zip = new ZipArchive;
				if ( $zip->open( esc_html( $file_location ) ) === TRUE ) {
					$zip->extractTo( $plugin_directory );
					$zip->close();
						    
					if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
						$plugin_data          = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_init );
						$status['plugin']     = $plugin_init;
						$status['pluginName'] = $plugin_data['Name'];
						$this->check_do_activate_plugin( $plugin_init );
					}
				} else {
					$status['errorMessage'] = esc_html__( 'There was an error installing plugin', 'mysterythemes-demo-importer' );
					wp_send_json_error( $status );
				}
			}
			
			$api = plugins_api(
				'plugin_information',
				array(
					'slug'   => sanitize_key( wp_unslash( $plugin_slug ) ),
					'fields' => array(
						'sections' => false,
					),
				)
			);
			if ( is_wp_error( $api ) ) {
				$status['errorMessage'] = $api->get_error_message();
				wp_send_json_error( $status );
			}

			$status['pluginName'] 	= $api->name;
			$skin     				= new WP_Ajax_Upgrader_Skin();
			$upgrader 				= new Plugin_Upgrader( $skin );
			$result   				= $upgrader->install( $api->download_link );

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				$status['debug'] = $skin->get_upgrade_messages();
			}

			if ( is_wp_error( $result ) ) {
				$status['errorCode']    = $result->get_error_code();
				$status['errorMessage'] = $result->get_error_message();
				wp_send_json_error( $status );
			} elseif ( is_wp_error( $skin->result ) ) {
				$status['errorCode']    = $skin->result->get_error_code();
				$status['errorMessage'] = $skin->result->get_error_message();
				wp_send_json_error( $status );
			} elseif ( $skin->get_errors()->get_error_code() ) {
				$status['errorMessage'] = $skin->get_error_messages();
				wp_send_json_error( $status );
			} elseif ( is_null( $result ) ) {
				global $wp_filesystem;

				$status['errorCode']    = esc_html__( 'unable_to_connect_to_filesystem', 'mysterythemes-demo-importer' );
				$status['errorMessage'] = esc_html__( 'Unable to connect to the filesystem. Please confirm your credentials.', 'mysterythemes-demo-importer' );

				// Pass through the error from WP_Filesystem if one was raised.
				if ( $wp_filesystem instanceof WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
					$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
				}

				wp_send_json_error( $status );
			}

			$install_status = install_plugin_install_status( $api );

			if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_slug ) ) {
				$plugin_data          = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_init );
				$status['plugin']     = $plugin_init;
				$status['pluginName'] = $plugin_data['Name'];

				if ( current_user_can( 'activate_plugin', $plugin_init ) && is_plugin_inactive( $plugin_init ) ) {
					$result = activate_plugin( $plugin_init );

					if ( is_wp_error( $result ) ) {
						$status['errorCode']    = $result->get_error_code();
						$status['errorMessage'] = $result->get_error_message();
						wp_send_json_error( $status );
					}

					wp_send_json_success( $status );
				}
			}
			wp_send_json_success( $status );
		}

		/**
		 * Activate Required Plugins Ajax Method
		 *
		 * @since 1.0.0
		 */
		public function activate_req_plugins() {

			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'mtdi_admin_import_nonce' ) ) {
				esc_html_e_( 'This action was stopped for security purposes.', 'mysterythemes-demo-importer' );
				die();
			}

			$plugininit 	= ( isset( $_POST['plugin_init'] ) ) ? esc_attr( $_POST['plugin_init'] ) : '';
			$result 	 	= activate_plugin( $plugininit );

			if ( is_wp_error( $result ) ) {
				// Process Error
				wp_send_json_error(
					array(
						'success' => false,
						'message' => $result->get_error_message(),
					)
				);
			} else {
				wp_send_json_success(
					array(
						'success' => true,
						'message' => esc_html__( 'Plugin Successfully Activated.', 'mysterythemes-demo-importer' ),
					)
				);
			}
		}

		/**
		 * Append popup form for demo import.
		 *
		 * @since 1.0.0
		 */
		public function append_popup_form() {
			echo '<div id="mtdi-demo-popup-wrap" class="mtdi-popup-wrap"></div>';
		}

		/**
		 * function about import all demo content.
		 *
		 * @since 1.0.0
		 */
		public function import_all_demo() {
			if (  wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'mtdi_admin_import_nonce' ) ) {
				$execution_time = sanitize_text_field( $_POST['execution_time'] );
				ini_set( 'memory_limit', '350M' );
				if ( $execution_time != 'default' ) {
					ini_set( 'max_execution_time', apply_filters( 'mtdi_demo_import_execution_time', $execution_time ) );
				} else {
					ini_set( 'max_execution_time', apply_filters( 'mtdi_demo_import_execution_time', 300 ) );
				}
				
				if ( empty( $_POST['plugin_slug'] ) ) {
					wp_send_json_error(
						array(
							'slug'         => '',
							'errorCode'    => esc_html__( 'Plugin slug is not specified', 'mysterythemes-demo-importer' ),
							'errorMessage' => esc_html__( 'Plugin slug is not specified.', 'mysterythemes-demo-importer' ),
						)
					);
				}

				$slug	= sanitize_key( wp_unslash( $_POST['plugin_slug'] ) );
				if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
					define( 'WP_LOAD_IMPORTERS', true );
				}

				if ( ! current_user_can( 'import' ) ) {
					$status['errorMessage'] = esc_html__( 'Sorry, you have no permission to import the demo content.', 'mysterythemes-demo-importer');
					wp_send_json_error( $status );
				}

				$status = array(
					'import' => 'demo',
					'slug'   => $slug,
				);

				$template = get_option( 'template' );
				do_action( 'mtdi_ajax_before_demo_import' );
				$xmldemopackages = get_transient( 'mtdi_theme_packages' );
				if ( empty( $xmldemopackages ) ) {
					$xmldemopackages = $this->retrieve_demo_by_activatetheme( $template );
				}

				if ( is_child_theme() ) {
					$parent_theme 	= get_template();
					$demo_data		= $xmldemopackages[$parent_theme]['child_themes'][ $slug ];
				} else {
					$demo_data	= $xmldemopackages[ $slug ];
				}
				$demoName             = strtoupper( $slug );
				$status['demoName']   = str_replace( '-', ' ', $demoName );
				$status['previewUrl'] = get_home_url();

				if ( ! empty( $demo_data ) ) {
					$status['xmlmessage'] 			=  $this->mtdi_import_dummy_xml( $slug, $demo_data, $status );
					$status['coremessage'] 			=  $this->mtdi_import_core_options( $slug, $demo_data );
					$status['customizermessage'] 	=  $this->mtdi_import_customizer_data( $slug, $demo_data, $status );
					$status['widgetmessage'] 		=  $this->mtdi_import_widget_settings( $slug, $demo_data, $status );
					
					// Update imported demo ID.
					update_option( 'mtdi_activated_check', $slug );
					do_action( 'mtdi_ajax_imported', $slug, $demo_data );

					$activated_demo_check = get_option( 'mtdi_activated_check' );
					if ( $activated_demo_check != '' ) {
						$status['message'] = esc_html__( 'success', 'mysterythemes-demo-importer' );
					} else {
						$status['message'] = esc_html__( 'fail', 'mysterythemes-demo-importer' );
					}
				}
				wp_send_json_success( $status );
			}
			wp_die();
		}

		/**
		 * Import site core options from its ID.
		 * General > Reading > Your homepage displays options
		 *
		 * @since 1.0.0
		 *
		 * @param  string $demo_id
		 * @param  array  $demo_data
		 * @return bool
		 */
		public function mtdi_import_core_options( $demo_id, $demo_data ) {
		    if ( ! empty( $demo_data ) ) {
		        foreach ( $demo_data as $option_key => $option_value ) {

		            if ( ! in_array( $option_key, array( 'name', 'theme_description','blog_description', 'show_on_front', 'blog_title', 'home_title' ) ) ) {
		                continue;
		            }

		            // Initialize page ID variable
		            $page_id = 0;

		            // Attempt to find the page by title
		            $args = array(
		                'post_type'      => 'page',
		                'title'     	 => $option_value,
		                'posts_per_page' => 1,
		                'post_status'    => 'publish',
		            );

		            $query = new WP_Query( $args );

		            if ( $query->have_posts() ) {
		            	while( $query->have_posts() ) {
		            		$query->the_post();
		            		$page_id = get_the_ID();
		            	}
		            }

		            // Reset post data
		            wp_reset_postdata();

		            // Format the value based on option key.
		            switch ( $option_key ) {

		                case 'show_on_front':
		                    // Your latest posts
		                    if ( in_array( $option_value, array( 'posts', 'page' ) ) ) {
		                        update_option( 'show_on_front', sanitize_text_field( $option_value ) );
		                    }
		                    break;

		                case 'home_title':
		                    // static page > Homepage (page_on_front)
		                    if ( $page_id ) {
		                        update_option( 'page_on_front', esc_attr( $page_id ) );
		                    }
		                    break;

		                case 'blog_title':
		                    // static page > Posts page: (page_for_posts)
		                    if ( $page_id ) {
		                        update_option( 'page_for_posts', esc_attr( $page_id ) );
		                    }
		                    break;

		                default:
		                    if ( $option_key == 'name' ) {
		                        $option_key = 'blogname';
		                    } elseif ( $option_key == 'blog_description' ) {
		                        $option_key = 'blogdescription';
		                    }
		                    update_option( $option_key, wp_kses_post( $option_value ) );
		                
		                break;

		            }
		        }
		        return true;
		    }
		}

		/**
		 * Import dummy content from a XML file.
		 *
		 * @since 1.0.0
		 *
		 * @param  string $demo_id
		 * @param  array  $demo_data
		 * @param  array  $status
		 * @return bool
		 */
		public function mtdi_import_dummy_xml( $demo_id, $demo_data, $status ) {
		    global $wp_filesystem;

		    // Initialize the WordPress filesystem, no more using file_put_contents
		    if ( ! function_exists( 'WP_Filesystem' ) ) {
		        require_once ABSPATH . 'wp-admin/includes/file.php';
		    }

		    WP_Filesystem();

		    $import_file_url = $demo_data['xml_file'];

		    // Load Importer API.
		    require_once ABSPATH . 'wp-admin/includes/import.php';

		    if ( ! class_exists( 'WP_Importer' ) ) {
		        $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';

		        if ( file_exists( $class_wp_importer ) ) {
		            require $class_wp_importer;
		        }
		    }

		    require MTDI_PLUGIN_DIR . 'includes/wp-importers/class-mtdi-importer.php';

		    // Import XML file demo content.
		    $folderpath = MTDI_PLUGIN_DIR . 'includes/wp-importers/temp';
		    if ( ! $wp_filesystem->is_dir( $folderpath ) ) {
		        $wp_filesystem->mkdir( $folderpath ); // Use WP_Filesystem for directory creation
		    }
		    $destination_path = $folderpath . "/demo.xml";

		    $response = wp_safe_remote_get( $import_file_url ); // Use wp_safe_remote_get for better security
		    $response_code = wp_remote_retrieve_response_code( $response );

		    if ( is_wp_error( $response ) ) {
		        // Handle the error appropriately
		        error_log( 'Failed to fetch the import file: ' . $response->get_error_message() );
		        $status['errorMsg'] = esc_html__( 'Failed to fetch the import file.', 'mysterythemes-demo-importer' );
		        wp_send_json_error( $status );
		        return false;
		    } elseif ( $response_code == 200 ) {
		        $file_content = wp_remote_retrieve_body( $response );
		        if ( ! $wp_filesystem->put_contents( $destination_path, $file_content, FS_CHMOD_FILE ) ) {
		            // Handle the error if put_contents fails
		            error_log( 'Failed to write the import file to the destination path.' );
		            $status['errorMsg'] = esc_html__( 'Failed to write the import file to the destination path.', 'mysterythemes-demo-importer' );
		            wp_send_json_error( $status );
		            return false;
		        }
		    } else {
		        // Handle the error for non-200 response codes
		        error_log( 'Unexpected response code: ' . $response_code );
		        $status['errorMsg'] = esc_html__( 'Unexpected response code while fetching the import file.', 'mysterythemes-demo-importer' );
		        wp_send_json_error( $status );
		        return false;
		    }

		    $import_file = MTDI_PLUGIN_DIR . 'includes/wp-importers/temp/demo.xml';

		    if ( $wp_filesystem->is_file( $import_file ) ) {
		        $wp_import = new MTDI_Demo_WPImporter();
		        $wp_import->fetch_attachments = true;

		        ob_start();
		        $wp_import->import( $import_file );
		        ob_end_clean();

		        flush_rewrite_rules();
		    } else {
		        $status['errorMsg'] = esc_html__( 'Missing XML file dummy content.', 'mysterythemes-demo-importer' );
		        wp_send_json_error( $status );
		        return false;
		    }

		    return true;
		}

		/**
		 * Import customizer data from a DAT file.
		 *
		 * @since 1.0.0
		 *
		 * @param  string $demo_id
		 * @param  array  $demo_data
		 * @param  array  $status
		 * @return bool
		 */
		public function mtdi_import_customizer_data( $demo_id, $demo_data, $status ) {
		    global $wp_filesystem;

		    // Initialize the WordPress filesystem.
		    if ( ! function_exists( 'WP_Filesystem' ) ) {
		        require_once ABSPATH . 'wp-admin/includes/file.php';
		    }
		    WP_Filesystem();

		    // Define file paths.
		    $import_file_url = $demo_data['theme_settings'];
		    $folderpath = MTDI_PLUGIN_DIR . 'includes/wp-importers/temp';
		    $destinationpath = trailingslashit( $folderpath ) . 'demo-customizer.dat';

		    // Fetch remote file.
		    $response = wp_remote_get( $import_file_url );
		    $response_code = wp_remote_retrieve_response_code( $response );

		    if ( $response_code == '200' ) {
		        // Ensure the folder exists.
		        if ( ! $wp_filesystem->is_dir( $folderpath ) ) {
		            $wp_filesystem->mkdir( $folderpath );
		        }

		        // Write the contents to the destination file.
		        $wp_filesystem->put_contents( $destinationpath, $response['body'], FS_CHMOD_FILE );
		    }

		    // Verify the file exists.
		    if ( $wp_filesystem->is_file( $destinationpath ) ) {
		        $results = MTDI_Customizer_Importer::import( $destinationpath, $demo_id, $demo_data );

		        if ( is_wp_error( $results ) ) {
		            return false;
		        }
		    } else {
		        $status['errorMsg'] = esc_html__( 'The DAT file customizer data is missing.', 'mysterythemes-demo-importer' );
		        wp_send_json_error( $status );
		    }
		    return true;
		}

		/**
		 * Import widgets settings from WIE or JSON file.
		 *
		 * @since 1.0.0
		 *
		 * @param  string $demo_id
		 * @param  array  $demo_data
		 * @param  array  $status
		 * @return bool
		 */
		public function mtdi_import_widget_settings( $demo_id, $demo_data, $status ) {
		    global $wp_filesystem;

		    // Initialize the WordPress filesystem.
		    if ( ! function_exists( 'WP_Filesystem' ) ) {
		        require_once ABSPATH . 'wp-admin/includes/file.php';
		    }
		    WP_Filesystem();

		    // Define file paths.
		    $import_file_wurl = $demo_data['widgets_file'];
		    $folderpath = trailingslashit( MTDI_PLUGIN_DIR . 'includes/wp-importers/temp' );
		    $destination_path = $folderpath . "demo-widget.wie";

		    // Fetch remote file.
		    $response = wp_remote_get( $import_file_wurl );
		    $response_code = wp_remote_retrieve_response_code( $response );

		    if ( $response_code == '200' ) {
		        // Ensure the folder exists.
		        if ( ! $wp_filesystem->is_dir( $folderpath ) ) {
		            $wp_filesystem->mkdir( $folderpath );
		        }

		        // Write the contents to the destination file.
		        $wp_filesystem->put_contents( $destination_path, $response['body'], FS_CHMOD_FILE );
		    }

		    // Verify the file exists.
		    if ( $wp_filesystem->is_file( $destination_path ) ) {
		        $results = MTDI_Widget_Importer::mtdi_import_widget( $destination_path, $demo_id, $demo_data );

		        if ( is_wp_error( $results ) ) {
		            return false;
		        }
		    } else {
		        $status['errorMsg'] = esc_html__( 'The WIE file widget content is missing.', 'mysterythemes-demo-importer' );
		        wp_send_json_error( $status );
		    }
		    return true;
		}

		/**
		 * Include required core importer files.
		 *
		 * @since 1.0.0
		 */
		public function include_files() {
			include_once MTDI_PLUGIN_DIR . 'includes/wp-importers/class-widget-importer.php';
			include_once MTDI_PLUGIN_DIR . 'includes/wp-importers/class-customizer-importer.php';
		}

		/**
		 *  Update widget and customizer demo import settings data.
		 *
		 * @since 1.0.0
		 */
		public function mtdi_update_widget_data( $widget, $widget_type, $instance_id, $demo_data ) {
			if ( ! empty( $demo_data['widgets_data_update'] ) ) {
				foreach ( $demo_data['widgets_data_update'] as $dropdown_type => $dropdown_data ) {
					if ( ! in_array( $dropdown_type, array( 'multi_checkbox', 'dropdown_categories', 'dropdown_pages', 'navigation_menus', 'mega_menus' ) ) ) {
						continue;
					}
				
					// Format the value based on dropdown type.
					switch ( $dropdown_type ) {

						case 'multi_checkbox':
							foreach ( $dropdown_data as $taxonomy => $taxonomy_data ) {
								if ( ! taxonomy_exists( $taxonomy ) ) {
									continue;
								}

								foreach ( $taxonomy_data as $widget_id => $widget_data ) {
									if ( ! empty( $widget_data[ $instance_id ] ) && $widget_id == $widget_type ) {
										foreach ( $widget_data[ $instance_id ] as $widget_key => $widget_value) {
											$widget[$widget_key] = array();
											foreach ( $widget_value as $k => $v ) {
												$term = get_term_by( 'name', $v, $taxonomy );
												if ( is_object( $term ) && $term->term_id ) {
													$term_id = $term->term_id;
													$widget[$widget_key][$term_id] = '1';
												}
											}
										}
									}
								}
							}
							break;

						case 'dropdown_categories':
							foreach ( $dropdown_data as $taxonomy => $taxonomy_data ) {
								if ( ! taxonomy_exists( $taxonomy ) ) {
									continue;
								}

								foreach ( $taxonomy_data as $widget_id => $widget_data ) {
									if ( ! empty( $widget_data[ $instance_id ] ) && $widget_id == $widget_type ) {
										foreach ( $widget_data[ $instance_id ] as $widget_key => $widget_value ) {
											$term = get_term_by( 'name', $widget_value, $taxonomy );

											if ( is_object( $term ) && $term->term_id ) {
												$widget[ $widget_key ] = $term->term_id;
											}
										}
									}
								}
							}
							break;

						case 'navigation_menus':
							foreach ( $dropdown_data as $widget_id => $widget_data ) {
								if ( ! empty( $widget_data[ $instance_id ] ) && $widget_id == $widget_type ) {
									foreach ( $widget_data[ $instance_id ] as $widget_key => $widget_value ) {
										$menu = wp_get_nav_menu_object( $widget_value );

										if ( is_object( $menu ) && $menu->term_id ) {
											$widget[ $widget_key ] = $menu->term_id;
										}
									}
								}
							}
							break;

							case 'dropdown_pages':
							    foreach ( $dropdown_data as $widget_id => $widget_data ) {
							        if ( ! empty( $widget_data[ $instance_id ] ) && $widget_id == $widget_type ) {
							            foreach ( $widget_data[ $instance_id ] as $widget_key => $widget_value ) {
							                $args = array(
							                    'post_type'      => 'page',
							                    'title'          => $widget_value,
							                    'posts_per_page' => 1,
							                    'post_status'    => 'publish',
							                );

							                $query = new WP_Query( $args );

							                if ( $query->have_posts() ) {
							                    $page = $query->posts[0];
							                    if ( is_object( $page ) && $page->ID ) {
							                        $widget[ $widget_key ] = $page->ID;
							                    }
							                }

							                // Reset post data
							                wp_reset_postdata();
							            }
							        }
							    }
							    break;

						case 'mega_menus':
							$nav_menu_items 			=  wp_get_nav_menu_items( $dropdown_data['menu']['name'] );
							$nav_menu_megamenu_items 	= $dropdown_data['menu']['items'];
							
							foreach ( $nav_menu_megamenu_items as $nav_menu_megamenu_item ) {
								$item_title 		= $nav_menu_megamenu_item["title"];
								$meta_key_name 		= $nav_menu_megamenu_item["meta_key"];
								$megamenu_widget_id = isset( $nav_menu_megamenu_item["widget_id"] ) ? $nav_menu_megamenu_item["widget_id"] : '';
								
								foreach ( $nav_menu_items as $nav_menu_item ) {
									if ( $item_title === $nav_menu_item->post_title ) {

										if ( isset( $nav_menu_megamenu_item['data'] ) ) {
											$megamenu_meta_value = get_post_meta( $nav_menu_item->ID, $meta_key_name, true );
											if ( function_exists( 'wp_get_sidebars_widgets' ) ) {
												$widgets 			= wp_get_sidebars_widgets(true);
												$megamenu_widgets 	= $widgets[$megamenu_widget_id];
												$menu_data 			= $nav_menu_megamenu_item['data'];
												foreach ( $menu_data as $menu_dat_key => $menu_dat_value ) {
													foreach ( $menu_dat_value as $widget_per_col_key => $widget_per_col_value ) {
														foreach ( $widget_per_col_value as $widget_num_key => $widget_num_value ) {
															$col_widget_count = 0;
															foreach ( $widget_num_value as $widget_index_key => $widget_index_value ) {
																$widget_index 		= $widget_index_value['index'];
																$widget_id_to_set 	= $megamenu_widgets[$widget_index];
																$megamenu_meta_value['layout'][0]['row'][$widget_per_col_key]['items'][$col_widget_count]['widget_id'] = $widget_id_to_set;
																$col_widget_count++;
															}
														}
													}
												}
												update_post_meta( $nav_menu_item->ID, $meta_key_name, $megamenu_meta_value );
											} // check wp_get_sidebars_widgets function End
										}

									}
								}
							} 
							break;
					}
				}
			}
			return $widget;
		}

		/**
		 * Update customizer settings data.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $data
		 * @param  array $demo_data
		 * @return array
		 */
		public function mtdi_update_customizer_data( $data, $demo_data ) {

			if ( ! empty( $demo_data['customizer_data_update'] ) ) {

				foreach ( $demo_data['customizer_data_update'] as $data_type => $data_value ) {
					if ( ! in_array( $data_type, array( 'pages', 'categories', 'nav_menu_locations', 'multi_categories', 'mega_menus' ) ) ) {
						continue;
					}

					switch ( $data_type ) {

						case 'categories':
							foreach ( $data_value as $taxonomy => $taxonomy_data ) {
								if ( ! taxonomy_exists( $taxonomy ) ) {
									continue;
								}

								foreach ( $taxonomy_data as $option_key => $option_value ) {
									if ( ! empty( $data['mods'][ $option_key ] ) ) {
										$term = get_term_by( 'name', $option_value, $taxonomy );

										if ( is_object( $term ) && $term->term_id ) {
											$data['mods'][ $option_key ] = $term->term_id;
										}
									}
								}
							}
							break;

						case 'multi_categories':
							foreach ( $data_value as $taxonomy => $taxonomy_data ) {
								if ( ! taxonomy_exists( $taxonomy ) ) {
									continue;
								}

								foreach ( $taxonomy_data as $option_key => $option_value ) {
									if ( ! empty( $data['mods'][ $option_key ] ) ) {
										$term_ids = array();
										foreach ( $option_value as $op_key => $op_value ) {
											$term = get_term_by( 'name', $op_value, $taxonomy );
											if ( is_object( $term ) && $term->term_id ) {
												$term_id 	= $term->term_id;
												$term_ids[] = $term_id;
											}
                                        }
        								$multi_values 	= ! is_array( $term_ids ) ? explode( ',', $term_ids ) : $term_ids;
        								$multi_s_value 	= array_map( 'sanitize_text_field', $multi_values );
                                        $data['mods'][ $option_key ] = $multi_s_value;
									}
								}
							}
							break;

						case 'nav_menu_locations':
							$nav_menus = wp_get_nav_menus();

							if ( ! empty( $nav_menus ) ) {
								foreach ( $nav_menus as $nav_menu ) {
									if ( is_object( $nav_menu ) ) {
										foreach ( $data_value as $location => $location_name ) {
											if ( $nav_menu->name == $location_name ) {
												$data['mods'][ $data_type ][ $location ] = $nav_menu->term_id;
											}
										}
									}
								}
							}
							break;

						case 'mega_menus':
							$nav_menu_items 			=  wp_get_nav_menu_items( $data_value['menu']['name'] );
							$nav_menu_megamenu_items 	= $data_value['menu']['items'];
							foreach ( $nav_menu_megamenu_items as $nav_menu_megamenu_item ) {
								$item_title 		= $nav_menu_megamenu_item["title"];
								$meta_key_name 		= $nav_menu_megamenu_item["meta_key"];
								$item_meta_value 	= $nav_menu_megamenu_item["item_meta"];
								foreach ( $nav_menu_items as $nav_menu_item ) {
									if ( $item_title === $nav_menu_item->post_title ) {
										update_post_meta( $nav_menu_item->ID, $meta_key_name, $item_meta_value );
									}
								}
							} 
							break;

					}	// Ends switch
				}
			}
			return $data;
		}

		/**
		 * Update custom nav menu items URL.
		 *
		 * @since 1.0.0
		 */
		public function update_nav_menu_items() {
			$menu_locations = get_nav_menu_locations();
			foreach ( $menu_locations as $location => $menu_id ) {

				if ( is_nav_menu( $menu_id ) ) {
					$menu_items = wp_get_nav_menu_items( $menu_id, array( 'post_status' => 'any' ) );
					if ( ! empty( $menu_items ) ) {
						foreach ( $menu_items as $menu_item ) {
							if ( isset( $menu_item->url ) && isset( $menu_item->db_id ) && 'custom' == $menu_item->type ) {
								$site_parts = parse_url( home_url( '/' ) );
								$menu_parts = parse_url( $menu_item->url );

								// Update existing custom nav menu item URL.
								if ( isset( $menu_parts['path'] ) && isset( $menu_parts['host'] ) && apply_filters( 'mtdi_nav_menu_item_url_hosts', in_array( $menu_parts['host'], array( 'demo.mysterythemes.com', 'localhost' ) ) ) ) {
									$menu_item->url = str_replace( array( $menu_parts['scheme'], $menu_parts['host'], $menu_parts['path'] ), array( $site_parts['scheme'], $site_parts['host'], trailingslashit( $site_parts['path'] ) ), $menu_item->url );
									update_post_meta( $menu_item->db_id, '_menu_item_url', esc_url_raw( $menu_item->url ) );
								}
							}
						} //End foreach
					}
				} // End is_nav_menu

			}
		}

		/**
		 * Set WC pages properly and disable setup wizard redirect.
		 *
		 * After importing demo data filter out duplicate WC pages and set them properly.
		 * Happens when the user run default woocommerce setup wizard during installation.
		 *
		 * Note: WC pages ID are stored in an option and slug are modified to remove any numbers.
		 *
		 * @since 1.0.0
		 *
		 * @param string $demo_id
		 */
		function mt_set_woo_pages( $demo_id ) {
			global $wpdb;
			$woo_pages = apply_filters(
				'mt_woo_' . $demo_id . '_pages',
				array(
					'shop'      => array(
						'name'  => 'shop',
						'title' => esc_html__( 'Shop', 'mysterythemes-demo-importer' ),
					),
					'cart'      => array(
						'name'  => 'cart',
						'title' => esc_html__( 'Cart', 'mysterythemes-demo-importer' ),
					),
					'checkout'  => array(
						'name'  => 'checkout',
						'title' => esc_html__( 'Checkout', 'mysterythemes-demo-importer' ),
					),
					'myaccount' => array(
						'name'  => 'my-account',
						'title' => esc_html__( 'My Account', 'mysterythemes-demo-importer' ),
					),
				)
			);

			// Set WooCommerce pages properly.
			foreach ( $woo_pages as $key => $wc_page ) {

				// Get the ID of every page with matching name or title.
				$page_ids = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE (post_name = %s OR post_title = %s) AND post_type = 'page' AND post_status = 'publish'", $wc_page['name'], $wc_page['title'] ) );

				if ( ! is_null( $page_ids ) ) {
					$page_id    = 0;
					$delete_ids = array();

					// Retrieve page with greater id and delete others.
					if ( sizeof( $page_ids ) > 1 ) {
						foreach ( $page_ids as $page ) {
							if ( $page->ID > $page_id ) {
								if ( $page_id ) {
									$delete_ids[] = $page_id;
								}

								$page_id = $page->ID;
							} else {
								$delete_ids[] = $page->ID;
							}
						}
					} else {
						$page_id = $page_ids[0]->ID;
					}

					// Delete posts.
					foreach ( $delete_ids as $delete_id ) {
						wp_delete_post( $delete_id, true );
					}

					// Update WC page.
					if ( $page_id > 0 ) {
						update_option( 'woocommerce_' . $key . '_page_id', $page_id );
						wp_update_post(
							array(
								'ID'        => $page_id,
								'post_name' => sanitize_title( $wc_page['name'] ),
							)
						);
					}
				}
			}
			// We no longer need WC setup wizard redirect.
			delete_transient( '_wc_activation_redirect' );
		}
	
		/**
		 * Update elementor settings data.
		 *
		 * @since 1.0.0
		 *
		 * @param string $demo_id Demo ID.
		 * @param array  $demo_data Demo Data.
		 */
		public function update_elementor_data( $demo_id, $demo_data ) {
		    if ( ! empty( $demo_data['elementor_data_update'] ) ) {
		        foreach ( $demo_data['elementor_data_update'] as $data_type => $data_value ) {
		            if ( ! empty( $data_value['post_title'] ) ) {
		                $args = array(
		                    'post_type'      => 'page',
		                    'title'          => $data_value['post_title'],
		                    'posts_per_page' => 1,
		                    'post_status'    => 'publish',
		                );

		                $query = new WP_Query( $args );

		                if ( $query->have_posts() ) {
		                    $page = $query->posts[0];

		                    if ( is_object( $page ) && $page->ID ) {
		                        $elementor_data = get_post_meta( $page->ID, '_elementor_data', true );

		                        if ( ! empty( $elementor_data ) ) {
		                            $elementor_data = $this->elementor_recursive_update( $elementor_data, $data_type, $data_value );
		                        }

		                        // Update elementor data.
		                        update_post_meta( $page->ID, '_elementor_data', $elementor_data );
		                    }
		                }

		                // Reset post data
		                wp_reset_postdata();
		            }
		        }
		    }
		}

		/**
		 * Delete the `Hello world!` post after successful demo import
		 *
		 * @since 1.0.0 
		 */
		function delete_post_import() {
		    $args = array(
		        'post_type' => 'post',
		        'post_status' => 'any',
		        'posts_per_page' => 1,
		        'title' => 'Hello world!',
		    );

		    $query = new WP_Query( $args );

		    if ( $query->have_posts() ) {
		        $page = $query->posts[0];

		        if ( is_object( $page ) && $page->ID ) {
		            wp_delete_post( $page->ID, true );
		        }
		    }

		    // Reset post data
		    wp_reset_postdata();
		}


		/**
		 * Update siteorigin panel settings data.
		 *
		 * @since 1.0.0
		 *
		 * @param string $demo_id Demo ID.
		 * @param array  $demo_data Demo Data.
		 */
		public function update_siteorigin_data( $demo_id, $demo_data ) {
		    if ( ! empty( $demo_data['siteorigin_panels_data_update'] ) ) {
		        foreach ( $demo_data['siteorigin_panels_data_update'] as $data_type => $data_value ) {
		            if ( ! empty( $data_value['post_title'] ) ) {
		                $args = array(
		                    'post_type'      => 'page',
		                    'title'          => $data_value['post_title'],
		                    'posts_per_page' => 1,
		                    'post_status'    => 'publish',
		                );

		                $query = new WP_Query( $args );

		                if ( $query->have_posts() ) {
		                    $page = $query->posts[0];

		                    if ( is_object( $page ) && $page->ID ) {
		                        $panels_data = get_post_meta( $page->ID, 'panels_data', true );

		                        if ( ! empty( $panels_data ) ) {
		                            $panels_data = $this->siteorigin_recursive_update( $panels_data, $data_type, $data_value );
		                        }

		                        // Update siteorigin panels data.
		                        update_post_meta( $page->ID, 'panels_data', $panels_data );
		                    }
		                }

		                // Reset post data
		                wp_reset_postdata();
		            }
		        }
		    }
		}

		/**
		 * Recursive function to address n level deep layout builder data update.
		 *
		 * @since 1.0.0
		 *
		 * @param  array  $panels_data
		 * @param  string $data_type
		 * @param  array  $data_value
		 * @return array
		 */
		public function siteorigin_recursive_update( $panels_data, $data_type, $data_value ) {
			static $instance = 0;

			foreach ( $panels_data as $panel_type => $panel_data ) {
				// Format the value based on panel type.
				switch ( $panel_type ) {

					case 'grids':
						foreach ( $panel_data as $instance_id => $grid_instance ) {
							if ( ! empty( $data_value['data_update']['grids_data'] ) ) {
								foreach ( $data_value['data_update']['grids_data'] as $grid_id => $grid_data ) {
									if ( ! empty( $grid_data['style'] ) && $instance_id === $grid_id ) {
										$level = isset( $grid_data['level'] ) ? $grid_data['level'] : (int) 0;
										if ( $level == $instance ) {
											foreach ( $grid_data['style'] as $style_key => $style_value ) {
												if ( empty( $style_value ) ) {
													continue;
												}

												// Format the value based on style key.
												switch ( $style_key ) {

													case 'background_image_attachment':
														$attachment_id = $this->get_attachment_id( $style_value );

														if ( 0 !== $attachment_id ) {
															$grid_instance['style'][ $style_key ] = $attachment_id;
														}
														break;

													default:
														$grid_instance['style'][ $style_key ] = $style_value;
														break;

												}
											}
										}
									}
								}
							}

							// Update panel grids data.
							$panels_data['grids'][ $instance_id ] = $grid_instance;
						}
						break;

					case 'widgets':
						foreach ( $panel_data as $instance_id => $widget_instance ) {
							if ( isset( $widget_instance['panels_data']['widgets'] ) ) {
								$instance          = $instance + 1;
								$child_panels_data = $widget_instance['panels_data'];
								$panels_data['widgets'][ $instance_id ]['panels_data'] = $this->siteorigin_recursive_update( $child_panels_data, $data_type, $data_value );
								$instance = $instance - 1;
								continue;
							}

							if ( isset( $widget_instance['nav_menu'] ) && isset( $widget_instance['title'] ) ) {
								$nav_menu = wp_get_nav_menu_object( $widget_instance['title'] );

								if ( is_object( $nav_menu ) && $nav_menu->term_id ) {
									$widget_instance['nav_menu'] = $nav_menu->term_id;
								}
							} elseif ( ! empty( $data_value['data_update']['widgets_data'] ) ) {
								$instance_class = $widget_instance['panels_info']['class'];

								foreach ( $data_value['data_update']['widgets_data'] as $dropdown_type => $dropdown_data ) {
									if ( ! in_array( $dropdown_type, array( 'dropdown_pages', 'dropdown_categories' ) ) ) {
										continue;
									}

									// Format the value based on data type.
									switch ( $dropdown_type ) {

										case 'dropdown_pages':
										    foreach ( $dropdown_data as $widget_id => $widget_data ) {
										        if ( ! empty( $widget_data[ $instance_id ] ) && $widget_id == $instance_class ) {
										            $level = isset( $widget_data['level'] ) ? $widget_data['level'] : (int) 0;

										            if ( $level == $instance ) {
										                foreach ( $widget_data[ $instance_id ] as $widget_key => $widget_value ) {
										                    $args = array(
										                        'post_type'      => 'page',
										                        'title'          => $widget_value,
										                        'posts_per_page' => 1,
										                        'post_status'    => 'publish',
										                    );

										                    $query = new WP_Query( $args );

										                    if ( $query->have_posts() ) {
										                        $page = $query->posts[0];

										                        if ( is_object( $page ) && $page->ID ) {
										                            $widget_instance[ $widget_key ] = $page->ID;
										                        }
										                    }

										                    // Reset post data
										                    wp_reset_postdata();
										                }
										            }
										        }
										    }
										    break;

										case 'dropdown_categories':
											foreach ( $dropdown_data as $taxonomy => $taxonomy_data ) {
												if ( ! taxonomy_exists( $taxonomy ) ) {
													continue;
												}

												foreach ( $taxonomy_data as $widget_id => $widget_data ) {
													if ( ! empty( $widget_data[ $instance_id ] ) && $widget_id == $instance_class ) {
														$level = isset( $widget_data['level'] ) ? $widget_data['level'] : (int) 0;

														if ( $level == $instance ) {
															foreach ( $widget_data[ $instance_id ] as $widget_key => $widget_value ) {
																$term = get_term_by( 'name', $widget_value, $taxonomy );

																if ( is_object( $term ) && $term->term_id ) {
																	$widget_instance[ $widget_key ] = $term->term_id;
																}
															}
														}
													}
												}
											}
											break;
									}
								}
							}
							$panels_data['widgets'][ $instance_id ] = $widget_instance;
						}
						break;
				}
			}
			return $panels_data;
		}

		/**
		 * Get an attachment ID from the filename.
		 *
		 * @since 1.0.0
		 *
		 * @param  string $filename
		 * @return int Attachment ID on success, 0 on failure
		 */
		function get_attachment_id( $filename ) {

			$attachment_id = 0;

			$file = basename( $filename );

			$query_args = array(
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'value'   => $file,
						'compare' => 'LIKE',
						'key'     => '_wp_attachment_metadata',
					),
				),
			);

			$query = new WP_Query( $query_args );

			if ( $query->have_posts() ) {

				foreach ( $query->posts as $post_id ) {

					$meta = wp_get_attachment_metadata( $post_id );

					$original_file       = basename( $meta['file'] );
					$cropped_image_files = wp_list_pluck( $meta['sizes'], 'file' );

					if ( $original_file === $file || in_array( $file, $cropped_image_files ) ) {
						$attachment_id = $post_id;
						break;
					}
				}
			}
			return $attachment_id;
		}


		/**
		 * Recursive function to address n level deep elementor data update.
		 *
		 * @since 1.0.0
		 *
		 * @param  array  $elementor_data
		 * @param  string $data_type
		 * @param  array  $data_value
		 * @return array
		 */
		public function elementor_recursive_update( $elementor_data, $data_type, $data_value ) {

			$elementor_data = json_decode( stripslashes( $elementor_data ), true );

			// Recursively update elementor data.
			foreach ( $elementor_data as $element_id => $element_data ) {
				if ( ! empty( $element_data['elements'] ) ) {
					foreach ( $element_data['elements'] as $el_key => $el_data ) {
						if ( ! empty( $el_data['elements'] ) ) {
							foreach ( $el_data['elements'] as $el_child_key => $child_el_data ) {
								if ( 'widget' === $child_el_data['elType'] ) {
									$settings   = isset( $child_el_data['settings'] ) ? $child_el_data['settings'] : array();
									$widgetType = isset( $child_el_data['widgetType'] ) ? $child_el_data['widgetType'] : '';

									if ( isset( $settings['display_type'] ) && 'categories' === $settings['display_type'] ) {
										$categories_selected = isset( $settings['categories_selected'] ) ? $settings['categories_selected'] : '';

										if ( ! empty( $data_value['data_update'] ) ) {
											foreach ( $data_value['data_update'] as $taxonomy => $taxonomy_data ) {
												if ( ! taxonomy_exists( $taxonomy ) ) {
													continue;
												}

												foreach ( $taxonomy_data as $widget_id => $widget_data ) {
													if ( ! empty( $widget_data ) && $widget_id == $widgetType ) {
														if ( is_array( $categories_selected ) ) {
															foreach ( $categories_selected as $cat_key => $cat_id ) {
																if ( isset( $widget_data[ $cat_id ] ) ) {
																	$term = get_term_by( 'name', $widget_data[ $cat_id ], $taxonomy );

																	if ( is_object( $term ) && $term->term_id ) {
																		$categories_selected[ $cat_key ] = $term->term_id;
																	}
																}
															}
														} elseif ( isset( $widget_data[ $categories_selected ] ) ) {
															$term = get_term_by( 'name', $widget_data[ $categories_selected ], $taxonomy );

															if ( is_object( $term ) && $term->term_id ) {
																$categories_selected = $term->term_id;
															}
														}
													}
												}
											}
										}

										// Update the elementor data.
										$elementor_data[ $element_id ]['elements'][ $el_key ]['elements'][ $el_child_key ]['settings']['categories_selected'] = $categories_selected;
									}
								}
							}
						}
					}
				}
			}
			return wp_json_encode( $elementor_data );
		}

		/**
		 * Reset existing active widgets.
		 *
		 * @since 1.0.0
		 */
		function mt_reset_widgets() {
			$sidebars_widgets = wp_get_sidebars_widgets();

			// Reset active widgets.
			foreach ( $sidebars_widgets as $key => $widgets ) {
				$sidebars_widgets[ $key ] = array();
			}
			wp_set_sidebars_widgets( $sidebars_widgets );
		}

		/**
		 * Delete existing navigation menus.
		 *
		 * @since 1.0.0
		 */
		function mt_delete_nav_menus() {
			$nav_menus = wp_get_nav_menus();

			// Delete navigation menus.
			if ( ! empty( $nav_menus ) ) {
				foreach ( $nav_menus as $nav_menu ) {
					wp_delete_nav_menu( $nav_menu->slug );
				}
			}
		}

		/**
		 * Remove theme modifications option.
		 *
		 * @since 1.0.0
		 */
		function mt_remove_theme_mods() {
			remove_theme_mods();
		}

		/**
		 * Display action links in the Plugins list table.
		 *
		 * @since 1.0.0
		 *
		 * @param  array $actions Plugin Action links.
		 * @return array
		 */
		function plugin_action_links( $actions ) {
			$new_actions = array(
				'importer' => '<a href="' . admin_url( 'themes.php?page=mysterythemes-demo-importer' ) . '" aria-label="' . esc_attr( __( 'View Demo Importer', 'mysterythemes-demo-importer') ) . '">' . esc_html__( 'MT Demo Importer', 'mysterythemes-demo-importer') . '</a>',
			);
			return array_merge( $new_actions, $actions );
		}

		/**
		 * Display row meta in the Plugins list table.
		 *
		 * @since 1.0.0
		 *
		 * @param  array  $plugin_meta Plugin Row Meta.
		 * @param  string $plugin_file Plugin Row Meta.
		 * @return array
		 */
		function plugin_row_meta( $plugin_meta, $plugin_file ) {
			if ( MTDI_PLUGIN_BASENAME === $plugin_file ) {
				$new_plugin_meta = array(
					'docs'    => '<a href="' . esc_url( apply_filters( 'mtdi_demo_importer_docs_url', '//mysterythemes.com/docs/mysterythemes-demo-importer/' ) ) . '" title="' . esc_attr( __( 'View Demo Importer Documentation', 'mysterythemes-demo-importer' ) ) . '" target="_blank">' . esc_html__( 'Documentation', 'mysterythemes-demo-importer' ) . '</a>',
					'support' => '<a href="' . esc_url( apply_filters( 'mtdi_demo_importer_support_url', '//wordpress.org/support/plugin/mysterythemes-demo-importer' ) ) . '" title="' . esc_attr( __( 'Visit Free Support Forum', 'mysterythemes-demo-importer' ) ) . '" target="_blank">' . esc_html__( 'Free Support', 'mysterythemes-demo-importer' ) . '</a>',
				);

				return array_merge( $plugin_meta, $new_plugin_meta );
			}
			return (array) $plugin_meta;
		}
		
		/**
		 * Theme support fallback notice.
		 *
		 * @since 1.0.0
		 */
		public function missing_notice() {
			$admin_obj = new MTDI();
			$themes_url = array_intersect( array_keys( wp_get_themes() ), $admin_obj->get_supported_themes() ) ? admin_url( 'themes.php?search=mysterythemes' ) : admin_url( 'theme-install.php?search=mysterythemes' );

			/* translators: %s: official Mystery themes URL */
			echo '<div class="error notice is-dismissible"><p><strong>' . esc_html__( 'Mystery Themes Demo Importer', 'mysterythemes-demo-importer') . '</strong> &#8211; ' . sprintf( esc_html__( 'This plugin requires %s to be activated to work.', 'mysterythemes-demo-importer'), '<a href="' . esc_url( $themes_url ) . '">' . esc_html__( 'Official Mystery Themes', 'mysterythemes-demo-importer') . '</a>' ) . '</p></div>';
		}
	}

endif;