
<?php
/**
 * Plugin Name: Restrict Public REST API Users
 * Description: Prevents unauthenticated access to WordPress REST API user endpoints.
 * Version: 1.0.0
 * Requires PHP: 7.2
 * Plugin URI: https://codigo.co.uk/
 * Author: Pablo Rica
 * Author URI: https://codigo.co.uk/team/pablo-rica
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
add_filter('rest_endpoints', function ($endpoints) {

    if (is_user_logged_in()) {
        return $endpoints;
    }

    foreach (array_keys($endpoints) as $route) {
        if (preg_match('#^/wp/v2/users(?:/|$)#', $route)) {
            unset($endpoints[$route]);
        }
    }

    return $endpoints;
});
