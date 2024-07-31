<?php
/**
 * File to sanitize customizer field
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
 */

if ( ! function_exists( 'easy_store_toggle_sanitize_checkbox' ) ) :

    /**
     * Sanitize checkbox.
     *
     * @param bool $checked Whether the checkbox is checked.
     * @return bool Whether the checkbox is checked.
     *
     * @since 1.0.0
     */
    function easy_store_toggle_sanitize_checkbox( $checked ) {

        return ( ( isset( $checked ) && true === $checked ) ? true : false );

    }

endif;

if ( ! function_exists( 'easy_store_sanitize_select' ) ) :

    /**
     * Sanitize select.
     *
     * @since 1.0.0
     *
     * @param mixed                $input The value to sanitize.
     * @param WP_Customize_Setting $setting WP_Customize_Setting instance.
     * @return mixed Sanitized value.
     */
    function easy_store_sanitize_select( $input, $setting ) {

        // Ensure input is a slug.
        $input = sanitize_key( $input );

        // Get list of choices from the control associated with the setting.
        $choices = $setting->manager->get_control( $setting->id )->choices;

        // If the input is a valid key, return it; otherwise, return the default.
        return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

    }

endif;

/**
 * Sanitize repeater value
 *
 * @since 1.0.0
 */
function easy_store_sanitize_repeater( $input, $setting ) {
    $input_decoded = json_decode( $input, true );
    $default_decoded = json_decode( $setting->default, true );

    $easy_store_icon_array          = array_flip( easy_store_font_awesome_icon_array() );
    $easy_store_social_icon_array   = array_flip( easy_store_font_awesome_social_icon_array() );
        
    if ( !empty( $input_decoded ) ) {

        foreach ( $input_decoded as $boxes => $box ) {
            foreach ( $box as $key => $value ) {

                if ( $key == 'mt_item_url' || $key == 'mt_item_upload' ) {
                    $input_decoded[$boxes][$key] = esc_url_raw( $value );
                } elseif ( $key == 'mt_item_icon' ) {
                    $default = $default_decoded[ 0 ][ 'mt_item_icon' ];
                    $input_decoded[ $boxes ][ $key ] = array_key_exists( $value, $easy_store_icon_array ) ? $value : $default;
                } elseif ( $key == 'mt_item_social_icon' ) {
                    $default = $default_decoded[ 0 ][ 'mt_item_social_icon' ];
                    $input_decoded[ $boxes ][ $key ] = array_key_exists( $value, $easy_store_social_icon_array ) ? $value : $default;
                } else {
                    $input_decoded[$boxes][$key] = wp_kses_post( $value );
                }
            }
        }
        return json_encode( $input_decoded );
    }
    
    return $input;
}