<?php

/**
 * Understrap template for ACF Block Name: Lenis Element
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

/*

*/

$pdfurl = do_shortcode('[e2pdf-download id="1" dataset="'.get_the_id().'" output="url" esc_url_raw="true"]').'&pdf_career='.get_the_id(); 
?>

<a id="e2pdf-download" 
    rel="nofollow" 
    class="e2pdf-download e2pdf-format-pdf" 
    target="_blank" 
    href="<?php echo esc_url($pdfurl); ?>"
>Download</a>


