<?php

class post {
    static $post_id = 0;
    function get_my_posts( $author){
        $wp_query = new WP_Query( array('post_status' => 'any', 'post_type' => 'post' , 'author' => $author ) );
        if( count( $wp_query -> posts ) > 0 ){
            return true;
        }else{
            return false;
        }
    }
    function my_posts( $author ){
        global $wp_query;
        
        if ((int) get_query_var('paged') > 0) {
            $paged = get_query_var('paged');
        } else {
            if ((int) get_query_var('page') > 0) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
        }
        
        if( (int)$author > 0  ){
            $wp_query = new WP_Query(array('post_status' => 'any', 'post_type' => 'post', 'paged' => $paged, 'author' => $author  ));
            
            foreach( $wp_query -> posts as $key => $post ){
                $wp_query -> the_post();
                if( $key > 0 ){
            ?>
                    <p class="delimiter">&nbsp;</p>
            <?php 
                }
            ?>
                <div id="post-<?php echo $post->ID; ?>" <?php post_class('post'); ?>>
                    <div class="entry-content">
                        <h2 class="caption">
                            <?php
                                if( $post -> post_status == 'publish' ){
                                    ?><a href="<?php echo get_permalink( $post -> ID )?> " title="<?php echo __( 'Permalink to ' , 'cosmotheme' ) . $post -> post_title; ?>" rel="bookmark"><?php echo $post -> post_title; ?></a><?php
                                }else{
                                    echo $post -> post_title;
                                }
                            ?>
                        </h2>
                        <div class="entry-meta">
                            <ul>
                                <?php if(options::logic( 'upload' , 'enb_edit_delete' ) && is_user_logged_in() && $post->post_author == get_current_user_id() && is_numeric(options::get_value( 'upload' , 'post_item_page' ))){ ?> 
                                    <li class="edit_post" title="<?php _e('Edit post','cosmotheme') ?>"><a href="<?php  echo add_query_arg( 'post', $post->ID, get_page_link(options::get_value( 'upload' , 'post_item_page' ))  ) ;  ?>"  ><?php echo _e('Edit','cosmotheme'); ?></a></li>    
                                <?php }   ?>
                                <?php if( options::logic( 'upload' , 'enb_edit_delete' )  && is_user_logged_in() && $post->post_author == get_current_user_id() ){  
                                    $confirm_delete = __('Confirm to delete this post.','cosmotheme');
                                ?>
                                <li class="delete_post" title="<?php _e('Remove post','cosmotheme') ?>"><a href="javascript:void(0)" onclick="if(confirm('<?php echo $confirm_delete; ?> ')){ removePost('<?php echo $post->ID; ?>','<?php echo home_url() ?>');}" ><?php echo _e('Delete','cosmotheme'); ?></a></li>
                                <?php  } ?>    
                            </ul>
                        </div>
                        <div class="excerpt bottom-separator"> 
                            <?php echo the_excerpt(); ?>
                        </div>
                    </div>
                </div>
            <?php
            }
        
            get_template_part('pagination');
            
        }else{
            get_template_part('loop', '404');
        }
    }
    
    function like( $paginate = true ) {
        
        global $wp_query;
        $uid = get_current_user_id();
        $template = 'front_page';
        $voted_posts = get_user_meta( $uid, ZIP_NAME.'_voted_posts',true );


        if(is_array($voted_posts) && sizeof($voted_posts)){

            if ((int) get_query_var('paged') > 0) {
                $paged = get_query_var('paged');
            } else {
                if ((int) get_query_var('page') > 0) {
                    $paged = get_query_var('page');
                } else {
                    $paged = 1;
                }
            }

            $wp_query = new WP_Query( array( 'post_type' => array( 'post', 'page'), 
                                            'post_status' => 'publish' , 
                                            'post__in' => $voted_posts, 
                                            'fp_type' => 'like', 
                                            'paged' => $paged  ) );

            
            $grid = tools::is_grid($template, '_new');

            if($grid){
                $grid_list_class = ' grid_view horizontal-posts' ;
            }else{
                $grid_list_class = ' list_view vertical-posts';
            }

            $home_url = home_url();
            $news = array('fp_type' => "news");
            $news_url = add_query_arg($news, $home_url);

            

            echo '<div class="row no-overflow">';                    
            echo '<div class="'.tools::primary_class( 0 , 'front_page', $return_just_class = true ).' relative" >';
            
            echo '<h2 class="content-title twelve columns  front_page">'.__('My liked posts','cosmotheme').'</h2>';
            
            echo '</div>';
            echo '</div>';
            
            
            /* content */
            self::loop('front_page', '_like' , $paginate );
            
        }else{

            echo '<h2 class="content-title twelve columns  front_page">'.__('Nothing found.','cosmotheme').'</h2>';
        }

       
    }

   
    
    function filter_where( $where = '' ) {
        global $wpdb;
        if( self::$post_id > 0 ){
            $where .= " AND  ".$wpdb->prefix."posts.ID < " . self::$post_id;
        }
        return $where;
    }
        
    function random_posts($no_ajax = false) {
        global $wp_query;
        if ((int) get_query_var('paged') > 0) {
            $paged = get_query_var('paged');
        } else {
            if ((int) get_query_var('page') > 0) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
        }

        $wp_query = new WP_Query(array('post_status' => 'publish', 'post_type' => 'post', 'posts_per_page' => 1, 'orderby' => 'rand', 'paged' => $paged));

        if ($wp_query->found_posts > 0) {
            $k = 0;
            foreach ($wp_query->posts as $post) {
                $wp_query->the_post();
                $result = get_permalink($post->ID);
            }
        }

        if (isset($no_ajax) && $no_ajax) {
            return $result;
        } else {
            echo $result;
            exit;
        }
    }
    function new_hot_posts( $p = true ) {
        if (options::logic('front_page', 'order')) {
            post::fnew_posts();
            echo '<p class="delimiter types">&nbsp;</p>';
            post::fhot_posts();
        } else {
            post::fhot_posts();
            echo '<p class="delimiter types">&nbsp;</p>';
            post::fnew_posts();
        }
    }
    function fnew_posts(){
        global $wp_query;
        if ((int) get_query_var('paged') > 0) {
            $paged = get_query_var('paged');
        } else {
            if ((int) get_query_var('page') > 0) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
        }
        
        if( options::logic( 'front_page' , 'enb_new_pg' ) ){
            $wp_query = new WP_Query(array('post_status' => 'publish', 'post_type' => 'post', 'paged' => $paged, 'fp_type' => 'news' , 'posts_per_page' => options::get_value('front_page', 'new_per_page')));
        } else {    
            $wp_query = new WP_Query(array('post_status' => 'publish', 'post_type' => 'post', 'fp_type' => 'news', 'posts_per_page' => options::get_value('front_page', 'new_per_page')));
        }

        $grid = tools::is_grid('front_page', '_new');
        if($grid){
            $grid_list_class = ' grid_view horizontal-posts' ;
        }else{
            $grid_list_class = ' list_view vertical-posts';
        }

        $home_url = home_url();
        $news = array('fp_type' => "news");
        $news_url = add_query_arg($news, $home_url);

        $words = explode(' ', options::get_value('front_page', 'new_label'));
        $label = '<strong><a href="' . $news_url . '">';
        $set = true;
        foreach ($words as $key => $word) {
            if ($key == options::get_value('front_page', 'new_nr_words')) {
                $label .= '</a></strong>';
                $set = false;
            }

            $label .= ' ' . $word;
        }

        if ($set) {
            $label .= '</a></strong>';
        }

        echo '<div class="row no-overflow">';                    
        echo '<div class="'.tools::primary_class( 0 , 'front_page', $return_just_class = true ).' relative" >';

        echo '<h2 class="content-title  '.tools::primary_class( 0 , 'front_page', $return_just_class = true ).'">' . $label . '</h2>';

        echo '</div>';
        echo '</div>';

        //echo '<div  class="row '.$grid_list_class . ' ">';

        /* content */
        self::loop('front_page', '_new', options::logic( 'front_page' , 'enb_new_pg' ) );

        //echo '</div>';
    }
    function new_posts( $paginate = true ) {
        global $wp_query;
        if ((int) get_query_var('paged') > 0) {
            $paged = get_query_var('paged');
        } else {
            if ((int) get_query_var('page') > 0) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
        }
        
        if ( options::logic( 'front_page' , 'enb_new_pg' ) && (options::get_value( 'front_page' , 'type' ) == 'new_posts' || options::get_value( 'front_page' , 'type' ) == 'new_hot_posts' ) ) {
            $wp_query = new WP_Query(array('post_status' => 'publish', 'post_type' => 'post', 'paged' => $paged , 'fp_type' => 'news' , 'posts_per_page' => options::get_value('front_page', 'new_per_page')));
        } else {    
            if( isset( $_GET['fp_type']) && $_GET['fp_type'] == 'news' ){
                $wp_query = new WP_Query(array('post_status' => 'publish', 'post_type' => 'post', 'paged' => $paged , 'fp_type' => 'news' ));
            }else{
                $wp_query = new WP_Query(array('post_status' => 'publish', 'post_type' => 'post', 'fp_type' => 'news' , 'posts_per_page' => options::get_value('front_page', 'new_per_page')));
            }
        }

        $grid = tools::is_grid('front_page', '_new');

        if($grid){
            $grid_list_class = ' grid_view horizontal-posts' ;
        }else{
            $grid_list_class = ' list_view vertical-posts';
        }

        $home_url = home_url();
        $news = array('fp_type' => "news");
        $news_url = add_query_arg($news, $home_url);

        $words = explode(' ', options::get_value('front_page', 'new_label'));
        $label = '<strong><a href="' . $news_url . '">';
        $set = true;
        foreach ($words as $key => $word) {
            if ($key == options::get_value('front_page', 'new_nr_words')) {
                $label .= '</a></strong>';
                $set = false;
            }

            $label .= ' ' . $word;
        }

        if ($set) {
            $label .= '</a></strong>';
        }

        echo '<div class="row no-overflow">';                    
        echo '<div class="'.tools::primary_class( 0 , 'front_page', $return_just_class = true ).' relative" >';
        
        echo '<h2 class="content-title  '.tools::primary_class( 0 , 'front_page', $return_just_class = true ).'">' . $label . '</h2>';
        
        echo '</div>';
        echo '</div>';
        
        //echo '<div  class="row '.$grid_list_class . ' ">';
        /* content */
        if( isset( $_GET['fp_type']) && $_GET['fp_type'] == 'news' ){
            self::loop('front_page', '_new' , $paginate );
        }else{
            self::loop('front_page', '_new' , options::logic( 'front_page' , 'enb_new_pg' ) );
        }
        //echo '</div>';

       
    }
    
