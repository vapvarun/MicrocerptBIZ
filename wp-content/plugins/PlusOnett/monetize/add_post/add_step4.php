<?php
session_start();
ob_start();
if($_GET['pid'])
{
	$is_delet_property = 1;
}
if($_SESSION['submission_info'])
{
	
	$preview_info = $_SESSION['submission_info'];
}else
{
	$preview_info1 = get_post_meta($_REQUEST['pid'],'',false);
	foreach($preview_info1 as $key=>$val)
	{
		if($val[0])
		{
			$preview_info[$key]=$val[0];	
		}
	}
	$post_id = get_post($_REQUEST['pid']);
	$preview_info['post_content']=$post_id->post_content;
	$page_title=$post_id->post_title;
	//$preview_info['post_tag']=$post_id->post_title;
}
if(function_exists('pt_check_captch_cond') && $_REQUEST['pid']==''){
	if(templ_is_show_capcha()){
		if(!pt_check_captch_cond())
		{
		wp_redirect(site_url('/?ptype=add_step2&backandedit=1&emsg=captch'));
		exit;
		}
	}
}
if($_POST)
{
	$_SESSION['submission_info']['package']=$_POST['package'];
}
global $upload_folder_path;
$image_array = array();

if($_SESSION["file_info"])
{
	$image_array = $_SESSION["file_info"];
}else
{
	$image_src = $thumb_img_arr[0];
	if($_REQUEST['pid']){
		$large_img_arr = bdw_get_images($_REQUEST['pid'],'medium');
		$thumb_img_arr = bdw_get_images($_REQUEST['pid'],'thumb');
	}
	$image_src = $large_img_arr[0];
}

if($_REQUEST['pid'])
{
	$large_img_arr = bdw_get_images($_REQUEST['pid'],'medium');
	$thumb_img_arr = bdw_get_images($_REQUEST['pid'],'thumb');
	if($thumb_img_arr)
	{
		$image_array = array_merge($image_array,$thumb_img_arr);
	}
}
?>
<?php get_header(); ?>
<div class="content content_full">
<div class="content_top content_top_full"></div>
	<div class="content_bg content_bg_full">
  <div class="entry">
    <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
      <div class="post-meta">
        <?php // templ_page_title_above(); //page title above action hook?>
       <h1>
	   <?php if(isset($_GET['alook'])){
		_e('Preview Article','templatic');
	   }
	   else{
		_e('Submit Article','templatic');
	   }
	    ?></h1>
        <?php templ_page_title_below(); //page title below action hook?>
      </div>
      <div id="post_<?php the_ID(); ?>">
        <div class="post-content">
		<?php if(isset($_GET['alook'])){
		}
		else{
		?>
         <div class="steps">
            	<ul>
                	<li class="current2"><?php _e('1. Login or Register','templatic');?></li>
                    <li class="current2"><?php _e('2. Article Detail','templatic');?></li>
                    <li class="current"><?php _e('3. Article Preview','templatic');?></li>
                   <li class="<?php if($_GET['ptype'] == 'add_step5'){?>last_current <?php }?> bgn"><?php _e(' 4. Success','templatic');?></li>
                </ul>
            </div>
        <?php } ?>
        <?php
if($_POST['coupon_code'])
{
	if(!templ_get_coupon_info($_POST['coupon_code']))
	{
		$url = site_url('/?ptype=add_step2&backandedit=1&emsg=invalid_coupon');
?>
<script type="text/javascript"> window.location.href="<?php echo $url;?>";</script>
<?php
		exit;
	}
}
	
$property_price_info = array();
if(function_exists('templ_get_package_price_info'))
{
	$property_price_info = templ_get_package_price_info($_REQUEST['package']);
}
$payable_amount = $property_price_info[0]['price'];
$alive_days = $property_price_info[0]['alive_days'];
$type_title = $property_price_info[0]['title'];
if($is_delet_property)
{	
}else
{
	if($_REQUEST['coupon_code']!='')
	{
		$discount_amt = get_discount_amount($_REQUEST['coupon_code'],$payable_amount);
		$payable_amount = $payable_amount-$discount_amt;
	}
}

