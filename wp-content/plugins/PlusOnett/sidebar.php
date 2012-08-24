<?php
/**
 * The Sidebar containing the Sidebar 1 and Sidebar 2 widget areas.
 */
?>
<?php templ_before_sidebar(); // before sidebar hooks?>
<?php
if(templ_is_layout('3_col_fix'))  ////Sidebar 3 column fixed
{
	templ_sidebar1('sidebar sidebar_3col_l left');
	templ_sidebar2('sidebar sidebar_3col_r right');	
}else
if(templ_is_layout('3_col_left'))  ////Sidebar 3 column left
{
?>
	<div class="sidebar sidebar_3col_merge_l left">
<?php
	templ_sidebar_2col_merge();
	templ_sidebar1('sidebar_3col_l_m left');
	templ_sidebar2('sidebar_3col_r_m right');
?>
	</div>
<?php
}else
if(templ_is_layout('3_col_right'))  ////Sidebar 3 column right
{
?>
	<div class="sidebar sidebar_3col_merge_r right">
<?php
	templ_sidebar_2col_merge();
	templ_sidebar1('sidebar_3col_l_m left');
	templ_sidebar2('sidebar_3col_r_m right');
?>
	</div>
<?php
}else
if(templ_is_layout('full_width'))  ////Sidebar Full width page
{
	
}else
if(templ_is_layout('2_col_right'))  ////Sidebar 2 column right
{
	echo '<div style="float: right; width: 174px;">'; // PARENT DIV START: adding this to sort out parent site menu
	echo '<div class="sidebar right"><div class="widget"><div class="textwidget"><div class="register_button" style="text-align: center; margin-bottom: 40px;">
	<input type="button" class="article_btn" onclick="window.location.href=\'/wp-signup.php\'" value="...microcerpt it">
	<small style="color: #B4C4DE; text-shadow: 1px 1px 1px #466395; font-size: 11px; text-align: center;">This section is for authors, writers, bloggers, poets, and publishers. Join now and start microcerpting. -------------------------- </small>
	</div></div></div>';

	/*
	if (get_current_blog_id() != 1 ) {
	echo '<div class="widget"><div class="textwidget">
		<h3>Author Menu</h3>
		<ul><li><a href="" style="text-decoration: none;">something</a></li></ul>
		</div></div>';
	}
	*/
	echo '<div class="widget">';
	sidebarlogin();
	echo '</div></div>';


//	if (get_current_blog_id() != 1 ) switch_to_blog(1);
	templ_sidebar1('sidebar right');		
//	if (get_current_blog_id() != 1 ) restore_current_blog(); 
	// parent site menu
	echo '<div class="sidebar right">';
	if (get_current_blog_id() != 1 ) {
		if( class_exists('Add_to_Any_Subscribe_Widget') ) { Add_to_Any_Subscribe_Widget::display(); echo '<br /><br />'; }
	}
	include (TEMPLATEPATH . '/menu_microcerpt.php');
	echo '</div>';
	
	echo '</div>'; // PARENT DIV END: adding this to sort out parent site menu
	
}
else  ////Sidebar 2 column left as default setting
{
	templ_sidebar1('sidebar left');
}
?>
<?php templ_after_sidebar(); // after sidebar hooks?>