    function fhot_posts( ) {
        global $wp_query;
        if ((int) get_query_var('paged') > 0) {
            $paged = get_query_var('paged');
        } else {
            if ((int) get_query_var('page') > 0) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
        }
        
        if ( options::logic( 'front_page' , 'enb_hot_pg' ) ) {
            $wp_query = new WP_Query(array(
                        'post_status' => 'publish',
                        'paged' => $paged,
                        'posts_per_page' => options::get_value('front_page', 'hot_per_page'),
                        'meta_key' => 'hot_date',
                        'orderby' => 'meta_value_num',
                        'fp_type' => 'hot',
                        'meta_query' => array(
                            array(
                                'key' => 'nr_like',
                                'value' => options::get_value('general', 'min_likes'),
                                'compare' => '>=',
                                'type' => 'numeric',
                            )),
                        'order' => 'DESC'));
        }else{
            $wp_query = new WP_Query(array(
                        'post_status' => 'publish',
                        'posts_per_page' => options::get_value('front_page', 'hot_per_page'),
                        'meta_key' => 'hot_date',
                        'orderby' => 'meta_value_num',
                        'fp_type' => 'hot',
                        'meta_query' => array(
                            array(
                                'key' => 'nr_like',
                                'value' => options::get_value('general', 'min_likes'),
                                'compare' => '>=',
                                'type' => 'numeric',
                            )),
                        'order' => 'DESC'));
        }

        $grid = tools::is_grid('front_page', '_hot');
        
        if($grid){
            $grid_list_class = ' grid_view horizontal-posts' ;
        }else{
            $grid_list_class = ' list_view vertical-posts';
        }

        $home_url = home_url();
        $hot = array('fp_type' => "hot");
        $hot_url = add_query_arg($hot, $home_url);

        $news = array('fp_type' => "news");
        $news_url = add_query_arg($news, $home_url);

        $words = explode(' ', options::get_value('front_page', 'hot_label'));
        $label = '<strong><a href="' . $hot_url . '">';
        $set = true;
        foreach ($words as $key => $word) {
            if ($key == options::get_value('front_page', 'hot_nr_words')) {
                $label .= '</a></strong>';
                $set = false;
            }

            $label .= ' ' . $word;
        }

        if ($set) {
            $label .= '</a></strong>';
        }

        echo '<div class="row no-overflow">';                    
        echo '<div class="'.tools::primary_class( 0 , 'front_page', $return_just_class = true ).' relative" >';

        echo '<h2 class="content-title  '.tools::primary_class( 0 , 'front_page', $return_just_class = true ).'">' . $label . '</h2>';

        echo '</div>';
        echo '</div>';

        //echo '<div  class="row '.$grid_list_class . ' ">';


        /* content */
        self::loop( 'front_page' , '_hot' , options::logic( 'front_page' , 'enb_hot_pg' ) );

        //echo '</div>';
    }
    
    function hot_posts( $paginate = true ) {
        global $wp_query;
        if ((int) get_query_var('paged') > 0) {
            $paged = get_query_var('paged');
        } else {
            if ((int) get_query_var('page') > 0) {
                $paged = get_query_var('page');
            } else {
                $paged = 1;
            }
        }
        
        if ( options::logic( 'front_page' , 'enb_hot_pg' ) && (options::get_value( 'front_page' , 'type' ) == 'hot_posts' || options::get_value( 'front_page' , 'type' ) == 'new_hot_posts' ) ) {
            $wp_query = new WP_Query(array(
                    'post_status' => 'publish',
                    'paged' => $paged,
                    'posts_per_page' => options::get_value('front_page', 'hot_per_page'),
                    'meta_key' => 'hot_date',
                    'orderby' => 'meta_value_num',
                    'fp_type' => 'hot',
                    'meta_query' => array(
                        array(
                            'key' => 'nr_like',
                            'value' => options::get_value('general', 'min_likes'),
                            'compare' => '>=',
                            'type' => 'numeric',
                        )),
                    'order' => 'DESC'));
        }else{
            if( isset( $_GET['fp_type']) && $_GET['fp_type'] == 'hot' ){
                $wp_query = new WP_Query(array(
                        'post_status' => 'publish',
                        'paged' => $paged,
                        'meta_key' => 'hot_date',
                        'orderby' => 'meta_value_num',
                        'fp_type' => 'hot',
                        'meta_query' => array(
                            array(
                                'key' => 'nr_like',
                                'value' => options::get_value('general', 'min_likes'),
                                'compare' => '>=',
                                'type' => 'numeric',
                            )),
                        'order' => 'DESC'));
            }else{
                $wp_query = new WP_Query(array(
                        'post_status' => 'publish',
                        'paged' => $paged,
                        'posts_per_page' => options::get_value('front_page', 'hot_per_page'),
                        'meta_key' => 'hot_date',
                        'orderby' => 'meta_value_num',
                        'fp_type' => 'hot',
                        'meta_query' => array(
                            array(
                                'key' => 'nr_like',
                                'value' => options::get_value('general', 'min_likes'),
                                'compare' => '>=',
                                'type' => 'numeric',
                            )),
                        'order' => 'DESC'));
            }
        }

        $grid = tools::is_grid('front_page', '_hot');
        if($grid){
            $grid_list_class = ' grid_view horizontal-posts' ;
        }else{
            $grid_list_class = ' list_view vertical-posts';
        }

        $home_url = home_url();
        $hot = array('fp_type' => "hot");
        $hot_url = add_query_arg($hot, $home_url);

        $news = array('fp_type' => "news");
        $news_url = add_query_arg($news, $home_url);

        $words = explode(' ', options::get_value('front_page', 'hot_label'));
        $label = '<strong><a href="' . $hot_url . '">';
        $set = true;
        foreach ($words as $key => $word) {
            if ($key == options::get_value('front_page', 'hot_nr_words')) {
                $label .= '</a></strong>';
                $set = false;
            }

            $label .= ' ' . $word;
        }

        if ($set) {
            $label .= '</a></strong>';
        }

        echo '<div class="row no-overflow">';                    
        echo '<div class="'.tools::primary_class( 0 , 'front_page', $return_just_class = true ).' relative" >';
        
        echo '<h2 class="content-title  '.tools::primary_class( 0 , 'front_page', $return_just_class = true ).'">' . $label . '</h2>';
        
        echo '</div>';
        echo '</div>';
        
        //echo '<div  class="row '.$grid_list_class . ' ">';

        /* content */
        if( isset( $_GET['fp_type']) && $_GET['fp_type'] == 'hot' ){
            self::loop( 'front_page' , '_hot' , $paginate );
        }else{
            self::loop( 'front_page' , '_hot' , options::logic( 'front_page' , 'enb_hot_pg' ) ); 
        }

        //echo '</div>';
    }
        
