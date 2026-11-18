<?php
/**
 * Header Navbar (bootstrap5)
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );
?>

<nav id="main-nav" 
    class="navbar navbar-transparent" 
    aria-labelledby="main-nav-label"
>

	<h2 id="main-nav-label" class="screen-reader-text">
		<?php esc_html_e( 'Main Navigation', 'understrap' ); ?>
	</h2>


	<div class="navbar-container <?php echo esc_attr( $container ); ?>">

		<?php  get_template_part(
            'global-templates/navbar',
            'logo',
            array('logoclass' => 'main-logo')
        ); ?>

		<button id="mainToggler"
            class="navbar-toggler burger ms-auto ms-md-0" 
            type="button" 
            aria-controls="mainMenuWrapper" 
            aria-hidden="false" 
            aria-label="<?php esc_attr_e( 'Toggle navigation', 'understrap' ); ?>"
        >
		</button>
        <?php if(is_search()): ?>
            <button type="button" 
                class="btn btn-search btn-fixed-search navbar-button">
            </button>
        <?php endif; ?>

		<!-- The WordPress Menu goes here -->
        <div id="mainMenuWrapper"
            class="wrapper navigation-wrapper fade show bg-purple"
        >
            <div class="mainmenu-container <?php echo esc_attr( $container ); ?>">
                <div class="row">
                    <!-- Do the left sidebar check -->
			        <?php  
                    $template_args['menu'] = 'mainMenu';
                    get_template_part( 
                        'global-templates/left-sidebar', 
                        'check', 
                        $template_args
                    ); 
                    ?>
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location'  => 'primary',
                            'container_class' => '',
                            'container_id'    => 'navbarNavDropdown',
                            'menu_class'      => 'navbar-nav  ms-auto',
                            'fallback_cb'     => '',
                            'menu_id'         => 'main-menu',
                            'depth'           => 2,
                            'walker'          => new Understrap_WP_Bootstrap_Navwalker(),
                        )
                    );
                    ?>

                    <?php echo do_shortcode('[hiring-button]'); ?>
                    
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

        <?php  get_template_part(
            'global-templates/navbar',
            'contact',
            array()
        ); ?>

        <?php  get_template_part(
            'global-templates/navbar',
            'search',
            array()
        ); ?>

	</div><!-- .navbar-container -->

</nav><!-- .site-navigation -->
