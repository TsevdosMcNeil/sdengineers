
# Change Log
All notable changes to this project will be documented in this file.
 
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [1.3.1] - 2026-11-18

### Security

- **Two-factor authentication (2FA):** Configured the WP 2FA plugin to require two-factor authentication for all WordPress users.
  - Enabled authenticator-app (TOTP) and email one-time-code methods, with recovery codes available during setup.
  - Configured a **7-day grace period** for existing users to enrol, followed by mandatory setup before accessing the WordPress dashboard.
  - Enabled setup reminders and prevented users from removing their 2FA configuration.
  - Verified the enrolment flow using an Editor account and prepared a reusable user setup guide.
- **User permissions:** Reduced existing administrator access to Editor where elevated privileges were not currently required, retaining the maintenance administrator account pending confirmation of any additional administrative needs.
- **REST API user enumeration:** Added a custom MU plugin to remove public `/wp/v2/users` routes for unauthenticated requests without disabling the REST API for Gutenberg and ACF.
  - Confirmed that `/wp-json/wp/v2/users` and `/wp-json/wp/v2/users/1` return `404 rest_no_route` when accessed publicly.
  - Verified the alternative REST route and normal Gutenberg editing after deployment.
  - Reviewed author enumeration; the `?author=1` request displayed a blank page, but its HTTP status was not established.

### Documentation

- **Security review report:** Added `SD_Engineers_WordPress_Security_Review_2026-11-18.pdf` to the repository as a record of the WordPress security audit, including user access controls, 2FA, REST API restrictions, plugin review, PHP vulnerability mitigation, hosting checks and backup verification.
  - Documented the scope and limitations of the review, including checks that were not independently verified through WP Engine.

### Maintenance

- **Plugin dependency review:** Reviewed DearFlip Lite, Carousel Slider Block, Modal Block and CoBlocks.
  - Retained **DearFlip Lite** because it is used in the published *The Structural Engineer – March Issue: Retaining Value* article.
  - Retained **Carousel Slider Block** and **Modal Block** to preserve possible legacy content dependencies; no current usage was identified during the manual review.
  - Retained **CoBlocks** because custom features in the original theme depend on it.
  - Confirmed that Carousel Slider Block and Modal Block were on their latest available versions at the time of review.
- **Elementor:** Deactivated and deleted the recently installed page builder, which was not part of the site's original Gutenberg/ACF architecture. Removed the unnecessary plugin to reduce maintenance and security exposure. Existing draft posts were not intentionally deleted; any Elementor-specific layouts in those drafts may no longer be editable or render correctly.

## [1.3.0] - 2026-11-18

### Updated

 - Docker

### Fixed

 - **WordPress Site Health (WP Engine staging):** Resolved a PHP fatal error on **Tools → Site Health → Info** by deactivating the **WP Engine Automated Migration** plugin (`wp-site-migrate`).
   - The plugin registered `WPEWPAdmin::handlePluginHealthInfo` on the `debug_information` filter, but the method did not exist in the loaded `WPEWPAdmin` class.
   - Confirmed the class was defined in `wp-content/plugins/wp-site-migrate/wp_admin.php` using a temporary diagnostic MU plugin.
   - Verified that Site Health loads correctly after deactivation on staging.
   - **Production:** Same deactivation planned; verification not yet recorded.

### Maintenance

 - Investigated WP Engine SSH access: public-key authentication succeeded, but the SSH gateway denied access to the staging installation. Inspected the remote MU plugins using SFTP and checked local WordPress hooks with WP-CLI.
 - Retained `debug-wpe-site-health.php` in the repository for future diagnostics; remove it from the remote staging server after testing. Do not deploy this diagnostic MU plugin to production.
 - No WordPress core or WP Engine-managed plugin files were modified.

## [1.2.0] - 2026-08-31

### Updated

 - [Featured Projects: New layout](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800684001649) 
  - Manage marketing email consent through  **HubSpot Communication Subscriptions**. 


