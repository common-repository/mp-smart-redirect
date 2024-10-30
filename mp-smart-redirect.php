<?php
/**
 * Plugin Name: MP Smart Redirect
 * Plugin URI: http://www.markputs.nl
 * Description: Try to redirect 404 to new/right URL, using the URI segments to find the right page. When a page is found 301 redirect to that page.
 * Version: 0.2
 * Author: Mark Puts
 * Author URI: http://www.markputs.nl
 * License: GPL2
 
   Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
defined('ABSPATH') or die("No script kiddies please!");

function mp_detect_404()
{
	/* Check if page is 404 */
    if (is_404())
    {
    	/* Fetch all possible pages from URI and convert it to array */
       $segments = explode('/', trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));
	   
	   /* Search all segments to find a matching page */
	   if(!empty($segments)):
		   foreach($segments as $segment):
			   $newId = url_to_postid($segment);
			   
			   if(!empty($newId))
			   {
			   	 	/* We found a page matching the segment, let's redirect! */
				   wp_redirect( get_permalink( $newId ), 301);
				   exit;
			   }
			   
	
		   endforeach;
		   
		   /* Nothing found in posts, now check for category */
		   foreach($segments as $segment):
			   $category = get_category_by_slug($segment);
			   
			   if(!empty($category))
			   {
			   	 	/* We found a page matching the segment, let's redirect! */
				   wp_redirect( get_category_link( $category->term_id ), 301);
				   exit;
			   }
			   
	
		   endforeach;
		endif;
        
    }
}

/* Add action to detect possible 404's */
add_action('template_redirect', 'mp_detect_404');