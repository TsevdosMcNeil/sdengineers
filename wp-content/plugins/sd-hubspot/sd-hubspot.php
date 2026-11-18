<?php
/**
 * Plugin Name:       SD hubspot
 * Version:           1.1.0
 * Requires           PHP: 7.2
 * Plugin URI:        https://codigo.co.uk/
 * Description:       A plugin to connect to HubSpot API
 * Author:            Pablo Rica
 * Author URI:        https://codigo.co.uk/team/pablo-rica
 * Text Domain:       sd-hubspot
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


final class SDHBT_Hubspot {

	/** @var SDHBT_Hubspot|null */
	private static $instance = null;

	/** Plugin version */
	const VERSION = '1.1.0';

	/** Singleton */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		$this->define_constants();

		// Load files
		$this->includes();

		// Hooks
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		add_action( 'plugins_loaded', array( $this, 'init' ) );
	}

	private function define_constants() {
		if ( ! defined( 'SDHBT_FILE' ) ) {
			define( 'SDHBT_FILE', __FILE__ );
		}
		if ( ! defined( 'SDHBT_DIR' ) ) {
			define( 'SDHBT_DIR', plugin_dir_path( __FILE__ ) );
		}
		if ( ! defined( 'SDHBT_URL' ) ) {
			define( 'SDHBT_URL', plugin_dir_url( __FILE__ ) );
		}
		if ( ! defined( 'SDHBT_BASENAME' ) ) {
			define( 'SDHBT_BASENAME', plugin_basename( __FILE__ ) );
		}
	}

	private function includes() {
		require_once SDHBT_DIR . 'includes/functions.php';
		require_once SDHBT_DIR . 'includes/class-plugin.php';

		if ( is_admin() ) {
			require_once SDHBT_DIR . 'admin/class-admin.php';
		}
	}

	public function init() {
		// Kick off core
		SDHBT_Plugin::instance();

		// Kick off admin
		if ( is_admin() ) {
			SDHBT_Admin::instance();
		}
	}

	public function activate() {
		if ( ! get_option( 'SDHBT_installed_at' ) ) {
			add_option( 'SDHBT_installed_at', time() );
		}
	}

	public function deactivate() {
    if ( ! get_option( 'SDHBT_deactivated_at' ) ) {
			add_option( 'SDHBT_deactivated_at', time() );
		}

    // Plugin options are deleted on uninstall, not deactivation, to allow for reactivation without losing the original install date
	}
}

SDHBT_Hubspot::instance();