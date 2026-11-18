<?php
if ( ! defined( 'ABSPATH' ) ) exit;

final class SDHBT_Admin {

	/** @var SDHBT_Admin|null */
	private static $instance = null;

	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
    add_action( 'admin_init', [$this, 'register_settings']);
		add_filter( 'plugin_action_links_' . SDHBT_BASENAME, array( $this, 'add_settings_link' ) );
	}

  public function add_menu() {
		add_options_page(
			__( 'SD Hubspot', 'sd-hubspot' ),
			__( 'SD Hubspot', 'sd-hubspot' ),
			'manage_options',
			'sdhbt-settings',
			array( $this, 'render_page' )
		);
	}

  public function register_settings() {

    // Register the group + options (this is what options.php checks)
    register_setting(
      'sdhbt-settings-group',
      'hubspot_form_guid',
      [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
      ]
    );

    register_setting(
      'sdhbt-settings-group',
      'hubspot_portal_id',
      [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
      ]
    );

    //Firstname → Surname → Company name → Email address.
    register_setting(
      'sdhbt-settings-group',
      'sdhbt_firstname_placeholder',
      [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
      ]
    );
    register_setting(
      'sdhbt-settings-group',
      'sdhbt_surname_placeholder',
      [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
      ]
    );
    register_setting(
      'sdhbt-settings-group',
      'sdhbt_company_placeholder',
      [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
      ]
    );
    register_setting(
      'sdhbt-settings-group',
      'sdhbt_email_placeholder',
      [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
      ]
    );
    register_setting(
      'sdhbt-settings-group',
      'sdhbt_optin_label',
      [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
      ]
    );
    register_setting(
      'sdhbt-settings-group',
      'sdhbt_marketing_opt_in',
      [
        'type'              => 'integer',
        'sanitize_callback' => 'absint',
        'default'           => 0,
      ]
    );

    register_setting(
      'sdhbt-settings-group',
      'sdhbt_send_label',
      [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
      ]
    );

    register_setting(
      'sdhbt-settings-group',
      'sdhbt_download_label',
      [
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
        'default'           => '',
      ]
    );
  }

  public function add_settings_link( $links ) {
		$url = admin_url( 'options-general.php?page=sdhbt-settings' );
		$links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'Settings', 'sd-hubspot' ) . '</a>';
		return $links;
	}

	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		echo '<div class="wrap">';
		echo '<h1>' . esc_html__( 'SD Hubspot', 'sd-hubspot' ) . '</h1>';
		echo '</div>';

    echo '<form method="post" action="options.php">';
    settings_fields( 'sdhbt-settings-group' );

    echo '<h3>' . esc_html__( 'Hubspot settings', 'sd-hubspot' ) . '</h3>';
    echo '<table class="form-table">';
    echo '<tr valign="top">';
    echo '<th scope="row">' . esc_html__( 'Hubspot Form GUID', 'sd-hubspot' ) . '</th>';
    echo '<td><input type="text" name="hubspot_form_guid" value="' . esc_attr( get_option('hubspot_form_guid') ) . '" /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">' . esc_html__( 'Hubspot Portal ID', 'sd-hubspot' ) . '</th>';
    echo '<td><input type="text" name="hubspot_portal_id" value="' . esc_attr( get_option('hubspot_portal_id') ) . '" /></td>';
    echo '</tr>';
    echo '</table>';

    echo '<h3>' . esc_html__( 'Form settings', 'sd-hubspot' ) . '</h3>';
    echo '<table class="form-table">';
    echo '<tr valign="top">';
    echo '<th scope="row">' . esc_html__( 'First Name Placeholder', 'sd-hubspot' ) . '</th>';
    echo '<td><input type="text" name="sdhbt_firstname_placeholder" placeholder="Enter your first name" value="' . esc_attr( get_option('sdhbt_firstname_placeholder') ) . '" /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">' . esc_html__( 'Surname Placeholder', 'sd-hubspot' ) . '</th>';
    echo '<td><input type="text" name="sdhbt_surname_placeholder" placeholder="Enter your surname" value="' . esc_attr( get_option('sdhbt_surname_placeholder') ) . '" /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">' . esc_html__( 'Company Placeholder', 'sd-hubspot' ) . '</th>';
    echo '<td><input type="text" name="sdhbt_company_placeholder" placeholder="Enter your company" value="' . esc_attr( get_option('sdhbt_company_placeholder') ) . '" /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">' . esc_html__( 'Email Placeholder', 'sd-hubspot' ) . '</th>';
    echo '<td><input type="text" name="sdhbt_email_placeholder" placeholder="Enter your email" value="' . esc_attr( get_option('sdhbt_email_placeholder') ) . '" /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
      echo '<td colspan="2" style="padding:0">';
      echo '<table>';
      echo '<tr valign="top">';
      echo '<th scope="row" colspan="4">' . esc_html__( 'Marketing Information', 'sd-hubspot' ) . '</th>';
      echo '</tr>';
      echo '<tr valign="top">';
      echo '<th scope="row" style="max-width: 30px;">' . esc_html__( 'Label', 'sd-hubspot' ) . '</th>';
      echo '<td style="min-width: 300px;"><input type="text" style="width:100%" name="sdhbt_optin_label" placeholder="Enter the newsletter label" value="' . esc_attr( get_option('sdhbt_optin_label') ) . '" /></td>';
      echo '<th scope="row" style="max-width: 30px;">' . esc_html__( 'ID', 'sd-hubspot' ) . '</th>';
      echo '<td style="min-width: 300px;"><input type="text" style="width:100%"  name="sdhbt_marketing_opt_in" placeholder="Enter the Marketing Information ID" value="' . esc_attr( get_option('sdhbt_marketing_opt_in') ) . '" /></td>';
      echo '</tr>';
      echo '</table>';
      echo '</td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">' . esc_html__( 'Send label', 'sd-hubspot' ) . '</th>';
    echo '<td><input type="text" name="sdhbt_send_label" placeholder="Send" value="' . esc_attr( get_option('sdhbt_send_label') ) . '" /></td>';
    echo '</tr>';
    echo '<tr valign="top">';
    echo '<th scope="row">' . esc_html__( 'Download label', 'sd-hubspot' ) . '</th>';
    echo '<td><input type="text" name="sdhbt_download_label" placeholder="Download PDF" value="' . esc_attr( get_option('sdhbt_download_label') ) . '" /></td>';
    echo '</tr>';
    echo '</table>';

    submit_button();
    echo '</form>';
	}

}
