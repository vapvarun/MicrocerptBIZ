</div>
<!-- /Container #end -->
</div>  <!-- /outer  #end -->
<?php templ_content_end(); // content end hooks?>
<?php get_template_part( 'footer_bottom' ); // footer bottom. ?>
 
 <?php templ_before_footer(); // content end hooks?>
 <div class="footer">
  <div class="footer_in">
	<div style="text-align: right">
		<div style="float: left">
		    <p class="copyright">&copy; 2011 <a href="<?php bloginfo('home'); ?>">
		      <?php bloginfo('name'); ?>
		      </a>. All Rights Reserved.</p>
		</div>
		<div class="footer_menu">
<a href="http://www.microcerpt.com/info/about-us/">About</a> · <a href="http://www.advertising.microcerpt.com/Networks/mediakit.html?user=streamside">Advertise</a> · <a href="http://www.microcerpt.com/contact/">Contact</a>
		</div>
	</div>
  </div>
</div> <!-- footer #end -->
<?php templ_after_footer(); // content end hooks?>

</div>
<?php wp_footer(); ?>
<?php echo (get_option('ga')) ? get_option('ga') : '' ?>
<?php templ_body_end(); // Body end hooks?>
	</body>
</html>