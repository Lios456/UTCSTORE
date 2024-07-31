<?php
/**
 * Custom hook file contains general hooks and functions.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 *
 */

/*-------------------- General: Sidebar -----------------------------------*/

	if ( ! function_exists( 'easy_store_add_sidebar' ) ) :

		/**
		 * Managed sidebar.
		 *
		 * @since 1.0.0
		 */
		function easy_store_add_sidebar() {

			global $post;

		    if ( 'post' === get_post_type() ) {
		        $sidebar_meta_option = get_post_meta( $post->ID, 'easy_store_sidebar_layout', true );
		    }

		    if ( 'page' === get_post_type() ) {
		        $sidebar_meta_option = get_post_meta( $post->ID, 'easy_store_sidebar_layout', true );
		    }
		     
		    if ( is_home() ) {
		        $set_id = get_option( 'page_for_posts' );
		        $sidebar_meta_option = get_post_meta( $set_id, 'easy_store_sidebar_layout', true );
		    }
		    
		    if ( empty( $sidebar_meta_option ) || is_archive() || is_search() ) {
		        $sidebar_meta_option = 'default_sidebar';
		    }
		    
		    $archive_sidebar      = easy_store_get_customizer_option_value( 'easy_store_archive_sidebar' );
		    $page_default_sidebar = easy_store_get_customizer_option_value( 'easy_store_global_page_sidebar' );
		    $post_default_sidebar = easy_store_get_customizer_option_value( 'easy_store_global_post_sidebar' );
		    
		    if ( $sidebar_meta_option == 'default_sidebar' ) {
		        if ( is_single() ) {
		            if ( $post_default_sidebar == 'right_sidebar' ) {
		                get_sidebar();
		            } elseif ( $post_default_sidebar == 'left_sidebar' ) {
		                get_sidebar( 'left' );
		            }
		        } elseif ( is_page() ) {
		            if ( $page_default_sidebar == 'right_sidebar' ) {
		                get_sidebar();
		            } elseif ( $page_default_sidebar == 'left_sidebar' ) {
		                get_sidebar( 'left' );
		            }
		        } elseif ( $archive_sidebar == 'right_sidebar' ) {
		            get_sidebar();
		        } elseif ( $archive_sidebar == 'left_sidebar' ) {
		            get_sidebar( 'left' );
		        }
		    } elseif ( $sidebar_meta_option == 'right_sidebar' ) {
		        get_sidebar();
		    } elseif ( $sidebar_meta_option == 'left_sidebar' ) {
		        get_sidebar( 'left' );
		    }
		}

	endif;

	add_action( 'easy_store_sidebar', 'easy_store_add_sidebar', 5 );

