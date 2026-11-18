<?php
/**
 * REST API Functions
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
defined( 'ABSPATH' ) || exit;




/**
 * Returns the latest posts
 *
 * @param  array $atts
 * @return array || WP_Error
 */
function cdg_get_latest_posts(
    $atts = array()
) {

    // set up default parameters
    extract(shortcode_atts(array(
        'post_type'      => 'post',
        'posts_per_page' => -1,
        'category'       => null,
        'taxonomies'     => null,
        'meta_query'     => null,
        'post__in'       => null
    ), $atts));

    //error_log('cdg_get_latest_posts $atts: '.print_r( $atts,true));

    $args = [
        'post_type' 	         => $post_type,
        'posts_per_page'         => $posts_per_page, 
        'post_status'            => 'publish',
        'no_found_rows'          => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
    ];

    if(isset($category)) {
		$args['category'] = $category;
	}	

    if( is_array($taxonomies)
        && count($taxonomies)
    ) {

        $args['tax_query'] = [];
        foreach($taxonomies as $taxonomy_slug => $terms_id) {
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy_slug,
                'field'    => 'id',
                'terms'    => $terms_id,
            ];
        }
    }

    if(isset($meta_query)) {
		$args['meta_query'] = $meta_query;
	}

    if(isset($post__in)) {
		$args['post__in'] = $post__in;
	}

    //error_log('cdg_get_latest_posts $args: '.print_r($args,true));

    if($posts = get_posts($args)) {
	    return $posts;
	}
    else {
        return new WP_Error( 
            'rest_project_invalid', 
            esc_html__( 'No results.', 'codigo' ), 
            array( 'status' => 404 ) 
        );
    }
}


/**
 * Returns the latest posts formatted to be used in VUE
 *
 * @param  array $atts
 * @return array || WP_Error
 */
function cdg_get_vue_latest_posts(
    $args = array()
) {

    extract(shortcode_atts(array(
        'post_type'      => 'post',
    ), $args));

    //error_log('cdg_get_vue_latest_posts: '.print_r($args,true));

    $posts = cdg_get_latest_posts($args);

    if(is_wp_error($posts )) {
        return $posts;
    }

    global $post;
    
    foreach ($posts as $key => $post) {
        
        setup_postdata($post); 

        if($post instanceof WP_Post) {

            $post_id = get_the_ID();

            $posts[$key]->class		= esc_attr( implode( ' ', get_post_class( ' ' , $post_id) ) );
            $posts[$key]->permalink = get_the_permalink();

            //error_log(print_r(get_the_post_thumbnail($post_id), true));
            if( get_the_post_thumbnail()) {
                $posts[$key]->thumbnail = get_the_post_thumbnail($post_id, 'large');
            }

            if($post_type == 'post'
                || $post_type == 'project'
            ) {
                //$default_image  = get_field('general_default_image','option');
                $portrait_id   = get_field($post_type.'_featured_image_portrait') ?: get_field($post_type.'_featured_image_landscape');
                $landscape_id  = get_field($post_type.'_featured_image_landscape') ?: get_field($post_type.'_featured_image_portrait');
                $posts[$key]->portrait_ratio  = "ratio-2x3";
                $posts[$key]->landscape_ratio = "ratio-3x2";

                $posts[$key]->portrait_img = wp_get_attachment_image(
                    $portrait_id ,
                    'large',
                    false,
                    array('class' => 'card-img-top') 
                );
                $posts[$key]->landscape_img = wp_get_attachment_image(
                    $landscape_id ,
                    'large',
                    false,
                    array('class' => 'card-img-top') 
                );

                if($post_type == 'post') {
                    $posts[$key]->excerpt = get_the_excerpt();
                    $posts[$key]->posted_on = cdg_get_posted_on(array('post_id' => $post_id));

                    $categories = wp_get_post_categories();
                    $posts[$key]->category = get_cat_name($categories[0]);
                }

                if($post_type == 'project') {
                    $posts[$key]->expertise = getProjectFeature('expertise', $post_id); 
                    $posts[$key]->sector = getProjectFeature('sector', $post_id); 
                    $posts[$key]->location = getProjectFeature('location', $post_id); 
                    $posts[$key]->year = get_the_date('Y', $post_id); 
                }
            }

            if($post_type == 'people') {
                $posts[$key]->role = getPeopleFeature('role', $post_id); 
                $posts[$key]->membertitle = getPeopleFeature('member_title', $post_id); 
                $posts[$key]->slideclass = "carousel-item member-item";
                $posts[$key]->membercontent = apply_filters('the_content', $post->post_content); 
            }

            //error_log(print_r($posts[$key],true));
        
            //error_log('post  '.print_r($posts[$key]->excerpt,true));
        }

    }
    wp_reset_postdata(); 

    //error_log(print_r($posts,true));
    return $posts;
}

