<?php
/**
 * Easy Store General Settings panel at Theme Customizer
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'customize_register', 'easy_store_general_settings_register' );

function easy_store_general_settings_register( $wp_customize ) {

    /**
     * Add General Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel(
	    'easy_store_general_settings_panel',
	    array(
	        'priority'       => 5,
	        'capability'     => 'edit_theme_options',
	        'theme_supports' => '',
	        'title'          => __( 'General Settings', 'easy-store' ),
	    )
    );

/*------------------------------ General: Site Layout ---------------------------------------------*/
    
    /**
     * Site layout
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_site_layout_section',
        array(
            'priority'  => 5,
            'panel'     => 'easy_store_general_settings_panel',
            'title'     => __( 'Site Layout', 'easy-store' )
        )
    );

    /**
     * Image Radio field for site layout
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_site_layout',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_site_layout'),
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Radio_Image(
        $wp_customize, 'easy_store_site_layout',
            array(
                'label'         => __( 'Select Site Layout', 'easy-store' ),
                'section'       => 'easy_store_site_layout_section',
                'settings'      => 'easy_store_site_layout',
                'priority'      => 5,
                'choices'       => easy_store_site_layout_choices(),
            )
        )
    );
    
    /**
     * Toggle option for block base widget editor.
     *
     * @since 1.1.6
     */
    $wp_customize->add_setting( 'easy_store_block_base_widget_editor_option', 
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_block_base_widget_editor_option' ),
            'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Toggle(
        $wp_customize, 'easy_store_block_base_widget_editor_option',
            array(
                'label'         => __( 'Enable Block Widget Editor', 'easy-store' ),
                'description'   => __( 'Enable/disable Block-based Widgets Editor (since WordPress 5.8)', 'easy-store' ),
                'section'       => 'easy_store_site_layout_section',
                'settings'      => 'easy_store_block_base_widget_editor_option',
                'priority'      => 10,
            )
        )
    );

    /**
     * Upgrade field
     *  
     */ 
    $wp_customize->add_setting( 'easy_store_upgrade_site_layout',
        array(
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Upgrade(
        $wp_customize, 'easy_store_upgrade_site_layout',
            array(
                'label'         => __( 'More Features', 'easy-store' ),
                'description'   => __( 'Upgrade to pro for site layout advanced settings.', 'easy-store' ),
                'section'       => 'easy_store_site_layout_section',
                'settings'      => 'easy_store_upgrade_site_layout',
                'url'           => esc_url( 'https://mysterythemes.com/pricing/?product_id=5943' ),
                'priority'      => 50,
            )
        )
    );

/*------------------------------ General: Colors --------------------------------------------------*/

    /**
     * Color option for primary theme color
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_primary_theme_color',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_primary_theme_color' ),
            'sanitize_callback' => 'sanitize_hex_color',
        )
    ); 
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize, 'easy_store_primary_theme_color',
            array(
                'label'      => __( 'Primary Theme Color', 'easy-store' ),
                'section'    => 'colors',
                'settings'   => 'easy_store_primary_theme_color',
                'priority'   => 5
            )
        )
    );

    /**
     * Color option for secondary theme color
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting(
        'easy_store_secondary_theme_color',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_secondary_theme_color' ),
            'sanitize_callback' => 'sanitize_hex_color',
        )
    ); 
    $wp_customize->add_control( new WP_Customize_Color_Control(
        $wp_customize,
            'easy_store_secondary_theme_color',
            array(
                'label'      => __( 'Secondary Theme Color', 'easy-store' ),
                'section'    => 'colors',
                'settings'   => 'easy_store_secondary_theme_color',
                'priority'   => 5
            )
        )
    );

/*------------------------------ General: Social Icons --------------------------------------------*/

    /**
     * Social Icons Section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_social_icons_section',
        array(
            'title'     => esc_html__( 'Social Icons', 'easy-store' ),
            'panel'     => 'easy_store_general_settings_panel',
            'priority'  => 40,
        )
    );

    /**
     * Repeater field for social media icons
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'social_media_icons', 
        array(
            'default' => easy_store_get_customizer_default( 'social_media_icons' ),
            'sanitize_callback' => 'easy_store_sanitize_repeater'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Repeater(
        $wp_customize, 'social_media_icons', 
            array(
                'label'             => __( 'Social Media Icons', 'easy-store' ),
                'section'           => 'easy_store_social_icons_section',
                'settings'          => 'social_media_icons',
                'priority'          => 5,
                'easy_store_box_label'       => __( 'Social Media Icon','easy-store' ),
                'easy_store_box_add_control' => __( 'Add Icon','easy-store' )
            ),
            array(
                'mt_item_social_icon' => array(
                    'type'        => 'social_icon',
                    'label'       => __( 'Social Media Logo', 'easy-store' ),
                    'description' => __( 'Choose social media icon.', 'easy-store' )
                ),
                'mt_item_url' => array(
                    'type'        => 'url',
                    'label'       => __( 'Social Icon Url', 'easy-store' ),
                    'description' => __( 'Enter social media url.', 'easy-store' )
                )
            )
        ) 
    );

    /**
     * Upgrade field for social icons
     *
     * @since 1.2.0
     */ 
    $wp_customize->add_setting( 'easy_store_upgrade_social_icon',
        array(
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Upgrade(
        $wp_customize, 'easy_store_upgrade_social_icon',
            array(
                'priority'      => 100,
                'section'       => 'easy_store_social_icons_section',
                'settings'      => 'easy_store_upgrade_social_icon',
                'label'         => __( 'More Features with Easy Store Pro', 'easy-store' ),
                'choices'       => easy_store_upgrade_choices( 'easy_store_social_icon' )
            )
        )
    );

/*------------------------------ General: Breadcrumb -----------------------------------------------*/

    /**
     * Breadcrumb Section
     *
     * @since 1.2.0
     */
    $wp_customize->add_section( 'easy_store_breadcrumb_section',
        array(
            'title'     => esc_html__( 'Breadcrumbs', 'easy-store' ),
            'panel'     => 'easy_store_general_settings_panel',
            'priority'  => 45,
        )
    );

    /**
     * Toggle option for block base widget editor.
     *
     * @since 1.2.0
     */
    $wp_customize->add_setting( 'easy_store_breadcrumb_option', 
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_breadcrumb_option' ),
            'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Toggle(
        $wp_customize, 'easy_store_breadcrumb_option',
            array(
                'label'         => __( 'Enable breadcrumb section.', 'easy-store' ),
                'section'       => 'easy_store_breadcrumb_section',
                'settings'      => 'easy_store_breadcrumb_option',
                'priority'      => 10,
            )
        )
    );

    /**
     * Upgrade field for breadcrumbs
     *
     * @since 1.2.0
     */ 
    $wp_customize->add_setting( 'easy_store_upgrade_breadcrumbs',
        array(
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Upgrade(
        $wp_customize, 'easy_store_upgrade_breadcrumbs',
            array(
                'priority'      => 100,
                'section'       => 'easy_store_breadcrumb_section',
                'settings'      => 'easy_store_upgrade_breadcrumbs',
                'label'         => __( 'More Features with Easy Store Pro', 'easy-store' ),
                'choices'       => easy_store_upgrade_choices( 'easy_store_breadcrumbs' )
            )
        )
    );

/*------------------------------ General: Sidebar -------------------------------------------------*/

    /**
     * Sidebar Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_sidebar_section',
        array(
            'priority'       => 50,
            'panel'          => 'easy_store_general_settings_panel',
            'capability'     => 'edit_theme_options',
            'title'          => __( 'Sidebar', 'easy-store' )
        )
    );

    /**
     * toggle option for sticky sidebar
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_general_sidebar_sticky_option',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_general_sidebar_sticky_option' ),
            'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Toggle(
        $wp_customize, 'easy_store_general_sidebar_sticky_option',
            array(
                'label'         => esc_html__( 'Enable Sidebar Sticky', 'easy-store' ),
                'section'       => 'easy_store_sidebar_section',
                'priority'      => 5,
            )
        )
    );

    /**
     * Heading field for Archive / Blog Sidebar Layout
     *
     * @since 1.2.0
     */
    $wp_customize->add_setting( 'easy_store_sidebar_archive_heading',
        array(
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Heading(
        $wp_customize, 'easy_store_sidebar_archive_heading',
            array(
                'priority'      => 10,
                'section'       => 'easy_store_sidebar_section',
                'settings'      => 'easy_store_sidebar_archive_heading',
                'label'         => __( 'Archive/Blog Sidebar Layout', 'easy-store' ),
            )
        )
    );

    /**
     * Radio image field for archive/blog sidebar
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_archive_sidebar',
        array(
            'default'           => easy_store_get_customizer_option_value( 'easy_store_archive_sidebar' ),
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Radio_Image(
        $wp_customize, 'easy_store_archive_sidebar',
            array(
                'priority'      => 15,
                'section'       => 'easy_store_sidebar_section',
                'settings'      => 'easy_store_archive_sidebar',
                'choices'       => easy_store_sidebar_layout_choices(),
            )
        )
    );

    /**
     * Heading field for Posts Sidebar Layout
     *
     * @since 1.2.0
     */
    $wp_customize->add_setting( 'easy_store_sidebar_posts_heading',
        array(
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Heading(
        $wp_customize, 'easy_store_sidebar_posts_heading',
            array(
                'priority'      => 20,
                'section'       => 'easy_store_sidebar_section',
                'settings'      => 'easy_store_sidebar_posts_heading',
                'label'         => __( 'Posts Sidebar Layout', 'easy-store' ),
            )
        )
    );

    /**
     * Radio image field for global posts sidebar
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_global_post_sidebar',
        array(
            'default'           => easy_store_get_customizer_option_value( 'easy_store_global_post_sidebar' ),
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Radio_Image(
        $wp_customize, 'easy_store_global_post_sidebar',
            array(
                'priority'      => 25,
                'section'       => 'easy_store_sidebar_section',
                'settings'      => 'easy_store_global_post_sidebar',
                'choices'       => easy_store_sidebar_layout_choices(),
            )
        )
    );

    /**
     * Heading field for Pages Sidebar Layout
     *
     * @since 1.2.0
     */
    $wp_customize->add_setting( 'easy_store_sidebar_pages_heading',
        array(
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Heading(
        $wp_customize, 'easy_store_sidebar_pages_heading',
            array(
                'priority'      => 30,
                'section'       => 'easy_store_sidebar_section',
                'settings'      => 'easy_store_sidebar_pages_heading',
                'label'         => __( 'Pages Sidebar Layout', 'easy-store' ),
            )
        )
    );

    /**
     * Radio image field for global pages sidebar
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_global_page_sidebar',
        array(
            'default'           => easy_store_get_customizer_option_value( 'easy_store_global_page_sidebar' ),
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Radio_Image(
        $wp_customize, 'easy_store_global_page_sidebar',
            array(
                'priority'      => 35,
                'section'       => 'easy_store_sidebar_section',
                'settings'      => 'easy_store_global_page_sidebar',
                'choices'       => easy_store_sidebar_layout_choices(),
            )
        )
    );

/*------------------------------ General: WooCommerce ---------------------------------------------*/
    
    if ( easy_store_is_woocommerce_activated() ) {

        /**
         * WooCommerce Settings
         *
         * @since 1.0.0
         */
        $wp_customize->add_section( 'easy_store_header_woocommerce_section',
            array(
                'priority'              => 80,
                'panel'                 => 'easy_store_general_settings_panel',
                'capability'            => 'edit_theme_options',
                'title'                 => __( 'WooCommerce', 'easy-store' ),
                'description_hidden'    => true,
                'description'           => __( 'Customize general settings about wooCommerce.', 'easy-store' ),
            )
        );

        /**
         * Text field for cart button lable
         *
         * @since 1.0.0
         */
        $wp_customize->add_setting( 'easy_store_add_to_cart_text',
            array(
                'capability'        => 'edit_theme_options',
                'default'           => easy_store_get_customizer_default( 'easy_store_add_to_cart_text' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );
        $wp_customize->add_control( 'easy_store_add_to_cart_text',
            array(
                'label'         => __( 'Cart button label', 'easy-store' ),
                'section'       => 'easy_store_header_woocommerce_section',
                'settings'      => 'easy_store_add_to_cart_text',
                'type'          => 'text',
                'priority'      => 10,
            )
        );

        /**
         * Text field for wishlist button label
         *
         * @since 1.0.0
         */
        $wp_customize->add_setting( 'easy_store_wishlist_btn_label',
            array(
                'capability'        => 'edit_theme_options',
                'default'           => easy_store_get_customizer_default( 'easy_store_wishlist_btn_label' ),
                'sanitize_callback' => 'sanitize_text_field',
            )
        );
        $wp_customize->add_control( 'easy_store_wishlist_btn_label',
            array(
                'label'         => __( 'Cart button label', 'easy-store' ),
                'section'       => 'easy_store_header_woocommerce_section',
                'settings'      => 'easy_store_wishlist_btn_label',
                'type'          => 'text',
                'priority'      => 10,
            )
        );

    }

}