/*-------------------- Header: Top Area -----------------------------------*/

	if ( ! function_exists( 'easy_store_top_header_start' ) ) :

		/**
		 * Top header start
		 *
		 * @since 1.0.0
		 */
		function easy_store_top_header_start() {
			echo '<div class="es-top-header-wrap es-clearfix">';
			echo '<div class="mt-container">';
		}

	endif;


	if ( ! function_exists( 'easy_store_top_left_section' ) ) :

		/**
		 * Top header left section
		 *
		 * @since 1.0.0
		 */
		function easy_store_top_left_section() {
			echo '<div class="es-top-left-section-wrapper">';
				$get_top_header_items = easy_store_get_customizer_option_value( 'easy_store_top_header_items' );
		        $get_decode_top_header_items = json_decode( $get_top_header_items );
		        if ( ! empty( $get_decode_top_header_items ) ) {
		            echo '<div class="es-items-wrapper">';
		            foreach ( $get_decode_top_header_items as $single_item ) {
		                $item_icon  = $single_item->mt_item_icon;
		                $item_info  = $single_item->mt_item_text;
		        ?>
		                    <div class="item-icon-info-wrap">                       
		                        <span class="item-icon"><i class="<?php echo esc_attr( $item_icon ); ?>"></i></span>                        
		                        <span class="item-info"><?php echo esc_html( $item_info ); ?></span>
		                    </div><!-- .item-icon-info-wrap -->
		        <?php
		            }
		            echo '</div><!-- .es-items-wrapper -->';
		        }
			echo '</div><!-- .es-top-left-section-wrapper -->';
		}

	endif;

	if ( ! function_exists( 'easy_store_top_right_section' ) ) :

		/**
		 * Top header right section
		 *
		 * @since 1.0.0
		 */
		function easy_store_top_right_section() {
			$easy_store_top_right_content = easy_store_get_customizer_option_value( 'easy_store_top_header_right_content' );
	?>
			<div class="es-top-right-section-wrapper">
				<?php
					if ( $easy_store_top_right_content == 'social' ) {
						easy_store_social_media_content();
					} else {
				?>
						<nav id="top-navigation" class="top-navigation" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'easy_store_top_menu', 'fallback_cb' => false, 'menu_id' => 'top-menu' ) );
							?>
						</nav><!-- #site-navigation -->
				<?php
					}

					if ( class_exists( 'WooCommerce' ) ) {
						easy_store_woocommerce_loginout_link();
					}
				?>
			</div><!-- .es-top-right-section-wrapper -->
	<?php
		}

	endif;

	if ( ! function_exists( 'easy_store_top_header_end' ) ) :

		/**
		 * Top header end
		 *
		 * @since 1.0.0
		 */
		function easy_store_top_header_end() {
			echo '</div><!-- .mt-container -->';
			echo '</div><!-- .es-top-header-wrap -->';
		}
		
	endif;

	/**
	 * Managed functions for top header hook
	 *
	 * @since 1.0.0
	 */
	add_action( 'easy_store_top_header', 'easy_store_top_header_start', 5 );
	add_action( 'easy_store_top_header', 'easy_store_top_left_section', 10 );
	add_action( 'easy_store_top_header', 'easy_store_top_right_section', 15 );
	add_action( 'easy_store_top_header', 'easy_store_top_header_end', 20 );

