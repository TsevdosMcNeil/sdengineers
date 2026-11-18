<?php
/**
 * Single post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$categories = wp_get_post_categories( $post->ID );

?>

<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">
    <?php 
        $back_args['title'] = __('Back to', 'codigo').'<br/>'.__('Journal', 'codigo');
        $back_args['url'] = get_permalink( get_option( 'page_for_posts' ) );
        get_template_part( 
            'sidebar-templates/sidebar', 
            'back',
            $back_args
        );
    ?>
    <header class="entry-header">

        <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

        <?php 
        if(is_array($categories)
            && isset($categories[0])
        ): ?>
        <div class="entry-meta">
            <?php 
                cdg_posted_on(
                    array(
                        'category' => get_cat_name($categories[0])
                    )
                ); 
            ?>
        </div><!-- .entry-meta -->
        <?php endif; ?>

    </header><!-- .entry-header -->

    <div class="entry-content">
        
        <?php
        the_content();
        ?>
    </div><!-- .entry-content -->
    <?php 
    $related = get_posts( 
        array( 
            'category__in' => $categories, 
            'numberposts'  => 4, 
            'post__not_in' => array( $post->ID ) 
        ) 
    );

    if( $related ): ?>
    <footer class="entry-footer">
        <h3><?php _e('Related', 'codigo') ?></h3>
        <div class="row">

            <?php 
            $int = 0;
            $args['excerpt']  = false;
            $args['category'] = false;
            foreach( $related as $post ) {
                $args['orientation'] = 'landscape';
                $csscol = "col-sm-8 col-xxl-3";
                if($int%2 == 1) {
                    $args['orientation'] = 'portrait';
                    $csscol = "col-sm-4 col-xxl-3";
                }
                if($int >= 2) {
                    $csscol .= " d-sm-none d-xxl-block";
                }
                setup_postdata($post); 
                ob_start(); ?>
                    <div class="<?=$csscol?>">
                        <?php get_template_part( 
                            'loop-templates/card', 
                            'post',
                            $args
                        ); ?>
                    </div><?php
                    $related = ob_get_contents();
                ob_end_clean();	
                echo $related;
                $int++;
            }
            wp_reset_postdata(); 
            ?>
        </div>
    </footer><!-- .entry-footer -->
    <?php endif; ?>
</article><!-- #post-## -->
