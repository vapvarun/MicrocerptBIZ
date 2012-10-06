<?php
/*
Plugin Name: TT Subscribe2 Frontend
Plugin URI: http://stiofan.themetailors.com/
Description: Adds shortcode [S2FE] for managing Subscribe2 subscriptions from the front end. Add non-logged-in mesage: [S2FE msg="Please login to manage your subscriptions."]
Version: 1.3
Author: Stiofan & Paolo
Author URI: http://themetailors.com/
*/

function TT_S2_product($types) {
   // $types[] = 'product'; // Add any custom post types here
    return $types;
}
add_filter('s2_post_types', 'TT_S2_product');



function TT_S2_product_category($taxonomies) {
   // $taxonomies[] = 'product_category'; // Add ay custom taxomnomies here
    return $taxonomies;
}
add_filter('s2_taxonomies', 'TT_S2_product_category');


############################################
########## GET ALL CATEGORIES ##############
############################################
function all_cats($exclude = false, $orderby = 'slug') {
global $subscribe2_options,$wpdb;
$subscribe2_options = get_option('subscribe2_options');
		$all_cats = array();
		$s2_taxonomies = array('category'); // ADD your custom taxonomies here like  array('category','product_category')
		$s2_taxonomies = apply_filters('s2_taxonomies', $s2_taxonomies);

		foreach( $s2_taxonomies as $taxonomy ) {
			if ( taxonomy_exists($taxonomy) ) {
				$all_cats = array_merge($all_cats, get_categories(array('hide_empty' => false, 'orderby' => $orderby, 'taxonomy' => $taxonomy)));
			}
		}

		if ( $exclude === true ) {
			// remove excluded categories from the returned object
			$excluded = explode(',', $subscribe2_options['exclude']);

			// need to use $id like this as this is a mixed array / object
			$id = 0;
			foreach ( $all_cats as $cat) {
				if ( in_array($cat->term_id, $excluded) ) {
					unset($all_cats[$id]);
				}
				$id++;
			}
		}

		return $all_cats;
	
}


############################################
########## CHECK FOR WPMU  #################
############################################
function get_usermeta_keyname_frontend($metaname) {
		global $wpdb, $s2_mu;

$s2_mu = false;
		if ( isset($wpmu_version) || strpos($wp_version, 'wordpress-mu') ) {
			$s2_mu = true;
		}
if ( function_exists('is_multisite') && is_multisite() ) {
			$s2_mu = true;
		}
		//if($s2_mu){echo '###';}
		// Is this WordPressMU or not?
		if ( $s2_mu === true ) {
			switch( $metaname ) {
				case 's2_subscribed':
				case 's2_cat':
				case 's2_format':
				case 's2_autosub':
				case 's2_authors':
					return $wpdb->prefix . $metaname;
					break;
			}
		}
		// Not MU or not a prefixed option name
		return $metaname;
	} // end get_usermeta_keyname()
	
