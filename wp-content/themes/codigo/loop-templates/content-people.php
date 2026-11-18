<?php
/**
 * Project post partial template
 *
 * @package Understrap
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

?>

<article <?php post_class(); ?> id="project-<?php the_ID(); ?>">

	<div class="entry-content member-info">
		<?php
		the_content();
		?>
	</div><!-- .entry-content -->

</article><!-- #project-## -->
