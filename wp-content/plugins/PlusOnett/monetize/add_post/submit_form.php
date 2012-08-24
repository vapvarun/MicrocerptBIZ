<?php
session_start();
ob_start();
if($_REQUEST['backandedit'])
{
}else
{
	$_SESSION['submission_info'] = array();
}
if(!allow_user_can_add())
{
	wp_redirect(get_option('siteurl'));
}
if($_REQUEST['pid'])
{
	if(!$current_user->data->ID)
	{
		wp_redirect(get_settings('home').'/index.php?ptype=login');
		exit;
	}
	
	$pid = $_REQUEST['pid'];
	$post_info = get_post_info($_REQUEST['pid']);
	foreach($form_fields as $key=>$val)
	{
		$post_title_val = $post_info['post_title'];
		$post_content_val = $post_info['post_content'];
		$post_excerpt_val = $post_info['post_excerpt'];
	}
	$cat_array = array();
	if($pid)
	{
		$taxonomy = CUSTOM_CATEGORY_TYPE1;//  e.g. post_tag, category, custom taxonomy
		$tax_args=array('orderby' => 'none');
		$catinfoarr = wp_get_post_terms( $pid , $taxonomy, $tax_args);
		$cat_array = array();
		if($catinfoarr)
		{
			for($c=0;$c<count($catinfoarr);$c++)
			{
				$post_category .= $catinfoarr[$c]->term_id.',';
			}
			$post_category_val	= trim($post_category,",");		
		}
		$taxonomy = CUSTOM_TAG_TYPE1;//  e.g. post_tag, category, custom taxonomy
		$tax_args=array('orderby' => 'none');
		$posttags = wp_get_post_terms( $pid , $taxonomy, $tax_args);
		if ($posttags) {
			foreach($posttags as $tag) 
			{
				$kw_tags .= $tag->name.','; 
			}
			$post_tags_val=trim($kw_tags,",");
		}
	}
	
	$post_meta = get_post_meta($_REQUEST['pid'], '',false);
	foreach($post_meta as $key=>$val)
	{
		$var = $key.'_val';
		$$var = $val[0];
	}
	$post_title = $post_info['post_title'];
	$post_content = $post_info['post_content'];
	
	
	$cat_array = array();
	if($pid)
	{
		$taxonomy = CUSTOM_CATEGORY_TYPE1;//  e.g. post_tag, category, custom taxonomy
		$tax_args=array('orderby' => 'none');
		$catinfoarr = wp_get_post_terms( $pid , $taxonomy, $tax_args);
		//$catinfoarr = get_the_category($post_info['ID']);
		$cat_array = array();
		for($c=0;$c<count($catinfoarr);$c++)
		{
			$cat_array[] = $catinfoarr[$c]->term_id;
		}
	}
	if(function_exists('bdw_get_images_with_info'))
	{
		$thumb_img_arr = bdw_get_images_with_info($_REQUEST['pid'],'thumb');
	}
}
if($_SESSION['submission_info'] && $_REQUEST['backandedit'])
{
	foreach($_SESSION['submission_info'] as $key=>$val)
	{
		$keyvar = $key.'_val';
		if($val && is_array($val))
		{
			$val = implode(',',$val);	
		}
		$$keyvar = stripslashes($val);
	}
}
if($_REQUEST['renew'])
{
	$property_list_type = get_post_meta($_REQUEST['pid'],'list_type',true);
}
if($_REQUEST['ptype']=='post_event')
{
	if($_REQUEST['pid'])
	{
		if($_REQUEST['renew'])
		{
			$page_title = RENEW_TEXT;
		}else
		{
			$page_title = POST_EDIT_TEXT;
		}
	}else
	{
		$page_title = POST_TITLE;
	}
}else
{
	if($_REQUEST['pid'])
	{
		if($_REQUEST['renew'])
		{
			$page_title = RENEW_LISING_TEXT;
		}else
		{
			$page_title = EDIT_LISING_TEXT;
		}
	}else
	{
		$page_title = POST_PLACE_TITLE;
	}
}
?>
<?php get_header(); ?>
st_url : "lists/media_list.js",
<!-- TinyMCE -->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins :"advimage,advlink,emotions,iespell,",
		editor_selector : "mceEditor",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,blockquote,|,link,unlink,anchor,image,code",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example word content CSS (should be your site CSS) this one removes paragraph margins
		content_css : "",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_li
		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->
