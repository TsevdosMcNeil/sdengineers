<?php
/**
 * Header Navbar Contact
 *
 * @package Codigo
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$container = get_theme_mod( 'understrap_container_type' );

extract(wp_parse_args(
    $args,
    array(
        'jsMembers' => null
    )
));

?>
<!-- Modal -->
<div id="modalMember" 
    class="modal fade modal-member" 
    data-bs-backdrop="static" 
    data-bs-keyboard="false" 
    tabindex="-1" 
    aria-hidden="true"
>
    <div class="modal-dialog <?php echo esc_attr( $container ); ?>">
        <div class="modal-content">
        <div class="row">
            <!-- Do the left sidebar check -->
            <?php  
            $template_args['modal'] = true;
            get_template_part( 
                'global-templates/left-sidebar', 
                'check', 
                $template_args
            ); 
            ?>
            <div class="member-info">   
                <!-- People carousel-->
                <div id="carouselPeople" 
                    class="bs-carousel carousel slide" 
                    data-bs-interval="false"
                >
                    <!-- Vue -->
                    <div id = "appPeopleModal"
                        class = "carousel-inner"
                        data-members = '<?php echo $jsMembers; ?>'
                    >
                        <peoplemodal-list></peoplemodal-list>
                    </div>
                    <!-- / Vue  -->
                </div>
            </div>

            <!-- Do the right sidebar check -->
            <?php
            get_template_part( 
                'global-templates/right-sidebar', 
                'check', 
                $template_args
            ); 
            ?>
        </div><!-- .row -->
        </div><!-- .row -->
    </div><!-- .modal-dialog -->
</div>
<!-- #modalMember -->
