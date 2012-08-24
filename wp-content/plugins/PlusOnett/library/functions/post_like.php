<?php
//////////POST LIKE THIS TABLE CODING///////////////
global $wpdb;
define('TEMPL_POST_LIKE_TABLE',$wpdb->prefix.'templ_like');
$templ_like_sql = 'CREATE TABLE  IF NOT EXISTS '.TEMPL_POST_LIKE_TABLE.' (
`like_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`ip_address` VARCHAR( 255 ) NOT NULL ,
`pid` INT( 11 ) NOT NULL ,
`addedon` DATETIME NOT NULL
) ENGINE = MYISAM';
$wpdb->query($templ_like_sql);

function templ_add_post_like($pid)
{
	
	global $wpdb;
	$ip_address = getenv("REMOTE_ADDR");
	$datenow = date('Y-m-d h:i:s');
	$currentvotes = get_post_meta($pid, 'votes', true);
	$currentvotes = $currentvotes + 1;
	update_post_meta($pid, 'votes', $currentvotes);
	if(!templ_check_post_like($pid))
	{
	
	
		$insert_sql = 'insert into '.TEMPL_POST_LIKE_TABLE.' (ip_address,pid,addedon) values ("'.$ip_address.'","'.$pid.'","'.$datenow.'")';
	}
	$wpdb->query($insert_sql);
	return 
	'<div class="vote" id="addlike">
					<span class="span_currentvote">'.$currentvotes.'</span>
					<span class="b_like_disable">Hype</span>
				</div>';
	
}
function templ_check_post_like($pid)
{
	global $wpdb;
	$ip_address = getenv("REMOTE_ADDR");
	
	return $wpdb->get_var("select addedon from ".TEMPL_POST_LIKE_TABLE." where pid=\"$pid\" and ip_address like \"$ip_address\"");
}
//echo templ_get_post_like_count('206');
function templ_get_post_like_count($pid)
{
	global $wpdb;
	return $wpdb->get_var("select count(like_id) from ".TEMPL_POST_LIKE_TABLE." where pid=\"$pid\"");
}

if($_REQUEST['ptype']=='postlike')
{
	if($_REQUEST['pid'])
	{
		
		echo templ_add_post_like($_REQUEST['pid']);
	}
	exit;
}
?>