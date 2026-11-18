<?php

/**
 * Custom template tags for this theme
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
  * Returns the amin color of the page or section
  *
  * @param  int $post_id
  * @return object
  */
function cdg_get_page_options($post_id) {

    $options = new stdClass();
    $options->page_color        = 'purple';
    $options->page_white_header = get_field('page_white_header');

    
    if(is_page()) {
        $options->page_color = get_field('page_color',$post_id) ?: 'purple';
    }

    //Journal
    if(is_single($post_id)) {
        $page_for_posts = get_option( 'page_for_posts' );
        $options->page_color = get_field('page_color',$page_for_posts) ?: 'purple';
    }
    if(is_home($post_id)) {
        $page_for_posts = get_option( 'page_for_posts' );
        $options->page_color = get_field('page_color',$page_for_posts) ?: 'purple';
    }

    //Projects
    if(is_archive($post_id)) {
        $options->page_color = 'orange';
    }
    if ( is_singular( 'project' ) ) {
        $options->page_color = 'orange';
    }

    //People
    if ( is_singular( 'people' ) ) {
        $options->page_color = 'purple';
    }
    
    return $options;
}


/**
 * Returns HTML with meta information for the current post-date/time and author.
 *
 * @param array $atts
 * 
 * @return void
 */
function cdg_get_posted_on(
    $atts = array()
) {

    //error_log('cdg_get_posted_on');

    // set up default parameters
    extract(shortcode_atts(array(
        'post_id'  => null,
        'class'    => '',
        'link'     => false,
        'category' => false,
        'author'   => false
    ), $atts));

    if(!$post_id) {
        $post_id = get_the_ID();
    }

    $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
    $time_string = sprintf(
        $time_string,
        esc_attr( get_the_date( 'c', $post_id ) ),
        esc_html( get_the_date('',$post_id) )
    );
    $time_string = apply_filters( 'understrap_posted_on_time', $time_string );

    if($category) {
        $time_string = sprintf(
            '<span class="entry-type">%1$s</span> - '.$time_string,
            $category
        );
    }

    if($link) {
        $posted_on = sprintf(
            '<span class="posted-on">
                <a 
                    href="%1$s" 
                    rel="bookmark"
                    class="%2$s"
                >%3$s</a>
            </span>',
            esc_url( get_permalink($post_id) ),
            $class,
            $time_string
        );
    } else {
        $posted_on = sprintf(
            '<span class="posted-on %1$s">
                %2$s
            </span>',
            $class,
            $time_string
        );
    }

    if($author) {

        $byline = apply_filters(
            'understrap_posted_by',
            sprintf(
                '<div class="author"><a href="%1$s">%2$s</a></div>',
                esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                esc_html( get_the_author() )
            )
        );
        $posted_on = $byline.$posted_on;
    }


    return apply_filters('understrap_posted_on', $posted_on ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @param array $atts
 * 
 * @return void
 */
function cdg_posted_on(
    $atts = array()
) {
    echo cdg_get_posted_on($atts); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}


/**
 * Returns HTML with the header for filtering accordions.
 *
 * @param array $atts
 * 
 * @return void
 */
function cdg_get_filter_header(
    $atts = array()
) {

    //error_log('cdg_get_posted_on');

    // set up default parameters
    extract(shortcode_atts(array(
        'h3_id'  => null,
        'target' => null,
    ), $atts));

    ob_start(); ?>
        <h3 class="accordion-header d-flex" 
            id="<?=$h3_id?>"
        >
            <button class="accordion-button collapsed" 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#<?=$target?>" 
                aria-expanded="true" 
                aria-controls="<?=$target?>"
            >
                <?php _e('Filter','codigo'); ?>
            </button>
        
            <!-- Vue -->
            <span id="appReset" 
                class="accordion-reset body-font mt-auto ms-auto" 
            >
                <reset-item></reset-item>
            </span> 
            <!-- / Vue  -->
        </h3>
        
        <?php
        $header_html = ob_get_contents();
    ob_end_clean();	

    return $header_html;
}

/**
 * Prints HTML with the header for filtering accordions.
 *
 * @param array $atts
 * 
 * @return void
 */
function cdg_filter_header(
    $atts = array()
) {
    echo cdg_get_filter_header($atts); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}