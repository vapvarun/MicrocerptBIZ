<?php get_header(); ?>
<?php
    get_ads('logo');
?>
<section id="main">

    <div class="row">
        <div id="entry-title" class="twelve columns relative">
        
            <?php
                if( have_posts () ){
                    ?><h2 class="content-title twelve columns  search"><?php _e( 'Search results: ' , 'cosmotheme' ); echo get_cat_name( get_query_var('cat') ); ?></h2><?php

                }else{
                    ?><h2 class="content-title twelve columns  search"><?php _e( 'Sorry, no posts found' , 'cosmotheme' ); ?></h2><?php

                }
            ?>
            
        
        </div>    
    </div>

    <div class="row">

        <?php layout::side( 'left' , 0 , 'search' ); ?>
        <div class="<?php echo tools::primary_class( 0 , 'search', $return_just_class = true ); ?>" id="primary"> <!-- use here class from options -->
            <?php post::loop( 'search' ); ?>
        </div>
        
        <?php layout::side( 'right' , 0 , 'search' ); ?>
    </div>
</section>
<?php get_footer(); ?>