if($_REQUEST['alook'])
{
	
}else
{
?>

                <div class="preview_info"> 
                  <?php
if($_REQUEST['pid'] || $_POST['renew'])
{
	$form_action_url = site_url('/?ptype=paynow');
}else
{
	$form_action_url = site_url('/?ptype=paynow');
}
?>
<form method="post" action="<?php echo $form_action_url; ?>" name="paynow_frm" id="paynow_frm" >
<input type="hidden" name="price_select" value="<?php echo $_REQUEST['price_select'];?>" />
<?php
if($is_delet_property)
{		
}else
{
	if(($_REQUEST['pid']=='' && $payable_amount>0) || ($_POST['renew'] && $payable_amount>0 && $_REQUEST['pid']!=''))
	{
		echo '<p>';
		printf(GOING_TO_PAY_MSG, get_currency_sym().$payable_amount,$alive_days,$type_title);
		if($discount_amt>0)
		{
			printf(SUBMIT_POST_PREVIEW_DISCOUNT_MSG,$_REQUEST['coupon_code'],get_currency_sym().$discount_amt);
		}
		echo '</p>';
	}else
	{
		echo '<p>';
		if($_REQUEST['pid']==''){
			printf(SUBMIT_POST_PREVIEW_FREE_MSG, $alive_days,$type_title);
		}else
		{
			printf(GOING_TO_UPDATE_MSG, get_currency_sym().$payable_amount,$alive_days,$type_title);
		}
		echo '</p>';
	}
}

if($is_delet_property)
{
$post_sql = mysql_query("select post_author,ID from $wpdb->posts where post_author = '".$current_user->data->ID."' and ID = '".$_REQUEST['pid']."'");
	if((mysql_num_rows($post_sql) > 0) || ($current_user->data->ID == 1)){	
?>
<h5 class="payment_head"><?php echo POST_DELETE_PRE_MSG;?></h5>
<input type="button" name="Delete" value="<?php echo POST_DELETE_BUTTON;?>" class="b_cancel" onclick="window.location.href='<?php echo site_url('/?ptype=delete&pid='.$_REQUEST['pid']);?>'" />
<input type="button" name="Cancel" value="<?php echo PRO_CANCEL_BUTTON;?>" class="b_cancel" onclick="window.location.href='<?php echo get_author_link($echo = false, $current_user->data->ID);?>'" />
<?php  } else { echo "ERROR: SORRY, you can not delete this post."; }?>

<?php
}else
{
?>
<input type="hidden" name="paynow" value="1">
<input type="hidden" name="coupon_code" value="<?php echo $_POST['coupon_code'];?>">
<input type="hidden" name="paymentmethod" value="<?php echo $_POST['paymentmethod'];?>">
<input type="hidden" name="pid" value="<?php echo $_POST['pid'];?>">
<?php
if($_REQUEST['pid'])
{
?>
<input type="submit" name="Submit and Pay" value="<?php echo PRO_UPDATE_BUTTON;?>" class="b_publish" />
<?php
}else
{
if($payable_amount>0)
{
?>
<input type="submit" name="Submit and Pay" value="<?php echo PRO_SUBMIT_PAY_BUTTON;?>" class="b_publish" />
<?php		
}else
{
?>
<input type="submit" name="Submit and Pay" value="<?php echo PRO_SUBMIT_BUTTON;?>" class="b_publish" />
<?php		
}
}
?>
<a href="<?php echo site_url('/?ptype=add_step2&backandedit=1');?><?php if($_REQUEST['pid']){ echo '&pid='.$_REQUEST['pid'];}?><?php if($_REQUEST['renew']){echo '&renew=1';}?>" class="b_goback " ><?php echo PRO_BACK_AND_EDIT_TEXT;?></a>
<input type="button" name="Cancel" value="<?php echo PRO_CANCEL_BUTTON;?>" class="b_cancel" onclick="window.location.href='<?php echo get_author_link($echo = false, $current_user->data->ID);?>'" />
<?php }?>
</form>
                
                </div>
                <?php }?>                      
                	<?php
