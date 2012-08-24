<?php
//submit_event.php
$currency = get_option('currency');
$currencysym = get_option('currencysym');


define('INDICATES_MANDATORY_FIELDS_TEXT',__('Indicates mandatory fields','templatic'));
define('POST_TITLE',__('Add Event','templatic'));
define('RENEW_TEXT',__('Renew','templatic'));
define('POST_EDIT_TEXT',__('Update','templatic'));
define('POST_DELETE_TEXT',__('Delete','templatic'));
define('POST_PLACE_TITLE',__('Add Listing','templatic'));
define('RENEW_LISING_TEXT',__('Renew','templatic'));
define('EDIT_LISING_TEXT',__('Update','templatic'));

define('IAM_TEXT',__("I am",'templatic'));
define('LOGINORREGISTER',__("Login or Register",'templatic'));
define('EXISTING_USER_TEXT',__("Existing User",'templatic'));
define('NEW_USER_TEXT',__("New User? Register Now",'templatic'));
define('LOGIN_TEXT',__('Login','templatic'));
define('PASSWORD_TEXT',__('Password','templatic'));
define('SUBMIT_BUTTON',__('Submit','templatic'));
define('PRO_PHOTO_TEXT',__('Add Images : <small>(You can upload more than one images to create image gallery on detail page)</small>','templatic'));
define('PHOTOES_BUTTON',__('Select Images','templatic'));
define('PRO_DESCRIPTION_TEXT',__('Listing Description','templatic'));
define('EVENT_DESCRIPTION_TEXT',__('Event Description','templatic'));
define('PRO_FEATURE_TEXT',__('Special Offers','templatic'));
define('CONTACT_TEXT',__('Contact','templatic'));
define('PRO_ADD_COUPON_TEXT',__('Enter Coupon Code','templatic'));
define('COUPON_NOTE_TEXT',__('Enter coupon code here (optional)','templatic'));
define('CAPTCHA_TITLE_TEXT',__('Captcha Verification','templatic'));
define('SELECT_TYPE_TEXT',__('Select Package Type','templatic'));
define('COUPON_CODE_TITLE_TEXT',__('Coupon Code','templatic'));

define('PUBLISH_DAYS_TEXT',__('%s : number of publish days are %s (<span id="%s">%s %s</span>)','templatic'));

define('SUBMIT_POST_PREVIEW_DISCOUNT_MSG',__('<br>Your applied coupon code is "%s" and discount amout is %s, deducted from the payable amount.','templatic'));

define('GOING_TO_PAY_MSG',__('This is preview of your article and it´s not published yet.  If there is something wrong, <br /> click "Go back and edit" or  if you want to add the  article then click on "Publish".','templatic'));
define('SUBMIT_POST_PREVIEW_FREE_MSG',__('This is preview of your article and it&rsquo;s not published yet.  If there is something wrong, <br /> click "Go back and edit" or  if you want to add the  article then click on "Publish".','templatic'));

define('GOING_TO_UPDATE_MSG',__('This is preview of your article and its not updated yet. <br />If there is something wrong then "Go back and edit" or if you want to add article then click on "Update now"','templatic'));
define('WRONG_COUPON_MSG',__('Invalid Coupon Code','templatic'));
define('CAPTCHA',__('Word Verification','templatic'));
define('PRO_PREVIEW_BUTTON',__('Review Your article','templatic'));
define('SELECT_LISTING_TYPE_TEXT',__('Select article Type','templatic'));
define('SELECT_POST_TYPE_TEXT',__('Select Type','templatic'));
define('IMAGE_ORDERING_MSG',__('Note : You can sort images from Dashboard and then clicking on "Edit" in the lisitng','templatic'));
define('CONTACT_NAME_TEXT',__('Name','templatic'));
define('CITY_TEXT',__('City','templatic'));
define('STATE_TEXT',__('State','templatic'));
define('MOBILE_TEXT',__('Mobile Number','templatic'));
define('EMAIL_TEXT',__('Email','templatic'));
define('WEBSITE_TEXT',__('Website','templatic'));
define('TWITTER_TEXT',__('Twitter','templatic'));
define('FACEBOOK_TEXT',__('Facebook','templatic'));
define('PHOTO_TEXT',__('Upload Photo','templatic'));
define('BIODATA_TEXT',__('Short Biodata Information','templatic'));
define('IMAGE_TYPE_MSG',__('Note : PNG, GIF of JPEG only, for better image quality upload image size 150x150','templatic'));
define('HTML_TAGS_ALLOW_MSG',__('Note : Basic HTML tags are allowed','templatic'));
define('HTML_SPECIAL_TEXT',__('Note: List out any special offers (optional)','templatic'));

define('PRO_BACK_AND_EDIT_TEXT',__('&laquo; Go Back and Edit','templatic'));
define('PRO_UPDATE_BUTTON',__('Update Now','templatic'));
define('PRO_SUBMIT_BUTTON',__('Publish','templatic'));
define('PRO_CANCEL_BUTTON',__('Cancel','templatic'));
define('PRO_SUBMIT_PAY_BUTTON',__('Pay & Publish','templatic'));
define('CONTACT_DETAIL_TITLE',__('Publisher Information','templatic'));
define('LISTING_DETAILS_TEXT',__('Enter article Details','templatic'));

