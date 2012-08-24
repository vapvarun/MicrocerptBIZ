<?php
/********************************************************************
You can add your filetes in this file and it will affected.
This is the common filter functions file where you can add you filtes.
********************************************************************/

add_filter('templ_page_title_filter','templ_page_title_fun');
function templ_page_title_fun($title)
{
	return '<h1>'.$title.'</h1>';
}

add_filter('templ_theme_guide_link_filter','templ_theme_guide_link_fun');
function templ_theme_guide_link_fun($guidelink)
{
	$guidelink .= "theme-documentation/plusone-theme-guide"; // templatic.com site theme guide url here
	return $guidelink;
}

add_filter('templ_theme_forum_link_filter','templ_theme_forum_link_fun');
function templ_theme_forum_link_fun($forumlink)
{
	$forumlink .= "viewforum.php?f=69"; // templatic.com site Forum url here
	return $forumlink;
}

/*//add_filter('templ_sidebar_widget_box_filter','templ_sidebar_widget_box_fun');
function templ_sidebar_widget_box_fun($content)
{
	//$content['top_navigation_above']='';
	//$content['slider_above']='';
	//$content['home_slider']='';
	//$content['slider_below']='';
	//$content['header_logo_right_side']='';
	
	//  Start Remove Side bar Widgets Area  Page Layout option wise
	if(get_option('ptthemes_page_layout')=='Full Page')
	{
	    $content['sidebar2']='';
		$content['sidebar_2col_merge']='';
		
	}else if(get_option('ptthemes_page_layout')=='Page 2 column - Right Sidebar' || get_option('ptthemes_page_layout')=='')
	{
	    $content['sidebar2']='';
		$content['sidebar_2col_merge']='';
		
	}else if(get_option('ptthemes_page_layout')=='Page 2 column - Left Sidebar')
	{
		$content['sidebar2']='';
	    $content['sidebar_2col_merge']='';
		
	}else if(get_option('ptthemes_page_layout')=='Page 3 column - Fixed')
	{
		 $content['sidebar_2col_merge']='';
		 
	}else if(get_option('ptthemes_page_layout')=='Page 3 column - Right Sidebar')
	{
	}else if(get_option('ptthemes_page_layout')=='Page 3 column - Left Sidebar')
	{
	}
	//  End Remove Side bar Widgets Area  Page Layout option wise
	
	
	return $content;
}*/


/*add_filter('templ_msg_notifications_filter','templ_msg_notifications_fun');
function templ_msg_notifications_fun($content)
{
	unset($content[0]);
	return $content;
}*/
/*add_filter('templ_sidebar_widget_box_filter','templ_sidebar_widget_box_fun');
function templ_sidebar_widget_box_fun($content)
{
	return $content;
}*/
/*add_filter('templ_widgets_listing_filter','templ_widgets_listing_fun');
function templ_widgets_listing_fun($content)
{
	return $content;
}
*/


add_filter('templ_sidebar_widget_box_filter','templ_sidebar_widget_box_fun');
function templ_sidebar_widget_box_fun($content)
{
	//$content['top_navigation']='';
	//$content['slider_above']='';
	$content['home_slider']='';
	//$content['slider_below']='';
	//$content['header_logo_right_side']='';
	
	
	// End Remove Footer Widgets Area Page Layout option wise
	//$content['top_navigation_above']='';
	//$content['main_navigation']='';
	$content['header_above']='';
	//$content['slider_above']='';
	//$content['slider_below']='';
	//$content['header_logo_right_side']='';
	//$content['single_post_below']='';
	$content['sidebar_2col_merge']='';
	
	$array_key = array_keys($content);
	$position = array_keys($array_key,'header_logo_right_side');
	$widget_pos = $position[0]+1;

$sidebar_widget_arr = array();
$sidebar_widget_arr['Footer Page'] =array(1,array('name' => 'Homepage : Content','id' => 'home_content','before_widget' => '<div class="widget">','after_widget' => '</div>','before_title' => '<h3>','after_title' => '</h3>'));
	array_splice($content, $widget_pos-1, 0, $sidebar_widget_arr);
	
	//print_r($content);
	
	return $content;
}

add_filter('templ_widgets_listing_filter','templ_widgets_listing_fun');
function templ_widgets_listing_fun($content)
{
	//print_r($content);
	$content['featured_video']='';
	$content['anything_slider']='';
	//$content['login']='';
	$content['anything_listing_slider']='';
	$content['nivo_slider']='';
	$content['my_bio']='';
	//$content['social_media']='';
	
	//print_r($content);
	//$content['flickr']='';
	return $content;
}


add_filter('templ_admin_menu_title_filter','templ_admin_menu_title_fun');
function templ_admin_menu_title_fun($content)
{
	return $content=__('PlusOne','templatic');
}

/////////search widget filter start/////////////
add_action('pre_get_posts', 'search_filter');
function search_filter($local_wp_query) 
{
	if(is_search())
	{
		add_filter('posts_where', 'searching_filter_where');
	}else
	{
		remove_filter('posts_where', 'searching_filter_where');	
	}
}