    function search(){
        
        $query = isset( $_GET['params'] ) ? (array)json_decode( stripslashes( $_GET['params'] )) : exit;
        $query['s'] = isset( $_GET['query'] ) ? $_GET['query'] : exit;
        
        global $wp_query;
        $result = array();
        $result['query'] = $query['s'];
        
        $wp_query = new WP_Query( $query );
        
        if( $wp_query -> have_posts() ){
            foreach( $wp_query -> posts as $post ){
                $result['suggestions'][] = $post -> post_title;
                $result['data'][] =  $post -> ID;
            }
        }
        
        echo json_encode( $result );
        exit();
    }
    
    function list_view($post, $template = 'blog_page') {
            $template_content_class = tools::primary_class( 0 , $template, $return_just_class = true );
            if($template_content_class == 'twelve columns'){
                $full_width = true;
                if(post::is_feat_enabled($post->ID)){
                    $header_class = 'six columns';
                    $content_class = 'six columns';
                }else{
                    $header_class = 'twelve columns';
                    $content_class = 'twelve columns';
                }
                
            }else{
                $full_width = false;
                $header_class = 'nine columns';
                $content_class = 'nine columns';
                
            }

            $classes = tools::login_attr($post->ID, 'nsfw');
            $attr = tools::login_attr($post->ID, 'nsfw mosaic-overlay', get_permalink($post->ID));
            $size = 'tmedium';
            $s = image::asize( image::size( $post->ID , $template , $size ) );

            
            if( get_post_format( $post -> ID ) == 'video' ){
                $format = meta::get_meta( $post -> ID , 'format' );

                if( isset( $format['feat_id'] ) && !empty( $format['feat_id'] ) )
                  {
                    $video_id = $format['feat_id'];
                    $video_type = 'self_hosted';
                    if(isset($format['feat_url']) && post::isValidURL($format['feat_url']))
                      {
                        $vimeo_id = post::get_vimeo_video_id( $format['feat_url'] );
                        $youtube_id = post::get_youtube_video_id( $format['feat_url'] );
                        
                        if( $vimeo_id != '0' ){
                          $video_type = 'vimeo';
                          $video_id = $vimeo_id;
                        }

                        if( $youtube_id != '0' ){
                          $video_type = 'youtube';
                          $video_id = $youtube_id;
                        }
                      }

                    if(isset($video_type) && isset($video_id) && is_user_logged_in () ){
                        if($video_type == 'self_hosted'){
                            $onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                        }else{
                            $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                        }    
                        
                    }else{
                        $meta = meta::get_meta( $post -> ID  , 'settings' );
                        if( isset( $meta['safe'] ) ){
                            if( !meta::logic( $post , 'settings' , 'safe' ) ){      
                                $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }
                        }else{
                            if($video_type == 'self_hosted'){
                                $onclick = 'playVideo("'.urlencode(wp_get_attachment_url($video_id)).'","'.$video_type.'",jQuery(this),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }else{
                                $onclick = 'playVideo("'.$video_id.'","'.$video_type.'",jQuery(this),jQuery(this).parent().width(),jQuery(this).parent().width()/1.37)';
                            }    
                        }   
                    }
                }
            }
        ?>
        <article class="post row big-post">
            <?php if(!$full_width){ ?>
            <div class="row bottom-separator">

            <?php } ?>
                <?php if(post::is_feat_enabled($post->ID)){ ?>
                <header   <?php post_class('post ' . $header_class, $post->ID); ?>>
                    <div class="readmore featimg" <?php if(isset($onclick)){ echo "onclick=".$onclick; }?>>
                        <div class="img">
                        <?php
                            if (has_post_thumbnail($post->ID)) {
                                $src = image::thumbnail($post->ID, $template, $size);
                                $caption = image::caption($post->ID);
                                

                                if (strlen($classes)) {
                                    ?>

                                    <a <?php echo $attr; ?>>
                                        <?php
                                            if ( get_post_format($post->ID) != 'video' ) {
                                                ?><div class="details"><?php _e('Read more', 'cosmotheme'); ?> <i class="cosmo-arrow">→</i></div><?php
                                            }
                                        ?>
                                    </a>
                                    <div class="format">&nbsp;</div>
                                    <?php echo image::mis($post->ID, $template, $size, 'safe image', 'nsfw'); ?>
                                    <?php
                                    if (options::logic('styling', 'stripes')) {
                                        ?><div class="stripes" >&nbsp;</div><?php
                                    }
                                    ?>
                                    <?php
                                    if (get_post_format($post->ID) == 'video') {
                                        echo '<div class="play">&nbsp;</div>';
                                    }
                                    ?>
                                    <?php
                                } else {
                                    ?>
                                    <a href="<?php if(!isset($onclick)){ echo get_permalink( $post -> ID ); }else{ echo 'javascript:void(0)'; } ?>" title="<?php echo $caption ?>" class="mosaic-overlay" >
                                        <?php
                                            if ( get_post_format($post->ID) != 'video' ) {
                                                ?><div class="details"><?php _e('Read more', 'cosmotheme'); ?> <i class="cosmo-arrow">→</i></div><?php
                                            }
                                        ?>
                                    </a>
                                    <div class="format">&nbsp;</div>
                                    <img src="<?php echo $src[0]; ?>" alt="<?php echo $caption; ?>" >
                                    <?php
                                    if (options::logic('styling', 'stripes')) {
                                        ?><div class="stripes" >&nbsp;</div><?php
                                    }
                                    ?>
                                    <?php
                                    if (get_post_format($post->ID) == 'video') {
                                        echo '<div class="play">&nbsp;</div>';
                                    }
                                    ?>
                                    <?php
                                }
                            } else{
                                if (strlen($classes)) {
                                    ?>
                                    <a <?php echo $attr; ?> >
                                        <?php
                                            if ( get_post_format($post->ID) != 'video' ) {
                                                ?><div class="details"><?php _e('Read more', 'cosmotheme'); ?> <i class="cosmo-arrow">→</i></div><?php
                                            }
                                        ?>
                                    </a>
                                    <div class="format">&nbsp;</div>
                                    <?php echo image::mis($post->ID, $template, $size, 'safe image', 'nsfw'); ?>
                                    <?php
                                    if (options::logic('styling', 'stripes')) {
                                        ?><div class="stripes" style="height: <?php echo $s[1]; ?>px">&nbsp;</div><?php
                                    }
                                    ?>
                                    <?php
                                    if (get_post_format($post->ID) == 'video') {
                                        echo '<div class="play">&nbsp;</div>';
                                    }
                                    ?>
                                    <?php
                                } else {
                                    ?>
                                    <a class="<?php echo $classes; ?> mosaic-overlay" href="<?php if(!isset($onclick)){ echo get_permalink( $post -> ID ); }else{ echo 'javascript:void(0)'; } ?>" >
                                        <?php
                                            if ( get_post_format($post->ID) != 'video' ) {
                                                ?><div class="details"><?php _e('Read more', 'cosmotheme'); ?> <i class="cosmo-arrow">→</i></div><?php
                                            }
                                        ?>
                                    </a>
                                    <div class="format">&nbsp;</div>
                                    <?php echo image::mis($post->ID, $template, $size, 'safe image', 'no.image'); ?>
                                    <?php
                                    if (options::logic('styling', 'stripes')) {
                                        ?><div class="stripes" >&nbsp;</div><?php
                                    }
                                    ?>
                                    <?php
                                    if (get_post_format($post->ID) == 'video') {
                                        echo '<div class="play">&nbsp;</div>';
                                    }
                                    ?>
                                    <?php
                                }
                            }
                        ?>    
                            
                        </div>
                    </div>
                </header>
                <?php } /*EOF enabled feat img*/  ?>
        <?php if(!$full_width){ ?>
            </div>
            <div class="row ">
                
        <?php } ?>
                <div class="<?php echo $content_class; ?>">
                    <h2 class="caption">
                        <?php like::content($post->ID, 2); ?>
                        <a <?php echo tools::login_attr($post->ID, 'nsfw', get_permalink($post->ID)) ?> title="<?php _e('Permalink to', 'cosmotheme'); ?> <?php echo $post->post_title; ?>" rel="bookmark"><?php echo $post->post_title; ?></a>
                    </h2>
                    <?php if ( options::logic('general', 'meta') ) { ?>
                    <div class="list-entry-meta clearfix">
                        <ul>
                            <li class="left">
                                <?php echo '<a href="' . get_author_posts_url( $post -> post_author ) . '" class="profile-pic">'  . cosmo_avatar( $post -> post_author , 32 , $default = DEFAULT_AVATAR_LOGIN ) . '</a>'; ?>
                            </li>
                            <li class="left delimit padded">
                                <b><?php echo get_the_author_meta( 'user_login',$post -> post_author);   ?></b><br>
                                <?php
                                if (options::logic('general', 'time')) {
                                     echo human_time_diff(get_the_time('U', $post->ID), current_time('timestamp')) . ' ' . __('ago', 'cosmotheme'); 
                                } else {
                                    echo date_i18n(get_option('date_format'), get_the_time('U', $post->ID)); 
                                }
                                ?>
                            </li>
                            <?php 
                                if ( function_exists( 'stats_get_csv' ) ){  
                                $views = stats_get_csv( 'postviews' , "&post_id=" . $post -> ID);    
                            ?>
                            <li class="left delimit padded">
                                <b><?php echo (int)$views[0]['views']; ?></b><br>
                                <?php
                                    if( (int)$views[0]['views'] == 1) {
                                        _e( 'view' , 'cosmotheme');
                                    }else{
                                        _e( 'views' , 'cosmotheme' );
                                    } 
                                ?>
                            </li>
                            <?php } ?>

                            <?php
                            if (comments_open($post->ID)) {
                                $comments_label = __('replies','cosmotheme');
                                if (options::logic('general', 'fb_comments')) {
                                    ?>
                                            <li class="left  padded" title="">
                                                <b>
                                                    <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                                        <fb:comments-count href="<?php echo get_permalink($post->ID) ?>"></fb:comments-count>
                                                    </a>
                                                </b><br>
                                                <?php echo $comments_label; ?>
                                            </li>
                                    <?php
                                } else {
                                    if(get_comments_number($post->ID) == 1){
                                        $comments_label = __('reply','cosmotheme');    
                                    }
                                    ?>
                                            <li class="left  padded" title="<?php echo get_comments_number($post->ID); ?> Comments">
                                                <b>
                                                    <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                                        <?php echo get_comments_number($post->ID) ?>
                                                    </a>
                                                </b><br>
                                                <?php echo $comments_label; ?>
                                            </li>
                                    <?php
                                }
                            }
                            ?>

                        </ul>    
                    </div>    
                    <?php } ?>
                    <div class="excerpt"> 
                        <?php
                            if ( is_user_logged_in() ) {
                                the_excerpt();
                            } else {
                                if ( !tools::is_nsfw( $post -> ID ) ) {
                                    the_excerpt();
                                }else{
                                    echo '<p>' . options::get_value( 'general' , 'nsfw_content' ) . '</p>';
                                }
                            }
                        ?>
                    </div>
                </div>  
            <?php if(!$full_width){ ?>
            </div>
                
                    
            <?php } ?>
        </article>            
        
        <?php
    }

