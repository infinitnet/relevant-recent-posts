<?php
/**
 * Plugin Name: Relevant Recent Posts
 * Description: Adds shortcodes for recent posts lists.
 * Author: Infinitnet
 * Author URI: https://infinitnet.io/
 * Plugin URI: https://github.com/infinitnet/relevant-recent-posts
 * Update URI: https://github.com/infinitnet/relevant-recent-posts
 * Version: 1.1
 * License: GPLv3
 * Text Domain: relevant-recent-posts
 */

if ( ! function_exists( 'infinitnet_relevant_recent_posts_shortcode' ) ) {

    function infinitnet_relevant_recent_posts_shortcode($atts) {
        global $post;

        $atts = shortcode_atts(array(
            'count' => 10,
            'scope' => 'category',
            'date'  => 'published',
            'class' => '',
            'nonav' => false
        ), $atts, 'recentposts');

        $count = intval($atts['count']);
        $count = max(1, min($count, 200));

        $valid_scopes = ['category', 'global'];
        if (!in_array($atts['scope'], $valid_scopes)) {
            $atts['scope'] = 'category';
        }

        $valid_dates = ['published', 'modified'];
        if (!in_array($atts['date'], $valid_dates)) {
            $atts['date'] = 'published';
        }

        if (!isset($post) || $post == null) {
            return '<p>' . esc_html__('Recent posts cannot be displayed.', 'relevant-recent-posts') . '</p>';
        }

        $args = array(
            'posts_per_page' => $count,
            'orderby' => ($atts['date'] === 'modified') ? 'modified' : 'date',
            'order'   => 'DESC'
        );

        if ($atts['scope'] === 'category') {
            $categories = get_the_category($post->ID);
            if (!empty($categories)) {
                $category_ids = array_map(function($category) {
                    return $category->term_id;
                }, $categories);
                $args['category__in'] = $category_ids;
            }
        }

        $relevant_atts = array(
            'count' => $atts['count'],
            'scope' => $atts['scope'],
            'date'  => $atts['date']
        );
        $serialized_atts = serialize($relevant_atts);
        
        $cache_key = "rrp_" . md5($serialized_atts);
        
        $output = get_transient($cache_key);

        if ($output === false) {
            $recent_posts_query = new WP_Query($args);
    
            if ($recent_posts_query->have_posts()) {
                $class_attr = !empty($atts['class']) ? ' class="' . esc_attr($atts['class']) . '"' : '';
    
                $output = $atts['nonav'] ? '' : '<nav>';
                $output .= '<ul' . $class_attr . '>';
    
                while ($recent_posts_query->have_posts()) {
                    $recent_posts_query->the_post();
                    $output .= '<li><a href="' . esc_url(get_permalink()) . '">' . esc_html(get_the_title()) . '</a></li>';
                }
    
                $output .= '</ul>';
                $output .= $atts['nonav'] ? '' : '</nav>';
    
                set_transient($cache_key, $output, 12 * HOUR_IN_SECONDS);
            } else {
                $output = $atts['nonav'] ? '<ul><p>' : '<nav><ul><p>';
                $output .= esc_html__('No posts found.', 'relevant-recent-posts');
                $output .= $atts['nonav'] ? '</p></ul>' : '</p></ul></nav>';
            }
    
            wp_reset_postdata();
        }
    
        return $output;
    }

    add_shortcode('recentposts', 'infinitnet_relevant_recent_posts_shortcode');
}

function infinitnet_clear_recent_relevant_posts_cache($post_id) {
    global $wpdb;

    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '%rrp_%'");
}

add_action('save_post', 'infinitnet_clear_recent_relevant_posts_cache');
add_action('delete_post', 'infinitnet_clear_recent_relevant_posts_cache');
add_action('transition_post_status', 'infinitnet_clear_recent_relevant_posts_cache');