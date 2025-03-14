<?php
defined('ABSPATH') or die("No script kiddies please!");
/**
* Provide a admin area view for the plugin
*
* This file is used to markup the admin-facing aspects of the plugin.
*
* @link       https://mysterythemes.com/
* @since      1.0.0
*
* @package    Mystery Themes Demo Importer
* @subpackage /admin/partials
*/

if ( !empty( $demodata ) ) {

	if ( is_child_theme() ) {
		$parent_theme = get_template();
		$plugin_name		= isset ( $demodata[$parent_theme]['child_themes'][$selected_demo]['name'] ) ? $demodata[$parent_theme]['child_themes'][$selected_demo]['name']: '';
		$theme_slug 		= isset ( $demodata[$parent_theme]['child_themes'][$selected_demo]['theme_slug'] ) ? $demodata[$parent_theme]['child_themes'][$selected_demo]['theme_slug']: '';
		$xml_file 			= isset ( $demodata[$parent_theme]['child_themes'][$selected_demo]['xml_file'] ) ? $demodata[$parent_theme]['child_themes'][$selected_demo]['xml_file']: '';
		$theme_settings 	= isset ( $demodata[$parent_theme]['child_themes'][$selected_demo]['theme_settings'] )? $demodata[$parent_theme]['child_themes'][$selected_demo]['theme_settings']: '';
		$widgets_file 		= isset ( $demodata[$parent_theme]['child_themes'][$selected_demo]['widgets_file'] ) ? $demodata[$parent_theme]['child_themes'][$selected_demo]['widgets_file']: '';
		$demo_url   		= isset ( $demodata[$parent_theme]['child_themes'][$selected_demo]['preview_url'] ) ? $demodata[$parent_theme]['child_themes'][$selected_demo]['preview_url']: '';
		$required_plugins 	= isset ( $demodata[$parent_theme]['child_themes'][$selected_demo]['plugins_list'] ) ? $demodata[$parent_theme]['child_themes'][$selected_demo]['plugins_list']: '';
		$execution_time 	= isset ( $demodata[$parent_theme]['child_themes'][$selected_demo]['execution_time'] ) ? $demodata[$parent_theme]['child_themes'][$selected_demo]['execution_time']: 'default';
	} else {
		$plugin_name		= isset ( $demodata[$selected_demo]['name'] ) ? $demodata[$selected_demo]['name']: '';
		$theme_slug 		= isset ( $demodata[$selected_demo]['theme_slug'] ) ? $demodata[$selected_demo]['theme_slug']: '';
		$xml_file 			= isset ( $demodata[$selected_demo]['xml_file'] ) ? $demodata[$selected_demo]['xml_file']: '';
		$theme_settings 	= isset ( $demodata[$selected_demo]['theme_settings'] )? $demodata[$selected_demo]['theme_settings']: '';
		$widgets_file 		= isset ( $demodata[$selected_demo]['widgets_file'] ) ? $demodata[$selected_demo]['widgets_file']: '';
		$demo_url   		= isset ( $demodata[$selected_demo]['preview_url'] ) ? $demodata[$selected_demo]['preview_url']: '';
		$required_plugins 	= isset ( $demodata[$selected_demo]['plugins_list'] ) ? $demodata[$selected_demo]['plugins_list']: '';
		$execution_time 	= isset ( $demodata[$selected_demo]['execution_time'] ) ? $demodata[$selected_demo]['execution_time']: 'default';
	}

	$json_plugin_lists 	= json_encode( $required_plugins );

	$current_theme	= wp_get_theme();
	$theme_version 	= $current_theme->get( 'Version' );
?>
	<div class="mtdi-popup-wrapper theme-overlay">
		<div class="theme-overlay">
			<div class="theme-backdrop"></div>
				<div class="theme-wrap1 control-panel-themes wp-clearfix">
					<div class="theme-about1 wp-clearfix">
					<div class="theme-info1">
							<div class="mtdi-leftsidewrap">
								<h2 class="theme-name"><?php echo esc_html( $plugin_name ); ?><span class="theme-version"><?php printf( /* translators: %s: theme version */ esc_html__( 'Version: %1$s', 'mysterythemes-demo-importer' ), esc_html( $theme_version ) ); ?></span></h2>
								<p class="theme-author"><?php printf( /* translators: %1$s: a tag with link, %2$s: a tag end */ wp_kses_post( 'By %1$s Mystery Themes %2$s', 'mysterythemes-demo-importer' ), '<a href="https://mysterythemes.com/" target="_blank">', '</a>' ); ?></p>
								<p class="mtdi-msg"></p>
								<div class="mtdi-plugin-req-wrapper">
								<?php
									$countvalue = array();
									if ( isset( $required_plugins ) && !empty( $required_plugins ) ) :
								?>
									<table class="plugins-list-table widefat striped">
										<thead>
											<tr>
												<th scope="col" class="manage-column required-plugins" colspan="2">
													<?php esc_html_e( 'Required Plugins', 'mysterythemes-demo-importer' ); ?>
													<p class="description">
														<?php esc_html_e( 'The following plugins are required for the theme to look exactly like the demo, so on click Import demo button, all required plugins will automatically installed and activated.', 'mysterythemes-demo-importer' ); ?>
													</p>
												</th>
											</tr>
										</thead>
										<tbody id="the-list">
											<?php
												$totalcount = count ( $required_plugins );
												$plugin_install_action = true;
												$i = 0;
												foreach ( $required_plugins as $key => $value ):
													// Installed but Inactive.
													if ( file_exists( WP_PLUGIN_DIR . '/' . esc_attr( $value['slug'] ) ) && is_plugin_inactive( $value['slug'] ) ) {
														$button_classes = 'button mtdi-activate-now';
														$button_text 	= esc_html__( 'Activate', 'mysterythemes-demo-importer' );
														// Not Installed.
													} elseif ( ! file_exists( WP_PLUGIN_DIR . '/' . esc_attr( $value['slug'] ) ) ) {
														$button_classes = 'button mtdi-install-plugin';
														$button_text 	= '';
														if ( isset( $value['install'] ) &&  $value['install'] === 'locally' ) {
															$button_text = esc_html__( 'Manual install required', 'mysterythemes-demo-importer' );
															/* translators: %s: theme name */
															$button_note  = sprintf( esc_html__( 'Some plugins required manual installation. %1s zip comes with current theme bundle. Please install plugin zip in your site and reload this page for further actions.', 'mysterythemes-demo-importer' ), $value['name'] );
															$plugin_install_action = false;
														} else {
															$button_text  = esc_html__( 'Install & Activate', 'mysterythemes-demo-importer' );
														}
														// Active.
													} else {
														$countvalue[] = $i++;
														$button_classes = 'button button-primary disabled updated-message activated';
														$button_text  = __( 'Activated', 'mysterythemes-demo-importer' );
													}
											?>
													<tr class="mtdi-plist-section mtdi-plist plugin-card-<?php echo esc_attr( $key ); ?>" data-slug="<?php echo esc_attr( $key ); ?>" data-init="<?php echo esc_attr( $value['slug'] ); ?>" data-name="<?php echo esc_attr( $value['name'] ); ?>">
														<td class="plugin-name">
															<a href="<?php echo esc_url( 'https://wordpress.org/plugins/' ).esc_attr( $key ); ?>" target="_blank"><?php echo esc_html( $value['name'] ); ?></a>
														</td>
														<td class="plugin-status">
															<a disabled="disabled" class="<?php echo esc_attr( $button_classes ); ?> instalplugin1 alignright mtdi-<?php echo esc_attr( $key ); ?>" <?php if ( isset( $value['install'] ) ) echo 'data-install="'.esc_attr( $value['install'] ).'"'; ?> data-init="<?php echo esc_attr( $value['slug'] ); ?>" data-slug="<?php echo esc_attr( $key ); ?>" data-name="<?php echo esc_attr( $value['name'] ); ?>"><?php echo esc_html( $button_text ); ?></a>
														</td>
													</tr>
											<?php 
												endforeach;
											?>
										</tbody>
									</table>
								<?php endif;?>
								</div><!-- .mtdi-plugin-req-wrapper -->
							</div><!-- .mtdi-leftsidewrap -->
						<p class="description">
							<em>
								<?php esc_html_e( 'Note: If your website already has content or is already in use, we recommend you to backup your website content before attempting a full site import.', 'mysterythemes-demo-importer' ); ?>
							</em>
						</p>
						<div class="demo-import-actions">
							<?php 
								$latestcount =  count( $countvalue );
								if ( $totalcount != $latestcount ) {
							?>
								<a class="button button-hero button-primary hide-if-no-js <?php if ( $plugin_install_action ) echo 'mtdi-install-setup'; ?>" href="#" data-name="<?php echo esc_attr( $plugin_name ); ?>" data-slug="<?php echo esc_attr( $theme_slug ); ?>" data-plugins="<?php echo esc_attr( $json_plugin_lists ); ?>" <?php if ( ! $plugin_install_action ) echo 'disabled="disabled"'; ?>>
									<?php esc_html_e( 'Install & Activate', 'mysterythemes-demo-importer' ); ?>
								</a>
								<a class="button button-hero button-primary hide-if-no-js mtdi-demo-import-step" href="#" data-name="<?php echo esc_attr( $plugin_name ); ?>" data-slug="<?php echo esc_attr( $theme_slug ); ?>" data-plugins="<?php echo esc_attr( $json_plugin_lists ); ?>" data-execution="<?php echo esc_attr( $execution_time ); ?>" style="display: none;"><?php esc_html_e( 'Import Demo Now', 'mysterythemes-demo-importer' ); ?></a>
							<?php } else { ?>
								<a class="button button-hero button-primary hide-if-no-js mtdi-install-setup" href="#" data-name="<?php echo esc_attr( $plugin_name ); ?>" data-slug="<?php echo esc_attr( $theme_slug ); ?>" data-plugins="<?php echo esc_attr ( $json_plugin_lists ); ?>" style="display: none;"><?php esc_html_e( 'Install & Activate', 'mysterythemes-demo-importer' ); ?></a>
								<a class="button button-hero button-primary hide-if-no-js mtdi-demo-import-step" href="#" data-name="<?php echo esc_attr( $plugin_name ); ?>" data-slug="<?php echo esc_attr( $theme_slug ); ?>" data-plugins="<?php echo esc_attr( $json_plugin_lists ); ?>" data-execution="<?php echo esc_attr( $execution_time ); ?>"><?php esc_html_e( 'Import Demo Now', 'mysterythemes-demo-importer' ); ?></a>
								<a class="button button-hero button-primary mtdi-customize-button hide-if-no-js" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" target="_blank" style="display: none"><?php esc_html_e( 'Customize Site', 'mysterythemes-demo-importer' ); ?></a>
							<?php }

								/**
								 * Leave note for manual plugin installation
								 * 
								 */
								if ( ! $plugin_install_action ) {
									if ( isset( $button_note ) ) {
										echo '<div class="plugin-action-note">';
											echo esc_html( $button_note );
										echo '</div>';
									}
								}
							?>
							<textarea style="display: none;" id="hidden_plugin_lists-<?php echo esc_attr( $theme_slug ); ?>"><?php echo esc_attr( $json_plugin_lists ); ?></textarea>
						</div><!-- .demo-import-actions -->
					</div><!-- .theme-info1 -->
					<div class="theme-screenshots1">
						<div class="screenshot">
							<iframe id="demoiframe" src="<?php echo esc_url( $demo_url ); ?>"></iframe>
							<img id="loadingMessage" src="<?php echo esc_url( MTDI_PLUGIN_URL .'/admin/images/ajax-loader.gif' ); ?>" />
						</div>
					</div><!-- .theme-screenshots1 -->
					<div class="theme-footer wp-full-overlay-footer mtdi-responsive-view">
						<div class="devices-wrapper">
							<div class="devices">
								<button type="button" class="preview-desktop active" aria-pressed="true" data-device="desktop">
									<span class="screen-reader-text"><?php esc_html_e( 'Enter desktop preview mode', 'mysterythemes-demo-importer' ); ?></span>
								</button>
								<button type="button" class="preview-tablet" aria-pressed="false" data-device="tablet">
									<span class="screen-reader-text"><?php esc_html_e( 'Enter tablet preview mode', 'mysterythemes-demo-importer' ); ?></span>
								</button>
								<button type="button" class="preview-mobile" aria-pressed="false" data-device="mobile">
									<span class="screen-reader-text"><?php esc_html_e( 'Enter mobile preview mode', 'mysterythemes-demo-importer' ); ?></span>
								</button>
							</div>
						</div>
					</div><!-- .theme-footer.wp-full-overlay-footer -->
					<a href="#" class="mtdi-demo-popup-close"><span class="dashicons dashicons-no-alt"></span></a>
				</div><!-- .theme-about1 -->
				<div id="demo-import-success-note-wrapper" style="display:none;">
					<span class="mtdi-note-close dashicons dashicons-remove"><?php esc_html_e( 'Close', 'mysterythemes-demo-importer' ); ?></span>
					<div class="demo-import-success-note">
						<div class="mtdi-note-header">
							<span class="demo-name"></span>
							<span class="demo-message"><?php esc_html_e( ' Demo Imported successfully!!', 'mysterythemes-demo-importer' ); ?></span>
						</div>
						<div class="mtdi-note-content">
							<div class="mtdi-note-inner-content mtdi-bold">
								<?php esc_html_e( 'Demo imported successfully in you site', 'mysterythemes-demo-importer' );  ?>
							</div>
							<div class="mtdi-note-inner-content">
								<?php esc_html_e( 'You can customize settings, modify images, text and design to make it yours!', 'mysterythemes-demo-importer' );  ?>
							</div>
							<div class="mtdi-note-inner-content">
								<?php esc_html_e( 'PS: We try our best to use images that are free from legal perspectives. However, we do not take any responsibiity. We strongly advise website owners to replace the images and any copyrighted media before publishing them online.', 'mysterythemes-demo-importer' );  ?>
							</div>
						</div>
						<div class="mtdi-note-footer">
							<div class="link-button-wrap">
								<a class="button button-hero button-primary" href="<?php echo esc_url( home_url('/') ); ?>" target="_blank"><?php esc_html_e( 'Visit Site', 'mysterythemes-demo-importer' ); ?><span class="dashicons dashicons-external"></span></a>
								<a class="button button-hero button-primary" href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize', 'mysterythemes-demo-importer' ); ?><span class="dashicons dashicons-external"></span></a>
							</div>
						</div>
					</div>
				</div><!-- .demo-import-success-note-wrapper -->
			</div><!-- .theme-wrap1 -->
		</div><!-- .theme-overlay -->
	</div><!-- .mtdi-popup-wrapper -->
<?php
}