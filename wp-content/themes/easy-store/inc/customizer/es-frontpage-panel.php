<?php
/**
 * Easy Store Front Page Settings panel at Theme Customizer
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action( 'customize_register', 'easy_store_additional_settings_register' );

function easy_store_additional_settings_register( $wp_customize ) {
    /**
     * Add Front Page Settings Panel
     *
     * @since 1.0.0
     */
    $wp_customize->add_panel( 'easy_store_front_page_settings_panel',
        array(
            'priority'       => 15,
            'title'          => __( 'Front Page Settings', 'easy-store' ),
        )
    );


/*------------------------------ Front Page: Promo Section ---------------------------------------------*/
    /**
     * Promo section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_promo_section',
        array(
            'priority'       => 15,
            'panel'          => 'easy_store_front_page_settings_panel',
            'title'          => __( 'Promo Section', 'easy-store' )
        )
    );

    /**
     * Repeater field for top header items
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'easy_store_promo_items', 
        array(
            'default'           =>  easy_store_get_customizer_default( 'easy_store_promo_items' ),
            'sanitize_callback' => 'easy_store_sanitize_repeater'
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Repeater(
        $wp_customize, 'easy_store_promo_items',
            array(
                'label'           => __( 'Promo Items', 'easy-store' ),
                'description'     => __( 'All promo items will be display via <strong>ES: Promo Items</strong> widget.', 'easy-store' ),
                'section'         => 'easy_store_promo_section',
                'settings'        => 'easy_store_promo_items',
                'priority'        => 5,
                'easy_store_box_label'       => __( 'Single Promo','easy-store' ),
                'easy_store_box_add_control' => __( 'Add Promo','easy-store' )
            ),
            array(
                'mt_item_icon' => array(
                    'type'        => 'icon',
                    'label'       => __( 'Promo Icon', 'easy-store' ),
                    'description' => __( 'Choose icon for single promo from available lists.', 'easy-store' )
                ),
                'mt_item_title' => array(
                    'type'        => 'text',
                    'label'       => __( 'Promo Title', 'easy-store' ),
                    'description' => __( 'Enter title for single promo.', 'easy-store' )
                ),
                'mt_item_text' => array(
                    'type'        => 'text',
                    'label'       => __( 'Promo Info', 'easy-store' ),
                    'description' => __( 'Enter short info for single promo.', 'easy-store' )
                )
            )
        ) 
    );

/*------------------------------ Front Page: Sponsors Section ---------------------------------------------*/
    /**
     * Sponsors Section
     *
     * @since 1.0.0
     */
    $wp_customize->add_section( 'easy_store_sponsors_section',
        array(
            'title'     => esc_html__( 'Our Sponsors', 'easy-store' ),
            'panel'     => 'easy_store_front_page_settings_panel',
            'priority'  => 20,
        )
    );

    /**
     * Repeater field for sponsors
     *
     * @since 1.0.0
     */
    $wp_customize->add_setting( 'our_sponsors', 
        array(
            'default'           => easy_store_get_customizer_default( 'our_sponsors' ),
            'sanitize_callback' => 'easy_store_sanitize_repeater',
        )
    );
    $wp_customize->add_control( new Easy_Store_Control_Repeater(
        $wp_customize, 'our_sponsors',
            array(
                'label'             => __( 'Our Sponsors', 'easy-store' ),
                'description'       => __( 'All sponsors items will be display via <strong>ES: Sponsors</strong> widget.', 'easy-store' ),
                'section'           => 'easy_store_sponsors_section',
                'settings'          => 'our_sponsors',
                'priority'          => 5,
                'easy_store_box_label'       => __( 'Single Sponsor','easy-store' ),
                'easy_store_box_add_control' => __( 'Add Sponsor','easy-store' ),
            ),
            array(
                'mt_item_upload' => array(
                    'type'        => 'upload',
                    'label'       => __( 'Sponsor Logo', 'easy-store' ),
                    'description' => __( 'Upload logo for sponsor.', 'easy-store' )
                )
            )
        )
    );

}