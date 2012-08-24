<?php
/*
Plugin Name: Custom functions
Description: Provides functions....
Version: 1.0
Author: Jon Caine
Author URI: 
*/


/*********************************************/
// only used by post id stuff in show post function
function ConvertFromArbitraryBase($Str, $Chars)
/*********************************************/
{
    /*
        Converts from an arbitrary-base string to a decimal string
    */

    if (ereg('^[' . $Chars . ']+$', $Str))
    {
        $Result = '0';

        for ($i=0; $i<strlen($Str); $i++)
        {
            if ($i != 0) $Result = bcmul($Result, strlen($Chars));
            $Result = bcadd($Result, strpos($Chars, $Str[$i]));
        }

        return $Result;
    }

    return false;
}


// function to shorted strings but wont cut whole words and keeps tag pairs in tact
// see http://php.net/manual/en/function.substr.php

function mb_substrws($text, $length = 180) { 
    if((mb_strlen($text) > $length)) { 
        $whitespaceposition = mb_strpos($text, ' ', $length) - 1; 
        if($whitespaceposition > 0) { 
            $chars = count_chars(mb_substr($text, 0, ($whitespaceposition + 1)), 1); 
            if ($chars[ord('<')] > $chars[ord('>')]) { 
                $whitespaceposition = mb_strpos($text, ">", $whitespaceposition) - 1; 
            } 
            $text = mb_substr($text, 0, ($whitespaceposition + 1)); 
        } 
        // close unclosed html tags 
        if(preg_match_all("|(<([\w]+)[^>]*>)|", $text, $aBuffer)) { 
            if(!empty($aBuffer[1])) { 
                preg_match_all("|</([a-zA-Z]+)>|", $text, $aBuffer2); 
                if(count($aBuffer[2]) != count($aBuffer2[1])) { 
                    $closing_tags = array_diff($aBuffer[2], $aBuffer2[1]); 
                    $closing_tags = array_reverse($closing_tags); 
                    foreach($closing_tags as $tag) { 
                            $text .= '</'.$tag.'>'; 
                    } 
                } 
            } 
        } 

    } 
    return $text; 
} 

/**
following function is reverse of stip_tags
example usdage:

echo strip_only($str, array('p', 'h1'));
echo strip_only($str, '<p><h1>');
**/

function strip_only($str, $tags) {
    if(!is_array($tags)) {
        $tags = (strpos($str, '>') !== false ? explode('>', str_replace('<', '', $tags)) : array($tags));
        if(end($tags) == '') array_pop($tags);
    }
    foreach($tags as $tag) $str = preg_replace('#</?'.$tag.'[^>]*>#is', '', $str);
    return $str;
}



// add re-write stuff for category page pagination

add_filter( 'rewrite_rules_array','my_insert_rewrite_rules' );
add_filter( 'query_vars','my_insert_query_vars' );
add_action( 'wp_loaded','my_flush_rules' );

// flush_rules() if our rules are not yet included
function my_flush_rules(){
	$rules = get_option( 'rewrite_rules' );

	if ( ! isset( $rules['blog/category/(.+?)/?([0-9]{1,})/?$'] ) ) {
		global $wp_rewrite;
	   	$wp_rewrite->flush_rules();
	}
}

// Adding a new rule
function my_insert_rewrite_rules( $rules )
{
	$newrules = array();
	$newrules['blog/category/(.+?)/?([0-9]{1,})/?$'] = 'index.php?category_name=$matches[1]&pageno=$matches[2]';
	return $newrules + $rules;
}

// Adding the id var so that WP recognizes it
function my_insert_query_vars( $vars )
{
    array_push($vars, 'pageno');
    return $vars;
}

// === add meta ====

function add_meta_to_posts_array ($posts_array) {
	$posts_array_with_meta = array ();	
	foreach ($posts_array AS $key => $single_post) {
		$posts_array_with_meta[$key] = add_meta_to_single_post ($single_post);
	}
	return $posts_array_with_meta;
}