if($form_fields)
{
   foreach($form_fields as $fkey=>$fval)
    {
		
        $fldkey = "$fkey";
		$$fldkey=$preview_info["$fkey"];
		
        if($$fldkey)
        {
						
            if($fkey=='post_content' || $fval['label'] == 'Term')
            {
              
            }
			elseif($fkey=='post_title')
            {
				
               echo '<h4>'.$preview_info['post_title'].'</h4>';
			?>   
            
            
             
			    <div class="post-meta">
            
             <?php _e('by','templatic');?>  <span class="post-author"> <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Posts by <?php the_author(); ?>">
              <?php the_author(); ?>
              </a> </span>
            
            
             <?php _e('on','templatic');?> <span class="post-date">
              <?php the_time(templ_get_date_format()) ?>
              </span>
            
           
            
            
            <?php
            }
			
			elseif($fkey=='post_category'||$fkey=='website'||$fkey=='post_tags')  
			{			
				if($fkey=='post_category'){
					echo '<span class="post-category">'.__($form_fields['post_category']['label'],'templatic').' : ';	
					if(is_array($preview_info['post_category'])){
						$val_category = implode(",",$preview_info['post_category']);
					}else{
						$val_category = $preview_info['post_category'];
					}
					echo $val_category.'</span>';
			  
				//the_taxonomies(array('before'=>'<span class="post-category">','sep'=>'</span><span class="post-tags">','after'=>'</span>')); 
				} 
				if($fkey=='website'){ ?>
					<span class="single-website"><a href="<?php echo get_post_meta($post->ID,"website",true); ?>" target="_blank"><?php _e('Website','templatic');?></a></span>  </div>  <!-- post meta #end--> 	
				<?php
				}
				if($fkey=='post_tags'){
			
				echo '<span class="post-tags">'.__($form_fields['post_tags']['label'],'templatic').' : </strong>'.$preview_info['post_tags'].'</span>';
				}
			}			
            else if($fval['type']=='upload')
            {
				?>
                
                <script src="<?php bloginfo('template_directory'); ?>/js/galleria.js" type="text/javascript" ></script>
                
            
               
				
				
				<div id="galleria">
				<?php
            if(count($image_array)>1)
            {
            for($im=0;$im<count($image_array);$im++)
            {
            ?>
            <div class="small"> 
            <a href="">
            <img src="<?php echo $image_array[$im];?>" />
            </a>
            </div>
            <?php
            }
            }
            ?>
              
        </div> <!-- galleria #end -->
        
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
        
        
		<a  href="<?php echo $$fldkey; ?>" rel="example_group" class="detail_img"  >
          	<img src="<?php bloginfo('template_url'); ?>/images/zoom_in.png" alt="" class="zoom"  />
         <img src="<?php echo templ_thumbimage_filter($$fldkey,'&amp;w=100&amp;h=100&amp;zc=1&amp;q=80');?>" alt=""  /></a>
         
         	<?php echo $preview_info['post_content'];?>
            
            <ul class="listing_field">
          
          <?php  }
		  
		  elseif($fval['type']=='catcheckbox')
            {
                echo '<li class="'.$fval['type'].'">'.__($fval['label'],'templatic').' : '.implode(",",$$fldkey).'</li>';
            }
			elseif($fval['type']=='multicheckbox')
            {
                if(is_array($$fldkey))
				{
					$val = implode(",",$$fldkey);
				}else
				{
					$val = $$fldkey;
				}
                echo '<li class="'.$fval['type'].'">'.__($fval['label'],'templatic').' : '.$val.'</li>';
            }
			elseif($fval['type']=='textarea')
            {
                echo '<li class="'.$fval['type'].'">'.__($fval['label'],'templatic').' : '.stripslashes($$fldkey).'</li>';
            }
			elseif($fval['type']=='texteditor')
            {
                echo '<li class="'.$fval['type'].'">'.__($fval['label'],'templatic').' : '.nl2br(stripslashes($$fldkey)).'</li>';
            }
		  
			else
            {
				
				echo '<li class="'.$fval['type'].'">'.__($fval['label'],'templatic').' : '.$$fldkey.'</li>';
				
            }
        }
		if($fval['type']=='geo_map')
		{
			echo '<li class="'.$fval['type'].'">'.__('Address','templatic').' : '.$preview_info['geo_address'].'</li>';	
		}
		
    }
    echo '</ul>';
}?>

  
                    <?php if($preview_info['geo_address']!=''){?>
                   <iframe src="http://maps.google.com/maps?f=q&source=s_q&hl=en&geocode=&q=<?php echo $preview_info['geo_address'];?>&ie=UTF8&z=14&iwloc=A&output=embed" height="308" width="301" scrolling="no" frameborder="0" ></iframe>
					<?php } ?>

                
         </form>
        </div>
       <!-- post content #end -->
       
       
       <div class="clearfix">
      <div class="post-meta  left">
						
						

						 <div class="post-share">
           
           <div class="addthis_toolbox addthis_default_style">
<a href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4c873bb26489d97f" class="addthis_button_compact sharethis"><?php _e('Share');?></a>
</div>
           </div> <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4c873bb26489d97f"></script>
					 <span class="post-view"><?php _e('Views : ');?><strong><?php echo user_post_visit_count($post->ID);?></strong></span>
					</div>
					<div class="right">  <?php 
            templ_show_twitter_button();
            templ_show_facebook_button();
        ?>  </div>
        
        </div>  <!-- post #bottom #end -->
	   
	   
        
       </div>
    </div>
  </div>
</div>
</div> <!-- content bg #end -->
    <div class="content_bottom content_bottom_full"></div>
</div> <!-- content end -->

<?php get_footer(); ?>
