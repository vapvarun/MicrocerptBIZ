<?php
    define('_LIMIT_' , 10 );
    define('_AUTL_' , 7 );
    define('BLOCK_TITLE_LEN' , 50 );
    
    /* google maps defines */
    define('MAP_LAT'    , 48.85680934671159 );
    define('MAP_LNG'    , 2.353348731994629 );
    define('MAP_CLAT'   , 48.85700699730661 );
    define('MAP_CLNG'   , 2.354121208190918 );
    define('MAP_ZOOM'   , 15 );
	define('DEFAULT_AVATAR'   , get_template_directory_uri()."/images/default_avatar.jpg" );
	define('DEFAULT_AVATAR_100'   , get_template_directory_uri()."/images/default_avatar_100.jpg" );
	define('DEFAULT_AVATAR_LOGIN'   , get_template_directory_uri()."/images/default_avatar_login.png" );
    if( function_exists( 'wp_get_theme' ) ){
        define( '_TN_'      , wp_get_theme() );
    }else{
        define( '_TN_'      , get_current_theme() );
    }
	define('BRAND'      , '' );
	define('ZIP_NAME'   , 'serpent' );


   // add_action('admin_bar_menu', 'de_cosmotheme');
    
	include 'lib/php/main.php';

    
    
    include 'lib/php/actions.register.php';
    include 'lib/php/menu.register.php';

    $content_width = 600;
  
    if( function_exists( 'add_theme_support' ) ){ 
        add_theme_support( 'automatic-feed-links' );
        add_theme_support( 'post-thumbnails' );
    }

    image::add_size();

    if( isset( $_GET['post_id'] ) && $_GET['post_id'] == -1 ){
        /*disable flash uploader, we need that to avoid uploader failure on front end*/
        add_filter('flash_uploader', '__return_false', 5);
    }

	if (version_compare($wp_version, '3.4', '>=')) { 
        add_theme_support( 'custom-background' );
    }else{ 
        if( function_exists( 'add_custom_background' ) ){
            add_custom_background();
        }else{
            add_theme_support( 'custom-background' );
        }
    } 

	add_theme_support( 'post-formats' , array( 'image' , 'video' , 'audio' ) );
	add_editor_style('editor-style.css');
	
	

    /* Localization */
    load_theme_textdomain( 'cosmotheme' );
    load_theme_textdomain( 'cosmotheme' , get_template_directory() . '/languages' );
    
    if ( function_exists( 'load_child_theme_textdomain' ) ){
        load_child_theme_textdomain( 'cosmotheme' );
    }

	function remove_post_format_fields() {
		remove_meta_box( 'formatdiv' , 'post' , 'side' ); 
	}
	add_action( 'admin_menu' , 'remove_post_format_fields' );
    
	if(is_admin() && ini_get('allow_url_fopen') == '1'){
		/*New version check*/	
		if( options::logic( 'cosmothemes' , 'show_new_version' ) ){
			function versionNotify(){
				echo api_call::compareVersions(); 
			}
		
			// Add hook for admin <head></head>
			add_action('admin_head', 'versionNotify');
		}

		/*Cosmo news*/
		if( options::logic( 'cosmothemes' , 'show_cosmo_news' ) && !isset($_GET['post_id'])  && !isset($_GET['post'])){
			function doCosmoNews(){
				echo api_call::getCosmoNews(); 
			}
		
			// Add hook for admin <head></head>
			add_action('admin_head', 'doCosmoNews');
		}	
	}

    add_filter( 'pre_get_posts', 'cosmo_posts_per_archive' );
    function cosmo_posts_per_archive( $query ) {
        if( isset( $_GET[ 'fp_type' ] ) ){
           if( $_GET[ 'fp_type' ] == 'news' ){
                $query -> set( 'posts_per_page', options::get_value( 'front_page', 'new_per_page' ) );
           }else if( $_GET[ 'fp_type' ] == 'hot' ){
                $query -> set( 'posts_per_page', options::get_value( 'front_page', 'hot_per_page' ) );
           }
        }        
        return $query;
    }

    /* Cosmothemes Backend link */
    function de_cosmotheme() {
        global $wp_admin_bar;    
        if ( !is_super_admin() || !is_admin_bar_showing() ){
            return;
        }
        $wp_admin_bar -> add_menu( array(
            'id' => 'cosmothemes',
            'parent' => '',
            'title' => _TN_,
            'href' => admin_url( 'admin.php?page=cosmothemes__general' )
            ) );   
    }

	add_filter('excerpt_length', 'cosmo_excerpt_length');
	function cosmo_excerpt_length($length) {
		return 70;  /* Or whatever you want the length to be. */
	}

    /*allow subscribers to upload files*/
	if ( current_user_can('subscriber') && !current_user_can('upload_files') )
	add_action('admin_init', 'allow_subscriber_uploads');

	function allow_subscriber_uploads() {
		$subscriber = get_role('subscriber');
		$subscriber->add_cap('upload_files');
	}

	if( !options::logic( 'general' , 'show_admin_bar' ) ){
		add_filter( 'show_admin_bar', '__return_false' );
	}

	/*Default pages creation*/
	if(!get_option( 'default-pages' ) ){ 
		
		$default_custom_pages = array('Post item','My added posts','My account'); /*create this pages only ones, this pages will be by defaut assigned in the theme settings. The user can change them. */
		
		foreach($default_custom_pages as $page){
			$pages = array(
				'post_title' => $page,
				'post_content' => '',
				'post_status' => 'publish',
				'post_type' => 'page'
			);

			wp_insert_post($pages);	
		}
		add_option( 'default-pages', 'pages created' );
	}

	add_editor_style('editor-style.css');
	
	get_template_part( '/videojs/video-js' ); 

