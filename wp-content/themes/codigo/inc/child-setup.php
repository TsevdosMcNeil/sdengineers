<?php
/**
 * Basic setup for child template
 * 
 * Adds the needed template hooks.
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
defined( 'ABSPATH' ) || exit;



/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 
        'child-understrap-styles', 
        get_stylesheet_directory_uri() . $theme_styles, 
        array(), 
        $the_theme->get( 'Version' ) 
    );

	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 
        'child-understrap-scripts', 
        get_stylesheet_directory_uri() . $theme_scripts, array(), 
        $the_theme->get( 'Version' ), 
        true 
    );
    
	if ( is_singular() 
        && comments_open() 
        && get_option( 'thread_comments' ) 
    ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );


/**
 * Add style to admin area
 * 
 * @see https://wordpress.stackexchange.com/questions/110895/adding-custom-stylesheet-to-wp-admin
 * @see https://decodecms.com/agregar-css-personalizado-al-area-de-administracion-de-wordpress/
 * 
 * Note: custom-editor-style.min.css contains the custom TinyMCE editor stylesheets, 
 * only works for editor areas, and it's being loaded by the function 
 * add_editor_style() in the parent theme (/understrap/inc/editor.php)
 * @see https://developer.wordpress.org/reference/functions/add_editor_style/
 *
 * @return void
 */
function admin_style() {

    // Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$admin_styles  = "/css/custom-admin-style{$suffix}.css";

    wp_enqueue_style( 
        'admin-styles', 
        get_stylesheet_directory_uri() . $admin_styles, 
        array(), 
        $the_theme->get( 'Version' ) 
    );
}
add_action('admin_enqueue_scripts', 'admin_style');



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @param string $current_mod The current value of the theme_mod.
 * @return string
 */
function understrap_default_bootstrap_version( $current_mod ) {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );


/**
 * Load theme's JavaScript and CSS sources.
 */
if ( ! function_exists( 'codigo_enqueue_scripts' ) ) {
	function codigo_enqueue_scripts() {
        if(defined('DEV_VERSION')) {
            define('VUE_STATUS', 'development');
            wp_enqueue_script( 'vue',  get_stylesheet_directory_uri().'/vue/js/vue.js', array(), '3.2.41', false);
        } else {
            define('VUE_STATUS', 'production');
            wp_enqueue_script( 'vue',  get_stylesheet_directory_uri().'/vue/js/vue.global.prod.min.js', array(), '3.2.41', false);
        }

        
        //Lottie
        wp_enqueue_script( 
            'lottie-player',  
            'https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js', 
            array(), 
            '', 
            false
        );
        wp_enqueue_script( 
            'lottie-interactivity',  
            'https://unpkg.com/@lottiefiles/lottie-interactivity@latest/dist/lottie-interactivity.min.js', 
            array('lottie-player'), 
            '', 
            false
        );

        if(is_front_page()) {    
            //Lenis
            wp_enqueue_script( 
                'lenis',  
                'https://cdn.jsdelivr.net/gh/studio-freight/lenis@0.2.28/bundled/lenis.js', 
                array('lottie-player'), 
                '', 
                false
            );
        }
	}
}

add_action( 'wp_enqueue_scripts', 'codigo_enqueue_scripts' );


/**
 * Remove and update menu items from administration sidebar
 *
 * @return void
 */
function update_dashboard_menus(){
    global $menu;
    $remove_menu_items = array(__('Comments'));
    $rename_menu_items = array(
        __('Posts') => __('Journal'));
    end($menu);
    while (prev($menu)){
        //error_log(print_r($menu[key($menu)],true));
        $item_title = explode(' ',$menu[key($menu)][0])[0];
        if($item_title != null) {
            if(in_array($item_title, $remove_menu_items) ) {
                //error_log('remove '.$item_title);
                unset($menu[key($menu)]);
            }
            if(array_key_exists($item_title, $rename_menu_items) ) {
                $menu[key($menu)][0] = $rename_menu_items[$item_title];            }
        }
    }
}
add_action( 'admin_menu', 'update_dashboard_menus' );


 /**
  * Remove default words from archive titles like "Category:", "Tag:", "Archives:"
  * https://wordpress.stackexchange.com/questions/179585/remove-category-tag-author-from-the-archive-title
  *
  * @param string $title
  * @return string
  */
  function codigo_remove_default_archive_words($title) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } 
    if ( is_tag() ) {
        $title = single_tag_title( '', false );
    } 
    if (is_tax()) { //for custom post types
        $title = sprintf(__('%1$s'), single_term_title('', false));
    }
    if (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    }
    if ( is_author() ) {
        $title =  get_the_author();
    }
    return $title;
}
add_filter( 'get_the_archive_title', 'codigo_remove_default_archive_words');


// Remove tags support from posts

/**
 * Remove tags support from posts (but not for custom post types)
 *
 * @return void
 */
function cdg_unregister_tags() {
    unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action('init', 'cdg_unregister_tags');


/**
 * This function modifies the main WordPress archive query for categories
 * and tags to include an array of post types instead of the default 'post' post type.
 *
 * @param object $query The main WordPress query.
 */
function tg_include_custom_post_types_in_archive_pages( $query ) {
    if ( $query->is_main_query() 
        && ! is_admin() 
        && ( is_tag() 
            && empty( 
                $query->query_vars['suppress_filters'] 
            ) 
        ) 
    ) {
        $query->set( 'post_type', 
            array( 'post', 
                'project' 
            ) 
        );
    }
}
add_action( 'pre_get_posts', 'tg_include_custom_post_types_in_archive_pages' );


/**
 * Add page slug to body class
 *
 * @param array $classes
 * @return array
 */
function add_slug_body_class( $classes ) {
    global $post;
    if ( isset( $post ) ) {
        $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

