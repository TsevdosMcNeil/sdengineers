<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

$container = get_theme_mod( 'understrap_container_type' );
?>

<div id="error-404-wrapper"
    class="wrapper" 
>

	<div id="content"
        class="<?php echo esc_attr( $container ); ?>"  
        tabindex="-1"
    >

		<div class="row">

			<!-- Do the left sidebar check -->
			<?php get_template_part( 'global-templates/left-sidebar-check' ); ?>

				<main class="site-main" id="main">

					<section class="error-404 not-found">

						<header class="entry-header">

							<h1 class="page-title"><?php echo wp_kses_post( get_field('404_title', 'options')); ?></h1>

						</header><!-- .page-header -->

						<div class="page-content">

                            <?php echo wp_kses_post( get_field('404_content', 'options')); ?>

						</div><!-- .page-content -->

					</section><!-- .error-404 -->

				</main>

			<!-- Do the right sidebar check -->
			<?php get_template_part( 'global-templates/right-sidebar-check' ); ?>

		</div><!-- .row -->

	</div><!-- #content -->

</div><!-- #error-404-wrapper -->

<?php
get_footer();
