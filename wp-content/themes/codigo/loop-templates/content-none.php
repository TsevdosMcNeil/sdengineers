<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * Learn more: https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<section class="no-results not-found">

	<header class="page-header">

	<p><?php esc_html_e( 'Nothing Found', 'understrap' ); ?></p>

	</header><!-- .page-header -->

	<div class="page-content">

		<?php

		if ( is_search() ) :

			printf(
				'<p>%s<p>',
				esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'understrap' )
			);
			//get_search_form();

		else :

			printf(
				'<p>%s<p>',
				esc_html__( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'understrap' )
			);
			//get_search_form();

		endif;
		?>
	</div><!-- .page-content -->

</section><!-- .no-results -->
