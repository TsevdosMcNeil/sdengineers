<?php

/**
 * Plugin Name: Custom WP Engine Site Health diagnostic.
 * Version: 1.0.0
 * Requires PHP: 7.2
 * Plugin URI: https://codigo.co.uk/
 * Description: Temporary WP Engine Site Health diagnostic.
 * Author: Pablo Rica
 * Author URI: https://codigo.co.uk/team/pablo-rica
 * License: GPL2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Remove after testing.
 */

add_action('admin_init', function () {
  if (
    ! current_user_can('manage_options') ||
    ! isset($_GET['debug_wpe_health'])
  ) {
    return;
  }

  global $wp_filter;

  header('Content-Type: text/plain; charset=utf-8');

  echo "WPEWPAdmin class:\n";

  if (class_exists('WPEWPAdmin')) {
    $reflection = new ReflectionClass('WPEWPAdmin');

    echo "Defined in: " . $reflection->getFileName() . "\n";
    echo "handlePluginHealthInfo exists: ";
    echo method_exists('WPEWPAdmin', 'handlePluginHealthInfo')
      ? "YES\n"
      : "NO\n";
  } else {
    echo "Class not loaded\n";
  }

  echo "\nRegistered debug_information callbacks:\n";

  if (isset($wp_filter['debug_information'])) {
    foreach ($wp_filter['debug_information']->callbacks as $priority => $callbacks) {
      foreach ($callbacks as $callback) {
        $function = $callback['function'];

        if (is_array($function)) {
          $target = is_object($function[0])
            ? get_class($function[0])
            : $function[0];

          echo "Priority {$priority}: {$target}::{$function[1]}\n";
        } elseif (is_string($function)) {
          echo "Priority {$priority}: {$function}\n";
        } else {
          echo "Priority {$priority}: Closure or other callback\n";
        }
      }
    }
  }

  exit;
});
