<?php
/**
 * Header Navbar Contact
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );

$text_color = get_field('contact_text_color', 'options') ?: '#FFF';
$bg_color   = get_field('contact_bg_color', 'options') ?: '#45464B';

?>


<!-- The WordPress Menu goes here -->
<div id="mainMenuContact"
    class="wrapper navigation-wrapper fade show"
    style="color:<?=$text_color?>;background-color:<?=$bg_color?>;"
>
    <div class="mainmenu-container <?php echo esc_attr( $container ); ?>">
        <div class="row">
            <!-- Do the left sidebar check -->
            <?php  
            $template_args['menu'] = 'contactMenu';
            get_template_part( 
                'global-templates/left-sidebar', 
                'check', 
                $template_args
            ); 
            ?>
            <div id="navbarContact" class="menu-main-menu-container row">
                <div class="col-md-6 contact-col-1">
                    <?php echo wp_kses_post( get_field('contact_column_1', 'option') ); ?>
                </div>
                <div class="col-md-6 contact-col-2">
                    <?php echo wp_kses_post( get_field('contact_column_2', 'options')); ?>
                </div>
                <div class="col-md-6 contact-col-3 mt-3">
                    <?php echo wp_kses_post( get_field('contact_column_3', 'options')); ?>
                </div>
                <div class="col-md-6 contact-col-4 mt-3">
                    <?php echo wp_kses_post( get_field('contact_column_4', 'options')); ?>
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