    function grid_view($post, $template) {
            $nofeat_article_class = '';
            if(!post::is_feat_enabled($post->ID)){
                $nofeat_article_class = 'nofeat';    
            }
        ?>
        <article <?php post_class('three columns  small-post no-overflow ' . tools::nsfw_class($post->ID), $post->ID).' '.$nofeat_article_class; ?> >
            <?php if(post::is_feat_enabled($post->ID)){ ?>
            <div class="readmore no-overflow">
                 <?php
                $classes = tools::login_attr($post->ID, 'nsfw');
                $attr = tools::login_attr($post->ID, 'nsfw mosaic-overlay', get_permalink($post->ID));
                $size = 'tgrid';
                if (has_post_thumbnail($post->ID)) {


                    $src = image::thumbnail($post->ID, $template, $size);
                    $caption = image::caption($post->ID);

                    if (strlen($classes)) {
                        ?>
                        <a <?php echo $attr; ?>>
                            <div class="details"><?php _e('Read more', 'cosmotheme'); ?> <i class="cosmo-arrow">→</i></div>
                        </a>
                        <div class="format">&nbsp;</div>
                        <?php echo image::mis($post->ID, $template, $size, 'safe image', 'nsfw'); ?>
                        <?php
                        if (options::logic('styling', 'stripes')) {
                            ?><div class="stripes">&nbsp;</div><?php
                        }
                        ?>
                        <?php
                    } else {
                        ?>
                        <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $caption ?>" class="mosaic-overlay" >
                            <div class="details"><?php _e('Read more', 'cosmotheme'); ?> <i class="cosmo-arrow">→</i></div>
                        </a>
                        <div class="format">&nbsp;</div>
                        <img src="<?php echo $src[0]; ?>" alt="<?php echo $caption; ?>" >
                        <?php
                        if (options::logic('styling', 'stripes')) {
                            ?><div class="stripes">&nbsp;</div><?php
                        }
                        ?>
                        <?php
                    }
                } else {
                    if (strlen($classes)) {
                        ?>
                        <a <?php echo $attr; ?>>
                            <div class="details"><?php _e('Read more', 'cosmotheme'); ?> <i class="cosmo-arrow">→</i></div>
                        </a>
                        <div class="format">&nbsp;</div>
                        <?php echo image::mis( $post->ID , $template , $size , 'safe image' , 'nsfw' ); ?>
                        <?php
                        if (options::logic('styling', 'stripes')) {
                            ?><div class="stripes">&nbsp;</div><?php
                        }
                        ?>
                        <?php
                    } else {
                        ?>
                        <a class="<?php echo $classes; ?> mosaic-overlay" href="<?php echo get_permalink($post->ID); ?>">
                            <div class="details"><?php _e('Read more', 'cosmotheme'); ?> <i class="cosmo-arrow">→</i></div>
                        </a>
                        <div class="format">&nbsp;</div>
                        <?php echo image::mis( $post->ID , $template , $size , 'safe image', 'no.image' ); ?>
                        <?php
                        if (options::logic('styling', 'stripes')) {
                            ?><div class="stripes">&nbsp;</div><?php
                        }
                        ?>
                        <?php
                    }
                }
                ?>
            </div>
            <?php } /*EOF if feat is enabled*/?>


            <h3 class="clearfix">
                <?php like::content($post->ID, 2); ?>    
                <a <?php echo tools::login_attr($post->ID, 'nsfw readmore', get_permalink($post->ID)) ?> title="<?php _e('Permalink to', 'cosmotheme'); ?> <?php echo $post->post_title; ?>" rel="bookmark">
                    <?php echo $post->post_title; ?>
                </a>
                
            </h3>
            <footer class="entry-footer">
                <div class="excerpt">
                    <p>
                        <?php
                        $ln = 180;
                        if ( is_user_logged_in() ) {
                            if (!empty($post->post_excerpt)) {
                                if (strlen(strip_tags(strip_shortcodes($post->post_excerpt))) > $ln) {
                                    echo mb_substr(strip_tags(strip_shortcodes($post->post_excerpt)), 0, $ln) . '[...]';
                                } else {
                                    echo strip_tags(strip_shortcodes($post->post_excerpt));
                                }
                            } else {
                                if (strlen(strip_tags(strip_shortcodes($post->post_content))) > $ln) {
                                    echo mb_substr(strip_tags(strip_shortcodes($post->post_content)), 0, $ln) . '[...]';
                                } else {
                                    echo strip_tags(strip_shortcodes($post->post_content));
                                }
                            }
                        }else{
                            if ( !tools::is_nsfw( $post -> ID ) ) {
                                if (!empty($post->post_excerpt)) {
                                    if (strlen(strip_tags(strip_shortcodes($post->post_excerpt))) > $ln) {
                                        echo mb_substr(strip_tags(strip_shortcodes($post->post_excerpt)), 0, $ln) . '[...]';
                                    } else {
                                        echo strip_tags(strip_shortcodes($post->post_excerpt));
                                    }
                                } else {
                                    if (strlen(strip_tags(strip_shortcodes($post->post_content))) > $ln) {
                                        echo mb_substr(strip_tags(strip_shortcodes($post->post_content)), 0, $ln) . '[...]';
                                    } else {
                                        echo strip_tags(strip_shortcodes($post->post_content));
                                    }
                                }
                            }else{
                                echo options::get_value( 'general' , 'nsfw_content' );
                            }
                        }    
                        ?>
                    </p>
                </div>
            </footer>                
        </article>
        <?php
    }

    function loop($template, $side = '', $paginate = true) {

        
        global $wp_query;        
        //echo '<input type="hidden" id="query-' . $template . $side . '" value="' . urlencode( json_encode( $wp_query -> query ) ) . '" />';
        if ( count( $wp_query->posts) > 0 ) { 
            if( tools::is_grid( $template , $side ) ){  
?>
            <div class="row grid_view horizontal-posts  ">
                <?php 
                    self::loop_switch( $template , 1 ); 

                    if ($paginate) {
                        get_template_part('pagination');
                    }
                ?>
            </div>
<?php
            }else{
?>              
            <div class="row list-view vertical-posts ">
                <?php 
                    self::loop_switch( $template , 0 ); 

                    if ($paginate) {
                        get_template_part('pagination');
                    }    
                ?>    

            </div>
                
<?php
            }
            
        } else {
            get_template_part('loop', '404');
        }
    }

