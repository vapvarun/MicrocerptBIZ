<?php get_header(); ?>
<?php
    get_ads('logo');
?>
<section id="main">

    <div class="row">
        <div id="entry-title" class="twelve columns relative">
        
            <?php
                if( have_posts () ){
                    ?>
                    <h2 class="content-title twelve columns  archive">
                        <?php
                            if ( is_day() ) {
                                echo  __( 'Daily archives' , 'cosmotheme' ) . ': <span>' . get_the_date();
                            }else if ( is_month() ) {
                                echo  __( 'Monthly archives' , 'cosmotheme' ) . ': <span>' . get_the_date( 'F Y' );
                            }else if ( is_year() ) {
                                echo  __( 'Yearly archives' , 'cosmotheme' ) . ': <span>' . get_the_date( 'Y' ) ;
                            }else {
                                echo  __( 'Blog archives' , 'cosmotheme' ) ;
                            }
                        ?>

                    </h2><?php

                }else{
                    ?><h2 class="content-title twelve columns  search"><?php _e( 'Sorry, no posts found' , 'cosmotheme' ); ?></h2><?php

                }
            ?>
            
        
        </div>    
    </div>
    <div class="row">

        <?php layout::side( 'left' , 0 , 'archive' ); ?>
        <div class="<?php echo tools::primary_class( 0 , 'author', $return_just_class = true ); ?>" id="primary">
            <?php post::loop( 'archive' ); ?>
        </div>
        <?php layout::side( 'right' , 0 , 'archive' ); ?>
    </div>
</section>
<?php get_footer(); ?>
