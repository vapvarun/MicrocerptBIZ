<?php
function templ_add_template_post_page($template)
{
	if($_REQUEST['ptype']=='submition')
	{
		$template = TEMPL_ADD_POST_FOLDER.'submit_form.php';
	}elseif($_REQUEST['ptype'] == 'preview')
	{
		$template = TEMPL_ADD_POST_FOLDER . "preview.php";
	}elseif($_REQUEST['ptype'] == 'paynow')
	{
		$template = TEMPL_ADD_POST_FOLDER . "paynow.php";
	}elseif($_REQUEST['ptype'] == 'cancel_return')
	{
		$template = TEMPL_ADD_POST_FOLDER . 'cancel.php';
		set_post_status($_REQUEST['pid'],'draft');
	}
	elseif($_GET['ptype'] == 'return' || $_GET['ptype'] == 'payment_success')  // PAYMENT GATEWAY RETURN
	{
		set_post_status($_REQUEST['pid'],'publish');
		$template = TEMPL_ADD_POST_FOLDER . 'return.php';
	}
	elseif($_GET['ptype'] == 'success')  // PAYMENT GATEWAY RETURN
	{
		$template = TEMPL_ADD_POST_FOLDER . 'add_step5.php';
	}elseif($_GET['ptype'] == 'notifyurl')  // PAYMENT GATEWAY NOTIFY URL
	{
		if($_GET['pmethod'] == 'paypal')
		{
			include_once(TEMPL_ADD_POST_FOLDER . 'ipn_process.php');
		}elseif($_GET['pmethod'] == '2co')
		{
			include_once(TEMPL_ADD_POST_FOLDER . 'ipn_process_2co.php');
		}
		exit;
	}elseif($_GET['ptype'] == 'add_step1')  
	{
		$template = TEMPL_ADD_POST_FOLDER.'add_step1.php';
	}elseif($_GET['ptype'] == 'add_step2')  
	{
		$template = TEMPL_ADD_POST_FOLDER.'add_step2.php';
	}elseif($_GET['ptype'] == 'add_step3')  
	{
		$template = TEMPL_ADD_POST_FOLDER.'add_step3.php';
	}elseif($_GET['ptype'] == 'add_step4')  
	{
		$template = TEMPL_ADD_POST_FOLDER.'add_step4.php';
	}elseif($_REQUEST['ptype'] == 'delete')
	{
		global $current_user;
		if($_REQUEST['pid'])
		{
			wp_delete_post($_REQUEST['pid']);
			$url = apply_filters('templ_after_post_delete_url_filter',get_author_link($echo = false, $current_user->data->ID));
			wp_redirect($url);
		}	
	}elseif($_REQUEST['ptype'] == 'image_uploader')
	{
		include_once(TEMPL_ADD_POST_FOLDER . 'image_uploader/upload.php');exit;
	}
	
	return $template;
}


//********************************************************
//Image delete and set order functionality
//********************************************************
if($_REQUEST['ptype'] == 'att_delete')
{	
    if($_REQUEST['remove'] == 'temp')
	{
		if($_SESSION["file_info"])
		{
			$tmp_file_info = array();
			foreach($_SESSION["file_info"] as $image_id=>$val)
			{
				    if($image_id == $_REQUEST['pid'])
					{
						@unlink(ABSPATH."/".$upload_folder_path."tmp/".$_REQUEST['pid'].".jpg");
					}else{	
						$tmp_file_info[$image_id] = $val;
					}
					
			}
			$_SESSION["file_info"] = $tmp_file_info;
		}
		
	}else{		
			wp_delete_attachment($_REQUEST['pid']);	
	}
	exit;
}else
if($_REQUEST['ptype'] == 'sort_image')
{
	global $wpdb;
	$arr_pid = explode(',',$_REQUEST['pid']);
	for($j=0;$j<count($arr_pid);$j++)
	{
		$media_id = $arr_pid[$j];
		if(strstr($media_id,'div_'))
		{
			$media_id = str_replace('div_','',$arr_pid[$j]);
		}
		$wpdb->query('update '.$wpdb->posts.' set  menu_order = "'.$j.'" where ID = "'.$media_id.'" ');
	}
	echo 'Image order saved successfully';
	exit;
}

