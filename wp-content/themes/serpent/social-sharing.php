<?php
    /* social sharing  */
    if( meta::logic( $post , 'settings' , 'sharing' ) ){
?>      <div class="six columns share">
            <div class="left lmargin"> 
                <div class="share_button"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo get_permalink( $post -> ID ); ?>" data-text="<?php echo $post -> post_title; ?>" data-count="horizontal">Tweet</a></div>
                <div class="share_button"><g:plusone size="medium"  href="<?php echo get_permalink( $post -> ID ); ?>"></g:plusone></div>
                <div class="share_button"><iframe src="http://www.facebook.com/plugins/like.php?href=<?php echo urlencode( get_permalink( $post->ID ) ); ?>&amp;layout=button_count&amp;show_faces=false&amp;&amp;action=like&amp;colorscheme=light" scrolling="no" frameborder="0" height="20" width="109"></iframe></div>
                <div class="share_button"><a href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&media=<?php if(function_exists('the_post_thumbnail')) echo wp_get_attachment_url(get_post_thumbnail_id()); ?>&description=<?php echo get_the_title(); ?>" class="pin-it-button" always-show-count="true" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="<?php _e('Pin It','cosmotheme'); ?>" /></a></div>
                <div class="share_button"><script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"></script></div>
    			<div class="share_button"><a name="fb_share" class="share_button" style="display: inline-block;position: relative;top: -6px;" type="button" share_url="<?php echo get_permalink( $post->ID ); ?>"> <?php _e('Share','cosmotheme')?> </a>  </div>
            </div>
        </div>
<?php
    }
?>
