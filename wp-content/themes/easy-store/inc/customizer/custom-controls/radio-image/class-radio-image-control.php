<?php
/**
 * Customizer Radio Image Control.
 * 
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Easy_Store_Control_Radio_Image' ) ) :
    
    /**
	 * Radio Image control (modified radio).
     *
     * @since 1.0.0
     */
	class Easy_Store_Control_Radio_Image extends WP_Customize_Control {

        /**
		 * The control type.
		 *
		 * @access public
		 * @var string
         * @since 1.0.0
		 */
		public $type = 'es-radio-image';
        
        /**
         * Refresh the parameters passed to the JavaScript via JSON.
         *
         * @see WP_Customize_Control::to_json()
         * @since 1.0.0
         */
        public function to_json() {
			parent::to_json();
			
            if ( isset( $this->default ) ) {
				$this->json['default'] = $this->default;
			} else {
				$this->json['default'] = $this->setting->default;
			}
			
            $this->json['value']    = $this->value();
			$this->json['link']     = $this->get_link();
            $this->json['id']       = $this->id;
            $this->json['choices']  = $this->choices;
            $this->json['column']   = 3;

            $this->json['inputAttrs'] = '';
			foreach ( $this->input_attrs as $attr => $value ) {
				$this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
                if ( $attr == 'column' ) {
                    $this->json['column'] = $value;
                }
			}
		}

        /**
         * An Underscore (JS) template for this control's content (but not its container).
         *
         * Class variables for this control class are available in the `data` JS object;
         * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
         *
         * @see WP_Customize_Control::print_template()
         *
         * @access protected
         * @since 1.0.0
         */
        protected function content_template() {
    ?>

			<# if ( data.tooltip ) { #>
				<a href="#" class="tooltip hint--left" data-hint="{{ data.tooltip }}"><span class='dashicons dashicons-info'></span></a>
			<# } #>
            <# if ( data.label || data.description ) { #>
                <label class="customizer-text">
                    <# if ( data.label ) { #><span class="customize-control-title">{{{ data.label }}}</span><# } #>
                    <# if ( data.description ) { #><span class="description customize-control-description">{{{ data.description }}}</span><# } #>
                </label>
            <# } #>
            <div id="input_{{ data.id }}" class="image image-col-{{ data.column }}">
                <# for ( key in data.choices ) { #>
                    <# dataAlt = ( _.isObject( data.choices[ key ] ) && ! _.isUndefined( data.choices[ key ].alt ) ) ? data.choices[ key ].alt : '' #>
                    <input {{{ data.inputAttrs }}} class="image-select" type="radio" value="{{ key }}" name="_customize-radio-{{ data.id }}" id="{{ data.id }}{{ key }}" {{{ data.link }}}<# if ( data.value === key ) { #> checked="checked"<# } #> data-alt="{{ dataAlt }}" />
                    <label for="{{ data.id }}{{ key }}" {{{ data.labelStyle }}} class="{{{ data.id + key }}}">
                        <# if ( _.isObject( data.choices[ key ] ) ) { #>
                            <img src="{{ data.choices[ key ].src }}" alt="{{ data.choices[ key ].alt }}">
                            
                        <# } else { #>
                            <img src="{{ data.choices[ key ] }}">
                        <# } #>
                        <span class="radio-label tooltip-text">{{ data.choices[ key ].title }}</span>
                    </label>
                <# } #>
            </div>
	<?php
		}

    }

endif;