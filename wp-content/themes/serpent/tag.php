<?php get_header(); ?>
<?php
    get_ads('logo');
?>
<section id="main">
    
    <div class="row">
        <div id="entry-title" class="twelve columns relative">
        
            <?php
                if( have_posts () ){
                    ?><h2 class="content-title twelve columns tag"><?php _e( 'Tags archives' , 'cosmotheme' ); echo ': ';  echo  urldecode(get_query_var('tag')); ?></h2><?php

                }else{
                    ?><h2 class="content-title twelve columns archive"><?php _e( 'Sorry, no posts found' , 'cosmotheme' ); ?></h2><?php
                }
            ?>
            
        </div>    
    </div>

    <div class="row">

        <?php layout::side( 'left' , 0 , 'tag' ); ?>
        <div class="<?php echo tools::primary_class( 0 , 'tag', $return_just_class = true ); ?>" id="primary"> <!-- use here class from options -->
            <?php post::loop( 'tag' ); ?>
        </div>

        <?php layout::side( 'right' , 0 , 'tag' ); ?>
    </div>
</section>
<?php get_footer(); ?>
