<?php
/**
 * Understrap Child Theme functions and definitions
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

// Array of files to include.
$child_includes = array(
    '/child-setup.php',
    '/child-extras.php',
    '/child-template-tags.php',
    '/child-custom-post-types.php',
    '/child-shortcodes.php',
    '/child-rest-api.php',
    '/child-blocks.php'
);

if( function_exists('get_field') ) {
    $child_includes[] = '/child-acf.php';
}

if( class_exists('WPSEO_Options')) {
    $child_includes[] = '/child-yoast-seo.php';
}

if( class_exists('Default_Admin_Color_Scheme')) {
    $child_includes[] = '/child-default-admin-color-scheme.php';
 }


// Include files.
foreach ( $child_includes  as $file ) {
	require_once get_theme_file_path( 'inc' . $file );
}
