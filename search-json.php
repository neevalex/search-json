<?php

/**
 *
 * @link              https://neevalex.com
 * @since             1.0.0
 * @package           Search_JSON
 *
 * @wordpress-plugin
 * Plugin Name:       Search JSON
 * Plugin URI:        https://github.com/neevalex/search-json
 * Description:       Outputs Wordpress search results in JSON format.
 * Version:           1.0.0
 * Author:            NeevAlex
 * Author URI:        https://neevalex.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       search-json
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

add_action('template_redirect',
function ($query)
{
	global $wp;
	$return = array();
	if ($wp->request == 'search-json') {
		
		$the_query = new WP_Query(array('s' => get_search_query()));

		if ($the_query->have_posts()) {
			
			foreach($the_query->posts as $post) {
				array_push($return, array(
					'id' => $post->ID,
					'title' => $post->post_title,
					'url' => get_permalink($post->ID),
					'yoast_meta' => get_post_meta($post->ID, '_yoast_wpseo_metadesc', true), 
					'aioseop_meta' => get_post_meta($post->ID, '_aioseop_description', true), 
					'excerpt' => get_the_excerpt($post->ID)
					));
			}
		}

        header('Content-Type: application/json');
		echo json_encode($return);
		exit;
	}
});