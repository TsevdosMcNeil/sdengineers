<?php
/**
 * Header Navbar Logo
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

extract(wp_parse_args(
    $args,
    array(
        'title' => '',
        'description' => '',
        'header_class' => 'entry-header index-header',
        'filter' => 'posts',
    )
));

?>
<header class="<?=$header_class?> row">
    <div class="col">
        <?=$title ?>
        <?=$description ?>
    </div>


    <?php if($filter == 'posts'): 
            $h3_id = "headingCategories";
            $accordion_id = "collapseCategories";
    ?>
        <div class="col-md-3">
            <div id="postFilters"
                class="accordion accordion-filter" 
            >
                <div class="accordion-item">
                    <?php 
                        cdg_filter_header(array(
                            'h3_id'  => $h3_id,
                            'target' => $accordion_id
                        )); 
                    ?>
                    <div id="<?=$accordion_id?>" 
                        class="accordion-collapse collapse" 
                        aria-labelledby="<?=$h3_id?>" 
                        data-bs-parent="#postFilters"
                    >
                        <h6 class="d-md-none"><?php _e('Type','codigo'); ?></h6>
                        <!-- Vue -->
                        <div id="appCategories">
                            <categories-list></categories-list>
                        </div>
                        <!-- / Vue  -->
                    </div>
                </div>
            </div>
        </div>

    <?php elseif($filter == 'projects'):  
        $h3_id = "headingTaxonomies";
        $accordion_id = "collapseTaxonomies";
    ?>
        <div class="col-md-5">
            <div class="accordion accordion-filter">
                <div class="accordion-item">
                    <?php 
                        cdg_filter_header(array(
                            'h3_id'  => $h3_id,
                            'target' => $accordion_id
                        )); 
                    ?>
                </div>
            </div>
        </div>

        <div class="col-md-1 text-end">
            <!-- Vue -->
            <div id="appDisplay">
                <button-display></button-display>
            </div>
            <!-- / Vue  -->
            
        </div>

        <div class="col-md-12">
            <div id="projectFilters"
                class="accordion" 
            >
                <div class="accordion-item">
                    <div id="<?=$accordion_id?>" 
                        class="accordion-collapse collapse" 
                        aria-labelledby="<?=$h3_id?>" 
                        data-bs-parent="#projectFilters"
                    >
                        <div class="row">
                            <div class="col-md-6">
                                <h6><?php _e('Sector','codigo'); ?></h6>
                                <!-- Vue -->
                                <div id="appSectors">
                                    <sector-list></sector-list>
                                </div>
                                <!-- / Vue  -->
                            </div>
                            <div class="col-md-6">
                                <h6><?php _e('Expertise','codigo'); ?></h6>
                                <!-- Vue -->
                                <div id="appExpertises">
                                    <expertise-list></expertise-list>
                                </div>
                                <!-- / Vue  -->
                            </div>
                        </div><!-- /.row  -->
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>


</header><!-- .entry-header -->