function add_meta_to_single_post ($post) {
	$tmp_post_array_with_meta = $post;
	$post_id = $post->ID;
	$tmp_post_array_with_meta->meta_author = get_post_meta($post_id, 'Author', true);
	$tmp_post_array_with_meta->source_type = get_post_meta($post_id, 'Source Type', true);
	$tmp_post_array_with_meta->source_Link = get_post_meta($post_id, 'Source Link', true);
	$tmp_post_array_with_meta->meta_title = get_post_meta($post_id, 'Title', true);
	$tmp_post_array_with_meta->meta_source_Link = get_post_meta($post_id, 'Source Link', true);
	$tmp_post_array_with_meta->meta_amazon_link = get_post_meta($post_id, 'Amazon link', true);
	$tmp_post_array_with_meta->meta_published_by = get_post_meta($post_id, 'Published by', true);
	// for rss
	$rss_sourcepermalink = get_post_meta($post_id, 'wpe_sourcepermalink', true);
	if ($rss_sourcepermalink) $tmp_post_array_with_meta->rss_permalink = $rss_sourcepermalink;
	$vapvarun = $tmp_post_array_with_meta->meta_author;
	$vapvarun = strtolower($vapvarun);
	$sPattern = '/\s*/m'; 
	$sReplace = '';
	$addon_author_url = preg_replace( $sPattern, $sReplace, $vapvarun );
	$main_site_url = get_site_url();
	$new_author_url = $main_site_url . '/' . $addon_author_url;
	if (site_url() == "http://www.microcerpt.com" )
	{	
		$tmp_post_array_with_meta->author_site_url = $new_author_url;
		
	}
	else{
			$tmp_post_array_with_meta->author_site_url = site_url();
	}
	
	$tmp_post_array_with_meta->read_more_link = $post->guid;
	return $tmp_post_array_with_meta;
}


// === show posts ====

/*
inputs are objects that contain:
$post->post_content
$post->rss_permalink
$post->meta_author
$post->meta_title
$post->source_type
$post->meta_source_Link
$post->meta_amazon_link
$post->meta_published_by
$post->guid
// new
$post->read_more_link
$show->facebook
$show->report
$show->readmore
$show->ratings
$show->single_page
*/

function show_post ($post, $show) {
	//print_r ($post);
	$post_content = $post->post_content;
	if  (($show->single_page) && ($post->rss_permalink)) {
		// strip out links
		$post_content = strip_only($post_content, '<a>');
	} else {
		// not home page for an rss item
		// replace br with space
		$post_content = str_replace(array("<br />","<br>"), " ", $post_content);
		// strip out html
		$post_content = strip_tags($post_content);
	}
	// only display the post if there is some contents after we have stripped out html (eg not just an image that has been removed)
	if ($post_content != "") {
		echo '<div ';
		post_class('post');
		echo 'id="post_' . $post->ID . '">';
		// title
		if  (($show->single_page) && ($post->rss_permalink))
			echo '<div class="quote_title">' . $post->post_title . '</div>';

		echo '<div class="quote_post"><div class="quote_body">';
		if ((strlen($post_content) > 300 ) && ! ($show->single_page)) {
			
			echo mb_substrws($post_content, 300) . '...';
		
		
		} else {
			$longpost = true;
			echo $post_content;
		
		}
		echo '</div><div class="source"><p>';
		if ($show->readmore && $longpost && (!is_single())) {
		

			$readmorelink = post_permalink();  // $post->read_more_link;
			 echo do_shortcode('[like]');
			echo '<a href="' . esc_url( $readmorelink ) .'">Know more...</a><br />';
		}
		if ($show->readmore && (!$longpost) && (!is_single())) {
		

			$readmorelink = post_permalink();  // $post->read_more_link;
			 echo do_shortcode('[like]');
			echo '<a href="' . esc_url( $readmorelink ) .'">Your feedback</a><br />';
		}
/**
			if (isset($post->rss_permalink)) {
				
			} else {
			}
**/
		if ($post->meta_author) echo '<a href="' . $post->author_site_url .'">Author: ' .  $post->meta_author .'</a><br />';
		if ($post->meta_title) echo 'Title: ' .  $post->meta_title .'<br />';
		if ($post->source_type) echo 'Source Type: ' .  $post->source_type .'<br />';					
		if (isset($post->rss_permalink)) {
			echo '<a href="'.  $post->rss_permalink . '" target="_blank">Link to source</a><br />';			
		} else if ($post->meta_source_Link) { 
			echo '<a href="'.  check_url_for_http($post->meta_source_Link) . '" target="_blank">Link to source</a><br />';
		}
		if ($post->meta_amazon_link) echo '<a href="'.  check_url_for_http($post->meta_amazon_link) . '" target="_blank">Buy on Amazon</a><br />';
		if ($post->meta_published_by) echo 'Published by: ' .  $post->meta_published_by .'<br />';
		if ($show->report) reportpostemail ($post->guid);
		//reportpostemail ($post->author_site_url . '?p=' . $post->ID);
		echo '</p>';
		
		if ($show->ratings or $show->facebook) {
			if ($post->guid) {
				$postUrl = $post->guid;
			} else {
				$postUrl = $post->rss_permalink;
			}
			$postId = md5 ($postUrl);
			// $postId = ConvertFromArbitraryBase (md5($postUrl), '0123456789abcdef');
			echo '<table>';
		}
		if ($show->ratings) {
			if (function_exists('the_ratings')) {
				echo "<tr><td>";
				echo '<div style="float: left;">';
				// the_ratings();
				the_ratings($start_tag = 'div', $custom_id = $post->ID);
				// the_ratings($start_tag = 'div', $custom_id = $postId);
				echo '</div>';
				echo "</td></tr>";
			}
		}
		if ($show->facebook) {
			if (function_exists('facebook_comments')) { 
				echo "<tr><td>";
				$postTitle = $post->post_title;
				facebook_comments($comments='', $postId, $postTitle, $postUrl);
				echo "</td></tr>";
			}
		}
		if ($show->ratings or $show->facebook) {
			echo '</table>';
		}
		echo '</div></div></div>';
	}
}


