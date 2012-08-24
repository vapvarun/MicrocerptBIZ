<?php
$title = PAY_CANCELATION_TITLE;
$filecontent = get_option('post_payment_cancel_msg_content');
$filecontent = stripslashes($filecontent);
$post_link=get_permalink($_REQUEST['pid']);
$store_name = get_option('blogname');
$search_array = array('[#order_amt#]','[#bank_name#]','[#account_number#]','[#orderId#]','[#site_name#]','[#submited_information_link#]');
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
</div></div>
<?php templ_page_title_below(); //page title below action hook?>

<div id="content" class="content_inner" >
<?php echo $filecontent; ?> 
</div> <!-- content #end -->
<div id="sidebar">
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>