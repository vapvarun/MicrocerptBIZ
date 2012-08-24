<?php get_header(); ?>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/tabber.js" language="javascript"></script>
<div  class="<?php templ_content_css();?>" >
<div class="content_top"></div>
	<div class="content_bg">
<!--  CONTENT AREA START -->

<?php
global $current_user;
if(isset($_GET['author_name'])) :
	$curauth = get_userdatabylogin($author_name);
else :
	$curauth = get_userdata(intval($author));
endif;
?>
<?php templ_page_title_above(); //page title above action hook?>
<div class="content-title"> 
<?php echo templ_page_title_filter( $curauth->display_name); //page tilte filter?> 
<a href="javascript: void(0);" id="mode"<?php if ($_COOKIE['mode'] == 'grid') echo ' class="flip"'; ?>></a> 
</div>
<div class="post-content">
<?php templ_page_title_below(); //page title below action hook?>
<?php
$sql = "select * from $custom_usermeta_db_table_name where is_active=1 and show_on_detail=1 and ctype='upload' or ctype='texteditor'";
			
			
			
			$post_meta_info = $wpdb->get_results($sql);
			foreach($post_meta_info as $user_meta_info_obj){
				if($user_meta_info_obj->ctype=='upload'){ 
				
				if(get_usermeta($current_user->data->ID,$user_meta_info_obj->htmlvar_name,true)!=''){
				?>
				
					
					<img class="alignleft" src="<?php echo templ_thumbimage_filter(get_usermeta($current_user->data->ID,$user_meta_info_obj->htmlvar_name,true),'&amp;w=121&amp;h=115&amp;zc=1&amp;q=80'); ?>" />
					
				<?php }
				}
				if($user_meta_info_obj->ctype=='texteditor'){ 
					if(get_usermeta($current_user->data->ID,$user_meta_info_obj->htmlvar_name,true)!=''){?>
						<p><?php echo get_usermeta($current_user->data->ID,$user_meta_info_obj->htmlvar_name,true); ?></p>
						
					<?php }
				}
			}
		
		?>
		<ul class="listing_field">
        <?php echo get_custom_usermeta_single_page($current_user->data->ID,' <li><span>{#TITLE#}</span> : {#VALUE#}</li>');?>	
		</ul>

	<div class="tabber" id="tab1">
		<div class="tabbertab">
			<h2>My Posts</h2>
			 <?php get_template_part('loop-listing'); ?>
			<?php get_template_part('pagination'); ?>
		</div>
		<div class="tabbertab">
			<h2>Voted Posts</h2>
			
			<?php
		$user_id = $current_user->data->ID; 
		 $querystr = "
			SELECT wposts.* 
			FROM $wpdb->posts wposts,$wpdb->postmeta wpostmeta
			WHERE wposts.ID = wpostmeta.post_id 
			AND wpostmeta.meta_key = 'thevoters'
			AND wposts.post_status = 'publish' 
			AND wposts.post_type = 'listing ' 
			ORDER BY wposts.post_date DESC
		 ";

		 $pageposts = $wpdb->get_results($querystr, OBJECT);
		 
	?>
		 <?php if ($pageposts): ?>
			 <?php global $post; ?>
			 <?php foreach ($pageposts as $post): ?>
			 <?php setup_postdata($post); ?>
			 
			 
			 <?php 
			 
			 $voters_user = get_post_meta($id, 'thevoters', true);
			 $voters_user = explode(",", $voters_user);
			 
			 
			 
			 foreach($voters_user as $voter_u) {
				if($voter_u == $user_id) {
				?>
				 <div class="post voted_list" id="post-<?php the_ID(); ?>">
				<div class="left">
					<?php 
					if(strtolower(get_option('ptthemes_vote'))=='registered user' || get_option('ptthemes_vote')==''){
						voting_author($post->ID);
					}
					else{	
					$currentvotes = get_post_meta($post->ID, 'votes', true);
					if(!$currentvotes) $currentvotes = 1;
					
					if(!templ_check_post_like($post->ID)){?>
						<div class="vote" id="addlike<?php echo $post->ID;?>">
							<span class="span_currentvote"><?php echo $currentvotes; ?></span>
							<a class="hype_link" onclick="add_like('<?php echo $post->ID;?>');">Hype</a>
						</div>
						<?php
					}else{?>
						<div class="vote" id="addlike<?php echo $post->ID;?>">
							<span class="span_currentvote"><?php echo $currentvotes; ?></span>
							<span class="b_like_disable">Hype</span>
						</div>               
						<?php
						}
					}
					?>
				</div>
			 
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
			 
			 if(get_post_meta($post->ID,"listing_img",true)){?>
			<a href="<?php the_permalink() ?>" class="<?php echo $display_style; ?>"><img src="<?php echo templ_thumbimage_filter(get_post_meta($post->ID,"listing_img",true),'&amp;w=121&amp;h=115&amp;zc=1&amp;q=80');?>" alt=""  /></a>
		<?php }
		else{ ?>
			<a href="<?php the_permalink() ?>" class="<?php echo $display_style; ?>"><img src="<?php echo bloginfo('template_directory');?>/images/no-image.png" alt="" title="No Image" /></a>
		<?php }
		?>		
			 
			 
			 <h3><a href="<?php the_permalink() ?>">
			  <?php the_title(); ?>
			  </a></h3>
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
            
            
            <?php 
			
			
			if(templ_is_show_listing_category()){?>
            <span class="post-category">
              <?php the_category(' / '); ?>
			   <?php the_taxonomies(array('before'=>'<span class="category">','sep'=>'</span><span class="tags">','after'=>'</span>')); ?>
            </span>
            <?php } ?>
            <?php if(templ_is_show_listing_comment()){?>
         <span class="single_comments"> <?php comments_popup_link(__('0','templatic'), __('1','templatic'), __('%','templatic'), '', __('Closed','templatic')); ?></span> <br />
          <?php } ?>
             <?php if(templ_is_show_listing_tags()){?>
                <?php the_tags('<span class="post-tags">', ', ', '</span>'); ?>
             <?php } ?>
           <span class="post-share"><a href="#">Share This</a></span>
           <span class="post-view"><?php _e('Views : ');?><strong><?php echo user_post_visit_count($post->ID);?></strong></span>
            <?php
			if(get_post_meta($post->ID,"website",true) != ''){ ?>
				<span class="single-website"><a href="<?php echo get_post_meta($post->ID,"website",true); ?>" target="_blank">Website</a></span>	
			<?php
			}			
			?>
			
        </div> <!-- post meta #end--> 
				
			 </div>
				<?php
				}
				
			}
			 
			 ?>
			
			 <?php endforeach; ?>
			 <?php else : ?>
				<h2 class="center">Not Found</h2>
				<p class="center">Sorry, but you had not voted yet.<br /><br /><br /><br /></p>
				
			 <?php endif; ?>
					</div>
					
				</div>

</div>
		</div> <!-- content bg #end -->
    <div class="content_bottom"></div>
</div> <!-- content end -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>