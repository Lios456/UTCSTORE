<?php
/**
 * Customizer Repeater Control.
 * 
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.0.0
*/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Easy_Store_Control_Repeater' ) ) :

	/**
     * Customize controls for repeater field
     *
     * @since 1.0.0
     */
    class Easy_Store_Control_Repeater extends WP_Customize_Control {
    	
        /**
         * The control type.
         *
         * @access public
         * @var string
         */
        public $type = 'repeater';

        public $easy_store_box_label = '';

        public $easy_store_box_add_control = '';

        /**
         * The fields that each container row will contain.
         *
         * @access public
         * @var array
         */
        public $fields = array();

        /**
         * Repeater drag and drop controller
         *
         * @since  1.0.0
         */
        public function __construct( $manager, $id, $args = array(), $fields = array() ) {
            $this->fields = $fields;
            $this->easy_store_box_label = $args['easy_store_box_label'] ;
            $this->easy_store_box_add_control = $args['easy_store_box_add_control'];
            parent::__construct( $manager, $id, $args );
        }

        public function render_content() {

            $values = json_decode( $this->value() );
            $repeater_id = $this->id;
            $field_count = count( $values );
        ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>

            <?php if ( $this->description ) { ?>
                <span class="description customize-control-description">
                    <?php echo wp_kses_post( $this->description ); ?>
                </span>
            <?php } ?>

            <ul class="es-repeater-field-control-wrap">
                <?php $this->easy_store_get_fields(); ?>
            </ul>

            <input type="hidden" <?php esc_attr( $this->link() ); ?> class="es-repeater-collector" value="<?php echo esc_attr( $this->value() ); ?>" />
            <input type="hidden" name="<?php echo esc_attr( $repeater_id ).'_count'; ?>" class="field-count" value="<?php echo absint( $field_count ); ?>">
            <input type="hidden" name="field_limit" class="field-limit" value="5">
            <button type="button" class="button es-repeater-add-control-field"><?php echo esc_html( $this->easy_store_box_add_control ); ?></button>
    <?php
        }

        private function easy_store_get_fields() {
            $fields = $this->fields;
            $values = json_decode( $this->value() );

            if ( is_array( $values ) ) {
            foreach( $values as $value ) {
        ?>
            <li class="es-repeater-field-control">
            <h3 class="es-repeater-field-title"><?php echo esc_html( $this->easy_store_box_label ); ?></h3>
            
            <div class="es-repeater-fields">
            <?php
                foreach ( $fields as $key => $field ) {
                $class = isset( $field['class'] ) ? $field['class'] : '';
            ?>
                <div class="es-repeater-field es-repeater-type-<?php echo esc_attr( $field['type'] ).' '.$class; ?>">

                <?php 
                    $label = isset( $field['label'] ) ? $field['label'] : '';
                    $description = isset( $field['description'] ) ? $field['description'] : '';
                    if ( $field['type'] != 'checkbox' ) { 
                ?>
                        <span class="customize-control-title"><?php echo esc_html( $label ); ?></span>
                        <span class="description customize-control-description"><?php echo esc_html( $description ); ?></span>
                <?php 
                    }

                    $new_value = isset( $value->$key ) ? $value->$key : '';
                    $default = isset( $field['default'] ) ? $field['default'] : '';

                    switch ( $field['type'] ) {
                        case 'text':
                            echo '<input data-default="'.esc_attr( $default ).'" data-name="'.esc_attr( $key ).'" type="text" value="'.esc_attr( $new_value ).'"/>';
                            break;

                        case 'url':
                            echo '<input data-default="'.esc_attr( $default ).'" data-name="'.esc_attr( $key ).'" type="text" value="'.esc_url( $new_value ).'"/>';
                            break;

                        case 'icon':
                            echo '<div class="es-repeater-selected-icon"><i class="'.esc_attr( $new_value ).'"></i><span><i class="fa fa-angle-down"></i></span></div><ul class="es-repeater-icon-list clearfix">';
                            $easy_store_font_awesome_icon_array = easy_store_font_awesome_icon_array();
                            foreach ( $easy_store_font_awesome_icon_array as $easy_store_font_awesome_icon ) {
                                $icon_class = $new_value == $easy_store_font_awesome_icon ? 'icon-active' : '';
                                echo '<li class='.esc_attr( $icon_class ).'><i class="'.esc_attr( $easy_store_font_awesome_icon ).'"></i></li>';
                            }
                            echo '</ul><input data-default="'.esc_attr( $default ).'" type="hidden" value="'.esc_attr( $new_value ).'" data-name="'.esc_attr( $key ).'"/>';
                            break;

                        case 'social_icon':
                            echo '<div class="es-repeater-selected-icon"><i class="'.esc_attr( $new_value ).'"></i><span><i class="fa fa-angle-down"></i></span></div><ul class="es-repeater-icon-list es-clearfix">';
                            $easy_store_font_awesome_social_icon_array = easy_store_font_awesome_social_icon_array();
                            foreach ( $easy_store_font_awesome_social_icon_array as $easy_store_font_awesome_icon ) {
                                $icon_class = $new_value == $easy_store_font_awesome_icon ? 'icon-active' : '';
                                echo '<li class='.esc_attr( $icon_class ).'><i class="'.esc_attr( $easy_store_font_awesome_icon ).'"></i></li>';
                            }
                            echo '</ul><input data-default="'.esc_attr( $default ).'" type="hidden" value="'.esc_attr( $new_value ).'" data-name="'.esc_attr( $key ).'"/>';
                            break;

                        /**
                         * Upload field
                         */
                        case 'upload':
                            $image_class = "";
                            $upload_btn_label = __( 'Select Image', 'easy-store' );
                            $remove_btn_label = __( 'Remove', 'easy-store' );
                            if ( $new_value ) { 
                                $image_class = ' hidden';
                            }
                            echo '<div class="cv-fields-wrap"><div class="attachment-media-view"><div class="placeholder'. esc_attr( $image_class ).'">';
                            esc_html_e( 'No image selected', 'easy-store' );
                            echo '</div><div class="thumbnail thumbnail-image"><img src="'.esc_url( $new_value ).'" style="max-width:100%;"/></div><div class="actions clearfix"><button type="button" class="button mt-delete-button align-left">'. esc_html( $remove_btn_label ) .'</button><button type="button" class="button mt-upload-button alignright">'. esc_html( $upload_btn_label ) .'</button><input data-default="'.esc_attr( $default ).'" class="upload-id" data-name="'.esc_attr( $key ).'" type="hidden" value="'.esc_attr( $new_value ).'"/></div></div></div>';
                            break;

                        default:
                            break;
                    }
                ?>
                </div>
                <?php
                } ?>

                <div class="es-clearfix es-repeater-footer">
                    <div class="alignright">
                    <a class="es-repeater-field-remove" href="#remove"><?php esc_html_e( 'Delete', 'easy-store' ) ?></a> |
                    <a class="es-repeater-field-close" href="#close"><?php esc_html_e( 'Close', 'easy-store' ) ?></a>
                    </div>
                </div>
            </div>
            </li>
            <?php   
            }
            }
        }
    }

endif;