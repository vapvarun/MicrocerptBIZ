<?php
/*
Plugin Name: JC author site link creator
Description: Plugin to provide authors code for linking to their microcerpt author site.
Author: Jon Caine
Version: 1.0
License: MIT License - http://www.opensource.org/licenses/mit-license.php

Copyright (c) 2011 Jon Caine - jc@joncaine.com

*/

/* Disallow direct access to the plugin file */
if (basename($_SERVER['PHP_SELF']) == basename (__FILE__)) {
	die('Sorry, but you cannot access this page directly.');
}

// add the admin options page and menu item
add_action('admin_menu', 'button_creator_add_page');
function button_creator_add_page() {
	// $plugin_hook = 
	add_menu_page('Button Creator', 'Button Creator', 'manage_options', 'button_creator_plugin', 'button_creator_options_page');
// jc added 
//	add_action( "admin_head-$plugin_hook" , 'my_enqueue_func' );
}

// display the admin options page
function button_creator_options_page() {
	$button_code = '<a href="'.get_home_url().'" target="_blank"><img src="http://www.microcerpt.com/wp-content/mu-plugins/jc_authorsite_linkbutton_creator/microcerpt_logo.jpg" style="border: 0" /></a>';
	$button_long_code = '<a href="'.get_home_url().'" target="_blank"><img src="http://www.microcerpt.com/wp-content/mu-plugins/jc_authorsite_linkbutton_creator/microcerpt_logo_long.jpg" style="border: 0" /></a>';
	?>
	<div>
	<h1>Button Creator</h1>
	<form>
	<table><tr><td><b>Example Button</b></td><td><b>Copy code from one of the below to create a link to your ...microcerpt website</b></td></tr>
	<tr><td valign="top">
	<?php echo $button_long_code; ?>
	</td><td>
	<textarea rows='5' cols='55'><?php echo $button_long_code; ?></textarea>
	</td></tr>
	<tr><td valign="top">
	<?php echo $button_code; ?>
	</td><td>
	<textarea rows='5' cols='55'><?php echo $button_code; ?></textarea>
	</td></tr>
	</table>
	</form></div>

	<?php
}


?>
