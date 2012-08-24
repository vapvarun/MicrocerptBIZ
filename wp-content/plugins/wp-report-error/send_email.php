<?php
	global $wpdb,$options;
	require_once( dirname(__FILE__) . '../../../../wp-load.php');
	
	$email_message=get_option('WRPErrors_message');
	$to = get_option('WRPErrors_email');
	if($to=="")
	{
		$to=get_bloginfo('admin_email');
	}
	if(isset($_POST['email']))
	{
	$from=$_POST['email'];
	}
	else
	{
		$from=get_bloginfo('admin_email');
	}
	$subject=get_option('WRPErrors_subject');
	$info =addslashes($_REQUEST['info']); 
	$email =$_REQUEST['email']; 
	$pageurl = $_REQUEST['pageurl'];
	$message="<h2>".$subject."</h2>";
	$message.="<p>Page URL = ".$pageurl.'</p>';
	$message.="<p>Message = ".$info.'</p>';
	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From:'.$email;
	$content=array();
	$content['from']=$from;
	$content['email']=$email;
	$content['message']=$message;
/*	if (!daddydesign_checkSpam ($content)) {
	mail($to, $subject, $message,$headers);
	echo $email_message;	
	}
	else
	{
		echo 'ERROR Sending Email. Please try again.';		
	}
*/
	if (mail($to, $subject, $message,$headers)) {
	echo $email_message;	
	}
	else
	{
		echo 'ERROR Sending Email. Please try again.';		
	}
?>