# SD HubSpot – PDF Download Gate

A plugin to connect to HubSpot API. 


[![version](https://img.shields.io/badge/version-1.0.7-blue.svg)](https://semver.org)
[![PHP Version](https://img.shields.io/badge/PHP-%3E=7.2-green.svg)](https://www.php.net/)



## Description

**SD HubSpot** is a lightweight WordPress plugin that connects a custom email capture form to HubSpot and reveals gated content after successful submission.

It allows you to:

* Collect user email addresses
* Send submissions directly to a HubSpot Form (via the HubSpot Forms API)
* Reveal a downloadable PDF after confirmation
* Keep the user on the same page (AJAX – no reload)
* Work with HubSpot **Free Tools** (no Private App required)

This plugin is ideal for:

* Lead magnets
* Whitepaper downloads
* Brochure downloads
* Gated PDF content
* Simple marketing funnels


## Packaging and Upload

* Run python `build-plugin.py` to create a zip file of the plugin.
  
```bash
cd wp-content/plugins/sd-hubspot
python build-plugin.py
#or
python3 build-plugin.py
...
✅ Production ZIP created at:
wp-content/plugins/sd-hubspot/dist/sd-hubspot.zip
```

* Upload the zip file to your WordPress site via the Plugins > Add New > Upload Plugin interface.


## How It Works

1. User enters their email address.
2. The form submits via AJAX to WordPress.
3. WordPress sends the data to HubSpot using the official Forms API.
4. If HubSpot confirms the submission, a download button appears instantly.

No page reload. No external embed scripts.


## Features

* ✅ HubSpot Free compatible
* ✅ Uses official HubSpot Forms API
* ✅ AJAX submission (no page refresh)
* ✅ Admin settings for Portal ID and Form GUID
* ✅ Built-in spam honeypot
* ✅ Clean shortcode-based implementation
* ✅ Optional secure download with one-time token (if implemented)


## Admin Settings

Under the plugin settings page, you can configure:

* **HubSpot Portal ID**
* **HubSpot Form GUID**

These values connect your WordPress form to your HubSpot account.


## Requirements

* WordPress 5.8+
* PHP 7.4+
* HubSpot Free or higher
* A published HubSpot Form


## Security Notes

* Server-side email validation
* Nonce verification
* Spam honeypot protection
* No API keys exposed
* Compatible with GDPR consent fields (if configured in HubSpot)


## Use Cases

* Marketing lead capture
* Resource downloads
* Newsletter signups with gated content
* Campaign landing pages


## Usage

1. Copy the `sd-hubspot` folder to your WordPress `wp-content/plugins` directory.
2. Activate the plugin from the WordPress admin dashboard.
3. Fill in the required HubSpot API details in the plugin settings page.
4. Use the provided shortcode `[sd_hubspot_form]` to display the form on any page or post.

## Shortcode Usage

Example:

```
[hubspot_pdf_gate
  pdf_url="https://your-site.com/wp-content/uploads/your-file.pdf"
  button_text="Download the PDF"
]
```

The Portal ID and Form GUID are pulled from the plugin settings.

---

## Functionality

The form data is first sent to WordPress via the REST API (JS functions) , where it is validated and then submitted to HubSpot via PHP.

The submission flow works as follows:

1. **The user fills in the form and submits it.**

   * Form markup: `includes/class-plugin.php`

2. **JavaScript collects the form data and sends it to the WordPress REST API.**

   * `assets/hubpdf-gate.js`
   * Request: `await fetch(HUBPDF.restUrl, {...})`

3. **WordPress receives and validates the submitted data.**

   * `includes/class-plugin.php`
   * REST endpoint: `register_rest_routes()`

4. **If the data is valid, WordPress sends the form submission to the HubSpot Forms API using `wp_remote_post()`.**

   * `includes/class-plugin.php`
   * HubSpot request: `wp_remote_post()`

5. **WordPress waits for HubSpot's response and retrieves the response status and data.**

   * `includes/class-plugin.php`
   * Response handling: `wp_remote_retrieve_*()` functions

6. **WordPress returns the result to the JavaScript request.**

   * A successful HubSpot submission returns an HTTP `200` response.
   * HubSpot or validation errors are returned with the corresponding error response.

7. **JavaScript handles the response.**

   * `assets/hubpdf-gate.js`
   * Success: `if (response.ok) {...}`
   * On success, the form displays the PDF download button.
   * On error, the form is displayed again with the relevant error message.

---

## Development

For a more detailed explanation of how the plugin is structured and how the code works, see:

[Development](DEVELOPMENT.md)

---


## Changelog

The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

### [1.0.1] - 2026-02-24

#### Updated

- Updated the README with more detailed explanations about the singleton pattern and its use in WordPress plugins.


### [1.0.0] - 2026-02-24

#### Added
- Initial release with basic plugin structure and functionality.


## Copyright and License

This plugin is released under the [GPL2 or later](https://www.gnu.org/licenses/gpl-2.0.html) license.

## Versioning

We use [Semantic Versioning (SemVer)](https://semver.org/) for versioning.
