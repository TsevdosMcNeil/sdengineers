<?php
/**
 * The sidebar containing the main widget area
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'left-sidebar' ) ) {
	return;
}

// when both sidebars turned on reduce col size to 2 from 4.
$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );

$args = wp_parse_args(
    $args,
    array(
        'menu'   => false,
        'footer' => false,
        'modal' => false,
    )
);

$sideclass = 'widget-area sidebar-left';
$sideid    = 'left-sidebar';
if($args['menu']) {
    $sideclass = 'sidebar-left';
    $sideid    = 'menu-left-sidebar';
} 
if($args['footer']) {
    $sideclass = 'sidebar-left';
    $sideid    = 'footer-left-sidebar';
} 
?>

<?php if ( 'both' === $sidebar_pos ) : ?>
	<div class="col-lg-2 <?=$sideclass?>" id="<?=$sideid?>">
<?php else : ?>
	<div class="col-lg-4 <?=$sideclass?>" id="<?=$sideid?>">
<?php endif; ?>

<?php if($args['menu']) : ?>
    <?php get_template_part('global-templates/navbar-logo' ); ?>
<?php elseif($args['modal']) : ?>
    <?php get_template_part('global-templates/navbar-logo' ); ?>
<?php elseif($args['footer']) : ?>
    <?php //empty ?>
<?php else : ?>
    <?php if( have_rows('page_left_menu') ): ?>
    <ul id="leftMenu">
    <?php while( have_rows('page_left_menu') ) : the_row(); ?>
        <li>
            <a data-menu="<?php echo get_row_index(); ?>"
                href="#<?php echo wp_kses_post(get_sub_field('link')); ?>"
            ><?php echo wp_kses_post(get_sub_field('title') );?></a>
        </li>
    <?php endwhile; ?>
    </ul>
    <?php endif;  ?>
    
<?php endif; ?>

</div><!-- #<?=$sideid?> -->
