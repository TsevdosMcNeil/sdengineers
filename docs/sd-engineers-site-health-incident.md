# SD Engineers — WordPress Site Health Fatal Error

**Date:** 18 November 2026  
**Project:** SD Engineers  
**Hosting:** WP Engine  
**Environments:** Staging and production  
**Status:** Fixed and verified on staging; production deactivation planned (not yet confirmed)

## Summary

Opening **Tools → Site Health → Info** caused a PHP fatal error on WP Engine staging and production. Investigation identified an invalid callback registered by the **WP Engine Automated Migration** plugin (`wp-site-migrate`). Deactivating that plugin on staging restored Site Health.

## Environments

- Local (Docker): `https://sdengineers.localhost:8443/`
- Staging: `https://sdengineersstg.wpengine.com/`
- Production: `https://sd-engineers.com/`
- Repository: `https://github.com/pablorica/sdengineers`

## Error

```text
PHP Fatal error: Uncaught TypeError: call_user_func_array():
Argument #1 ($callback) must be a valid callback,
class WPEWPAdmin does not have a method "handlePluginHealthInfo"
```

The error occurred while WordPress applied the `debug_information` filter in the Site Health information screen.

## Investigation

### 1. Attempted SSH access to WP Engine staging

Generated a dedicated Ed25519 SSH key and attempted to connect to the WP Engine SSH gateway. Public-key authentication succeeded, but the gateway denied installation access:

```text
Authenticated to sdengineersstg.ssh.wpengine.net using "publickey".
Cannot access install sdengineersstg
```

This was a separate installation-access issue, not the cause of the Site Health fatal error. The dashboard exposed SFTP connection details, so the investigation continued using downloaded files and a temporary diagnostic MU plugin.

### 2. Inspected WP Engine MU plugins

Downloaded the staging `wp-content/mu-plugins/` directory and searched for the missing callback and class:

```bash
grep -RInE 'class WPEWPAdmin|WPEWPAdmin|handlePluginHealthInfo' \
  ~/Dockers/sdengineers/wp-content/mu-plugins/
```

No matches were found. A search for Site Health-related hooks identified `wpengine-common/site-health.php`, which registered a separate anonymous `debug_information` callback; this was not the failing callback.

### 3. Checked local WordPress callbacks

Used WP-CLI in the Docker environment to inspect `debug_information` callbacks:

```bash
docker compose run --rm wpcli eval '
global $wp_filter;
if ( isset( $wp_filter["debug_information"] ) ) {
    foreach ( $wp_filter["debug_information"]->callbacks as $priority => $callbacks ) {
        foreach ( $callbacks as $callback ) {
            var_dump( $priority, $callback["function"] );
        }
    }
}'
```

The local environment showed an ACF Site Health callback but did not reproduce the failing WP Engine callback.

### 4. Inspected callbacks on staging

A **temporary, administrator-only diagnostic MU plugin** was uploaded to staging to inspect the loaded `WPEWPAdmin` class and registered `debug_information` callbacks.

The diagnostic identified:

```text
WPEWPAdmin class:
Defined in: /nas/content/live/sdengineersstg/wp-content/plugins/wp-site-migrate/wp_admin.php
handlePluginHealthInfo exists: NO

Registered debug_information callbacks:
Priority 10: Closure or other callback
Priority 10: ACF\Site_Health\Site_Health::render_tab_content
Priority 10: WPEWPAdmin::handlePluginHealthInfo
```

This established that the **WP Engine Automated Migration** plugin supplied the class, while a callback referred to a method absent from that class. The exact reason for the mismatch (for example, plugin bug or inconsistent files) was not determined.

## Resolution

1. Located **WP Engine Automated Migration** under **Plugins → Installed Plugins** on staging.
2. **Deactivated** the plugin; it was not deleted.
3. Reopened **Tools → Site Health → Info**.
4. **Confirmed that Site Health worked again** without the fatal error.

This confirms the migration plugin was responsible for the staging failure. No WordPress core, theme, or WP Engine-managed MU-plugin files needed modification.

## Production follow-up

- [ ] Confirm no migration is currently running or planned that depends on WP Engine Automated Migration.
- [ ] Deactivate **WP Engine Automated Migration** on production.
- [ ] Verify **Tools → Site Health → Info** works on production.
- [ ] Check the front end and WordPress admin for regressions.
- [ ] Remove the temporary staging diagnostic MU plugin: `wp-content/mu-plugins/debug-wpe-site-health.php`.
- [ ] Update this record with the production verification result and date.

## Notes

- **WP Engine Automated Migration** is intended for site migrations and is generally unnecessary for routine site operation after migration, but confirm there is no active migration before deactivating it.
- The SSH gateway's post-quantum key-exchange warning was unrelated to the PHP fatal error.
- The SSH gateway installation-access denial remains a separate issue if shell/WP-CLI access is needed later.
- Avoid modifying WP Engine-managed plugin code to work around this error when deactivation resolves it and migration functionality is not needed.
