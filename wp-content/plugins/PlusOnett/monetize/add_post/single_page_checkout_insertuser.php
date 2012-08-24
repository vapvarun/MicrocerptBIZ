<?php
$_SESSION['userinset_error'] = '';
if($_POST && !$userInfo)
{
	if (  $_SESSION['submition_info']['user_email'] == '' )
	{
		$error =  __('Email for Publisher Information is Empty. Please enter Email, your all informations will sent to your Email.','templatic');	
		$_SESSION['userinset_error'] = $error;
		wp_redirect(get_option('siteurl').'?ptype=submition&backandedit=1&usererror=1');
		exit;
	}
	
	require( 'wp-load.php' );
	require(ABSPATH.'wp-includes/registration.php');
	
	global $wpdb;
	$errors = new WP_Error();
	
	$user_email = $_SESSION['submition_info']['user_email'];
	$user_login = $user_email;	
	$user_login = sanitize_user( $user_login );
	$user_email = apply_filters( 'user_registration_email', $user_email );
	
	// Check the username
	if ( $user_login == '' )
		$errors->add('empty_username', __('ERROR: Please enter a username.','templatic'));
	elseif ( !validate_username( $user_login ) ) {
		$errors->add('invalid_username', __('<strong>ERROR</strong>: This username is invalid.  Please enter a valid username.','templatic'));
		$user_login = '';
	} elseif ( username_exists( $user_login ) )
		$errors->add('username_exists', __('<strong>ERROR</strong>: '.$user_email.' This username is already registered, please choose another one.','templatic'));

	// Check the e-mail address
	if ($user_email == '') {
		$errors->add('empty_email', __('<strong>ERROR</strong>: Please type your e-mail address.','templatic'));
	} elseif ( !is_email( $user_email ) ) {
		$errors->add('invalid_email', __('<strong>ERROR</strong>: The email address isn&#8217;t correct.','templatic'));
		$user_email = '';
	} elseif ( email_exists( $user_email ) )
		$errors->add('email_exists', __('<strong>ERROR</strong>: '.$user_email.' This email is already registered, please choose another one.','templatic'));

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
		//$_SESSION['userinset_error'] = $errors;
		wp_redirect(get_option('siteurl').'?ptype=submition&backandedit=1&usererror=1');
		exit;
	}
		
	$user_pass = wp_generate_password(12,false);
	$user_id = wp_create_user( $user_login, $user_pass, $user_email );
	
	if ( !$user_id ) {
		$_SESSION['userinset_error'] = sprintf(__('<strong>ERROR</strong>: Couldn&#8217;t register you... please contact the <a href="mailto:%s">webmaster</a> !','templatic'), get_option('admin_email'));
		wp_redirect(get_option('siteurl').'?ptype=submition&backandedit=1&usererror=1');
		exit;
	}
	
	$user_fname = $_SESSION['submition_info']['user_fname'];
	$userName = $_SESSION['submition_info']['user_fname'];
	$user_nicename = strtolower(str_replace(array("'",'"',"?",".","!","@","#","$","%","^","&","*","(",")","-","+","+"," "),array('','','','-','','-','-','','','','','','','','','','-','-',''),$user_login));
	$user_nicename = get_user_nice_name($user_fname,''); //generate nice name
		update_usermeta($user_id, 'first_name', $user_fname); // User Address Information Here
	
		$updateUsersql = "update $wpdb->users set user_nicename=\"$user_nicename\" , display_name=\"$user_fname\"  where ID=\"$user_id\"";
		$wpdb->query($updateUsersql);
	
	if ( $user_id) 
	{
		///////REGISTRATION EMAIL START//////
		$fromEmail = get_site_emailId();
		$fromEmailName = get_site_emailName();
		$store_name = get_option('blogname');
		$client_message = __('[SUBJECT-STR]Registration Email[SUBJECT-END]<p>Dear [#$user_name#],</p>
		<p>Your login information:</p>
		<p>Username: [#$user_login#]</p>
		<p>Password: [#$user_password#]</p>
		<p>You can login from [#$store_login_url#] or the URL is : [#$store_login_url_link#].</p>
		<p>We hope you enjoy. Thanks!</p>
		<p>[#$store_name#]</p>','templatic');
		$filecontent_arr1 = explode('[SUBJECT-STR]',$client_message);
		$filecontent_arr2 = explode('[SUBJECT-END]',$filecontent_arr1[1]);
		$subject = $filecontent_arr2[0];
		if($subject == '')
		{
			$subject = "Registration Email";
		}
		$client_message = $filecontent_arr2[1];
		$store_login = '<a href="'.get_option('siteurl').'/?ptype=login">Click Login</a>';
		$store_login_link = get_option('siteurl').'/?ptype=login';
		/////////////customer email//////////////
		$search_array = array('[#$user_name#]','[#$user_login#]','[#$user_password#]','[#$store_name#]','[#$store_login_url#]','[#$store_login_url_link#]');
		$replace_array = array($_POST['user_fname'],$user_login,$user_pass,$store_name,$store_login,$store_login_link);
		$client_message = str_replace($search_array,$replace_array,$client_message);	
		templ_sendEmail($fromEmail,$fromEmailName,$user_email,$userName,$subject,$client_message,$extra='');///To clidne email
		//////REGISTRATION EMAIL END////////
	}
	$current_user_id = $user_id;
}
?>