function searching_filter_where($where) {
	global $wpdb;
	$scat = trim($_REQUEST['catdrop']);
	$todate = trim($_REQUEST['todate']);
	$frmdate = trim($_REQUEST['frmdate']);
	$articleauthor = trim($_REQUEST['articleauthor']);
	$exactyes = trim($_REQUEST['exactyes']);
	if($scat>0)
	{
		$where .= " AND  $wpdb->posts.ID in (select $wpdb->term_relationships.object_id from $wpdb->term_relationships join $wpdb->term_taxonomy on $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id and $wpdb->term_taxonomy.term_id=\"$scat\" ) ";
	}
	if($todate!="")
	{
		$where .= " AND   DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d') >='".$todate."'";
	}
	else if($frmdate!="")
	{
		$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d') <='".$frmdate."'";
	}
	else if($todate!="" && $frmdate!="")
	{
		$where .= " AND  DATE_FORMAT($wpdb->posts.post_date,'%Y-%m-%d') BETWEEN '".$todate."' and '".$frmdate."'";
	}
	if($articleauthor!="" && $exactyes!=1)
	{
		$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  like '".$articleauthor."') ";
	}
	if($articleauthor!="" && $exactyes==1)
	{
		$where .= " AND  $wpdb->posts.post_author in (select $wpdb->users.ID from $wpdb->users where $wpdb->users.display_name  = '".$articleauthor."') ";
	}
	return $where;
}
/////////search widget filter end/////////////



/*add_filter('templ_admin_post_custom_fields_filter','templ_admin_post_custom_fields_fun');
function templ_admin_post_custom_fields_fun($array)
{
	$pt_metaboxes = $array;
	$pt_metaboxes["custofiled"] = array (
				"name"		=> "custofiled",
				"default" 	=> "",
				"label" 	=> __("Custom Title"),
				"type" 		=> "text",
				"desc"      => __("Enter Custom Title. eg. : code from youtibe, vimeo, etc")
			);
	return $pt_metaboxes;
}*/


/*add_filter('templ_breadcrumbs_navigation_filter','templ_breadcrumbs_navigation_fun');
function templ_breadcrumbs_navigation_fun($bc)
{
	return '<b>'.$bc.'</b>';	
}*/

add_action('templ_page_title_above','templ_page_title_below_fun'); //page title above action hook
//add_action('templ_page_title_below','templ_page_title_below_fun');  //page title below action hook
function templ_page_title_below_fun()
{
	templ_set_breadcrumbs_navigation();
}

add_filter('templ_anything_slider_widget_content_filter','templ_anything_slider_content_fun');
function templ_anything_slider_content_fun($post)
{
	ob_start(); // don't remove this code
/////////////////////////////////////////////////////
	if(get_the_post_thumbnail( $post->ID, array())){
	?>
	<a class="post_img" href="<?php echo get_permalink($post->ID);?>"><?php echo  get_the_post_thumbnail( $post->ID, array(220,220),array('class'	=> "",));?></a>
	<?php
    }else if($post_images = bdw_get_images($post->ID,'medium')){ 
	global $thumb_url;
	?>
	<a class="post_img" href="<?php echo get_permalink($post->ID);?>">
	 <img src="<?php echo get_bloginfo('template_url');?>/thumb.php?src=<?php echo $post_images[0];?>&amp;w=220&amp;h=220&amp;zc=1&amp;q=80<?php echo $thumb_url;?>" alt="<?php echo get_the_title($post->ID);?>" title="<?php echo get_the_title($post->ID);?>"  /></a>
	<?php } ?>
    <div class="tslider3_content">
    <h3> <a class="widget-title" href="<?php echo get_permalink($post->ID);?>"><?php echo get_the_title($post->ID);?></a></h3>
    <p>
	<?php echo bm_better_excerpt(605, ' ... ');?></p>
    <p><a href="<?php echo get_permalink($post->ID);?>" class="more"><?php _e('Read More','templatic')?></a></p>
   </div>


<?php
/////////////////////////////////////////////////////
	$return = ob_get_contents(); // don't remove this code
	ob_end_clean(); // don't remove this code
	return  $return;
}

///////////////////////
function templ_listing_content_att()
{
	do_action('templ_listing_content_att');
}

add_action('templ_listing_content_att','templ_listing_content_att_action');
function templ_listing_content_att_action()
{
	global $post;
	if(apply_filters('templ_date_listing_filter',true))
	{
	?>
   		<span><?php the_time('F j, Y') ?> </span>
    <?php	
	}
	if(apply_filters('templ_category_listing_filter',true))
	{
	?>
   		<span class="i_cate"> <?php the_category(", "); ?> </span>
    <?php	
	}
	if(apply_filters('templ_comment_listing_filter',true))
	{
	?>
   		<span> <a href="<?php the_permalink(); ?>#commentarea"  class="i_comment" ><?php comments_number('0', '1', '%'); ?> </a></span>
    <?php	
	}
	
}

?>