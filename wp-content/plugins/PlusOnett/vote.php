<?php
$file = dirname(__FILE__);
$file = substr($file, 0, stripos($file, "wp-content") );
 $_SESSION['post_user_insert']='';
require( $file . "/wp-load.php");
$currentvotes = get_post_meta($_POST['post'], 'votes', true);

if($_POST['already'] == 'voted'){
	$currentvotes = $currentvotes - 1;	
	$voters = get_post_meta($_POST['post'], 'thevoters', true);
	$voters = explode(",", $voters);
	$cnt=0;
	session_start();	
	foreach($voters as $voter) {
		
		if($voter == $user_ID) {
			$alreadyVoted = true;
		}
		else{
			if($cnt == 0){
				$vote_user_number=$voter;
			}
			else{
				$vote_user_number.=','.$voter;	
				$_SESSION['post_user_insert']=$vote_user_number;
			}			
		}		
		$cnt=$cnt+1;		
	}

	update_post_meta($_POST['post'], 'thevoters', $_SESSION['post_user_insert']);	
	update_post_meta($_POST['post'], 'votes', $currentvotes);	 
	session_destroy();
}
else{
	$currentvotes = $currentvotes + 1;
	$voters = get_post_meta($_POST['post'], 'thevoters', true);

	if(!$voters) $voters = $_POST['user']; else $voters = $voters.",".$_POST['user'];
	 
	update_post_meta($_POST['post'], 'votes', $currentvotes);
	update_post_meta($_POST['post'], 'thevoters', $voters);
	
	?>
<?php }
echo $currentvotes;
?>
	