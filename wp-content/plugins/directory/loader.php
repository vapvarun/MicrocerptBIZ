<?php
/*
Plugin Name: Directory
Plugin URI: http://premium.wpmudev.org/project/wordpress-directory
Description: Directory - Create full blown directory site.
Version: 2.1.1.7
Author: Ivan Shaovchev, Andrey Shipilov (Incsub), Arnold Bailey (Incsub)
Author URI: http://premium.wpmudev.org
WDP ID: 164
License: GNU General Public License (Version 2 - GPLv2)
*/

/*
Copyright 2012 Incsub, (http://incsub.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 2 - GPLv2) as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

// Define plugin version
define( 'DR_VERSION', '2.1.1.7' );
// define the plugin folder url
define( 'DR_PLUGIN_URL', plugin_dir_url(__FILE__) );
// define the plugin folder dir
define( 'DR_PLUGIN_DIR', plugin_dir_path(__FILE__) );
// The text domain for strings localization
define( 'DR_TEXT_DOMAIN', 'dr_text_domain' );
// The key for the options array
define( 'DR_OPTIONS_NAME', 'dr_options' );

if(class_exists('Classifieds_Core') ){
	trigger_error('Warning: This version of Directory plugin cannot be used on the same install as the Classifieds plugin. We are working to correct this problem. Please only activate one or the other for this version.<br /><br /><br />', E_USER_ERROR);
}

// include core files
include_once 'core/core.php';
include_once 'core/functions.php';
include_once 'core/template-tags.php';
include_once 'core/payments.php';
include_once 'core/ratings.php';

//If another version of CustomPress not loaded, load ours.
if(!class_exists('CustomPress_Core')) {
	include_once 'core/custompress/loader.php';
}

//Decide whether to load Admin, Buddypress or Standard version
add_action('plugins_loaded', 'dr_on_plugins_loaded');
function dr_on_plugins_loaded(){

	if(is_admin()){ 	//Are we admin
		include_once 'core/admin.php';
		include_once 'core/tutorial.php';
		require_once 'core/contextual_help.php';
	}

}

///////////////////////////////////////////////////////////////////////////
/* -------------------- Update Notifications Notice ---------------------*/
if ( !function_exists( 'wdp_un_check' ) ) {
	add_action( 'admin_notices', 'wdp_un_check', 5 );
	add_action( 'network_admin_notices', 'wdp_un_check', 5 );
	function wdp_un_check() {
		if ( !class_exists( 'WPMUDEV_Update_Notifications' ) && current_user_can( 'install_plugins' ) )
		echo '<div class="error fade"><p>' . __('Please install the latest version of <a href="http://premium.wpmudev.org/project/update-notifications/" title="Download Now &raquo;">our free Update Notifications plugin</a> which helps you stay up-to-date with the most stable, secure versions of WPMU DEV themes and plugins. <a href="http://premium.wpmudev.org/wpmu-dev/update-notifications-plugin-information/">More information &raquo;</a>', 'wpmudev') . '</p></div>';
	}
}
/* --------------------------------------------------------------------- */

// Notification for Transition from old version 1.x to new version 2.x of the plugin
if ( get_option( 'dp_options' ) && ! isset( $_POST['install_dir2'] ) ) {

	add_action( 'admin_notices', 'directory_check', 5 );
	add_action( 'network_admin_notices', 'directory_check', 5 );

	function directory_check() {
		if ( current_user_can( 'install_plugins' ) ) {
			?>
			<div id="message_directory" class="updated">
				<p><?php _e( "<b>The Directory plugin</b><br /><b>Notice:</b> We found that you've used an older version of the plugin. To use the new version of the plugin, we recommend you  to make <b>Backup of your Database</b> and then click on <b>'Transition to new version'.</b> All your data will be migrated to the new version.<br /> If you were using the included Directory theme, you will need to <b>reactivate</b> it after the transition is complete.", DR_TEXT_DOMAIN ); ?></p>
				<form method="post">
					<input type="submit" name="install_dir2" value="Transition to new version" />
				</form>
				<br />
			</div>
			<?php
		}
	}
}
