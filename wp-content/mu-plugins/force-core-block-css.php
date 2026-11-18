<?php

/**
 * Plugin Name: Force Core Block CSS to Load Globally
 * Version: 1.0.0
 * Requires PHP: 7.2
 * Plugin URI: https://codigo.co.uk/
 * Description: Ensures that core block CSS is always loaded globally to prevent design regressions.
 * Author: Pablo Rica
 * Author URI: https://codigo.co.uk/team/pablo-rica
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

add_filter( 'should_load_separate_core_block_assets', '__return_false', 100 );
add_filter( 'should_load_block_assets_on_demand', '__return_false', 100 );

add_action( 'wp_enqueue_scripts', function () {
    // Ensure the common block library styles are always enqueued.
    wp_enqueue_style( 'wp-block-library' );
}, 20 );
