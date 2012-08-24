<?php
session_start();
ob_start();
add_filter('templ_payable_amount_filter','templ_payable_amount_fun');
function templ_payable_amount_fun($amt)
{
	if($_REQUEST['coupon_code']!='')
	{
		$discount_amt = get_discount_amount($_REQUEST['coupon_code'],$amt);
		$amt = $amt-$discount_amt;
	}
	return $amt;
}
global $current_user;
if($current_user->data->ID=='' && $_SESSION['submission_info'])
{
	include_once(TEMPL_ADD_POST_FOLDER.'single_page_checkout_insertuser.php');
}
global $form_fields;
$postmeta_arr = array();
$payable_amount = 0;
if(function_exists('templ_paynow_before_insert')){
$payable_amount = templ_paynow_before_insert();
}

foreach($form_fields as $fkey=>$fval)
{
	if($fval['type']=='text' || $fval['type']=='textarea' || $fval['type']=='select' || $fval['type']=='file' || $fval['type']=='checkbox' || $fval['type']=='radio' || $fval['type']=='catselect' || $fval['type']=='catdropdown' || $fval['type']=='catradio' || $fval['type']=='catcheckbox' || $fval['type']=='upload' || $fval['type']=='hidden' || $fval['type']=='disabled' || $fval['type']=='date' || $fval['type']=='multicheckbox' | $fval['type']=='texteditor')
	{
		$fldkey = "$fkey";
		$$fldkey = $_SESSION['submission_info']["$fkey"];
		if(is_array($$fldkey))
		{
			$$fldkey = implode(',',$$fldkey);
		}
		if($fldkey=='post_title' || $fldkey=='post_content' || $fldkey=='post_tags' || $fldkey=='post_status' || $fldkey=='post_date' || $fldkey=='post_author' || $fldkey=='post_category' || $fldkey=='post_excerpt')
		{			
		}else
		{
			$postmeta_arr[$fldkey] = $$fldkey;
		}
	}
	
	if($fval['type']=='geo_map')
	{
		$postmeta_arr['geo_address'] = $_SESSION['submission_info']['geo_address'];	
		$postmeta_arr['geo_longitude'] =  $_SESSION['submission_info']['geo_longitude'];	
		$postmeta_arr['geo_latitude'] =  $_SESSION['submission_info']['geo_latitude'];	
	}
}

global $current_user;
if (is_numeric($post_category)) {
$term = get_term( (int)$post_category, CUSTOM_CATEGORY_TYPE1);
$post_category=$term->name;
}

if(strtolower(get_option('ptthemes_article_status'))=='draft' || get_option('ptthemes_article_status')==''){
	$post_status='draft';
}
else{
	$post_status='publish';
}


if(!$post_status){$post_status = get_property_default_status();}
if(!$post_author){$post_author = $current_user->data->ID;}
if($post_category){$catids_arr = explode(',',$post_category);}else{$catids_arr = array('1');}
if($post_tags){$tagkw = explode(',',$post_tags);}
$my_post = array();
$my_post['post_title'] = apply_filters('templ_paynow_title_filter',$post_title);
$my_post['post_type'] = apply_filters('templ_paynow_post_type_filter',CUSTOM_POST_TYPE1);
if($post_content){$my_post['post_content'] = apply_filters('templ_paynow_content_filter',$post_content);}
if($post_excerpt){$my_post['post_excerpt'] = apply_filters('templ_paynow_excerpt_filter',$post_excerpt);}
if($catids_arr){$my_post['tax_input'] = apply_filters('templ_paynow_category_filter',$catids_arr);}
if($tagkw){$my_post['tags_input'] = apply_filters('templ_paynow_tagkw_filter',$tagkw);}
if($post_author){$my_post['post_author'] = apply_filters('templ_paynow_author_filter',$post_author);}
if($post_status){$my_post['post_status'] = apply_filters('templ_paynow_status_filter',$post_status);}
if($post_date){ $my_post['post_date'] = apply_filters('templ_paynow_postdate_filter',$post_date);}
if($_REQUEST['pid'] && $submission_info['renew']=='')
{
	$my_post['ID'] = $_POST['pid'];
	$my_post['post_status'] = apply_filters('templ_paynow_edit_status_filter',get_post_status($_POST['pid']));
}
$submission_info = $_SESSION['submission_info'];
if($_REQUEST['pid'])
{
	if($submission_info['renew'])
	{
		$my_post['ID'] = $_POST['pid'];
		$my_post['post_date'] = date('Y-m-d H:i:s');
		$my_post['post_status'] = apply_filters('templ_paynow_renew_status_filter',$post_status);
		$last_postid = wp_update_post($my_post); //renew post
		wp_set_object_terms($last_postid, $catids_arr, $taxonomy=CUSTOM_CATEGORY_TYPE1);
		wp_set_object_terms($last_postid, $tagkw, $taxonomy=CUSTOM_TAG_TYPE1);
	}else
	{
		
		$last_postid = wp_update_post($my_post); //update post
		wp_set_object_terms($last_postid, $catids_arr, $taxonomy=CUSTOM_CATEGORY_TYPE1);
		wp_set_object_terms($last_postid, $tagkw, $taxonomy=CUSTOM_TAG_TYPE1);
	}
	
}else
{
	$last_postid = wp_insert_post($my_post); //insert post
	wp_set_object_terms($last_postid, $catids_arr, $taxonomy=CUSTOM_CATEGORY_TYPE1);
	wp_set_object_terms($last_postid, $tagkw, $taxonomy=CUSTOM_TAG_TYPE1);
}

if($_SESSION["file_info"])
{
	$wp_upload_dir = wp_upload_dir();
	$subdir = $wp_upload_dir['subdir'];
	for($im=0;$im<count($_SESSION["file_info"]);$im++)
	{
		$image = $_SESSION["file_info"][$im];
		$image_arr = explode('/',$image);
		$img_name = $dest[count($image_arr)-1];
		$post_img['post_title'] = $img_name;
		$post_img['guid'] = $image;
		$post_img['post_status'] = 'attachment';
		$post_img['post_parent'] = $last_postid;
		$post_img['post_type'] = 'attachment';
		$post_img['post_mime_type'] = 'image/jpeg';
		$post_img['menu_order'] = $im;
		$last_postimage_id = wp_insert_post( $post_img ); // Insert the post into the database
	
		$thumb_info_arr = array();
		$hwstring_small = "height='1024' width='900'";
		
		update_post_meta($last_postimage_id, '_wp_attached_file', get_attached_file_meta_path($post_img['guid']));
		$post_attach_arr = array(
							"width"	=>	'900',
							"height" => '1024',
							"file"	=> get_attached_file_meta_path($post_img['guid']),
							"sizes"=> $sizes_info_array,
							);
		wp_update_attachment_metadata( $last_postimage_id, $post_attach_arr );
	}
}

$postmeta_arr = apply_filters('templ_paynow_custom_fields_filter',$postmeta_arr);
if($last_postid && $postmeta_arr)
{
	foreach($postmeta_arr as $pmkey=>$pmval)
	{
		update_post_meta($last_postid, $pmkey, $pmval);	//POST META
	}
}

$_SESSION['submission_info'] = array();
$_SESSION['file_info'] = array();

if(apply_filters('templ_skip_payment_method','0'))
{	
	templ_paynow_after_insert_nopayment();
}else
{
	templ_paynow_after_insert_payment($payable_amount,$last_postid,$my_post);	
}

/*global $current_user;
if($current_user->data->ID=='' && $_SESSION['submission_info'])
{
	include_once(TEMPLATEPATH . '/library/includes/single_page_checkout_insertuser.php');
}*/
?>