<?php
/*
Plugin Name: JC pagination
Description: Provides global pagination functions
Version: 1.0
Author: Jon Caine
Author URI: 
*/

// function to shorted strings but wont cut whole words and keeps tag pairs in tact
// see http://php.net/manual/en/function.substr.php

function pagination_slice_array ($posts, $posts_per_page, $pageno) {
	if (! $pageno) {
		$pageno = 1;
	}
	
	//echo 'page no: ' . $pageno . '<br />';

	$total_posts = count ($posts); // get count for later

	// work out what post numbers we want for this page
	if ($pageno == 1) {
		$this_page_from = 0;
		$this_page_to = $posts_per_page -1;
	} else {
		$this_page_from = ($pageno - 1) * $posts_per_page; 
		# so if page is 2: (2-1) * 10 - = 10 
		$this_page_to = ($pageno * $posts_per_page) -1 ; 
		# so if page is 2: 2 * 10 - 1 = 19
	}
	// get the posts for this page
	$posts = array_slice($posts, $this_page_from, $posts_per_page);

	// make these vars global for access by pagination nav later
	$GLOBALS['pageno'] = $pageno;
	$GLOBALS['total_posts'] = $total_posts;
	$GLOBALS['posts_per_page'] = $posts_per_page;
	return $posts;
}


function pagination_page_nav ($num_of_nav_buttons, $url_before_pageno, $url_after_pageno) {
	global $pageno, $total_posts, $posts_per_page;
	if ($total_posts > $posts_per_page) {
		echo '<div class="pagination"><div class="Navi">';
		// pagination: page list:
		$num_pages = ceil ($total_posts / $posts_per_page);

		if ($pageno != 1) { // check we arent on the first page
			//echo '<a href="/blog/category/' . $current_category . '/1">&laquo; First</a>';
			echo '<a href="' . $url_before_pageno . '1' . $url_after_pageno . '">&laquo; First</a>';
			$prevpageno = $pageno - 1;
			//echo '<a href="/blog/category/' . $current_category . '/' . $prevpageno .'"><strong><</strong></a>';
			echo '<a href="' . $url_before_pageno . $prevpageno . $url_after_pageno . '"><strong><</strong></a>';
		}

		/*		
		if ($num_pages > $num_of_nav_buttons) {
			if ($pageno > $num_of_nav_buttons) {
				$set_start = $pageno - $num_of_nav_buttons;
				$set_end = $pageno;
			} else {
				$set_start = 1;
				$set_end = $num_of_nav_buttons;
			}
		*/
		if ($num_pages > $num_of_nav_buttons) {
			$num_of_sets = ceil ($num_pages / $num_of_nav_buttons);
			// find the set we are in
			for ($myset = 1; $myset <= $num_of_sets; $myset++) {
				if ($myset == 1) {
					$set_start = 1;
					$set_end = $num_of_nav_buttons;
				} else {
					$set_start = (($myset - 1) * $num_of_nav_buttons) + 1;
					$set_end = $myset * $num_of_nav_buttons;
				}
				if (($pageno >= $set_start) && ($pageno <= $set_end)) {
					break;
				}
			}
			if ($set_end > $num_pages) {
				$set_end = $num_pages;
			}
		} else {
			$set_start = 1;
			$set_end = $num_pages;
		}

		for ($i = $set_start; $i <= $set_end; $i++) {
			if ($i == $pageno) {
				echo '<strong class="on">'.$i.'</strong>';
			} else {
				//echo '<a href="/blog/category/' . $current_category . '/' . $i .'">'.$i.'</a> ';
				echo '<a href="' . $url_before_pageno . $i . $url_after_pageno . '">' . $i . '</a>';
			}
		}
		// next post link
		$nextpageno = $pageno + 1;
		if ($pageno != $num_pages) { // check we arent on the last page
			//echo '<a href="/blog/category/' . $current_category . '/' . $nextpageno .'"><strong>></strong></a>';
			echo '<a href="' . $url_before_pageno . $nextpageno . $url_after_pageno . '"><strong>></strong></a>';

			// last post link
			//echo '<a href="/blog/category/' . $current_category . '/' . $num_pages .'">Last &raquo;</a>';
			echo '<a href="' . $url_before_pageno . $num_pages . $url_after_pageno . '">Last &raquo;</a>';
		}
		echo '</div></div>';
	}
}


?>