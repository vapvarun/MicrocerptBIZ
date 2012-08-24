<?php get_header(); ?>
<?php
if($_SERVER['HTTP_REFERER'] == '' || !strstr($_SERVER['HTTP_REFERER'],$_SERVER['REQUEST_URI']))
{
$question_viewed_count = get_post_meta($post->ID,'question_viewed_count',true);
update_post_meta($post->ID,'question_viewed_count',$question_viewed_count+1);}
?>

<div  class="<?php templ_content_css();?>" >
<div class="content_top"></div>
	<div class="content_bg">
<!--  CONTENT AREA START -->
<?php templ_before_single_entry(); // before single entry  hooks?>
<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>
<?php templ_page_title_above(); //page title above action hook?>

<div class="content-title"> <?php echo templ_page_title_filter(get_the_title()); //page tilte filter?> </div>
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
            	 <?php the_taxonomies(array('before'=>'<span class="post-category">','sep'=>'</span><span class="post-tags-none">','after'=>'</span>')); ?>
             <?php } ?>
             
             <?php  if(templ_is_show_post_tags()){?>
            	 <?php the_taxonomies(array('before'=>'<span class="post-category-none">','sep'=>'</span><span class="post-tags">','after'=>'</span>')); ?>
             <?php } ?>
            
            <?php if(templ_is_show_listing_comment()){?>
         <span class="single_comments"> <?php comments_popup_link(__('0','templatic'), __('1','templatic'), __('%','templatic'), '', __('Closed','templatic')); ?></span> <br />
          <?php } ?>
             
           
        </div> <!-- post meta #end--> 
    <?php templ_before_single_post_content(); // BEFORE  single post content  hooks?>
  
    <div class="post-content post">
     
        <script src="<?php bloginfo('template_directory'); ?>/js/galleria.js" type="text/javascript" ></script>
       
             <div id="galleria">
            <?php 
			if($post_images = bdw_get_images($post->ID,'large'))
			{
				?><?php
		$post_images = bdw_get_images($post->ID,'thumb');
		for($i=1;$i<count($post_images);$i++)
		{
		?>
         <div class="small"> 
                <a href="#">
		<img src="<?php echo $post_images[$i];?>" alt="" />
          </a>
            </div>
            
             
		<?php	
		}
	}?></div>
    
    <script type="text/javascript">
		 var $cg = jQuery.noConflict();
    // Load theme
    Galleria.loadTheme('<?php bloginfo('template_directory'); ?>/js/galleria.classic.js');
    // run galleria and add some options
    $cg('#galleria').galleria({
        image_crop: true, // crop all images to fit
        thumb_crop: true, // crop all thumbnails to fit
        transition: 'fade', // crossfade photos
        transition_speed: 700, // slow down the crossfade
		autoplay: true,
        data_config: function(img) {
            // will extract and return image captions from the source:
            return  {
                title: $cg(img).parent().next('strong').html(),
                description: $cg(img).parent().next('strong').next().html()
            };
        },
        extend: function() {
            this.bind(Galleria.IMAGE, function(e) {
                // bind a click event to the active image
                $cg(e.imageTarget).css('cursor','pointer').click(this.proxy(function() {
                    // open the image in a lightbox
                    this.openLightbox();
                }));
            });
        }
    });
    </script>
            
       
        <div class="left">
			<?php voting($post->ID); ?>
		</div>
        
        
        
        <script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
    <script type="text/javascript">
   <!--
   var $n = jQuery.noConflict();
        $n(document).ready(function() {    
            $n("a[rel=example_group]").fancybox({
                'transitionIn'		: 'none',
                'transitionOut'		: 'none',
                'titlePosition' 	: 'over',
                'titleFormat '		: function(title, currentArray, currentIndex, currentOpts) {
                    return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
                }
            });    
        });
		//-->
    </script> 
        
        <?php if(get_post_meta($post->ID,"listing_img",true)){?>		
		<a  href="<?php echo get_post_meta($post->ID,"listing_img",true); ?>" rel="example_group" class="detail_img"  >
          <img src="<?php bloginfo('template_url'); ?>/images/zoom_in.png" alt="" class="zoom"  />
         <img src="<?php echo templ_thumbimage_filter(get_post_meta($post->ID,"listing_img",true),'&amp;w=121&amp;h=115&amp;zc=1&amp;q=80');?>" alt=""  /></a>
		 <?php }
		 else { ?>
			<a href="<?php the_permalink() ?>"><img src="<?php echo bloginfo('template_directory');?>/images/no-image.png" alt="" title="No Image" /></a>
		 <?php
		 }
		 $sql = "select * from $custom_post_meta_db_table_name where is_active=1 and show_on_detail=1 ";
			if($fields_name)
			{
				$fields_name = '"'.str_replace(',','","',$fields_name).'"';
				$sql .= " and htmlvar_name in ($fields_name) ";
			}
			$sql .=  " order by sort_order asc,admin_title asc";
			
			$post_meta_info = $wpdb->get_results($sql);
			foreach($post_meta_info as $post_meta_info_obj){
				if($post_meta_info_obj->ctype=='upload'){ 
				if(get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true)!=''){
				?>
					<a href="<?php echo get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true); ?>" rel="example_group" class="detail_img" >
					<img src="<?php bloginfo('template_url'); ?>/images/zoom_in.png" alt="" class="zoom"  />
					<img src="<?php echo templ_thumbimage_filter(get_post_meta($post->ID,$post_meta_info_obj->htmlvar_name,true),'&amp;w=121&amp;h=115&amp;zc=1&amp;q=80'); ?>"/></a>
				<?php }
				}
			}
		
		?> 
       <?php the_content(); ?> 
       
       
		<ul class="listing_field">
        <?php echo get_post_custom_listing_single_page($post->ID,' <li><span>{#TITLE#}</span> : {#VALUE#}</li>');?>	
		</ul>
             
       </div>    <!-- postcontent #end -->    
       
       </div> <!-- post #end -->
      
      
      <div class="clearfix">
      <div class="post-meta  left">
						
						<?php
			if(get_post_meta($post->ID,"website",true) != ''){ ?>
				<span class="single-website"><a href="<?php echo get_post_meta($post->ID,"website",true); ?>">Website</a></span>	
			<?php
			}			
			?>

						 <div class="post-share">
           
           <div class="addthis_toolbox addthis_default_style">
<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c873bb26489d97f" class="addthis_button_compact sharethis"><?php _e('Share');?></a>
</div>
           </div> <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c873bb26489d97f"></script>
					 <span class="post-view"><?php _e('Views : ');?><strong><?php echo user_post_visit_count($post->ID);?></strong></span>
					</div>
					<div class="right likethis">  <?php 
            templ_show_twitter_button();
            templ_show_facebook_button();
        ?>  </div>
        
        </div>  <!-- post #bottom #end -->
        
        
        <!-- post navigation -->
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
  </div> <!-- #end -->
             
   <?php templ_after_single_post_content(); // after single post content hooks?>        
	<?php templ_after_single_entry(); // after single entry  hooks?>   
	<?php if (function_exists('dynamic_sidebar')){ dynamic_sidebar('single_post_below'); }?>	
           
 
    
    
</div> <!-- entry #end -->
 
<?php endwhile; ?>
<?php endif; ?>



<?php comments_template(); ?>
</div> <!-- content bg #end -->
    <div class="content_bottom"></div>
</div> <!-- content end -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>