<?php
// based on Topher Groenink walker
// https://gist.github.com/xmartyxcorex/8798210
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Walker_Category_Posts extends Walker_Category{
    public $displayed = array();
    function start_el( &$output, $category, $depth = 0, $args = array(), $current_page = 0 ) {
        if(isset($category))parent::start_el( $output, $category, $depth, $args );
        global $wpdb;
        $post_args = array(
            'posts_per_page' => -1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'shared_item_category',
                    'field' => 'term_id',
                    'include_children' => false,
                )
            ),
            //'shared_item_category' => $category->slug,
            'post_type' => 'shared_item',
            'orderby' => 'name',
            'order' => 'asc',
            'update_term_cache' => 0,
            'no_found_rows' => 1,
        );
        if($category==null){
            $post_args['tax_query'] = array(array(
            'taxonomy' => 'shared_item_category',
            'field' => 'term_id',
            'operator' => 'NOT IN',
            'terms' => get_terms('shared_item_category', array(
                    'fields' => 'ids'
                ))
            ));
        }else{
            $post_args['tax_query'][0]['terms'] = $category->term_id;
        }
        if ( $posts = get_posts( $post_args ) ) {
            $output .= '<ul>';
            foreach ( $posts as $post ){
                $query = "SELECT
                CASE WHEN comment_date = 0 THEN 'This item has been requested.'
                WHEN comment_date_gmt > CURRENT_TIMESTAMP OR comment_date_gmt = 0 THEN 'na'
                ELSE 'available' END availability,
                user_nicename
                FROM $wpdb->comments
                LEFT JOIN $wpdb->users ON $wpdb->comments.user_id = $wpdb->users.ID
                WHERE comment_post_ID = $post->ID AND comment_type = 'lending' LIMIT 1";
                $lending = $wpdb->get_row($query);
                $thumb = get_the_post_thumbnail($post->ID, 'thumbnail');
                $output .= '<li>';
                $output .= '<div class="thumbnail">'.$thumb.'</div>';
                $output .= '<span class="text"><a href="'.get_the_permalink($post->ID).'">'.get_the_title($post->ID).'</a><br />';
                if($lending->availability=='na')$output .= sprintf(__('This item is currently borrowed by %s.', 'sharing-club'), $lending->user_nicename);
                else $output .= __(isset($lending)?$lending->availability:'available', 'sharing-club');
                $output .= '</span>';
                $output .= '</li>';
            }
            $output .= '</ul>';
            return $output;
        }
    }
}
?>
