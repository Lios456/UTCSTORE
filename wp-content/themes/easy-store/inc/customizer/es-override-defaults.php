<?php
/**
 * Override default customizer panels, sections, settings or controls.
 *
 * @package Mystery Themes
 * @subpackage Easy Store
 * @since 1.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

$wp_customize->get_section( 'title_tagline' )->panel = 'easy_store_header_settings_panel';
$wp_customize->get_section( 'title_tagline' )->priority = '10';

$wp_customize->get_section( 'header_image' )->priority = '20';
$wp_customize->get_section( 'header_image' )->title    = __( 'Innerpages Header Image', 'easy-store' );
$wp_customize->get_section( 'header_image' )->panel    = 'easy_store_header_settings_panel';

$wp_customize->get_section( 'colors' )->panel    = 'easy_store_general_settings_panel';
$wp_customize->get_section( 'colors' )->priority = '10';

$wp_customize->get_section( 'background_image' )->panel = 'easy_store_general_settings_panel';
$wp_customize->get_section( 'background_image' )->priority = '15';