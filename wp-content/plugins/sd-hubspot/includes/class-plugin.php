<?php
if (! defined('ABSPATH')) exit;

final class SDHBT_Plugin
{

  /** @var SDHBT_Plugin|null */
  private static $instance = null;

  public static function instance()
  {
    if (null === self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  private function __construct()
  {
    // Front-end hooks
    add_action('wp_enqueue_scripts', array($this, 'enqueue_front'));

    // shortcode
    add_shortcode('hubspot_pdf_gate', array($this, 'shortcode_pdfgate'));

    // REST route HOLA
    add_action('rest_api_init', array($this, 'register_rest_routes'));
  }

  public function enqueue_front()
  {
    // Only enqueue when shortcode exists
    if (! shortcode_exists('hubspot_pdf_gate')) {
      return;
    }

    wp_enqueue_script(
      'hubpdf-gate',
      SDHBT_URL . '/assets/hubpdf-gate.js',
      [],
      SDHBT_Hubspot::VERSION,
      true
    );

    //Enqueue styles
    wp_enqueue_style(
      'hubpdf-gate-style',
      SDHBT_URL . '/assets/hubpdf-gate.css',
      [],
      SDHBT_Hubspot::VERSION
    );
  }

  /**
   * Shortcode handler for [hubspot_pdf_gate pdf_url="" button_text=""]
   */
  public function shortcode_pdfgate(
    $atts = array(),
    $content = ''
  ) {
    $atts = shortcode_atts(
      [
        'pdf_url'   => '',
        'button_text' => (esc_attr(get_option('sdhbt_download_label')) ?? 'Download PDF'),
      ],
      $atts,
      'hubspot_pdf_gate'
    );

    $form_guid = esc_attr(get_option('hubspot_form_guid'));
    $portal_id = esc_attr(get_option('hubspot_portal_id'));
    $subscription_type_id = esc_attr(get_option('sdhbt_marketing_opt_in')) ?? 0;
    $optin_label = esc_attr(get_option('sdhbt_optin_label')) ?? 'Opt in to email updates from us';

    if (! $form_guid || ! $portal_id) {
      return '<p><strong>HubSpot form not configured. Please fill in the required HubSpot API details in the plugin settings page.</strong></p>';
    }

    if (! $atts['pdf_url']) {
      return '<p><strong>Missing pdf file.</strong></p>';
    }



    wp_localize_script(
      'hubpdf-gate',
      'HUBPDF',
      [
        'restUrl'   => rest_url('hubpdf/v1/submit'),
        'nonce'     => wp_create_nonce('wp_rest'),
        'portalId'  => $portal_id,
        'formGuid'  => $form_guid,
        'subscriptionTypeId' => $subscription_type_id,
        'consentText' => esc_html($optin_label),
        'pdfUrl'      => $atts['pdf_url'],
        'buttonText' => $atts['button_text'],
      ]
    );

    //Firstname → Surname → Company name → Email address.
    $firstname_placeholder = esc_attr(get_option('sdhbt_firstname_placeholder')) ?? 'First Name';
    $surname_placeholder = esc_attr(get_option('sdhbt_surname_placeholder')) ?? 'Surname';
    $company_placeholder = esc_attr(get_option('sdhbt_company_placeholder')) ?? 'Company Name';
    $email_placeholder = esc_attr(get_option('sdhbt_email_placeholder')) ?? 'Email Address';
    $send_label = esc_attr(get_option('sdhbt_send_label')) ?? 'Send';

    ob_start(); ?>

    <div class="hubpdf-wrapper">
      <a class="hubpdf-openform wp-block-file__button wp-element-button">
        <?php echo esc_html($atts['button_text']); ?>
      </a>

      <div class="hubpdf-inner-wrapper">

        <div class="hubpdf-content">

          <form class="hubpdf-form">
            <input
              class="hubpdf-text hubpdf-firstname"
              type="text"
              name="firstname"
              placeholder="<?php echo esc_attr($firstname_placeholder); ?>"
              required />
            <input
              class="hubpdf-text hubpdf-surname"
              type="text"
              name="surname"
              placeholder="<?php echo esc_attr($surname_placeholder); ?>"
              required />
            <input
              class="hubpdf-text hubpdf-company"
              type="text"
              name="company"
              placeholder="<?php echo esc_attr($company_placeholder); ?>" />
            <input
              class="hubpdf-text hubpdf-email"
              type="email"
              name="email"
              placeholder="<?php echo esc_attr($email_placeholder); ?>"
              required />
            <label class="hubpdf-optin">
            <input
              type="checkbox"
              name="marketing_opt_in"
              class="hubpdf-optin-input"
            />

            <span class="hubpdf-optin-label">
              <?php echo esc_html($optin_label); ?>
            </span>
          </label>

            <button type="submit" class="hubpdf-submit wp-block-file__button wp-element-button"><?php echo esc_attr($send_label); ?></button>
          </form>

          <div
            class="hubpdf-result"
            style="display:none;">
            <p class="hubpdf-msg">Sending...</p>
            <a class="hubpdf-download wp-block-file__button wp-element-button"

              target="_blank"
              rel="noopener">Download</a>
            <a class="hubpdf-close wp-block-file__button wp-element-button"></a>
          </div>
        </div>
      </div>
    </div>

<?php
    return ob_get_clean();
  }

  public function register_rest_routes()
  {
    register_rest_route(
      'hubpdf/v1',
      '/submit',
      [
        'methods'  => 'POST',
        'permission_callback' => '__return_true',
        'callback' => function ($request) {

          $firstname = sanitize_text_field($request->get_param('firstname'));
          $surname   = sanitize_text_field($request->get_param('surname'));
          $company   = sanitize_text_field($request->get_param('company'));
          $email     = sanitize_email($request->get_param('email'));
          $marketingOptIn = ! empty( $request->get_param('marketingOptIn'));
          $consentText = sanitize_text_field($request->get_param('consentText'));
          $portalId  = sanitize_text_field($request->get_param('portalId'));
          $formGuid  = sanitize_text_field($request->get_param('formGuid'));
          $subscriptionTypeId = sanitize_text_field($request->get_param('subscriptionTypeId'));


          if (empty($firstname) || empty($surname) || empty($email)) {
            // error_log("Firstname:". $firstname);
            // error_log("Surname:". $surname);
            // error_log("Email:". $email);
            $required_fields_msg = "Missing required fields: ";
            if (empty($firstname)) {
              $required_fields_msg .= ' "First name"';
            }
            if (empty($surname)) {
              $required_fields_msg .= ' "Surname"';
            }
            if (empty($email)) {
              $required_fields_msg .= ' "Email"';
            }
            return new WP_REST_Response(['message' => $required_fields_msg], 400);
          }
          if (!is_email($email)) {
            return new WP_REST_Response(['message' => 'Invalid email'], 400);
          }
          if (empty($portalId) || empty($formGuid)) {
            return new WP_REST_Response(['message' => 'Missing HubSpot credentials'], 401);
          }

          $endpoint = "https://api.hsforms.com/submissions/v3/integration/submit/{$portalId}/{$formGuid}";

          $payload = [
            'fields' => [
              [
                'name'  => 'email',
                'value' => $email,
              ],
              [
                'name'  => 'firstname',
                'value' => $firstname,
              ],
              [
                'name'  => 'lastname',
                'value' => $surname,
              ],
              [
                'name'  => 'company',
                'value' => $company,
              ],
            ],
            "legalConsentOptions" => [
              "consent" => [
                "consentToProcess" => true,
                "text" => "Consent text",
                "communications" => [
                  [
                    "value" => $marketingOptIn,
                    "subscriptionTypeId" => $subscriptionTypeId,
                    "text" => $consentText,
                  ]
                ],
              ]
            ],
            'context' => [
              'pageUri' => esc_url_raw(
                $request->get_param('pageUri')
              ),
              'pageName' => sanitize_text_field(
                $request->get_param('pageName')
              ),
            ],
          ];

          $response = wp_remote_post(
            $endpoint,
            [
              'headers' => [
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json',
              ],
              'body'    => wp_json_encode($payload),
              'timeout' => 15,
            ]
          );

          if (is_wp_error($response)) {
            return new WP_REST_Response(
              [
                'message' => 'HubSpot connection failed',
                'error'   => [
                  'code'    => $response->get_error_code(),
                  'message' => $response->get_error_message(),
                  'data'    => $response->get_error_data(),
                ],
                'debug' => [
                  'endpoint' => $endpoint,
                  'payload'  => $payload,
                ],
              ],
              500
            );
          }

          $hubspot_status  = wp_remote_retrieve_response_code($response);
          $hubspot_message = wp_remote_retrieve_response_message($response);
          $hubspot_body    = wp_remote_retrieve_body($response);
          $hubspot_headers = wp_remote_retrieve_headers($response);

          $decoded_body = json_decode($hubspot_body, true);

          if (json_last_error() !== JSON_ERROR_NONE) {
            $decoded_body = $hubspot_body;
          }

          $response_headers = [];

          foreach ($hubspot_headers as $header_name => $header_value) {
            $response_headers[$header_name] = $header_value;
          }

          /*
          * Log server-side as well.
          *
          * Do not leave personal data logging enabled permanently
          * on production.
          */
          // error_log(
          //   'HubSpot submission response: ' .
          //     wp_json_encode(
          //       [
          //         'status'  => $hubspot_status,
          //         'message' => $hubspot_message,
          //         'body'    => $decoded_body,
          //         'headers' => $response_headers,
          //         'payload' => $payload,
          //       ]
          //     )
          // );

          if ($hubspot_status < 200 || $hubspot_status >= 300) {
            return new WP_REST_Response(
              [
                'message' => 'HubSpot rejected submission',
                'hubspot' => [
                  'status'        => $hubspot_status,
                  'statusMessage' => $hubspot_message,
                  'body'          => $decoded_body,
                  'headers'       => $response_headers,
                ],
                'debug' => [
                  'endpoint' => $endpoint,
                  'payload'  => $payload,
                ],
              ],
              $hubspot_status
            );
          }

          return new WP_REST_Response(
            [
              'message' => 'Success',
              'hubspot' => [
                'status'        => $hubspot_status,
                'statusMessage' => $hubspot_message,
                'body'          => $decoded_body,
                'headers'       => $response_headers,
              ],
            ],
            200
          );
        }
      ]
    );
  }
}
