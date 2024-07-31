<?php
/**
 * Customizer callback functions.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/*---------------------------------- Header Panel Callback -----------------------------------*/

    if ( ! function_exists( 'easy_store_wishlist_active_callback' ) ) :

        /**
         * Check if wishlist option is enabled.
         *
         * @since 1.0.0
         *
         * @param WP_Customize_Control $control WP_Customize_Control instance.
         *
         * @return bool Whether the control is active to the current preview.
         */
        function easy_store_wishlist_active_callback( $control ) {
            if ( $control->manager->get_setting( 'easy_store_header_wishlist_option' )->value() == true ) {
                return true;
            } else {
                return false;
            }
        }
        
    endif;

    if ( ! function_exists( 'easy_store_top_header_option_active_callback' ) ) :

        /**
         * Check if header site information option is enabled.
         *
         * @since 1.0.0
         *
         * @param WP_Customize_Control $control WP_Customize_Control instance.
         *
         * @return bool Whether the control is active to the current preview.
         */
        function easy_store_top_header_option_active_callback( $control ) {
            if ( true == $control->manager->get_setting( 'easy_store_top_header_option' )->value() ) {
                return true;
            } else {
                return false;
            }
        }

    endif;


    if ( ! function_exists( 'easy_store_header_cart_active_callback' ) ) :

        /**
         * Check if header site information option is enabled.
         *
         * @since 1.2.0
         *
         * @param WP_Customize_Control $control WP_Customize_Control instance.
         *
         * @return bool Whether the control is active to the current preview.
         */
        function easy_store_header_cart_active_callback( $control ) {
            if ( true == $control->manager->get_setting( 'easy_store_header_cart_option' )->value() ) {
                return true;
            } else {
                return false;
            }
        }

    endif;

/*---------------------------------- Footer Panel Callback -----------------------------------*/
    
    if ( ! function_exists( 'easy_store_footer_widget_active_callback' ) ) :

        /**
         * Check if footer widget option is enabled.
         *
         * @since 1.2.0
         *
         * @param WP_Customize_Control $control WP_Customize_Control instance.
         *
         * @return bool Whether the control is active to the current preview.
         */
        function easy_store_footer_widget_active_callback( $control ) {
            if ( true == $control->manager->get_setting( 'easy_store_footer_widget_option' )->value() ) {
                return true;
            } else {
                return false;
            }
        }

    endif;