<?php
/**
 * Header Navbar Logo
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


extract(wp_parse_args(
    $args,
    array(
        'logoclass' => ''
    )
));

?>


<!-- Your site title as branding in the menu -->
<?php if ( ! has_custom_logo() ) { 

    $svglogo = '<svg version="1.1" 
    xmlns="http://www.w3.org/2000/svg" 
    xmlns:xlink="http://www.w3.org/1999/xlink" 
    x="0px" 
    y="0px"
    viewBox="0 0 115 85.6" 
    enable-background="new 0 0 115 85.6" 
    xml:space="preserve"
>
    <g>
        <g>
            <path d="M25.2,50.4C11.3,50.4,0,39.1,0,25.2C0,11.3,11.3,0,25.2,0v15.7c-5.2,0-9.5,4.3-9.5,9.5c0,5.2,4.3,9.5,9.5,9.5V50.4z"/>
            <path d="M24.8,85.6c-8.1,0-16.2-3.1-22.4-9.3l11.1-11.1c3,3,7,4.7,11.3,4.7s8.3-1.7,11.3-4.7c3-3,4.7-7,4.7-11.3 c0-4.3-1.7-8.3-4.7-11.3l11.1-11.1c6,6,9.3,13.9,9.3,22.4c0,8.5-3.3,16.4-9.3,22.4C41.1,82.5,33,85.6,24.8,85.6"/>
        </g>
        <path d="M72.3,85.5V69.8c14.9,0,27-12.1,27-27s-12.1-27-27-27V0C95.8,0,115,19.2,115,42.7C115,66.3,95.8,85.5,72.3,85.5"/>
    </g>
</svg>';?>

<?php if ( is_front_page() && is_home() ) : ?>

    <h1 class="navbar-brand mb-0 <?=$logoclass?>">
        <a rel="home" 
            href="<?php echo esc_url( home_url( '/' ) ); ?>" 
            itemprop="url"
        >    
            <?=$svglogo?>
        </a>
    </h1>

<?php else : ?>
    <a class="navbar-brand <?=$logoclass?>" 
        rel="home" 
        href="<?php echo esc_url( home_url( '/' ) ); ?>" 
        itemprop="url"
    >
        <?=$svglogo?>
    </a>

<?php endif; ?>

<?php
} else {
    the_custom_logo();
}
?>
<!-- end custom logo -->
