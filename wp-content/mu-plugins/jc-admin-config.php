<?php
/*
Plugin Name: JC custom changes
Description: Plugin to customise admin pages menu bar, admin bar for everyone except superadmin. Also sets up site registration wigit for users with no site. <strong>Note: This plugin requires a minimum of WordPress 3.1. If you are running a version less than that, please upgrade your WordPress install now.</strong>
Author: Jon Caine
Version: 1.0
License: MIT License - http://www.opensource.org/licenses/mit-license.php

Copyright (c) 2011 Jon Caine - jc@joncaine.com

*/

/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}

/* WordPress 3.1 introduces the admin bar in both the admin area and the public-facing site. For subscribers, there's also now
	a pesky link to the Dashboard and a redundant link to the user's profile in the My Account menu. Let's remove the Dashboard
	link and only show the Profile link on the site. */

// mote last param is for priortiy.. high number means runs later.. ie after theme setup
add_action('admin_menu', 'my_remove_menu_pages', 100);
// add_action('admin_bar_menu', 'edit_admin_bar_links', 100);

// for debug use 
//show_menu_array ();

function my_remove_menu_pages() {
	global $current_user;
	if (!current_user_can('superadmin')) {
		remove_menu_page('link-manager.php');
		remove_menu_page('tools.php');	
		remove_menu_page('upload.php');	
		remove_menu_page('themes.php');	
		remove_menu_page('users.php');	
		remove_menu_page('options-general.php');	
		remove_menu_page('edit-comments.php');	
		remove_menu_page('edit.php?post_type=page');
	
		// Article menu (from plusone template)
		remove_menu_page('edit.php?post_type=listing');	
		// PlusOne menu (plusonehp template)
		remove_menu_page("templatic_wp_admin_menu");
		// had to edit /Users/jon/Sites/microcerpt.local/wp-content/themes/PlusOne/admin/admin_menu.php
		// Reports menu (reports plugin)
		// had to edit /wp-content/plugins/wp-reportpost/main.php	
		// wp-reportpost/new-reports
		remove_menu_page("wp-reportpost/new-reports.php");	
		// remove mysites from dashboard sub-menu
		remove_submenu_page( 'index.php', 'my-sites.php' );
		remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=category' );
		
		remove_submenu_page( 'edit.php', 'post-new.php');
		remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag');

		add_menu_page( 'Category Request', 'Category Request', 'manage_options', 'request-category.php');
	}
}



function edit_admin_bar_links() {
	global $current_user, $wp_admin_bar;
	if (!current_user_can('superadmin') && is_admin_bar_showing() ) {
		$wp_admin_bar->remove_menu('comments');	
		$wp_admin_bar->remove_menu('appearance');	
		$wp_admin_bar->remove_menu('new-content');	
		$wp_admin_bar->remove_menu('my-blogs');	

		// only show add post menu to those who have a blog
		$user_id = get_current_user_id();
		$blogs = get_blogs_of_user($user_id);
		if (count($blogs)  > 0 ) {
			$wp_admin_bar->add_menu( array(
				'id' => 'new_post',
				'title' => __( 'Add Post'),
				'href' => admin_url( 'post-new.php' )
				) );			
		} else {
			
		}
		
		/* example sub menu
			$wp_admin_bar->add_menu( array(
				'id' => 'custom_menu',
				'title' => __( 'Posts'),
				'href' => false ) );
			$wp_admin_bar->add_menu( array(
				'id' => 'new_post',
				'parent' => 'custom_menu',
				'title' => __( 'Add'),
				'href' => admin_url( 'post-new.php' )
				) );
		*/
	}
}

//////// now lets setup the widget for registration ///////

// Create the function to output the contents of our Dashboard Widget

function example_dashboard_widget_function() {
	global $current_user;
	// Display whatever it is you want to show
	echo '<div style="color: green; font-size: 400%"><a href="/wp-signup.php">Welcome '. $current_user->user_login .', click here to setup your site</a></div>';
} 


function create_blog () {
	global $current_user;
	$current_site = get_current_site();
	get_currentuserinfo();
	$user_id = $current_user->ID;
	$path = '/' . get_user_meta($user_id, 'blogname', 1);
	$title = $path . ' some title';

	if ( ! $newblog_id = wpmu_create_blog($current_site->domain, $path, $title, $user_id, $meta = array('public' => 1)) ) {
		new WP_Error('insert_blog', __('Could not create site.'));
		echo '<div style="color: red; font-size: 400%">';
		print_r ($newblog_id);
		echo '</div>';
	} else {
		echo '<div style="color: green; font-size: 400%">site created! new id is: ' . $newblog_id . '</div>';
	}
} 



//  remove some dashboard widgets from global dashboard

/*
add_action('wp_user_dashboard_setup', 'hhs_remove_user_dashboard_widgets' );
function hhs_remove_user_dashboard_widgets() {
	global $wp_meta_boxes; 
	unset($wp_meta_boxes['dashboard-user']['normal']['core']['dashboard_primary']); //WordPress Blog
	unset($wp_meta_boxes['dashboard-user']['normal']['core']['dashboard_secondary']); //Other WordPress News
}
*/

// Create the function use in the action hook

