<?php
/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$bootstrap_version = get_theme_mod( 'understrap_bootstrap_version', 'bootstrap4' );
$navbar_type       = get_theme_mod( 'understrap_navbar_type', 'collapse' );

$post_id      = $post->ID ?? '';
$page_options = cdg_get_page_options($post_id);
$page_class   = $page_options->page_white_header ? 'header-white' : '';
$page_class  .= " ".$page_options->page_color;

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>

    <script>
        const REST_POSTS_URL   = "<?php echo esc_url_raw( rest_url( 'wp/v1/' ) ); ?>";
        const REST_NONCE       = "<?php echo wp_create_nonce( 'wp_rest' ); ?>";
        const REST_LOADING_GIF = "<?=get_stylesheet_directory_uri()?>/vue/img/loading.gif";
        const REST_NO_RESULTS  = "<?php _e( 'No Results', 'codigo' ); ?>";
    </script>
</head>

<body <?php body_class('hide-menu hide-contact-menu hide-search-menu'); ?> 
    <?php understrap_body_attributes(); ?>
>

<?php 
$pageStyle = '';
if(is_front_page()) { 
    $pageStyle = 'style="opacity:0"';
    echo do_shortcode('[splash 
        background_color=white 
        splash_id=splashHome
    ]');
} 
?>

<?php do_action( 'wp_body_open' ); ?>
<div id="page"
    class="site <?=$page_class?>" 
    <?=$pageStyle?>
>

	<!-- ******************* The Navbar Area ******************* -->
	<header id="wrapper-navbar">

		<a class="skip-link sr-only sr-only-focusable" href="#content"><?php esc_html_e( 'Skip to content', 'understrap' ); ?></a>

		<?php get_template_part( 
            'global-templates/navbar', 
            $navbar_type . '-' . $bootstrap_version 
        ); ?>

	</header><!-- #wrapper-navbar end -->
    