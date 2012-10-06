<?php
    remove_filter( 'pre_get_posts', 'cosmo_posts_per_archive' );
    get_header();
?>
<?php
    get_ads('logo');
?>
<section id="main">

    
    <?php if( options::get_value( 'front_page' , 'type' ) == 'page' ){  ?>
   <?php /*  <div class="row">
        <div id="entry-title" class="twelve columns relative">
       
            <h2 class="content-title">
                <?php like::content( options::get_value( 'front_page' , 'page' ) , 1 ); ?>
                <?php  // echo get_the_title(options::get_value( 'front_page' , 'page' )); 
		?>
            </h2>
       
        </div>    
    </div>   */ ?>
    <?php } ?>

    <div class="row">
        <?php $left = layout::side( 'left' , 0 , 'front_page' ); ?>

        <div class="<?php echo tools::primary_class( 0 , 'front_page', $return_just_class = true ); ?>" id="primary">
            <div id="content" role="main">

                <?php
                    /* if hot or new  */
                    if( isset( $_GET[ 'fp_type' ] ) ){ 
                        switch( $_GET[ 'fp_type' ] ){
                            case 'hot' : {
                                post::hot_posts();
                                break;
                            }
                            case 'news' : {
                                post::new_posts();
                                break;
                            }
                            
                            
                            default : {
                                
                                if(is_user_logged_in() ){
                                    if( $_GET['fp_type'] == 'like' ){
                                        post::like();
                                        break;
                                    }
                                }
                                if( options::get_value( 'front_page' , 'type' ) == 'hot_posts' ){
                                    post::hot_posts();
                                }
                                if( options::get_value( 'front_page' , 'type' ) == 'new_posts' ){
                                    post::new_posts();
                                }
                                if( options::get_value( 'front_page' , 'type' ) == 'new_hot_posts' ){
                                    post::new_hot_posts();
                                }
                                break;
                            }
                        }
                    }else{
                        /* if not set params for hot or new */
                        if( options::get_value( 'front_page' , 'type' ) != 'page' ){
                            if( options::get_value( 'front_page' , 'type' ) == 'hot_posts' ){
                                post::hot_posts( false );
                            }
                            if( options::get_value( 'front_page' , 'type' ) == 'new_posts' ){
                                post::new_posts( false );
                            }
                            if( options::get_value( 'front_page' , 'type' ) == 'new_hot_posts' ){
                                post::new_hot_posts( false );
                            }
                            $post_id = 0;
                        }else{

                            $wp_query = new WP_Query( array( 'page_id' => options::get_value( 'front_page' , 'page' ) ) );

                            if( $wp_query -> post_count > 0 ){
                                foreach( $wp_query -> posts as $post ){  
                                    $wp_query -> the_post();
                                    $post_id = $post -> ID;

                                    $classes = tools::login_attr( $post -> ID , 'nsfw' );
                                    $template = 'single';
                                    $attr = tools::login_attr( $post -> ID , 'nsfw mosaic-overlay' , get_permalink( $post -> ID ) );
                                    if( layout::length( $post_id , $template ) == layout::$size['large'] ){
                                        $size = 'tlarge';
                                    }else{
                                        $size = 'tmedium'; 
                                    }
                                    $s = image::asize( image::size( $post->ID , $template , $size ) );
                                    
                                    $zoom = false; 
                                    
                                    if( options::logic( 'general' , 'enb_featured' ) ){
                                        if ( has_post_thumbnail( $post -> ID ) && get_post_format( $post -> ID ) != 'video' ) {
                                            $src        = image::thumbnail( $post -> ID , $template , $size );
                                            $src_       = image::thumbnail( $post -> ID , $template , 'full' );
                                            $caption    = image::caption( $post -> ID );
                                            $zoom       = true;
                                        }
                                    } 

                ?>
                                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'post big-post single-post no_bg' , $post -> ID ); ?>>    
                                        
                                        <?php
                                            if( options::logic( 'general' , 'enb_featured' ) ){
                                                if ( has_post_thumbnail( $post -> ID ) && get_post_format( $post -> ID ) != 'video' ) {
                                                    $src = image::thumbnail( $post -> ID , $template , $size );
                                                    $caption = image::caption( $post -> ID );
                                        ?>

                                                    <header class="entry-header">
                                                        <div class=" readmore featimg">
                                                            <div class="img">
                                                                <?php
                                                                    echo '<img src="' . $src[0] . '" alt="' . $caption . '" >';
                                                                ?>
                                                                <div class="stripes">&nbsp;</div><!--Must put height equal to image-->
                                                            </div>
                                                        </div>
                                                    </header>
                                        <?php
                                                }
                                            }
                                        ?>

                                        <?php
                                        
                                            $meta_view_style = post::get_meta_view_style($post); 
                                        ?>
                                        <div class="entry-content <?php echo $meta_view_style; ?>">
                                        
                                            <div class="row">
                                                <?php
                                                    if( meta::logic( $post , 'settings' , 'meta' ) ){
                                                        post::meta( $post );
                                                    }
                                                ?>

                                                <?php
                                                    if(post::get_meta_view_style($post) == 'vertical' && meta::logic( $post , 'settings' , 'meta' )){
                                                        $content_class = ' seven columns ';
                                                    }else{
                                                        $content_class = ' nine ';
                                                    }
                                                ?>
                                                <div class="<?php echo $content_class;   ?>">
                                                    <?php the_content(); ?>
                                                </div>
                                            </div>    
                                        </div>

                                        <!-- footer -->
                                        <footer class="entry-footer">
                                            <div class="share">
                                                <?php get_template_part('social-sharing'); ?>
                                            </div>
                                            
                                        </footer>
                                    </article>                                    
                            
                            <?php 
                                    }  /*EOF for each*/
                                } /*EOF if have posts*/
                            ?>        

                <?php
                        }
                    }
                ?>
            </div>
            
        </div>

        <?php $left = layout::side( 'right' , 0 , 'front_page' ); ?>
    </div>
    
</section>
<?php get_footer(); ?>