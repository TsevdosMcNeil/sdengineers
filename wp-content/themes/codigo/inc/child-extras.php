<?php

/**
 * Custom functions that act independently of the theme templates
 * 
 * Eventually, some of the functionality here could be replaced by core features.
 * php version 7.4.1
 *
 * @category   Category
 * @package    Understrap
 * @subpackage Codigo
 * @author     Pablo Rica <pablo@codigo.co.uk>
 * @license    MIT 
 * @version    GIT: @1.0.0@
 * @link       link
 * @since      UnderstrapChild 1.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;




/**
 * Remove hook understrap_body_classes
 * defined in wp-content/themes/understrap/inc/extras.php
 *
 * @param array $classes
 * 
 * @return array
 */
function remove_understrap_body_classes($classes) {
    remove_filter( 'body_class', 'understrap_body_classes', 10);
    return $classes;
}
add_filter( 'body_class', 'remove_understrap_body_classes', 9 );


/**
 * Remove hook understrap_all_excerpts_get_more_link
 * defined in wp-content/themes/understrap/inc/extras.php
 *
 * @param array $post_excerpt
 * 
 * @return array
 */
function remove_understrap_all_excerpts_get_more_link($post_excerpt) {
    remove_filter( 'wp_trim_excerpt', 'understrap_all_excerpts_get_more_link' , 10);
    return $post_excerpt;
}
add_filter( 'wp_trim_excerpt', 'remove_understrap_all_excerpts_get_more_link', 9 );

/**
 * Update excerpt lenght
 *
 * @param int $length
 * @return int 
 */
function cdg_excerpt_length($length){ 
    return 20; 
} 
add_filter('excerpt_length', 'cdg_excerpt_length');


/**
 * Remove default Gutenberg patterns
 *
 * @return void
 */
function fire_theme_support() {
    remove_theme_support('core-block-patterns');
}
add_action('after_setup_theme', 'fire_theme_support');




/**
 * MENU
 */


/**
 * Add data-color attribute to menu items
 *
 * @param  array $atts
 * @param  object $item
 * @param  array $args
 * @return array
 */
function cdg_menu_atts( 
    $atts, 
    $item, 
    $args 
){
    $color = get_field('menu_color', $item);
    if($color) {
        $atts['data-color'] = $color;
    }

    $mdata = get_field('menu_data', $item);
    if($mdata) {
        $atts['data-'.$mdata] = true;
    }

    return $atts;
}
add_filter( 
    'nav_menu_link_attributes', 
    'cdg_menu_atts', 
    10, 
    3 
);


/**
 * Get Related Posts By Taxonomy.
 *
 * 1. Get posts by related IDs array (if any)
 * 2. Make a query by taxonomy and return if we have sufficient results.
 * 3. If we don't, make a query to get default posts ( TODO: get extra posts so that we can remove previously found posts, to avoid 'post__not_in' query )
 * 4. Merge the found post ids with previous ones and make return a fresh query by those ids in that order.
 *
 * @param int    $number     The number of posts to return.
 * @param string $post_type  Post type.
 * @param array  $postin     ids to be included.
 * @param array  $postnotin  ids to be excluded.
 * @param string $taxonomy   Taxonomy.
 * @param int    $term_id    Term id
 *
 * @return WP_Query
 */
function cdg_get_related_posts( 
    int    $number    = 4, 
    string $post_type = 'post',
    array  $postin    = null,
    array  $postnotin = null,
    string $taxonomy  = '', 
    int    $term_id   = null
) {

    $args = [
        'orderby'            => 'post__in',
        'posts_per_page'     => $number,
        'post_type'          => $post_type,
        'ignore_custom_sort' => true, // ignore advanced-post-types-order plugin
    ];

    // If there are default ids, get the posts
    $default_posts_ids  = [];
    if( ! empty( $postin ) ) {
        $args['post__in'] = $postin;
        $default_posts_query  = new WP_Query($args);
        $default_posts_ids = wp_list_pluck( 
            $default_posts_query->posts, 
            'ID' 
        ); 
    } else {
        $postin = [];
    }

    $default_posts_count = count( $default_posts_ids );
    // If we have sufficient no of results, early return.
    if ( $number == $default_posts_count ) {
        return $default_posts_query;
    }
  
    //TODO Avoid 'post__not_in'
    //https://docs.wpvip.com/technical-references/code-quality-and-best-practices/using-post__not_in/

    if( empty( $postnotin) ) {
        $postnotin = [];
    }

    $taxargs = [
       'posts_per_page'         => $number - $default_posts_count,
       'post_type'              => $post_type,
       'fields'                 => 'ids',
       'no_found_rows'          => true,
       'post__not_in'           => array_merge($postin, $postnotin),
       'update_post_meta_cache' => false,
       'update_post_term_cache' => false,
       'tax_query'              => [
          [
             'taxonomy' => $taxonomy,
             'field'    => 'id',
             'terms'    => $term_id,
          ],
       ],
    ];
 
    $post_tax_query  = new WP_Query( $taxargs );
    $post_by_tax_ids = $post_tax_query->posts;

    $relatedids = array_merge( 
        $default_posts_ids,
        $post_by_tax_ids
    );

    $the_post_count = count( $relatedids);

    // If we have sufficient no of results, early return.
    if ( $number == $the_post_count ) {
        $args['post__in'] = $relatedids; 
        return new WP_Query( $args );
    }
 
    // Otherwise get default posts and merge the two set of ids.
    $args['fields']   = 'ids';
    $args['post__in'] = null;

    $post_default_post_query = new WP_Query( $args );
    //Remove duplicates
    $ids = array_merge( 
        $relatedids, 
        $post_default_post_query->posts 
    );
 
    // Get the posts and with retained order.
    $args['fields']   = 'all';
    $args['post__in'] = $ids;
 
    return new WP_Query( $args );
 
}

/**
 * Get embed video html from url
 *
 * @param string $url
 * @return string|false
 */
function cdg_get_embed_video_html($url) {
    // Vimeo
    if (strpos($url, 'vimeo.com') !== false) {
        preg_match('/vimeo\.com\/(\d+)/', $url, $matches);

        if (!empty($matches[1])) {
            return '<iframe
                src="https://player.vimeo.com/video/' . esc_attr($matches[1]) . '?autoplay=1&muted=1&loop=1&background=1"
                frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen>
            </iframe>';
        }
    }

    // YouTube
    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {

        preg_match(
            '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?\/]+)/',
            $url,
            $matches
        );

        if (!empty($matches[1])) {
            $id = $matches[1];

            return '<iframe
                src="https://www.youtube.com/embed/' . esc_attr($id) . '?autoplay=1&mute=1&loop=1&playlist=' . esc_attr($id) . '&playsinline=1"
                frameborder="0"
                allow="autoplay; encrypted-media; fullscreen; picture-in-picture"
                allowfullscreen>
            </iframe>';
        }
    }

    return false;
}