// remove generatore info from html (and other outputs) source 
function i_want_no_generators()
{
return '';
}
add_filter('the_generator','i_want_no_generators');

/////// add http to urls that don't start with http /////

function check_url_for_http ($url) {
	if (! preg_match( '/^' . 'http:\/\/' . '/i', $url)) { 
		$url = 'http://' . $url;
	}
	return $url;	
}

/**
 	create standard pages for new blogs
**/
function create_default_pages ($blog_id) {
   switch_to_blog($blog_id);
   // switch theme
   $postdata = array('post_parent' => 0,
		'menu_order' => 0, //If new post is a page, sets the order should it appear in the tabs.
		'post_status' => 'publish',
		'post_title'   => 'About',
		'post_name'  => 'about', /* the slug */
		'post_content' => 'Sorry, I have nothing to tell you about myself. :( ',
		'post_type'   => 'page');
   $newid = wp_insert_post($postdata);
   if ($newid && !is_wp_error($newid)) {
      add_meta($newid);
   } else {
		error_log ('create_default_pages: could not create page for blog id: ' . $blog_id);
   }

   $postdata = array('post_parent' => 0,
		'menu_order' => 1, //If new post is a page, sets the order should it appear in the tabs.
		'post_status' => 'publish',
		'post_title'   => 'Events',
		'post_name'  => 'events', /* the slug */
		'post_content' => 'Sorry, I have no events to tell you about. :( ',
		'post_type'   => 'page');
   $newid = wp_insert_post($postdata);
   if ($newid && !is_wp_error($newid)) {
      add_meta($newid);
   } else {
		error_log ('create_default_pages: could not create page for blog id: ' . $blog_id);
   }


   $postdata = array('post_parent' => 0,
		'menu_order' => 2, //If new post is a page, sets the order should it appear in the tabs.
		'post_status' => 'publish',
		'post_title'   => 'Links',
		'post_name'  => 'links', /* the slug */
		'post_content' => 'Sorry, I have no links. :( ',
		'post_type'   => 'page');
   $newid = wp_insert_post($postdata);
   if ($newid && !is_wp_error($newid)) {
      add_meta($newid);
   } else {
		error_log ('create_default_pages: could not create page for blog id: ' . $blog_id);
   }

}
 
add_action('wpmu_new_blog', 'create_default_pages');




?>
