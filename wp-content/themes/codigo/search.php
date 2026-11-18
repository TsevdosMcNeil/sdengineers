<?php
/**
 * The template for displaying search results pages
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );

?>

<div id="search-wrapper"
    class="wrapper" 
>

	<div id="content"
        class="<?php echo esc_attr( $container ); ?>"  
        tabindex="-1"
    >

		<div class="row">

			<?php
			// Do the left sidebar check and open div#primary.
			get_template_part( 'global-templates/left-sidebar-check' );
			?>

			<main class="site-main" id="main">
				<header class="page-header search-header">

						<h1 class="page-title">
							<?php
							_e( 'Search', 'codigo' );
							?>
						</h1>

				</header><!-- .page-header -->

				<div class="row">
					<div class="col-md-6">
						<h3 class="search-results-title">
						<?php
						printf(
							/* translators: %s: query term */
							esc_html__( "You searched for '%s'", 'codigo' ),
							'<span>' . get_search_query() . '</span>'
						);
						?>
						</h3>
					</div>
					<div class="col-md-6">
					<?php 
                    if ( get_search_query() 
                        && have_posts() 
                    ): ?>
                        <div class="search-mobile-results d-md-none">
                        <?php
                        while ( have_posts() ) :
                            the_post();
                            get_template_part( 
                                'loop-templates/content', 
                                'search',
                                array('viewport' => 'mobile')
                            );
                        endwhile; ?>
                        </div>
						<table class="search-desktop-results table table-striped table-hover d-none d-md-block">
							<tbody>
							<?php
							while ( have_posts() ) :
								the_post();
								get_template_part( 
                                    'loop-templates/content', 
                                    'search',
                                    array('viewport' => 'desktop')
                                );
							endwhile;
							?>
							</tbody>
						</table>
						
					<?php else : ?>
						<?php get_template_part( 'loop-templates/content', 'none' ); ?>
					<?php endif; ?>
					</div>
				</div>

			</main>

			<?php
			// Do the right sidebar check and close div#primary.
			get_template_part( 'global-templates/right-sidebar-check' );
			?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #search-wrapper -->

<?php
get_footer();
