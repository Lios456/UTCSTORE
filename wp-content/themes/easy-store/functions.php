<?php
/**
 * Easy Store functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

/*--------------------- Theme Support ----------------------------*/

	if ( ! function_exists( 'easy_store_setup' ) ) :

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		function easy_store_setup() {
			/*
			 * Make theme available for translation.
			 * Translations can be filed in the /languages/ directory.
			 * If you're building a theme based on Easy Store, use a find and replace
			 * to change 'easy-store' to the name of your theme in all the template files.
			 */
			load_theme_textdomain( 'easy-store', get_template_directory() . '/languages' );

			// Add default posts and comments RSS feed links to head.
			add_theme_support( 'automatic-feed-links' );

			/*
			 * Let WordPress manage the document title.
			 * By adding theme support, we declare that this theme does not use a
			 * hard-coded <title> tag in the document head, and expect WordPress to
			 * provide it for us.
			 */
			add_theme_support( 'title-tag' );

			/**
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
			 */
			add_theme_support( 'post-thumbnails' );

			/**
			 * Define various image size
			 *
			 * @link https://developer.wordpress.org/reference/functions/add_image_size/
			 */
			add_image_size( 'easy-store-slider', 840, 500 );
			add_image_size( 'easy-store-blog-thumb', 600, 300 );


			
			// This theme uses wp_nav_menu() in one location.
			register_nav_menus( array(
				'easy_store_top_menu' 	  => esc_html__( 'Top Menu', 'easy-store' ),
				'easy_store_primary_menu' => esc_html__( 'Primary Menu', 'easy-store' ),
				'easy_store_footer_menu'  => esc_html__( 'Footer Menu', 'easy-store' )
			) );

			/*
			 * Switch default core markup for search form, comment form, and comments
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			) );

			// Set up the WordPress core custom background feature.
			add_theme_support( 'custom-background', apply_filters( 'easy_store_custom_background_args', array(
				'default-color' => 'ffffff',
				'default-image' => '',
			) ) );

			// Add theme support for selective refresh for widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );

			/**
			 * Add support for core custom logo.
			 *
			 * @link https://codex.wordpress.org/Theme_Logo
			 */
			add_theme_support( 'custom-logo', array(
				'height'      => 320,
				'width'       => 75,
				'flex-width'  => true,
				'flex-height' => true,
			) );
	        
	        /**
	         * Restoring the classic Widgets Editor
	         * 
	         * @since 1.1.6
	         */
	        $easy_store_block_base_widget_editor_option = easy_store_get_customizer_option_value( 'easy_store_block_base_widget_editor_option' );
	        if ( true !== $easy_store_block_base_widget_editor_option ) {
	            remove_theme_support( 'widgets-block-editor' );
	        }
		}

	endif;

	add_action( 'after_setup_theme', 'easy_store_setup' );

/*--------------------- Content Width ----------------------------*/
	
	if ( ! function_exists( 'easy_store_content_width' ) ) :

		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 *
		 * Priority 0 to make it available to lower priority callbacks.
		 *
		 * @global int $content_width
		 */
		function easy_store_content_width() {
			$GLOBALS['content_width'] = apply_filters( 'easy_store_content_width', 640 );
		}

	endif;

	add_action( 'after_setup_theme', 'easy_store_content_width', 0 );

/*--------------------- Define theme version ---------------------*/

	if ( ! defined( 'EASY_STORE_VERSION' ) ) {
		// Replace the version number of the theme on each release.
		$easy_store_theme_info = wp_get_theme();
		define( 'EASY_STORE_VERSION', $easy_store_theme_info->get( 'Version' ) );
	}

/*--------------------- WooCommerce Activated --------------------*/

	if ( ! function_exists( 'easy_store_is_woocommerce_activated' ) ) :

	    /**
	     * Query WooCommerce activation
	     *
	     * @return boolean
	     */

	    function easy_store_is_woocommerce_activated() {
	        if ( class_exists( 'WooCommerce' ) ) {
	          return true;
	        } else {
	          return false;
	        }
	    }

	endif;

/*--------------------- Require core files -----------------------*/
	/**
	 * Implement the Custom Header feature.
	 */
	require get_template_directory() . '/inc/custom-header.php';

	/**
	 * Custom template tags for this theme.
	 */
	require get_template_directory() . '/inc/template-tags.php';

	/**
	 * Functions which enhance the theme by hooking into WordPress.
	 */
	require get_template_directory() . '/inc/template-functions.php';

	/**
	 * Customizer additions.
	 */
	require get_template_directory() . '/inc/customizer/customizer.php';

	/**
	 * Load Jetpack compatibility file.
	 */
	if ( defined( 'JETPACK__VERSION' ) ) {
		require get_template_directory() . '/inc/jetpack.php';
	}

	/**
	 * Load WooCommerce compatibility file.
	 */
	if ( class_exists( 'WooCommerce' ) ) {
		require get_template_directory() . '/inc/woocommerce.php';
	}

	/**
	 * Custom files for hook
	 */
	require get_template_directory() . '/inc/es-custom-hooks.php';

	/**
	 * Load sidebar meta file
	 */
	require get_template_directory() . '/inc/es-post-sidebar-meta.php';

	/**
	 * Load widget functions file
	 */
	require get_template_directory() . '/inc/widgets/es-widget-functions.php';

	/**
	 * Load theme's dynamic styles
	 */
	require get_template_directory() . '/inc/es-dynamic-styles.php';

	// Admin screen.
	if ( is_admin() ) {
		require get_template_directory() . '/inc/admin/es-class-admin.php';
		require get_template_directory() . '/inc/admin/es-class-notice.php';
		require get_template_directory() . '/inc/admin/es-class-dashboard.php';
	}