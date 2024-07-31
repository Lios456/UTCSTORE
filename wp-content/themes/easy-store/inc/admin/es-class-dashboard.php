<?php
/**
 * Admin Dashboard Notice
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Easy_Store_Admin_Dashboard' ) ) :

	/**
	 * Class Easy_Store_Admin_Dashboard
	 */
	class Easy_Store_Admin_Dashboard {

		public $theme_name;
		public $theme_slug;
		public $theme_author_uri;
		public $theme_author_name;
		public $free_plugins;

		/**
		 * Easy_Store_Admin_Dashboard constructor.
		 */
		public function __construct() {

			global $admin_main_class;

			add_action( 'admin_menu', array( $this, 'easy_store_admin_menu' ) );

			//theme details
			$theme                      = wp_get_theme();
			$this->theme_name           = $theme->get( 'Name' );
			$this->theme_slug           = $theme->get( 'TextDomain' );
			$this->theme_author_uri     = $theme->get( 'AuthorURI' );
			$this->theme_author_name    = $theme->get( 'Author' );

			$this->free_plugins = $admin_main_class->easy_store_free_plugins_lists();
		}

		/**
		 * Add admin menu.
		 */
		public function easy_store_admin_menu() {
			add_theme_page( sprintf( esc_html__( '%1$s Dashboard', 'easy-store' ), $this->theme_name ), sprintf( esc_html__( '%1$s Dashboard', 'easy-store' ), $this->theme_name ) , 'edit_theme_options', 'easy-store-dashboard', array( $this, 'get_started_screen' ) );
		}

		/**
		 * Get started screen page.
		 */
		public function get_started_screen() {
			$current_tab = empty( $_GET['tab'] ) ? 'welcome' : sanitize_title( $_GET['tab'] );

			// Look for a {$current_tab}_screen method.
			if ( is_callable( array( $this, $current_tab . '_screen' ) ) ) {
				return $this->{ $current_tab . '_screen' }();
			}

			// Fallback to about screen.
			return $this->welcome_screen();
		}

		/**
		 * Dashboard header
		 *
		 * @access private
		 */
		private function dashboard_header() {
	?>
			<div class="dashboard-header">
				<div class="easy-store-container">
					<div class="header-top">
						<h1 class="heading"><?php printf( esc_html__( '%1$s Options', 'easy-store' ), $this->theme_name ); ?></h1>
						<span class="theme-version"><?php printf( esc_html__( 'Version: %1$s', 'easy-store' ), EASY_STORE_VERSION ); ?></span>
						<span class="author-link"><?php printf( wp_kses_post( 'By <a href="%1$s" target="_blank">%2$s</a>', 'easy-store' ), $this->theme_author_uri, $this->theme_author_name ); ?></span>
					</div><!-- .header-top -->
					<div class="header-nav">
						<nav class="dashboard-nav">
							<li>
								<a class="nav-tab <?php if ( empty( $_GET['tab'] ) && $_GET['page'] == 'easy-store-dashboard' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'easy-store-dashboard' ), 'themes.php' ) ) ); ?>">
									<span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e( 'Welcome', 'easy-store' ); ?>
								</a>
							</li>
							<li>
								<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'starter' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'easy-store-dashboard', 'tab' => 'starter' ), 'themes.php' ) ) ); ?>">
									<span class="dashicons dashicons-images-alt2"></span> <?php esc_html_e( 'Stater Sites', 'easy-store' ); ?>
								</a>
							</li>
							<li>
								<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'free_pro' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'easy-store-dashboard', 'tab' => 'free_pro' ), 'themes.php' ) ) ); ?>">
									<span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'Free Vs Pro', 'easy-store' ); ?>
								</a>
							</li>
							<li>
								<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'plugin' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'easy-store-dashboard', 'tab' => 'plugin' ), 'themes.php' ) ) ); ?>">
									<span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e( 'Plugins', 'easy-store' ); ?>
								</a>
							</li>
							<li>
								<a class="nav-tab <?php if ( isset( $_GET['tab'] ) && $_GET['tab'] == 'changelog' ) echo 'active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'easy-store-dashboard', 'tab' => 'changelog' ), 'themes.php' ) ) ); ?>">
									<span class="dashicons dashicons-flag"></span> <?php esc_html_e( 'Changelog', 'easy-store' ); ?>
								</a>
							</li>
						</nav>
						<div class="upgrade-pro">
							<a href="<?php echo esc_url( 'https://mysterythemes.com/pricing/?product_id=12736' ); ?>" target="_blank" class="button button-primary"><?php esc_html_e( 'More Features With Pro', 'easy-store' ); ?></a>
						</div><!-- .upgrade-pro -->
					</div><!-- .header-nav -->
				</div><!-- .easy-store-container -->
			</div><!-- .dashboard-header -->
	<?php
		}

		/**
		 * Dashboard sidebar
		 * 
		 * @access private
		 */
		private function dashboard_sidebar() {

			$review_url = 'https://wordpress.org/support/theme/'. $this->theme_slug .'/reviews/?filter=5#new-post';
	?>
			<div class="sidebar-wrapper">
				<aside class="sidebar">
					<div class="sidebar-block">
						<h4 class="block-title"><?php esc_html_e( 'Leave us a review', 'easy-store' ); ?></h4>
						<p><?php printf( wp_kses_post( 'Are you are enjoying <b>%1$s</b>? We would love to hear your feedback.', 'easy-store' ), $this->theme_name ); ?></p>
						<a class="button button-primary" href="<?php echo esc_url( $review_url ); ?>" target="_blank" rel="external noopener noreferrer">
							<?php esc_html_e( 'Submit a review', 'easy-store' ); ?>
							<span class="dashicons dashicons-external"></span>
						</a>
					</div><!-- .sidebar-block -->
				</aside>
			</div><!-- .sidebar-wrapper -->
	<?php
		}

		/**
		 * render the welcome screen.
		 */
		public function welcome_screen() {
			$doc_url        = 'https://docs.mysterythemes.com/'. $this->theme_slug;
			$support_url    = 'https://wordpress.org/support/theme/'. $this->theme_slug;
	?>
			<div id="easy-store-dashboard">
				<?php $this->dashboard_header(); ?>
				<div class="dashboard-content-wrapper">
					<div class="easy-store-container">
						
						<div class="main-content welcome-content-wrapper">
							
							<div class="welcome-block quick-links">
								<div class="block-header">
									<img class="block-icon" src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/img/quick-link.svg' ); ?>" alt="icon">
									<h3 class="block-title"><?php esc_html_e( 'Customizer quick link', 'easy-store' ); ?></h3>
								</div><!-- .block-header -->
								<div class="block-content content-column">
									<div class="col">
										<li>
											<a href="<?php echo esc_url( admin_url( 'customize.php' ).'?autofocus[section]=title_tagline' ); ?>" target="_blank" class="welcome-icon"><span class="dashicons dashicons-visibility"></span><?php esc_html_e( 'Setup site logo', 'easy-store' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( admin_url( 'customize.php' ).'?autofocus[section]=easy_store_social_icons_section' ); ?>" target="_blank" class="welcome-icon"> <span class="dashicons dashicons-menu-alt"> </span><?php esc_html_e( 'Social Icons', 'easy-store' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( admin_url( 'customize.php' ).'?autofocus[section]=easy_store_sidebar_section' ); ?>" target="_blank" class="welcome-icon "><span class="dashicons dashicons-text-page"> </span><?php esc_html_e( 'Manage Sidebar', 'easy-store' ); ?></a>
										</li>
									</div>
									<div class="col">
										<li>
											<a href="<?php echo esc_url( admin_url( 'customize.php' ).'?autofocus[panel]=easy_store_header_settings_panel' ); ?>" target="_blank" class="welcome-icon"> <span class="dashicons dashicons-admin-tools"> </span> <?php esc_html_e( 'Header Settings', 'easy-store' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( admin_url( 'customize.php' ).'?autofocus[panel]=easy_store_front_page_settings_panel' ); ?>" target="_blank" class="welcome-icon"> <span class="dashicons dashicons-welcome-widgets-menus"> </span><?php esc_html_e( 'Front Page Settings', 'easy-store' ); ?></a>
										</li>
										<li>
											<a href="<?php echo esc_url( admin_url( 'customize.php' ).'?autofocus[panel]=easy_store_footer_settings_panel' ); ?>" target="_blank" class="welcome-icon"><span class="dashicons dashicons-media-document"> </span><?php esc_html_e( 'Footer Settings', 'easy-store' ); ?></a>
										</li>
									</div>
								</div><!-- .block-content -->
							</div><!-- .welcome-block.quick-links -->

							<div class="welcome-block documentation">
								<div class="block-header">
									<img class="block-icon" src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/img/docs.svg' ); ?>" alt="icon">
									<h3 class="block-title"><?php esc_html_e( 'Theme Documentation', 'easy-store' ); ?></h3>
								</div><!-- .block-header -->
								<div class="block-content">
									<p>
										<?php printf( wp_kses_post( 'Need more details? Please check our full documentation for detailed information on how to use <b>%1$s</b>.', 'easy-store' ), $this->theme_name ); ?>
										<a href="<?php echo esc_url( $doc_url ); ?>" target="_blank"><?php esc_html_e( 'Go to doc', 'easy-store' ); ?><span class="dashicons dashicons-external"></span></a>
									</p>
								</div><!-- .block-content -->
							</div><!-- .welcome-block documentation -->

							<div class="welcome-block support">
								<div class="block-header">
									<img class="block-icon" src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/img/support.svg' ); ?>" alt="icon">
									<h3 class="block-title"><?php esc_html_e( 'Contact Support', 'easy-store' ); ?></h3>
								</div><!-- .block-header -->
								<div class="block-content">
									<p>
										<?php printf( wp_kses_post( 'We want to make sure you have the best experience using <b>%1$s</b>, and that is why we have gathered all the necessary information here for you. We hope you will enjoy using <b>%1$s</b> as much as we enjoy creating great products.', 'easy-store' ), $this->theme_name ); ?>
										<a href="<?php echo esc_url( $support_url ); ?>" target="_blank"><?php esc_html_e( 'Contact Support', 'easy-store' ); ?><span class="dashicons dashicons-external"></span></a>
									</p>
								</div><!-- .block-content -->
							</div><!-- .welcome-block support -->

							<div class="welcome-block tutorial">
								<div class="block-header">
									<img class="block-icon" src="<?php echo esc_url( get_template_directory_uri() . '/inc/admin/assets/img/tutorial.svg' ); ?>" alt="icon">
									<h3 class="block-title"><?php esc_html_e( 'Tutorial', 'easy-store' ); ?></h3>
								</div><!-- .block-header -->
								<div class="block-content">
									<p>
										<?php printf( wp_kses_post( 'This tutorial has been prepared for those who have a basic knowledge of HTML and CSS and has an urge to develop websites. After completing this tutorial, you will find yourself at a moderate level of expertise in developing sites or blogs using WordPress.', 'easy-store' ), $this->theme_name ); ?>
										<a href="<?php echo esc_url( 'https://wpallresources.com/' ); ?>" target="_blank"><?php esc_html_e( 'WP Tutorials', 'easy-store' ); ?><span class="dashicons dashicons-external"></span></a>
									</p>
								</div><!-- .block-content -->
							</div><!-- .welcome-block tutorial -->

							<div class="return-to-dashboard">
								<?php if ( current_user_can( 'update_core' ) && isset( $_GET['updated'] ) ) : ?>
									<a href="<?php echo esc_url( self_admin_url( 'update-core.php' ) ); ?>">
										<?php is_multisite() ? esc_html_e( 'Return to Updates', 'easy-store' ) : esc_html_e( 'Return to Dashboard &rarr; Updates', 'easy-store' ); ?>
									</a> |
								<?php endif; ?>
								<a href="<?php echo esc_url( self_admin_url() ); ?>"><?php is_blog_admin() ? esc_html_e( 'Go to Dashboard &rarr; Home', 'easy-store' ) : esc_html_e( 'Go to Dashboard', 'easy-store' ); ?></a>
							</div><!-- .return-to-dashboard -->

						</div><!-- .welcome-content-wrapper -->

						<?php $this->dashboard_sidebar(); ?>

					</div><!-- .easy-store-container -->
				</div><!-- .dashboard-content-wrapper -->
			</div><!-- #easy-store-dashboard -->
	<?php
		}

		/**
		 * render the starter sites screen
		 */
		public function starter_screen() {
			global $admin_main_class;

			$activated_theme    = get_template();
			$demodata           = get_transient( 'easy_store_demo_packages' );
			
			if ( empty( $demodata ) || $demodata == false ) {
				$demodata = get_transient( 'mtdi_theme_packages' );
				if ( $demodata ) {
					set_transient( 'easy_store_demo_packages', $demodata, WEEK_IN_SECONDS );
				}
			}

			$activated_demo_check   = get_option( 'mtdi_activated_check' );
	?>
			<div id="easy-store-dashboard">
				<?php $this->dashboard_header(); ?>
				<div class="dashboard-content-wrapper starter-dashboard-content-wrapper">
					<div class="easy-store-container">

						<div class="main-content starter-content-wrapper">
							<div class="easy-store-theme-demos rendered <?php if( isset( $demodata ) && empty( $demodata ) ) echo "no-demo-sites" ?>">
								<?php $admin_main_class->install_demo_import_plugin_popup(); ?>
								<div class="demo-listing-wrapper wp-clearfix">
									<?php if ( isset( $demodata ) && empty( $demodata ) ) { ?>
										<span class="configure-msg"><?php esc_html_e( 'No demos are configured for this theme, please contact the theme author', 'easy-store' ); ?></span>
									<?php
										} else {
									?>
										<div class="all-demos-wrap">
											<?php
												foreach ( $demodata as $value ) {
													$theme_name         = $value['name'];
													$theme_slug         = $value['theme_slug'];
													$preview_screenshot = $value['preview_screen'];
													$demourl            = $value['preview_url'];
													if ( ( strpos( $activated_theme, 'pro' ) !== false && strpos( $theme_slug, 'pro' ) !== false ) || ( strpos( $activated_theme, 'pro' ) == false ) ) {
											?>
														<div class="single-demo<?php if ( strpos( $activated_theme, 'pro' ) == false && strpos( $theme_slug, 'pro' ) !== false ) { echo ' pro-demo'; } ?>" data-categories="ltrdemo" data-name="<?php echo esc_attr ( $theme_slug ); ?>" style="display: block;">
															<div class="preview-screenshot">
																<a href="<?php echo esc_url ( $demourl ); ?>" target="_blank">
																	<img class="preview" src="<?php echo esc_url ( $preview_screenshot ); ?>" />
																</a>
															</div><!-- .preview-screenshot -->
															<div class="demo-info-wrapper">
																<h2 class="mtdi-theme-name theme-name" id="nokri-name"><?php echo esc_html ( $theme_name ); ?></h2>
																<div class="mtdi-theme-actions theme-actions">
																	<?php
																		if ( $activated_demo_check != '' && $activated_demo_check == $theme_slug ) {
																	?>
																			<a class="button disabled button-primary hide-if-no-js" href="javascript:void(0);" data-name="<?php echo esc_attr ( $theme_name ); ?>" data-slug="<?php echo esc_attr ( $theme_slug ); ?>" aria-label="<?php printf ( esc_html__( 'Imported %1$s', 'easy-store' ), $theme_name ); ?>">
																				<?php esc_html_e( 'Imported', 'easy-store' ); ?>
																			</a>
																	<?php
																		} else {
																			if ( strpos( $activated_theme, 'pro' ) == false && strpos( $theme_slug, 'pro' ) !== false ) {
																				$s_slug = explode( "-pro", $theme_slug );
																				$purchaseurl = 'https://mysterythemes.com/wp-themes/'.$s_slug[0].'-pro';
																	?>
																				<a class="button button-primary mtdi-purchasenow" href="<?php echo esc_url( $purchaseurl ); ?>" target="_blank" data-name="<?php echo esc_attr ( $theme_name ); ?>" data-slug="<?php echo esc_attr ( $theme_slug ); ?>" aria-label="<?php printf ( esc_html__( 'Purchase Now', 'easy-store' ), $theme_name ); ?>">
																					<?php esc_html_e( 'Buy Now', 'easy-store' ); ?>
																				</a>
																	<?php
																			} else {
																				if ( is_plugin_active( 'mysterythemes-demo-importer/mysterythemes-demo-importer.php' ) ) {
																					$button_tooltip = esc_html__( 'Click to import demo', 'easy-store' );
																				} else {
																					$button_tooltip = esc_html__( 'Demo importer plugin is not installed or activated', 'easy-store' );
																				}
																	?>
																				<a title="<?php echo esc_attr( $button_tooltip ); ?>" class="button button-primary hide-if-no-js mtdi-demo-import" href="javascript:void(0);" data-name="<?php echo esc_attr ( $theme_name ); ?>" data-slug="<?php echo esc_attr ( $theme_slug ); ?>" aria-label="<?php printf ( esc_attr__( 'Import %1$s', 'easy-store' ), $theme_name ); ?>">
																					<?php esc_html_e( 'Import', 'easy-store' ); ?>
																				</a>
																	<?php
																			}
																		}
																	?>
																		<a class="button preview install-demo-preview" target="_blank" href="<?php echo esc_url ( $demourl ); ?>">
																			<?php esc_html_e( 'View Demo', 'easy-store' ); ?>
																		</a>
																</div><!-- .mtdi-theme-actions -->
															</div><!-- .demo-info-wrapper -->
														</div><!-- .single-demo -->
											<?php
													}
												}
											?>
										</div><!-- .mtdi-demo-wrapper -->
								<?php
									}
								?>
								</div><!-- .demo-listing-wrapper -->

							</div><!-- .easy-store-theme-demos -->

						</div><!-- .starter-content-wrapper -->
					</div><!-- .easy-store-container -->
				</div><!-- .dashboard-content-wrapper -->
			</div><!-- #easy-store-dashboard -->
	<?php
		}

		/**
		 * render the free vs pro screen
		 */
		public function free_pro_screen() {
	?>
			<div id="easy-store-dashboard">
				<?php $this->dashboard_header(); ?>
				<div class="dashboard-content-wrapper">
					<div class="easy-store-container">

						<div class="main-content free-pro-content-wrapper">
							
							<table class="compare-table">
								<thead>
									<tr>
										<th class="table-feature-title"><h3><?php esc_html_e( 'Features', 'easy-store' ); ?></h3></th>
										<th><h3><?php echo esc_html( $this->theme_name ); ?></h3></th>
										<th><h3><?php esc_html_e( 'Easy Store Pro', 'easy-store' ); ?></h3></th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><h3><?php esc_html_e( 'Price', 'easy-store' ); ?></h3></td>
										<td><?php esc_html_e( 'Free', 'easy-store' ); ?></td>
										<td><?php esc_html_e( '$59.99', 'easy-store' ); ?></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'Import Demo Data', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-yes"></span></td>
										<td><span class="dashicons dashicons-yes"></span></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'Pre Loaders Layouts', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-no-alt"></span></td>
										<td><span class="dashicons dashicons-yes"></span></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'Header Layout', 'easy-store' ); ?></h3></td>
										<td><?php esc_html_e( '1', 'easy-store' ); ?></td>
										<td><?php esc_html_e( '3', 'easy-store' ); ?></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'Typography Options', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-no-alt"></span></td>
										<td><span class="dashicons dashicons-yes"></span></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'Google Fonts Options', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-no-alt"></span></td>
										<td><?php esc_html_e( '600+', 'easy-store' ); ?></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'Archive Layout', 'easy-store' ); ?></h3></td>
										<td><?php esc_html_e( '1', 'easy-store' ); ?></td>
										<td><?php esc_html_e( '3', 'easy-store' ); ?></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'WooCommerce Compatible', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-yes"></span></td>
										<td><span class="dashicons dashicons-yes"></span></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'YITH Quick View Compatible', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-no-alt"></span></td>
										<td><span class="dashicons dashicons-yes"></span></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'The Events Calendar Plugin Compatible', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-no-alt"></span></td>
										<td><span class="dashicons dashicons-yes"></span></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'Custom 404 Page', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-no-alt"></span></td>
										<td><span class="dashicons dashicons-yes"></span></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'Support', 'easy-store' ); ?></h3></td>
										<td><?php esc_html_e( 'Forum', 'easy-store' ); ?></td>
										<td><?php esc_html_e( 'Forum + Emails/Support Ticket', 'easy-store' ); ?></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'Browser Compatibility', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-yes"></span></td>
										<td><span class="dashicons dashicons-yes"></span></td>
									</tr>
									<tr>
										<td><h3><?php esc_html_e( 'GDPR Compatible', 'easy-store' ); ?></h3></td>
										<td><span class="dashicons dashicons-yes"></span></td>
										<td><span class="dashicons dashicons-yes"></span></td>
									</tr>
									<tr>
										<td><?php esc_html_e( 'Get access to all Pro features and power-up your website', 'easy-store' ); ?></td>
										<td></td>
										<td class="btn-wrapper">
										<a href="<?php echo esc_url( apply_filters( 'easy_store_pro_theme_url', 'https://mysterythemes.com/pricing/?product_id=12736' ) ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Buy Pro', 'easy-store' ); ?></a>
										</td>
									</tr>
								</tbody>
							</table>

						</div><!-- .free-pro-content-wrapper -->

						<?php $this->dashboard_sidebar(); ?>

					</div><!-- .easy-store-container -->
				</div><!-- .dashboard-content-wrapper -->
			</div><!-- #easy-store-dashboard -->
	<?php
		}

		/**
		 * render the plugin screen
		 */
		public function plugin_screen() {

			global $admin_main_class;

			$free_plugins = $this->free_plugins;
	?>
			<div id="easy-store-dashboard">
				<?php $this->dashboard_header(); ?>
				<div class="dashboard-content-wrapper">
					<div class="easy-store-container">

						<div class="plugin-content-wrapper">
							<div class="header-content">
								<h3><?php esc_html_e( 'Recommended Free Plugins', 'easy-store' ); ?></h3>
								<p><?php esc_html_e( 'These Free Plugins might be handy for you.', 'easy-store' ); ?></p>
							</div><!-- .header-content -->
							<div class="plugin-listing">
								<?php
									if ( ! empty( $free_plugins ) ) {
										foreach( $free_plugins as $key => $value ) {

											switch( $value['action'] ) {
												case 'install' :
													$btn_class = 'es-plugin-action button';
													$btn_action = 'install';
													$label = esc_html__( 'Install and Activate', 'easy-store' );
													break;

												case 'inactive' :
													$btn_class = 'es-plugin-action button disabled';
													$btn_action = '';
													$label = esc_html__( 'Deactivate', 'easy-store' );
													break;

												case 'active' :
													$btn_class = 'es-plugin-action button button-primary';
													$btn_action = 'activate';
													$label = esc_html__( 'Activate', 'easy-store' );
													break;
											}
								?>
											<div class="single-plugin-wrap">
												<div class="plugin-thumb-wrap">
													<div class="plugin-thumb">
														<?php
															if ( ! empty( $value['icon_url'] ) ) {
																echo '<img src="'. esc_url( $value['icon_url'] ) .'" />';
															}
														?>
													</div>
													<h4 class="plugin-name"><?php echo esc_html( $value['name'] ); ?></h4>
												</div><!-- .plugin-thumb-wrap -->
												<div class="plugin-content-wrap">
													<div class="plugin-description"><?php echo esc_html( $value['description'] ); ?></div>
													<div class="plugin-meta-wrap">
														<span class="version"><?php printf( esc_html__( 'Version %1$s', 'easy-store' ), $value['version'] ); ?></span>
														<span class="seperator">|</span>
														<span class="author"><?php echo wp_kses_post( $value['author'] ); ?></span>
													</div><!-- .plugin-meta-wrap -->
													<div class="plugin-button-wrap plugin-card-<?php echo esc_attr( $value['slug'] ); ?>">
														<a class="<?php echo esc_attr( $btn_class ); ?>" data-file="<?php echo esc_attr( $value['filename'] ); ?>" data-slug="<?php echo esc_attr( $value['slug'] ); ?>" data-action="<?php echo esc_attr( $btn_action ); ?>" data-redirect="<?php echo esc_url( admin_url( 'themes.php' ).'?page=easy-store-dashboard&tab=plugin' ); ?>" href="#"><?php echo esc_html( $label ); ?></a>
													</div><!-- .plugin-button-wrap -->
												</div><!-- .plugin-content-wrap -->
											</div><!-- .single-plugin-wrap -->
								<?php
										}
									} else {
										esc_html_e( 'Currently, no single plugin was listed.', 'easy-store' );
									}
								?>
							</div><!-- .plugin-listing -->
						</div><!-- .plugin-content-wrapper -->

						<?php $this->dashboard_sidebar(); ?>

					</div><!-- .easy-store-container -->
				</div><!-- .dashboard-content-wrapper -->
			</div><!-- #easy-store-dashboard -->
	<?php
		}

		/**
		 * render the changelog screen
		 */
		public function changelog_screen() {

			global $admin_main_class;

			if ( ! is_child_theme() ) {
                $changelogFilePath = get_template_directory() . '/changelog.txt';
            } else {
                $changelogFilePath = get_stylesheet_directory() . '/changelog.txt';
            }

            $get_changelog = $admin_main_class->get_changelog( $changelogFilePath );
	?>
			<div id="easy-store-dashboard">
				<?php $this->dashboard_header(); ?>
				<div class="dashboard-content-wrapper">
					<div class="changelog-top-wrapper">
						<ul class="easy-store-container">
							<li>
								<span class="new"><?php esc_html_e( 'N', 'easy-store' ); ?></span>
								<?php esc_html_e( 'New', 'easy-store' ); ?>
							</li>
							<li>
								<span class="improvement"><?php esc_html_e( 'I', 'easy-store' ); ?></span>
								<?php esc_html_e( 'Improvement', 'easy-store' ); ?>
							</li>
							<li>
								<span class="fixed"><?php esc_html_e( 'F', 'easy-store' ); ?></span>
								<?php esc_html_e( 'Fixed', 'easy-store' ); ?>
							</li>
							<li>
								<span class="tweak"><?php esc_html_e( 'T', 'easy-store' ); ?></span>
								<?php esc_html_e( 'Tweak', 'easy-store' ); ?>
							</li>
						</ul>
					</div><!-- .changelog-top-wrapper -->
					<div class="easy-store-container">
						<div class="changelog-content-wrapper">
							<?php
								foreach( $get_changelog as $log ) {
							?>
									<section class="changelog-block">
										<div class="block-top">
											<span class="block-version"><?php printf( esc_html__( 'Version: %1$s', 'easy-store' ), $log['version'] ); ?></span>
											<span class="block-date"><?php printf( __( 'Released on: <strong> %1$s </strong>', 'easy-store' ), $log['date'] ); ?></span>
										</div><!-- .block-top -->
										<div class="block-content">
											<ul>
												<?php
													// loop for new 
													if ( ! empty( $log['new'] ) ) {
														foreach( $log['new'] as $note ) {
															echo '<li><span class="new" title="New">N</span>'. esc_html( $note ) .'</li>';
														}
													}
													
													// loop for improvement
													if ( ! empty( $log['imp'] ) ) {
														foreach( $log['imp'] as $note ) {
															echo '<li><span class="improvement" title="Improvement">I</span>'. esc_html( $note ) .'</li>';
														}
													}

													// loop for fixed
													if ( ! empty( $log['fix'] ) ) {
														foreach( $log['fix'] as $note ) {
															echo '<li><span class="fixed" title="Fixed">F</span>'. esc_html( $note ) .'</li>';
														}
													}

													// loop for tweak
													if ( ! empty( $log['tweak'] ) ) {
														foreach( $log['tweak'] as $note ) {
															echo '<li><span class="tweak" title="Tweak">T</span>'. esc_html( $note ) .'</li>';
														}
													}
												?>
											</ul>
										</div><!-- .block-content -->
									</section><!-- .changelog-block -->
							<?php
								}
							?>
						</div><!-- .changelog-content-wrapper -->

						<?php $this->dashboard_sidebar(); ?>

					</div><!-- .easy-store-container -->
				</div><!-- .dashboard-content-wrapper -->
			</div><!-- #easy-store-dashboard -->
	<?php
		}
		
	}
	new Easy_Store_Admin_Dashboard();

endif;