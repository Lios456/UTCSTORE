<?php
/**
 * Customizer helper where define functions related to customizer panel, sections and settings.
 * 
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*------------------------ General Panel Choices ------------------------------------*/
    
    if ( ! function_exists( 'easy_store_site_layout_choices' ) ) :

        /**
         * function to return choices of site container layout.
         *
         * @since 1.2.0
         */
        function easy_store_site_layout_choices() {

            $site_layout = apply_filters( 'easy_store_site_layout_choices',
                array(
                    'fullwidth'    => array(
                        'title'     => __( 'Full Width', 'easy-store' ),
                        'src'       => get_template_directory_uri() . '/assets/images/full-width.png'
                    ),
                    'boxed'         => array(
                        'title'     => __( 'Boxed', 'easy-store' ),
                        'src'       => get_template_directory_uri() . '/assets/images/boxed-layout.png'
                    )
                )
            );

            return $site_layout;

        }

    endif;

    if ( ! function_exists( 'easy_store_sidebar_layout_choices' ) ) :

        /**
         * function to return choices for sidebar layouts.
         *
         * @since 1.2.0
         */
        function easy_store_sidebar_layout_choices() {

            $sidebar_layouts = apply_filters( 'easy_store_sidebar_layout_choices',
                array(
                    'left_sidebar'  => array(
                        'title'     => __( 'Left Sidebar', 'easy-store' ),
                        'src'       => get_template_directory_uri() . '/assets/images/left-sidebar.png'
                    ),
                    'right_sidebar'    => array(
                        'title'     => __( 'Right Sidebar', 'easy-store' ),
                        'src'       => get_template_directory_uri() . '/assets/images/right-sidebar.png'
                    ),
                    'no_sidebar'  => array(
                        'title'     => __( 'No Sidebar', 'easy-store' ),
                        'src'       => get_template_directory_uri() . '/assets/images/no-sidebar.png'
                    ),
                    'no_sidebar_center'  => array(
                        'title'     => __( 'No Sidebar Center', 'easy-store' ),
                        'src'       => get_template_directory_uri() . '/assets/images/no-sidebar-center.png'
                    )
                )
            );

            return $sidebar_layouts;

        }

    endif;

/*------------------------ Footer Panel Choices -------------------------------------*/

    if ( ! function_exists( 'easy_store_footer_widget_column_choices' ) ) :

        /**
         * function to return choices of footer widget column layout.
         *
         * @since 1.2.0
         */
        function easy_store_footer_widget_column_choices() {

            $footer_widget = apply_filters( 'easy_store_footer_widget_column_choices',
                array(
                    'column_one'        => array(
                        'title'         => __( 'One Column', 'easy-store' ),
                        'src'           => get_template_directory_uri() . '/assets/images/footer-1.png'
                    ),
                    'columns_two'   => array(
                        'title'         => __( 'Two Columns', 'easy-store' ),
                        'src'           => get_template_directory_uri() . '/assets/images/footer-2.png'
                    ),
                    'columns_three'     => array(
                        'title'         => __( 'Three Columns', 'easy-store' ),
                        'src'           => get_template_directory_uri() . '/assets/images/footer-3.png'
                    ),
                    'columns_four' => array(
                        'title'         => __( 'Four Columns', 'easy-store' ),
                        'src'           => get_template_directory_uri() . '/assets/images/footer-4.png'
                    )
                )
            );

            return $footer_widget;

        }

    endif;

/*---------------------------------- Upgrade Control Choices -----------------------------------*/
    
    if ( ! function_exists( 'easy_store_upgrade_choices' ) ) :

        /**
         * function to return choices for upgrade to pro.
         *
         * @since 1.0.0
         */
        function easy_store_upgrade_choices( $setting_id ) {

            $upgrade_info_lists = array(

                'social_icon'   => array( __( 'Add unlimited social icons field.', 'easy-store' ), __( 'More icons with official color.', 'easy-store' ), __( 'Device visibility', 'easy-store' ) ),

                'header_main'   => array( __( '3+ header layouts.', 'easy-store' ), __( 'Additional features for elements.', 'easy-store' ) ),

                'breadcrumbs'   => array( __( 'Device visibility', 'easy-store' ), __( 'Typography Option', 'easy-store' ), __( 'Color Option', 'easy-store' ) ),

                'footer_widget'   => array( __( 'Different Background Type', 'easy-store' ) ),

            );

            $setting_id = explode( 'easy_store_', $setting_id );
            $setting_id = $setting_id[1];

            return $upgrade_info_lists[$setting_id];

        }

    endif;