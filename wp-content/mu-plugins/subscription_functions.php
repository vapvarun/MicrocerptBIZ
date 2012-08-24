<?php
/*
Plugin Name: Subscription functions
Description:  Used by admin bar, dashboard widgets etc.
Author: Jon Caine
Version: 1.0
License: MIT License - http://www.opensource.org/licenses/mit-license.php

Copyright (c) 2011 Jon Caine - jc@joncaine.com

*/

/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}

// $debug = true;


function get_author_subs_with_names ($member_id) {
	global $debug;

	if ($debug)
		error_log ('get_author_subs_with_names ($member_id = '.$member_id.')');

	if (isset ($GLOBALS['subscriptions']['author_subs_with_names']) )  {
		if ($debug)
			error_log ('get_author_subs_with_names --> I have cache so I am returning it.');
		return $GLOBALS['subscriptions']['author_subs_with_names'];
	} else {
		if ($debug)
			error_log ('get_author_subs_with_names --> No cache so building author subs with names.');

		$author_subs_obj = get_subscriptions ($member_id, 2);
		if (! $author_subs_obj) {
			if ($debug)
				error_log ('get_author_subs_with_names --> get_subscriptions ($member_id = '.$member_id.', 2) returning 0 so there are no subs. returning 0');
			return 0;
		} else {
			if ($debug)
				error_log ('get_author_subs_with_names --> get_subscriptions ($member_id = '.$member_id.', 2) returned subs so adding names etc and returning them');

			$GLOBALS['subscriptions']['author_subs_with_names'] = array();
			// lets add the author names 
			foreach ($author_subs_obj as $key => $author_sub_value_array) {
				switch_to_blog($author_sub_value_array->receiver_id);
				$author_info = get_option('author_profile_options');
				$author_name = $author_info['author_profile_name'];
				array_push ($GLOBALS['subscriptions']['author_subs_with_names'], array('id' => $author_sub_value_array->receiver_id, 'name' => $author_name));
				restore_current_blog();
			}
		}
		return $GLOBALS['subscriptions']['author_subs_with_names'];
	}
}


function get_subscriptions ($member_id, $type_id) {
	global $wpdb;
	global $debug;
	
	if ($debug)
		error_log ('get_subscriptions ($member_id = '.$member_id.', $type_id  = '.$type_id.')');
	
	if (! isset ($GLOBALS['subscriptions']) ) {
		$GLOBALS['subscriptions'] = array ();
	}
	
		
	if (! isset ($GLOBALS['subscriptions'][$type_id]) ) {
		if ($debug)
			error_log ('get_subscriptions --> no cache so getting subs.');
		
		if ($type_id == 1) {
			$subscriptions_sql = 'SELECT * FROM wp_subscriptions INNER JOIN wp_terms ON wp_subscriptions.receiver_id = wp_terms.term_id  WHERE type_id = 1 AND member_id = '.$member_id;
		} else {
			$subscriptions_sql = 'SELECT * FROM wp_subscriptions WHERE type_id = ' . $type_id .' AND member_id = '.$member_id;
		}
		
		if ($myresult = $wpdb->query($subscriptions_sql)) {
			if ($debug)
				error_log ('get_subscriptions --> query success, returning subs.');

			$GLOBALS['subscriptions'][$type_id] = $wpdb->last_result;
			return $GLOBALS['subscriptions'][$type_id];
		} else {
			// FIXME: $wpdb->query - need to better handle both query error and query success returning 0 rows.
			if ($debug)
				error_log ('get_subscriptions --> QUERY FAIL, prob due to no subs. returning 0. $wpdb error: ' .  $wpdb->last_error . ' - SQL was: ' . $wpdb->func_call );
			return 0;
		}
	} else {
		if ($debug)
			error_log ('get_subscriptions --> found cache so returning subs.');
		return $GLOBALS['subscriptions'][$type_id];
	}
	
	
	/** for debugging
	foreach ($GLOBALS['subscriptions'][$type_id] as $key => $value_array) {
		error_log ("sub values for array $key");
		foreach ($value_array as $key_var => $value_var) {
			error_log ("   $key_var => $value_var");
		}
	}
	**/
	/** further debugging stuff
	echo 'sql: ' . $subscriptions_sql . "\n";
	echo 'subs for type_id ' . $type_id .":\n";
	print_r ($GLOBALS['subscriptions'][$type_id]);
	print_r ($wpdb);
	**/
}


// check to see if a specific cat/author (receiver) has been subscribed to
function subscription_check ($member_id, $type_id, $receiver_id) {
	global $debug;
	
	if ($debug)
		error_log ('subscription_check ($member_id = '.$member_id.', $type_id  = '.$type_id.', $receiver_id = '.$receiver_id.')');
	$subscriptions = get_subscriptions ($member_id, $type_id);
	if (!$subscriptions) {
		if ($debug)
			error_log ('subscription_check --> get_subscriptions ($member_id = '.$member_id.', $type_id = '.$type_id.') returning 0 so I am returning 1 as there are no subs!');
		return 0;
	} else {
		foreach ($subscriptions as $key => $value_array) {
			if ($value_array->receiver_id == $receiver_id) {
				if ($debug)
					error_log ('subscription_check --> returning 1 as I have found a sub for $receiver_id = '.$receiver_id);
				return 1;
				break;
			}
		}
		if ($debug)
			error_log ('subscription_check --> returning 0 as I have looked through all the subs and there is not entry for $receiver_id = '.$receiver_id);
		return 0;
	}
}


?>