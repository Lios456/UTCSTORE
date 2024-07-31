<?php
/**
 * Easy Store Theme Customizer
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function easy_store_customize_register( $wp_customize ) {

    require get_template_directory(). '/inc/customizer/es-override-defaults.php';

    require get_template_directory(). '/inc/customizer/custom-controls/register-custom-controls.php';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'easy_store_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'easy_store_customize_partial_blogdescription',
		) );
	}
    

    /**
     * Register theme upsell sections.
     *
     * @since 1.0.5
     */
    $wp_customize->add_section( new Easy_Store_Customize_Section_Upsell(
        $wp_customize,
            'theme_upsell',
            array(
                'title'    => esc_html__( 'Easy Store Pro', 'easy-store' ),
                'pro_text' => esc_html__( 'Buy Pro', 'easy-store' ),
                'pro_url'  => 'https://mysterythemes.com/wp-themes/easy-store-pro/',
                'priority'  => 1,
            )
        )
    );

    /*-----------------------------------------------------------------------------------------------------------------------*/
    /**
     * Checkbox for show home content
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_homepage_content_status',
        array(
            'capability'        => 'edit_theme_options',
            'default'           => easy_store_get_customizer_default( 'easy_store_homepage_content_status' ),
            'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Toggle(
        $wp_customize, 'easy_store_homepage_content_status',
            array(
                'label'         => __( 'Enable HomePage Content', 'easy-store' ),
                'description'   => __( 'Enable/disable latest posts content in Home page.', 'easy-store' ),
                'section'       => 'static_front_page',
                'settings'      => 'easy_store_homepage_content_status',
                'priority'      => 15
            )
        )
    );

}
add_action( 'customize_register', 'easy_store_customize_register' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function easy_store_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function easy_store_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Render the shoppin cart lablel for the selective refresh partial.
 *
 * @return void
 */
function easy_store_customize_partial_shopping_cart_label() {
    $shopping_cart_label = easy_store_get_customizer_option_value( 'easy_store_shopping_cart_label' );
    return $shopping_cart_label;
}

/**
 * Render the wishlist button label in primary menu for the selective refresh partial.
 *
 * @return void
 */
function easy_store_customize_partial_wishlist_text() {
    $easy_store_wishlist_text = easy_store_get_customizer_option_value( 'easy_store_wishlist_text' );
    return $easy_store_wishlist_text;
}

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function easy_store_customize_preview_js() {
	wp_enqueue_script( 'easy-store-customizer', get_template_directory_uri() . '/assets/js/customizer.js', array( 'customize-preview' ), '20180123', true );
}
add_action( 'customize_preview_init', 'easy_store_customize_preview_js' );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Enqueue required scripts/styles for customizer panel
 *
 * @since 1.0.0
 */
function easy_store_customize_backend_scripts() {

    wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/assets/library/font-awesome/css/font-awesome.min.css', array(), '4.7.0' );
    
    wp_enqueue_style( 'easy_store_admin_customizer_style', get_template_directory_uri() . '/assets/css/es-customizer-style.css', array(), EASY_STORE_VERSION );

    wp_enqueue_script( 'easy_store_admin_customizer', get_template_directory_uri() . '/assets/js/es-customizer-controls.js', array( 'jquery', 'customize-controls' ), EASY_STORE_VERSION, true );
}
add_action( 'customize_controls_enqueue_scripts', 'easy_store_customize_backend_scripts', 10 );

/*----------------------------------------------------------------------------------------------------------------------------------------*/
/**
 * Load customizer required panels.
 */
require get_template_directory() . '/inc/customizer/es-customizer-stater.php';

require get_template_directory() . '/inc/customizer/es-general-panel.php';
require get_template_directory() . '/inc/customizer/es-header-panel.php';
require get_template_directory() . '/inc/customizer/es-innerpage-panel.php';
require get_template_directory() . '/inc/customizer/es-frontpage-panel.php';
require get_template_directory() . '/inc/customizer/es-footer-panel.php';

require get_template_directory() . '/inc/customizer/es-customizer-sanitize.php';
require get_template_directory() . '/inc/customizer/es-customizer-helper.php';
require get_template_directory() . '/inc/customizer/es-customizer-callback.php';