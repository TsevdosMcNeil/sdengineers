<?php

/**
 * Custom Shortcodes
 *
 * Adds new shortcodes to theme.
 * php version 7.4.1
 *
 * @category   Category
 * @package    Understrap
 * @subpackage UnderstrapChild
 * @author     Pablo Rica <pablo@codigo.co.uk>
 * @license    MIT
 * @version    GIT: @1.0.0@
 * @link       link
 * @since      UnderstrapChild 1.0
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

//Run shortcodes in widgets
//add_filter( 'widget_text', 'do_shortcode' );


/**
 * Displays a row with the latest posts
 *
 * @param array $atts
 * @return string
 */
function shortcode_cdg_latest_posts(
    $args = array()
) {

    $args = shortcode_atts(array(
        'post_type'      => 'post',
        'posts_per_page' => -1
    ), $args);

    $latestposts = cdg_get_latest_posts($args);

    if(!is_wp_error($latestposts)) {

        global $post; //this is needed to make setup_postdata() work

        $post_cards = '';
        foreach ($latestposts as $post) {

            setup_postdata($post);

            ob_start(); ?>
                <div class="col post-col">
                    <?php get_template_part(
                        'loop-templates/card',
                        'post'
                    ); ?>
                </div><?php
                $post_cards .= ob_get_contents();
            ob_end_clean();
        }
        wp_reset_postdata();

        $index_html = '';
        ob_start(); ?>
            <div class="row shortcode-row">
                <?=$post_cards?>
            </div><?php
            $index_html .= ob_get_contents();
        ob_end_clean();

    }

    return $index_html;
}
// To place [latest_posts post_type=post posts_per_page=3] in Articles
add_shortcode('latest_posts', 'shortcode_cdg_latest_posts');


/**
 * Displays the member title
 *
 * @param array $atts
 * @return string
 */
function shortcode_cdg_member_title(
    $args = array()
) {
    global $post;

    $title_html     = '';
    $title          = get_the_title($post);
    $memberole      = getPeopleFeature('role', $post->ID);
    $membertitle    = getPeopleFeature('member_title', $post->ID);
    $memberpronouns = '';
    if  ( getPeopleFeature (
            'member_pronouns',
            $post->ID
        ) != 'Unable to get the feature'
    ) {
        $memberpronouns = getPeopleFeature('member_pronouns', $post->ID);
    }

    if($title) {
        ob_start(); ?>
            <div class="member-header">
                <h3 class="member-title"><?=$title?></h3>
                <h3 class="member-role"><?=$memberole?></h3>
                <h3 class="member-accreditation"><?=$membertitle?></h3>
                <h3 class="member-pronouns"><?=$memberpronouns?></h3>
            </div><?php
            $title_html = ob_get_contents();
        ob_end_clean();
    }

    return $title_html;
}
// To place [member_title] in Articles
add_shortcode('member_title', 'shortcode_cdg_member_title');

/**
 * Displays the People via VUE
 *
 * @param array $atts
 * @return string
 */
function shortcode_cdg_display_people(
    $args = array()
) {

    $filter_id    = "peopleFilters";
    $h3_id        = "headingRoles";
    $accordion_id = "collapseRoles";
    ob_start(); ?>

        <div id="<?=$filter_id?>"
            class="accordion people-accordion col-md-4 accordion-filter"
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
                    data-bs-parent="#<?=$filter_id?>"
                >
                    <h6 class="d-md-none"><?php _e('Role','codigo'); ?></h6>
                    <!-- Vue -->
                    <div id="appRoles">
                        <roles-list></roles-list>
                    </div>
                    <!-- / Vue  -->
                </div>
            </div>
        </div>

        <div id="appPeople">
            <people-cards></people-cards>
        </div>
        <!-- / Vue  -->

        <?php
         get_template_part(
            'global-templates/modal',
            'member',
            array()
        );

        $index_html = ob_get_contents();
    ob_end_clean();

    return $index_html;
}
// To place [people] in Articles
add_shortcode('people', 'shortcode_cdg_display_people');



