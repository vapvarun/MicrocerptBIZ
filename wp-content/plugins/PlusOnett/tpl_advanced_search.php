<?php
/*
Template Name: Page - Advanced Search
*/
?>
<?php
add_action('wp_head','templ_header_tpl_advsearch');
function templ_header_tpl_advsearch()
{
	?>
	<script type="text/javascript" language="javascript">var rootfolderpath = '<?php echo bloginfo('template_directory');?>/images/';</script>
	<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/dhtmlgoodies_calendar.js"></script>
    <?php
}
?>
<?php get_header(); ?>
<div  class="<?php templ_content_css();?>" >
	<div class="content_top"></div>
	<div class="content_bg">
<!--  CONTENT AREA START -->

<?php if ( have_posts() ) : ?>
<?php while ( have_posts() ) : the_post(); ?>
<div class="entry">
  <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
    <div class="post-meta">
      <h1><?php the_title(); ?></h1>
     </div>
    <div class="post-content">
      <?php endwhile; ?>
      <?php endif; ?>
      
        <div class="post-content">
    	 <?php the_content(); ?>
    </div>
      
      
      <div id="advancedsearch">
        <h4> <?php _e('Search this website','templatic') ?></h4>
        <form method="get"  action="<?php bloginfo('url'); ?>" name="searchform" onsubmit="return sformcheck();">
          <div class="advanced_left">
            <p>
              <input class="adv_input" name="s" id="adv_s" type="text" onfocus="if(this.value=='Search') this.value='';" onblur="if(this.value=='') this.value='<?php _e('Search','templatic');?>';" value="<?php _e('Search','templatic');?>" />
            </p>
            <p> <label><?php _e('Category','templatic');?></label>
           <?php /*?> <?php
            $term = get_term( (int)$fval, CUSTOM_CATEGORY_TYPE1);
			$str = '<select name="'.$key.'" '.$val['extra'].'>';
			$args = array('taxonomy' => CUSTOM_CATEGORY_TYPE1);
			$all_categories = get_categories($args);
			foreach($all_categories as $key => $cat) 
			{
				$seled='';
				if($term->name==$cat->name){ $seled='selected="selected"';}
				$str .= '<option value="'.$cat->name.'" '.$seled.'>'.$cat->name.'</option>';	
			}
			echo $str .= '</select>';
			?><?php */?>
              <?php wp_dropdown_categories( array('name' => 'catdrop','orderby'=> 'name','show_option_all' => __('select category','templatic'), 'taxonomy'=>CUSTOM_CATEGORY_TYPE1) ); ?>
            </p>
            <p>
              <label><?php _e('Date','templatic');?></label>
                <input name="todate" type="text" class="textfield" />
                <img src="<?php echo bloginfo('template_directory');?>/images/cal.gif" alt="Calendar" class="adv_calendar" onclick="displayCalendar(document.searchform.todate,'yyyy-mm-dd',this)"  />
                
                
               <?php _e('<span>to</span>','templatic');?> 
                <input name="frmdate" type="text" class="textfield"  />
                <img src="<?php echo bloginfo('template_directory');?>/images/cal.gif" alt="Calendar"  class="adv_calendar" onclick="displayCalendar(document.searchform.frmdate,'yyyy-mm-dd',this)"  />
            </p>
            <p>
              <label>
                <?php _e('Author','templatic');?> </label>
                <input name="articleauthor" type="text" class="textfield"  />
                <span class="adv_author">
                <?php _e('Exact author','templatic');?>
                </span>
                <input name="exactyes" type="checkbox" value="1" class="checkbox" />
             
            </p>
          </div>
          <input type="submit" value="Submit" class="adv_submit" />
        </form>
      </div>
    </div>
  </div>
</div>


</div> <!-- content bg #end -->
    <div class="content_bottom"></div>
</div> <!-- content end -->

<script type="text/javascript" >
function sformcheck()
{
if(document.getElementById('adv_s').value=="")
{
	alert('<?php _e('Please enter word you want to search','templatic') ?>');
	document.getElementById('adv_s').focus();
	return false;
}
if(document.getElementById('adv_s').value=='<?php _e('Search','templatic');?>')
{
document.getElementById('adv_s').value = ' ';
}
return true;
}
</script>
<?php get_sidebar(); ?>
<?php get_footer(); ?>