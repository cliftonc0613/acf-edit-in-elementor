<?php
/**
 * Plugin Name: ACF Edit in Elementor
 * Version: 1.0.0
 * Plugin URI: https://drumcreative.com/
 * Description: Edit ACF fields from the Elementor Page Settings tab.
 * Author: Hugh Lashbrooke
 * Author URI: https://drumcreative.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: acf-edit-in-elementor
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Joel Newcomer
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin class files.
require_once 'includes/class-acf-edit-in-elementor.php';
require_once 'includes/class-acf-edit-in-elementor-settings.php';

// Load plugin libraries.
require_once 'includes/lib/class-acf-edit-in-elementor-admin-api.php';
require_once 'includes/lib/class-acf-edit-in-elementor-post-type.php';
require_once 'includes/lib/class-acf-edit-in-elementor-taxonomy.php';

/**
 * Returns the main instance of ACF_Edit_in_Elementor to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object ACF_Edit_in_Elementor
 */
function acf_edit_in_elementor() {
	$instance = ACF_Edit_in_Elementor::instance( __FILE__, '1.0.0' );

	if ( is_null( $instance->settings ) ) {
		$instance->settings = ACF_Edit_in_Elementor_Settings::instance( $instance );
	}

	return $instance;
}

acf_edit_in_elementor();
