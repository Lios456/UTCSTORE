<?php
/**
 * WooCommerce Compatibility File
 *
 * @link https://woocommerce.com/
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

/*------------------------- Woocommerce setup ---------------------------------*/
	/**
	 * WooCommerce setup function.
	 *
	 * @link https://docs.woocommerce.com/document/third-party-custom-theme-compatibility/
	 * @link https://github.com/woocommerce/woocommerce/wiki/Enabling-product-gallery-features-(zoom,-swipe,-lightbox)-in-3.0.0
	 *
	 * @return void
	 */
	function easy_store_woocommerce_setup() {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}

	add_action( 'after_setup_theme', 'easy_store_woocommerce_setup' );

/*------------------------- Woocommerce scripts/styles ------------------------*/
	/**
	 * WooCommerce specific scripts & stylesheets.
	 *
	 * @return void
	 */
	function easy_store_woocommerce_scripts() {
		wp_enqueue_style( 'easy-store-woocommerce-style', get_template_directory_uri() . '/woocommerce.css' );

		$font_path   = WC()->plugin_url() . '/assets/fonts/';
		$inline_font = '@font-face {
				font-family: "star";
				src: url("' . $font_path . 'star.eot");
				src: url("' . $font_path . 'star.eot?#iefix") format("embedded-opentype"),
					url("' . $font_path . 'star.woff") format("woff"),
					url("' . $font_path . 'star.ttf") format("truetype"),
					url("' . $font_path . 'star.svg#star") format("svg");
				font-weight: normal;
				font-style: normal;
			}';

		wp_add_inline_style( 'easy-store-woocommerce-style', $inline_font );
	}
	add_action( 'wp_enqueue_scripts', 'easy_store_woocommerce_scripts' );

/*------------------------- Woocommerce general function ----------------------*/
	/**
	 * Add 'woocommerce-active' class to the body tag.
	 *
	 * @param  array $classes CSS classes applied to the body tag.
	 * @return array $classes modified to include 'woocommerce-active' class.
	 */
	function easy_store_woocommerce_active_body_class( $classes ) {
		$classes[] = 'woocommerce-active';

		return $classes;
	}
	add_filter( 'body_class', 'easy_store_woocommerce_active_body_class' );

/*------------------------- Woocommerce product lists -------------------------*/
	
	if ( ! function_exists( 'easy_store_woocommerce_related_products_args' ) ) :

		/**
		 * Related Products Args.
		 *
		 * @param array $args related products args.
		 * @return array $args related products args.
		 */
		function easy_store_woocommerce_related_products_args( $args ) {
			$defaults = array(
				'posts_per_page' => 3,
				'columns'        => 3,
			);

			$args = wp_parse_args( $defaults, $args );

			return $args;
		}

	endif;
	add_filter( 'woocommerce_output_related_products_args', 'easy_store_woocommerce_related_products_args' );


	if ( ! function_exists( 'easy_store_woocommerce_product_columns_wrapper' ) ) :

		/**
		 * Product columns wrapper.
		 *
		 * @return  void
		 */
		function easy_store_woocommerce_product_columns_wrapper() {
			$columns = get_option( 'woocommerce_catalog_columns', 4 );
			echo '<div class="columns-' . absint( $columns ) . '">';
		}

	endif;
	add_action( 'woocommerce_before_shop_loop', 'easy_store_woocommerce_product_columns_wrapper', 40 );

	if ( ! function_exists( 'easy_store_woocommerce_product_columns_wrapper_close' ) ) :

		/**
		 * Product columns wrapper close.
		 *
		 * @return  void
		 */
		function easy_store_woocommerce_product_columns_wrapper_close() {
			echo '</div>';
		}

	endif;
	add_action( 'woocommerce_after_shop_loop', 'easy_store_woocommerce_product_columns_wrapper_close', 40 );