## [1.1.16] - 2026-07-24

### Updated

 - [Featured Projects: New layout](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800684001649) - Avoid truncated title
- 
## [1.1.15] - 2026-07-24

### Updated

 - [PDF Download Link - New form fields](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800684001637) - New checkbox


## [1.1.14] - 2026-07-21

### Fixed

 - Home Logo Lottie animation
  

## [1.1.13] - 2026-07-21

### Updated

 - Alternative Home Video alignment


## [1.1.12] - 2026-07-21

### Fixed

 - Home Lottie animation


## [1.1.11] - 2026-07-15

### Updated

 - [PDF Download Link - New form fields](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800684001637) 
    - Plugin bugs
    - HubSpot rejected submission


## [1.1.10] - 2026-06-30

### Updated

 - Show [Project Thumbnail Video](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800618904507) 
in [Featured Projects](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800684001649) on the Homepage

  
## [1.1.9] - 2026-07-01

### Updated

 - [PDF Download Link - New form fields](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800684001637) - Form CSS



## [1.1.8] - 2026-06-30

### Updated

 - [Project Thumbnail Video](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800618904507) 
- 
## [1.1.7] - 2026-06-30

### Updated

 - [Featured Projects: New layout](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800684001649) 


## [1.1.6] - 2026-06-30

### Updated

 - [PDF Download Link - New form fields](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1214800684001637) 



## [1.1.5] - 2026-05-14

### Updated

 - [Home | Expertise](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1211913817587910) 


## [1.1.4] - 2026-03-19

### Updated

 - [Plugin:  PDF Download Link - Hubspot API](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1212565701596970) - Feedback from designer


## [1.1.3] - 2026-03-19

### Updated

 - [Plugin:  PDF Download Link - Hubspot API](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1212565701596970) - Update styling


## [1.1.2] - 2026-03-20

### Updated

 - [Projects Index page](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1211112918274520)


## [1.1.1] - 2026-03-19

### Updated

 - [Plugin:  PDF Download Link - Hubspot API](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1212565701596970) - Form class



## [1.1.0] - 2026-02-24

### Added

 - [Plugin:  PDF Download Link - Hubspot API](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1212565701596970) 


## [1.0.9] - 2026-02-17

### Updated

 - [Home | Expertise](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1211913817587910) 



## [1.0.8] - 2026-02-17

### Updated

 - [Home | Journal](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1211913817587915) 



## [1.0.7] - 2026-02-17

### Updated

 - [Home | Practice](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1211913817587913) 


## [1.0.6] - 2026-02-17

### Updated

 - [Home | Featured Projects](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1211913817587908) 


## [1.0.5] - 2026-02-16

### Updated

 - [Home | Scroll animation](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1211112918274517) 
  - Scroll animation sped up
 

## [1.0.4] - 2025-12-24

### Updated

 - [Home | Scroll animation](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1211112918274517)
 

## [1.0.3] - 2025-12-24

### Fixed

 - Custom Mu-Plugin to force core block CSS to Load Globally

## [1.0.2] - 2025-10-10

### Updated

- [Mobile | Right-Side Widget](https://app.asana.com/1/355454733208534/project/1203105638447914/task/1211112917393489)


## [1.0.1] - 2024-09-28

### Changed

- [Maintenance](https://app.asana.com/0/1208375910010864/1208375910010867)
	 - Plugins and Core
	 - The latest verion of ACF now automatically escapes unsafe HTML when rendered by the_field or the ACF shortcode. To avoid breaking the template we made updates in the the_field function in the following files:
		- **codigo/global-templates/navbar-contact.php**,
		- **codigo/404.php**,
		- **codigo/footer.php** 
        - **codigo/sidebar-templates/sidebar-left.php** 
   - Spam comments deleted and site updated to avoid new spam

## [1.0.0] 

### Changed

 - Go Live
