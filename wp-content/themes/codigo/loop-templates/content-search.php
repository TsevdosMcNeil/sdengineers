<?php
/**
 * Search results partial template
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

extract(wp_parse_args(
    $args,
    array(
        'viewport' => 'desktop',
    )
));


$post_type = get_post_type();
if($post_type == 'post') {
	$post_type = 'journal';
}


$permalink = get_permalink();
if($post_type == 'member') {
	$permalink = get_permalink(get_field('general_people_page','options'))."#post-".get_the_ID();
}
if($post_type == 'career') {
	$permalink = get_permalink(get_field('general_careers_page', 'options'))."#post-".get_the_ID();
}

if($viewport == 'mobile'):
?>
<div class="search-mobile-result">
        <h6 class="search-mobile-result--type mb-0">
            <?php echo ucfirst($post_type); ?> 
        </h6>
        <?php
		the_title(
			sprintf( '
            <h5 class="search-mobile-result--title mb-0">
                <a href="%s" 
					rel="bookmark" 
				>', 
				esc_url( $permalink ) 
			),
			'   </a>
            </h5>'
		);
		?>
</div>
<?php endif; 
if($viewport == 'desktop'):
?>
<tr>
	<th scope="row"
		id="post-<?php the_ID(); ?>"
		<?php post_class('col-md-6'); ?>
	>
		<?php
		the_title(
			sprintf( '<a href="%s" 
					rel="bookmark" 
					class="stretched-link"
				>', 
				esc_url( $permalink ) 
			),
			'</a>'
		);
		?>
	</th>
	<td class="col-md-6"><?php echo ucfirst($post_type); ?></td>
</tr><!-- #post-<?php the_ID(); ?> -->
<?php endif; 
