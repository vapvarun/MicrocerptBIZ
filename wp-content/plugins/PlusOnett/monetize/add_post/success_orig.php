<?php 
if($_REQUEST['renew'])
{
	$title = RENEW_SUCCESS_TITLE;
}else
{
	$title = POSTED_SUCCESS_TITLE;
}

global $upload_folder_path;
$paymentmethod = get_post_meta($_REQUEST['pid'],'paymentmethod',true);
if($paymentmethod == 'prebanktransfer')
{
	//$filecontent = stripslashes(get_option('post_pre_bank_trasfer_msg_content'));
	if($filecontent==""){
	$filecontent =  __('<p>Thank you, your request received successfully.</p>
<p>To publish the property please transfer the amount of <u>[#payable_amt#] </u> at our bank with the following information :</p>
<p>Bank Name : [#bank_name#]</p>
<p>Account Number : [#account_number#]</p>
<br>
<p>Please include the ID as reference :#[#submition_Id#]</p>
<p><a href="[#submited_information_link#]" >View your submitted listing &raquo;</a>
<br>
<p>Thank you for visit at [#site_name#].</p>','templatic');
	}
}else
{
	$filecontent = stripslashes(get_option('post_added_success_msg_content'));
	if($filecontent==""){
		$filecontent=__('<p>Thank you, your information has been successfully received.</p><p><a href="[#submited_information_link#]" >View your submitted information »</a></p> <p>Thank you for visiting us at [#site_name#].</p>','templatic');
	}
}

$store_name = get_option('blogname');
$paid_amount = get_currency_sym().get_post_meta($_REQUEST['pid'],'paid_amount',true);
if($paymentmethod == 'prebanktransfer')
{
	$paymentInfo = get_option('payment_method_prebanktransfer');
	$payOpts = $paymentInfo['payOpts'];
	$bankInfo = $payOpts[0]['value'];
	$accountinfo = $payOpts[1]['value'];
}
$order_id = $_REQUEST['pid'];
if(get_post_type($order_id)=='event')
{
	$post_link = site_url('/?ptype=preview_event&alook=1&pid='.$_REQUEST['pid']);
}else
{
$post_link = site_url('/?ptype=preview&alook=1&pid='.$_REQUEST['pid']);	
}

$orderId = $_REQUEST['pid'];
$search_array = array('[#payable_amt#]','[#bank_name#]','[#account_number#]','[#submition_Id#]','[#site_name#]','[#submited_information_link#]');
$replace_array = array($paid_amount,$bankInfo,$accountinfo,$order_id,$store_name,$post_link);
$filecontent = str_replace($search_array,$replace_array,$filecontent);
?>
<?php get_header(); ?>

<div id="wrapper" class="clearfix">
<div id="inner_pages" class="clearfix" >
<?php templ_page_title_above(); //page title above action hook?>
<div class="entry">
  <div class="post-meta"> 
  <?php echo templ_page_title_filter($title); //page tilte filter?> 
  </div>
</div>
<?php templ_page_title_below(); //page title below action hook?>
<div id="content" class="content_inner" > <?php echo $filecontent;?> </div>
<!-- content #end -->
<div id="sidebar">
  <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