define('POST_DELETE_PRE_MSG',__('Are you really sure want to DELETE this article? A deleted article can not be recovered later','templatic'));
define('POST_DELETE_BUTTON',__('Yes, Delete Please!','templatic'));
define('IS_A_FEATURE_PRO_TEXT',__('This place is listed as Featured. Do you want to remove it from featured article?','templatic'));
define('SELECT_PAY_MEHTOD_TEXT',__('Select Payment Method','templatic'));

//success.php
define('POSTED_SUCCESS_TITLE',__('Submission Successfully','templatic'));
define('RENEW_SUCCESS_TITLE',__('Article Renewal Successful','templatic'));
define('POSTED_SUCCESS_MSG',__('<h2>Article information posted successfully.</h2> <p> You can edit this message from language.php file at root of theme folder.</p>','templatic'));
//cancel.php
define('PAY_CANCELATION_TITLE',__('Article Posting Canceled','templatic'));
define('PAY_CANCEL_MSG',__('Article Post Canceled.<br><br>You can edit this message from language.php file at root of theme folder.','templatic'));

//return.php
define('PAYMENT_SUCCESS_TITLE',__('Payment Success','templatic'));
define('PAYMENT_SUCCESS_MSG',__('Thank you for joining us. Your payment was received and Article post published.<br>Thank you for becoming our valued member.<br><br>You can edit this message from language.php file at root of theme folder.','templatic'));
?>
<?php
$form_fields = array();

$form_fields['post_title'] = array(
		"label"		=> 'Title',
		"type"		=>	'text',
		"default"	=>	'',
		"extra"		=>	'id="post_title" size="25" class="textfield medium"',
		"is_require"	=>	'1',
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<span id="ptitle_span" class="message_error2"></span>',
		);

$form_fields['post_content'] = array(
		"label"		=> 'Description ',
		"type"		=>	'textarea',
		"default"	=>	'',
		"extra"		=>	'id="post_content" size="25" class="textfield large mceEditor"',
		"is_require"	=>	'',
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">Note : Basic HTML tags are allowed</p>',
		);

// Category Checkboxes
/*$form_fields['post_category'] = array(
		"label"		=> 'Category',
		"type"		=>	'catcheckbox',
		"default"	=>	'',
		"extra"		=>	'class="checkbox"',
		"is_require"	=>	'1',
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_before"=>	'<div class="form_cat">',
		"tag_after"=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">Select Category</p>',
		);*/

// Our Own Category Drop Down
/* $form_fields['post_category'] = array(
		"label"		=> 'Category',
		"type"		=>	'catselect',
		"default"	=>	'',
		"extra"		=>	'id="post_category_0" class="textfield"',
		"is_require"	=>	'1',
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">Select Category</p>',
		); */

// Wordpress Default Category Drop Down
$form_fields['post_category'] = array(
		"label"		=> 'Category',
		"type"		=>	'catdropdown',
		"default"	=>	'',
		"extra"		=>	'id="post_category" class="textfield"',
		"is_require"	=>	'1',
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">Category</p>',
		);

/* // Category Radio Button
$form_fields['post_category'] = array(
		"label"		=> 'Category',
		"type"		=>	'catradio',
		"default"	=>	'',
		"extra"		=>	'id="post_category"',
		"js"		=>	'1',
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_before"=>	'<div class="form_cat">',
		"tag_after"=>	'</div>',
		"tag_st"	=>	'<div class="category_label">',
		"tag_end"	=>	'</div>',
		);*/

$form_fields['post_tags'] = array(
		"label"		=> 'Tags',
		"type"		=>	'text',
		"default"	=>	'',
		"extra"		=>	'id="post_tags" size="25" class="textfield medium"',
		"is_require"	=>	'',
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">Enter keywords. eg. : <b>mykeyword1, mykeyword2</b></p>',
		);
$form_fields['website'] = array(
		"label"		=> 'Article Url',
		"type"		=>	'text',
		"default"	=>	'',
		"extra"		=>	'id="website" size="25" class="textfield medium"',
		"is_require"	=>	'',
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">Enter Article Url. eg. : <b>http://www.test.com/your-article.html</b></p>',
		);
$form_fields['listing_img'] = array(
		"label"		=> 'Image',
		"type"		=>	'upload',
		"default"	=>	'',
		"extra"		=>	'id="listing_img"',
		"is_require"	=>	'',
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">Select image to upload</p>',
		);

