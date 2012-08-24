<?php
/******************************************************************
=======  PLEASE DO NOT CHANGE BELOW CODE  =====
You can add in below code but don't remove original code.
This code to include add post,edit and preview from front end.
This file is included in functions.php of theme root at very last php coding line.

You can call add post,edit and preview page by the link 
Add New Post : http://mydomain.com/?ptype=submition  => echo site_url('/?ptype=submition');
Preview New Post : http://mydomain.com/?ptype=register => echo site_url('/?ptype=preview');
Payment New Post : http://mydomain.com/?ptype=register => echo site_url('/?ptype=paynow');
Payment Cancel Return : http://mydomain.com/?ptype=register => echo site_url('/?ptype=cancel_return');
Payment Payment Success : http://mydomain.com/?ptype=register => echo site_url('/?ptype=payment_success');
Payment Success : http://mydomain.com/?ptype=register => echo site_url('/?ptype=payment_success');
Paypal Return : http://mydomain.com/?ptype=register => echo site_url('/?ptype=return');
Paypal Success : http://mydomain.com/?ptype=register => echo site_url('/?ptype=success');
********************************************************************/
define('TEMPL_ADD_POST_FOLDER',TT_MODULES_FOLDER_PATH . "add_post/");
define('TEMPL_ADD_POST_URI',get_bloginfo('template_directory') . "/monetize/add_post/");

include_once(TEMPL_ADD_POST_FOLDER.'lang_add.php'); // language file

////////filter to retrive the page HTML from the url.
add_filter('templ_add_template_page_filter','templ_add_template_post_page'); //filter to add pages like addpost,preveiw,delete and etc....
include_once(TEMPL_ADD_POST_FOLDER.'functions_add.php'); // function file
?>