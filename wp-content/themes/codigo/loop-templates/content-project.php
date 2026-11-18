<?php

/**
 * Project post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

$post_id = get_the_ID();

if (! isset($post_id) || empty($post_id)) {
  return;
}

$taxonomies = array('sector', 'expertise');

foreach ($taxonomies as $taxonomy) {
  $terms[$taxonomy] = wp_get_post_terms($post_id, $taxonomy);
}
//error_log(print_r($terms,true));


?>

<article <?php post_class(); ?> id="project-<?php the_ID(); ?>">
  <?php
  $back_args['title'] = __('Back to', 'codigo') . '<br/>' . __('Projects', 'codigo');
  $back_args['url'] = get_post_type_archive_link('project');
  get_template_part(
    'sidebar-templates/sidebar',
    'back',
    $back_args
  );
  ?>

  <header class="entry-header">

    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

  </header><!-- .entry-header -->

  <div class="entry-content">
    <?php
    the_content();
    ?>
  </div><!-- .entry-content -->

  <?php

  $related     = get_field('project_related_projects') ?: [];
  $relatedHTML = '';
  $sector_id   = false;
  if (isset($terms['sector'][0])) {
    $sector_id   = $terms['sector'][0]->term_id;
  }
  if (class_exists('WPSEO_Options')) {
    $sector_id = yoast_get_primary_term_id(
      'sector',
      $post_id
    );
  }


  if (isset($sector_id)) {

    $the_related_query = cdg_get_related_posts(
      3,
      'project',
      $related,
      [$post_id],
      'sector',
      $sector_id,
    );

    if ($the_related_query->have_posts()) :
      $int = 0;
      $args['subheader']   = __('Project', 'codigo');
      $args['post_type']   = 'project';
      $args['orientation'] = 'landscape';
      $csscol = "col-sm-6 col-xxl-4 ";

      while ($the_related_query->have_posts()) {
        if ($int >= 2) {
          $csscol .= " d-sm-none d-xxl-block";
        }

        $the_related_query->the_post();

        ob_start(); ?>
        <div class="<?= $csscol ?>">
          <?php get_template_part(
            'loop-templates/card',
            'project',
            $args
          ); ?>
        </div><?php
              $relatedHTML .= ob_get_contents();
              ob_end_clean();

              $int++;
            }
          endif;
          wp_reset_postdata();
        }


        if ($relatedHTML): ?>
    <footer class="entry-footer">
      <h3><?php _e('Related', 'codigo') ?></h3>
      <div class="row">
        <?= $relatedHTML ?>
      </div>
    </footer><!-- .entry-footer -->
  <?php endif; ?>
</article><!-- #project-## -->