/*
*  Posts and categories
*/

/**
 * Return the posts filtered by $request_data
 *
 * @param object $request_data
 * @return array
 */
function filter_posts( $request_data ) {

    $args = array(
        'post_type'      => 'post',
        'posts_per_page' => -1
    );

    $parameters = $request_data->get_params();

    if(
        isset($parameters['category']) 
        && $parameters['category'] != null 
        && $parameters['category'] != 'all'
    ) {
		$args['category'] = $parameters['category'];
	}	

    $posts = cdg_get_vue_latest_posts($args);

    if(is_wp_error($posts )) {
        return $posts;
    }

    return rest_ensure_response($posts);
}

/**
 * Return the post categories
 *
 * @param object $request_data
 * @return array
 */
function filter_postcats() {

    if($categories = get_categories() ) {
        foreach ($categories as $key => $category) {
            $categories[$key]->permalink = get_category_link($category->term_id);
        }
        // With array_values() we ensure that a json array is produced and not a json object.
	    return rest_ensure_response( array_values( $categories ) );
	}
    else {
        // Return a WP_Error because the request product was not found. In this case we return a 404 because the main resource was not found.
        return new WP_Error( 
            'rest_categories_invalid', 
            esc_html__( 'No results.', 'codigo' ), 
            array( 'status' => 404 ) 
        );
    }

    // If the code somehow executes to here something bad happened return a 500.
    return new WP_Error( 
        'rest_api_sad', 
        esc_html__( 'Something went wrong.', 'codigo' ), 
        array( 'status' => 500 ) 
    );
}


/*
*  Custom Post Types and Taxonomies
*/

/**
 * Return the taxonomy terms
 *
 * @param object $request_data
 * @return array
 */
function filter_taxonomy($request_data) {

    $parameters = $request_data->get_params();
	 //error_log(print_r($parameters,true));
     $args = array(
        'taxonomy' => 'sector',
        'hide_empty' => true,
     );

    if(
        isset($parameters['taxonomy']) 
        && $parameters['taxonomy'] != null 
        && $parameters['taxonomy'] != 'all'
    ) {
		$args['taxonomy'] = $parameters['taxonomy'];
	}	

    if($terms = get_terms($args)) {
        foreach ($terms as $key => $term) {
            $terms[$key]->permalink = get_term_link($term->term_id);
        }
        
        $firstTerm = new stdClass();
        $firstTerm->term_id = 'all';
        $firstTerm->name = 'All';
        array_unshift($terms , $firstTerm);

	    return rest_ensure_response($terms);
	}
    else {
        return new WP_Error( 
            'rest_taxonomy_invalid', 
            esc_html__( 'No results.', 'codigo' ), 
            array( 'status' => 404 ) 
        );
    }
    return new WP_Error( 
        'rest_api_sad', 
        esc_html__( 'Something went wrong.', 'codigo' ), 
        array( 'status' => 500 ) 
    );
}

/**
 * Return the projects filtered by $request_data
 *
 * @param object $request_data
 * @return array
 */
function filter_projects( $request_data ) {

	//error_log(print_r($request_data,true));
	//error_log('slug '+$request_data->slug);

	 $parameters = $request_data->get_params();
	 //error_log(print_r($parameters,true));

    
    $primaryposts = [];
    $primarytax = [];
    $taxonomies = [];
    if(
        isset($parameters['sector']) 
        && $parameters['sector'] != null 
        && $parameters['sector'] != 'all'
    ) {
        $primarytax['sector'] = [
            'key' => '_yoast_wpseo_primary_sector',
            'value' => $parameters['sector'],
        ];
		$taxonomies['sector'] = $parameters['sector'];
	}

    if(
        isset($parameters['expertise']) 
        && $parameters['expertise'] != null 
        && $parameters['expertise'] != 'all'
    ) {
		
        $primarytax['expertise'] = [
            'key' => '_yoast_wpseo_primary_expertise',
            'value' => $parameters['expertise'],
        ];
        $taxonomies['expertise'] = $parameters['expertise'];
	}

    //Projects with this sector or expertise as primary will be displayed at the fist places
    if( class_exists('WPSEO_Options')) {
        //  Yoast SEO stores the primary term ID as the post meta field _yoast_wpseo_primary_{taxonomy}.
        $items = 0;
        if(count($primarytax)) {
            $metaquery['meta_query'] = [];
            if(isset($primarytax['sector']) ) {
                $metaquery['meta_query'][] =$primarytax['sector'];
                $items++;
            }
            if(isset($primarytax['expertise']) ) {
                $metaquery['meta_query'][] = $primarytax['expertise'];
                $items++;
            }
            if($items > 1 ) {
                $metaquery['meta_query'] = array_merge(
                    $metaquery['meta_query'],
                    ['relation' => 'OR']
                );
            }

            //error_log(print_r($metaquery,true));
            $primaryargs = array_merge([
                    'post_type'      => 'project',
                    'posts_per_page' => -1,
                ],
                $metaquery
            );
        
            $primaryposts = cdg_get_vue_latest_posts($primaryargs);

            //error_log(print_r($primaryposts,true));

        }
    }

    $args = array(
        'post_type'      => 'project',
        'posts_per_page' => -1,
        'taxonomies'     => $taxonomies
    );

    if(!is_wp_error($primaryposts )) {
        $posts = array_merge(
            $primaryposts, 
            cdg_get_vue_latest_posts($args)
        );
        //duplicate projects will be removed
        $posts = array_map(
            "unserialize", 
            array_unique(
                array_map(
                    "serialize", 
                    $posts
                )
            )
        );
    } else {
        $posts = cdg_get_vue_latest_posts($args);
    }

    if(is_wp_error($posts )) {
        return $posts;
    }

    return rest_ensure_response($posts);
}