/*-------------------- Header: Main Area ----------------------------------*/

	if ( ! function_exists( 'easy_store_header_start' ) ) :

		/**
		 * Main header start
		 *
		 * @since 1.0.0
		 */
		function easy_store_header_start() {
			echo '<header id="masthead" class="site-header">';
		}

	endif;

	if ( ! function_exists( 'easy_store_header_logo_section_start' ) ) :

		/**
		 * Logo section start
		 *
		 * @since 1.0.0
		 */
		function easy_store_header_logo_section_start() {
			echo '<div class="es-header-logo-wrapper es-clearfix"><div class="mt-container">';
		}

	endif;

	if ( ! function_exists( 'easy_store_site_branding' ) ):

		/**
		 * Site branding
		 *
		 * @since 1.0.0
		 */
		function easy_store_site_branding() {
	?>
			<div class="site-branding">
				<?php
				the_custom_logo();
				if ( is_front_page() || is_home() ) : ?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
				endif;

				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
				<?php
				endif; ?>
			</div><!-- .site-branding -->
	<?php
		}

	endif;

	if ( ! function_exists( 'easy_store_header_area_section_start' ) ) :

		/**
		 * Header area section start
		 *
		 * @since 1.0.0
		 */
		function easy_store_header_area_section_start() {
			echo '<div class="es-header-area-cart-wrapper">';
		}

	endif;

	if ( ! function_exists( 'easy_store_header_area_content' ) ) :

		/**
		 * Header area
		 *
		 * @since 1.0.0
		 */
		function easy_store_header_area_content() {
			if ( is_active_sidebar( 'header_area_sidebar' ) ) :
				dynamic_sidebar( 'header_area_sidebar' );
			endif;
		}

	endif;

	if ( ! function_exists( 'easy_store_header_area_section_end' ) ) :

		/**
		 * Header area section end
		 *
		 * @since 1.0.0
		 */
		function easy_store_header_area_section_end() {
			echo '</div><!-- .es-header-area-wrapper -->';
		}

	endif;

	if ( ! function_exists( 'easy_store_header_logo_section_end' ) ) :

		/**
		 * Logo section end
		 *
		 * @since 1.0.0
		 */
		function easy_store_header_logo_section_end() {
			echo '</div><!-- .mt-container --></div><!-- .es-header-logo-wrapper -->';
		}

	endif;

	if ( ! function_exists( 'easy_store_main_menu_section' ) ) :

		/**
		 * Main menu section
		 *
		 * @since 1.0.0
		 */
		function easy_store_main_menu_section() {
	?>
			<div class="es-main-menu-wrapper">
				<div class="mt-container">
					<div class="es-home-icon">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"> <i class="fa fa-home"> </i> </a>
					</div><!-- .np-home-icon -->
					<div class="mt-header-menu-wrap">
	                	<a href="javascript:void(0)" class="menu-toggle hide"> <i class="fa fa-navicon"> </i> </a>
						<nav id="site-navigation" class="main-navigation" role="navigation">
							<?php wp_nav_menu( array( 'theme_location' => 'easy_store_primary_menu', 'menu_id' => 'primary-menu' ) );
							?>
						</nav><!-- #site-navigation -->
					</div><!-- .mt-header-menu-wrap -->
					
					<?php
						$easy_store_header_wishlist_option = easy_store_get_customizer_option_value( 'easy_store_header_wishlist_option' );
						if ( $easy_store_header_wishlist_option == true ) {
			                if ( function_exists( 'YITH_WCWL' ) && easy_store_is_woocommerce_activated() ) {
			                	$easy_store_wishlist_url = YITH_WCWL()->get_wishlist_url();
			                	$easy_store_wishlist_text = easy_store_get_customizer_option_value( 'easy_store_wishlist_text' );
	            	?>
			            		<div class="es-wishlist-wrap">
				                    <a class="es-wishlist-btn" href="<?php echo esc_url( $easy_store_wishlist_url ); ?>" title="<?php esc_attr_e( 'Wishlist Tab', 'easy-store' ); ?>">
				                    	<i class="fa fa-heart"> </i>
				                    	<span class="es-btn-label"><?php echo esc_html( $easy_store_wishlist_text ); ?></span>
				                    	<span class="es-wl-counter"><?php printf( esc_html( '(%s)', 'easy-store' ), yith_wcwl_count_products() ); ?></span>
				                    </a>
								</div><!-- .es-wishlist-wrap -->
					<?php
							}
						}
					?>
				</div><!-- .mt-container -->
			</div><!-- .es-main-menu-wrapper -->
	<?php
		}

	endif;

	if ( ! function_exists( 'easy_store_header_end' ) ) :

		/**
		 * Main header end
		 *
		 * @since 1.0.0
		 */
		function easy_store_header_end() {
			echo '</header><!-- #masthead -->';
		}
		
	endif;

	/**
	 * Managed functions for header hook
	 *
	 * @since 1.0.0
	 */
	add_action( 'easy_store_header', 'easy_store_header_start', 5 );
	add_action( 'easy_store_header', 'easy_store_header_logo_section_start', 10 );
	add_action( 'easy_store_header', 'easy_store_site_branding', 15 );
	add_action( 'easy_store_header', 'easy_store_header_area_section_start', 20 );
	add_action( 'easy_store_header', 'easy_store_header_area_content', 25 );
	if ( easy_store_is_woocommerce_activated() ) {
		add_action( 'easy_store_header', 'easy_store_woocommerce_header_cart', 30 );
	}
	add_action( 'easy_store_header', 'easy_store_header_area_section_end', 35 );
	add_action( 'easy_store_header', 'easy_store_header_logo_section_end', 40 );
	add_action( 'easy_store_header', 'easy_store_main_menu_section', 45 );
	add_action( 'easy_store_header', 'easy_store_header_end', 50 );

