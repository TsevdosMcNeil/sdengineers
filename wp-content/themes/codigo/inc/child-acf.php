<?php
/**
 * Understrap Child Theme Advanced Custom Fields plugin setup
 * php version 7.4.1
 *
 * @category   Category
 * @package    Understrap
 * @subpackage UnderstrapChild
 * @author     Pablo Rica <pablo@codigo.co.uk>
 * @license    MIT 
 * @version    GIT: @1.0.0@
 * @link       link
 * @since      UnderstrapChild 1.0
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;

// Exit if ACF is not enabled.
if( ! function_exists('get_field') ) {
    exit;
}

/**
 * Add new template options to admin menu
 *
 * @return void
 */
if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Theme General Settings',
		'menu_title'	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-general-settings',
		'capability'	=> 'edit_posts',
	));

	acf_add_options_sub_page(array(
	 	'page_title' 	=> 'Theme Settings: General',
	 	'menu_title'	=> 'General',
	 	'parent_slug'	=> 'theme-general-settings',
	));

    acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Settings: Splash',
		'menu_title'	=> 'Splash',
		'parent_slug'	=> 'theme-general-settings',
	));

    acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Settings: Footer',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
	));

    acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Settings: Contact',
		'menu_title'	=> 'Contact',
		'parent_slug'	=> 'theme-general-settings',
	));

    acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Settings: 404',
		'menu_title'	=> '404',
		'parent_slug'	=> 'theme-general-settings',
	));
    
	/*

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Theme Settings: Footer',
		'menu_title'	=> 'Footer',
		'parent_slug'	=> 'theme-general-settings',
		
	));
	
	
    */
}

/**
 * Get API key for Google Maps
 *
 * @return void
 */
function my_acf_init() {
	
	$google_api_key = get_field('general_google_api_key','option');
	
	acf_update_setting('google_api_key', $google_api_key);
}
//add_action('acf/init', 'my_acf_init');


/**
 * Add new Gutenberg Blocks
 *
 * @return void
 */
function register_acf_block_types() {

	$sidebar_block = array(
        'name'              => 'sidebar-block',
        'title'             => __('Sidebar Block'),
        'description'       => __('Block to be displayed om the left sidebar'),
        'render_callback'   => 'my_acf_block_render_callback',
        //'render_template'   => get_stylesheet_directory()  . '/template-parts/block/content-testimonial.php',
        'category'          => 'codigo-blocks',
        'icon'              => 'pressthis',
        'mode'              => 'edit', //preview, edit
        'keywords'          => array( 'image', 'title', 'hover', 'link', 'sidebar' ),
    );
    acf_register_block_type($sidebar_block);


    $project_features_block = array(
        'name'              => 'project-features-block',
        'title'             => __('Project Features'),
        'description'       => __('It displays the project features: location, sector, expertise, client...'),
        'render_callback'   => 'my_acf_block_render_callback',
        //'render_template'   => get_stylesheet_directory()  . '/template-parts/block/content-testimonial.php',
        'category'          => 'codigo-blocks',
        'icon'              => 'list-view',
        'mode'              => 'preview',
        'keywords'          => array( 'project', 'taxonomies', 'features', 'custom fields' ),
    );
    acf_register_block_type($project_features_block);


    $lenis_element_block = array(
        'name'              => 'lenis-element-block',
        'title'             => __('Lenis Element'),
        'description'       => __('It renders the animation of a homepage video element'),
        'render_callback'   => 'my_acf_block_render_callback',
        //'render_template'   => get_stylesheet_directory()  . '/template-parts/block/content-testimonial.php',
        'category'          => 'codigo-blocks',
        'icon'              => 'code-standards',
        'mode'              => 'edit',
        'keywords'          => array( 'home', 'animation', 'lottie', 'lenis' ),
    );
    acf_register_block_type($lenis_element_block);

    $download_pdf_block = array(
        'name'              => 'download-pdf-block',
        'title'             => __('Download PDF button'),
        'description'       => __('Link to the auto-generated PDF of this element'),
        'render_callback'   => 'my_acf_block_render_callback',
        //'render_template'   => get_stylesheet_directory()  . '/template-parts/block/content-testimonial.php',
        'category'          => 'codigo-blocks',
        'icon'              => 'download',
        'mode'              => 'edit',
        'keywords'          => array( 'PDF', 'auto-generated', 'link', 'shortcode', 'button' ),
    );
    acf_register_block_type($download_pdf_block);

}

// Check if function exists and hook into setup.
if( function_exists('acf_register_block_type') ) {
    add_action('acf/init', 'register_acf_block_types');
}

function my_acf_block_render_callback( $block ) {
    
    // convert name ("acf/carousel") into path friendly slug ("carousel")
    $slug = str_replace('acf/', '', $block['name']);
    
    // include a template part from within the "template-parts/block" folder
    if( file_exists( get_theme_file_path("/acf-blocks/content-{$slug}.php") ) ) {
        include( get_theme_file_path("/acf-blocks/content-{$slug}.php") );
    }
}

