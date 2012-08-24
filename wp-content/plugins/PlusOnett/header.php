<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
	<script type="text/javascript" src="<?php echo get_template_directory_uri() . '/disqus.js'; ?>"></script>
    <title><?php wp_title ( '|', true,'right' ); ?></title>
   <?php do_action('templ_head_meta');?>
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
    <link rel="stylesheet" type="text/css" media="screen" href="<?php echo get_template_directory_uri() . '/rss.css'; ?>" />
    <?php do_action('templ_head_css');?>
	<?php

	wp_enqueue_script('jquery');
	wp_enqueue_script('cycle', get_template_directory_uri() . '/js/jquery.cycle.all.min.js', 'jquery', false);
	wp_enqueue_script('cookie', get_template_directory_uri() . '/js/jquery.cookie.js', 'jquery', false);
	if ( is_singular() ) wp_enqueue_script( 'comment-reply' );
	wp_enqueue_script('script', get_template_directory_uri() . '/js/script.js', 'jquery', false);
	do_action('templ_head_js');
	wp_head();
	
	?>
	
	<?php // wp_enqueue_script( 'jquery' ) ?>
	<?php // wp_head(); ?>
	
<script type="text/javascript">
jQuery(document).ready(function(){
jQuery(".vote a").click(
function() {
var some = jQuery(this);
var thepost = jQuery(this).attr("post");
var theuser = jQuery(this).attr("user");
var thealready = jQuery(this).attr("already");
jQuery.post("<?php bloginfo('template_url'); ?>/vote.php", {user: theuser, post: thepost, already: thealready}, 
function(data) {
span_id='span#span_vote'+thepost;
a_span_id='span#a_span_vote'+thepost;
var votebox = ".vote"+thepost+" "+span_id;
var a_votebox = ".vote"+thepost+" "+a_span_id;
jQuery(votebox).text(data);
jQuery(a_votebox).text(data);


});
});
});	
</script>
<script language="javascript" type="text/javascript">
function voted_minus(id)
{
	
	document.getElementById('voted_minus_lnk'+id).style.display = 'none';
	document.getElementById('voted_plus_lnk'+id).style.display = '';
	//document.getElementById('vote_plus'+id).style.display = 'none';
	document.getElementById('vote_minus'+id).style.display = 'none';
}
function voted_minus_other(id)
{
	
	document.getElementById('voted_minus_lnk'+id).style.display = 'none';
	document.getElementById('voted_plus_lnk'+id).style.display = '';
	
}
function voted_plus(id)
{
	document.getElementById('voted_minus_lnk'+id).style.display = '';
	document.getElementById('voted_plus_lnk'+id).style.display = 'none';
	document.getElementById('vote_plus'+id).style.display = 'none';
	//document.getElementById('vote_minus'+id).style.display = 'none';
}
function voted_plus_other(id)
{
	document.getElementById('voted_minus_lnk'+id).style.display = '';
	document.getElementById('voted_plus_lnk'+id).style.display = 'none';
	
}
function vote_over_plus(id)
{
	document.getElementById('span_vote'+id).style.display = 'none';
	document.getElementById('span_plus'+id).style.display = '';
	document.getElementById('span_minus'+id).style.display = 'none';
	
}
function vote_over_minus(id)
{
	document.getElementById('span_vote'+id).style.display = 'none';
	document.getElementById('span_plus'+id).style.display = 'none';
	document.getElementById('span_minus'+id).style.display = '';
	
}
function current_vote(id)
{
	document.getElementById('span_vote'+id).style.display = '';
	document.getElementById('span_plus'+id).style.display = 'none';
	document.getElementById('span_minus'+id).style.display = 'none';
	
}
function a_voted_minus(id)
{
	
	document.getElementById('a_voted_minus_lnk'+id).style.display = 'none';
	document.getElementById('a_voted_plus_lnk'+id).style.display = '';
	document.getElementById('a_vote_minus'+id).style.display = 'none';
}
function a_voted_minus_other(id)
{
	
	document.getElementById('a_voted_minus_lnk'+id).style.display = 'none';
	document.getElementById('a_voted_plus_lnk'+id).style.display = '';
	
}
function a_voted_plus(id)
{
	document.getElementById('a_voted_minus_lnk'+id).style.display = '';
	document.getElementById('a_voted_plus_lnk'+id).style.display = 'none';
	document.getElementById('a_vote_plus'+id).style.display = 'none';
}
function a_voted_plus_other(id)
{
	document.getElementById('a_voted_minus_lnk'+id).style.display = '';
	document.getElementById('a_voted_plus_lnk'+id).style.display = 'none';
	
}
function a_vote_over_plus(id)
{
	document.getElementById('a_span_vote'+id).style.display = 'none';
	document.getElementById('a_span_plus'+id).style.display = '';
	document.getElementById('a_span_minus'+id).style.display = 'none';
	
}
function a_vote_over_minus(id)
{
	document.getElementById('a_span_vote'+id).style.display = 'none';
	document.getElementById('a_span_plus'+id).style.display = 'none';
	document.getElementById('a_span_minus'+id).style.display = '';
	
}
function a_current_vote(id)
{
	document.getElementById('a_span_vote'+id).style.display = '';
	document.getElementById('a_span_plus'+id).style.display = 'none';
	document.getElementById('a_span_minus'+id).style.display = 'none';
	
}
</script>
<script type="text/javascript" language="javascript" >
var root_path_js = '<?php echo get_option('siteurl')."/";?>';
</script>
<script type="text/javascript" language="javascript" src="<?php bloginfo('template_directory'); ?>/library/js/article_detail.js" ></script>
</head>
<body <?php body_class(); ?>>
<?php templ_body_start(); // Body Start hooks?>
<?php templ_get_top_header_navigation_above() ?>
<?php //templ_get_top_header_navigation() 
//jQuery(some).replaceWith('<span class="voted">Voted</span>');
//jQuery(some).replaceWith('<a post="'+thepost+'" user="'+theuser+'" already="voted" onclick="'+alert(thepost)+'">Votedspan</a>');
//jQuery(some).replaceWith('<a id="voted_a" post="'+thepost+'" user="'+theuser+'" already="voted" onclick="'+vote_text(user= theuser, post= thepost, already= thealready)+'">Voted</a>');
?>

<div class="wrapper">
<?php templ_header_start(); // Header Start hooks?>
<div class="header clear">
  <div class="header_in">
    <div class="logo">
      <?php  templ_site_logo(); ?>
    </div>
    <div class="header_right">
      <?php  templ_get_top_header_navigation() ?>
      <?php if (function_exists('dynamic_sidebar')){ dynamic_sidebar('header_logo_right_side'); }?>
    </div>
  </div> <!-- header inner #end -->
</div> <!-- header #end -->

<?php templ_header_end(); // Header End hooks?>

<?php templ_content_start(); // content start hooks?>

<div class="outer">
<!-- Container -->
<div id="container" class="clear">

<div class="main_nav">
  <?php  templ_get_main_header_navigation(); ?>
</div> <!-- main navi #end -->