############################################
########## BUILD CATEGOR CHECKBOXS #########
############################################	
function display_category_form($selected = array(), $override = 1) {
		global $wpdb;
$cat_return = '';
		if ( $override == 0 ) {
			$all_cats = all_cats(true);
		} else {
			$all_cats = all_cats(false);
		}
//print_r($all_cats);
		$half = (count($all_cats) / 2);
		$i = 0;
		$j = 0;
		$cat_return .= "<table width=\"100%\" cellspacing=\"2\" cellpadding=\"5\" class=\"editform\">\r\n";
		$cat_return .= "<tr><td align=\"left\" colspan=\"2\">\r\n";
		$cat_return .= "<label><input type=\"checkbox\" name=\"checkall\" value=\"checkall_cat\" /> " . __('Select / Unselect All', 'subscribe2') . "</label>\r\n";
		$cat_return .= "</td></tr>\r\n";
		$cat_return .= "<tr valign=\"top\"><td width=\"50%\" align=\"left\">\r\n";
		foreach ( $all_cats as $cat ) {
			if ( $i >= $half && 0 == $j ){
				$cat_return .= "</td><td width=\"50%\" align=\"left\">\r\n";
				$j++;
			}
			$catName = '';
			$parents = array_reverse( get_ancestors($cat->term_id, $cat->taxonomy) );
			if ( $parents ) {
				foreach ( $parents as $parent ) {
					$parent = get_term($parent, $cat->taxonomy);
					$catName .= $parent->name . ' &raquo; ';
				}
			}
			$catName .= $cat->name;

			if ( 0 == $j ) {
				$cat_return .= "<label><input class=\"checkall_cat\" type=\"checkbox\" name=\"category[]\" value=\"" . $cat->term_id . "\"";
				if ( in_array($cat->term_id, $selected) ) {
						$cat_return .= " checked=\"checked\"";
				}
				$cat_return .= " /> <abbr title=\"" . $cat->slug . "\">" . $catName . "</abbr></label><br />\r\n";
			} else {
				$cat_return .= "<label><input class=\"checkall_cat\" type=\"checkbox\" name=\"category[]\" value=\"" . $cat->term_id . "\"";
				if ( in_array($cat->term_id, $selected) ) {
							$cat_return .= " checked=\"checked\"";
				}
				$cat_return .= " /> <abbr title=\"" . $cat->slug . "\">" . $catName . "</abbr></label><br />\r\n";
			}
			$i++;
		}
		$cat_return .= "</td></tr>\r\n";
		$cat_return .= "</table>\r\n";
		
		return $cat_return;
	} // end display_category_form()
	
	
