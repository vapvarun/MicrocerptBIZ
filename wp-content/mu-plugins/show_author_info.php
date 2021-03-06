<?php
/*
Plugin Name: Author Information
Description: A nice way to show/hide author information
Version: 1.0
Author: Jon Caine
Author URI: 
*/

add_action('init', 'add_jquery');

function add_jquery() {
	wp_enqueue_script( 'jquery' ); 
	wp_enqueue_script('author-info', '/wp-content/mu-plugins/showauthor.js', 'jquery', false);

	/* 
	wp_register_script('my-upload', '/wp-content/mu-plugins/showauthor.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
	*/

}

add_action('wp_head', 'author_info_head');

function author_info_head() {
// 	echo "<link href=\"". get_bloginfo('wpurl'). "/wp-content/plugins/post-information/post-information.css\" type=\"text/css\" rel=\"stylesheet\" media=\"screen\" />";
?>
<style type="text/css" media="screen">
.author-info {
	padding-top: 10px;
}
</style>
<?php }

function author_info() {
	$options = get_option('author_profile_options');
//	echo '<input id="show_author_info" type="button" value="Show info" />';
	if ( $options['author_profile_name'] ) echo '<H1 style="font-size: 30px; padding-bottom: 5px; font-weight: normal; margin-bottom: 10px; border-bottom: 1px solid #D6DCE8;">'. $options['author_profile_name'] .'</H1>';
	echo '<div style="font-size: small"?>Profile: <a href="#" id="show_author_info">show</a> | <a href="#" id="hide_author_info">hide</a></div>';
	echo '<div class="author-info" style="font-size: 12pt">';
		if ( $options['author_profile_image'] ) {
			$img_path = $options['author_profile_image'];
			$min_width = 100;
			$max_width = 215;
			list($width) = getimagesize($img_path);
			if (($width > $max_width) and ($width < $min_width )) 
				echo "<img src='{$options['author_profile_image']}' style='padding-right: 5px ; float: left'/>";
		}
		if ( $options['author_profile_biography'] ) echo '<strong>Biography</strong><br /> '. nl2br($options['author_profile_biography']) .'<br /><br />';
		if ( $options['author_profile_events'] ) echo '<strong>Upcoming Events</strong><br /> '. nl2br($options['author_profile_events']) .'<br /><br />';
		if ( $options['author_profile_links'] ) echo '<strong>Links</strong><br /> '. nl2br($options['author_profile_links']) .'';
	echo '</div>';
}

?>
