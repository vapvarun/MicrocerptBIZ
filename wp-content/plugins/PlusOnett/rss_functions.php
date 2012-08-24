<?php 
	function get_rss ($rss_url, $title, $mymaxitems) {

		echo '<h2>' . $title .'</h2>';
		// Get RSS Feed(s)
		include_once(ABSPATH . WPINC . '/feed.php');

		// Get a SimplePie feed object from the specified feed source.
		// $rss = fetch_feed('http://news.google.com/news?ned=us&topic=w&output=rss');
		// $rss = fetch_feed('http://www.theregister.co.uk/hardware/pc_chips/headlines.atom');
		// $rss = fetch_feed('http://www.moldychum.com/home-old/rss.xml'); // clean with some images
		// $rss = fetch_feed('http://www.midcurrent.com/news/index.rdf'); // clean
		// $rss = fetch_feed('http://news.yahoo.com/rss/world'); // clean with some images
		// $rss = fetch_feed('http://mf.feeds.reuters.com/reuters/UKWorldNews'); // advert and bookmark/email buttons
		$rss = fetch_feed($rss_url);

		if (!is_wp_error( $rss ) ) : // Checks that the object is created correctly 
		    // Figure out how many total items there are, but limit it to 5. 
		    $maxitems = $rss->get_item_quantity($mymaxitems); 

		    // Build an array of all the items, starting with element 0 (first element).
		    $rss_items = $rss->get_items(0, $maxitems); 
		endif;

		if ($maxitems == 0) { 
			echo '<li>No items.</li>';
		} else {
		    // Loop through each feed item and display each item as a hyperlink.
		    foreach ( $rss_items as $item ) {
				echo '<div class="post">
					<div class="quote_post">
						<div class="quote_body">'
							. $item->get_description() . '
						</div>
						<div class="source"><a href="' . esc_url( $item->get_permalink() ).'" target="_blank">Read More</a><br />Posted '.$item->get_date('j F Y | g:i a') ;
						/* disable this for the moment as needs a post id for each item.  */
						if (function_exists('the_ratings')) {
							echo '<div style="float: left;">';
							the_ratings('div', crc32($item->get_id()));
							echo '</div>';
						}
						
						if (function_exists('facebook_comments')) facebook_comments(); 
				echo '</div></div></div> ';
			}
		}
	}
?>