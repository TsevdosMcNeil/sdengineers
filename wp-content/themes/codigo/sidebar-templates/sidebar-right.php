<?php
/**
 * The right sidebar containing the main widget area
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! is_active_sidebar( 'right-sidebar' ) ) {
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

$sideclass = 'widget-area sidebar-right';
$sideid    = 'right-sidebar';
if($args['menu']) {
    $sideclass = 'd-flex flex-column align-items-end sidebar-right';
    $sideid    = 'menu-right-sidebar';
} 
if($args['footer']) {
    $sideclass = 'sidebar-right';
    $sideid    = 'footer-right-sidebar';
} 
?>

<?php if ( 'both' === $sidebar_pos ) : ?>
	<div class="col-2 <?=$sideclass?>" id="<?=$sideid?>">
<?php else : ?>
	<div class="col-4 <?=$sideclass?>" id="<?=$sideid?>">
<?php endif; ?>

<?php if($args['menu']) : ?>

    <?php if($args['menu'] == 'mainMenu') : ?>
        <button class="navbar-toggler close togglemenu" 
            type="button" 
            aria-controls="mainMenuWrapper" 
            aria-hidden="true" 
            aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>"
        >
        </button>

        <button type="button" 
            class="btn btn-search btn-search-white navbar-button">
        </button>
    <?php elseif($args['menu'] == 'contactMenu') : ?>
        <button class="navbar-toggler contact" 
            type="button" 
            aria-controls="mainMenuWrapper" 
            aria-hidden="true" 
            aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>"
        >
        </button>
    <?php elseif($args['menu'] == 'searchMenu') : ?>
        <button class="navbar-toggler search togglemenu" 
            type="button" 
            aria-controls="mainMenuWrapper" 
            aria-hidden="true" 
            aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>"
        >
        </button>

        <button class="navbar-toggler close closesearch" 
            type="button" 
            aria-controls="mainMenuWrapper" 
            aria-hidden="true" 
            aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>"
        >
        </button>
    <?php endif; ?>

<?php elseif($args['modal']) : ?>
    <button class="navbar-toggler member-close" 
        type="button" 
        data-bs-dismiss="modal"
        data-inactivate-carousel="carouselPeople"
        aria-label="<?php esc_attr_e( 'Close modal', 'codigo' ); ?>"
    >
    </button>

    <button class="bs-carousel carousel-control-prev" 
        type="button" 
        data-bs-target="#carouselPeople" 
        data-bs-slide="prev"
    >
        <span class="carousel-control-prev-icon" 
            aria-hidden="true"
        ></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="bs-carousel carousel-control-next"
            type="button" 
            data-bs-target="#carouselPeople" 
            data-bs-slide="next"
    >
        <span class="carousel-control-next-icon" 
            aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
<?php elseif($args['footer']) : ?>
    <?php //empty ?>
<?php else : ?>
    <?php //dynamic_sidebar( 'right-sidebar' );?>
<?php endif; ?>

</div><!-- #<?=$sideid?> -->
