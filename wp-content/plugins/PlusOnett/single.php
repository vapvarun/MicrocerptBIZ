<?php get_header(); ?>
<div  class="<?php templ_content_css();?>" >
<div class="content_top"></div>
	<div class="content_bg">
    
<!--  CONTENT AREA START -->
	<?php templ_before_single_entry(); // before single entry  hooks?>
    <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
    
    
    <?php templ_page_title_above(); //page title above action hook?>
    <div class="detail-title" style="visibility:hidden">
				 
				
          <?php // echo templ_page_title_filter(get_the_title()); //page tilte filter?>
          
            <!--  Post Title Condition for Post Format-->
            <?php if ( has_post_format( 'chat' )){?>
            
             <?php  echo templ_page_title_filter(get_the_title()); //page tilte filter?>
             
            <?php }elseif(has_post_format( 'gallery' )){?>
            
            <?php  echo templ_page_title_filter(get_the_title()); //page tilte filter?>
            
            <?php }elseif(has_post_format( 'image' )){?>
            
           <?php  echo templ_page_title_filter(get_the_title()); //page tilte filter?>
           
            <?php }elseif(has_post_format( 'link' )){?>
            
           <?php  echo templ_page_title_filter(get_the_title()); //page tilte filter?>
           
            <?php }elseif(has_post_format( 'video' )){?>
            
             <?php  echo templ_page_title_filter(get_the_title()); //page tilte filter?>
             
            <?php }elseif(has_post_format( 'audio' )){?>
            
             <?php  echo templ_page_title_filter(get_the_title()); //page tilte filter?>
             
            <?php }else{?>
            
            <?php  echo templ_page_title_filter(get_the_title()); //page tilte filter?>
            
            <?php }?>
            <!--  Post Title Condition for Post Format-->				
			</div>
    		
            <?php templ_page_title_below(); //page title below action hook?>
    
    
    
    <div class="entry">
      <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
      
      
      		 <div class="post-meta">
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
            <span class="post-category">
              <?php the_category(' / '); ?>
			    </span> 
            <?php } ?>
            <?php if(templ_is_show_listing_comment()){?>
         <span class="single_comments"> <?php comments_popup_link(__('0','templatic'), __('1','templatic'), __('%','templatic'), '', __('Closed','templatic')); ?></span>  
          <?php } ?>
          
           
         
            
			
        </div> <!-- post meta #end--> 
     
         
        <?php  templ_before_single_post_content(); // BEFORE  single post content  hooks?>
        
        <!--  Post Content Condition for Post Format-->
        <?php if ( has_post_format( 'chat' )){?>
        
        <div class="post-content">
        <?php the_content(); ?>
        </div>
        
        <?php }elseif(has_post_format( 'gallery' )){?>
        
        <div class="post-content">
        <?php the_content(); ?>
        </div>
        
        <?php }elseif(has_post_format( 'image' )){?>
        
        <div class="post-content">
        
        <?php the_content(); ?>
        </div>
        
        <?php }elseif(has_post_format( 'link' )){?>
        
        <div class="post-content">
        <?php the_content(); ?>
        </div>
        
        <?php }elseif(has_post_format( 'video' )){?>
        
        <div class="post-content">
        <?php the_content(); ?>
        </div>
        
        <?php }elseif(has_post_format( 'audio' )){?>
        
        <div class="post-content">
        <?php the_content(); ?>
        </div>
        
        <?php }elseif(has_post_format( 'quote' )){?> 
        
        <div class="post-content">
        <?php the_content(); ?>
        </div>
        
        <?php }elseif(has_post_format( 'status' )){?> 
        
        <div class="post-content">
        <?php the_content(); ?>
        </div>
        
        <?php }else{?>
        
        <div class="post-content">
			<div class="quote_post">
				<div class="quote_body">
			        <?php the_content(); ?>
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
					wprp(true);
					echo '</p>';
					if (function_exists('the_ratings')) {
						echo '<div style="float: left;">';
						the_ratings();
						echo '</div>';
					}
					?>
				</div>
			</div>
        </div>
         
        
        <?php }?>  
        <!--  Post Content Condition for Post Format-->
        
        <?php templ_after_single_post_content(); // after single post content hooks?>
       <!-- twitter & facebook likethis option-->
       
        
      </div>
      
       
        
         
      
      
      <div class="post-meta bottom_meta bottom_meta_blog left">
						
						<?php if(templ_is_show_listing_tags()){?>
							<?php the_tags('<span class="post-tags">', ', ', '</span>'); ?>
                         <?php } ?>

						<div class="post-share">
           
           <div class="addthis_toolbox addthis_default_style">
<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c873bb26489d97f" class="addthis_button_compact sharethis"><?php _e('Share');?></a>
</div>
           </div> <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c873bb26489d97f"></script>
					 
					</div>
					
      
      
       <div class="right">  <?php 
            templ_show_twitter_button();
            templ_show_facebook_button();
        ?>  </div>
      
      
      <div class="post-navigation clear">
        <?php
            $prev_post = get_adjacent_post(false, '', true);
            $next_post = get_adjacent_post(false, '', false); ?>
        <?php if ($prev_post) : $prev_post_url = get_permalink($prev_post->ID); $prev_post_title = $prev_post->post_title; ?>
        <a class="post-prev" href="<?php echo $prev_post_url; ?>"><em>Previous post</em><span><?php echo $prev_post_title; ?></span></a>
        <?php endif; ?>
        <?php if ($next_post) : $next_post_url = get_permalink($next_post->ID); $next_post_title = $next_post->post_title; ?>
        <a class="post-next" href="<?php echo $next_post_url; ?>"><em>Next post</em><span><?php echo $next_post_title; ?></span></a>
        <?php endif; ?>
      </div>
    </div>
    <?php endwhile; ?>
    <?php endif; ?>
    
    
    
     <?php templ_after_single_entry(); // after single entry  hooks?>
    <?php if (function_exists('dynamic_sidebar')){ dynamic_sidebar('single_post_below'); }?>
    <?php comments_template(); ?>

</div> <!-- content bg #end -->
    <div class="content_bottom"></div>
</div> <!-- content end -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>