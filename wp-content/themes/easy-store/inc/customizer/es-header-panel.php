<?php
/**
 * Easy Store Header Settings panel at Theme Customizer
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'customize_register', 'easy_store_header_settings_register' );

function easy_store_header_settings_register( $wp_customize ) {

	/**
     * Add Header Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel( 'easy_store_header_settings_panel',
	    array(
	        'priority'       => 10,
	        'title'          => __( 'Header Settings', 'easy-store' ),
	    )
    );

/*------------------------------ Header: Top Area ---------------------------------------------*/

	/**
     * Top Header section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_top_header_section',
        array(
        	'priority' => 5,
        	'panel'    => 'easy_store_header_settings_panel',
            'title'    => __( 'Top Area', 'easy-store' ),
        )
    );

    /**
     * Toggle option for Top Area
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_top_header_option',
        array(
            'default' 			=> easy_store_get_customizer_default( 'easy_store_top_header_option' ),
            'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Toggle(
        $wp_customize, 'easy_store_top_header_option',
            array(
                'label'     	=> __( 'Enable Top Area', 'easy-store' ),
                'section'   	=> 'easy_store_top_header_section',
                'settings'		=> 'easy_store_top_header_option',
                'priority'  	=> 5,
            )
        )
    );

    /**
     * Repeater field for top header items
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_top_header_items', 
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_top_header_items' ),
            'sanitize_callback' => 'easy_store_sanitize_repeater'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Repeater(
        $wp_customize, 'easy_store_top_header_items',
            array(
                'label'           => __( 'Left Content Settings', 'easy-store' ),
                'section'         => 'easy_store_top_header_section',
                'settings'        => 'easy_store_top_header_items',
                'priority'        => 15,
                'active_callback' => 'easy_store_top_header_option_active_callback',
                'easy_store_box_label'       => __( 'Single Item','easy-store' ),
                'easy_store_box_add_control' => __( 'Add Item','easy-store' )
            ),
            array(
                'mt_item_icon' => array(
                    'type'        => 'icon',
                    'label'       => __( 'Item Icon', 'easy-store' ),
                    'description' => __( 'Choose icon for single item from available lists.', 'easy-store' )
                ),
                'mt_item_text' => array(
                    'type'        => 'text',
                    'label'       => __( 'Item Info', 'easy-store' ),
                    'description' => __( 'Enter short info for single item.', 'easy-store' )
                )
            )
        ) 
    );

    /**
     * Right side content type
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_top_header_right_content',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_top_header_right_content' ),
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( 'easy_store_top_header_right_content',
        array(
            'label'           => __( 'Right Content Settings', 'easy-store' ),
            'section'         => 'easy_store_top_header_section',
            'settings'        => 'easy_store_top_header_right_content',
            'active_callback' => 'easy_store_top_header_option_active_callback',
            'type'            => 'select',
            'priority'        => 15,
            'choices'         => array(
                'social'        => __( 'Social Icon', 'easy-store' ),
                'menu'          => __( 'Menu', 'easy-store' )
            )
        )
    );

    if ( easy_store_is_woocommerce_activated() ) {
        /**
         * Toggle option for login button
         *
         * @since 1.2.0
         */
        $wp_customize->add_setting( 'easy_store_woo_login_button',
            array(
                'capability'        => 'edit_theme_options',
                'default'           => easy_store_get_customizer_default( 'easy_store_woo_login_button' ),
                'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
            )
        );
        $wp_customize->add_control( new Easy_Store_Control_Toggle(
            $wp_customize, 'easy_store_woo_login_button',
                array(
                    'label'             => __( 'Enable Login button', 'easy-store' ),
                    'section'           => 'easy_store_top_header_section',
                    'settings'          => 'easy_store_woo_login_button',
                    'priority'          => 20,
                    'active_callback'   => 'easy_store_top_header_option_active_callback',
                )
            )
        );
    }

