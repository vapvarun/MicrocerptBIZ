<div id="AdSpeed" style="top: 20px; position: absolute; left: 20px;">
<?php if (in_category('fly-fishing')) { ?>  
	<!-- AdSpeed.com Serving Code 7.9.5 for [Zone] Test Zone [Any Dimension] -->
	<script type="text/javascript" src="http://www.adserver.microcerpt.com/ad.php?do=js&zid=30616&wd=-1&ht=-1&target=_top"></script>
	<!-- AdSpeed.com End -->
<?php } ?>
</div>

<?php templ_before_loop(); // before loop hooks?>
<div id="loop" class="<?php if ($_COOKIE['mode'] == 'grid') echo 'grid'; else echo 'list clear'; ?> ">
  <?php 
		global $paged;
		// need cat ID to set in queries
		$queried_category = get_term( get_query_var('cat'), 'category' ); 
		$catID = $queried_category->term_id;
		//echo 'cat id: ' . $catID .'<br /><br />';
		// get data from master site
		$args = array( 'cat' => $catID, 'posts_per_page' => '10', 'paged' => $paged);
		// $args = array( 'cat' => $catID, 'posts_per_page' => '3' );
	
		function add_meta ($posts_array) {
			$posts_array_with_meta = array ();
			foreach ($posts_array AS $key => $single_post) {
				$tmp_post_array_with_meta = $single_post;
				// $tmp_post_array_with_meta->MYVAR = 'something good! - ' . $key;
				
				// $post_id = get_the_ID(); 
				$post_id = $single_post->ID;
				$tmp_post_array_with_meta->meta_author = get_post_meta($post_id, 'Author', true);
				$tmp_post_array_with_meta->source_type = get_post_meta($post_id, 'Source Type', true);
				$tmp_post_array_with_meta->source_Link = get_post_meta($post_id, 'Source Link', true);
				$tmp_post_array_with_meta->meta_title = get_post_meta($post_id, 'Title', true);
				$tmp_post_array_with_meta->meta_source_Link = get_post_meta($post_id, 'Source Link', true);
				$tmp_post_array_with_meta->meta_amazon_link = get_post_meta($post_id, 'Amazon link', true);
				$tmp_post_array_with_meta->meta_published_by = get_post_meta($post_id, 'Published by', true);
				$tmp_post_array_with_meta->author_site_url = site_url();
								
				$posts_array_with_meta[$key] = $tmp_post_array_with_meta;
			}
			return $posts_array_with_meta;
		}

		$posts_parent_orig = get_posts( $args );
		$posts_parent = add_meta ($posts_parent_orig);
		
		// get data from author sites

		$blog_list = get_blog_list( 0, 'all' );
		foreach ($blog_list AS $blog) {
		    // echo 'Blog '.$blog['blog_id'].': '.$blog['domain'].$blog['path'].'<br />';

			// $other_blog_id = 15;
			$author_blog_id = $blog['blog_id'];
			// exclude master/parent site blog.. consider making this an array or regex if need to exlude others
			if ($author_blog_id != 1) {
				switch_to_blog($author_blog_id);
				global $wpdb;
				$wpdb->terms = "wp_terms";
				$wpdb->term_taxonomy = "wp_term_taxonomy";
				$wpdb->term_relationships = "wp_term_relationships";
				$posts_author_orig = get_posts( $args );
				$posts_author = add_meta ($posts_author_orig);

				// if first author.. ie no $posts_authors_all array
				if(!$posts_authors_all) {
					$posts_authors_all = $posts_author;
				} else {
					$posts_authors_all_tmp = array_merge($posts_authors_all, $posts_author);
					$posts_authors_all = $posts_authors_all_tmp;
				}
			}
			restore_current_blog();
		}

		
		$posts_combined = array_merge($posts_parent, $posts_authors_all);
		if (in_category('xxreligion')) { 
			print_r ($posts_combined);
		}
		
		function sort_posts_array_by_post_date($a, $b) {
		    if ($a->post_date == $b->post_date)
		        return 0;
		    return $a->post_date > $b->post_date ? -1 : 1;
		}
		
		usort($posts_combined, 'sort_posts_array_by_post_date');

		// mow lets display the posts!
		foreach( $posts_combined as $post ) :	setup_postdata($post);
	?>
    
    
    	<!-- <a href="<?php the_guid(); ?>"><?php the_title(); ?></a> - <?php the_guid(); ?> -->
	
    
  <div <?php post_class('post'); ?> id="post_<?php the_ID(); ?>">
   		<!-- /// adding this div just to hide the title //// -->
		<?php if (in_category('xxreligion')) { ?>
			<div>
		<?php } else { ?>
			<div name="hiddentitle" style="visibility:hidden">
		<?php } ?>
        <!--  Post Title Condition for Post Format-->
           <h3><a href="<?php the_permalink() ?>">
          <?php the_title(); ?>
          </a><?php echo ' - '.get_the_date().' - '.$post->guid.'<br />'; ?></h3>
			</div>
                                     
    <?php templ_before_loop_post_content(); // before loop post content hooks ?>
            
			<div class="quote_post">
				<div class="quote_body">
			        <?php 
						// only for non microcerpt posts (ie rss)
						if (! preg_match("/" . preg_quote(site_url(), '/') . "/", $post->guid)) { 
							$rss_post = true;
							// strip out html except p tags
							$post_content = strip_tags($post->post_content, '<p>');
							if (strlen($post_content) > 550 ) {
								echo mb_substrws($post_content, 550) . '...';
							} else {
								echo $post_content;
							}
						} else {
							templ_get_listing_content();
							$rss_post = false;
						}
					?>
				</div>
				<div class="source">
					<p>
					<?php 
						if (in_category('xxfly-fishing')) {
							echo '<small>'. $post->author_site_url .'</small><br />';
						}
										
					if ($rss_post) { // only do read more for non microcerpt posts (ie rss)
						echo '<a href="' . esc_url( $post->guid ).'" target="_blank">Read More</a><br />';
					}

					if ($post->meta_author) echo '<a href="' . $post->author_site_url .'">Author: ' .  $post->meta_author .'</a><br />';
					if ($post->meta_title) echo 'Title: ' .  $post->meta_title .'<br />';
					if ($post->source_type) echo 'Source Type: ' .  $post->source_type .'<br />';					
					if ($post->meta_source_Link) echo '<a href="'.  $post->meta_source_Link . '" target="_blank">Link to source</a><br />';
					if ($post->meta_amazon_link) echo '<a href="'.  $post->meta_amazon_link . '" target="_blank">Buy on Amazon</a><br />';
					if ($post->meta_published_by) echo 'Published by: ' .  $post->meta_published_by .'<br />';
					reportpostemail ($post->guid);
					echo '</p>';
					if (function_exists('the_ratings')) {
						echo '<div style="float: left;">';
						the_ratings();
						echo '</div>';
					}
                    if (function_exists('facebook_comments')) facebook_comments();
					?>
				</div>
			</div>
			
    <!--  Post Content Condition for Post Format-->
     <?php templ_after_loop_post_content(); // after loop post content hooks?>
  </div>

		<?php 
        $page_layout = templ_get_page_layout();
        if($page_layout=='full_width'){
                    if($pcount==3){
                    $pcount=0; 
                    ?>
                        <div class="hr clearfix"></div>
                <?php } }
                else if(($page_layout=='3_col_fix' ) || ($page_layout=='3_col_right') ||( $page_layout=='3_col_left')){
                    if($pcount==2){
                    $pcount=0; 
                    ?>
                        <div class="hr clearfix"></div>
                <?php }
                }
                else if ($pcount==2){
                    $pcount=0; 
                    ?>
                <div class="hr clearfix"></div>
                <?php }?>
        
  <?php endforeach; // JC ?>

</div>

<?php templ_after_loop(); // after loop hooks?>