//********************************************************
//Multi Image Uploader add for submit post form hook action
//********************************************************
add_action('templ_submit_form_image_uploader','templ_submit_form_image_uploader_fun');
function templ_submit_form_image_uploader_fun()
{
	include_once(TEMPL_ADD_POST_FOLDER . 'image_uploader/image_uploader.php');
}



//********************************************************
//Google map add for submit post form hook action
//********************************************************
add_action('templ_submit_form_googlemap','templ_submit_form_googlemap_fun');
function templ_submit_form_googlemap_fun()
{
	include_once(TEMPL_ADD_POST_FOLDER . 'google_map/location_add_map.php');
}


/************************************
//FUNCTION NAME : templ_paynow_before_insert
//ARGUMENTS : None.
//RETURNS : action hook call for payment.php befor insert data.
***************************************/
function templ_paynow_before_insert()
{
	$payable_amount = 0;
	$info = array();
	if(function_exists('templ_get_package_price_info'))
	{
		$property_price_info = templ_get_package_price_info($_SESSION['submission_info']['package']);	
	}	
	
	$property_price_info = $property_price_info[0];
	if($property_price_info['price']>0)
	{
		$payable_amount = $property_price_info['price'];	
	}
	
	if(function_exists('templ_nopayment_redirect'))
	{

		if($_REQUEST['pid']=='' && $payable_amount>0 && $_REQUEST['paymentmethod']=='')
		{
			templ_nopayment_redirect();
		}
	}
	return apply_filters('templ_payable_amount_filter',$payable_amount);
}

function templ_paynow_after_insert_email()
{
	///////ADMIN EMAIL START//////
	global $last_postid,$my_post;
	$fromEmail = get_site_emailId();
	$fromEmailName = get_site_emailName();
	$store_name = get_option('blogname');
	$email_content = get_option('post_submited_success_email_content');
	$email_subject = get_option('post_submited_success_email_subject');
	if(!$email_subject)
	{
		$email_subject = sprintf(__('New place listing of ID:#%s','templatic'),$last_postid);
	}
	if(!$email_content)
	{
		$email_content = __('<p>Dear [#to_name#],</p><p>You Submitted below information. The email if for your knowledge of information submition.</p>[#information_details#]<br><p>We hope you enjoy. Thanks!</p><p>[#site_name#]</p>','templatic');
	}
	
	$information_details = "<p>".__('ID','templatic')." : ".$last_postid."</p>";
	$information_details .= '<p>'.__('View more detail from','templatic').' <a href="'.get_permalink($last_postid).'">'.$my_post['post_title'].'</a></p>';
	
	$search_array = array('[#to_name#]','[#information_details#]','[#site_name#]');
	$replace_array = array($fromEmail,$information_details,$store_name);
	$filecontent = apply_filters('templ_paynow_admin_email_filter',str_replace($search_array,$replace_array,$email_content));
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$headers .= 'To: '.$fromEmailName.' <'.$fromEmail.'>' . "\r\n";
	@mail($fromEmail,$email_subject,$email_content,$headers);
	//////ADMIN EMAIL END////////
}

