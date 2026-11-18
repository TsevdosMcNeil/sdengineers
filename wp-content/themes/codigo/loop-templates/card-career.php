<?php
/**
 * Post rendering content according to caller of get_template_part
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;


//error_log(print_r($args,true));
extract(wp_parse_args(
    $args,
    array(
        'accordion_id' => 'accordionCareers',
    )
));

//error_log(print_r($orientation,true));

?>
<div 
    <?php post_class('accordion-item accordion-item-career'); ?> 
    id="post-<?php the_ID(); ?>"
>
    <h3 class="accordion-header" 
        id="careerHeading_<?php the_ID(); ?>"
    >
        <button class="accordion-button collapsed" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#collapse_<?php the_ID(); ?>" 
            aria-expanded="true" 
            aria-controls="collapse_<?php the_ID(); ?>"
        >
            <?php the_title() ?>
        </button>
    </h3>
    <div id="collapse_<?php the_ID(); ?>" 
    class="accordion-collapse collapse" 
    aria-labelledby="careerHeading_<?php the_ID(); ?>" 
    data-bs-parent="<?=$accordion_id?>">
        <div class="accordion-body">
            <?php the_content() ?>
        </div>
    </div>
</div><!-- #career-## -->

