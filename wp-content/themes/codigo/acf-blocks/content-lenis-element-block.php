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

$start = get_field('lenis_start');
$end = get_field('lenis_end');

//error_log(print_r($start , true));
//error_log(print_r($end , true));


if ( $start 
    && $end
):
?>
<div class="leniscnf"
    data-startframe='<?php echo $start['frame'] ?>'
    data-starteffect='<?php echo json_encode($start['effect']) ?>'
    data-endframe='<?php echo $end['frame'] ?>'
    data-endeffect='<?php echo json_encode($end['effect']) ?>'
></div>
<?php
endif;