$custom_metaboxes = get_post_custom_fields_templ(CUSTOM_POST_TYPE1);
foreach($custom_metaboxes as $key=>$val)
{
	$name = $val['name'];
	$site_title = $val['site_title'];
	$type = $val['type'];
	$default_value = $val['default'];
	$is_require = $val['is_require'];
	$admin_desc = $val['desc'];
	$option_values = $val['option_values'];
	if($type=='text'){
		$form_fields[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'text',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="textfield medium"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">'.$admin_desc.'</p>',
		);
	}elseif($type=='checkbox'){
		$form_fields[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'checkbox',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="checkbox"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="addlisting_row"><div class="category_label">',
		"outer_end"	=>	'</div></div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">'.$admin_desc.'</p>',
		);
	}elseif($type=='textarea'){
		$form_fields[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'textarea',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="textfield large"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">'.$admin_desc.'</p>',
		);
		
	}elseif($type=='texteditor'){
		$form_fields[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'texteditor',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="textfield large mceEditor"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">'.$admin_desc.'</p>',
		);
	}elseif($type=='select'){
		//$option_values=explode(",",$option_values );
		$form_fields[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'select',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" class="select xxl"',
		"options"	=> 	$option_values,
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'',			
		);
	}elseif($type=='radio'){
		//$option_values=explode(",",$option_values );
		$form_fields[$name] = array(
			"label"		=> $site_title,
			"type"		=>	'radio',
			"default"	=>	$default_value,
			"extra"		=>	'',
			"options"	=> 	$option_values,
			"is_require"	=>	$is_require,
			"outer_st"	=>	'<div class="addlisting_row">',
			"outer_end"	=>	'</div>',
			"tag_before"=>	'<div class="form_cat">',
			"tag_after"=>	'</div>',
			"tag_st"	=>	'',
			"tag_end"	=>	'<p class="message_note listing">'.$admin_desc.'</p>',		
			);
	}elseif($type=='multicheckbox'){
		//$option_values=explode(",",$option_values );
		$form_fields[$name] = array(
			"label"		=> $site_title,
			"type"		=>	'multicheckbox',
			"default"	=>	$default_value,
			"extra"		=>	'',
			"options"	=> 	$option_values,
			"is_require"	=>	$is_require,
		    "outer_st"	=>	'<div class="addlisting_row">',
		    "outer_end"	=>	'</div>',
			"tag_before"=>	'<div class="form_cat"><label>',
			"tag_after"=>	'</label></div>',
			"tag_st"	=>	'<div class="category_label">',
			"tag_end"	=>	'</div><p class="message_note listing">'.$admin_desc.'</p>',		
			);
	
	}elseif($type=='date'){
		$form_fields[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'date',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" size="25" class="textfield medium"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">'.$admin_desc.'</p> ',
		);
		
	}elseif($type=='upload'){
	$form_fields[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'upload',
		"default"	=>	$default_value,
		"extra"		=>	'id="'.$name.'" class="textfield medium"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="addlisting_row">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'<p class="message_note listing">'.$admin_desc.'</p>',
		);
	}elseif($type=='head'){
	$form_fields[$name] = array(
		"label"		=> $site_title,
		"type"		=>	'head',
		"outer_st"	=>	'<h3>',
		"outer_end"	=>	'</h3>',
		);
	}elseif($type=='geo_map'){
	$form_fields[$name] = array(
		"label"		=> '',
		"type"		=>	'geo_map',
		"default"	=>	$default_value,
		"extra"		=>	'',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'',
		"outer_end"	=>	'',
		"tag_st"	=>	'',
		"tag_end"	=>	'',
		);		
	}elseif($type=='image_uploader'){
	$form_fields[$name] = array(
		"label"		=> '',
		"type"		=>	'image_uploader',
		"default"	=>	$default_value,
		"extra"		=>	'',
		//"is_require"	=>	$is_require,
		"outer_st"	=>	'',
		"outer_end"	=>	'',
		"tag_st"	=>	'',
		"tag_end"	=>	'',
		);		
	}
}
/*$form_fields['coupon_code_heading'] = array(
			"label"		=> 'Coupon Code',
			"type"		=>	'head',
			"outer_st"	=>	'<h3>',
			"outer_end"	=>	'</h3>',
			);

$form_fields['coupon_code'] = array(
		"label"		=> 'Enter Coupon Code',
		"type"		=>	'text',
		"default"	=>	'',
		"extra"		=>	'id="coupon_code" size="25" class="textfield"',
		"is_require"	=>	$is_require,
		"outer_st"	=>	'<div class="form_row clearfix">',
		"outer_end"	=>	'</div>',
		"tag_st"	=>	'',
		"tag_end"	=>	'',
		);

if($_REQUEST['pid']=='' || ($_REQUEST['pid']!='' && $_REQUEST['renew']))
{
	$form_fields['heading'] = array(
			"label"		=> ' Select Package',
			"type"		=>	'head',
			"outer_st"	=>	'<h5 class="form_title">',
			"outer_end"	=>	'</h5>',
			);
	
	if(function_exists('templ_get_package_price_html'))
	{
		$pack_opts = templ_get_package_price_html();
	}
	$form_fields['package'] = array(
			"label"		=> '',
			"type"		=>	'packageradio',
			"default"	=>	'1',
			"extra"		=>	'',
			"options"	=> 	$pack_opts,
			"is_require"	=>	$is_require,
			"outer_st"	=>	'<div class="packages">',
			"outer_end"	=>	'</div>',
			"tag_before"=>	'<div class="package">',
			"tag_after" =>	'</div>',
			"tag_st"	=>	'',
			"tag_end"	=>	'',		
			);
	
}*/
?>