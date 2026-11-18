<?php

/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$featured = $featured_portrait = $featured_landscape = get_the_post_thumbnail(get_the_ID(), 'large');

//error_log(print_r($args,true));
extract(wp_parse_args(
  $args,
  array(
    'orientation' => 'both',
    'subheader'   => false,
    'post_type'   => 'project',
  )
));

//error_log(print_r($orientation,true));

$portrait_id   = get_field('project_featured_image_portrait') ?: get_field('project_featured_image_landscape');
$landscape_id  = get_field('project_featured_image_landscape') ?: get_field('project_featured_image_portrait');
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

if($portrait_img) {
  $featured_portrait = $portrait_img;
}
if($landscape_img) {
  $featured_landscape = $landscape_img;
}


//error_log(' Title: ' . print_r(get_the_title() ,true));
// if(get_the_title() == "Mary Ward Centre Queensway House") {
//   error_log(' Portrait Image: ' . print_r($portrait_img,true));
//   error_log(' Landscape Image: ' . print_r($landscape_img,true));
//   error_log(' Featured Image: ' . print_r($featured,true));
// }


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

if($portrait_video_html) {
  $featured_portrait = $portrait_video_html;
}
if($landscape_video_html) {
  $featured_landscape = $landscape_video_html;
}

// if(get_the_title() == "Mary Ward Centre Queensway House") {
//   error_log(' Portrait Image: ' . print_r($featured_portrait,true));
//   error_log(' Landscape Image: ' . print_r($featured_landscape,true));
//   error_log(' Featured Image: ' . print_r($featured,true));
// }


if ($orientation == 'portrait') {
  $ratio    = $portrait_ratio;
  $featured = $featured_portrait;
  
}
if ($orientation == 'landscape') {
  $ratio    = $landscape_ratio;
  $featured = $featured_landscape;
}

?>

<div
  <?php post_class('card card-post card-project'); ?>
  id="post-<?php the_ID(); ?>">
  <a
    href="<?php echo esc_url(get_permalink()); ?>"
    rel="bookmark">

    <?php if ($subheader) : ?>
      <div class="entry-subheader">
        <?= $subheader ?>
      </div>
    <?php endif; ?>

    <?php if ($orientation == 'both') : ?>
      <figure class="size-large ratio <?= $portrait_ratio ?> portrait">
        <?php echo $featured_portrait; ?>
      </figure>
      <figure class="size-large ratio <?= $landscape_ratio ?> landscape">
        <?php echo $featured_landscape; ?>
      </figure>
    <?php else: ?>
      <figure class="size-large ratio <?= $ratio ?> <?= $orientation ?>">
        <?php echo $featured; ?>
      </figure>
    <?php endif; ?>

    <header class="card-header">
      <?php
      the_title(
        '<h6 class="card-title mb-0">',
        '</h6>'
      );
      ?>
      <?php if ($post_type == 'project') : ?>
        <div class="entry-location h6">
          <?php echo getProjectFeature('location', get_the_ID()); ?>
        </div><!-- .entry-location -->
      <?php else: ?>
        <div class="entry-meta">
          <?php cdg_posted_on(); ?>
        </div><!-- .entry-meta -->
      <?php endif ?>
      <div class="btn btn-primary entry-readmore"></div>
    </header><!-- .entry-header -->
  </a>
</div><!-- #post-## -->