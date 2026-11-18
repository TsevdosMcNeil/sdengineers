<?php

/**
 * Guttenberg block functions
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



/**
 * Modify block content
 *
 * @param  string
 * @param  array
 * 
 * @return string
 */
function cdg_render_block_core_group(
  string $block_content,
  array $block
): string {

  //error_log($block['blockName']);
  if (
    !is_admin() &&
    !wp_is_json_request()
  ) {



    //Display <figcaption> after <figure>
    if (
      $block['blockName'] === 'core/image'
    ) {
      //error_log($block['blockName']);
      //error_log($block['innerHTML']);
      preg_match('/<figcaption>(.*?)<\/figcaption>/s', $block['innerHTML'], $match);
      if (isset($match[0])) {
        $block['innerHTML'] = preg_replace('/<figcaption>(.*?)<\/figcaption>/s', '', $block['innerHTML']) . $match[0];
      }
      //error_log($block['innerHTML']);
      //$block_content = $block['innerHTML'];
    }

    /* Vimeo features:
        * - Adding mute button HTML block
        * - Getting settings of URL:
        *    - autoplay
        *    - loop
        *    - hidecontrols
        *   Example: 
        *   https://vimeo.com/812092824?autoplay=1&loop=1&hidecontrols=1
        */

    if (
      $block['blockName'] === 'core/embed'
    ) {
      //error_log(print_r($block,true));
      //error_log($block['blockName']);
      //error_log($block['innerHTML']);
      //error_log($block['attrs']['url']);
      //error_log($block_content);

      if (str_contains(
        $block['innerHTML'],
        'wp-block-embed-vimeo'
      )) {

        /*
                if( preg_match( 
                        '/autoplay/i', 
                        $block['innerHTML']
                    )
                ) {
                    //Adding autoplay
                    $block['innerHTML'] = preg_replace(
                        '/src="([^"]*)/', 
                        '$0&amp;background=1&amp;autoplay=1', 
                        $block['innerHTML'] 
                    );
                }
                */

        // Adding autoplay.
        if (preg_match(
          '/autoplay=1/i',
          $block['attrs']['url']
        )) {

          $block['innerHTML'] = preg_replace(
            '/src="([^"]*)/',
            '$0&amp;background=1&amp;autoplay=1',
            $block['innerHTML']
          );
        }

        // Adding loop.
        if (preg_match(
          '/loop=1/i',
          $block['attrs']['url']
        )) {

          $block['innerHTML'] = preg_replace(
            '/src="([^"]*)/',
            '$0&amp;loop=1',
            $block['innerHTML']
          );
        }

        // Adding mute button.
        if (! preg_match(
          '/hidecontrols=1/i',
          $block['attrs']['url']
        )) {
          $block['innerHTML'] = str_replace(
            '</iframe>',
            '</iframe>
                        <div class="vimeo-controls"> 
                            <button class="btn vimeo-control muted">Muted</button>
                            <button class="btn vimeo-control unmuted hide">Unmuted</button>
                        </div>',
            $block['innerHTML']
          );
        }

        //error_log($block['innerHTML']);
        $block_content = $block['innerHTML'];
      }
    }


    //post-featured-image
    if (
      $block['blockName'] === 'core/post-featured-image'
    ) {

      //error_log($block['blockName']);

      // Only inside a query loop / post template context
      $post_id = $instance->context['postId'] ?? null;
      //error_log('post_id: ' . $post_id);
      if (!$post_id) {
        $post_id = get_the_ID();
      }

      //error_log('post_id: ' . $post_id);

      if (!$post_id) {
        return $block_content;
      }

      $featured_portrait = $featured_landscape = get_the_post_thumbnail($post_id, 'large');

      //error_log(' Featured Image: ' . print_r($featured_portrait,true));

      $portrait_id   = get_field('project_featured_image_portrait', $post_id) ?: get_field('project_featured_image_landscape', $post_id);
      $landscape_id  = get_field('project_featured_image_landscape', $post_id) ?: get_field('project_featured_image_portrait', $post_id);
      $portrait_ratio  = "ratio-2x3";
      $landscape_ratio = "ratio-3x2";

      $portrait_img = wp_get_attachment_image(
        $portrait_id,
        'large',
        false,
        array('class' => 'card-img-top')
      );
      $landscape_img = wp_get_attachment_image(
        $landscape_id,
        'large',
        false,
        array('class' => 'card-img-top')
      );

      if ($portrait_img) {
        $featured_portrait = $portrait_img;
      }
      if ($landscape_img) {
        $featured_landscape = $landscape_img;
      }

      $portrait_video = get_field('project_featured_video_portrait');
      $portrait_video_html = false;
      if (isset($portrait_video) && $portrait_video['type'] != "none") {
        if ($portrait_video['type'] == "file" && $portrait_video['file']) {
          $video_url = wp_get_attachment_url($portrait_video['file']);
          $portrait_video_html =  '<video
            autoplay
            muted
            loop
            playsinline
            preload="metadata"
            width="100%"
          >
            <source src="' . esc_url($video_url) . '" 
              type="' . esc_attr(get_post_mime_type($portrait_video['file'])) . '">
            Your browser does not support the video tag.
          </video>';
        }
        if ($portrait_video['type'] == "embed" && $portrait_video['embed_video']) {
          $portrait_video_html = cdg_get_embed_video_html($portrait_video['embed_video']);
        }
      }

      $landscape_video = get_field('project_featured_video_landscape');
      $landscape_video_html = false;
      if (isset($landscape_video) && $landscape_video['type'] != "none") {
        if ($landscape_video['type'] == "file" && $landscape_video['file']) {
          $video_url = wp_get_attachment_url($landscape_video['file']);
          $landscape_video_html =  '<video
            autoplay
            muted
            loop
            playsinline
              preload="metadata"
              width="100%"
            >
              <source src="' . esc_url($video_url) . '" 
                type="' . esc_attr(get_post_mime_type($landscape_video['file'])) . '">
              Your browser does not support the video tag.
            </video>';
        }
        if ($landscape_video['type'] == "embed" && $landscape_video['embed_video']) {
          $landscape_video_html = cdg_get_embed_video_html($landscape_video['embed_video']);
        }
      }

      if ($portrait_video_html) {
        $featured_portrait = $portrait_video_html;
      }
      if ($landscape_video_html) {
        $featured_landscape = $landscape_video_html;
      }

      $block_content = sprintf(
        '<figure class="size-large ratio portrait wp-block-post-featured-image">%s</figure>
        <figure class="size-large ratio landscape wp-block-post-featured-image">%s</figure>',
        $featured_portrait,
        $featured_landscape
      );
    }
  }

  return $block_content;
}

add_filter('render_block', 'cdg_render_block_core_group', null, 2);
