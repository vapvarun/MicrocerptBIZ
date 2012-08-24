<?php 
    $fb_id = options::get_value( 'social' , 'facebook' );
    if( strlen( trim( $fb_id ) ) ){
        $fb['likes'] = social::pbd_get_transient($name = 'facebook',$user_id=$fb_id,$cacheTime = 120); /*cache - in minutes*/
        $fb['link'] = 'http://facebook.com/people/@/'  . $fb_id ;
    }
?>  
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?> xmlns:fb="http://ogp.me/ns/fb#"><!--<![endif]-->
<head>
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta name="robots"  content="index, follow" />
    
    <meta name="description" content="<?php echo get_bloginfo('description'); ?>" /> 
    <?php if( is_single() || is_page() ){ ?>
        <meta property="og:title" content="<?php the_title() ?>" />
        <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>" />
        <meta property="og:url" content="<?php the_permalink() ?>" />
        <meta property="og:type" content="article" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:description" content="<?php echo get_bloginfo('description'); ?>"/>
        <?php 
            if(options::get_value( 'social' , 'facebook_app_id' ) != ''){
                ?><meta property='fb:app_id' content='<?php echo options::get_value( 'social' , 'facebook_app_id' ); ?>'><?php
            }
            
            global $post;
            $src  = wp_get_attachment_image_src( get_post_thumbnail_id( $post -> ID ) , 'thumbnail' );
            echo '<meta property="og:image" content="'.$src[0].'"/>'; 
            echo ' <link rel="image_src" href="'.$src[0].'" / >';           
            wp_reset_query();   
        }else{ ?>
            <meta property="og:title" content="<?php echo get_bloginfo('name'); ?>"/>
            <meta property="og:site_name" content="<?php echo get_bloginfo('name'); ?>"/>
            <meta property="og:url" content="<?php echo home_url() ?>/"/>
            <meta property="og:type" content="blog"/>
            <meta property="og:locale" content="en_US"/>
            <meta property="og:description" content="<?php echo get_bloginfo('description'); ?>"/>
            <meta property="og:image" content="<?php echo get_template_directory_uri()?>/fb_screenshot.png"/> 
    <?php
        }
    ?>

    <title><?php bloginfo('name'); ?> &raquo; <?php bloginfo('description'); ?><?php if ( is_single() ) { ?><?php } ?><?php wp_title(); ?></title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0" />

    <?php
        if( strlen( options::get_value( 'styling' , 'favicon' ) ) ){
            $path_parts = pathinfo( options::get_value( 'styling' , 'favicon' ) );
            if( $path_parts['extension'] == 'ico' ){
    ?>
                <link rel="shortcut icon" href="<?php echo options::get_value( 'styling' , 'favicon' ); ?>" />
    <?php
            }else{
    ?>
                <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" />
    <?php
            }
        }else{
    ?>
            <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" />
    <?php
        }
    ?>

    <link rel="profile" href="http://gmpg.org/xfn/11" />

    <!-- ststylesheet -->
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="all" />
    <link href='http://fonts.googleapis.com/css?family=PT+Sans+Narrow:400,700&amp;subset=latin,cyrillic' rel='stylesheet' type='text/css' />

    <?php if( options::get_value( 'styling' , 'logo_type' ) == 'text' ) { ?>
        <link href='http://fonts.googleapis.com/css?family=<?php  echo str_replace(' ' , '+' , trim( options::get_value( 'styling' , 'logo_font_family' ) ) );?>' rel='stylesheet' type='text/css' />
    <?php } ?>


    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/lib/css/shortcode.css" />

		<?php if ( options::logic( 'styling' , 'front_end' ) ){ ?>  
		<link rel="stylesheet" type="text/css" href="<?php echo home_url()?>/wp-admin/css/farbtastic.css" />
		<?php } ?>
		
        <!-- javascript -->
        <?php  
			wp_enqueue_script( "jquery" );	
			if ( is_singular() ){ wp_enqueue_script( "comment-reply" ); } 
            
			wp_register_script( 'actions', get_template_directory_uri().'/lib/js/actions.js' , array('jquery') );
            
            if(is_page() ) {
                wp_enqueue_script('media-upload');
                wp_enqueue_script('thickbox'); 
                
                wp_enqueue_style( 'ui-lightness');
                wp_enqueue_style('thickbox');
            }
            
            wp_enqueue_script( 'actions' );
            
            wp_head();

        ?>

        <!--[if lt IE 9]>
            <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    <?php if ( options::logic( 'styling' , 'front_end' ) ){ ?>  
    <link rel="stylesheet" type="text/css" href="<?php echo home_url()?>/wp-admin/css/farbtastic.css" />
    <?php } ?>
    
    <!-- javascript -->
    <?php 
        wp_enqueue_script( "jquery" );  
        if ( is_singular() ){ wp_enqueue_script( "comment-reply" ); } 
        
        wp_register_script( 'actions', get_template_directory_uri().'/lib/js/actions.js' , array('jquery') );
        
        if(is_page() ) {
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox'); 
            
            wp_enqueue_style( 'ui-lightness');
            wp_enqueue_style('thickbox');
        }
        
        wp_enqueue_script( 'actions' );
    ?>

    <!--[if lt IE 9]>
        <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/autoinclude/ie.css">
    <![endif]-->
    
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/chrome-frame/1/CFInstall.min.js"></script>
        <script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery.chrome_frame.js"></script>
        <style>
            #chrome_msg { display:none; z-index: 999; position: fixed; top: 0; left: 0; background: #ece475; border: 2px solid #666; border-top: none; font: bold 11px Verdana, Geneva, Arial, Helvetica, sans-serif; line-height: 100%; width: 100%; text-align: center; padding: 5px 0; margin: 0 auto; }
            #chrome_msg a, #chrome_msg a:link { color: #a70101; text-decoration: none; }
            #chrome_msg a:hover { color: #a70101; text-decoration: underline; }
            #chrome_msg a#msg_hide { float: right; margin-right: 15px; cursor: pointer; }
            /* IE6 positioning fix */
            * html #chrome_msg { left: auto; margin: 0 auto; border-top: 2px solid #666;  }
        </style>
    <![endif]-->    
    <?php wp_head(); ?>    

    <!-- init ajaxurl -->
    <script type="text/javascript">
        <?php
            $siteurl = get_option('siteurl');
            if( !empty($siteurl) ){
                $siteurl = rtrim( $siteurl , '/') . '/wp-admin/admin-ajax.php' ;
            }else{
                $siteurl = home_url('/wp-admin/admin-ajax.php');
            }
        ?>

        var ajaxurl = "<?php echo $siteurl; ?>";
        var cookies_prefix = "<?php echo ZIP_NAME; ?>";  
        var themeurl = "<?php echo get_template_directory_uri(); ?>";
        jQuery( function(){
            jQuery( '.demo-tooltip' ).tour();
        });

    </script>

    <script type="text/javascript">
        /*redirect to post item page*/
        jQuery(document).ready(function(){
            var post_item_page = "<?php  echo get_page_link(options::get_value( 'upload' , 'post_item_page' ));  ?>"; 
            jQuery('.simplemodal-submit').click(function() { 
                jQuery('[name="redirect_to"]').val(post_item_page);
            })
        });
    </script>

    <!--Custom CSS-->
    <?php if( strlen( options::get_value( 'custom_css' , 'css' ) )  > 0 ){ ?>
        <style type="text/css">
            <?php echo options::get_value( 'custom_css' , 'css' ); ?>    
        </style>

    <?php }  ?>

    <?php if( options::get_value( 'styling' , 'logo_type' ) == 'text' ) {
        $logo_font_family = explode('&',options::get_value('styling' , 'logo_font_family'));
        $logo_font_family = $logo_font_family[0];
        $logo_font_family = str_replace( '+',' ',$logo_font_family );
    ?>
        <style type="text/css">
            hgroup.logo a h3{
            font-family: '<?php echo $logo_font_family ?>', arial, serif !important;
            font-size: <?php echo options::get_value('styling' , 'logo_font_size')?>px;
            font-weight: <?php echo options::get_value('styling' , 'logo_font_weight')?>;
        }
        </style>
    <?php } ?>
