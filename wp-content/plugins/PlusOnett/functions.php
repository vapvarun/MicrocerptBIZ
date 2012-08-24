<?php
load_theme_textdomain('templatic');
//load_textdomain( 'templatic', TEMPLATEPATH.'/language/en_US.mo' );
/*** Theme setup ***/
define('TT_ADMIN_FOLDER_NAME','admin');
define('TT_ADMIN_FOLDER_PATH',TEMPLATEPATH.'/'.TT_ADMIN_FOLDER_NAME.'/'); //admin folder path

if(file_exists(TT_ADMIN_FOLDER_PATH . 'constants.php')){
include_once(TT_ADMIN_FOLDER_PATH.'constants.php');  //ALL CONSTANTS FILE INTEGRATOR
}

if(file_exists(TT_FUNCTIONS_FOLDER_PATH . 'custom_filters.php')){
include_once (TT_FUNCTIONS_FOLDER_PATH . 'custom_filters.php'); // manage theme filters in the file
}

if(file_exists(TT_FUNCTIONS_FOLDER_PATH . 'widgets.php')){
include_once (TT_FUNCTIONS_FOLDER_PATH . 'widgets.php'); // theme widgets in the file
}

if(file_exists(TT_FUNCTIONS_FOLDER_PATH . 'post_like.php')){
include_once (TT_FUNCTIONS_FOLDER_PATH . 'post_like.php'); // theme like in the file
}

// Theme admin functions
include_once (TT_FUNCTIONS_FOLDER_PATH . 'custom_functions.php');

include_once(TT_ADMIN_FOLDER_PATH.'admin_main.php');  //ALL ADMIN FILE INTEGRATOR

if(file_exists(TT_WIDGET_FOLDER_PATH . 'widgets_main.php')){
include_once (TT_WIDGET_FOLDER_PATH . 'widgets_main.php'); // Theme admin WIDGET functions
}

if(file_exists(TT_MODULES_FOLDER_PATH . 'modules_main.php')){
include_once (TT_MODULES_FOLDER_PATH . 'modules_main.php'); // Theme moduels include file
}

include_once(TT_ADMIN_FOLDER_PATH.'auto_update_framework.php');  //FRAMEWORK AUTO UPDATE LINK
if(file_exists(TT_INCLUDES_FOLDER_PATH . 'auto_install/auto_install.php')){
include_once (TT_INCLUDES_FOLDER_PATH . 'auto_install/auto_install.php'); // sample data insert file
}
if(file_exists(TT_FUNCTIONS_FOLDER_PATH . 'captcha/captcha_function.php')){
include_once (TT_FUNCTIONS_FOLDER_PATH . 'captcha/captcha_function.php'); // manage theme filters in the file
}

add_theme_support( 'post-formats', array( 'aside', 'gallery','link', 'image','quote', 'status','video', 'audio','chat') );

// realted posts ////////////////

include_once ('rss_functions.php');


?>