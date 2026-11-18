<?php
/**
 * The template for displaying archive pages
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<div class="wrapper" id="archive-wrapper">

	<div class="<?php echo esc_attr( $container ); ?>" id="content" tabindex="-1">

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

			<main class="site-main" id="main">
                <?php 
                    $args = array();

                    ob_start(); 
                        the_archive_title( '<h1 class="page-title">', '</h1>' );
                        $args['title'] = ob_get_contents();
                    ob_end_clean();

                    ob_start(); 
                        the_archive_description( '<div class="taxonomy-description">', '</div>' );
                        $args['description'] = ob_get_contents();
                    ob_end_clean();

                    $args['header_class'] =  'entry-header index-header page-header';
                    
                    get_template_part( 
                        'global-templates/index',
                        'header',
                        $args
                    ); 

				if ( have_posts() ) {
					// Start the Loop.
                    $post_cards = '';
                    $int        = 0;
					while ( have_posts() ) {
						the_post();
                        if($int == 0 ) {
                            ob_start(); ?>
                                <div class="col">
                                    <?php get_template_part( 
                                        'loop-templates/card-main', 
                                        'post'
                                    ); ?>
                                </div><?php
                                $post_cards .= ob_get_contents();
                            ob_end_clean();	
                        } else {
                            ob_start(); ?>
                                <div class="col">
                                    <?php get_template_part( 
                                        'loop-templates/card', 
                                        'post'
                                    ); ?>
                                </div><?php
                                $post_cards .= ob_get_contents();
                            ob_end_clean();	
                        }
                        $int++;
					}

                    $index_html = '';
                    ob_start(); ?>
                        <div id="phpPosts"
                            class="row index-row"
                        >
                            <?=$post_cards?>
                        </div><?php
                        $index_html .= ob_get_contents();
                    ob_end_clean();	
                    echo $index_html;

				} else {
					get_template_part( 'loop-templates/content', 'none' );
				}
				?>

                <!-- Vue -->
                <div id="appPosts">
                    <posts-list></posts-list>
                </div>
                <!-- / Vue  -->

			</main><!-- #main -->

			<?php
			// Do the right sidebar check.
			get_template_part( 'global-templates/right-sidebar-check' );
			?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #archive-wrapper -->

<?php
get_footer();
