<?php
session_start();
ob_start();
?>
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
		$filecontent=__('<p>Thank you, your information has been successfully received.</p><p><a href="[#submited_information_link#]" >View your submitted information &raquo;</a></p> <p>Thank you for visiting us at [#site_name#].</p>','templatic');
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

$post_link = site_url('/?ptype=add_step4&alook=1&pid='.$_REQUEST['pid']);	
//$post_link = site_url('/?post_type=listing&pid='.$_REQUEST['pid'].'&preview=true');	
$orderId = $_REQUEST['pid'];
$search_array = array('[#payable_amt#]','[#bank_name#]','[#account_number#]','[#submition_Id#]','[#site_name#]','[#submited_information_link#]');
$replace_array = array($paid_amount,$bankInfo,$accountinfo,$order_id,$store_name,$post_link);
$filecontent = str_replace($search_array,$replace_array,$filecontent);
?>
<?php get_header(); ?>
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
         <div class="steps steps_final">
            	<ul>
                	<li class="current2"><?php _e('1. Login or Register','templatic');?></li>
                    <li class="current2"><?php _e('2. Article Detail','templatic');?></li>
                    <li class="current2"><?php _e('3. Article Preview','templatic');?></li>
                   <li class="last_current bgn "><?php _e(' 4. Success','templatic');?></li>
                </ul>
            </div>
            
         
         <h1> <?php _e('Article Posted Successfully','templatic');?></h1>

			 <?php echo $filecontent;?> 
                
         	
         
        </div>
         <!-- post content #end -->
	   
	   
        
      </div>
    </div>
  </div>
</div>
</div> <!-- content bg #end -->
    <div class="content_bottom content_bottom_full"></div>
</div> <!-- content end -->

<?php get_footer(); ?>