############################################
########## SHORT CODE FUNCTION #############
############################################
function tt_s2_sc($atts) {
	extract( shortcode_atts( array(
		'msg' => 'You must be logged in to change your settings.',
	), $atts ) );
	get_currentuserinfo();
	global $wpdb, $user_ID,$blog_id;
	$form_return ='';
	if($user_ID){	

$form_return .= "<script language=\"javascript\">
jQuery(document).ready(function(){jQuery('input[name=\"checkall\"]').click(function(){var checked_status=this.checked;jQuery('input[class=\"'+this.value+'\"]').each(function(){this.checked=checked_status})});jQuery('input[class^=\"checkall\"]').click(function(){var checked_status=true;jQuery('input[class=\"'+this.className+'\"]').each(function(){if((this.checked==true)&&(checked_status==true)){checked_status=true}else{checked_status=false}if(jQuery().jquery>='1.6'){jQuery('input[value=\"'+this.className+'\"]').prop('checked',checked_status)}else{jQuery('input[value=\"'+this.className+'\"]').attr('checked',checked_status)}})});var checked_status=true;jQuery('input[class^=\"checkall\"]').each(function(){if((this.checked==true)&&(checked_status==true)){checked_status=true}else{checked_status=false}if(jQuery().jquery>='1.6'){jQuery('input[value=\"'+this.className+'\"]').prop('checked',checked_status)}else{jQuery('input[value=\"'+this.className+'\"]').attr('checked',checked_status)}})});
</script>";

################################################# SUBSCRIBE SHIZZLE ##############################################################
$subscribe2_options = array();
$subscribe2_options = get_option('subscribe2_options');

// Is this WordPressMU or not?
		$s2_mu = false;
		if ( isset($wpmu_version) || strpos($wp_version, 'wordpress-mu') ) {
			$s2_mu = true;
		}
		if ( function_exists('is_multisite') && is_multisite() ) {
			$s2_mu = true;
		}





if(isset($_POST['s2_admin']) && 'user' == $_POST['s2_admin']){
if(!is_user_member_of_blog( $user_ID, $blog_id )){add_user_to_blog($sub_id, $user_ID, 'subscriber');}
$cats = $_POST['category'];
if ( isset($_POST['s2_format']) ) {
				update_user_meta($user_ID, get_usermeta_keyname_frontend('s2_format'), $_POST['s2_format']);
			} else {
				// value has not been set so use default
				update_user_meta($user_ID, get_usermeta_keyname_frontend('s2_format'), 'excerpt');
			}
			if ( isset($_POST['new_category']) ) {
				update_user_meta($user_ID, get_usermeta_keyname_frontend('s2_autosub'), $_POST['new_category']);
			} else {
				// value has not been passed so use Settings defaults
				if ( $subscribe2_options['show_autosub'] == 'yes' && $subscribe2_options['autosub_def'] == 'yes' ) {
					update_user_meta($user_ID, get_usermeta_keyname_frontend('s2_autosub'), 'yes');
				} else {
					update_user_meta($user_ID, get_usermeta_keyname_frontend('s2_autosub'), 'no');
				}
			}
				
				

			if ( empty($cats) || $cats == '-1' ) {
				$oldcats = explode(',', get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_subscribed'), true));
				if ( $oldcats ) {
					foreach ( $oldcats as $cat ) {
						delete_user_meta($user_ID, get_usermeta_keyname_frontend('s2_cat') . $cat);
					}
				}
				delete_user_meta($user_ID, get_usermeta_keyname_frontend('s2_subscribed'));
			} elseif ( $cats == 'digest' ) {
				$all_cats = all_cats(false, 'ID');
				foreach ( $all_cats as $cat ) {
					('' == $catids) ? $catids = "$cat->term_id" : $catids .= ",$cat->term_id";
					update_user_meta($user_ID, get_usermeta_keyname_frontend('s2_cat') . $cat->term_id, $cat->term_id);
				}
				update_user_meta($user_ID, get_usermeta_keyname_frontend('s2_subscribed'), $catids);
			} else {
				if ( !is_array($cats) ) {
					$cats = (array)$_POST['category'];
				}
				sort($cats);
				$old_cats = explode(',', get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_subscribed'), true));
				$remove = array_diff($old_cats, $cats);
				$new = array_diff($cats, $old_cats);
				if ( !empty($remove) ) {
					// remove subscription to these cat IDs
					foreach ( $remove as $id ) {
						delete_user_meta($user_ID, get_usermeta_keyname_frontend('s2_cat') . $id);
					}
				}
				if ( !empty($new) ) {
					// add subscription to these cat IDs
					foreach ( $new as $id ) {
						update_user_meta($user_ID, get_usermeta_keyname_frontend('s2_cat') . $id, $id);
					}
				}
				update_user_meta($user_ID, get_usermeta_keyname_frontend('s2_subscribed'), implode(',', $cats));
			}
}
####################################################################################################################################
########################################### FORM SHIZLE ###########################################################################

	

//print_r($subscribe2_options);
		// show our form
		
		//echo "<div class=\"wrap\">";
		//echo "<div id=\"icon-users\" class=\"icon32\"></div>";
		$form_return .= "<h2>" . __('Notification Settings', 'subscribe2') . "</h2>\r\n";
		if ( isset($_GET['email']) ) {
			$user = get_userdata($user_ID);
			$form_return .= "<span style=\"color: red;line-height: 300%;\">" . __('Editing Subscribe2 preferences for user', 'subscribe2') . ": " . $user->display_name . "</span>";
		}
		$form_return .= "<form method=\"post\" action=\"\">";
		$form_return .= "<p>";
		if ( function_exists('wp_nonce_field') ) {
			wp_nonce_field('subscribe2-user_subscribers' . $s2nonce);
		}
		$form_return .= "<input type=\"hidden\" name=\"s2_admin\" value=\"user\" />";
		if ( $subscribe2_options['email_freq'] == 'never' ) {
			$form_return .= __('Receive email as', 'subscribe2') . ": &nbsp;&nbsp;";
			$form_return .= "<label><input type=\"radio\" name=\"s2_format\" value=\"html\"" . checked(get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_format'), true), 'html', false) . " />";
			$form_return .= " " . __('HTML - Full', 'subscribe2') ."</label>&nbsp;&nbsp;";
			$form_return .= "<label><input type=\"radio\" name=\"s2_format\" value=\"html_excerpt\"" . checked(get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_format'), true), 'html_excerpt', false) . " />";
			$form_return .= " " .  __('HTML - Excerpt', 'subscribe2') . "</label>&nbsp;&nbsp;";
			$form_return .= "<label><input type=\"radio\" name=\"s2_format\" value=\"post\"" . checked(get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_format'), true), 'post', false) . " />";
			$form_return .= " " . __('Plain Text - Full', 'subscribe2') . "</label>&nbsp;&nbsp;";
			$form_return .= "<label><input type=\"radio\" name=\"s2_format\" value=\"excerpt\"" . checked(get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_format'), true), 'excerpt', false) . " />";
			$form_return .= " " . __('Plain Text - Excerpt', 'subscribe2') . "</label><br /><br />\r\n";

			if ( $subscribe2_options['show_autosub'] == 'yes' ) {
				$form_return .= __('Automatically subscribe me to newly created categories', 'subscribe2') . ': &nbsp;&nbsp;';
				$form_return .= "<label><input type=\"radio\" name=\"new_category\" value=\"yes\"" . checked(get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_autosub'), true), 'yes', false) . " />";
				$form_return .= " " . __('Yes', 'subscribe2') . "</label>&nbsp;&nbsp;";
				$form_return .= "<label><input type=\"radio\" name=\"new_category\" value=\"no\"" . checked(get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_autosub'), true), 'no', false) . " />";
				$form_return .= " " . __('No', 'subscribe2') . "</label>";
				$form_return .= "</p>";
			}
###################################################################
// subscribed categories
			if ( $s2_mu ) {
				global $blog_id;
				$subscribed = get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_subscribed'), true);
				// if we are subscribed to the current blog display an "unsubscribe" link
				if ( !empty($subscribed) ) {
					$unsubscribe_link = esc_url( add_query_arg('s2mu_unsubscribe_frontent', $blog_id) );
					//echo "<p><a href=\"". $unsubscribe_link ."\" class=\"button\">" . __('Unsubscribe me from this blog', 'subscribe2') . "</a></p>";
				} else {
					// else we show a "subscribe" link
					$subscribe_link = esc_url( add_query_arg('s2mu_subscribe', $blog_id) );
					//echo "<p><a href=\"". $subscribe_link ."\" class=\"button\">" . __('Subscribe to all categories', 'subscribe2') . "</a></p>";
				}
				$form_return .= "<h2>" . __('Subscribed Categories on', 'subscribe2') . " " . get_option('blogname') . " </h2>\r\n";
			} else {
				$form_return .= "<h2>" . __('Subscribed Categories', 'subscribe2') . "</h2>\r\n";
			}
			$form_return .= display_category_form(explode(',', get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_subscribed'), true)), $subscribe2_options['reg_override']);
		} else {
			// we're doing daily digests, so just show
			// subscribe / unnsubscribe
			$form_return .= __('Receive periodic summaries of new posts?', 'subscribe2') . ': &nbsp;&nbsp;';
			$form_return .= "<label>";
			$form_return .= "<input type=\"radio\" name=\"category\" value=\"digest\"";
			if ( get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_subscribed'), true) ) {
				$form_return .= " checked=\"checked\"";
			}
			$form_return .= " /> " . __('Yes', 'subscribe2') . "</label> <label><input type=\"radio\" name=\"category\" value=\"-1\" ";
			if ( !get_user_meta($user_ID, get_usermeta_keyname_frontend('s2_subscribed'), true) ) {
				$form_return .= " checked=\"checked\"";
			}
			$form_return .= " /> " . __('No', 'subscribe2');
			$form_return .= "</label></p>";
		}
#####################################################################
				
				
				$form_return .= "<p class=\"submit\"><input type=\"submit\" class=\"button-primary\" name=\"submit\" value=\"" . __("Update Preferences", 'subscribe2') . " &raquo;\" /></p>";
		$form_return .= "</form>\r\n";
		$form_return .= "<style>.s2_message{display:none;}</style>";
			
############################################################################################################

}else{
$form_return .= "<p>{$msg}</p>";	
}

return $form_return;
}

if(!is_admin()){
//tell wordpress to register the  shortcode
add_shortcode("S2FE", "tt_s2_sc");
}

?>