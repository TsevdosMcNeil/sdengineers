<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete options you created
delete_option( 'SDHBT_installed_at' );
delete_option( 'hubspot_form_guid' );
delete_option( 'hubspot_portal_id' );
delete_option( 'sdhbt_firstname_placeholder'  );
delete_option( 'sdhbt_surname_placeholder'  );
delete_option( 'sdhbt_company_placeholder'  );
delete_option( 'sdhbt_email_placeholder'  );
delete_option( 'sdhbt_optin_label'  );
delete_option( 'sdhbt_marketing_opt_in'  );
delete_option( 'sdhbt_send_label' );
delete_option( 'sdhbt_download_label' );

if ( get_option( 'SDHBT_deactivated_at' ) ) {
  delete_option( 'SDHBT_deactivated_at' );
}

// If you create CPT data etc, you can clean it here (careful).
