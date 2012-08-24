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
add_action('admin_menu', 'micocerpting_add_page');
function micocerpting_add_page() {
	// $plugin_hook = 
	add_menu_page('Microcerpting', 'Microcerpting', 'manage_options', 'microcerpting_plugin', 'microcerpting_options_page');
// jc added 
//	add_action( "admin_head-$plugin_hook" , 'my_enqueue_func' );
}

function show_agreement_form () {
	?>
		<form action="admin.php?page=microcerpting_plugin" method="post">

		This is a section for writers.
		<br /><br />
		<input type="checkbox" name="agreed" value="yes" /> I Agree<br /><br />
		<input name="Submit" type="submit" value="<?php esc_attr_e('Start Microcerpting'); ?>" />
		</form></div>
	<?php
}

// display the admin options page
function microcerpting_options_page() {
	$current_user = wp_get_current_user();
	
	?>
	<div>
	<h1>Microcerpting</h1>
	<?php 
	if (isset ($_POST['agreed'])) {
		if ($_POST['agreed'] == 'yes') {
			?>
				You are now registered as a writer. Happy Microcerpting! <br /><br />
				Click on the Microcerpting menu button above to start.
			<?php
			add_user_meta( $current_user->ID, 'is_writer', '1', true );
		} else {
			?>
				You need to agree to this page to start Microcerpting.<br /><br />
			<?php
				show_agreement_form ();
		}
	} else {
		show_agreement_form ();
	}
}


?>