do_action('templ_paynow_after_insert_email');
function templ_paynow_after_insert_payment($payable_amount,$last_postid,$my_post)
{
	templ_paynow_after_insert_email($last_postid,$my_post);
	if($payable_amount <= 0)
	{
		$suburl .= "&pid=$last_postid";
		if($submission_info['renew'])
		{
			$suburl .= "&renew=1";
		}
		if($_REQUEST['pid'])
		{
			global $current_user;
			$rurl = get_author_posts_url($current_user->data->ID);
		}else
		{
			$rurl = site_url("/?ptype=success$suburl");
		}
		$rurl = apply_filters('templ_paynow_renew_redirect_url',$rurl);
		if($rurl){wp_redirect($rurl);}
		exit;
	}else
	{
		$paymentmethod = $_REQUEST['paymentmethod'];
		$paymentSuccessFlag = 0;
		if($paymentmethod == 'prebanktransfer' || $paymentmethod == 'payondelevary')
		{
			if($submission_info['renew'])
			{
				$suburl = "&renew=1";
			}
			$suburl .= "&pid=$last_postid";
			$surl = apply_filters('templ_paynow_success_redirect_url',site_url('/?ptype=success&paydeltype='.$paymentmethod.$suburl));
			if($surl){wp_redirect($surl);}
		}
		else
		{			
			$path = apply_filters('templ_paynow_payment_gateway_file',TT_INCLUDES_FOLDER_PATH.'payment/'.$paymentmethod.'/'.$paymentmethod.'_response.php');
			if(file_exists($path))
			{
				include_once($path);
			}
		}
		exit;
	}
	do_action('templ_paynow_after_insert_payment');
}
do_action('templ_paynow_after_insert_nopayment');
/************************************
//FUNCTION NAME : templ_skip_payment_method
//ARGUMENTS : $flag.
//RETURNS : filter to skin payment method or not
			if $flag == 1 => payment method was except and you can add the payment
			if $flag == 0 => payment mathod is compulsory,will give error is not selected<br />
			Default value of $flag is "0".
***************************************/
/*add_filter('templ_skip_payment_method','templ_skip_payment_method_fun');
function templ_skip_payment_method_fun($flag)
{
	return $flag;
}*/


function set_post_status($pid,$status='publish')
{
	if($pid)
	{
		global $wpdb;
		$wpdb->query("update $wpdb->posts set post_status=\"$status\" where ID=\"$pid\"");
	}
}

if(!function_exists('get_property_default_status'))
{
	function get_property_default_status()
	{
		return apply_filters('templ_addnew_default_status','publish');
	}
}

function get_attached_file_meta_path($imagepath)
{
	do_action('get_attached_file_meta_path');
}

function allow_user_can_add()
{
	return apply_filters('templ_allow_user_can_add','1');
}

function allow_user_register()
{
	return apply_filters('templ_allow_user_register','1');
}

function templ_get_the_url($url)
{
	return apply_filters('templ_the_url_filter',$url);
}

function templ_upload_phy_path()
{
	$upload_info = wp_upload_dir();
	return $upload_info['basedir'];
}
function templ_upload_url_path()
{
	$upload_info = wp_upload_dir();
	return $upload_info['baseurl'];
}

function templ_tmp_phy_path()
{
	return templ_upload_phy_path().'/tmp/';
}
function templ_tmp_url_path()
{
	return templ_upload_url_path().'/tmp/';
}

function templ_image_phy_path()
{
	$upload_dir = wp_upload_dir();
	return $upload_dir['path'];
}
function templ_image_url_path()
{
	$upload_dir = wp_upload_dir();
	return $upload_dir['url'];
}

function get_image_size($src)
{
	$img = imagecreatefromjpeg($src);
	if (!$img) {
		echo "ERROR:could not create image handle ". $src;
		exit(0);
	}
	$width = imageSX($img);
	$height = imageSY($img);
	return array('width'=>$width,'height'=>$height);
	
}
function move_original_image_file($src,$dest)
{
	copy($src, $dest);
	unlink($src);
	$dest = explode('/',$dest);
	$img_name = $dest[count($dest)-1];
	$img_name_arr = explode('.',$img_name);

	$my_post = array();
	$my_post['post_title'] = $img_name_arr[0];
	$wp_upload_dir = wp_upload_dir();
	$url = $wp_upload_dir['subdir'];
	$my_post['guid'] = $url.'/'.$img_name;
	return $my_post;
}