/*------------------------- Woocommerce general -------------------------------*/
	/**
	 * Remove default WooCommerce wrapper.
	 */
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

	if ( ! function_exists( 'easy_store_woocommerce_wrapper_before' ) ) :

		/**
		 * Before Content.
		 *
		 * Wraps all WooCommerce content in wrappers which match the theme markup.
		 *
		 * @return void
		 */
		function easy_store_woocommerce_wrapper_before() {
			?>
			<div id="primary" class="content-area">
				<main id="main" class="site-main" role="main">
			<?php
		}

	endif;
	add_action( 'woocommerce_before_main_content', 'easy_store_woocommerce_wrapper_before' );

	if ( ! function_exists( 'easy_store_woocommerce_wrapper_after' ) ) :

		/**
		 * After Content.
		 *
		 * Closes the wrapping divs.
		 *
		 * @return void
		 */
		function easy_store_woocommerce_wrapper_after() {
	?>
				</main><!-- #main -->
			</div><!-- #primary -->
	<?php
		}

	endif;

	add_action( 'woocommerce_after_main_content', 'easy_store_woocommerce_wrapper_after' );

	if ( ! function_exists( 'easy_store_woocommerce_get_sidebar' ) ) :

		/**
		 * Managed the shop sidebar in WooCommerce pages.
		 */
		function easy_store_woocommerce_get_sidebar() {
			get_sidebar( 'shop' );
		}
		
	endif;

	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	add_action( 'woocommerce_sidebar', 'easy_store_woocommerce_get_sidebar', 10 );

/*------------------------- Woocommerce buttons -------------------------------*/

	if ( ! function_exists( 'easy_store_woocommerce_cart_link_fragment' ) ) :

		/**
		 * Cart Fragments.
		 *
		 * Ensure cart contents update when products are added to the cart via AJAX.
		 *
		 * @param array $fragments Fragments to refresh via AJAX.
		 * @return array Fragments to refresh via AJAX.
		 */
		function easy_store_woocommerce_cart_link_fragment( $fragments ) {
			ob_start();
			easy_store_woocommerce_cart_link();
			$fragments['a.cart-contents'] = ob_get_clean();

			return $fragments;
		}

	endif;
	add_filter( 'woocommerce_add_to_cart_fragments', 'easy_store_woocommerce_cart_link_fragment' );

	if ( ! function_exists( 'easy_store_woocommerce_cart_link' ) ) :

		/**
		 * Cart Link.
		 *
		 * Displayed a link to the cart including the number of items present and the cart total.
		 *
		 * @return void
		 */
		function easy_store_woocommerce_cart_link() {

			$cart_label = easy_store_get_customizer_option_value( 'easy_store_shopping_cart_label' );
			$cart_title = apply_filters( 'easy_store_cart_link_title', __( 'View your shopping cart', 'easy-store' ) );
	?>
			<a class="cart-contents es-clearfix" href="<?php echo esc_url( wc_get_cart_url() ); ?>" title="<?php echo esc_attr( $cart_title ); ?>">
				<span class="es-cart-meta-wrap">
					<span class="cart-title-wrap">
						<span class="cart-title"><?php echo esc_html( $cart_label ); ?></span>
						<span class="amount"><?php echo wp_kses_data( WC()->cart->get_cart_subtotal() ); ?></span>
						<span class="count"><?php echo wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'easy-store' ), WC()->cart->get_cart_contents_count() ) );?></span>
					</span>
					<span class="cart-icon"><i class="fa fa-shopping-bag"></i></span>
				</span><!-- .es-cart-meta-wrap -->
			</a>
	<?php
		}
	
	endif;

/*------------------------------------------------------------------------------------------------------------*/

	if ( ! function_exists( 'easy_store_woocommerce_header_cart' ) ) :

		/**
		 * Display Header Cart.
		 *
		 * @return void
		 */
		function easy_store_woocommerce_header_cart() {
			$easy_store_header_cart_option = easy_store_get_customizer_option_value( 'easy_store_header_cart_option');
			if ( false == $easy_store_header_cart_option ) {
				return;
			}
			if ( is_cart() ) {
				$class = 'current-menu-item';
			} else {
				$class = '';
			}
		?>
			<ul id="site-header-cart" class="site-header-cart">
				<li class="<?php echo esc_attr( $class ); ?>">
					<?php easy_store_woocommerce_cart_link(); ?>
				</li>
				<li>
					<?php
						$instance = array(
							'title' => 'cart widget',
						);

						the_widget( 'WC_Widget_Cart', $instance );
					?>
				</li>
			</ul>
		<?php
		}

	endif;