function setup_site_add_widget() {
	global $current_user, $wp_meta_boxes;
	$user_id = get_current_user_id();
	$blogs = get_blogs_of_user($user_id);
	
	if (count($blogs) == 0) {

		// wp_add_dashboard_widget('example_dashboard_widget', 'Create Website', 'example_dashboard_widget_function');	
		wp_add_dashboard_widget('example_dashboard_widget', 'Create Website', 'create_blog');	
	
			// now lets get our widget to the top of the page /////// Globalize the metaboxes array, this holds all the widgets for wp-admin

		// global the $wp_meta_boxes variable (this will allow us to alter the array)
		// Then we make a backup of your widget
		$my_widget = $wp_meta_boxes['dashboard-user']['normal']['core']['example_dashboard_widget'];
		//We then unset that part of the array
		unset($wp_meta_boxes['dashboard-user']['normal']['core']['example_dashboard_widget']);
		//Now we just add your widget back in to the high priority widgets
		$wp_meta_boxes['dashboard-user']['normal']['high']['example_dashboard_widget'] = $my_widget;

		// mow lets re-order the high priority widgets

		// Get the high dashboard widgets array 
		// (which has our new widget already but at the end)

		$high_dashboard = $wp_meta_boxes['dashboard-user']['normal']['high'];

		// Backup and delete our new dashbaord widget from the end of the array

		$example_widget_backup = array('example_dashboard_widget' => $high_dashboard['example_dashboard_widget']);
		unset($high_dashboard['example_dashboard_widget']);

		// Merge the two arrays together so our widget is at the beginning

		$sorted_dashboard = array_merge($example_widget_backup, $high_dashboard);

		// Save the sorted array back into the original metaboxes 

		$wp_meta_boxes['dashboard-user']['normal']['high'] = $sorted_dashboard;
	
		// lets add some styling 
		//	add_action('wp_head', 'your_function');
		/*
		function widget_style () {
				<style type="text/css" media="screen">

				</style>
		}
		*/
	}
} 

// Hook into the 'wp_dashboard_setup' action to register our other functions
// add_action('wp_dashboard_setup', 'example_add_dashbtyoard_widgets' );

// dont need the below now as setting up site through: /create_blog.php
// add_action('wp_user_dashboard_setup', 'setup_site_add_widget' );

/* Last, but not least, let's redirect folks to their profile when they login or if they try to access the Dashboard via direct URL */
/*
		if (is_multisite() && empty($blogs)) {
			return;
		} else if ($parent_file == 'index.php') {
			error_log ("parent file is index.php");
			if (headers_sent()) {
				error_log ("headers_sent");
				echo '<meta http-equiv="refresh" content="0;url='.admin_url('profile.php').'">';
				echo '<script type="text/javascript">document.location.href="'.admin_url('profile.php').'"</script>';
			} else {
				error_log ("refirecting");
				wp_redirect(admin_url('profile.php'));
				exit();
			}
		}
		
*/

// add custom stylesheet

function my_admin_head() {
        echo '<link rel="stylesheet" type="text/css" href="' .plugins_url('admin_custom/wp-admin.css', __FILE__). '">';
		error_log ("my_admin_head loaded from: " . plugins_url('admin_custom/wp-admin.css', __FILE__));
}

add_action('admin_head', 'my_admin_head');

/////////// sort out dashboard widgets //////////////

// Remove and Create Custom Dashboard Widgets
function wp_admin_dashboard_customise() {
	// Globalise and get access to the widgets and current user info
	global $wp_meta_boxes, $current_user;

	if (!current_user_can('superadmin')) {
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);

		// Remove widget: Plugins, WordPress Blog and Other WordPress News

	
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
		
		
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_drafts']);
		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
		
		// Create a call back function for our new widget
		wp_add_dashboard_widget('custom_help_widget', 'Help and Support', 'wp_admin_dashboard_help_widget');
	}
}

add_action('wp_dashboard_setup', 'wp_admin_dashboard_customise');

	//Our new dashboard widget
function wp_admin_dashboard_help_widget() {
	require_once ( ABSPATH . '/author_dashboard/help.php');
}

//change admin footer text
function remove_footer_admin () {
	echo "";
}

add_filter('admin_footer_text', 'remove_footer_admin'); 

function customize_meta_boxes() {
  /* Removes meta boxes from Posts */
  /*
  remove_meta_box('postcustom','post','normal');
  remove_meta_box('trackbacksdiv','post','normal');
  remove_meta_box('commentstatusdiv','post','normal');
  remove_meta_box('commentsdiv','post','normal');
  remove_meta_box('postexcerpt','post','normal');
  */ 
  // for post edit/add page
	if (!current_user_can('superadmin')) {	
	  remove_meta_box('tagsdiv-post_tag','post','normal');
	  remove_meta_box('formatdiv','post','normal');
	  remove_meta_box('postexcerpt','post','normal');
	  remove_meta_box('trackbacksdiv','post','normal');
	  remove_meta_box('postcustom','post','normal');
	  remove_meta_box('commentstatusdiv','post','normal');
	  remove_meta_box('slugdiv','post','normal');
	  remove_meta_box('authordiv','post','normal');
	}

  /* Removes meta boxes from pages */
  /*
  remove_meta_box('postcustom','page','normal');
  remove_meta_box('trackbacksdiv','page','normal');
  remove_meta_box('commentstatusdiv','page','normal');
  remove_meta_box('commentsdiv','page','normal'); 
  */
}

add_action('admin_init','customize_meta_boxes');


// Take out tags column on all posts page

function custom_columns( $defaults ) {
	unset($defaults['tags']);
	return $defaults;
}
add_filter( "manage_posts_columns", "custom_columns" );

// remove dashboard menu

function hide_the_entire_adminmenu() {
	global $current_user;
	get_currentuserinfo();
	if (!current_user_can('superadmin')) {
		echo '
		<style type="text/css">
		#adminmenuback {display: none;} /*Hides the admin menu*/
		#adminmenuwrap {display: none;} /*Hides the admin menu*/
		#wpcontent {margin-left: 10px;} /*Makes the content area stretch across the entire page*/
		</style>
		';
	}
}

add_action('admin_head', 'hide_the_entire_adminmenu');


?>