/**
 * Return the memebers filtered by $request_data
 *
 * @param WP_REST_Request $request_data
 * @return array
 */
function filter_people( $request_data ) {

	//error_log(print_r($request_data,true));
	//error_log('slug '+$request_data->slug);

    $args = array(
        'post_type'      => 'people',
        'posts_per_page' => -1
    );

	 $parameters = $request_data->get_params();
	 //error_log(print_r($parameters,true));

    if(
        isset($parameters['members']) 
        && $parameters['members'] != null 
    ) {
        $arrParameters = explode(",",$parameters['members']);
		$args['post__in'] = $arrParameters;
	}

    $taxonomies = [];
    if(
        isset($parameters['role']) 
        && $parameters['role'] != null 
        && $parameters['role'] != 'all'
    ) {
		$taxonomies['role'] = $parameters['role'];
	}
    $args['taxonomies'] = $taxonomies;

    //error_log(print_r($args,true));
    

    $posts = cdg_get_vue_latest_posts($args);

    if(is_wp_error($posts )) {
        return $posts;
    }

    return rest_ensure_response($posts);
}



function add_routes_api(){

    /*
    * Posts
    */

    // wp-json/wp/v1/posts/category=all
	register_rest_route( 
        'wp/v1', 
        '/posts/category=(?P<category>[\,a-zA-Z0-9-]+)', 
        array(
            // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'filter_posts',
        )
    );

    // http://local.danny.sdengineers.com/wp-json/wp/v1/post_cats
    // https://sdengineersstg.wpengine.com/wp-json/wp/v1/post_cats
    register_rest_route( 
        'wp/v1', 
        '/post_cats', 
        array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'filter_postcats',
        )
    );


    
    /*
    * Custom post types and taxonomies
    */

    // wp-json/wp/v1/taxonomy=sector
    // wp-json/wp/v1/taxonomy=expertise
    // wp-json/wp/v1/taxonomy=role
    register_rest_route( 
        'wp/v1', 
        '/taxonomy=(?P<taxonomy>[\,a-zA-Z0-9-]+)', 
        array(
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'filter_taxonomy',
        )
    );

    // wp-json/wp/v1/projects/sector=all/expertise=all
	register_rest_route( 
        'wp/v1', 
        '/projects/sector=(?P<sector>[\,a-zA-Z0-9-]+)/expertise=(?P<expertise>[\,a-zA-Z0-9-]+)', 
        array(
            // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
            'methods'  => WP_REST_Server::READABLE,
            'callback' => 'filter_projects',
        )
    ); 

    // wp-json/wp/v1/people/role=all
    // wp-json/wp/v1/people/members=687,689
    // wp-json/wp/v1/people/role=all/members=687,689
	register_rest_route( 
        'wp/v1', 
        '/people(?:/role=(?P<role>[\,a-zA-Z0-9-]+))?(?:/members=(?P<members>[\,a-zA-Z0-9-]+))?', 
        array(
            'methods'  => 'GET',
            'callback' => 'filter_people',
        )
    );

	// wp-json/wp/v1/projects/sector=office/solution=none/region=year/date=DESC
    /*
	register_rest_route( 'wp/v1', 'case-study/solution=(?P<solution>[\,a-zA-Z0-9-]+)/region=(?P<region>[\,a-zA-Z0-9_-]+)/sector=(?P<sector>[\,a-zA-Z0-9-]+)/date=(?P<date>[a-zA-Z0-9-]+)', array(
        // By using this constant we ensure that when the WP_REST_Server changes our readable endpoints will work as intended.
        'methods'  => WP_REST_Server::READABLE,
        'callback' => 'filter_casestudies',
    ));
    */

}
add_action( 'rest_api_init', 'add_routes_api');
