<?php
/*
Plugin Name: JC author profile for site
Description: Plugin to provide author profile for each athor site.
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
add_action('admin_menu', 'plugin_admin_add_page');
function plugin_admin_add_page() {
	// $plugin_hook = 
	add_menu_page('Author Profile', 'Author Profile', 'manage_options', 'jc_author_profile_plugin', 'plugin_options_page');
// jc added 
//	add_action( "admin_head-$plugin_hook" , 'my_enqueue_func' );
}

// display the admin options page
function plugin_options_page() {
	?>
	<div>
	<form action="options.php" method="post">
	<?php settings_fields('author_profile_options'); ?>	
	<?php 
		if ($_GET['settings-updated'] == 'true') {
			echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>Settings saved.</strong></p></div>';	
		}		
	?>	
	<?php do_settings_sections('jc_author_profile_plugin'); ?>
	<input name="Submit" type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
	</form></div>

	<?php
}

// add the admin settings and such
add_action('admin_init', 'plugin_admin_init');
function plugin_admin_init(){
	register_setting( 'author_profile_options', 'author_profile_options', 'plugin_options_validate' );
	//register_setting( 'author_profile_options', 'author_profile_options' );
	/* First, we register the settings. In my case, I’m going to store all my settings in one options field, as an array. This is usually the recommended way. 
	The first argument is a group, which needs to be the same as what you used in the settings_fields function call. 
	The second argument is the name of the options. If we were doing more than one, we’d have to call this over and over for each separate setting. 
	The final arguement is a function name that will validate your options. Basically perform checking on them, to make sure they make sense. */

	add_settings_section('jc_author_profile_plugin_main', 'Author Profile Settings', 'plugin_section_text', 'jc_author_profile_plugin');
	/* This creates a “section” of settings.
	The first argument is simply a unique id for the section.
	The second argument is the title or name of the section (to be output on the page).
	The third is a function callback to display the guts of the section itself.
	The fourth is a page name. This needs to match the text we gave to the do_settings_sections function call. */

	add_settings_field('author_profile_name', 'Name', 'plugin_setting_name', 'jc_author_profile_plugin', 'jc_author_profile_plugin_main');

	add_settings_field('author_profile_biography', 'Biography', 'plugin_setting_biography', 'jc_author_profile_plugin', 'jc_author_profile_plugin_main');
	
	add_settings_field('author_profile_events', 'Upcoming Events', 'plugin_setting_events', 'jc_author_profile_plugin', 'jc_author_profile_plugin_main');

	add_settings_field('author_profile_links', 'Links to your websites', 'plugin_setting_links', 'jc_author_profile_plugin', 'jc_author_profile_plugin_main');
	
	add_settings_field('author_profile_phone', 'Phone Number', 'plugin_setting_phone', 'jc_author_profile_plugin', 'jc_author_profile_plugin_main');
	
	add_settings_field('author_profile_email', 'Email', 'plugin_setting_email', 'jc_author_profile_plugin', 'jc_author_profile_plugin_main');
	
	add_settings_field('author_profile_microcerpt', 'Microcerpt', 'plugin_setting_microcerpt', 'jc_author_profile_plugin', 'jc_author_profile_plugin_main');

	add_settings_field('author_profile_image', 'Photograph', 'plugin_setting_image', 'jc_author_profile_plugin', 'jc_author_profile_plugin_main');
	
	/* The first argument is simply a unique id for the field.
	The second is a title for the field.
	The third is a function callback, to display the input box.
	The fourth is the page name that this is attached to (same as the do_settings_sections function call).
	The fifth is the id of the settings section that this goes into (same as the first argument to add_settings_section).*/
}

function plugin_section_text() {
	//echo '<p>Main description of this section here.</p>';
}

function plugin_setting_image() {
	$options = get_option('author_profile_options');
//	error_log ("function plugin_setting_string");
//	error_log (implode (',', $options));

	if ($options['author_profile_image']) {
		$img_path = $options['author_profile_image'];
		$min_width = 100; 
		$max_width = 215; 
		list($width) = getimagesize($img_path); 
		if ($width > $max_width) {
			/* sort this out another time..  
			 $image = new SimpleImage();
			 $image->load($img_path);
			 $image->resizeToWidth($max_width);
			 $image->save($img_path);
			*/
			echo 'The image you have uploaded is greater than the maximum width of ' . $max_width . 'pixels. Please upload an image between ' . $min_width . ' and ' . $max_width .' pixels wide.<br />';
		} else if ($width < $min_width ) {
			echo 'The image you have uploaded is less than the minimum width of ' . $min_width . 'pixels. Please upload an image between ' . $min_width . ' and ' . $max_width .' pixels wide.<br />';

		} else {
			echo "<img src='{$img_path}' /><br />";
		}
		// echo 'image width: ' .$width . '<br />';
	}
	echo "<input id='author_profile_image' type='text' size='36' name='author_profile_options[author_profile_image]' value='{$options['author_profile_image']}' />";
	echo '<input id="upload_image_button" type="button" value="Upload Image" />';
	echo '<br />Image must be JPG format and between 100 and 215 pixels wide.<br />';
}