</head>

<?php
    $position   = '';
    $repeat     = '';
    $bgatt      = '';
    $background_color = '';

    if( is_single() || is_page() ){
        $settings = meta::get_meta( $post -> ID , 'settings' );
        if( ( isset( $settings['post_bg'] ) && !empty( $settings['post_bg'] ) ) || ( isset( $settings['color'] ) && !empty( $settings['color'] ) ) ){
            if( isset( $settings['post_bg'] ) && !empty( $settings['post_bg'] ) ){ 
                $background_img = "background-image: url('" . $settings['post_bg'] . "');";
            }

            if( isset( $settings['color'] ) && !empty( $settings['color'] ) ){
                $background_color = "background-color: " . $settings['color'] . "; ";
            }

            if( isset( $settings['position'] ) && !empty( $settings['position'] ) ){
                $position = 'background-position: '. $settings['position'] . ';';
            }
            if( isset( $settings['repeat'] ) && !empty( $settings['repeat'] ) ){
                $repeat = 'background-repeat: '. $settings['repeat'] . ';';
            }
            if( isset( $settings['attachment'] ) && !empty( $settings['attachment'] ) ){
                $bgatt = 'background-attachment: '. $settings['attachment'] . ';';
            }
        }else{
            if(get_background_image() == '' && get_bg_image() != ''){ 
                if(get_bg_image() != 'pattern.none.png'){
                    $background_img = 'background-image: url('.get_template_directory_uri().'/lib/images/pattern/'.get_bg_image().');';
                }else{
                    $background_img = '';
                }    
                /*if day or night images are set then we will add 'background-attachment:fixed'   */
                if(strpos(get_bg_image(),'.jpg')){
                    $background_img .= ' background-attachment:fixed';
                }
            }else{
                $background_img = '';
            }
            if(get_content_bg_color() != ''){
                $background_color = "background-color: " . get_content_bg_color() . "; ";
            }
        }
    }else{
        if(get_background_image() == '' && get_bg_image() != ''){
            if(get_bg_image() != 'pattern.none.png'){
                $background_img = 'background-image: url('.get_template_directory_uri().'/lib/images/pattern/'.get_bg_image().');';
            }else{
                $background_img = '';
            }    
            /*if day or night images are set then we will add 'background-attachment:fixed'   */
            if(strpos(get_bg_image(),'.jpg')){
                $background_img .= ' background-attachment:fixed;';
            }
        }else{
            $background_img = '';
        }
        if(get_content_bg_color() != ''){
            $background_color = "background-color: " . get_content_bg_color() . "; ";
        }

        if( strlen( get_background_image() ) ){
            $background_img = '';
        }

        if( strlen( get_background_color() ) ){
            $background_color = '';
        }
    }
