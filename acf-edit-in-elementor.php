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


// Add custom controls to the Page Settings inside the Elementor Global Options.
if ( ! function_exists( 'th_add_custom_controls_elem_page_settings_top' ) ) {
    function th_add_custom_controls_elem_page_settings_top(Elementor\Core\DocumentTypes\Page $page) {
        if(isset($page) && $page->get_id() > ""){
			$page->add_control(
				'acf_fields_header',
				[
					'label' => __( 'Custom Fields', 'th-widget-pack' ),
					'type' => \Elementor\Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

            $page->add_control(
                'first_content_header',
                [
                    'label' => __( 'First Content Header', 'edit-acf-in-elementor' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'My Header',
                    'return_value' => 'yes',
                ]
            );
            
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
    update_field('first_content_header', $page_settings['first_content_header'], $post_id); ?>
    <script>
	// Page Settings Panel - onchange save and reload elementor window.
	jQuery( function( $ ) {
    	if (typeof $e != "undefined" ){
            elementor.reloadPreview();
        }
	});
	</script>    
<?php }    
    
// Add post-meta shortcode
function post_meta( $atts, $content ) {
	global $post;
	$page_settings = get_post_meta( $post->ID, '_elementor_page_settings', true );
	$post_meta = print_r($page_settings, true);
    return $post_meta;
}
add_shortcode ('post-meta', 'post_meta');

add_action( 'elementor/editor/before_enqueue_scripts', 'th_enqueue_before_editor' );
function th_enqueue_before_editor() {
    wp_enqueue_script( 'acf-edit-in-elementor', get_stylesheet_directory_uri() . '/assets/js/acf-edit-in-elementor.js', array( 'jquery' ) );
}