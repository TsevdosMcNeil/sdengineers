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

                    $back_args['title'] = __('Back to', 'codigo').'<br/>'.__('Projects', 'codigo');
                    $back_args['url'] = get_post_type_archive_link('project');
                    get_template_part( 
                        'sidebar-templates/sidebar', 
                        'back',
                        $back_args
                    );
                    

                    $args = array();

                    ob_start(); 
                        the_archive_title( '<h1 class="page-title">', '</h1>' );
                        $args['title'] = ob_get_contents();
                    ob_end_clean();

                    $args['header_class'] =  'entry-header index-header page-header';
                    $args['filter']       =  false;
                    
                    get_template_part( 
                        'global-templates/index',
                        'header',
                        $args
                    ); 

                    
                    
    

                    if ( have_posts() ) {
                        // Start the Loop.
                        $post_cards = '';
                        $post_rows  = array();
                        $int        = 0;
                        while ( have_posts() ) {
                            the_post();
    
                            ob_start(); ?>
                                <div class="col project-col">
                                    <?php get_template_part( 
                                        'loop-templates/card', 
                                        'project'
                                    ); ?>
                                </div><?php
                                $post_cards .= ob_get_contents();
                            ob_end_clean();	

                        }

                        $index_html = '';
                        ob_start(); ?>
                            <div id="phpPosts"
                                class="row project-row"
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
                <div id="appProjects">
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