?>
<body <?php body_class(); ?> style="<?php echo $background_color ; ?> <?php echo $background_img ; ?>  <?php echo $position; ?> <?php echo $repeat; ?> <?php echo $bgatt; ?>">
   
    <?php   if( is_user_logged_in () && (isset($post -> ID) && $post -> ID != options::get_value( 'upload' , 'post_item_page' )) && options::logic( 'general' , 'sticky_bar' ) && options::logic( 'general' , 'user_login' )){ ?> 
        <div class="sticky-bar" id="sticky-bar">
            <div class="container <?php if (options::logic('styling', 'larger')) { echo 'large'; } ?>">
                <div class="left">

                    <a href="#" class="profile-pic">
                        <?php
						$u_id = get_current_user_id();
						$picture = facebook::picture();
                        if( strlen( $picture )  && get_user_meta( $u_id , 'custom_avatar' , true ) == ''){
                            ?><a href="http://facebook.com/profile.php?id=<?php echo facebook::id(); ?>" class="profile-pic"><img src="<?php echo $picture; ?>" width="32" width="32" /></a><?php
                        }else{
                            echo '<a href="' . get_author_posts_url( $u_id ) . '" class="profile-pic">'  . cosmo_avatar( $u_id , 32 , $default = DEFAULT_AVATAR_LOGIN ) . '</a>';
                        }
                        ?>
                    </a>
                    <div class="cosmo-icons">
                        <ul>
                            <li class="signin"><a href="<?php echo get_author_posts_url( $u_id ); ?>"><?php the_author_meta( 'user_login', $u_id ); ?></a></li>
                        </ul>
                    </div>
                </div>
                <div class="right">
                    <div class="cosmo-icons fr">
						<?php
							$url = home_url();
							$like = array( 'fp_type' => "like" );
							$url_like = add_query_arg( $like , $url );
						?>
                        <ul>

                            <?php if(options::logic( 'upload' , 'enb_image' ) ){    ?>
                            <li class="image show-hover hidden"><a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#pic_upload"><?php _e('Image','cosmotheme'); ?></a></li>
                            <?php } ?> 
                            <?php if(options::logic( 'upload' , 'enb_video' ) ){    ?>
                            <li class="video show-hover hidden"> <a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#video_upload"><?php _e('Video','cosmotheme'); ?></a></li>
                            <?php } ?> 
                            <?php if(options::logic( 'upload' , 'enb_text' ) ){ ?>
                            <li class="text show-hover hidden"> <a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#text_post"><?php _e('Text','cosmotheme'); ?></a></li>
                            <?php } ?> 
                            <?php if(options::logic( 'upload' , 'enb_audio' ) ){    ?>
                            <li class="audio show-hover hidden"> <a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#audio_post"><?php _e('Audio','cosmotheme'); ?></a></li>
                            <?php } ?>
                            <?php if(options::logic( 'upload' , 'enb_file' ) ){ ?>
                            <li class="attach show-hover hidden"> <a href="<?php echo get_page_link(options::get_value( 'upload' , 'post_item_page' )); ?>#file_post"><?php _e('File','cosmotheme'); ?></a></li>
                            <?php } ?> 
          
                            <?php if(is_numeric(options::get_value( 'general' , 'user_profile_page' )) && options::get_value( 'general' , 'user_profile_page' ) > 0){ ?>
                                <li class="my-settings show-first"><a href="<?php  echo get_page_link(options::get_value( 'general' , 'user_profile_page' ));  ?>"><?php _e( 'My settings' , 'cosmotheme' ); ?></a></li>
                            <?php } ?>
                            <?php
                                if( post::get_my_posts( get_current_user_id() ) ){
                            ?>
                                <li class="my-profile show-first"><a href="<?php echo get_author_posts_url( $u_id );  ?>"><?php _e( 'My profile' , 'cosmotheme' ); ?></a></li>
                            <?php } ?>
                            <?php
                                if( post::get_my_posts( get_current_user_id() ) && (int)options::get_value( 'general' , 'my_posts_page' ) > 0 ){
                            ?>
                                    <li class="my-posts show-first"><a href="<?php echo get_permalink( options::get_value( 'general' , 'my_posts_page' ) ) ?>"><?php _e( 'My added posts' , 'cosmotheme' ); ?></a></li>
                            <?php
                                }
                            ?>  
                            <?php if( options::logic( 'general' ,  'enb_likes' ) ){ ?> 
                                <li class="my-likes show-first"><a href="<?php echo $url_like; ?>"><?php _e( 'My liked posts' , 'cosmotheme' ); ?></a></li>
                            <?php } ?>
                            <?php if(is_numeric(options::get_value( 'upload' , 'post_item_page' )) && options::get_value( 'upload' , 'post_item_page' ) > 0){ ?>
                                <li class="my-add"><a href="<?php  echo get_page_link(options::get_value( 'upload' , 'post_item_page' ));  ?>"><?php _e( 'Add post' , 'cosmotheme' ); ?></a></li>
                            <?php } ?>
                                <li class="my-logout"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Log out' , 'cosmotheme' ); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>    
        </div>
    <?php } ?>
    <div class="container <?php if (options::logic('styling', 'larger')) { echo 'large'; } ?> boxed" id="page">
        <div id="fb-root"></div>
     <?php
        $tooltips = options::get_value( '_tooltip' );
            if( is_array( $tooltips ) && !empty( $tooltips ) ){
                $tools = array();
                foreach( $tooltips as $key => $tooltip ){
                    if( is_front_page()  && $tooltip['res_type'] == 'front_page' ){
                        if( defined('IS_FOR_DEMO') ){
                            if( is_user_logged_in() ){
                                if( $tooltip['title'] != 'Login form for members' ){
                                    $location = 'front_page';
                                    $id = 0;
                                    $tools[] = $tooltip;
                                }
                            }else{
                                $location = 'front_page';
                                $id = 0;
                                $tools[] = $tooltip;
                            }
                        }else{
                            $location = 'front_page';
                            $id = 0;
                            $tools[] = $tooltip;
                        }
                    }
                    
                    if( is_single() && isset( $tooltip['res_type'] ) && $tooltip['res_type'] == 'single' && isset( $tooltip['res_posts'] ) && $tooltip['res_posts'] == $post -> ID ){
                        $location = 'single';
                        $id = $post -> ID ;
                        $tools[] = $tooltip;
                    }
                    
                    if( is_page() && isset( $tooltip['res_type'] ) && $tooltip['res_type'] == 'page' && isset( $tooltip['res_pages'] ) && $tooltip['res_pages'] == $post -> ID ){
                        $location = 'page';
                        $id = $post -> ID ;
                        $tools[] = $tooltip;
                    }
                }
                
                if( isset( $location ) ){
                    if( ( isset( $_COOKIE[ ZIP_NAME . '_tour_closed_' . $location . '_' . $id ] ) && $_COOKIE[ ZIP_NAME . '_tour_closed_' . $location . '_' . $id ] != 'true' ) || !isset( $_COOKIE[ ZIP_NAME . '_tour_closed_' . $location . '_' . $id ] ) ){
                        foreach( $tools as $key => $tool ){
                            if( $key + 1 == count( $tools ) ){
                                tools::tour( array( $tool['top'] , $tool['left'] ) , $location , $id , $tool['type'] , $tool['title'] , $tool['description'] , ( $key + 1 ) . '/' . count( $tools ) , false );
                            }else{
                                tools::tour( array( $tool['top'] , $tool['left'] ) , $location , $id , $tool['type'] , $tool['title'] , $tool['description'] , ( $key + 1 ) . '/' . count( $tools ) );
                            }
                        }
                    }
                }
            }
    ?>
        <?php
            if( options::logic( 'general' , 'enb_keyboard' ) ){
        ?>
        <div class="row">
            <div class="twelve columns" id="big-keyboard">
                <div id="keyboard-container" class="bottom-separator relative">
                    <div id="img">
                        <img src="<?php echo get_template_directory_uri()?>/images/keyboard.png"  alt=""/>
                        <p class="hint">
                            <?php _e( 'Use advanced navigation for a better experience.' , 'cosmotheme' ); ?>
                            <br />
                            <?php _e( 'You can quickly scroll through posts by pressing the above keyboard keys. Now press <strong>the button in right corner</strong> to close this window.' , 'cosmotheme' ); ?>
                        </p>
                    </div>
                    <div class="close"></div>
                </div>
            </div>
        </div>
        <?php
            }
        ?>
        <?php
            if( options::logic( 'general' , 'fb_comments' ) ){
                if(options::get_value( 'social' , 'facebook_app_id' ) != ''){
        ?>
                    <?php
                        if( is_user_logged_in () ){
                    ?>
                            <script src="http://connect.facebook.net/en_US/all.js" type="text/javascript" id="fb_script"></script>
                            <script type="text/javascript">
                                FB.getLoginStatus(function(response) {
                                    if( typeof response.status == 'unknown' ){
                                        jQuery(function(){
                                            jQuery.cookie('fbs_<?php echo options::get_value( 'social' , 'facebook_app_id' ); ?>' , null , {expires: 365, path: '/'} );
                                        });
                                    }else{
                                        if( response.status == 'connected' ){
                                            jQuery(function(){
                                                jQuery('#fb_script').attr( 'src' ,  document.location.protocol + '//connect.facebook.net/en_US/all.js#appId=<?php echo options::get_value( 'social' , 'facebook_app_id' ); ?>' );
                                            });
                                        }
                                    }
                                });
                            </script>
                    <?php
                        }else{
                    ?>
                            <script src="http://connect.facebook.net/en_US/all.js" type="text/javascript"></script>
                    <?php
                        }
                    ?>
        <?php
                }else{
        ?>
                    <script src="http://connect.facebook.net/en_US/all.js" type="text/javascript"></script>

        <?php   }
            }else{
        ?>  
                <script src="http://connect.facebook.net/en_US/all.js" type="text/javascript" id="fb_script"></script>  
        <?php   
            }
        ?> 
        <script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"> </script> 

        <header id="header">
            <div class="row">
                <div class="branding">
                    <div class="columns four">
                        <div class="innerContent">
                            <hgroup class="logo">
                                
                                <?php 
                                    $top_separator = '';
                                    if( options::get_value( 'styling' , 'logo_type' ) == 'text' ) { 
                                        $top_separator = 'top-separator';
                                ?>
                                        <a href="<?php echo home_url(); ?>" class="hover"><h3><?php bloginfo('name'); ?> <span><?php bloginfo('description'); ?></span></h3></a>
                                <?php }elseif(options::get_value( 'styling' , 'logo_type' ) == 'image' && options::get_value( 'styling' , 'logo_url' ) == '' ){ 
                                ?>
                                        <a href="<?php echo home_url(); ?>" class="hover">
                                            <h1>
                                                <img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" />
                                            </h1>
                                        </a>
                                <?php }else{?>
                                        <a href="<?php echo home_url(); ?>" class="hover">
                                            <h1>
                                                <img src="<?php echo options::get_value( 'styling' , 'logo_url' ) ?>" >
                                            </h1>
                                        </a>
                                <?php } ?>
                            </hgroup>
                        </div>
                    </div>
                    <div class="cosmo-social four columns">
                        <div class="right rmargin">
                            <?php
                                if( isset( $fb ) && is_array( $fb ) && !empty( $fb ) && isset( $fb['link'] ) ){
                            ?>
                                    <a href="<?php echo $fb['link']; ?>" target="_blank" class="fb hover-menu"><span id="facebook"><?php echo $fb['likes']; ?></span></a>
                            <?php
                                }

                                if( strlen( options::get_value( 'social' , 'twitter' ) ) ){
                                    $nr_tweets = tweets::followers_count(options::get_value( 'social' , 'twitter' ))
                            ?>
                                    <a href="http://twitter.com/<?php echo options::get_value( 'social' , 'twitter' ) ?>" target="_blank" class="twitter hover-menu"><span><?php echo $nr_tweets; ?></span></a>
                            <?php
                                }
                                $query = new WP_Query('status=public');
                                wp_reset_query();
                            ?>
                            <?php
                                if( options::logic( 'social' , 'rss' ) ){
                            ?>
                                <a href="<?php bloginfo('rss2_url'); ?>" class="rss hover-menu"><span><?php echo $query -> found_posts ; ?></span></a>
                            <?php
                                }
                            ?>
                        </div>
                    </div>
                    <div id="searchbox" class="four columns">
                        <?php get_template_part( 'searchform' ); ?>              
                    </div>
                </div>
            </div>
            <nav id="access" role="navigation" class="row no-overflow bottom-separator <?php echo $top_separator;?>">
                <div id="d-menu" class="cosmo-icons ten columns">
                    <?php echo menu( 'header_menu' , array( 'number-items' => options::get_value( 'menu' , 'header' )  , 'current-class' => 'active','type' => 'category', 'class' => 'sf-menu nav-bar fl supplement' ) ); ?>
                </div>
                <?php if( options::logic( 'general' ,  'user_login' ) ){ ?>
                <div class="two columns" id="menu-login">
                <?php
                    $role = array( 
                        10 => __( 'Administrator' , 'cosmotheme' ) ,
                        7 => __( 'Editor' , 'cosmotheme' ) , 
                        2 => __( 'Author' , 'cosmotheme' ) , 
                        1 => __( 'Contributor' , 'cosmotheme'  ) , 
                        0 => __( 'Subscriber' , 'cosmotheme' ), 
                        '' => __( 'Subscriber' , 'cosmotheme' )
                    );
                    
                    if( is_user_logged_in () ){
                        $u_id = get_current_user_id();
                        
                        $picture = facebook::picture();
                        if( strlen( $picture ) && get_user_meta( $u_id , 'custom_avatar' , true ) == ''){
                            ?><a href="http://facebook.com/profile.php?id=<?php echo facebook::id(); ?>" class="profile-pic"><img src="<?php echo $picture; ?>" width="32" width="32" /></a><?php
                        }else{
                            echo '<a href="' . get_author_posts_url( $u_id ) . '" class="profile-pic">'  . cosmo_avatar( $u_id , 32 , $default = DEFAULT_AVATAR_LOGIN ) . '</a>';
                        }
                        
                        $url = home_url();

                        $like = array( 'fp_type' => "like" );
                        $url_like = add_query_arg( $like , $url );
                        
                        ?>
                            <div class="cosmo-icons">
                                <ul class="sf-menu">
                                    <li class="signin">
                                        <?php 
                                            $user = (array)get_userdata( $u_id ); //var_dump($user['roles']);
                                            if($wp_version < 3.3){
                                                if( !isset( $user['user_level'] ) ){
                                                    $user['user_level'] = '';
                                                }
                                                $user_login = $user['user_login'];
                                                $user_role = $role[ $user['user_level'] ];
                                            }else{
                                                if(isset($user['roles'][0])){
                                                    $user['user_level'] =   $user['roles'][0]; 
                                                }else $user['user_level']=__( 'Subscriber' , 'cosmotheme' );
                                                $user_login = $user['data']->user_login;    
                                                $user_role = $user['user_level'];
                                            }   
                                        ?>
                                            <a href="<?php echo get_author_posts_url( $u_id );  ?>"><?php echo $user_login; ?><img src="<?php echo get_template_directory_uri() ?>/images/mask.png" class="mask" alt="Mask" /><span><?php echo $user_role; ?></span></a>
                                        
                                        <ul>
                                            <?php if(is_numeric(options::get_value( 'general' , 'user_profile_page' )) && options::get_value( 'general' , 'user_profile_page' ) > 0){ ?>
                                                    <li class="my-settings"><a href="<?php  echo get_page_link(options::get_value( 'general' , 'user_profile_page' ));  ?>"><?php _e( 'My settings' , 'cosmotheme' ); ?></a></li>
                                            <?php } ?>
                                            <?php
                                                if( post::get_my_posts( get_current_user_id() ) ){
                                            ?>
                                                    <li class="my-profile"><a href="<?php echo get_author_posts_url( $u_id );  ?>"><?php _e( 'My profile' , 'cosmotheme' ); ?></a></li>
                                            <?php
                                                }
                                            ?>        
                                            <?php
                                                if( post::get_my_posts( get_current_user_id() ) && (int)options::get_value( 'general' , 'my_posts_page' ) > 0 ){
                                            ?>
                                                    <li class="my-posts"><a href="<?php echo get_permalink( options::get_value( 'general' , 'my_posts_page' ) ) ?>"><?php _e( 'My added posts' , 'cosmotheme' ); ?></a></li>
                                            <?php
                                                }
                                            ?>
                                            <?php 
                                                if( options::logic( 'general' ,  'enb_likes' ) ){
                                                    ?><li class="my-likes"><a href="<?php echo $url_like; ?>"><?php _e( 'My liked posts' , 'cosmotheme' ); ?></a></li><?php
                                                }
                                            ?>
                                            <?php if(is_numeric(options::get_value( 'upload' , 'post_item_page' )) && options::get_value( 'upload' , 'post_item_page' ) > 0){ ?>
                                            <li class="my-add"><a href="<?php  echo get_page_link(options::get_value( 'upload' , 'post_item_page' ));  ?>"><?php _e( 'Add post' , 'cosmotheme' ); ?></a></li>     
                                            <?php } ?>
                                            <li class="my-logout"><a href="<?php echo wp_logout_url( home_url() ); ?>"><?php _e( 'Log out' , 'cosmotheme' ); ?></a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        <?php
                    }else{
                        ?>
                            <div class="right">
                                <a href="<?php echo wp_login_url(); ?>" class="profile-pic simplemodal-login simplemodal-none left"><img src="<?php echo get_template_directory_uri() ?>/images/default_avatar_login.png" /></a>
                                <div class="cosmo-icons left">
                                    <ul class="sf-menu">
                                        <li class="signin">
                                            <a class="simplemodal-login simplemodal-none" href="<?php echo wp_login_url(); ?>"><?php _e( 'sign in' , 'cosmotheme' ); ?><span><?php _e( 'members area' , 'cosmotheme' ); ?></span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        <?php
                    }
                ?>
                </div>
                <?php } ?>
                <div class="mobile-menu twelve" class="hide">
                    <div class="toggle-menu"><?php _e('MENU','cosmotheme'); ?></div>
                    <?php echo menu( 'header_menu' , array( 'number-items' => 20 , 'current-class' => 'active','type' => 'category', 'class' => 'mobile-nav-menu', 'menu_id' => 'mobile-nav-menu' ) ); ?>
                    
                </div>
            </nav>

            <?php get_template_part( 'slideshow' ); ?>

            <?php
                if( options::logic( 'general' , 'breadcrumbs' ) && !is_front_page() ){
                    echo '<div class="breadcrumbs">';
                    echo '<ul>';
                    dimox_breadcrumbs();
                    echo '</ul>';
                    echo '<div class="clear"></div></div>';
                }
            ?>
        </header>
