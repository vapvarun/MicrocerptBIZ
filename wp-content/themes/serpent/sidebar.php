<aside id="subscribe" class="widget"><div class="widget_subscribe"><h4 class="widget-title">Subscribe</h4><p class="delimiter">&nbsp;</p>
		<?php

if ( is_home() )
    {
	global $blog_id;
	//echo $blog_id;
	global $current_user;
	get_currentuserinfo();
  	//echo $current_user->ID;
	global $wpdb;
	$type = 2;
	$receiver_id = $blog_id;
	
$query = 'SELECT * FROM wp_subscriptions WHERE member_id = ' . $current_user->ID . ' AND type_id = ' . $type . ' AND receiver_id = ' . $receiver_id ;
$select_result = $wpdb->get_results($query);
if (!$select_result) 
			{
			$add_remove = "add";
			$type = 2;

	?>

       			<form method="post" action="<?php subscriptions ($add_remove, $type, $receiver_id) ?>"> 
			<input type="button" value="Subscribe Author" name="Subscribe Author"> 
			</form>
        
	<?php
				
			}
	else
			{
			$add_remove = "remove";
			$type = 2;

	?>

       			 <form method="post" action="<?php subscriptions ($add_remove, $type, $receiver_id) ?>"> 
			<input type="button" value="Unsubscribe Author" name="Unsubscribe Author"> 
			</form>
        
	<?php


			}


}

else
{
if (is_category())
{
global $blog_id;
	//echo $blog_id;
	global $current_user;
	get_currentuserinfo();
  	//echo $current_user->ID;
	global $wpdb;
	$type = 1;
	$receiver_id = $blog_id;
$category=get_query_var('cat');
$receiver_id = 	$category;
$query = 'SELECT * FROM wp_subscriptions WHERE member_id = ' . $current_user->ID . ' AND type_id = ' . $type . ' AND receiver_id = ' . $receiver_id ;
$select_result = $wpdb->get_results($query);
if (!$select_result) 
			{
			$add_remove = "add";
			$type = 1;

	?>

       			<form method="post" action="<?php subscriptions ($add_remove, $type, $receiver_id) ?>"> 
			<input type="button" value="Subscribe This CAT" name="Subscribe"> 
			</form>
        
	<?php
				
			}
	else
			{
			$add_remove = "remove";
			$type = 1;

	?>

       			 <form method="post" action="<?php subscriptions ($add_remove, $type, $receiver_id) ?>"> 
			<input type="button" value="Unsubscribe This CAT" name="Unsubscribe"> 
			</form>
        
	<?php


			}


}



}
    
function subscriptions ($add_remove, $type, $receiver_id) {
	global $wpdb; global $current_user;
	get_currentuserinfo();
	$current_userid = $current_user->ID;
	$redirto = wp_get_referer();
	
	switch ($add_remove) {
		case 'add':
			$insert_result = $wpdb->insert('wp_subscriptions',  array( 'member_id' => $current_user->ID, 'type_id' => $type, 'receiver_id' => $receiver_id ));
			break;
		case 'remove':
			$delete_sub_sql = 'DELETE FROM wp_subscriptions WHERE member_id = ' . $current_user->ID . ' AND type_id = ' . $type . ' AND receiver_id = ' . $receiver_id ;
			$mydelete_result = $wpdb->query($delete_sub_sql);
			break;		
		default:
			// TODO: add error handling here
			break;
	}

}
      
    


?>
</div><div class="clear"></div></aside>

<?php
	if(!is_author()){
		get_template_part('author-box');
	}
    if(dynamic_sidebar ( 'main' ) ){
        
    }
?>