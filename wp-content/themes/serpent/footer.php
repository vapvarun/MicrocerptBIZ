<?php
    $footer_background_color = '';
    if( strlen( get_footer_bg_color() ) ){
        $footer_background_color = "background: " . get_footer_bg_color() . "; ";
    }
?>
        <footer id="colophon" role="contentinfo" data-role="footer" data-position="fixed" data-fullscreen="true" style="<?php echo $footer_background_color;?>">

            <?php
                ob_start();
                ob_clean();
                get_sidebar( 'footer-first' );
                $f1 = ob_get_clean();
                ob_start();
                ob_clean();
                get_sidebar( 'footer-second' );
                $f2 = ob_get_clean();
                ob_start();
                ob_clean();
                get_sidebar( 'footer-third' );
                $f3 = ob_get_clean();
                               

                if( strlen( $f1 . $f2 . $f3 ) ){
            ?>
            <div id="footerWidgets" class="row boxed">
                <div class="four columns">
                    <?php echo $f1; ?>
                </div>
                <div class="four columns">
                    <?php echo $f2; ?>
                </div>
                <div class="four columns">
                    <?php echo $f3; ?>
                </div>
            </div>
            <?php
                }
            ?>

            <div class="row boxed" id="footerCopyright" style="<?php echo $footer_background_color;?>">
                <div class="five columns"> 
                    <p class="copyright"><?php echo str_replace('%year%',date('Y') , options::get_value('general' , 'copy_right') ); ?></p>
                </div>
                <div class="seven columns">
                    <?php echo menu( 'footer_menu' , array( 'class' => 'right no-margin' , 'number-items' => options::get_value( 'menu' , 'footer' )  , 'current-class' => 'active','type' => 'category') ); ?>    
                   
                </div>
            </div>
            
        </footer>
                
        <?php
            if( options::logic( 'general' , 'enb_keyboard' ) ){
        ?>
                <div class="keyboard-demo" style="cursor:pointer;">
                    <img src="<?php echo get_template_directory_uri()?>/images/small-keyboard.png" alt="small_keyboard" />
                </div>
        <?php
            }
        ?>    
    </div>
    
    <div class="overlay">&nbsp;</div>
    <?php if(is_singular()){ ?>
        <script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>
        <script type="text/javascript">
            (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/plusone.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
            })();
        </script>
    <?php } ?>
    <?php wp_footer();?>
    </body> 
</html>