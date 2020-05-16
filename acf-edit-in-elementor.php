<?php
/**
 * Plugin Name: ACF Edit in Elementor
 * Version: 1.0.0
 * Plugin URI: https://drumcreative.com/
 * Description: Edit ACF fields from the Elementor Page Settings tab.
 * Author: Joel Newcomer
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

function map_acf_to_el_field_type($acf_field_type) {
	if ($acf_field_type == 'text') {
		return \Elementor\Controls_Manager::TEXT;
	}
	if ($acf_field_type == 'textarea') {	
		return \Elementor\Controls_Manager::TEXTAREA;
	}
}

// Add custom controls to the Page Settings inside the Elementor Global Options.
if ( ! function_exists( 'eaie_add_custom_controls_elem_page_settings_top' ) ) {
    function th_add_custom_controls_elem_page_settings_top(Elementor\Core\DocumentTypes\Page $page) {
        if(isset($page) && $page->get_id() > ""){
	        $fields = get_field_objects($page->get_id());
	        $message = '<pre>';
	        $message .= print_r($fields,true);
	        $message .= '</pre>';
	        ?>
	        <script>
		        // alert("");
		        </script>
	        <?php
		    // ACF Fields Header control
			$page->add_control(
				'acf_fields_header',
				[
					'label' => __( 'Custom Fields', 'th-widget-pack' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);
			
			foreach ($fields as $field) {

            $page->add_control(
                $field['name'],
                [
                    'label' => __( $field['label'], 'edit-acf-in-elementor' ),
                    'type' => map_acf_to_el_field_type($field['type']),
                    'default' => $field['value'],
                    'return_value' => 'yes',
                ]
            );

			    
		    }
            
            $page->add_control(
				'refresh_preview',
				[
					'type' => \Elementor\Controls_Manager::BUTTON,
					'label' => '',
					'text' => 'Refresh Preview',
					'event' => 'RefreshPreview',
				]
			);
            
         }        
    }
}
add_action( 'elementor/element/wp-page/document_settings/after_section_start', 'th_add_custom_controls_elem_page_settings_top',10, 2);


add_action( 'save_post', 'save_page_settings_to_acf' );
// Save Elementor page settings to ACF
function save_page_settings_to_acf($post_id) {
    $page_settings = get_post_meta( $post_id, '_elementor_page_settings', true );
    if ($page_settings) {
	    $fields = get_field_objects($post_id);
	    foreach ($fields as $field) {
		    update_field($field['name'], $page_settings[$field['name']], $post_id);
		}
	}
}    
    
// Add post-meta shortcode
function post_meta( $atts, $content ) {
	global $post;
	$page_settings = get_post_meta( $post->ID, '_elementor_page_settings', true );
	$post_meta = print_r($page_settings, true);
    return $post_meta;
}
add_shortcode ('post-meta', 'post_meta');

add_action( 'elementor/editor/before_enqueue_scripts', 'aeie_enqueue_before_editor' );
function aeie_enqueue_before_editor() {
    wp_enqueue_script( 'acf-edit-in-elementor', plugin_dir_url( __FILE__ ) . '/assets/js/acf-edit-in-elementor.js', array( 'jquery' ) );
}