/*-------------------- Header: Inner page Title ---------------------------*/

	if ( ! function_exists( 'easy_store_innerpage_title_content' ) ) :

		/**
		 * Page title for innerpages
		 *
		 * @since 1.0.0
		 */
		function easy_store_innerpage_title_content() {
			if ( !is_front_page() ) {
				$inner_header_attribute = '';
				$inner_header_attribute = apply_filters( 'easy_store_inner_header_style_attribute', $inner_header_attribute );
				if ( !empty( $inner_header_attribute ) ) {
					$header_class = 'has-bg-img';
				} else {
					$header_class = 'no-bg-img';
				}
		?>
				<div class="custom-header <?php echo esc_attr( $header_class ); ?>" <?php echo ( ! empty( $inner_header_attribute ) ) ? ' style="' . esc_attr( $inner_header_attribute ) . '" ' : ''; ?>>
		            <div class="mt-container">
		    			<?php
		    				if ( is_single() || is_page() ) {
		    					the_title( '<h1 class="entry-title">', '</h1>' );
		    				} elseif ( is_home() ) {
		    				   echo '<h1 class="entry-title">'. apply_filters( 'the_title', get_the_title( get_option( 'page_for_posts' ) ) ) .'</h1>';
		    				} elseif ( is_archive() ) {
		    					if ( easy_store_is_woocommerce_activated() && is_shop() && wc_get_page_id( 'shop' ) != -1 ) {
		    				?>
		    						<h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
		    				<?php
		    					} else {
		    						the_archive_title( '<h1 class="page-title">', '</h1>' );
		    						the_archive_description( '<div class="taxonomy-description">', '</div>' );
		    					}
		    				} elseif ( is_search() ) {
		    			?>
		    					<h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'easy-store' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
		    			<?php
		    				} elseif ( is_404() ) {
		    					echo '<h1 class="entry-title">'. esc_html( '404 Error', 'easy-store' ) .'</h1>';
		    				}
		    			?>
		    			<?php easy_store_inner_breadcrumb(); ?>
		            </div><!-- .mt-container -->
				</div><!-- .custom-header -->
		<?php
			}
		}
		
	endif;

	add_action( 'easy_store_page_title', 'easy_store_innerpage_title_content', 5 );

/*-------------------- Front Page: Widget Section -------------------------*/
	
	if ( ! function_exists( 'easy_store_front_page_widget_area' ) ) :

		/**
		 * Managed the homepage widget area
		 *
		 * @since 1.0.0
		 */
		function easy_store_front_page_widget_area() {

			if ( is_front_page() ) {
				echo '<div id="es-front-page-widgets" class="front-page-widgets-area">';
				if ( is_active_sidebar( 'front_page_section_area' ) ) {
					dynamic_sidebar( 'front_page_section_area' );
				}
				else {
					do_action( 'easy_store_default_front_page_section_area' );
				}
				echo '</div><!-- #es-front-page-widgets -->';
			}

		}

	endif;

	add_action( 'easy_store_before_content', 'easy_store_front_page_widget_area', 5 );