<script>var rootfolderpath = '<?php echo bloginfo('template_directory');?>/images/';</script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/dhtmlgoodies_calendar.js"></script>
<link href="<?php bloginfo('template_directory'); ?>/library/css/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<div class="content content_full">
  <div class="entry">
    <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
      <div class="post-meta">
        <?php templ_page_title_above(); //page title above action hook?>
        <?php echo templ_page_title_filter($page_title); //page tilte filter?>
        <?php templ_page_title_below(); //page title below action hook?>
      </div>
      <div id="post_<?php the_ID(); ?>">
        <div class="post-content">
          <?php if(allow_user_register()){?>
        
          <?php if($_REQUEST['usererror']==1)
			{
				if($_SESSION['userinset_error'])
				{
					for($i=0;$i<count($_SESSION['userinset_error']);$i++)
					{
						echo '<div class="error_msg">'.$_SESSION['userinset_error'][$i].'</div>';
					}
					echo "<br>";
				}
			}
			if($_REQUEST['emsg']==1)
			{
			?>
	          <div class="error_msg"><?php echo INVALID_USER_PW_MSG;?></div>
          <?php
			}
			$error_msg = apply_filters('templ_submit_form_emsg_filter','');
			if($error_msg)
			{
			?>
	          <div class="error_msg"><?php echo $error_msg;?></div>
          	<?php	
			}
			if($current_user->ID=='')
			 {
			 ?>
          <h5 class="form_title spacer_none"><?php echo LOGINORREGISTER;?> </h5>
          <div class="form_row clearfix">
            <label><?php echo IAM_TEXT;?> </label>
            <span class=" user_define">
            <input name="user_login_or_not" type="radio" value="existing_user" <?php if($user_login_or_not=='existing_user'){ echo 'checked="checked"';}?> onclick="set_login_registration_frm(this.value);" />
            <?php echo EXISTING_USER_TEXT;?> </span> <span class="user_define">
            <input name="user_login_or_not" type="radio" value="new_user" <?php if($user_login_or_not=='new_user'){ echo 'checked="checked"';}?> onclick="set_login_registration_frm(this.value);" />
            <?php echo NEW_USER_TEXT;?> </span> </div>
          <div class="login_submit clearfix" id="login_user_frm_id">
            <form name="loginform" id="loginform" action="<?php echo site_url('/?ptype=login'); ?>" method="post" >
              <div class="form_row clearfix">
                <label><?php echo LOGIN_TEXT;?> <span>*</span> </label>
                <input type="text" class="textfield " id="user_login" name="log" />
              </div>
              <div class="form_row clearfix">
                <label><?php echo PASSWORD_TEXT;?> <span>*</span> </label>
                <input type="password" class="textfield " id="user_pass" name="pwd" />
              </div>
              <div class="form_row clearfix">
                <input name="submit" type="submit" value="<?php echo SUBMIT_BUTTON;?>" class="b_submit" />
              </div>
              <?php	$login_redirect_link = site_url('/?ptype=submition');?>
              <input type="hidden" name="redirect_to" value="<?php echo $login_redirect_link; ?>" />
              <input type="hidden" name="testcookie" value="1" />
              <input type="hidden" name="pagetype" value="<?php echo $login_redirect_link; ?>" />
            </form>
          </div>
          <?php }?>
          <?php }?>
          <?php
			 if($_REQUEST['pid'] || $_POST['renew']){
				$form_action_url = site_url('/?ptype=preview');
			 }else
			 {
				 $form_action_url = site_url('/?ptype=preview&pid='.$_REQUEST['pid']);
			 }
			 ?>
          <form name="submissiion_form" id="submissiion_form" action="<?php echo $form_action_url; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="user_login_or_not" id="user_login_or_not" value="<?php echo $user_login_or_not;?>" />
            <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'];?>" />
            <input type="hidden" name="renew" value="<?php echo $_REQUEST['renew'];?>" />
            <h5 class="form_title spacer_none" <?php if(get_option('is_user_addevent')=='0' || get_option('is_user_eventlist')=='0'){ echo 'style="display:none"';} ?>> <?php echo SELECT_LISTING_TYPE_TEXT;?></h5>
            <div class="form_row clearfix" <?php if(get_option('is_user_addevent')=='0' || get_option('is_user_eventlist')=='0'){ echo 'style="display:none"';} ?>>
              <label><?php echo SELECT_POST_TYPE_TEXT;?> </label>
              <?php if(get_option('is_user_addevent')=='0'){}else{ ?>
              <span class=" user_define">
              <input name="listing_type" id="place_listing" type="radio" value="post_listing" <?php if($_REQUEST['ptype']=='post_listing'){ echo 'checked="checked"';}?> onclick="window.location.href='<?php echo get_option('siteurl');?>/?ptype=post_listing'" />
              <?php echo POST_PLACE_TITLE;?> </span>
              <?php }?>
              <?php if(get_option('is_user_eventlist')=='0'){}else{ ?>
              <span class="user_define">
              <input name="listing_type" id="event_listing" type="radio" value="post_event" <?php if($_REQUEST['ptype']=='post_event'){ echo 'checked="checked"';}?>  onclick="window.location.href='<?php echo get_option('siteurl');?>/?ptype=post_event'" />
              <?php echo POST_TITLE;?> </span>
              <?php }?>
              
            </div>
            
            <?php
			$validation_info = array();
            foreach($form_fields as $key=>$val)
			{
				$str = ''; $fval = '';
				$field_val = $key.'_val';
				if($$field_val){$fval = $$field_val;}else{$fval = $val['default'];}
				
				if($val['is_require'])
				{
					$validation_info[] = array(
											   'name'	=> $key,
											   'espan'	=> $key.'_error',
											   'type'	=> $val['type'],
											   'text'	=> $val['label'],
											   );
				}
				if($val['type']=='text')
				{
					$str = '<input name="'.$key.'" type="text" '.$val['extra'].' value="'.$fval.'">';
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';
					}
				}elseif($val['type']=='hidden')
				{
					$str = '<input name="'.$key.'" type="hidden" '.$val['extra'].' value="'.$fval.'">';	
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='textarea')
				{
					$str = '<textarea name="'.$key.'" '.$val['extra'].'>'.$fval.'</textarea>';	
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='texteditor')
				{
					$str = '<textarea name="'.$key.'" '.$val['extra'].'>'.$fval.'</textarea>';
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='file')
				{
					$str = '<input name="'.$key.'" type="file" '.$val['extra'].' value="'.$fval.'">';
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='include')
				{
					$str = @include_once($val['default']);
				}else
				if($val['type']=='head')
				{
					$str = '';
				}else
				if($val['type']=='date')
				{
					$str = '<input name="'.$key.'" type="text" '.$val['extra'].' value="'.$fval.'">';	
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='catselect')
				{
					$term = get_term( (int)$fval, CUSTOM_CATEGORY_TYPE1);
					$str = '<select name="'.$key.'" '.$val['extra'].'>';
					$args = array('taxonomy' => CUSTOM_CATEGORY_TYPE1);
					$all_categories = get_categories($args);
					foreach($all_categories as $key => $cat) 
					{
					
						$seled='';
						if($term->name==$cat->name){ $seled='selected="selected"';}
						$str .= '<option value="'.$cat->name.'" '.$seled.'>'.$cat->name.'</option>';	
					}
					$str .= '</select>';
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='catdropdown')
				{
					$cat_args = array('name' => 'post_category', 'id' => 'post_category_0', 'selected' => $fval, 'class' => 'textfield', 'orderby' => 'name', 'echo' => '0', 'hierarchical' => 1, 'taxonomy'=>CUSTOM_CATEGORY_TYPE1);
					$cat_args['show_option_none'] = __('Select Category','templatic');
					$str .=wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args));
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='select')
				{
					$str = '<select name="'.$key.'" '.$val['extra'].'>';
					$option_values_arr = explode(',', $val['options']);
					for($i=0;$i<count($option_values_arr);$i++)
					{
						$seled='';
						
						if($fval==$option_values_arr[$i]){ $seled='selected="selected"';}
						$str .= '<option value="'.$option_values_arr[$i].'" '.$seled.'>'.$option_values_arr[$i].'</option>';	
					}
					$str .= '</select>';
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='catcheckbox')
				{
					$fval_arr = explode(',',$fval);
					$str .= $val['tag_before'].get_categories_checkboxes_form(CUSTOM_CATEGORY_TYPE1,$fval_arr).$oval.$val['tag_after'];
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='catradio')
				{
					$args = array('taxonomy' => CUSTOM_CATEGORY_TYPE1);
					$all_categories = get_categories($args);
					foreach($all_categories as $key1 => $cat) 
					{
						
						
							$seled='';
							if($fval==$cat->term_id){ $seled='checked="checked"';}
							$str .= $val['tag_before'].'<input name="'.$key.'" type="radio" '.$val['extra'].' value="'.$cat->name.'" '.$seled.'> '.$cat->name.$val['tag_after'];	
						
					}
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='checkbox')
				{
					if($fval){ $seled='checked="checked"';}
					$str = '<input name="'.$key.'" type="checkbox" '.$val['extra'].' value="1" '.$seled.'>';
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='upload')
				{
					$str = '<input name="'.$key.'" type="file" '.$val['extra'].' '.$uclass.' value="'.$fval.'" > ';
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}
				else
				if($val['type']=='radio')
				{
					$options = $val['options'];
					if($options)
					{
						$option_values_arr = explode(',',$options);
						for($i=0;$i<count($option_values_arr);$i++)
						{
							$seled='';
							if($fval==$option_values_arr[$i]){$seled='checked="checked"';}
							$str .= $val['tag_before'].'<input name="'.$key.'" type="radio" '.$val['extra'].' value="'.$option_values_arr[$i].'" '.$seled.'> '.$option_values_arr[$i].$val['tag_after'];
						}
						if($val['is_require'])
						{
							$str .= '<span id="'.$key.'_error"></span>';	
						}
					}
				}else
				if($val['type']=='multicheckbox')
				{
					$options = $val['options'];
					if($options)
					{  $chkcounter = 0;
					    
						$option_values_arr = explode(',',$options);
						for($i=0;$i<count($option_values_arr);$i++)
						{
							$chkcounter++;
							$seled='';
							$fval_arr = explode(',',$fval);
							if(in_array($option_values_arr[$i],$fval_arr)){ $seled='checked="checked"';}
							$str .= $val['tag_before'].'<input name="'.$key.'[]"  id="'.$key.'_'.$chkcounter.'" type="checkbox" '.$val['extra'].' value="'.$option_values_arr[$i].'" '.$seled.'> '.$option_values_arr[$i].$val['tag_after'];
						}
						if($val['is_require'])
						{
							$str .= '<span id="'.$key.'_error"></span>';	
						}
					}
				}
				else
				if($val['type']=='packageradio')
				{
					$options = $val['options'];
					foreach($options as $okey=>$oval)
					{
						$seled='';
						if($fval==$okey){$seled='checked="checked"';}
						$str .= $val['tag_before'].'<input name="'.$key.'" type="radio" '.$val['extra'].' value="'.$okey.'" '.$seled.'> '.$oval.$val['tag_after'];	
					}
					if($val['is_require'])
					{
						$str .= '<span id="'.$key.'_error"></span>';	
					}
				}else
				if($val['type']=='geo_map')
				{
					do_action('templ_submit_form_googlemap');	
				}else
				if($val['type']=='image_uploader')
				{
					do_action('templ_submit_form_image_uploader');	
				}
				if($val['is_require'])
				{
					$label = '<label>'.$val['label'].' <span>*</span> </label>';
				}else
				{
					$label = '<label>'.$val['label'].'</label>';
				}
				echo $val['outer_st'].$label.$val['tag_st'].$str.$val['tag_end'].$val['outer_end'];
			}
			?>
              
            <script type="text/javascript">
			 function show_value_hide(val)
			 {
			 	document.getElementById('property_submit_price_id').innerHTML = document.getElementById('span_'+val).innerHTML;
			 }
			 </script>
            <?php
		if($current_user->ID=='')
		 {
		 ?>
            <div id="contact_detail_id" style="display:none;">
              <h5 class="form_title"><?php echo CONTACT_DETAIL_TITLE; ?></h5>
              <div class="form_row clearfix">
                <label><?php echo CONTACT_NAME_TEXT; ?></label>
                <input name="user_fname" id="user_fname" value="<?php echo $user_fname;?>" type="text" class="textfield" />
              </div>
              <div class="form_row clearfix">
                <label><?php echo CONTACT_TEXT; ?></label>
                <input  name="user_phone" id="user_phone" value="<?php echo $user_phone;?>" type="text" class="textfield" />
              </div>
              <div class="form_row clearfix">
                <label><?php echo EMAIL_TEXT; ?></label>
                <input name="user_email" id="user_email" value="<?php echo $user_email;?>" type="text" class="textfield" />
              </div>
            </div>
            <?php }?>
            
            <?php 
		if(function_exists('pt_get_captch') && $_REQUEST['pid']==''){?>  <h5 class="form_title"><?php echo CAPTCHA_TITLE_TEXT; ?></h5> <?php pt_get_captch(); }?>
            <input type="submit" name="Update" value="<?php echo PRO_PREVIEW_BUTTON;?>" class="b_review" />
            <div class="form_row clearfix"> <span class="message_note">
              <?php _e('Note: You will be able to see a preview in the next page','templatic');?>
              </span> </div>
          </form>
        </div>
        <!-- post content #end -->
        
      </div>
    </div>
  </div>
</div>
<script language="javascript" type="text/javascript">
function set_login_registration_frm(val)
{
	if(val=='existing_user')
	{
		document.getElementById('contact_detail_id').style.display = 'none';
		document.getElementById('login_user_frm_id').style.display = '';
		document.getElementById('user_login_or_not').value = val;
	}else  //new_user
	{
		document.getElementById('contact_detail_id').style.display = '';
		document.getElementById('login_user_frm_id').style.display = 'none';
		document.getElementById('user_login_or_not').value = val;
	}
}
<?php if($user_login_or_not)
{
?>
set_login_registration_frm('<?php echo $user_login_or_not;?>');
<?php
}
?>
</script>
<?php
include_once(TEMPL_ADD_POST_FOLDER . 'submition_validation.php');
?>
<?php get_footer(); ?>