function getCategoryList( $parent = 0, $level = 0, $categories = 0, $page = 1, $per_page = 1000 ) 
{
	$count = 0;
	if ( empty($categories) ) 
	{
		$args = array('hide_empty' => 0,'orderby'=>'id');
			
		$categories = get_categories( $args );
		if ( empty($categories) )
			return false;
	}		
	$children = _get_term_hierarchy('category');
	return _cat_rows1( $parent, $level, $categories, $children, $page, $per_page, $count );
}
function _cat_rows1( $parent = 0, $level = 0, $categories, &$children, $page = 1, $per_page = 20, &$count )
{
	//global $category_array;
	$start = ($page - 1) * $per_page;
	$end = $start + $per_page;
	ob_start();

	foreach ( $categories as $key => $category ) 
	{
		if ( $count >= $end )
			break;

		$_GET['s']='';
		if ( $category->parent != $parent && empty($_GET['s']) )
			continue;

		// If the page starts in a subtree, print the parents.
		if ( $count == $start && $category->parent > 0 ) {
			$my_parents = array();
			$p = $category->parent;
			while ( $p ) {
				$my_parent = get_category( $p );
				$my_parents[] = $my_parent;
				if ( $my_parent->parent == 0 )
					break;
				$p = $my_parent->parent;
			}

			$num_parents = count($my_parents);
			while( $my_parent = array_pop($my_parents) ) {
				$category_array[] = _cat_rows1( $my_parent, $level - $num_parents );
				$num_parents--;
			}
		}

		if ($count >= $start)
		{
			$categoryinfo = array();
			$category = get_category( $category, '', '' );
			$default_cat_id = (int) get_option( 'default_category' );
			$pad = str_repeat( '&#8212; ', max(0, $level) );
			$name = ( $name_override ? $name_override : $pad . ' ' . $category->name );
			$categoryinfo['ID'] = $category->term_id;
			$categoryinfo['name'] = $name;
			$category_array[] = $categoryinfo;
		}

		unset( $categories[ $key ] );
		$count++;
		if ( isset($children[$category->term_id]) )
			_cat_rows1( $category->term_id, $level + 1, $categories, $children, $page, $per_page, $count );
	}
	$output = ob_get_contents();
	ob_end_clean();
	return $category_array;
}
function get_post_info($pid)
{
	global $wpdb;
	$productinfosql = "select ID,post_title,post_content from $wpdb->posts where ID=$pid";
	$productinfo = $wpdb->get_results($productinfosql);
	if($productinfo)
	{
		foreach($productinfo[0] as $key=>$val)
		{
			$productArray[$key] = $val; 
		}
	}
	return $productArray;
}

function get_payment_optins($method)
{
	global $wpdb;
	$paymentsql = "select * from $wpdb->options where option_name like 'payment_method_$method'";
	$paymentinfo = $wpdb->get_results($paymentsql);
	if($paymentinfo)
	{
		foreach($paymentinfo as $paymentinfoObj)
		{
			$option_value = unserialize($paymentinfoObj->option_value);
			$paymentOpts = $option_value['payOpts'];
			$optReturnarr = array();
			for($i=0;$i<count($paymentOpts);$i++)
			{
				$optReturnarr[$paymentOpts[$i]['fieldname']] = $paymentOpts[$i]['value'];
			}
			return $optReturnarr;
		}
	}
}

add_filter('templ_author_listing_links_filter','templ_author_listing_links_fun');
function templ_author_listing_links_fun()
{
	global $wp_query, $current_user,$post;
	$queryvar = $wp_query->query_vars;
	 if($current_user->data->ID == $queryvar['author'] || $current_user->data->ID==1)
	 { ?>
        <span class="author_link"><a href="<?php echo site_url('/?ptype=add_step2&amp;pid='.$post->ID);?>"><?php echo POST_EDIT_TEXT;?></a></span>
        
        <span class="author_link"><a href="<?php echo site_url('/?ptype=add_step2&amp;renew=1&amp;pid='.$post->ID);?>"><?php echo RENEW_TEXT;?></a></span>
        
        <span class="author_link"><a href="<?php echo site_url('/?ptype=add_step4&amp;pid='.$post->ID);?>"><?php echo POST_DELETE_TEXT;?></a></span>
	 <?php
     }
}

