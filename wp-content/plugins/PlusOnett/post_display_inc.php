<?php

// use function with case statements! 

	$inc_page = $GLOBALS['inc_page_global'];

	echo '<div class="quote_post"><div class="quote_body">';
	echo 'include loaded, page: ' . $inc_page;
	switch ($inc_page) {
		case 'single':
        	the_content();
			break;
		case 'loop':
			# used for categories
			templ_get_listing_content();
			break;
		case 'rss':
			$item->get_description();
			break;		
	}

	echo '</div>
	<div class="source">';


	if ( ($inc_page == 'single') | ($inc_page == 'loop ') ) {
		echo '<p>';
		$post_id = get_the_ID(); 
		$author = get_post_meta($post_id, 'Author', true);
		$source_Link = get_post_meta($post_id, 'Source Link', true);
		$amazon_link = get_post_meta($post_id, 'Amazon link', true);
		$published_by = get_post_meta($post_id, 'Published by', true);
		if ($author) echo 'Author: ' .  $author .'<br />';
		if ($source_Link) echo '<a href="'.  $source_Link . '" target="_blank">Link to source</a><br />';
		if ($amazon_link) echo '<a href="'.  $amazon_link . '" target="_blank">Buy from Amazon</a><br />';
		if ($published_by) echo 'Published by: ' .  $published_by .'<br />';
		wprp(true);
		echo '</p>';
	} elseif ($inc_page == 'rss') {
		echo '<a href="' . esc_url( $item->get_permalink() ).'" target="_blank">Read More</a><br />Posted '.$item->get_date('j F Y | g:i a') ;
	}

	if (function_exists('the_ratings')) {
		echo '<div style="float: left;">';
		the_ratings();
		echo '</div>';
	}


	if ( ($inc_page == 'rss') | ($inc_page == 'loop ') ) {
		if (function_exists('facebook_comments')) facebook_comments(); 
	} elseif ($inc_page == 'single') {

	}

	// close the quote_post and source DIVs
	echo '</div></div>';


?>