/**
 * Displays the Hiring button
 *
 * @param array $atts
 * @return string
 */
function shortcode_cdg_display_hiring_button(
    $args = array()
) {

    $button_html = '';
    $args        = shortcode_atts(
        array(
            'post_type'      => 'career',
            'posts_per_page' => -1
        ),
        $args
    );

    if( ! is_wp_error(
            cdg_get_latest_posts($args)
        )
    ) {

        $button = get_field('general_hiring_button','options');

        ob_start(); ?>
            <a class="alert alert-secondary job-alert"
                href="<?=$button['page']?>?scrollto=<?=$button['anchor']?>"
                role="alert"
            >
                <?=$button['title']?> <span class="hide"> <?=$button['hidden_title']?> </span>
            </a><?php
            $button_html .= ob_get_contents();
        ob_end_clean();

    }

    return $button_html;
}
// To place [hiring-button] in Head
add_shortcode(
    'hiring-button',
    'shortcode_cdg_display_hiring_button'
);

/**
 * Displays the Careers
 *
 * @param array $atts
 * @return string
 */
function shortcode_cdg_display_careers(
    $args = array()
) {

    $args = shortcode_atts(array(
        'post_type'      => 'career',
        'posts_per_page' => -1
    ), $args);

    $index_html  = '';
    $latestposts = cdg_get_latest_posts($args);

    if(!is_wp_error($latestposts)) {

        global $post; //this is needed to make setup_postdata() work

        $post_cards = '';
        foreach ($latestposts as $post) {

            setup_postdata($post);

            ob_start();
                get_template_part(
                    'loop-templates/card',
                    'career',
                    array(
                        'accordion_id' => 'accordionCareers',
                    )
                );
                $post_cards .= ob_get_contents();
            ob_end_clean();
        }
        wp_reset_postdata();

        ob_start(); ?>
            <div class="accordion careers-accordion" id="accordionCareers">
                <?=$post_cards?>
            </div><?php
            $index_html .= ob_get_contents();
        ob_end_clean();

    }

    return $index_html;
}
// To place [careers] in Articles
add_shortcode(
    'careers',
    'shortcode_cdg_display_careers'
);


/**
 * Adds the splash animation
 *
 * @param array  $atts
 * @return string
 */
function codigo_splash(
    $atts = array()
) {
    // set up default parameters
    extract(shortcode_atts(array(
        'background_color' => 'white',
        'splash_id' => 'splashHome'
    ), $atts));

    $mp4 = get_field('splash_mp4','option');
    $duration = get_field('splash_duration','option');

    ob_start(); ?>
    <div
        class="splash splash-page hide wp-block-columns break-container p-0 m-0 has-<?=$background_color?>-background-color has-background"
        style="z-index: -1"
        data-duration=<?php echo esc_attr($duration); ?>
        id="<?=$splash_id?>"
        tabindex="-1"
        aria-labelledby="<?=$splash_id?>Label"
    >
        <video autoplay
            muted
            width="100%"
        >
            <source src="<?php echo esc_url($mp4); ?>"
                type="video/mp4"
            >
        </video>
    </div>
    <?php
    //poster="<?php echo get_stylesheet_directory_uri().'/img/splash.png'; "
    $splashPage = ob_get_contents();
    ob_end_clean();

    return $splashPage;
}
// To place [splash background_color=white splash_id=splashHome]
add_shortcode('splash', 'codigo_splash');




/**
 * PDF: Displays the project content without tags and ACFs
 *
 *  - The custom featured block will not be rendered with the rest of the content
 *  - The sidebar blocks will not be rendered with the rest of the content
 *  - The gutenberg groups with class 'pdf-hidden' (and only with this class), will not be rendered with the rest of the content
 *
 *
 * @see https://e2pdf.com/support/desk/inserting-post-content-into-imported-pdf
 *
 * @param array $atts - Attributes
 * @return string
 */
