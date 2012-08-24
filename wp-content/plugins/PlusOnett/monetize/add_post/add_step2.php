<?php
session_start();
ob_start();
if($_POST['user_login_or_not']=='new_user')
{
	function register_new_user($user_login, $user_email, $user_phone) {
	global $wpdb;
	$errors = new WP_Error();
	
	$user_login = sanitize_user( $user_login );
	$user_email = apply_filters( 'user_registration_email', $user_email );

	// Check the username
	if ( $user_login == '' )
		$errors->add('empty_username', __('ERROR: Please enter a username.','templatic'));
	elseif ( !validate_username( $user_login ) ) {
		$errors->add('invalid_username', __('<strong>ERROR</strong>: This username is invalid.  Please enter a valid username.','templatic'));
		$user_login = '';
	} elseif ( username_exists( $user_login ) )
		$errors->add('username_exists', __('<strong>ERROR</strong>: This username is already registered, please choose another one.','templatic'));

	// Check the e-mail address
	if ($user_email == '') {
		$errors->add('empty_email', __('<strong>ERROR</strong>: Please enter your e-mail address.','templatic'));
	} elseif ( !is_email( $user_email ) ) {
		$errors->add('invalid_email', __('<strong>ERROR</strong>: The email address isn&#8217;t correct.','templatic'));
		$user_email = '';
	} elseif ( email_exists( $user_email ) )
		$errors->add('email_exists', __('<strong>ERROR</strong>: This email is already registered, please choose another one.','templatic'));
		
	// Check the user_phone
	if ( $user_phone == '' )
		$errors->add('empty_phone', __('ERROR: Please enter contact detail','templatic'));
	do_action('register_post', $user_login, $user_email, $errors);
	$errors = apply_filters( 'registration_errors', $errors );
	if($errors)
	{
		$_SESSION['userinset_error'] = array();
		foreach($errors as $errorsObj)
		{
			foreach($errorsObj as $key=>$val)
			{
				for($i=0;$i<count($val);$i++)
				{
					$usererror .= $val[$i].'<br />';
					if($val[$i]){break;}
				}
			} 
		}
		$_SESSION['userinset_error'] = $usererror;
		
	}
	if ( $errors->get_error_code() )
	{
		$_SESSION['userinset_error'] = $errors;
		wp_redirect(get_option('siteurl').'?ptype=add_step1&usererror=1');
		exit;
	}	
	$user_pass = wp_generate_password(12,false);
	$user_id = wp_create_user( $user_login, $user_pass, $user_email );
	
	global $form_fields_usermeta;
	foreach($form_fields_usermeta as $fkey=>$fval)
	{
		$fldkey = "$fkey";
		$$fldkey = $_POST["$fkey"];
		update_usermeta($user_id, $fkey, $$fldkey); // User Custom Metadata Here
	}
	
	$userName = $_POST['user_fname'];
	update_usermeta($user_id, 'first_name', $_POST['user_fname']); // User First Name Information Here
	update_usermeta($user_id, 'last_name', $_POST['user_lname']); // User Last Name Information Here
	
	$user_nicename = get_user_nice_name($_POST['user_fname'],$_POST['user_lname']); //generate nice name
	$updateUsersql = "update $wpdb->users set user_url=\"$user_web\", user_nicename=\"$user_nicename\", display_name=\"$userName\"  where ID=\"$user_id\"";
	$wpdb->query($updateUsersql);
	
	if ( !$user_id ) {
		$errors->add('registerfail', sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !','templatic'), get_option('admin_email')));
		return $errors;
	}
	global $upload_folder_path;
	
	if ( $user_id ) 
	{
		///////REGISTRATION EMAIL START//////
		$fromEmail = get_site_emailId();
		$fromEmailName = get_site_emailName();
		$store_name = get_option('blogname');
		$client_message = apply_filters('templ_registration_email_content',__('<p>Dear [#$user_name#],</p>
		<p>Your login information:</p>
		<p>Username: [#$user_login#]</p>
		<p>Password: [#$user_password#]</p>
		<p>You can login from [#$store_login_url#] or the URL is : [#$store_login_url_link#].</p>
		<p>We hope you enjoy. Thanks!</p>
		<p>[#$store_name#]</p>','templatic'));
		$subject = apply_filters('templ_registration_email_subject','Registration Confirmation Email');
		$store_login = '<a href="'.site_url().'/?ptype=login">Click Login</a>';
		$store_login_link = site_url().'/?ptype=login';
		/////////////customer email//////////////
		$search_array = array('[#$user_name#]','[#$user_login#]','[#$user_password#]','[#$store_name#]','[#$store_login_url#]','[#$store_login_url_link#]');
		$replace_array = array($user_login,$user_login,$user_pass,$store_name,$store_login,$store_login_link);
		$client_message = str_replace($search_array,$replace_array,$client_message);	
		templ_sendEmail($fromEmail,$fromEmailName,$user_email,$userName,$subject,$client_message,$extra='');///To clidne email
		//////REGISTRATION EMAIL END////////
	}
	return array($user_id,$user_pass);
}
if ( defined('RELOCATE') ) { // Move flag is set
	if ( isset( $_SERVER['PATH_INFO'] ) && ($_SERVER['PATH_INFO'] != $_SERVER['PHP_SELF']) )
		$_SERVER['PHP_SELF'] = str_replace( $_SERVER['PATH_INFO'], '', $_SERVER['PHP_SELF'] );

	$schema = ( isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
	if ( dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) != site_url() )
		update_option('siteurl', dirname($schema . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']) );
}

//Set a cookie now to see if they are supported by the browser.
//setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
if ( SITECOOKIEPATH != COOKIEPATH )
	setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);

// allow plugins to override the default actions, and to add extra actions if they want
do_action('login_form_' . $action);

$http_post = ('POST' == $_SERVER['REQUEST_METHOD']);

$user_login = '';
	$user_email = '';
	if ( $http_post ) {
		$user_login = $_POST['user_fname'];
		$user_email = $_POST['user_email'];
		$user_phone = $_POST['user_phone'];
		
		$errors = register_new_user($user_login, $user_email, $user_phone);
		if ( !get_option('users_can_register') ) {
		wp_redirect(get_option('siteurl').'?ptype=add_step1&usererror=1');
		exit();
	}
		if ( !is_wp_error($errors) ) 
		{
			$_POST['log'] = $user_login;
			$_POST['pwd'] = $errors[1];
			$_POST['testcookie'] = 1;
			
			$secure_cookie = '';
			// If the user wants ssl but the session is not ssl, force a secure cookie.
			if ( !empty($_POST['log']) && !force_ssl_admin() )
			{
				$user_name = sanitize_user($_POST['log']);
				if ( $user = get_userdatabylogin($user_name) )
				{
					if ( get_user_option('use_ssl', $user->ID) )
					{
						$secure_cookie = true;
						force_ssl_admin(true);
					}
				}
			}
			if($_REQUEST['reg_redirect_link']=='')
			{
				//$_REQUEST['reg_redirect_link']=get_author_link($echo = false, $errors[0]);
				
				$redirect_to = 	site_url('/?ptype=add_step2');
			}
			//$redirect_to = $_REQUEST['reg_redirect_link'];
			$redirect_to = 	site_url('/?ptype=add_step2');
			if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
				$secure_cookie = false;
		
			$user = wp_signon('', $secure_cookie);
		
			if ( !is_wp_error($user) ) 
			{
				wp_safe_redirect($redirect_to);
				exit();
			}
			exit();
		}
	}
	
}
if($_POST['user_login_or_not']=='existing_user')
{
	$secure_cookie = '';
	

	if ( !empty($_POST['log']) && !force_ssl_admin() ) {
		$user_name = sanitize_user($_POST['log']);
		if ( $user = get_userdatabylogin($user_name) ) {
			if ( get_user_option('use_ssl', $user->ID) ) {
				$secure_cookie = true;
				force_ssl_admin(true);
			}
		}
	}
	
	///////////////////////////
	
	if($_REQUEST['redirect_to']=='')
	{
		$_REQUEST['redirect_to']=get_author_link($echo = false, $user->ID);
	}
	if ( isset( $_REQUEST['redirect_to'] ) ) {
		$redirect_to = $_REQUEST['redirect_to'];
		// Redirect to https if user wants ssl
		if ( $secure_cookie && false !== strpos($redirect_to, 'wp-admin') )
			$redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
	} else {
		$redirect_to = admin_url();
	}

	if ( !$secure_cookie && is_ssl() && force_ssl_login() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
		$secure_cookie = false;

	$user = wp_signon('', $secure_cookie);

	$redirect_to = apply_filters('login_redirect', $redirect_to, isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '', $user);

	if(is_wp_error($user))
	{
		if(strstr($_SERVER['HTTP_REFERER'],'ptype=submition') && $_POST['log']!='' && $_POST['pwd']!='')
		{
			wp_redirect($_SERVER['HTTP_REFERER'].'&emsg=1');
		}
	}
	
	if ( !is_wp_error($user) ) {
	if(!strstr($_SERVER['HTTP_REFERER'],'ptype=submition'))
	{
		//$redirect_to = 	get_author_link($echo = false, $user->data->ID);
		$redirect_to = 	site_url('/?ptype=add_step2');
	}
	$redirect_to = apply_filters('templ_login_redirect_filter',$redirect_to);
	wp_redirect($redirect_to);
	exit();
	}

	$errors = $user;
	// Clear errors if loggedout is set.
	if ( !empty($_GET['loggedout']) )
		$errors = new WP_Error();
	// If cookies are disabled we can't log in even with a valid user+pass
	if ( isset($_POST['testcookie']) && empty($_COOKIE[TEST_COOKIE]) )
		$errors->add('test_cookie', __("<strong>ERROR</strong>: Cookies are blocked or not supported by your browser. You must <a href='http://www.google.com/cookies.html'>enable cookies</a> to use WordPress.",'templatic'));

	// Some parts of this script use the main login form to display a message
	if( isset($_GET['loggedout']) && TRUE == $_GET['loggedout'] )
	{
		$successmsg = '<div class="sucess_msg">'.YOU_ARE_LOGED_OUT_MSG.'</div>';
	}
	elseif( isset($_GET['registration']) && 'disabled' == $_GET['registration'] )
	{
		$successmsg = USER_REG_NOT_ALLOW_MSG;
	}
	elseif( isset($_GET['checkemail']) && 'confirm' == $_GET['checkemail'] )
	{
		$successmsg = EMAIL_CONFIRM_LINK_MSG;
	}
	elseif( isset($_GET['checkemail']) && 'newpass' == $_GET['checkemail'] )
	{
		$successmsg = NEW_PW_EMAIL_MSG;
	}
	elseif( isset($_GET['checkemail']) && 'registered' == $_GET['checkemail'] )
	{
		$successmsg = REG_COMPLETE_MSG;
	}
	
	if(($_POST['log'] && $errors) || ($_POST['log']=='' && $_REQUEST['testcookie']))
	{
		if($_REQUEST['pagetype'])
		{
			wp_redirect($_REQUEST['pagetype'].'&emsg=1');
		}else
		{
			wp_redirect(site_url().'?ptype=login&page1=sign_in&logemsg=1');
		}
		exit;
	}
}


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
<script>var rootfolderpath = '<?php echo bloginfo('template_directory');?>/images/';</script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/dhtmlgoodies_calendar.js"></script>
<link href="<?php bloginfo('template_directory'); ?>/library/css/dhtmlgoodies_calendar.css" rel="stylesheet" type="text/css" />
<!-- TinyMCE -->
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/library/js/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins :"advimage,advlink,emotions,iespell,",

		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,bullist,numlist,|,link,unlink,image",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_buttons4 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example word content CSS (should be your site CSS) this one removes paragraph margins
		content_css : "css/word.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
</script>
<!-- /TinyMCE -->
<div class="content content_full">
<div class="content_top content_top_full"></div>
	<div class="content_bg content_bg_full">
  <div class="entry">
    <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
      <div class="post-meta">
        <?php // templ_page_title_above(); //page title above action hook?>
        <h1><?php _e('Submit Article','templatic');?></h1>
        <?php templ_page_title_below(); //page title below action hook?>
      </div>
      <div id="post_<?php the_ID(); ?>">
        <div class="post-content">
         <div class="steps">
            	<ul>
                	<li class="current2" ><?php _e('1. Login or Register','templatic');?></li>
                    <li class="current"><?php _e('2. Article Detail','templatic');?></li>
                    <li><?php _e('3. Article Preview','templatic');?></li>
                    <li class="<?php if($_GET['ptype'] == 'add_step5'){?>last_current <?php }?> bgn"><?php _e(' 4. Success','templatic');?></li>
                </ul>
            </div>            
        
      <form name="submissiion_form" id="submissiion_form" action="<?php echo site_url('/?ptype=add_step3'); ?>" method="post" enctype="multipart/form-data" >  
       <input type="hidden" name="user_login_or_not" id="user_login_or_not" value="<?php echo $user_login_or_not;?>" />
            <input type="hidden" name="pid" value="<?php echo $_REQUEST['pid'];?>" />
            <input type="hidden" name="renew" value="<?php echo $_REQUEST['renew'];?>" />
      <div class="listing_info">
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
					$str = '<input name="'.$key.'" type="text" '.$val['extra'].' value="'.$fval.'"><img src="'.get_template_directory_uri().'/images/cal.gif" alt="Calendar"  onclick="displayCalendar(document.submissiion_form.'.$key.',\'yyyy-mm-dd\',this)" class="i_calendar" align="absmiddle" border="0" />';	
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
					$cat_args = array('name' => 'post_category', 'id' => 'post_category', 'selected' => $fval, 'class' => 'textfield', 'orderby' => 'name', 'echo' => '0', 'hierarchical' => 1, 'taxonomy'=>CUSTOM_CATEGORY_TYPE1);
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
				
				echo $val['outer_st'];
				if($val['is_require'])
				{
					echo '<label>';
					_e($val['label'],'templatic');
					echo '<small>*</small> </label>';
				}else
				{	
					echo '<label>';
					_e($val['label'],'templatic');
					echo '</label>';
				}
				echo $val['tag_st'].$str.$val['tag_end'].$val['outer_end'];
			}
			?>
              <?php if(function_exists('pt_get_captch') && $_REQUEST['pid']==''){
						  if(templ_is_show_capcha()){
						  ?>  
						   <div class="captcha">
								<h3> <?php _e(CAPTCHA_TITLE_TEXT,'templatic'); ?></h3>
								 <?php pt_get_captch(); ?>
							</div>
					  <?php }
					  }?> 
        </div> <!-- listing inf #end -->
        
           <div class="addlisting_row">
        <input name="" type="submit" value="Next Step" class="button" /> 
            </div>
         </form>
        </div>
        <!-- post content #end -->
        
      </div>
    </div>
  </div>
</div>
</div> <!-- content bg #end -->
    <div class="content_bottom content_bottom_full"></div>
</div> <!-- content end -->
<?php
include_once(TEMPL_ADD_POST_FOLDER . 'submition_validation.php');
?>
<?php get_footer(); ?>
