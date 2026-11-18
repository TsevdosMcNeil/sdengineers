<?php
/**
 * Sidebar left CTA
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

extract(wp_parse_args(
    $args,
    array(
        'title' => 'Back to <br/>Journal',
        'url' => get_permalink( get_option( 'page_for_posts' ) ),
    )
));

?>
<div class="sidebar-block-wrapper sidebar-block-wrapper-back">
    <a class="sidebar-block sidebar-block-left sidebar-block-back" 
        href="<?=$url?>"
    >
        <p class="sidebar-block_title"><?=$title?></p>
        <div class="btn btn-back sidebar-block_link"></div>
    </a>
</div><!-- .entry-header -->
