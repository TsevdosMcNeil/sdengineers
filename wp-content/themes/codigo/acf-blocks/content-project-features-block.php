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

$project_id = get_the_ID();

//error_log(print_r($_REQUEST,true));
$pdf = false;
if( isset( $_REQUEST['page'] ) 
    && $_REQUEST['page'] == 'e2pdf'
) {
    //This is a PDF
    $pdf = true;
    $project_id = $_REQUEST['dataset'];
}
if( isset( $_REQUEST['pdf_project'] ) ){
    //This is a PDF
    $pdf = true;
    $project_id = $_REQUEST['pdf_project'];
}

$features = array(
    'location',
    'sector',
    'expertise',
    'project_year',
    'press'
);



?>
<div class="project-features">
    <?php 
    $HTML_features ='';

    if( have_rows('gblock_projectfeatures') ):

        // Loop through rows.
        while ( have_rows('gblock_projectfeatures') ) : the_row();

            $featval = '';
            $vueval  = '';
            $modal   = '';

            if( in_array(
                    get_row_layout(), 
                    $features
                )
            ):
                $title = get_sub_field('title');

                if($posts = get_sub_field('posts')) {
                    if(is_array($posts)){
                        $HTML_posts = '';
                        foreach($posts as $post_id) {
                            ob_start(); ?>
                            <a href="<?=get_permalink($post_id)?>">
                                <?=get_the_title($post_id)?>
                            </a><br/><?php
                                $HTML_posts .= ob_get_contents();
                            ob_end_clean();
                        }
                        $featval = $HTML_posts;
                    }
                } else {
                    $featval = getProjectFeature(
                        get_row_layout(), 
                        $project_id, 
                        true
                    );
                    //error_log(get_row_layout()." for ".$project_id.":");
                    //error_log(print_r($featval, true));
                }
            endif;

            if( get_row_layout() == 'people' ): 
                $title   = get_sub_field('title');
                if($people = get_sub_field('people')) {
                    if(is_array($people)){
                        $arrayMembers = Array();
                        $member_separator = '';
                        if($pdf) {
                            $member_separator = '<br/>';
                        }
                        foreach($people as $member_id) {
                            $memberObj        = new \stdClass();
                            $memberObj->id    = $member_id;
                            $memberObj->title = strip_tags(get_the_title($member_id));
                            $arrayMembers[]  = $memberObj;
                            ob_start(); ?>
                                <span class="d-none d-editor-block">
                                    <?=$memberObj->title?>
                                <span><?php echo $member_separator;
                                $featval .= ob_get_contents();
                            ob_end_clean();
                        }
                        //error_log(print_r($arrayMembers, true));
                        $jsMembers = json_encode($arrayMembers);
                        //error_log(print_r($jsMembers, true));
                        ob_start(); ?>
                            <!-- Vue -->
                            <span id="appPeopleList"
                                data-members = '<?php echo $jsMembers; ?>'
                            >
                                <people-list></people-list>
                            </span><?php
                            $vueval = ob_get_contents();
                        ob_end_clean();

                        ob_start(); 
                            get_template_part(
                                'global-templates/modal',
                                'member',
                                array(
                                    'jsMembers' => $jsMembers
                                )
                            );
                            $modal = ob_get_contents();
                        ob_end_clean();
                    }
                }
            endif;

            if( get_row_layout() == 'project_pdf' ): 
                $title   = get_sub_field('title');
                $pdfurl  = do_shortcode('[e2pdf-download id="2" dataset="'.get_the_id().'" output="url" esc_url_raw="true"]').'&pdf_project='.get_the_id(); 
                ob_start(); ?>
                    <a id="e2pdf-download" 
                        rel="nofollow" 
                        class="e2pdf-download e2pdf-format-pdf" 
                        target="_blank" 
                        href="<?php echo esc_url($pdfurl); ?>"
                    >Download</a><?php
                    $featval = ob_get_contents();
                ob_end_clean();

                if($pdf) {
                    $title   = '';
                    $featval = '';
                }
            endif;

            if( get_row_layout() == 'custom_feature' ): 
                $title   = get_sub_field('title');
                $featval = str_replace(array("<p>","</p>"), "", get_sub_field('value'));
            endif;

            if($pdf) {
                $vueval = '';
                $modal = '';
            }

            ob_start(); ?>
            <div class="project-feature">
                <h6 class="mb-0"><?=$title?></h6>
                <p><strong><?=$featval?></strong></p>
                <?=$vueval?>
            </div><?php
                echo $modal;
                $HTML_features .= ob_get_contents();
            ob_end_clean();	

        endwhile;
    endif;
   
    echo $HTML_features;
    ?>
</div><!-- .project-features -->
