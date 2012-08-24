<?php 
/*
Plugin Name: Superfish Dropdown Menu
Plugin URI: http://shailan.com/wordpress/plugins/superfish-dropdown-menu/
Description: This is a navigation widget that can be used for pages & for categories. It is based on Superfish jQuery Plugin. For more useful plugins be sure to visit <a href="http://shailan.com">shailan.com</a>.
Tags: jquery, dropdown, menu, superfish, animated, css, navigation
Version: 1.1
Author: Matt Say
Author URI: http://shailan.com
*/

global $registered_skins;

class shailan_SFdropdown {

	function shailan_SFdropdown(){
		global $registered_skins;
	
		if(!is_admin()){
			// Header styles
			add_action( 'wp_head', array('shailan_SFdropdown', 'header') );
		
			// Scripts
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'superfish', shailan_SFdropdown::get_plugin_directory() . '/js/superfish.js', array('jquery') );
			wp_enqueue_script( 'supersubs', shailan_SFdropdown::get_plugin_directory() . '/js/supersubs.js', array('jquery') );
			wp_enqueue_script( 'hoverIntent',shailan_SFdropdown::get_plugin_directory() . '/js/hoverIntent.js', array('jquery') );
			wp_enqueue_script( 'bgiframe', shailan_SFdropdown::get_plugin_directory() . '/js/jquery.bgiframe.min.js', array('jquery') );
		}
		add_action( 'wp_footer', array('shailan_SFdropdown', 'footer') );
		
		$registered_skins = array();
		
	}

	function header(){
		echo "\n\t<link rel=\"stylesheet\" type=\"text/css\" href=\"".shailan_SFdropdown::get_plugin_directory()."/css/superfish.css\" media=\"screen\">";
	}
	
	function footer(){
		//echo "\n\t";
	}
	
	function options(){}

	function get_plugin_directory(){
		return WP_PLUGIN_URL . '/superfish-dropdown-menu';	
	}

}; //class shailan_Superfish_Dropdown

// Include the widget
include_once('superfish-widget.php');

// Initialize the plugin.
$superfish = new shailan_SFdropdown();

// Register the widget
add_action('widgets_init', create_function('', 'return register_widget("shailan_SFWidget");'));


?>