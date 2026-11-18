<?php
/**
 * Header Navbar Contact
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );

$text_color = get_field('search_text_color', 'options') ?: '#FFF';
$bg_color   = get_field('search_bg_color', 'options') ?: '#E50695';

?>


<!-- The WordPress Menu goes here -->
<div id="mainMenuSearch"
    class="wrapper navigation-wrapper fade show"
    style="color:<?=$text_color?>;background-color:<?=$bg_color?>;"
>
    <div class="mainmenu-container <?php echo esc_attr( $container ); ?>">
        <div class="row">
            <!-- Do the left sidebar check -->
            <?php  
            $template_args['menu'] = 'searchMenu';
            get_template_part( 
                'global-templates/left-sidebar', 
                'check', 
                $template_args
            ); 
            ?>
            <div id="navbarSearch" 
                class="menu-main-menu-container row h-100 d-flex align-items-start justify-content-center"
            >
                <div class="col-md-12">
                    <header class="page-header search-nav-header">
                        <h1 class="page-title">
                            <?php
                            esc_attr_e( 'Search', 'codigo' );
                            ?>
                        </h1>
                    </header><!-- .page-header -->
                </div>
                <div class="col-md-12">
                    <?php get_search_form();?>
                </div>
            </div>

            <!-- Do the right sidebar check -->
            <?php
            get_template_part( 
                'global-templates/right-sidebar', 
                'check', 
                $template_args
            ); 
            ?>
        </div><!-- .row -->
    </div><!-- .mainmenu-container -->
</div><!-- #mainMenuWrapper -->
