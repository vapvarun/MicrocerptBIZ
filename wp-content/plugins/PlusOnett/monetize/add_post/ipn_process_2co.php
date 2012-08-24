<?php
global $Cart,$General;
foreach ($_POST as $field=>$value)
{
	$ipnData["$field"] = $value;
}

$postid    = intval($ipnData['x_invoice_num']);
$pnref      = $ipnData['x_trans_id'];
$amount     = doubleval($ipnData['x_amount']);
$result     = intval($ipnData['x_response_code']);
$respmsg    = $ipnData['x_response_reason_text'];
$customer_email    = $ipnData['x_email'];
$customer_name = $ipnData['x_first_name'];

$fromEmail = get_site_emailId();
$fromEmailName = get_site_emailName();
$subject = "Acknowledge for Place Listing ID #$postid payment";

if ($result == '1')
{
	// Valid IPN transaction.
	$post_default_status = get_property_default_status();
	if($post_default_status=='')
	{
		$post_default_status = 'publish';
	}
	set_property_status($postid,$post_default_status);
	$productinfosql = "select ID,post_title,guid,post_author from $wpdb->posts where ID = $postid";
	$productinfo = $wpdb->get_results($productinfosql);
	foreach($productinfo as $productinfoObj)
	{
		$post_title = '<a href="'.$productinfoObj->guid.'">'.$productinfoObj->post_title.'</a>'; 
		$aid = $productinfoObj->post_author;
		$userInfo = get_author_info($aid);
		$to_name = $userInfo->user_nicename;
		$to_email = $userInfo->user_email;
	}
	$fromEmail = get_site_emailId();
	$fromEmailName = get_site_emailName();
	
	$email_admin_content = get_option('post_payment_success_admin_email_content');
	$email_admin_subject = get_option('post_payment_success_admin_email_subject');
	
	if($email_admin_subject=="" && $email_admin_content=="")
	{
		$email_admin_subject = __("Payment received successfully",'templatic');
		$email_admin_content=__('<p>Dear [#to_name#],</p><p>[#transaction_details#]</p><br><p>We hope you enjoy . Thanks!</p>
		<p>[#site_name#]</p>','templatic');
	}
	$amount_form = number_format($amount,2);
	$transaction_details  = sprintf(__('<p>Dear %s,</p>
			<p>payment for ID #$postid confirmation.<br></p>
			<p><b>You may find detail below:</b></p>
			<p>----</p>
			<p>ID : %s</p>
			<p>Title : %s</p>
			<p>User Name : %s</p>
			<p>User Email : %s</p>
			<p>Paid Amount :       %s</p>
			<p>Transaction ID :       %s</p>
			<p>Result Code : %s</p>
			<p>Response Message : %s</p>
			<p>----</p><br><br>
			<p>Thank you.</p>
			','templatic'),$fromEmailName,$postid,$post_title,$to_name,$to_email,$amount_form,$pnref,$result,$respmsg);
		$store_name = get_option('blogname');
		$search_array = array('[#to_name#]','[#transaction_details#]','[#site_name#]');
		$replace_array = array($fromEmailName,$transaction_details,$store_name);
		$email_admin_content = str_replace($search_array,$replace_array,$email_admin_content);
		templ_sendEmail($to_email,$to_name,$fromEmail,$fromEmailName,$email_admin_subject,$email_admin_content,$extra='');///To admin email
		
		$email_client_content = get_option('post_payment_success_client_email_content');
		$email_client_subject = get_option('post_payment_success_client_email_subject');
		
		if($email_admin_subject=="" && $email_admin_content=="")
		{
			$email_client_subject = "Acknowledgment for your Payment ";
			$email_client_content=__('<p>Dear [#to_name#],</p>
			<p>[#transaction_details#]</p>
			<br>
			<p>We hope you enjoy. Thanks!</p>
			<p>[#site_name#]</p>','templatic');
		}
		$store_name = get_option('blogname');
		$search_array = array('[#to_name#]','[#transaction_details#]','[#site_name#]');
		$replace_array = array($to_name,$transaction_details,$store_name);
		$email_client_content = str_replace($search_array,$replace_array,$email_client_content);	
		templ_sendEmail($fromEmail,$fromEmailName,$to_email,$to_name,$email_client_subject,$email_client_content,$extra='');///To admin email
	return true;
}
else if ($result != '1')
{
	$eamount = number_format($amount,2);
	$message = sprintf(__('<p>Dear %s,</p>
			<p>payment for the Listing ID #$postid incompleted.<br>
			because of $respmsg</p>
			<p><b>You may find detail below:</b></p>
			<p>----</p>
			<p>ID : %s</p>
			<p>Title : %s</p>
			<p>User Name : %s</p>
			<p>User Email : %s</p>
			<p>Paid Amount :   %s</p>
			<p>Transaction ID : %s</p>
			<p>Result Code : %s</p>
			<p>Response Message : %s</p>
			<p>----</p><br><br>
			<p>Thank you.</p>
			','templatic'),$fromEmailName,$postid,$post_title,$to_name,$to_email,$eamount,$pnref,$result,$respmsg);
	mail($fromEmail,$subject,$message,$headerarr);
	return false;
}
?>