/*------------------------------ Header: Main Area --------------------------------------------*/
    /**
     * Main Header section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_main_header_section',
        array(
            'priority'  => 15,
            'panel'     => 'easy_store_header_settings_panel',
            'title'     => __( 'Main Area', 'easy-store' ),
        )
    );

    /**
     * toggle option for primary menu sticky
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_primary_menu_sticky',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_primary_menu_sticky' ),
            'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Toggle(
        $wp_customize, 'easy_store_primary_menu_sticky',
            array(
                'label'         => __( 'Enable Primary Menu Sticky', 'easy-store' ),
                'section'       => 'easy_store_main_header_section',
                'settings'      => 'easy_store_primary_menu_sticky',
                'priority'      => 10,
            )
        )
    );

    if ( easy_store_is_woocommerce_activated() ) {

        /**
         * Toggle option for shopping cart icon
         *
         * @since 1.0.0
         */
        $wp_customize->add_setting( 'easy_store_header_cart_option',
            array(
                'default'           => easy_store_get_customizer_default( 'easy_store_header_cart_option' ),
                'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
            )
        );
        $wp_customize->add_control( new Easy_Store_Control_Toggle(
            $wp_customize, 'easy_store_header_cart_option',
                array(
                    'label'         => __( 'Enable cart icon in header.', 'easy-store' ),
                    'section'       => 'easy_store_main_header_section',
                    'settings'      => 'easy_store_header_cart_option',
                    'priority'      => 15,
                )
            )
        );

        /**
         * Text field for cart text
         *
         * @since 1.0.0
         */
        $wp_customize->add_setting( 'easy_store_shopping_cart_label',
            array(
                'capability'        => 'edit_theme_options',
                'default'           => easy_store_get_customizer_default( 'easy_store_shopping_cart_label' ),
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );
        $wp_customize->add_control( 'easy_store_shopping_cart_label',
            array(
                'label'             => __( 'Cart Box Text', 'easy-store' ),
                'section'           => 'easy_store_main_header_section',
                'settings'          => 'easy_store_shopping_cart_label',
                'type'              => 'text',
                'priority'          => 20,
                'active_callback'   => 'easy_store_header_cart_active_callback'
            )
        );
        $wp_customize->selective_refresh->add_partial( 'easy_store_shopping_cart_label',
            array(
                'selector'        => '#site-header-cart span.cart-title',
                'render_callback' => 'easy_store_customize_partial_shopping_cart_label',
            )
        );
    }

    if ( easy_store_is_woocommerce_activated() && function_exists( 'YITH_WCWL' ) ) {

        /**
         * Toggle option for WishtList icon
         *
         * @since 1.0.0
         */
        $wp_customize->add_setting( 'easy_store_header_wishlist_option',
            array(
                'default'           => easy_store_get_customizer_default( 'easy_store_header_wishlist_option' ),
                'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
            )
        );
        $wp_customize->add_control( new Easy_Store_Control_Toggle(
            $wp_customize, 'easy_store_header_wishlist_option',
                array(
                    'label'         => __( 'Enable wishlist icon in header.', 'easy-store' ),
                    'section'       => 'easy_store_main_header_section',
                    'settings'      => 'easy_store_header_wishlist_option',
                    'priority'      => 30,
                )
            )
        );

        /**
         * Text field for wishlist text
         *
         * @since 1.0.0
         */
        $wp_customize->add_setting( 'easy_store_wishlist_text',
            array(
                'capability'        => 'edit_theme_options',
                'default'           => easy_store_get_customizer_default( 'easy_store_wishlist_text' ),
                'transport'         => 'postMessage',
                'sanitize_callback' => 'sanitize_text_field',
            )
        );
        $wp_customize->add_control( 'easy_store_wishlist_text',
            array(
                'label'             => __( 'WishList text', 'easy-store' ),
                'section'           => 'easy_store_main_header_section',
                'settings'          => 'easy_store_wishlist_text',
                'type'              => 'text',
                'priority'          => 35,
                'active_callback'   => 'easy_store_wishlist_active_callback'
            )
        );
        $wp_customize->selective_refresh->add_partial( 'easy_store_wishlist_text',
            array(
                'selector'        => '.es-main-menu-wrapper span.es-btn-label',
                'render_callback' => 'easy_store_customize_partial_wishlist_text',
            )
        );
        
    }

    /**
     * Upgrade field for header main
     *
     * @since 1.2.0
     */ 
    $wp_customize->add_setting( 'easy_store_upgrade_header_main',
        array(
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Upgrade(
        $wp_customize, 'easy_store_upgrade_header_main',
            array(
                'priority'      => 100,
                'section'       => 'easy_store_main_header_section',
                'settings'      => 'easy_store_upgrade_header_main',
                'label'         => __( 'More Features with Easy Store Pro', 'easy-store' ),
                'choices'       => easy_store_upgrade_choices( 'easy_store_header_main' )
            )
        )
    );

}