    function loop_switch( $template = '' , $grid = 1 ) {
        global $wp_query;
        if ( !empty( $template ) ) {
            $ajax = false;
        } else {
            $template = isset( $_POST['template'] ) && strlen( $_POST['template'] ) ? $_POST['template'] : exit();
            $query = isset( $_POST['query'] ) && !empty( $_POST['query'] ) ? (array)json_decode( urldecode( $_POST['query'] ) ) : exit();
            $query['post_status'] = 'publish';
            $wp_query = new WP_Query( $query );
            $grid = isset($_POST['grid']) ? (int)$_POST['grid'] : 1;
            $ajax = true;
        }
        
        $template   = str_replace( array( '_hot' , '_new' , '_like' ) , '' , $template );
        
        if( $grid == 1 ){
            $k = 1;
            $i = 1;
            $nr = $wp_query->post_count;

            if (layout::length(0, $template) == layout::$size['large']) {
                $div = 4;
                $div_class = 'twelve';
            } else {
                $div = 3;
                $div_class = 'nine';
            }

            foreach ($wp_query->posts as $post) {
                $wp_query->the_post();
                if ($i == 1) {
                    /*if (( $nr - $k ) < $div) {
                        $classes = 'class="last"';
                    } else {
                        $classes = '';
                    }*/
                    
                    echo '<div class="row grid-view  no-overflow horizontal-posts '.$div_class.'">';
                }

                self::grid_view($post, $template);

                if ($i % $div == 0) {
                    echo '</div>';
                    $i = 0;
                }
                $i++;
                $k++;
            }

            if ($i > 1) {
                echo '</div>';
            }
        }else{
            foreach ($wp_query->posts as $index => $post) {
                $wp_query->the_post();
               /*if ($index > 0) {
                    ?><p class="delimiter">&nbsp;</p><?php
                }*/

                self::list_view($post, $template);
            }
        }
        if( $ajax ){
            
            exit();
        }
    }
    
    function shmeta($post, $nav = true) {
        global $wp_query;
        ?>
        <div class="entry-meta">
            <ul>
                <?php if(options::logic( 'upload' , 'enb_edit_delete' ) && is_user_logged_in() && $post->post_author == get_current_user_id() && is_numeric(options::get_value( 'upload' , 'post_item_page' ))){ ?> 
                <li class="edit_post" title="<?php _e('Edit post','cosmotheme') ?>"><a href="<?php  echo add_query_arg( 'post', $post->ID, get_page_link(options::get_value( 'upload' , 'post_item_page' ))  ) ;  ?>"  ><?php echo _e('Edit','cosmotheme'); ?></a></li>    
                <?php }   ?>
                
                <li class="author" title="Author">
                    <a href="<?php echo get_author_posts_url($post->post_author) ?>">
        <?php echo get_the_author_meta('display_name', $post->post_author); ?>
                    </a>
                </li>
        <?php
        if (comments_open($post->ID)) {
            if (options::logic('general', 'fb_comments')) {
                ?>
                        <li class="cosmo-comments" title="">
                            <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                                <fb:comments-count href="<?php echo get_permalink($post->ID) ?>"></fb:comments-count>
                            </a>
                        </li>
                <?php
            } else {
                ?>
                        <li class="cosmo-comments" title="<?php echo get_comments_number($post->ID); ?> Comments">
                            <a <?php echo tools::login_attr($post->ID, 'nsfw', get_comments_link($post->ID)) ?>>
                <?php echo get_comments_number($post->ID) ?>
                            </a>
                        </li>
                <?php
            }
        }
        ?>

                <li class="cosmo-love"><?php like::content($post->ID, 3); ?></li>
            </ul>
        </div>
        <?php
    }

	function get_meta_view_style($post){
		$meta = meta::get_meta( $post -> ID , 'settings' );

		if( isset( $meta[ 'meta_view_style' ] ) && strlen( $meta[ 'meta_view_style' ] ) && !is_author() ){
			$meta_view_style =  meta::get_meta( $post -> ID , 'settings'  ); 
			$meta_view_style = $meta_view_style[ 'meta_view_style' ];
		}else{
			
			$meta_view_style =  options::get_value( 'general' , 'meta_view_style' ); 
			
		}
		return $meta_view_style;	
	}

	function show_meta_author_box($post){
		$meta = meta::get_meta( $post -> ID , 'settings' );

		  
		if( isset( $meta[ 'author' ] ) && strlen( $meta[ 'author' ] ) && !is_author() ){
			$show_author = meta::logic( $post , 'settings' , 'author' );
		}else{
			if( is_single() ){
				$show_author = options::logic( 'blog_post' , 'post_author_box' );
			}

			if( is_page() ){
				$show_author = options::logic( 'blog_post' , 'page_author_box' );
			}

			if( !( is_single() || is_page() ) ){
				$show_author = true;
			}
		}

		return $show_author;
	}
  
    function meta( $post ) {
        global $wp_query;
		
        if(self::get_meta_view_style($post) == 'vertical'){
            $the_class = ' two columns ';
        }else{
            $the_class = ' three ';
        }

		?>
        <div class=" <?php echo $the_class; ?> entry-meta">

            <?php if(self::show_meta_author_box($post) /*&& self::get_meta_view_style($post) == 'vertical'*/ ) { 
				$role = array( 
                                    10 => __( 'Administrator' , 'cosmotheme' ) ,
                                    7 => __( 'Editor' , 'cosmotheme' ) , 
                                    2 => __( 'Author' , 'cosmotheme' ) , 
                                    1 => __( 'Contributor' , 'cosmotheme'  ) , 
                                    0 => __( 'Subscriber' , 'cosmotheme' ), 
                                    '' => __( 'Subscriber' , 'cosmotheme' )
                                );
			?>  
			<div class="entry-author">
				<a href="<?php echo get_author_posts_url($post->post_author) ?>" class="profile-pic" ><?php echo cosmo_avatar( $post->post_author , 32 , $default = DEFAULT_AVATAR_LOGIN ); ?></a>
				<a href="<?php echo get_author_posts_url($post->post_author) ?>">
					<?php echo get_the_author_meta('display_name', $post->post_author); ?> <br/>
					<img src="<?php echo get_template_directory_uri(); ?>/images/mask.png" class="mask" alt="">
					<span><?php echo $role[ get_the_author_meta( 'user_level' , $post->post_author ) ]; ?></span>
				</a>
			</div>
			<?php } ?>	
            <ul>
                <?php if(options::logic( 'upload' , 'enb_edit_delete' ) && is_user_logged_in() && $post->post_author == get_current_user_id() && is_numeric(options::get_value( 'upload' , 'post_item_page' ))){ ?> 
                    <li class="edit_post" title="<?php _e('Edit post','cosmotheme') ?>"><a href="<?php  echo add_query_arg( 'post', $post->ID, get_page_link(options::get_value( 'upload' , 'post_item_page' ))  ) ;  ?>"  ><?php echo _e('Edit','cosmotheme'); ?></a></li>    
                <?php }   ?>
				<?php if( options::logic( 'upload' , 'enb_edit_delete' )  && is_user_logged_in() && $post->post_author == get_current_user_id() ){  
					$confirm_delete = __('Confirm to delete this post.','cosmotheme');
				?>
				<li class="delete_post" title="<?php _e('Remove post','cosmotheme') ?>"><a href="javascript:void(0)" onclick="if(confirm('<?php echo $confirm_delete; ?> ')){ removePost('<?php echo $post->ID; ?>','<?php echo home_url() ?>');}" ><?php echo _e('Delete','cosmotheme'); ?></a></li>
				<?php  } ?>
				<?php if(!self::show_meta_author_box($post) || self::get_meta_view_style($post) != 'vertical') { ?>
                <li class="author" title="<?php _e('Author','cosmotheme') ?>"><a href="<?php echo get_author_posts_url($post->post_author) ?>"><?php echo get_the_author_meta('display_name', $post->post_author); ?></a></li>
				<?php } ?>
                <li class="time">
                    <a href="javascript:void(0)">
                        <time>
                            <?php
                            if (options::logic('general', 'time')) {
                                echo human_time_diff(get_the_time('U', $post->ID), current_time('timestamp')) . ' ' . __('ago', 'cosmotheme');
                            } else {
                                echo date_i18n(get_option('date_format'), get_the_time('U', $post->ID));
                            }
                            ?>
                        </time>
                    </a>
                </li>
                <?php
                    if (comments_open($post->ID)) {
						$comments_label = __('comments','cosmotheme');  
                        if (options::logic('general', 'fb_comments')) {
                            ?><li class="cosmo-comments" title=""><a href="<?php echo get_comments_link($post->ID); ?>"> <fb:comments-count href="<?php echo get_permalink($post->ID) ?>"></fb:comments-count> <?php if(self::get_meta_view_style($post) == 'vertical'){ echo $comments_label; } ?> </a></li><?php
                        } else {
							
							if(get_comments_number($post->ID) == 1){
								$comments_label = __('comment','cosmotheme');
							}
                            ?><li class="cosmo-comments" title="<?php echo get_comments_number($post->ID); echo ' '.$comments_label; ?>"><a href="<?php echo get_comments_link($post->ID) ?>"> <?php echo get_comments_number($post->ID) ?> <?php if(self::get_meta_view_style($post) == 'vertical'){ echo $comments_label; } ?> </a></li><?php
                        }
                    }
                ?>
            </ul>
            <?php
            $tags = wp_get_post_terms($post->ID, 'post_tag');

            if (!empty($tags)) {
                ?>
                <ul class="b_tag">
                    <?php
                    foreach ($tags as $tag) {
                        $t = get_tag($tag);
                        echo '<li><a href="' . get_tag_link($tag) . '" rel="tags">' . $t->name . '</a></li>';
                    }
                    ?>
                </ul>
                <?php
            }
            ?>
            <?php
            $categories = wp_get_post_terms($post->ID, 'category');
            if (!empty($categories)) {
                ?>
                <ul class="category">
                    <?php
                    foreach ($categories as $category) {
                        $cat = get_category($category);
                        echo '<li><a href="' . get_category_link($category) . '">' . $cat->name . '</a></li>';
                    }
                    ?>
                </ul>
                <?php
            }
            ?>
        </div>
        <?php
        }