/*-------------------- Footer: General ------------------------------------*/

	if ( ! function_exists( 'easy_store_footer_start' ) ) :

		/**
		 * Footer start
		 *
		 * @since 1.0.0
		 */
		function easy_store_footer_start() {
			echo '<footer id="colophon" class="site-footer" role="contentinfo">';
		}

	endif;

	if ( ! function_exists( 'easy_store_footer_end' ) ) :

		/**
		 * Footer end
		 *
		 * @since 1.0.0
		 */
		function easy_store_footer_end() {
			echo '</footer><!-- #colophon -->';
		}

	endif;

	if ( ! function_exists( 'easy_store_go_top' ) ) :

		/**
		 * Go to Top Icon
		 *
		 * @since 1.0.0
		 */
		function easy_store_go_top() {
			echo '<div id="es-scrollup" class="animated arrow-hide"><i class="fa fa-chevron-up"></i></div>';
		}
		
	endif;

	/**
	 * Managed functions for footer general hook
	 *
	 * @since 1.0.0
	 */
	add_action( 'easy_store_footer', 'easy_store_footer_start', 5 );
	add_action( 'easy_store_footer', 'easy_store_footer_end', 35 );
	add_action( 'easy_store_footer', 'easy_store_go_top', 40 );

/*-------------------- Footer: Widget Area --------------------------------*/
	
	if ( ! function_exists( 'easy_store_footer_widget_section' ) ) :

		/**
		 * Footer widget section
		 *
		 * @since 1.0.0
		 */

		function easy_store_footer_widget_section() {
			get_sidebar( 'footer' );
		}

	endif;

	/**
	 * Managed functions for footer widget area
	 *
	 * @since 1.0.0
	 */
	add_action( 'easy_store_footer', 'easy_store_footer_widget_section', 10 );
	
/*-------------------- Footer: Bottom Area --------------------------------*/
	
	if ( ! function_exists( 'easy_store_bottom_footer_start' ) ) :

		/**
		 * Bottom footer start
		 *
		 * @since 1.0.0
		 */
		function easy_store_bottom_footer_start() {
			echo '<div class="bottom-footer es-clearfix">';
			echo '<div class="mt-container">';
		}

	endif;


	if ( ! function_exists( 'easy_store_footer_site_info_section' ) ) :

		/**
		 * Bottom footer side info
		 *
		 * @since 1.0.0
		 */
		function easy_store_footer_site_info_section() {
	?>
			<div class="site-info">
				<span class="es-copyright-text">
					<?php 
						$easy_store_copyright_text = easy_store_get_customizer_option_value( 'easy_store_copyright_text' );
						echo esc_html( $easy_store_copyright_text );
					?>
				</span>
				<span class="sep"> | </span>
				<?php
					$designer_url = 'https://mysterythemes.com';
					/* translators: 1: Theme name, 2: Theme author. */
					printf( esc_html__( 'Theme: %1$s by %2$s.', 'easy-store' ), 'Easy Store', '<a href="'. esc_url( $designer_url ) .'" rel="designer">Mystery Themes</a>' );
				?>
			</div><!-- .site-info -->
	<?php
		}

	endif;

	if ( ! function_exists( 'easy_store_footer_menu_section' ) ) :

		/**
		 * Bottom footer menu
		 *
		 * @since 1.0.0
		 */
		function easy_store_footer_menu_section() {
	?>
			<nav id="footer-navigation" class="footer-navigation" role="navigation">
				<?php wp_nav_menu( array( 'theme_location' => 'easy_store_footer_menu', 'menu_id' => 'footer-menu', 'fallback_cb' => false ) );
				?>
			</nav><!-- #site-navigation -->
	<?php
		}

	endif;

	if ( ! function_exists( 'easy_store_bottom_footer_end' ) ) :

		/**
		 * Bottom footer end
		 *
		 * @since 1.0.0
		 */
		function easy_store_bottom_footer_end() {
			echo '</div><!-- .mt-container -->';
			echo '</div> <!-- bottom-footer -->';
		}

	endif;

	/**
	 * Managed functions for footer bottom area
	 *
	 * @since 1.0.0
	 */
	add_action( 'easy_store_footer', 'easy_store_bottom_footer_start', 15 );
	add_action( 'easy_store_footer', 'easy_store_footer_site_info_section', 20 );
	add_action( 'easy_store_footer', 'easy_store_footer_menu_section', 25 );
	add_action( 'easy_store_footer', 'easy_store_bottom_footer_end', 30 );