<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


//error_log(print_r($args,true));
extract(wp_parse_args(
    $args,
    array(
        'orientation' => 'both',
        'excerpt'     => true,
        'category'    => true,
    )
));

//error_log(print_r($orientation,true));

$portrait_id   = get_field('post_featured_image_portrait') ?: get_field('post_featured_image_landscape');
$landscape_id  = get_field('post_featured_image_landscape') ?: get_field('post_featured_image_portrait');
$portrait_ratio  = "ratio-2x3";
$landscape_ratio = "ratio-3x2";

$portrait_img = wp_get_attachment_image(
    $portrait_id ,
    'large',
    false,
    array('class' => 'card-img-top') 
);
$landscape_img = wp_get_attachment_image(
    $landscape_id ,
    'large',
    false,
    array('class' => 'card-img-top') 
);


if($orientation == 'portrait') {
    $ratio        = $portrait_ratio;
    $featured_img = $portrait_img;
}
if($orientation == 'landscape') {
    $ratio        = $landscape_ratio;
    $featured_img = $landscape_img;
}

?>

<div 
    <?php post_class('card card-post'); ?> 
    id="post-<?php the_ID(); ?>"
>
    <a 
        href="<?php echo esc_url( get_permalink() ); ?>" 
        rel="bookmark"
    >
        <?php 
        if($category) : 
            $categories = wp_get_post_categories( $post->ID );
            if(isset($categories[0])):  ?>
            <div class="entry-category">
                <?=get_cat_name($categories[0])?>
            </div><?php   
            endif;
        endif; ?>
        
        <?php if($orientation == 'both') : ?>
            <figure class="size-large ratio <?=$portrait_ratio?> portrait">
                <?php echo $portrait_img; ?>
            </figure>
            <figure class="size-large ratio <?=$landscape_ratio?> landscape">
                <?php echo $landscape_img; ?>
            </figure>
        <?php else: ?>
            <figure class="size-large ratio <?=$ratio?> <?=$orientation?>">
                <?php echo $featured_img; ?>
            </figure>
        <?php endif; ?>

        <header class="card-header">
            <?php if($excerpt) : ?>
                <div class="entry-meta">
                    <?php cdg_posted_on(); ?>
                </div><!-- .entry-meta -->
                <?php
                the_title(
                    '<h6 class="card-title mb-0">',
                    '</h6>'
                );
                ?>
                <div class="entry-excerpt">
                    <?php the_excerpt(); ?>
                </div>
            <?php else: ?>
                <?php
                the_title(
                    '<h6 class="card-title mb-0">',
                    '</h6>'
                );
                ?>
                <div class="entry-meta">
                    <?php cdg_posted_on(); ?>
                </div><!-- .entry-meta -->
            <?php endif ?>

            <div class="btn btn-primary entry-readmore"></div>

        </header><!-- .entry-header -->
    </a>
</div><!-- #post-## -->

