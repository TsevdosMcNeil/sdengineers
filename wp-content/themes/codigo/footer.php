<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$post_id = $post->ID ?? '';
$page_options = cdg_get_page_options($post_id);
$container    = get_theme_mod( 'understrap_container_type' );
?>

<?php get_template_part( 'sidebar-templates/sidebar', 'footerfull' ); ?>

<div class="wrapper" id="wrapper-footer">

	<div class="<?php echo esc_attr( $container ); ?>">

		<footer class="site-footer row" id="colophon">

            <!-- Do the left sidebar check -->
            <?php  
            $template_args['footer'] = true;
            get_template_part( 
                'global-templates/left-sidebar', 
                'check', 
                $template_args
            ); 
            ?>

				<div class="site-info row">

					<div class="col-md-6 col-lg-7 col-xl-2 d-flex align-items-end justify-content-start footer_column footer_column_1">
                        <div class="footer_column_inner">
                            <?php echo wp_kses_post( get_field('footer_column_1', 'options')); ?>
                        </div>
					</div><!-- .col -->
                    <div class="col-md-6 col-lg-5 col-xl-2 d-flex align-items-end justify-content-start footer_column footer_column_2">
                        <div class="footer_column_inner">
                            <?php echo wp_kses_post( get_field('footer_column_2', 'options')); ?>
                        </div>
					</div><!-- .col -->
                    <div class="col-md-6 col-lg-7 col-xl-2 d-flex align-items-start justify-content-start footer_column footer_column_3">
                        <div class="footer_column_inner">
                            <?php echo wp_kses_post( get_field('footer_column_3', 'options')); ?>
                        </div>
					</div><!-- .col -->
                    <div class="col d-flex align-items-end justify-content-end footer_column footer_column_4">
                        <div class="footer_column_inner">
                            <?php echo wp_kses_post( get_field('footer_column_4', 'options')); ?>
                        </div>
					</div><!-- .col -->

                </div><!-- row end -->

            <!-- Do the right sidebar check -->
            <?php
            get_template_part( 
                'global-templates/right-sidebar', 
                'check', 
                $template_args
            ); 
            ?>

		</footer><!-- #colophon -->

        <div class="footer-media">

         <?php 
            $upload_dir = wp_get_upload_dir();
            if(isset( $upload_dir['baseurl'] ) ):
                $footerLottieVersion = 'v3.2';
                $frames = '[0, 179]'; //"op":180 ?>
                <lottie-player id="footerLottie" 
                    src="<?php echo $upload_dir['baseurl'].'/homevideo/footer/footerbg_'.$page_options->page_color.'_'.$footerLottieVersion.'.json'; ?>"
                ></lottie-player>

                <script>
                    LottieInteractivity.create({
                        player: "#footerLottie",
                        mode: "scroll",
                        actions: [
                        {
                            visibility: [0, 1],  //Begin the action when the Lottie object is starting to be  visible on-screen and stop when 100% of the Lottie object   is visible on-screen.
                            type: "loop",
                            frames: <?=$frames?>,
                        }
                        ]
                    });
                </script>
            <?php endif; ?>

		</div><!-- .footer-media -->

	</div><!-- container end -->

</div><!-- wrapper end -->

</div><!-- #page we need this extra closing tag here -->

<?php wp_footer(); ?>

</body>

</html>