function plugin_setting_links() {
	$options = get_option('author_profile_options');
	echo "<textarea id='author_profile_links' name='author_profile_options[author_profile_links]' rows='10' cols='55'>{$options['author_profile_links']}</textarea>";
}

function plugin_setting_phone() {
	$options = get_option('author_profile_options');
	echo "<input id='author_profile_phone' name='author_profile_options[author_profile_phone]' type='text' size='52' value='{$options['author_profile_phone']}'>";
}

function plugin_setting_email() {
	$options = get_option('author_profile_options');
	echo "<input id='author_profile_email' name='author_profile_options[author_profile_email]' type='text' size='52'  value='{$options['author_profile_email']}'>";
}

function plugin_setting_microcerpt() {
	$options = get_option('author_profile_options');
	echo "<textarea id='author_profile_microcerpt' name='author_profile_options[author_profile_microcerpt]' rows='10' cols='55'>{$options['author_profile_microcerpt']}</textarea>";
}

function plugin_setting_events() {
	$options = get_option('author_profile_options');
	echo "<textarea id='author_profile_events' name='author_profile_options[author_profile_events]' rows='10' cols='55'>{$options['author_profile_events']}</textarea>";
	echo '<p>Please note this field is limited to 500 characters</p>';
}

function plugin_setting_biography() {
	$options = get_option('author_profile_options');
//	error_log ("function plugin_setting_string");
//	error_log (implode (',', $options));

	echo "<textarea id='author_profile_biography' name='author_profile_options[author_profile_biography]' rows='10' cols='55'>{$options['author_profile_biography']}</textarea>";
	echo '<p>Please note this field is limited to 1000 characters</p>';
	// echo "<input id='author_profile_text_string' name='author_profile_options[author_profile_text_string]' size='40' type='text' value='{$options['author_profile_text_string']}' />";
}




function plugin_setting_name() {
	$options = get_option('author_profile_options');
//	error_log ("function plugin_setting_string");
//	error_log (implode (',', $options));
	echo "<input id='author_profile_name' name='author_profile_options[author_profile_name]' size='52' type='text' value='{$options['author_profile_name']}' />";
}

/*  It just gets the options then outputs the input HTML for it. Note the “name” is set to plugin_options[text_string]. This is not coincidence, the name *must* start with plugin_options in our case. Why? Because that is the second argument we passed to register_settings.

The settings pages use a whitelist system. Only valid options get read. Anything else gets tossed out, for security. Here, we’re using a php trick. PHP interprets an incoming GET or POST data of name[thing] as being an array called name with ‘thing’ as one of the elements in it. So, all our options are going to take the form of plugin_options[some_thing], so that we get that single array back, and the array name itself is whitelisted.
*/

// validate our options
function plugin_options_validate($options) {
	// error_log ("function plugin_options_validate input");
	// error_log (implode ('~', $options));	

	// error_log ("name: " . $options['author_profile_name']);
	
	//$options = get_option('author_profile_options');
	// $options['author_profile_biography'] = trim($input['author_profile_text_string']);
	//$options['author_profile_image'] = trim($input['author_profile_image']);

	if (strlen($options['author_profile_biography']) > 1000 ) {
		$options['author_profile_biography'] = mb_substrws($options['author_profile_biography'], 1000);
	}
	if (strlen($options['author_profile_events']) > 500 ) {
		$options['author_profile_events'] = mb_substrws($options['author_profile_events'], 500);
	}

	/* JC TODO.. lets sort out validation later.
	if(!preg_match('/^[a-z0-9]{32}$/i', $options['author_profile_text_string'])) {
		$options['author_profile_text_string'] = '';
	}
	*/
//	error_log ("function plugin_options_validate output");
//	error_log (implode (',', $options));	
	return $options;
}
/* Here I’m taking a liberty with the code. I’m going to say that our text_string has to be exactly 32 alphanumerics long. You can actually validate any way you want in here. The point of this function is simply to let you check all the incoming options and make sure they are valid in some way. Invalid options need to be fixed or blanked out. Finally, you return the whole input array again, which will get automatically saved to the database.

Take special note of the fact that I don’t return the original array. One of the drawbacks of this sort of approach is that somebody could, in theory, send bad options back to the plugin. These would then be in the $input array. So by validating my options and *only* my options, then any extra data they send back which might make it here gets blocked. So the validation function not only makes sure that my options have valid values, but that no other options get through. In short, $input is untrusted data, but the returned $newinput should be trusted data.

Update: What if you have multiple options screens? Or just want to only be able to edit a few of your options? One downside of the validation method I detail above is that your single plugin_options field gets completely replaced by $newinput. If you only want to have it change a few of your options, then there’s an easy technique to do that:

Basically I load the existing options, then update only the ones I want to change from the $input values. Then I return the whole thing. The options I don’t change thus remain the same.
*/

/////// setup the image upload



function my_admin_scripts() {
	wp_enqueue_script('media-upload');
	wp_enqueue_script('thickbox');
	wp_register_script('my-upload', '/wp-content/mu-plugins/my-script.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
}

function my_admin_styles() {
	wp_enqueue_style('thickbox');
}

if (isset($_GET['page']) && $_GET['page'] == 'jc_author_profile_plugin') {
	add_action('admin_print_scripts', 'my_admin_scripts');
	add_action('admin_print_styles', 'my_admin_styles');
}


?>