function ya_do_it_admin_bar_remove() {
        global $wp_admin_bar;

        /* **edit-profile is the ID** */
        $wp_admin_bar->remove_menu('edit-profile');
 }

add_action('wp_before_admin_bar_render', 'ya_do_it_admin_bar_remove', 0);
	function load_css() {
		
		$files = scandir( get_template_directory()."/css/autoinclude" );
		foreach( $files as $file ){
			if( is_file( get_template_directory()."/css/autoinclude/$file" ) ){
				wp_register_style( $file.'-style',get_template_directory_uri() . '/css/autoinclude/'.$file );
				wp_enqueue_style( $file.'-style' );
			}
		}

		if(options::logic( 'general' , 'enb_lightbox' ) && is_singular()){
			wp_register_style( 'prettyPhoto',get_template_directory_uri() . '/css/prettyPhoto.css' );
			wp_enqueue_style( 'prettyPhoto' );
		}

		//wp_enqueue_script( 'modernizr' , get_template_directory_uri() . '/js/modernizr.js' , array( 'jquery' ) );
		wp_enqueue_script( 'slides_min' , get_template_directory_uri() . '/js/slides.min.jquery.js' , array( 'jquery' ) );
		wp_enqueue_script( 'orbit' , get_template_directory_uri() . '/js/jquery.orbit.min.js' , array( 'jquery' , 'foundation' ) );
		wp_enqueue_script( 'foundation' , get_template_directory_uri() . '/js/foundation.js' , array( 'jquery' ) );
		wp_enqueue_script( 'app' , get_template_directory_uri() . '/js/app.js' , array( 'foundation ' ) );
		wp_enqueue_script( 'mosaic' , get_template_directory_uri() . '/js/jquery.mosaic.1.0.1.min.js' , array( 'jquery' ) );
		wp_enqueue_script( 'superfish' , get_template_directory_uri() . '/js/jquery.superfish.js' , array( 'jquery' ) );
		wp_enqueue_script( 'supersubs' , get_template_directory_uri() . '/js/jquery.supersubs.js' , array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui-accordion' );
		wp_enqueue_script( 'jquery-accordion-mobile' , get_template_directory_uri() . '/js/jquery.accordion.js' , array( 'jquery' ) );
		wp_enqueue_script( 'tour' , get_template_directory_uri() . '/js/tour.js' , array( 'jquery' ) );
		wp_enqueue_script( 'tabs' , get_template_directory_uri() . '/js/jquery.tabs.pack.js' , array( 'jquery' ) );
		wp_enqueue_script( 'scrollto' , get_template_directory_uri() . '/js/jquery.scrollTo-1.4.2-min.js' , array( 'jquery' ) );
		wp_enqueue_script( 'functions' , get_template_directory_uri() . '/js/functions.js' , array( 'jquery' , 'tabs' , 'scrollto' ) );
		wp_enqueue_script( 'jquery-cookie' , get_template_directory_uri() . '/js/jquery.cookie.js' , array( 'jquery' ) );
		
		if(options::logic( 'general' , 'enb_lightbox' ) && is_singular()){
			wp_enqueue_script( 'prettyPhoto' , get_template_directory_uri() . '/js/jquery.prettyPhoto.js' , array( 'jquery' ) );
			wp_enqueue_script( 'prettyPhotoSettings' , get_template_directory_uri() . '/js/prettyPhoto.settings.js' , array( 'prettyPhoto' ) );
		}

		if( options::logic( 'general' , 'enb_hot_keys' ) ){
			wp_enqueue_script( 'hot_keys' , get_template_directory_uri() . '/js/hot_keys.js' , array( 'functions' ) );
		}
	}

	add_action('wp_enqueue_scripts', 'load_css');
?>