        function add_image_post(){
        	$response = array(  'image_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '',
        						'success_msg' => ''	);
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post! ','cosmotheme');	
        	}
        	if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'Title is required. ';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}
        	if(!isset($_POST['attachments']) || !is_array($_POST['attachments']) || !isset($_POST['featured']) || !is_numeric($_POST['featured']))
			  {
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['image_error'] = __('An image post must have a featured image. ','cosmotheme');
			  }
        	
        	
        	if($is_valid){
        		/*create post*/
        		$post_categories = array(1);
        		if(isset($_POST['category_id'])){
        			$post_categories = array($_POST['category_id']);
        		}
        			
        		$post_content = '';
        		if(isset($_POST['image_content'])){
        			$post_content = $_POST['image_content'];
        		}
        			
        		if(isset($_POST['post_id'])){
					$new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  /*add image as content*/
				}else{
					$new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  /*add image as content*/
				}
        			
				    
			    if(is_numeric($new_post))
				  {
		       		$attachments = get_children( array('post_parent' => $new_post, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID') );
					foreach ($attachments as $index => $id) {
					  $attachment = $index;
					} 
					foreach($_POST['attachments'] as $index=>$imageid)
					  {
						if($imageid==$_POST['featured'])
						  {
							  set_post_thumbnail($new_post, $imageid);
							  unset($_POST['attachments'][$index]);
						  }
						$attachment_post=get_post($imageid);
						$attachment_post->post_parent=$new_post;
						wp_update_post($attachment_post);
					  }
					
					if(isset($_POST['nsfw'])){
						$settings_meta = array(	  "safe"=>  "yes");
						meta::set_meta( $new_post , 'settings' , $settings_meta );
					}else{
						$settings_meta = array(	  "safe"=>  "yes");
						delete_post_meta($new_post, 'settings', $settings_meta );
					}	
						
					/*add source meta data*/
					if(isset($_POST['source']) && trim($_POST['source']) != ''){
					  $settings_meta = array(	  "post_source"=>  $_POST['source']);
					  meta::set_meta( $new_post , 'source' , $settings_meta );	
					}else{
						$settings_meta = array(	  "post_source"=>  $_POST['source']);
						delete_post_meta($new_post, 'source', $settings_meta );
					}	
							
					/*add video url meta data*/
					$image_format_meta = array("type" => 'image', 'images'=>$_POST['attachments']);
					meta::set_meta( $new_post , 'format' , $image_format_meta );

					if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio') ){
						set_post_format( $new_post , $_POST['post_format']);
					}
						
					if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
						/*if post was publihed imediatelly then we will show the prmalink to the user*/
							
						$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
							
					}else{
							$response['success_msg'] = __('Success. Your post is awaiting moderation.','cosmotheme');
					}	
						$response['post_id'] = $new_post;
				   }	        		
        		}	
        	echo json_encode($response);
        	exit;
        }

		function add_file_post(){

			$response = array(  'image_error' => '',
								'file_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '',
        						'success_msg' => ''	);
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post! ','cosmotheme');	
        	}
            
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'Title is required. ';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}

			if(!isset($_POST['attachments'])){
        		$is_valid = false;	
        		$response['error_msg'] = 'File is required. ';
        		$response['file_error'] = __('File is required. ','cosmotheme');
        	}
        	
        		if($is_valid){
        			/*create post*/
        			$post_categories = array(1);
        			if(isset($_POST['category_id'])){
        				$post_categories = array($_POST['category_id']);
        			}
        			
        			$post_content = '';
        			if(isset($_POST['file_content'])){
        				$post_content = $_POST['file_content'];
        			}
        			
        			
                    if(isset($_POST['post_id'])){
						$new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  
					}else{
						$new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  
					}
                    
				    if(is_numeric($new_post))
					  {
						set_post_thumbnail($new_post, null);
						foreach($_POST['attachments'] as $index=>$attachid)
						  {
							if($attachid==$_POST['featured'])
							  {
								set_post_thumbnail($new_post, $attachid);
								unset($_POST['attachments'][$index]);
							  }
							$attachment_post=get_post($attachid);
							$attachment_post->post_parent=$new_post;
							wp_update_post($attachment_post);
						  }
						$file_url_meta = array(	  "link"=>  '', "type" => 'link', 'link_id' => $_POST['attachments']);
						meta::set_meta( $new_post , 'format' , $file_url_meta );
						
						if(isset($_POST['nsfw'])){
							$settings_meta = array(	  "safe"=>  "yes");
							meta::set_meta( $new_post , 'settings' , $settings_meta );
						}else{
							$settings_meta = array(	  "safe"=>  "yes");
							delete_post_meta($new_post, 'settings', $settings_meta );
						}	
						
						/*add source meta data*/
						if(isset($_POST['source']) && trim($_POST['source']) != ''){
						  $settings_meta = array(	  "post_source"=>  $_POST['source']);
						  meta::set_meta( $new_post , 'source' , $settings_meta );	
						}else{
							$settings_meta = array(	  "post_source"=>  $_POST['source']);
							delete_post_meta($new_post, 'source', $settings_meta );
						}	
													
						/*add file url meta data*/
						

						if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio' || $_POST['post_format'] == 'link') ){
							set_post_format( $new_post , $_POST['post_format']);
						}
						
						if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
							/*if post was publihed imediatelly then we will show the prmalink to the user*/
								
							$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
							
						}else{
							$response['success_msg'] = __('Success. Your post is awaiting moderation.','cosmotheme');
						}	
						$response['post_id'] = $new_post;
				    }	
				    
	        		
        		}	
        	echo json_encode($response);
        	exit;
		}

		function add_audio_post(){
			$response = array(  'image_error' => '',
								'audio_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '',
        						'success_msg' => ''	);
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post! ','cosmotheme');	
        	}
        	
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'Title is required. ';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}

			if(!isset($_POST['attachments'])){
        		$is_valid = false;	
        		$response['error_msg'] = 'Audio File is required. ';
        		$response['audio_error'] = __('Audio File is required. ','cosmotheme');
        	}
   	        	
        		if($is_valid){
        			/*create post*/
        			$post_categories = array(1);
        			if(isset($_POST['category_id'])){
        				$post_categories = array($_POST['category_id']);
        			}
        			
        			$post_content = '';
        			if(isset($_POST['audio_content'])){
        				$post_content = $_POST['audio_content'];
        			}

					if(isset($_POST['post_id'])){
						$new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  
					}else{
						$new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  
					}
                    
				    if(is_numeric($new_post))
					  {
						set_post_thumbnail($new_post, null);
						foreach($_POST['attachments'] as $index=>$attachid)
						  {
							if($attachid==$_POST['featured'])
							  {
								set_post_thumbnail($new_post, $attachid);
								unset($_POST['attachments'][$index]);
							  }
							$attachment_post=get_post($attachid);
							$attachment_post->post_parent=$new_post;
							wp_update_post($attachment_post);
						  }
						$audio_url_meta = array(	  "audio"=>  $_POST['attachments'], "type" => 'audio');
						meta::set_meta( $new_post , 'format' , $audio_url_meta );

						if(isset($_POST['nsfw'])){
							$settings_meta = array(	  "safe"=>  "yes");
							meta::set_meta( $new_post , 'settings' , $settings_meta );
						}else{
							$settings_meta = array(	  "safe"=>  "yes");
							delete_post_meta($new_post, 'settings', $settings_meta );
						}	
						
						/*add source meta data*/
						if(isset($_POST['source']) && trim($_POST['source']) != ''){
						  $settings_meta = array(	  "post_source"=>  $_POST['source']);
						  meta::set_meta( $new_post , 'source' , $settings_meta );	
						}else{
							$settings_meta = array(	  "post_source"=>  $_POST['source']);
							delete_post_meta($new_post, 'source', $settings_meta );
						}	
												
						if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio' || $_POST['post_format'] == 'link') ){
							set_post_format( $new_post , $_POST['post_format']);
						}
						
						if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
							/*if post was publihed imediatelly then we will show the prmalink to the user*/
								
							$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
							
						}else{
							$response['success_msg'] = __('Success. Your post is awaiting moderation.','cosmotheme');
						}	
						$response['post_id'] = $new_post;
				    }	
				    
	        		
        		}	
        	echo json_encode($response);
        	exit;
		}
        
        function add_text_post(){
        	$response = array(  'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '' );
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post!','cosmotheme');	
        	}
        	
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}
        	
        		if($is_valid){

	        			/*create post*/
        				/*$post_content = self::get_embeded_video($video_id,$video_type);*/
	        			$post_categories = array(1);
	        			//$response['video_error'] = $_POST['category_id'];
	        			if(isset($_POST['category_id'])){
	        				$post_categories = array($_POST['category_id']);
	        			}
	        			
	        			$post_content = '';
	        			if(isset($_POST['text_content'])){
	        				$post_content = $_POST['text_content'];
	        			}
	        			
                        if(isset($_POST['post_id'])){
                            $new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  
                        }else{
                            $new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  
                        }
                        
					    if(is_numeric($new_post)){	
						   
							
							if(isset($_POST['nsfw'])){
								$settings_meta = array(	  "safe"=>  "yes");
								meta::set_meta( $new_post , 'settings' , $settings_meta );
							}else{
								$settings_meta = array(	  "safe"=>  "yes");
								delete_post_meta($new_post, 'settings', $settings_meta );
							}
							
							/*add source meta data*/
						    if(isset($_POST['source']) && trim($_POST['source']) != ''){
							  $settings_meta = array(	  "post_source"=>  $_POST['source']);
							  meta::set_meta( $new_post , 'source' , $settings_meta );	
							}else{
								$settings_meta = array(	  "post_source"=>  $_POST['source']);
								delete_post_meta($new_post, 'source', $settings_meta );
							}	
						
							if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
								/*if post was publihed imediatelly then we will show the prmalink to the user*/
									
								$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
								
							}else{
								$response['success_msg'] = __('Success. Your post is awaiting moderation','cosmotheme');
							}	
							$response['post_id'] = $new_post;
					    }
				
        		}
        			
        	echo json_encode($response);
        	exit;
        	
        }
        
        function add_video_post(){
        	$response = array(  'video_error' => '',
        						'error_msg' => '',	
        						'title_error' => '',
        						'post_id' => 0,
        						'auth_error' => '' );
        	
        	
        	$is_valid = true;
        	
        	if(!is_user_logged_in()){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['auth_error'] = __('You must be logged in to submit a post!','cosmotheme');	
        	}
        	
            if(is_user_logged_in() && isset($_POST['post_id'])){
				$post_edit = get_post($_POST['post_id']);
				
				if(get_current_user_id() != $post_edit->post_author){
					$is_valid = false;	
					$response['error_msg'] = __('You are not the author of this post. ','cosmotheme');
					$response['title_error'] = __('You are not the author of this post. ','cosmotheme');
				}
			}
            
        	if(!isset($_POST['title']) || trim($_POST['title']) == ''){
        		$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['title_error'] = __('Title is required. ','cosmotheme');
        	}
        	
			if(!isset($_POST['attachments']) || !is_array($_POST['attachments']) || !isset($_POST['featured']) || !is_numeric($_POST['featured']))
			{
				$is_valid = false;	
        		$response['error_msg'] = 'error';
        		$response['video_error'] = __('A video post must have a featured video.','cosmotheme');
			}
        	
        	if($is_valid)
			  {
	        	/*create post*/
        		/*$post_content = self::get_embeded_video($video_id,$video_type);*/
	        	$post_categories = array(1);
	        	//$response['video_error'] = $_POST['category_id'];
	        	
	        	if(isset($_POST['category_id'])){
	        		$post_categories = array($_POST['category_id']);
	        	}
	        			
	        	$post_content = '';
	        	if(isset($_POST['video_content'])){
	        		$post_content = $_POST['video_content'];
	        	}
	        			
        				
                if(isset($_POST['post_id'])){
                  $new_post = self::create_new_post($_POST['title'], $_POST['tags'], $post_categories, $post_content, $_POST['post_id']);  
                }else{
                  $new_post = self::create_new_post($_POST['title'],$_POST['tags'],$post_categories,$post_content);  
                }
                    
				if(is_numeric($new_post))
				  {	
					if(isset($_POST['nsfw'])){
						$settings_meta = array(	  "safe"=>  "yes");
						meta::set_meta( $new_post , 'settings' , $settings_meta );
					}else{
						$settings_meta = array(	  "safe"=>  "yes");
						delete_post_meta($new_post, 'settings', $settings_meta );
					}	
							
					/*add source meta data*/
					if(isset($_POST['source']) && trim($_POST['source']) != ''){
					  $settings_meta = array(	  "post_source"=>  $_POST['source']);
					  meta::set_meta( $new_post , 'source' , $settings_meta );	
					}else{
						$settings_meta = array(	  "post_source"=>  $_POST['source']);
						delete_post_meta($new_post, 'source', $settings_meta );
					}	

					$featured_video_url=false;

					foreach($_POST['attachments'] as $index=>$videoid)
					  {
						if($videoid==$_POST['featured'])
						  {
							$featured_video_id=$videoid;
							unset($_POST['attachments'][$index]);
							if(isset($_POST['video_urls'][$videoid]) && post::isValidURL($_POST['video_urls'][$videoid]))
							  {
								set_post_thumbnail($new_post,$videoid);
								$featured_video_url=$_POST['video_urls'][$videoid];
								unset($_POST['video_urls'][$videoid]);
							  }
							else set_post_thumbnail($new_post, null);
							}
						 $attachment_post=get_post($videoid);
						 $attachment_post->post_parent=$new_post;
						 wp_update_post($attachment_post);
					  }
				
				  $video_format_meta=array("type"=>"video", "video_ids"=>$_POST['attachments'], "feat_id"=>$featured_video_id, "feat_url"=>$featured_video_url);
				  if(isset($_POST['video_urls']))
					$video_format_meta["video_urls"]=$_POST["video_urls"];
				  meta::set_meta( $new_post , 'format' , $video_format_meta );

				  if(isset($_POST['post_format']) && ($_POST['post_format'] == 'video' || $_POST['post_format'] == 'image' || $_POST['post_format'] == 'audio') ){
					set_post_format( $new_post , $_POST['post_format']);
				  }
									
				  if(options::get_value( 'upload' , 'default_posts_status' ) == 'publish'){
					/*if post was publihed imediatelly then we will show the prmalink to the user*/
									
					$response['success_msg'] = sprintf(__('You can check your post %s here%s.','cosmotheme'),'<a href="'.get_permalink($new_post).'">','</a>');
								
				  }else{
					  $response['success_msg'] = __('Success. Your post is awaiting moderation','cosmotheme');
				  }	
					  $response['post_id'] = $new_post;
				}
        			
        	}
        	        			
        	echo json_encode($response);
        	exit;
        }
        
       
        function get_embeded_video($video_id,$video_type,$autoplay = 0,$width = 570,$height = 414){
        	
        	$embeded_video = '';
        	if($video_type == 'youtube'){
        		$embeded_video	= '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$video_id.'?wmode=transparent&autoplay='.$autoplay.'" wmode="opaque" frameborder="0" allowfullscreen></iframe>';
        	}elseif($video_type == 'vimeo'){
        		$embeded_video	= '<iframe src="http://player.vimeo.com/video/'.$video_id.'?title=0&amp;autoplay='.$autoplay.'&amp;byline=0&amp;portrait=0" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe>';
        	}
        	
        	return $embeded_video;
        }
        
		function get_local_video($video_url, $width = 570, $height = 414, $autoplay = false ){
			
            $result = '';    
			
            if($autoplay){
                $auto_play = 'true';
            }else{
                $auto_play = 'false';
            }
            
			$result = do_shortcode('[video mp4="'.$video_url.'" width="'.$width.'" height="'.$height.'"  autoplay="'.$auto_play.'"]');
			
			return $result;	
		}
  
        function get_video_thumbnail($video_id,$video_type){
        	$thumbnail_url = '';
        	if($video_type == 'youtube'){
				$thumbnail_url = 'http://i1.ytimg.com/vi/'.$video_id.'/hqdefault.jpg';
        	}elseif($video_type == 'vimeo'){
        		
				$hash = wp_remote_get("http://vimeo.com/api/v2/video/$video_id.php");
				$hash = unserialize($hash['body']);
				
				$thumbnail_url = $hash[0]['thumbnail_large'];  
        	}
        	
        	return $thumbnail_url;
        }
        
    	function get_youtube_video_id($url){
	        /*
	         *   @param  string  $url    URL to be parsed, eg:  
	 		*  http://youtu.be/zc0s358b3Ys,  
	 		*  http://www.youtube.com/embed/zc0s358b3Ys
	 		*  http://www.youtube.com/watch?v=zc0s358b3Ys 
	 		*  
	 		*  returns
	 		*  */	
        	$id=0;
        	
        	/*if there is a slash at the en we will remove it*/
        	$url = rtrim($url, " /");
        	if(strpos($url, 'youtu')){
	        	$urls = parse_url($url); 
	     
			    /*expect url is http://youtu.be/abcd, where abcd is video iD*/
			    if(isset($urls['host']) && $urls['host'] == 'youtu.be'){  
			        $id = ltrim($urls['path'],'/'); 
			    } 
			    /*expect  url is http://www.youtube.com/embed/abcd*/ 
			    else if(strpos($urls['path'],'embed') == 1){  
			        $id = end(explode('/',$urls['path'])); 
			    } 
			     
			    /*expect url is http://www.youtube.com/watch?v=abcd */
			    else if( isset($urls['query']) ){ 
			        parse_str($urls['query']); 
			        $id = $v; 
			    }else{
					$id=0;
				} 
        	}	
			
			return $id;
        }
        
        function  get_vimeo_video_id($url){
        	/*if there is a slash at the en we will remove it*/
        	$url = rtrim($url, " /");
        	$id = 0;
        	if(strpos($url, 'vimeo')){
				$urls = parse_url($url); 
				if(isset($urls['host']) && $urls['host'] == 'vimeo.com'){  
					$id = ltrim($urls['path'],'/'); 
					if(!is_numeric($id) || $id < 0){
						$id = 0;
					}
				}else{
					$id = 0;
				} 
        	}	
			return $id;
		}
        

	    function isValidURL($url)
		{
			return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $url);
		}

        function create_new_post($post_title,$post_tags, $post_categories, $content = '', $post_id = 0 ){
        	$current_user = wp_get_current_user();

        	$post_status = options::get_value( 'upload' , 'default_posts_status' )	;
        	if($post_id == 0){
				$post_args = array(
		            'post_title' => $post_title,
		            'post_content' => $content ,
		            'post_status' => $post_status,
		            'post_type' => 'post',
					'post_author' => $current_user -> ID,
					'tags_input' => $post_tags,
					'post_category' => $post_categories
		        );
                
                $new_post = wp_insert_post($post_args);
        	}else{
                $updated_post = get_post($post_id);
        		$post_args = array(
        			'ID' => $post_id,	
		            'post_title' => $post_title,
		            'post_content' => $content ,
		            'post_status' => $post_status,
                    'comment_status'=> $updated_post -> comment_status,
		            'post_type' => 'post',
					'post_author' => $current_user -> ID,
					'tags_input' => $post_tags,
        			'post_category' => $post_categories
		        );
                
                $new_post = wp_update_post($post_args);
        	}    
	
	        
	        
			if($post_status == 'pending'){ /*we will notify admin via email if a this option was activated*/
				if(is_email(options::get_value( 'upload' , 'pending_email' ))){
					$tomail = options::get_value( 'upload' , 'pending_email' );
					$subject = __('A new post is awaiting your moderation','cosmotheme');
					$message = __('A new post is awaiting your moderation.','cosmotheme');
					$message .= ' ';
					$message .= sprintf(__('To moderate the post go to  %s ','cosmotheme'), home_url('/wp-admin/post.php?post='.$new_post.'&action=edit')) ;

					wp_mail($tomail, $subject , $message);

				}	
			}

	        return $new_post;
        }

		function remove_post(){
			if(isset($_POST['post_id']) && is_numeric($_POST['post_id'])){
				$post = get_post($_POST['post_id']);
				if(get_current_user_id() == $post->post_author){ echo 'ee';
					wp_delete_post($_POST['post_id']);
				}
			}  

			exit;
		}
        
        function get_source($post_id){
        	
        	$source = '';
  			$source_meta = meta::get_meta( $post_id , 'source' );
  			
  			if(is_array($source_meta) && sizeof($source_meta) && isset($source_meta['post_source']) && trim($source_meta['post_source']) != ''){
  				if(self::isValidURL($source_meta['post_source'])){
  					$source_url = $source_meta['post_source'];
        			if( !is_numeric(strpos($source_meta['post_source'], 'http')) ){ /*if the $source dos not contain http we will add it*/
						$source_url = 'http://'.$source_meta['post_source'];
					}
        			$source = '<div class="three columns entry-source"><span><a href="'.$source_url.'" target="_blank" >'.__('View source','cosmotheme').'</a></span></div>';
        		}else{
        			$source = '<div class="three columns entry-source"><span>'.__('Source:','cosmotheme').' '.$source_meta['post_source'].'</span></div>';
        		}
  			}else{
  				$source = ''; //'<div class="source no_source"><p>'.__('Unknown source','cosmotheme').'</p></div>';
  			}
  			
        
        			
  			return $source;      	
        }

		function get_attached_file($post_id){
        	
        	$attached_file = '';
  			$attached_file_meta = meta::get_meta( $post_id , 'format' );

  			
			if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['link_id']) && is_array($attached_file_meta['link_id'])){
				foreach($attached_file_meta['link_id'] as $file_id)
				  {
					$attachment_url = explode('/',wp_get_attachment_url($file_id));
					$file_name = '';
					if(sizeof($attachment_url)){
					  $file_name = $attachment_url[sizeof($attachment_url) - 1];
					}	
					$attached_file .= '<div class="attach">';
					$attached_file .= '	<a href="'.wp_get_attachment_url($file_id).'">'.$file_name.'</a>';
					$attached_file .= '</div>';
				  }
			}else if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['link_id']))
			  {
				$file_id=$attached_file_meta['link_id'];
				$attachment_url = explode('/',wp_get_attachment_url($file_id));
					$file_name = '';
					if(sizeof($attachment_url)){
					  $file_name = $attachment_url[sizeof($attachment_url) - 1];
					}	
					$attached_file .= '<div class="attach">';
					$attached_file .= '	<a href="'.wp_get_attachment_url($file_id).'">'.$file_name.'</a>';
					$attached_file .= '</div>';
			  }
  					
  			return $attached_file;      	
        }

		function get_audio_file($post_id){
        	$attached_file = '';
  			$attached_file_meta = meta::get_meta( $post_id , 'format' );
  			
			if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['audio']) && is_array($attached_file_meta['audio'])){

				foreach($attached_file_meta['audio'] as $audio_id)
				  {
					$attached_file .= '[audio:'.wp_get_attachment_url($audio_id).']';
				  }				
			}else if(is_array($attached_file_meta) && sizeof($attached_file_meta) && isset($attached_file_meta['audio']) && $attached_file_meta['audio'] != '' ){
			  $attached_file .= '[audio:'.$attached_file_meta['audio'].']';
			}
  					
  			return $attached_file;      	
        }
        
        function play_video($width=570, $height=414){
        	$result = '';	

            if(isset($_POST['width']) && is_numeric($_POST['width']) && isset($_POST['height']) && is_numeric($_POST['height'])){
                $width = $_POST['width'];
                $height = $_POST['height'];
            }

        	if(isset($_POST['video_id']) && isset($_POST['video_type']) && $_POST['video_type'] != 'self_hosted'){	
        		$result = self::get_embeded_video($_POST['video_id'],$_POST['video_type'],1,$width, $height);
        	}else{
                $video_url = urldecode($_POST['video_id']);
                $result = self::get_local_video($video_url, $width, $height, true );
            }	
        	
        	echo $result;
        	exit;
        }
        
        function list_tags($post_id){
            $tag_list = '';
            $tags = wp_get_post_terms($post_id, 'post_tag');

            if (!empty($tags)) {
                    $i = 1;
                    foreach ($tags as $tag) { 
                        if($i==1){
                            $tag_list .= $tag->name;
                        }else{
                            $tag_list .= ', '.$tag->name;
                        }    
                        $i++;
                    }
            }
            
            return $tag_list;
        }


        /*check if showing featured image on archive pages is enabled*/
        public function is_feat_enabled($post_id){
            
            $meta = meta::get_meta( $post_id , 'settings' );
            
            if(isset($meta['show_feat_on_archive']) && $meta['show_feat_on_archive'] == 'yes'){
            
                return true;
            }elseif(isset($meta['show_feat_on_archive']) && $meta['show_feat_on_archive'] == 'no'){
                return false;
            }else{
                if(options::get_value( 'blog_post' , 'show_feat_on_archive' ) == 'yes'){
                    return true;
                }else{
                    return false;
                }
            }
            
        }
    }
?>
