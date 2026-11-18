<?php
/**
 * Codigo Yoast SEO plugin setup
 * php version 7.4.1
 *
 * @category   Category
 * @package    Understrap
 * @subpackage Codigo
 * @author     Pablo Rica <pablo@codigo.co.uk>
 * @license    MIT 
 * @version    GIT: @1.0.0@
 * @link       link
 * @since      Codigo 1.0
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;

// Exit if Yoast SEO is not enabled.
if( ! class_exists('WPSEO_Options')) {
   exit;
}

/**
 * Adds the custom category to be selectable as a primary term.
 * 
 * @see https://developer.yoast.com/customization/yoast-seo/filters/primary-term-taxonomies-filter/
 *
 * @param array  $taxonomies     The current taxonomies that can be used as a primary term.
 * @param string $post_type      The current post type.
 * @param array  $all_taxonomies All registered taxonomies.
 *
 * @return mixed The taxonomies that can be used as primary terms.
 */
function add_additional_primary_term_taxonomies( 
    $taxonomies, 
    $post_type, 
    $all_taxonomies 
) {
    if ( $post_type === 'project') {
        $taxonomies['sector']    = get_taxonomy('sector');
        $taxonomies['expertise'] = get_taxonomy('expertise');
    }

    if ( isset( $taxonomies['location'] ) ) {
        unset( $taxonomies['location'] );
    }
    //error_log(print_r($taxonomies,true));
    return $taxonomies;
}

add_filter( 
    'wpseo_primary_term_taxonomies', 
    'add_additional_primary_term_taxonomies', 
    10, 
    3 
);
