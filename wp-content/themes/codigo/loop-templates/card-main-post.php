<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


extract(wp_parse_args(
    $args,
    array(
        'orientation' => 'landscape',
        'excerpt'     => false, //deprecated
        'category'    => true,
    )
));

$landscape_id  = get_field('post_featured_image_landscape') ?: get_field('post_featured_image_portrait');

$ratio  = "ratio-3x2";
$featured_img = wp_get_attachment_image(
    $landscape_id ,
    'large',
    false,
    array('class' => 'card-img-top') 
);

?>

<div 
    <?php post_class('card card-post card-main-post'); ?> 
    id="post-<?php the_ID(); ?>"
>
    <a 
        href="<?php echo esc_url( get_permalink() ); ?>" 
        rel="bookmark"
    >
        <div class="row">
            
            <div class="col-xxl-9">
                <?php 
                if($category) : 
                    $categories = wp_get_post_categories( $post->ID );
                    if(isset($categories[0])):  ?>
                    <div class="entry-category d-md-none">
                        <?=get_cat_name($categories[0])?>
                    </div><?php   
                    endif;
                endif; ?>
                <figure class="size-large ratio <?=$ratio?> <?=$orientation?>">
                    <?php echo $featured_img; ?>
                </figure>
            </div>
            <div class="col-xxl-3">
                <header class="card-header">
                <?php 
                if($category) : 
                    $categories = wp_get_post_categories( $post->ID );
                    if(isset($categories[0])): 
                ?>
                    <div class="entry-category mb-0 d-none d-md-block">
                        <?=get_cat_name($categories[0])?>
                    </div>
                <?php endif; 
                endif; 
                ?>
                <div class="entry-meta d-md-none">
                    <?php cdg_posted_on(); ?>
                </div><!-- .entry-meta -->
                <?php
                    the_title(
                        '<h6 class="md-h3 card-title mb-0">',
                        '</h6>'
                    );
                ?>

                    <div class="entry-excerpt d-md-none">
                        <?php the_excerpt(); ?>
                    </div>

                    <div class="entry-meta d-none d-md-block">
                        <?php cdg_posted_on(); ?>
                    </div><!-- .entry-meta -->

                    <div class="btn btn-primary entry-readmore"></div>
                </header><!-- .entry-header -->
            </div>
        </div><!-- .row -->
    </a>
 </div><!-- #post-<?php the_ID(); ?> -->
