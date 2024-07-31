<?php
/**
 * Customizer Heading Control.
 * 
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.2.0
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Easy_Store_Control_Heading' ) ) :
    
    /**
     * Heading control.
     *
     * @since 1.0.0
     */
    class Easy_Store_Control_Heading extends WP_Customize_Control {

        /**
         * The control type.
         *
         * @access public
         * @var string
         * @since 1.0.0
         */
        public $type = 'es-heading';

        /**
         * An Underscore (JS) template for this control's content (but not its container).
         *
         * Class variables for this control class are available in the `data` JS object;
         * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
         *
         * @see WP_Customize_Control::print_template()
         *
         * @access protected
         */
        protected function content_template() {
    ?>
            <h4 class="mt-customizer-heading">{{{ data.label }}}</h4>
            <# if ( data.description ) { #>
            <div class="description">{{{ data.description }}}</div>
            <# } #>
    <?php
        }

    }

endif;