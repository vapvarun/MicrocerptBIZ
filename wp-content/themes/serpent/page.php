<?php 
if( $post -> ID == options::get_value( 'general' , 'user_profile_page' ) ){
	get_template_part( 'user_profile_update' );
}
get_header(); 

?>
<section id="main">

    <?php
        while( have_posts () ){
            the_post();
            $post_id = $post -> ID;

            $classes = tools::login_attr( $post -> ID , 'nsfw' );
            $template = 'page';
            $attr = tools::login_attr( $post -> ID , 'nsfw mosaic-overlay' , get_permalink( $post -> ID ) );
            if( layout::length( $post_id , $template ) == layout::$size['large'] ){
                $size = 'tlarge';
            }else{
                $size = 'tmedium'; 
            }
            $s = image::asize( image::size( $post->ID , $template , $size ) );
            
            $zoom = false; 
            
            if( options::logic( 'general' , 'enb_featured' ) ){
                if ( has_post_thumbnail( $post -> ID ) ) {
                    $src        = image::thumbnail( $post -> ID , $template , $size );
                    $src_       = image::thumbnail( $post -> ID , $template , 'full' );
                    $caption    = image::caption( $post -> ID );
                    $zoom       = true;
                }
            }
    ?>
            
            <div class="row">
                <div id="entry-title" class="twelve columns relative">
                
                    <h2 class="content-title">
                        
                        <?php
                            if( (int)options::get_value( 'general' , 'my_posts_page' ) == $post_id ){
                                _e( 'My posts' , 'cosmotheme' );
                            }else{
                                like::content( $post->ID , 1 );
                                the_title();
                            }
                        ?>
                    </h2>
                    <nav class="hotkeys-meta">
                        <?php
                            $zclasses = ''; 
                            if( !( options::logic( 'general' , 'enb_lightbox' ) && $zoom )  ){
                                $zclasses = 'no-zoom';
                            }
                        ?>
                        <span class="nav-previous <?php echo $zclasses; ?>"><?php previous_post_link( '%link' , __('Previous', 'cosmotheme') ); ?></span>
                        <?php
                            if( options::logic( 'general' , 'enb_lightbox' ) && $zoom  ){
                        ?>
                                <span class="nav-zoom"><a href="<?php echo $src_[0]; ?>" title="<?php echo $caption;  ?>" rel="prettyPhoto-<?php echo $post -> ID; ?>"><?php _e( 'Full size' , 'cosmotheme' ); ?></a></span>
                        <?php
                            }
                        ?>
                        <span class="nav-next"><?php next_post_link( '%link' , __('Next', 'cosmotheme') ); ?></span>
                    </nav>
                
                </div>    
            </div>
                    
            <div class="row">

                <?php layout::side( 'left' , $post_id , 'page'); ?>

                <div id="primary" class="<?php echo tools::primary_class( $post_id , 'page', $return_just_class = true ); ?>" >
                    <div id="content" role="main">        
						<?php
                            if( (int)options::get_value( 'general' , 'my_posts_page' ) == $post_id ){
                        ?>
                                <article id="post-<?php the_ID(); ?>" <?php post_class( 'post my-posts big-post' , $post -> ID ); ?>>
                                
                                    <div class="list">
                                        <?php post::my_posts( get_current_user_id() ); ?>
                                    </div>
                                </article> 
                        <?php
                            }else{
                        ?>
                            
                        
                                <?php 
                                    if( $post_id == options::get_value( 'upload' , 'post_item_page' ) ){
                                        get_template_part( 'post_item' );
                                    }elseif( $post_id == options::get_value( 'general' , 'user_profile_page' ) ){
                                        get_template_part( 'user_profile' );
                                    }else{
                                        get_ads('logo');

                                ?>
                                
                                <article id="post-<?php the_ID(); ?>" <?php post_class( 'post  single-post no_bg' , $post -> ID ); ?>>    

                                    
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
                                                            <?php if (options::logic('styling', 'stripes')) { ?>
                                                            <div class="stripes">&nbsp;</div><!--Must put height equal to image-->
                                                            <?php } ?>
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
                                    <footer class="entry-footer">
                                        <div class="share">
                                            <?php get_template_part('social-sharing'); ?>
                                        </div>
                                        <?php
                                            if( strlen( options::get_value( 'advertisement' , 'content' ) ) > 0 ){
                                        ?>
                                                <div class="cosmo-ads zone-2">
                                                    <?php echo options::get_value( 'advertisement' , 'content' ); ?>
                                                </div>
                                        <?php
                                            }
                                        ?>
                                    </footer>
                                </article>

                                <p class="delimiter blank">&nbsp;</p>

                                <?php
                                    /* comments */
                                    if( comments_open() ){
                                        if( options::logic( 'general' , 'fb_comments' ) ){
                                            ?>
                                            <div id="comments">
                                                <h3 id="reply-title"><?php _e( 'Leave a reply' , 'cosmotheme' ); ?></h3>
                                                <p class="delimiter">&nbsp;</p>
                                                <fb:comments href="<?php the_permalink(); ?>" num_posts="5" width="430" height="120" reverse="true"></fb:comments>
                                            </div>
                                            <?php
                                        }else{
                                            comments_template( '', true );
                                        }
                                    }
                                }
                            ?>
                            
                        <?php
                            }
                        ?>
					</div>
                </div>
                
                <?php layout::side( 'right' , $post_id , 'page' ); ?>
            </div>
    <?php
        }
    ?>
</section>
<?php get_footer(); ?>