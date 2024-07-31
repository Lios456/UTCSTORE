<?php
/**
 * Easy Store Footer Settings panel.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'customize_register', 'easy_store_footer_settings_register' );

function easy_store_footer_settings_register( $wp_customize ) {

	/**
     * Add Footer Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel( 'easy_store_footer_settings_panel',
	    array(
	        'priority'       => 25,
	        'title'          => __( 'Footer Settings', 'easy-store' ),
	    )
    );

/*------------------------------ Footer: Widget Area --------------------------------------------*/
   /**
     * Footer Widgets Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_footer_widget_section',
        array(
            'priority'  	 => 5,
            'panel'     	 => 'easy_store_footer_settings_panel',
            'title'     	 => __( 'Footer Widget Area', 'easy-store' )
        )
    );

    /**
     * Toggle option for footer widget area.
     *
     * @since 1.2.0
     */
    $wp_customize->add_setting( 'easy_store_footer_widget_option', 
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_footer_widget_option' ),
            'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Toggle(
        $wp_customize, 'easy_store_footer_widget_option',
            array(
                'label'         => __( 'Enable Footer Widget Area', 'easy-store' ),
                'section'       => 'easy_store_footer_widget_section',
                'settings'      => 'easy_store_footer_widget_option',
                'priority'      => 5,
            )
        )
    );

    /**
     * Image Radio field for footer widget column
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_footer_widget_column',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_footer_widget_column'),
            'sanitize_callback' => 'easy_store_sanitize_select',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Radio_Image(
        $wp_customize, 'easy_store_footer_widget_column',
            array(
                'label'             => __( 'Widget Area Column', 'easy-store' ),
                'description'       => __( 'Choose number of column at footer widget area.', 'easy-store' ),
                'section'           => 'easy_store_footer_widget_section',
                'settings'		    => 'easy_store_footer_widget_column',
                'priority'          => 10,
                'choices'           => easy_store_footer_widget_column_choices(),
                'active_callback'   => 'easy_store_footer_widget_active_callback'
            )
        )
    );

    /**
     * Upgrade field for footer widget
     *
     * @since 1.2.0
     */ 
    $wp_customize->add_setting( 'easy_store_upgrade_footer_widget',
        array(
            'sanitize_callback' => 'sanitize_text_field'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Upgrade(
        $wp_customize, 'easy_store_upgrade_footer_widget',
            array(
                'priority'      => 100,
                'section'       => 'easy_store_footer_widget_section',
                'settings'      => 'easy_store_upgrade_footer_widget',
                'label'         => __( 'More Features with Easy Store Pro', 'easy-store' ),
                'choices'       => easy_store_upgrade_choices( 'easy_store_footer_widget' )
            )
        )
    );


/*------------------------------ Footer: Bottom Area --------------------------------------------*/
    /**
     * Bottom Footer Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_bottom_footer_section',
        array(
            'priority'       => 10,
            'panel'          => 'easy_store_footer_settings_panel',
            'title'          => __( 'Bottom Footer', 'easy-store' )
        )
    );
    
    /**
     * Text field for copyright text
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_copyright_text',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_copyright_text' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control( 'easy_store_copyright_text',
        array(
            'label'         => __( 'Copyright Text', 'easy-store' ),
            'section'       => 'easy_store_bottom_footer_section',
            'settings'      => 'easy_store_copyright_text',
            'type'          => 'text',
            'priority'      => 5
        )
    );
}