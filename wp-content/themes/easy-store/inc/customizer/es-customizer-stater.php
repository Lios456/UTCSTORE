<?php
/**
 * Includes theme customizer defaults and starter functions.
 * 
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'easy_store_get_customizer_option_value' ) ) :

    /**
     * Get the customizer value `get_theme_mod()`
     * 
     * @since 1.0.0
     */
    function easy_store_get_customizer_option_value( $setting_id ) {

        return get_theme_mod( $setting_id, easy_store_get_customizer_default( $setting_id ) );

    }

endif;

if ( ! function_exists( 'easy_store_get_customizer_default' ) ) :

    /**
     * Returns an array of the desired default theme options
     *
     * @return array
     */
    function easy_store_get_customizer_default( $setting_id ) {

        $default_values = apply_filters( 'easy_store_get_customizer_default',
            array(

            // general
            'easy_store_homepage_content_status'            => true,
            'easy_store_general_sidebar_sticky_option'      => true,
            'easy_store_archive_sidebar'                    => 'right_sidebar',
            'easy_store_global_page_sidebar'                => 'right_sidebar',
            'easy_store_global_post_sidebar'                => 'right_sidebar',
            // color
            'easy_store_primary_theme_color'                => '#27B6D4',
            'easy_store_secondary_theme_color'              => '#DD1F26',
            'easy_store_site_layout'                        => 'fullwidth',
            'easy_store_block_base_widget_editor_option'    => false,
            //breadcrumb
            'easy_store_breadcrumb_option'                  => true,
            // header
            'easy_store_top_header_option'                  => false,
            'easy_store_top_header_items'                   => json_encode( array(
        									                    array(
        									                        'mt_item_icon' => 'fa fa-map-marker',
        									                        'mt_item_text' => '',
        									                    )
        									                )),
            'easy_store_top_header_right_content'           => 'social',
            'easy_store_header_cart_option'                 => true,
            'easy_store_shopping_cart_label'                => __( 'Shopping Item', 'easy-store' ),
            'easy_store_header_wishlist_option'             => true,
            'easy_store_wishlist_text'                      => __( 'Wishlist', 'easy-store' ),
            'easy_store_add_to_cart_text'                   => __( 'Add To Cart', 'easy-store' ),
            'easy_store_wishlist_btn_label'                 => __( 'Add To Wishlist', 'easy-store' ),
            'easy_store_primary_menu_sticky'                => false,
            'easy_store_woo_login_button'                   => true,
            // Innerpage setting
            'easy_store_archive_read_more'                  => __( 'Read More', 'easy-store' ),
            'easy_store_archive_title_prefix_option'        => true,
            // additional setting
            'social_media_icons'                            =>json_encode(array(
                                                                array(
                                                                    'mt_item_social_icon' => 'fa fa-facebook-f',
                                                                    'mt_item_url'         => '',
                                                                )
                                                            )),
            'easy_store_promo_items'                      => json_encode(array(
											                    array(
											                        'mt_item_icon'  => 'fa fa-star-o',
											                        'mt_item_title' => '',
											                        'mt_item_text'  => ''
											                    )
											                )),
            'our_sponsors'                                 =>json_encode(array(
    											                array(
    											                    'mt_item_upload' => '',
    											                )
    											            )),
            
            //footer
            'easy_store_footer_widget_option'               => true,
            'easy_store_footer_widget_column'               => 'columns_three',
            'easy_store_copyright_text'                     =>  __( 'Easy Store', 'easy-store' ),
        )
        );
         return  $default_values[$setting_id];
        }
endif;