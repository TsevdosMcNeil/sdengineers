<?php

/**
 * Understrap tempalte for ACF Block Name: Member Card
 * php version 7.4.1
 *
 * @category   Category
 * @package    Understrap
 * @subpackage Codigo
 * @author     Pablo Rica <pablo@codigo.co.uk>
 * @license    MIT
 * @version    GIT: @1.0.0@
 * @link       link
 * @since      Codigo 0.0.82
 */
// Exit if accessed directly.
defined('ABSPATH') || exit;

// Exit if ACF is not enabled.
if (!function_exists('get_field')) {
    exit;
}


if (get_field('gblock_lsbh_show')):

$image = get_field('gblock_lsbh_image');
$title = get_field('gblock_lsbh_title');
$link = get_field('gblock_lsbh_link');


$mobile_bg_colour   = get_field('gblock_lsbh_mobile_bg_colour') ?? '#FFF';
$mobile_text_colour = get_field('gblock_lsbh_mobile_text_colour') ?? 'black';


$size = 'thumbnail';


$block_class = "sidebar-block-wrapper";
if($image) {
    $block_class .= " large";
}

$sidebar_tag = "div";
if($link) {
    $sidebar_tag = "a";
}
?>
<div class="<?=$block_class?>">
    <<?=$sidebar_tag?>
        class="sidebar-block"
        style="
          --mobile-bg-color: <?=$mobile_bg_colour?>;
          --mobile-text-color: <?=$mobile_text_colour?>;
        "
        <?php echo ($link ? 'href="'.$link.'"' : ''); ?>
    >
        <?php if($image):  ?>
            <figure  class="ratio ratio-3x2 mb-2" >
                <?php
                echo wp_get_attachment_image(
                    $image,
                    $size,
                    '',
                    array()
                );
                ?>
            </figure>
        <?php else: ?>
            <div class="sidebar-block_image-placeholder py-8 md-hidden"></div>
        <?php endif; ?>
        <?php if($title) { ?>
            <p class="sidebar-block_title"><?=$title?></p>
        <?php } ?>
        <?php if($link) { ?>
            <div class="btn btn-primary sidebar-block_link fill-<?=$mobile_text_colour?>"></div>
        <?php } ?>
    </<?=$sidebar_tag?>>
</div>
<?php
else:
?>
<div class="acf-block-empty sidebar-block-empty"></div>
<?php
endif;
