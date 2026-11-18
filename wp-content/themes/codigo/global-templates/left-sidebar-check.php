<?php
/**
 * Left sidebar check
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$args = wp_parse_args(
    $args,
    array(
        'menu'   => false,
        'footer' => false,
        'modal' => false,
    )
);

$sidebar_pos = get_theme_mod( 'understrap_sidebar_position' );

$class = 'col content-area content-primary';
$id    = 'primary';
if($args['menu']) {
    $id    = 'menuPrimary';
} 
if($args['footer']) {
    $id    = 'footerPrimary';
} 
if($args['modal']) {
    $id    = 'modalPrimary';
} 

if ( 'left' === $sidebar_pos 
    || 'both' === $sidebar_pos 
) {
    get_template_part( 
        'sidebar-templates/sidebar', 
        'left', 
        $args 
    );
}
?>

    <div id="<?=$id?>" 
        class="<?=$class?>"
    >
