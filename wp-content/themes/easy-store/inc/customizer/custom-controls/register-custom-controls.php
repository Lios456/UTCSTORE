<?php
/**
 * Define path for required files for Custom Control
 * 
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.2.0
*/

// Load/Register control radio image.
require_once get_template_directory() . '/inc/customizer/custom-controls/radio-image/class-radio-image-control.php';
$wp_customize->register_control_type( 'Easy_Store_Control_Radio_Image' );

// Load/Register control heading.
require_once get_template_directory() . '/inc/customizer/custom-controls/heading/class-heading-control.php';
$wp_customize->register_control_type( 'Easy_Store_Control_Heading' );

// Load/Register control toggle.
require_once get_template_directory() . '/inc/customizer/custom-controls/toggle/class-toggle-control.php';
$wp_customize->register_control_type( 'Easy_Store_Control_Toggle' );

// Load control repeater.
require_once get_template_directory() . '/inc/customizer/custom-controls/repeater/class-repeater-control.php';




// Load/Register control upgrade.
require_once get_template_directory() . '/inc/customizer/custom-controls/upgrade/class-upgrade-control.php';
$wp_customize->register_control_type( 'Easy_Store_Control_Upgrade' );


// Load/Register section upsell.
require_once get_template_directory() . '/inc/customizer/custom-controls/upsell/class-upsell-section.php';
$wp_customize->register_section_type( 'Easy_Store_Customize_Section_Upsell' );