function shortcode_cdg_pdf_project_content(
    $atts = array(),
) {

    $atts = apply_filters('e2pdf_model_shortcode_e2pdf_wp_atts', $atts);

    $id = null;
    if( isset( $_REQUEST['pdf_project'] ) ){
        $id = $_REQUEST['pdf_project'];
    }
    else if( isset( $_REQUEST['dataset'] ) ){
        $id = $_REQUEST['dataset'];
    }
    else if( isset( $atts['id'] ) ){
        $id = $atts['id'];
    }

    //error_log('ID:');
    //error_log(print_r($id, true));

    $pdf_html = '';

    if( $id ) {
        $wp_post = get_post($id);
        $content = $wp_post->post_content;
        //error_log(print_r($content, true));

        //Remove breaklines
        //$content = str_replace(array("\r", "\n"), '', $content);

        //Remove Features block
        $regexp_features_block = '/<!-- wp:acf\/project-features-block {(.*)} \/-->/';
        $content = preg_replace($regexp_features_block, '', $content);

        //Remove sidebar-blocks
        $regexp_sidebar_block = '/<!-- wp:acf\/sidebar-block {(.*)} \/-->/';
        $content = preg_replace($regexp_sidebar_block, '', $content);


        //Remove "Project tags" title
        $project_tags = '<p class="mb-0"><strong>Project tags</strong></p>';
        $content = str_replace($project_tags, '', $content);

        //Images
        //$content = str_replace('<img ', '<img height="300px" ', $content);
        $regexp_figure = '/<figure(.*)><img(.*)<\/figure>/';
        //Remove first image
        $content = preg_replace($regexp_figure, '', $content, 1);
        //Place images in columns
        preg_match_all('/<figure(.*)><img(.*)<\/figure>/', $content, $figures_array);
        //error_log(print_r($figures_array[0], true));
        foreach($figures_array[0] as $figure) {
            $regexp_caption = '/<figcaption(.*)<\/figcaption>/';
            preg_match($regexp_caption, $figure, $caption_array);

            $image = preg_replace($regexp_caption, '', $figure);
            $image = str_replace('<img ', '<img height="300px" ', $image);

            $caption = '';
            if( isset( $caption_array[0] ) ) {
                $caption = str_replace('<figcaption ', '<p style="max-width:40px; padding-right:20px; margin-right:20px; text-align:left;font-size:10px;font-weight:bold;color:#C4C4C4" ', $caption_array[0]);
                $caption = str_replace('</figcaption>', '</p>', $caption);
            }

            ob_start(); ?>
            </td>
            </tr>
            </table>
            <table style="height:300px">
                <tr>
                    <td style="max-width:80px; text-align:left; vertical-align: top;"><?php echo $caption ?></td>
                    <td colspan="3" style="min-width:500px; text-align:left; vertical-align: top;"><?php echo $image ?></td>
                </tr>
                <tr>
                    <td style="text-align:left; vertical-align: top;">&nbsp;</td>
                    <td style="text-align:left; vertical-align: top;"></td>
                    <td style="text-align:left; vertical-align: top;"></td>
                    <td style="text-align:left; vertical-align: top;"></td>
                </tr>
            </table>
            <table>
            <tr>
                <td style="text-align:left; vertical-align: top;"></td>
                <td style="text-align:left; vertical-align: top;"></td>
                <td style="text-align:left; vertical-align: top;"></td>
                <td style="text-align:left; vertical-align: top;"></td>
            </tr>
            <tr>
                <td style="max-width:80px; text-align:left; vertical-align: top;">&nbsp;</td>
                <td colspan="3" style="min-width:500px; text-align:left; vertical-align: top;">
            <?php
            $table_figure = ob_get_contents();
            ob_end_clean();

            $content = str_replace($figure, $table_figure, $content);
        }

        //Replace title tags by paragraphs
        $open_title_tags = ['<h1','<h2','<h3','<h4','<h5','<h6'];
        $content = str_replace($open_title_tags, '<p', $content);
        $close_title_tags = ['</h1>','</h2>','</h3>','</h4>','</h5>','</h6>'];
        $content = str_replace($close_title_tags, '</p>', $content);

        //Remove double breaklines
        $content = str_replace(array("\r\r", "\r\n", "\n\n", "\n\r"), '', $content );


        remove_filter('the_content', 'wpautop');
        $content = apply_filters('the_content', $content, $id);
        add_filter('the_content', 'wpautop');
        //Add spaces below each paragrapgh
        $content = str_replace("</p>", "</p>\r\n<p>&nbsp;</p>\r\n", $content);
        //Remove double spaces
        $content = str_replace("<p>&nbsp;</p>\r\n<p>&nbsp;</p>", "<p>&nbsp;</p>\r\n", $content);

        //Remove Video Controls
        $content = str_replace('<button class="btn vimeo-control muted">Muted</button>', '', $content);
        $content = str_replace('<button class="btn vimeo-control unmuted hide">Unmuted</button>', '', $content);

        ob_start(); ?>
        <table>
            <tr>
                <td style="max-width:80px; text-align:left; vertical-align: top;">&nbsp;</td>
                <td colspan="3" style="min-width:500px; text-align:left; vertical-align: top;"><?php echo $content ?></td>
            </tr>
            <tr>
                <td style="text-align:left; vertical-align: top;"></td>
                <td style="text-align:left; vertical-align: top;"></td>
                <td style="text-align:left; vertical-align: top;"></td>
                <td style="text-align:left; vertical-align: top;"></td>
            </tr>
        </table>
        <?php
        $pdf_html = ob_get_contents();
        ob_end_clean();

        //$pdf_html = $content;

        //error_log(print_r($pdf_html, true));

    }

    return $pdf_html;
}
// To place [cdg-pdf-project-content]
add_shortcode(
    'cdg-pdf-project-content',
    'shortcode_cdg_pdf_project_content'
);

