<?php
/*
Plugin Name: Admin bar
Description: Plugin to customise admin menu bar
Author: Jon Caine
Version: 1.0
License: MIT License - http://www.opensource.org/licenses/mit-license.php

Copyright (c) 2011 Jon Caine - jc@joncaine.com

*/

/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}


function get_page_edit_by_name ($name, $admin_url) {
	// TODO: perhaps we will need blog ID here!? 
	$mypage = get_page_by_title( $name );
	// TODO: stop people editing permalinks / slug / page title
	// http://www.microcerpt.com/author-master/wp-admin/post.php?post=13&action=edit
	return $admin_url . 'post.php?post=' . $mypage->ID .'&action=edit';
}

add_action('admin_bar_menu', 'custom_admin_bar', 100);
add_action( 'bp_adminbar_menus', 'custom_admin_bar', 100 );

function custom_admin_bar () {
	global $wp_admin_bar, $wp_query,$wpdb, $post;
	$current_user = wp_get_current_user();
//	$blog_id = get_current_blog_id(); // wrong we dont want the current blog we want the logged in users blog
	$blog_id = $current_user->primary_blog;
	$blog_details = get_blog_details($blog_id);
	$blog_url = $blog_details->siteurl . '/';
//	$blog_url = get_blogaddress_by_id($current_user->primary_blog);
	$admin_url = $blog_url . 'wp-admin/';
	// TODO: could use just get_blog_details for $blog_url 
	
	/**
		REMOVE UNWANTED MENU ITEMS
	**/
	
	// $wp_admin_bar->remove_menu('view-site');	
	// $wp_admin_bar->remove_menu('dashboard');	

/**
		prepare data for menus later
**/
	// for single post/microcerpt page - build categories list for this post and marking them suscribed or not
	if (is_single()) {
			$subscribe_all_url = 'subscribe.php?';	// used in SUBSCRIPTION MENU

			// build cat list
			if ($blog_id == 1) {
				$categories_sql = 'SELECT * FROM wp_terms LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
				  LEFT JOIN wp_term_relationships ON wp_term_taxonomy.term_taxonomy_id = wp_term_relationships.term_taxonomy_id
				  WHERE wp_term_relationships.object_id = '. $post->ID;
			} else {
				$categories_sql = 'SELECT * FROM wp_terms LEFT JOIN wp_term_taxonomy ON wp_terms.term_id = wp_term_taxonomy.term_id
				  LEFT JOIN wp_'.$blog_id.'_term_relationships ON wp_term_taxonomy.term_taxonomy_id = wp_'.$blog_id.'_term_relationships.term_taxonomy_id
				  WHERE wp_'.$blog_id.'_term_relationships.object_id = '. $post->ID;
			}
			if ($myresult = $wpdb->query($categories_sql)) {
				$categories_object = $wpdb->last_result;
				
				// build cats array including subscription info
				$categories = array();
				foreach ($categories_object as $catkey => $cat_value_array) {
					if (subscription_check ($current_user->ID, 1, $cat_value_array->term_id)) {
						$subscribed = 1;
						// there is at least one cat that is subscribed so we will need unsubscribe menu
						if (! isset ($unsub_menu_needed['categories'])) {
							if (!$unsub_menu_needed)
								$unsub_menu_needed = array ();
							$unsub_menu_needed['categories'] = 1;
						}
					} else {
						$subscribed = 0;
						
						//  there is at least one cat that is not subscribed so we will need subscribe all menu item
						if (!$sub_all_menu_item_needed)
							$sub_all_menu_item_needed = 1;
					}
					array_push ($categories, array('id' => $cat_value_array->term_id, 'name' => $cat_value_array->name, 'subscribed' => $subscribed));
				}
			} else {
				// FIXME: $wpdb->query - need to better handle both query error and query success returning 0 rows.
				error_log ("NOT adding categories ERROR: " .  $wpdb->last_error . ' - SQL: ' . $wpdb->func_call);
			}		
	} elseif (is_category()) {
		//$category = get_the_category();
	$category = get_the_category();
	$category = array_reverse($category);
             
	}
		$wp_admin_bar->add_menu( array(
		'id' => 'my-journal',
		'title' => __( 'My Journal'),
		'href' => $blog_url . 'wp-admin') );



	/**
			SUBSCRIPTION MENU
	**/

	if ( ((is_home() or is_single()) and $blog_id != 1) or is_category()) {

		// subscribe top level menu
		$wp_admin_bar->add_menu( array(
			'id' => 'subscribe_menu',
			'title' => __( 'Subscribe'),
			'href' => false ) );
	}
	
	/**
			SUBSCRIPTION MENU - AUTHOR/BLOG ITEMS
	**/
	// author menu item for author sites (not master site): home pages and single microcerpt/post pages
	if ( (is_home() or is_single()) and $blog_id != 1) {
		
		$author_info = get_option('author_profile_options');
		$author_name = $author_info['author_profile_name'];
		$author_id = get_the_author_id() - 218;
		$vapvarun = $_SERVER["REQUEST_URI"];
		$query_url = explode("/",  $vapvarun ); 
		$author_uri = $query_url[1];
		$vap_blog_id = get_id_from_blogname( $author_uri );
	
		
		// lets check to see if the user has subscribed to this author/blog
		//if (subscription_check ($current_user->ID)) {
			// user has subscribed to this author/blog so we will need the unsubscription menu
			//if (! isset ($unsub_menu_needed['author'])) {
			//	if (!$unsub_menu_needed)
				//	$unsub_menu_needed = array ();
				//$unsub_menu_needed['author'] = 1;
			//}
		//} else {
			// user has not subscribed to this author/blog so we will need the subscription menu item
			$wp_admin_bar->add_menu( array(
				'id' => 'subscribe_author',
				'parent' => 'subscribe_menu',
				'title' => __( $author_name ),
				'href' => 'subscribe.php?addauthor=' . $vap_blog_id ) );

			$subscribe_all_url .= 'addauthor=' . $blog_id . '&'; // for single mcerpt/post page use only.
			
			//  the author is not subscribed so we will need the subscribe all menu item
			if (!$sub_all_menu_item_needed)
				$sub_all_menu_item_needed = 1;
		//}
	}

	/**
			SUBSCRIPTION MENU - CATEGORY ITEMS
	**/

	if (is_single()) {
		// run through categories for this microcerpt/post and dusplay subscribe menu for those that user is not subscribed to
		foreach ($categories as $catkey => $catvalue) {
			if ($catvalue['subscribed'] == 0) {
				$wp_admin_bar->add_menu( array(
					'id' => 'category_' . $catvalue['id'],
					'parent' => 'subscribe_menu',
					'title' => __($catvalue['name']),
					'href' => 'subscribe.php?addcategory=' . $catvalue['id'] ) );
			
				// add to subscribe all url
				$subscribe_all_url .= 'addcategory_'. $catvalue['id'] . '=' . $catvalue['id'] . '&';
			}
		}

		// subscribe_all menu items
		if ($sub_all_menu_item_needed) {
			$wp_admin_bar->add_menu( array(
				'id' => 'subscribe_all',
				'parent' => 'subscribe_menu',
				'title' => __( 'all' ),
				'href' => $subscribe_all_url ) );
		}
		
	} else if (is_category()) {
		
		// check if user has subscribed to this category then offer subscribe / unsubscribe as appropriate.
		if (subscription_check ($current_user->ID, 1, $category[0]->cat_ID)) {
			// user has subscribed to this cat so we will need the unsubscription menu
			if (! isset ($unsub_menu_needed['cat'])) {
				if (!$unsub_menu_needed)
					$unsub_menu_needed = array ();
				$unsub_menu_needed['cat'] = 1;
			}
		} else {
			// user has not subscribed to this cat so we will need the subscription menu item
		$current_category = $wp_query->query_vars['category_name'];					
$idObj = get_category_by_slug($current_category); 
  $id = $idObj->term_id;
			$wp_admin_bar->add_menu( array(
				'id' => 'subscribe_category',
				'parent' => 'subscribe_menu',
				'title' => __($idObj->name),
				'href' => 'subscribe.php?addcategory=' . $idObj->term_id ) );
		}
		
	}

	/**
			SUBSCRIPTION MENU - UNSUBSCRIBE SUB-MENU
	**/
	if ($unsub_menu_needed) {
		$wp_admin_bar->add_menu( array(
			'id' => 'unsubscribe_menu',
			'parent' => 'subscribe_menu',
			'title' => __('Unsubscribe'),
			'href' => false ) );
		
		// for author home page and author site single mcerpt/post pages
		if ($unsub_menu_needed['author']) {
			$wp_admin_bar->add_menu( array(
				'id' => 'unsubscribe_author',
				'parent' => 'unsubscribe_menu',
				'title' => __($author_name),
				'href' => 'subscribe.php?delauthor=' . $author_id ) );
		}
		// for category page
		if ($unsub_menu_needed['cat']) {
			$wp_admin_bar->add_menu( array(
				'id' => 'unsubscribe_category',
				'parent' => 'unsubscribe_menu',
				'title' => __($category[0]->cat_name),
				'href' => 'subscribe.php?addcategory=' . $category[0]->cat_ID ) );

		}
		// for single page (author sites only)
		if ($unsub_menu_needed['categories']){
			foreach ($categories as $catkey => $catvalue) {
				if ($catvalue['subscribed'] == 1) {
					$wp_admin_bar->add_menu( array(
						'id' => 'category_' . $catvalue['id'],
						'parent' => 'unsubscribe_menu',
						'title' => __($catvalue['name']),
						'href' => 'subscribe.php?delcategory=' . $catvalue['id'] ) );
				}
			}
		}
	}

	/**
			MY CATEGORIES MENU
	**/	
	$wp_admin_bar->add_menu( array(
		'id' => 'my_categories',
		'title' => __( 'My Categories'),
		'href' => false ) );

	$mycategories = get_subscriptions ($current_user->ID, 1);

	if (!$mycategories) {
		// error_log ('menu mycategories --> get_subscriptions ($member_id = '.$current_user->ID.', $type_id = 1) returning 0 so there are no cat subs!');
		$wp_admin_bar->add_menu( array(
			'id' => 'no_categories',
			'parent' => 'my_categories',
			'title' => __( 'No Categories'),
			'href' => false ) );
	} else {
		foreach ($mycategories as $key => $value_array) {
			$wp_admin_bar->add_menu( array(
				'id' => 'my_category_' . $value_array->receiver_id,
				'parent' => 'my_categories',
				'title' => __( $value_array->name ),
				'href' => 'http://www.microcerpt.com/blog/category/' . $value_array->slug . '/' ) );
		}

		// unsubscribe menu items

		$wp_admin_bar->add_menu( array(
			'id' => 'cat_unsubscribe',
			'parent' => 'my_categories',
			'title' => __( 'Unsubscribe'),
			'href' => false ) );

		foreach ($mycategories as $key => $value_array) {
			$wp_admin_bar->add_menu( array(
				'id' => 'my_category_unsub_' . $value_array->receiver_id,
				'parent' => 'cat_unsubscribe',
				'title' => __( $value_array->name ),
				'href' => 'subscribe.php?delcategory=' . $value_array->receiver_id ) );
		}	
	}

	/**
			MY AUTHORS MENU
	**/	

	$wp_admin_bar->add_menu( array(
		'id' => 'my_authors',
		'title' => __( 'My Authors'),
		'href' => false ) );

	$author_subs = get_author_subs_with_names ($current_user->ID);

	if (!$author_subs) {
		// error_log ('menu my_authors --> get_author_subs_with_names ($member_id = '.$current_user->ID.') returning 0 so there are no author subs!');
		$wp_admin_bar->add_menu( array(
			'id' => 'no_authors',
			'parent' => 'my_authors',
			'title' => __( 'No Authors'),
			'href' => false ) );
	} else {
		foreach ($author_subs as $key => $value_array) {
			$wp_admin_bar->add_menu( array(
				'id' => 'my_author_' . $value_array['id'],
				'parent' => 'my_authors',
				'title' => __( $value_array['name'] ),
				'href' => get_blogaddress_by_id($value_array['id']) ) );
		}

		// unsubscribe menu items

		$wp_admin_bar->add_menu( array(
			'id' => 'authors_unsubscribe',
			'parent' => 'my_authors',
			'title' => __( 'Unsubscribe'),
			'href' => false ) );

		foreach ($author_subs as $key => $value_array) {
			$wp_admin_bar->add_menu( array(
				'id' => 'my_author_unsub_' . $value_array['id'],
				'parent' => 'authors_unsubscribe',
				'title' => __( $value_array['name'] ),
				'href' => 'subscribe.php?delauthor=' . $value_array['id'] ) );
		}
	}

	/**
			replace edit profile menu item
	**/	
	
		


	/**
	▪	Single microcerpt page:
		▪	category names
		▪	author name
		▪	all
	▪	Author home page:
		▪	author name
	▪	category page:
		▪	category name	
	**/ 
}


?>