/*------------------------------------------------------------------------------------------------------------*/

	if ( ! function_exists( 'easy_store_add_to_cart_text' ) ) :

		/**
		 * function about add to cart label
		 */
		function easy_store_add_to_cart_text() {
	        global $product;
			if ( is_a( $product, 'WC_Product' ) ) {
				$add_to_cart_text = easy_store_get_customizer_option_value( 'easy_store_add_to_cart_text' );
				// echo $add_to_cart_text;
				if ( $product->is_type( 'variable' ) ) {
					return esc_html__( 'Select Options' , 'easy-store' );
				} else {
					$add_to_cart_text = sprintf( esc_html__( '%s', 'easy-store'), $add_to_cart_text );
					return $add_to_cart_text;
				}
			}
		}

	endif;

	add_filter( 'woocommerce_product_add_to_cart_text', 'easy_store_add_to_cart_text', 1, 1 );
	add_filter( 'woocommerce_product_single_add_to_cart_text', 'easy_store_add_to_cart_text', 1, 1 );

/*------------------------------------------------------------------------------------------------------------*/

	if ( ! function_exists( 'easy_store_no_product_found' ) ) :

		/**
		 * Display div structure for no product found
		 *
		 * @since 1.0.0
		 */
		function easy_store_no_product_found() {
	?>
			<div class="es-no-product-found"><?php esc_html_e( 'No products found', 'easy-store' ); ?></div>
	<?php
		}

	endif;

/*------------------------ single product layout ---------------------------*/
/**
 * Managed single product layout
 *
 * @since 1.0.0
 */
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_link_close', 15 );

// start product title wrapper
add_action( 'woocommerce_shop_loop_item_title', 'easy_store_product_title_wrap_open', 5 );
function easy_store_product_title_wrap_open() {
	echo '<div class="es-product-title-wrap">';
}

// end product title wrapper
add_action( 'woocommerce_after_shop_loop_item_title', 'easy_store_product_title_wrap_close', 15 );
function easy_store_product_title_wrap_close() {
	echo '</div><!-- .es-product-title-wrap -->';
}

// start product cart section wrapper
add_action( 'woocommerce_after_shop_loop_item', 'easy_store_product_buttons_wrap_open', 5 );
function easy_store_product_buttons_wrap_open() {
	echo '<div class="es-product-buttons-wrap">';
}

// end product cart section wrapper
add_action( 'woocommerce_after_shop_loop_item', 'easy_store_product_buttons_wrap_close', 30 );
function easy_store_product_buttons_wrap_close() {
	echo '</div><!-- .es-product-buttons-wrap -->';
}

add_action( 'woocommerce_after_shop_loop_item', 'easy_store_wishlist_button', 20 );
function easy_store_wishlist_button() {
	if ( ! function_exists( 'YITH_WCWL' ) ) {
	    return;
	}
	global $product;
	$product_id 		= yit_get_product_id( $product );
	$current_product 	= wc_get_product( $product_id );
	$product_type 		= $current_product->get_type();
?>
	<a href="<?php echo esc_url( add_query_arg( 'add_to_wishlist', intval( $product_id ) ) )?>" rel="nofollow" data-product-id="<?php echo esc_attr( $product_id ); ?>" data-product-type="<?php echo esc_attr( $product_type ); ?>" class="add_to_wishlist" >
		<?php
			$easy_store_wishlist_btn_label = easy_store_get_customizer_option_value( 'easy_store_wishlist_btn_label' );
			echo esc_html( $easy_store_wishlist_btn_label );
		?>
	</a>
<?php
}

/*------------------------------------------------------------------------------------------------------------*/

/**
 * Removed breadcrumb 
 *
 * @since 1.0.0
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

/*------------------------------------------------------------------------------------------------------------*/
/**
 * Add permalink at product title
 */

function woocommerce_template_loop_product_title() {
    echo '<a href="'. esc_url( get_permalink() ) .'"><h2 class="woocommerce-loop-product__title">' . get_the_title() . '</h2> </a>';
}

if ( ! function_exists( 'easy_store_woocommerce_loginout_link' ) ) :

	/**
	 * wooCommerce user loginout link
	 *
	 * Displayed the loginout link in the top header.
	 *
	 * @return void
	 */
	function easy_store_woocommerce_loginout_link() {
		if ( false == easy_store_get_customizer_option_value( 'easy_store_woo_login_button' ) ) {  
			return;
		}
		if ( is_user_logged_in() ) {
			echo '<a class="loginout" href="' . esc_url( wp_logout_url( home_url() ) ) . '">'. __( 'Logout', 'easy-store' ).'</a>';
		} else {
			echo '<a class="loginout" href="' . esc_url( home_url( '/my-account' ) ) . '">'. __( 'Login', 'easy-store' ).'</a>';
		}
	}

endif;