/**
 * PDF: Displays the project featured block
 *
 *
 * @param array $atts - Attributes
 * @return string
 */
function shortcode_cdg_pdf_project_features (
    $atts = array(),
) {

    $atts = apply_filters('e2pdf_model_shortcode_e2pdf_wp_atts', $atts);

    $id = null;
    if( isset( $_REQUEST['pdf_project'] ) ){
        $id = $_REQUEST['pdf_project'];
    }
    else if( isset( $_REQUEST['dataset'] ) ){
        $id = $_REQUEST['dataset'];
    }
    else if( isset( $atts['id'] ) ){
        $id = $atts['id'];
    }

    $pdf_html = '';

    if( $id ) {
        $wp_post = get_post($id);
        $content = $wp_post->post_content;
        //error_log(print_r($wp_post, true));
        //$content = str_replace(array("\r", "\n"), '', $content);

        $regexp_features_block = '/<!-- wp:acf\/project-features-block {(.*)} \/-->/';
        preg_match($regexp_features_block, $content, $features_array);
        $content = $features_array[0];

        //error_log(print_r($content, true));

        remove_filter('the_content', 'wpautop');
        $content = apply_filters('the_content', $content);
        add_filter('the_content', 'wpautop');
        $content = str_replace("</p>", "</p>\r\n", $content);

        $pdf_html = $content;

        //error_log(print_r($pdf_html, true));

    }

    return $pdf_html;
}
// To place [cdg-pdf-project-features]
add_shortcode(
    'cdg-pdf-project-features',
    'shortcode_cdg_pdf_project_features'
);


/**
 * PDF: Displays the project first image
 *
 *
 * @param array $atts - Attributes
 * @return string
 */
