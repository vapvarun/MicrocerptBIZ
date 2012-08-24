<?php
 


require_once('wp-load.php');

if ( !is_user_logged_in()) {
	echo "UH OH! You aren't allowed to view this page. Please go back from whence you came.";
} else {
	if ($_GET['addcategory']) {
		subscriptions ('add', 'category', $_GET['addcategory']);
	}
	
	// FOR add and remove all options: loop through the GET variables looking for categories to add or remove
	foreach ($_GET as $key => $value) {
		if (preg_match("/addcategory_/i", $key)) {
		    subscriptions ('add', 'category', $value);
		} 
	}

	if ($_GET['addauthor']) {
		subscriptions ('add', 'author', $_GET['addauthor']);
	}	
	if ($_GET['delcategory']) {
		subscriptions ('remove', 'category', $_GET['delcategory']);
	}	
	if ($_GET['delauthor']) {
		subscriptions ('remove', 'author', $_GET['delauthor']);
	}	
	
	
	
	if ($_GET['test']) {
		if ($_GET['test'] == 'true' ) {
			return true;
		} elseif ($_GET['test'] == 'false' ) {
			return false;
		}
	}
}


// example: subscriptions ('add', 'category', 'catid');

function subscriptions ($add_remove, $type, $receiver_id) {
	global $wpdb;
	$current_user = wp_get_current_user();
	$redirto = wp_get_referer();
	switch ($type) {
		case 'category':
			$type_id = 1;
			break;
		case 'author':
			$type_id = 2;
			break;
		default:
			break;
	}

	if ($_GET['debug']) {
		

			
		// cats check
		$categories_sql = 'SELECT * FROM wp_69_term_relationships INNER JOIN wp_terms ON wp_69_term_relationships.term_taxonomy_id = wp_terms.term_id WHERE object_id = 60';
		if ($myresult = $wpdb->query($categories_sql)) {
			$categories = $wpdb->last_result;
		}
		echo "\n\ncats: \n\n";
		foreach ($categories as $key => $value_array) {
			echo "\ncat: $key\n";
			echo $value_array->name . '-> ' . $value_array->term_id;
		}
		// end checks
		
		
		// $sql = 'SELECT * FROM subscriptions_author where member_id=' . $current_user->ID . ' and receiver_id='.$receiver_id;
		$sql = 'SELECT * FROM subscriptions_author where member_id=' . $current_user->ID;
		
		$subscribed_categories_sql = 'SELECT * FROM thetugis_wrdp4.subscriptions_category 
		INNER JOIN thetugis_wrdp4.wp_terms ON thetugis_wrdp4.subscriptions_category.receiver_id = thetugis_wrdp4.wp_terms.term_id';
		
		if ($myresult = $wpdb->query($sql)) {
			// print_r ($wpdb->last_result);
		} else {
			// TODO: what happens if query fails?
		}
		//print_r ($wpdb);
	}

	switch ($add_remove) {
		case 'add':
			if ( ! $wpdb->insert('wp_subscriptions',  array( 'member_id' => $current_user->ID, 'type_id' => $type_id, 'receiver_id' => $receiver_id ) ) ) {
				// TODO: fix error handling
				echo '<div style="color: red; font-size: 100%">';
				echo 'Sorry could not add your subscription. Please wait a bit and try again, and if it still does not work <a href="/contact/">contact us</a> and let us know.';
				echo '</div>';
			} else {
				if (! $_GET['debug'])
					echo '<script type="text/javascript">window.location.replace("'.$redirto.'");</script>';
			}
			break;
		case 'remove':
			$delete_sub_sql = 'DELETE FROM wp_subscriptions WHERE member_id = ' . $current_user->ID . ' AND type_id = ' . $type_id . ' AND receiver_id = ' . $receiver_id ;
		
			if ($myresult = $wpdb->query($delete_sub_sql)) {
				if (! $_GET['debug'])
					echo '<script type="text/javascript">window.location.replace("'.$redirto.'");</script>';
			} else {
				// TODO: what happens if query fails?
			}
			break;		
		default:
			// TODO: add error handling here
			break;
	}

}


?>