<?php
/**
 * Understrap Child Theme Custom Post Types
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



/*
* Projects
*/

/**
 * Returns the list of values of a project custom field 
 * or a project custom taxonomy
 *
 * @param string  $featslug
 * @param int     $post_id
 * @return string
 */
function getProjectFeature(
    $featslug, 
    $post_id,
    $linkedterms = false
) {

    $join = ' <br /> ';
    if($featslug == 'location') {
        $join = ', ';
    }

    $primary_term_id = null;
    if( class_exists('WPSEO_Options')) {
        $primary_term_id = yoast_get_primary_term_id($featslug, $post_id );
    }

    $featval = get_field('project_'.$featslug, $post_id);

    if(!$featval) {
        $terms = get_the_terms( 
            $post_id, 
            $featslug 
        ); 
        
        if(is_array($terms)){
            foreach($terms as $term) {
                if($primary_term_id == $term->term_id) {
                    $term->name = '<b>'.$term->name.'</b>';
                }
                if($linkedterms) {
                    ob_start(); 
                    ?><a href='<?=get_term_link($term)?>'><?=$term->name?></a><?php
                        $featval .= ob_get_contents();
                    ob_end_clean();
                    $featval = $featval.$join;
                } else {
                    $featval .= $term->name.$join;
                }
            }
            
            if($join == ', '){
                $featval = rtrim($featval, $join);
            }
        }
    }

    if(!$featval) {
        return __( 'Unable to get the feature', 'codigo' );
    }

    return $featval;
}

/**
 * Hook to update custom post type columns
 * manage_{$post_type}_posts_columns
 *
 * @param array $columns
 * @return array
 */
function add_custom_columns($prevcolumns) {

    $columns = array(
        2 => array("location" => "Location"),
        3 => array("sector" => "Sector"),
        4 => array("expertise" => "Expertise"),
        6 => array("project_year" => "Year")
    );

    foreach($columns as $columpos => $newcolumn){
        $newcolumns = array_slice($prevcolumns, 
                        0, 
                        $columpos, 
                        true
                    ) 
                    + $newcolumn
                    + array_slice($prevcolumns, 
                        $columpos, 
                        count($prevcolumns)-$columpos, 
                        true
                    );

        $prevcolumns = $newcolumns;

        //error_log(print_r($newcolumns,true));
    }
    return $newcolumns;
}
add_filter( 'manage_project_posts_columns', 
    'add_custom_columns' 
);

/**
 * Hook to display content in the new custom taxonomy column
 * manage_{$post_type}_posts_custom_column
 *
 * @param string $column_name
 * @param int    $post_id
 * @return string
 */

function add_custom_columns_content( 
    $column_name, 
    $post_id 
) {
    $newcolumns = array(
       "location",
       "sector",
       "expertise",
       "status",
       "project_year"
    );

    foreach($newcolumns as $newcolumn) {
        if($column_name == $newcolumn) {
            echo getProjectFeature($newcolumn, $post_id);
        }
    }
}
add_action( 'manage_project_posts_custom_column' , 
    'add_custom_columns_content', 
    10, 
    2 
);


/*
* People
*/

/**
 * Returns the list of values of a project custom field 
 * or a project custom taxonomy
 *
 * @param string  $featslug
 * @param int     $post_id
 * @return string
 */
function getPeopleFeature(
    $featslug, 
    $post_id 
) {

    $join    = ', ';
    $featval = '';

    $featval = get_field($featslug, $post_id);

    if(!$featval) {

        if($featslug == 'role') {
            $terms = get_the_terms( $post_id, $featslug ); 
            if(is_array($terms)){
                foreach($terms as $term) {
                    $featval .= $term->name.$join;
                }
                $featval = rtrim($featval, $join);
            }
        }

        if($featslug == 'portrait') {
            $featval = get_the_post_thumbnail($post_id, array(100,'auto'));
        }
    }


    if(!$featval) {
        return __( 'Unable to get the feature', 'codigo' );
    }

    return $featval;
}

/**
 * Hook to update custom post type columns
 * manage_{$post_type}_posts_columns
 *
 * @param array $columns
 * @return array
 */
function add_custom_columns_people($prevcolumns) {

    $columns = array(
        2 => array("role" => "Role"),
        3 => array("portrait" => "Portrait")
    );

    foreach($columns as $columpos => $newcolumn){
        $newcolumns = array_slice($prevcolumns, 
                        0, 
                        $columpos, 
                        true
                    ) 
                    + $newcolumn
                    + array_slice($prevcolumns, 
                        $columpos, 
                        count($prevcolumns)-$columpos, 
                        true
                    );

        $prevcolumns = $newcolumns;

        //error_log(print_r($newcolumns,true));
    }
    return $newcolumns;
}
add_filter( 'manage_people_posts_columns', 
    'add_custom_columns_people' 
);

/**
 * Hook to display content in the new custom taxonomy column
 * manage_{$post_type}_posts_custom_column
 *
 * @param string $column_name
 * @param int    $post_id
 * @return string
 */

function add_custom_columns_content_people( 
    $column_name, 
    $post_id 
) {
    $newcolumns = array(
       "role",
       "portrait"
    );

    foreach($newcolumns as $newcolumn) {
        if($column_name == $newcolumn) {
            echo getPeopleFeature($newcolumn, $post_id);
        }
    }
}
add_action( 'manage_people_posts_custom_column' , 
    'add_custom_columns_content_people', 
    10, 
    2 
);