function get_categories_checkboxes_form($taxno_name, $selected_cats = null) {
	$args = array('get' => 'all','taxonomy' 	=> $taxno_name);
	$all_categories = get_categories($args);	
	//$chkcounter=0;
	$o = '<div class="cat_list" ><ul class="category_list">';
	foreach($all_categories as $key => $cat) {
		//$chkcounter++;
		if($cat->parent == "0") $o .= __show_category_form($cat, $selected_cats, $taxno_name,$chkcounter=0);
	}
	return $o . '</ul></div>';
}
function __show_category_form($cat_object, $selected_cats = null, $taxno_name,$chkcounter) {
	$args = array('taxonomy'                 => $taxno_name,
				  'parent'                 => $cat_object->cat_ID);
	$checked = "";
	if(!is_null($selected_cats) && is_array($selected_cats)) {
		$checked = (in_array($cat_object->cat_ID, $selected_cats)) ? 'checked="checked"' : "";
	}
	//$chkcounter++;
	$ou = '<li><label><input ' . $checked .' type="checkbox" name="post_category[]" id="post_category_'. $chkcounter .'" value="'. $cat_object->cat_name .'" /> ' . $cat_object->cat_name . '</label>';
	$childs = get_categories($args);
	foreach($childs as $key => $cat) {
		$chkcounter++;
		$ou .= '<ul class="category_list_sub">' . __show_category_form($cat, $selected_cats, $taxno_name,&$chkcounter) . '</ul>';
	}
	$ou .= '</li>';
	return $ou;
}

add_action('templ_after_loop_post_content','templ_after_loop_post_content_fnc');
function templ_after_loop_post_content_fnc()
{
	if(is_author())
	{
		
		global $current_user,$wp_query,$post;
		$qvar = $wp_query->query_vars;
		$authname = $qvar['author_name'];
		$nicename = $current_user->data->user_nicename;
		
		if(($authname == $nicename) || ($_REQUEST['author']== $current_user->data->ID))
		{
		
			if($post->post_type==CUSTOM_POST_TYPE1)
			{
				$editurl = site_url("/?ptype=add_step2&pid=$post->ID");
				$deleteurl = site_url("/?ptype=add_step4&pid=$post->ID");
			?>
			<a href="<?php echo $editurl;?>" class="edit"><?php _e('Edit','templatic');?></a> 
			<a href="<?php echo $deleteurl;?>" class="delete"><?php _e('Delete','templatic');?></a>
			<?php
			}else
			{
			?>
		   	<?php edit_post_link('Edit', '', '');?>
			<?php
			}
		}
	}
}

//*********************************************
// author cutom post listing filer.
//*********************************************
add_action('pre_get_posts', 'author_filter');
function author_filter($local_wp_query)
{
	if(is_author())
	{
		add_filter('posts_where', 'posts_where_author');
	}
}
function posts_where_author($where)
{
	global $current_user,$wp_query,$post;
	
	$qvar = $wp_query->query_vars;
	$authname = $qvar['author_name'];
	$nicename = $current_user->data->user_nicename;
	if(($authname == $nicename) || ($_REQUEST['author']== $current_user->data->ID))
	{
		$where = str_replace("'post'","'".CUSTOM_POST_TYPE1."'", $where);
		$where = str_replace("OR wp_posts.post_status = 'private'","OR wp_posts.post_status = 'private' OR wp_posts.post_status = 'draft'", $where);
	}else
	{
		 $where = str_replace("'post'","'".CUSTOM_POST_TYPE1."'", $where);
	}
	return $where;
}

function templ_get_post_taxonomy($pid,$custom_taxonomy = CUSTOM_CATEGORY_TYPE1)
{
	if($pid)
	{
		$category = wp_get_object_terms($pid, $custom_taxonomy);
		$category_arr = array();
		if($category)
		{	
			foreach($category as $category_obj)
			{
				$category_arr[$category_obj->term_id] = $category_obj->name;	
			}
			if($category_arr)
			{
				return implode(', ',$category_arr);	
			}
		}		
	}
}
?>