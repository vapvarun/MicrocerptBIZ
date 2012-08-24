<?php templ_before_loop(); // before loop hooks?>
<?php if ( have_posts() ) : ?>
<div id="loop" class="<?php if ($_COOKIE['mode'] == 'grid') echo 'grid'; else echo 'list clear'; ?> ">
  <?php 
	$pcount=0; 
	while ( have_posts() ) : the_post(); 
	$pcount++;
	?>
    
    
    
    
  <div <?php post_class('post'); ?> id="post_<?php the_ID(); ?>">
   
    			        

   		<!-- /// adding this div just to hide the title //// -->
		<div name="hiddentitle" style="visibility:hidden">
        <!--  Post Title Condition for Post Format-->
        <?php if ( has_post_format( 'chat' )){?>
        <h3><a href="<?php the_permalink() ?>">
          <?php the_title(); ?>
          </a></h3>
         <?php }elseif(has_post_format( 'gallery' )){?>
          <h3><a href="<?php the_permalink() ?>">
          <?php the_title(); ?>
          </a></h3>
         <?php }elseif(has_post_format( 'image' )){?>
           <h3><a href="<?php the_permalink() ?>">
          <?php the_title(); ?>
          </a></h3>
         <?php }elseif(has_post_format( 'link' )){?>
           <h3><a href="<?php the_permalink() ?>">
          <?php the_title(); ?>
          </a></h3>
         <?php }elseif(has_post_format( 'video' )){?>
           <h3><a href="<?php the_permalink() ?>">
          <?php the_title(); ?>
          </a></h3>
         <?php }elseif(has_post_format( 'audio' )){?>
           <h3><a href="<?php the_permalink() ?>">
          <?php the_title(); ?>
          </a></h3>
           <?php }else{?>
           <h3><a href="<?php the_permalink() ?>">
          <?php the_title(); ?>
          </a></h3>
           <?php }?>
         <!--  Post Title Condition for Post Format-->
			</div>
                                    
        <div class="post-meta" style="visibility: hidden;">
             <?php if(templ_is_show_listing_author()){?>
              by <span class="post-author"> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Posts by <?php the_author(); ?>">
              <?php the_author(); ?>
              </a> </span>
              <?php } ?>
            
            
            <?php if(templ_is_show_listing_date()){?>
              on <span class="post-date">
              <?php the_time(templ_get_date_format()) ?>
              </span>
              <?php } ?>
            
            
              <?php  if(templ_is_show_listing_category()){?>
            	 <?php the_taxonomies(array('before'=>'<span class="post-category">','sep'=>'</span><span class="post-tags-none">','after'=>'</span>')); ?>
             <?php } ?>
             
             <?php  if(templ_is_show_post_tags()){?>
            	 <?php the_taxonomies(array('before'=>'<span class="post-category-none">','sep'=>'</span><span class="post-tags">','after'=>'</span>')); ?>
             <?php } ?>
            
            
            <?php if(templ_is_show_listing_comment()){?>
         <span class="single_comments"> <?php comments_popup_link(__('0','templatic'), __('1','templatic'), __('%','templatic'), '', __('Closed','templatic')); ?></span>  
          <?php } ?>
            
         
            
			
        </div> <!-- post meta #end--> 
                                     
    <?php templ_before_loop_post_content(); // before loop post content hooks?>
     <!--  Post Content Condition for Post Format-->
    <?php if ( has_post_format( 'chat' )){?>
    
    <div class="post-content blog_list_content">
      <?php templ_get_listing_content()?>
    </div>
    
    <?php }elseif(has_post_format( 'gallery' )){?>
    
    <div class="post-content blog_list_content">
       <?php 
            if(get_the_post_thumbnail( $post->ID)){?>
      <a href="<?php the_permalink(); ?>"> <?php echo get_the_post_thumbnail( $post->ID, array(100,100),array('class'	=> "alignleft",));?> </a>
      <?php }elseif($post_images = bdw_get_images($post->ID,'thumb')){ ?>
      <a  href="<?php the_permalink(); ?>"> <img class="alignleft" src="<?php echo templ_thumbimage_filter($post_images[0],'&amp;w=100&amp;h=100&amp;zc=1&amp;q=80');?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
      <?php
            }?>
            
            
            <?php 
	/*global $wpdb,$custom_post_meta_db_table_name;
	$post_meta_info = $wpdb->get_results("select * from $custom_post_meta_db_table_name where post_type = 'listing'"); */
	
	if(get_post_meta($post->ID,"listing_img",true)){?>
			<a href="<?php the_permalink() ?>"><img src="<?php echo templ_thumbimage_filter(get_post_meta($post->ID,"listing_img",true),'&amp;w=121&amp;h=115&amp;zc=1&amp;q=80');?>" alt=""  /></a>
		<?php }
		else{ ?>
			
		<?php }
		?>	
            
    </div>
    
     <?php }elseif(has_post_format( 'image' )){?>
      <div class="post-content blog_list_content">
      
      <?php 
            if(get_the_post_thumbnail( $post->ID)){?>
      <a href="<?php the_permalink(); ?>"> <?php echo get_the_post_thumbnail( $post->ID, array(150,150),array('class'	=> "alignleft",));?> </a>
      <?php }elseif($post_images = bdw_get_images($post->ID,'thumb')){ ?>
      <a  href="<?php the_permalink(); ?>"> <img class="alignleft" src="<?php echo templ_thumbimage_filter($post_images[0],'&amp;w=150&amp;h=150&amp;zc=1&amp;q=80');?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
      <?php
            }?>
            
            
            <?php 
	/*global $wpdb,$custom_post_meta_db_table_name;
	$post_meta_info = $wpdb->get_results("select * from $custom_post_meta_db_table_name where post_type = 'listing'"); */
	
	if(get_post_meta($post->ID,"listing_img",true)){?>
			<a href="<?php the_permalink() ?>"><img src="<?php echo templ_thumbimage_filter(get_post_meta($post->ID,"listing_img",true),'&amp;w=121&amp;h=115&amp;zc=1&amp;q=80');?>" alt=""  /></a>
		<?php }
		else{ ?>
			
		<?php }
		?>	
            
            
      <?php templ_get_listing_content()?>
    </div>
    
     <?php }elseif(has_post_format( 'link' )){?>
     
      <div class="post-content blog_list_content">
      <?php templ_get_listing_content()?>
      </div>
      
     <?php }elseif(has_post_format( 'video' )){?>
     
     <div class="post-content blog_list_content">
      <?php templ_get_listing_content()?>
      </div>
      
     <?php }elseif(has_post_format( 'audio' )){?>
     
     <div class="post-content blog_list_content">
      <?php templ_get_listing_content()?>
      </div>
      
     <?php }elseif(has_post_format( 'quote' )){?> 
     
     <div class="post-content blog_list_content">
      <?php templ_get_listing_content()?>
      </div>
      
     <?php }elseif(has_post_format( 'status' )){?> 
     
     <div class="post-content blog_list_content">
      <?php templ_get_listing_content()?>
      </div>
      
      <?php }else{?>
      <div class="post-content blog_list_content">
        <?php 
		if(strtolower(get_option('ptthemes_image_position'))=='left' || get_option('ptthemes_image_position')==''){
			$display_style="left_img";
		}
		else if(strtolower(get_option('ptthemes_image_position'))=='right'){
			$display_style="right_img";
		}
		else if(strtolower(get_option('ptthemes_image_position'))=='display none'){
			$display_style="none_display";
		}
		
            if(get_the_post_thumbnail( $post->ID)){?>
				<a href="<?php the_permalink(); ?>" class="<?php echo $display_style; ?>"> <?php echo get_the_post_thumbnail( $post->ID, array(100,100),array('class'	=> "alignleft",));?> </a>
		<?php }
		elseif(get_post_meta($post->ID,"listing_img",true)){?>
			<a href="<?php the_permalink() ?>" class="<?php echo $display_style; ?>"><img src="<?php echo templ_thumbimage_filter(get_post_meta($post->ID,"listing_img",true),'&amp;w=121&amp;h=115&amp;zc=1&amp;q=80');?>" alt=""  /></a>
		<?php }
		elseif($post_images = bdw_get_images($post->ID,'thumb')){ ?>
				<a  href="<?php the_permalink(); ?>" class="<?php echo $display_style; ?>"> <img class="alignleft" src="<?php echo templ_thumbimage_filter($post_images[0],'&amp;w=121&amp;h=115&amp;zc=1&amp;q=80');?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"  /> </a>
      <?php
            }?>  
			<div class="quote_post">
				<div class="quote_body">
			        <?php templ_get_listing_content()?>
				</div>
				<div class="source">
					<p>
					<?php 
					$post_id = get_the_ID(); 
					$author = get_post_meta($post_id, 'Author', true);
					$title = get_post_meta($post_id, 'Title', true);
					$source_Link = get_post_meta($post_id, 'Source Link', true);
					$amazon_link = get_post_meta($post_id, 'Amazon link', true);
					$published_by = get_post_meta($post_id, 'Published by', true);
					if ($author) echo 'Author: —' .  $author .'<br />';
					if ($title) echo 'Title: ' .  $title .'<br />';
                                        if ($source_Link) echo '<a href="'.  $source_Link . '" target="_blank">Link to source</a><br />';
					if ($amazon_link) echo '<a href="'.  $amazon_link . '" target="_blank">Buy on Amazon</a><br />';
					if ($published_by) echo 'Published by: ' .  $published_by .'<br />';					
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
     </div>
      <?php }?>  
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
        
  <?php endwhile; ?>
</div>
<?php else : ?>	
<?php get_search_form(); ?>
<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.', 'templatic' ); ?></p>
<?php endif; ?>
<?php templ_after_loop(); // after loop hooks?>

