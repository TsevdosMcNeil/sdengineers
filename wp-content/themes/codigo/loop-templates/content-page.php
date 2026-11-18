<?php
/**
 * Partial template for content in page.php
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>"
    <?php post_class(); ?> 
>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

    <?php 
    $firstAnchor = null;
    if(is_front_page()) {
        $firstAnchor = '<div id="expertise"
            style="height:0" 
            aria-hidden="true"  
            class="wp-block-spacer nowaypoint d-block"
        ></div>';
        get_template_part( 
            'global-templates/home', 
            'lottie' 
        ); 
    }  
    ?>

    <div class="entry-content">
		<?php
        echo $firstAnchor;
		the_content();
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">

		<?php understrap_edit_post_link(); ?>

	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
