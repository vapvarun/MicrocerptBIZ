<?php get_header(); ?>
<?php
    get_ads('logo');
?>
<section id="main">
    <div class="row">
        <div id="entry-title" class="twelve columns relative">
        
            <?php
                if( have_posts () ){
                    ?><h2 class="content-title twelve columns  archive">
                        <?php _e( 'Author archives: ' , 'cosmotheme' ); echo get_cat_name( get_query_var('cat') ); ?>
                        <span class='vcard'>
                            <a class="url fn n" href="" title="<?php echo esc_attr( get_the_author_meta( 'display_name' , $post-> post_author ) ); ?>" rel="me">
                                <?php echo get_the_author_meta( 'display_name' , $post-> post_author ); ?>
                            </a>
                        </span>
                    </h2><?php

                }else{
                    ?><h2 class="content-title twelve columns  archive"><?php _e( 'Sorry, no posts found' , 'cosmotheme' ); ?></h2><?php

                }
            ?>
            
        
        </div>    
    </div>
    <div class="row">

        <?php layout::side( 'left' , 0 , 'author' ); ?>
        <div class="<?php echo tools::primary_class( 0 , 'author', $return_just_class = true ); ?>" id="primary">
            <?php post::loop( 'author' ); ?>
        </div>
        <?php layout::side( 'right' , 0 , 'author' ); ?>
    </div>
</section>
<?php get_footer(); ?>