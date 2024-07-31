<?php
/**
 * Easy Store Design Settings panel.
 *
 * This file contains all innerpages design related like archive, page, post layouts and their sidebars
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

add_action( 'customize_register', 'easy_store_design_settings_register' );

function easy_store_design_settings_register( $wp_customize ) {

    /**
     * Add Design Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel( 'easy_store_design_settings_panel',
        array(
            'priority'       => 20,
            'title'          => __( 'Innerpage Settings', 'easy-store' ),
        )
    );

/*------------------------------ InnerPage: Archive Page ---------------------------------------------*/
    /**
     * Archive Settings
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_archive_settings_section',
        array(
            'priority'       => 5,
            'panel'          => 'easy_store_design_settings_panel',
            'title'          => __( 'Archive Settings', 'easy-store' )
        )
    );

    /**
     * Toggle option for archive title prefix.
     *
     * @since 1.2.0
     */
    $wp_customize->add_setting( 'easy_store_archive_title_prefix_option',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_archive_title_prefix_option' ),
            'sanitize_callback' => 'easy_store_toggle_sanitize_checkbox'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Toggle(
        $wp_customize, 'easy_store_archive_title_prefix_option',
            array(
                'priority'      => 10,
                'section'       => 'easy_store_archive_settings_section',
                'settings'      => 'easy_store_archive_title_prefix_option',
                'label'         => __( 'Enable archive page title prefix.', 'easy-store' )
            )
        )
    );

    /**
     * Text field for archive read more
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_archive_read_more',
        array(
            'default'           => easy_store_get_customizer_default( 'easy_store_archive_read_more' ),
            'sanitize_callback' => 'sanitize_text_field',
        )
    );
    $wp_customize->add_control( 'easy_store_archive_read_more',
        array(
            'label'         => __( 'Read More Text', 'easy-store' ),
            'section'       => 'easy_store_archive_settings_section',
            'settings'      => 'easy_store_archive_read_more',
            'type'          => 'text',
            'priority'      => 15
        )
    );

}