function shortcode_cdg_pdf_project_featured_image (
    $atts = array(),
) {

    $atts = apply_filters('e2pdf_model_shortcode_e2pdf_wp_atts', $atts);

    $id = null;
    if( isset( $_REQUEST['pdf_project'] ) ){
        $id = $_REQUEST['pdf_project'];
    }
    else if( isset( $_REQUEST['dataset'] ) ){
        $id = $_REQUEST['dataset'];
    }
    else if( isset( $atts['id'] ) ){
        $id = $atts['id'];
    }

    $pdf_html = '';

    if( $id ) {
        $wp_post    = get_post($id);
        $content    = $wp_post->post_content;
        $newContent = '';

        preg_match('/<figure(.*)><img(.*)<\/figure>/', $content, $figure_array);
        //error_log(print_r($figures_array[0], true));
        $figure = $figure_array[0];
        $regexp_caption = '/<figcaption(.*)<\/figcaption>/';
        $image = preg_replace($regexp_caption, '', $figure);
        $image = str_replace('<img ', '<img height="300px" ', $image);


        ob_start(); ?>
        <table style="height:300px">
            <tr>
                <td style="text-align:left; vertical-align: top;"></td>
                <td style="text-align:left; vertical-align: top;"></td>
                <td style="text-align:left; vertical-align: top;"></td>
                <td style="text-align:left; vertical-align: top;"></td>
            </tr>
            <tr>
                <td style="max-width:80px; text-align:left; vertical-align: top;">&nbsp;</td>
                <td colspan="3" style="min-width:500px; text-align:left; vertical-align: top;"><?php echo $image ?></td>
            </tr>
        </table>
        <?php
        //poster="<?php echo get_stylesheet_directory_uri().'/img/splash.png'; "
        $newContent .= ob_get_contents();
        ob_end_clean();


        $pdf_html = $newContent;

        //error_log(print_r($pdf_html, true));

    }

    return $pdf_html;
}
// To place [cdg-pdf-project-featured-image]
add_shortcode(
    'cdg-pdf-project-featured-image',
    'shortcode_cdg_pdf_project_featured_image'
);


/**
 * PDF: Displays the career content
 *
 *
 * @param array $atts - Attributes
 * @return string
 */
function shortcode_cdg_pdf_career_content(
    $atts = array(),
) {

    $atts = apply_filters('e2pdf_model_shortcode_e2pdf_wp_atts', $atts);

    $id = null;
    if( isset( $_REQUEST['pdf_career'] ) ){
        $id = $_REQUEST['pdf_career'];
    }
    else if( isset( $_REQUEST['dataset'] ) ){
        $id = $_REQUEST['dataset'];
    }
    else if( isset( $atts['id'] ) ){
        $id = $atts['id'];
    }

    //error_log('ID:');
    //error_log(print_r($id, true));

    $pdf_html = '';

    if( $id ) {
        $wp_post = get_post($id);
        $content = $wp_post->post_content;
        //error_log(print_r($content, true));

        //Remove breaklines
        $content = str_replace(array("\r", "\n"), '', $content);

        //Remove contact form
        $regexp_form = '/<!-- wp:contact-form-7(?:[^>]+)?>.*?\/contact-form-selector -->/';
        $content = preg_replace($regexp_form, '', $content);

        //error_log(print_r($content, true));

        //Remove download ACF block
        $regexp_download = '/<!-- wp:acf\/download-pdf-block(?:[^>]+)?>.*?/';
        $content = preg_replace($regexp_download, '', $content);

        //error_log(print_r($content, true));

        remove_filter('the_content', 'wpautop');
        $content = apply_filters('the_content', $content, $id);
        add_filter('the_content', 'wpautop');

        $pdf_html = $content;

       //error_log(print_r($pdf_html, true));

    }

    return $pdf_html;
}
// To place [cdg-pdf-career-content]
add_shortcode(
    'cdg-pdf-career-content',
    'shortcode_cdg_pdf_career_content'
);
