<?php

// Excerpt length

function bm_better_excerpt($length, $ellipsis) {
$text = get_the_content();
$text = strip_tags($text);
$text = substr($text, 0, $length);
$text = substr($text, 0, strrpos($text, " "));
$text = $text.$ellipsis;
return $text;
}

 ///////////NEW FUNCTIONS  START//////
function bdw_get_images($iPostID,$img_size='thumb',$no_images='') 
{
    $arrImages =& get_children('order=ASC&orderby=menu_order ID&post_type=attachment&post_mime_type=image&post_parent=' . $iPostID );
	$counter = 0;
	$return_arr = array();
	if($arrImages) 
	{		
       foreach($arrImages as $key=>$val)
	   {
	   		$id = $val->ID;
			if($img_size == 'large')
			{
				$img_arr = wp_get_attachment_image_src($id,'full');	// THE FULL SIZE IMAGE INSTEAD
				$return_arr[] = $img_arr[0];
			}
			elseif($img_size == 'medium')
			{
				$img_arr = wp_get_attachment_image_src($id, 'medium'); //THE medium SIZE IMAGE INSTEAD
				$return_arr[] = $img_arr[0];
			}
			elseif($img_size == 'thumb')
			{
				$img_arr = wp_get_attachment_image_src($id, 'thumbnail'); // Get the thumbnail url for the attachment
				$return_arr[] = $img_arr[0];
			}
			$counter++;
			if($no_images!='' && $counter==$no_images)
			{
				break;	
			}
	   }
	  return $return_arr;
	}
}

function get_site_emailId()
{
	
	if(get_option('ptthemes_site_email'))
	{
		return get_option('ptthemes_site_email');	
	}
	return get_option('admin_email');
}
function get_site_emailName()
{
	
	if(get_option('ptthemes_site_name'))
	{
		return stripslashes(get_option('ptthemes_site_name'));	
	}
	return stripslashes(get_option('blogname'));
}

/************************************
//FUNCTION NAME : commentslist
//ARGUMENTS :comment data, arguments,depth level for comments reply
//RETURNS : Comment listing format
***************************************/
function commentslist($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
    
    
   <li >
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?> >
    <div class="comment_left"> <?php echo get_avatar($comment, 45, get_bloginfo('template_url').'/images/no-avatar.png'); ?> </div>
    <div class="comment-text">
      <div class="comment-meta">
        <p class="comment-author"><span><?php printf(__('<p class="comment-author"><span>%s</span></p>','templatic'), get_comment_author_link()) ?></span></p>
        <?php printf(__('<p class="comment-date">&nbsp;~ %s</p>','templatic'), get_comment_date(templ_get_date_format())) ?>
        <?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
      </div>
     
       
      <div class="text">
      
      	 <?php if ($comment->comment_approved == '0') : ?>
      
        <?php _e('Your comment is awaiting moderation.','templatic') ?>
     
      <?php endif; ?>
      
      <?php comment_text() ?>
     </div>
     
    </div>
  </div>
    
	
    
<?php
}


// ---------------------------------------------------------------------- ///
//Shortcodes add --------------------------------------------------------
//----------------------------------------------------------------------- /// 

// Shortcodes - Messages -------------------------------------------------------- //
function message_download( $atts, $content = null ) {
   return '<p class="download">' . $content . '</p>';
}
add_shortcode( 'Download', 'message_download' );

function message_alert( $atts, $content = null ) {
   return '<p class="alert">' . $content . '</p>';
}
add_shortcode( 'Alert', 'message_alert' );

function message_note( $atts, $content = null ) {
   return '<p class="note">' . $content . '</p>';
}
add_shortcode( 'Note', 'message_note' );


function message_info( $atts, $content = null ) {
   return '<p class="info">' . $content . '</p>';
}
add_shortcode( 'Info', 'message_info' );


// Shortcodes - About Author -------------------------------------------------------- //

function about_author( $atts, $content = null ) {
   return '<div class="about_author">' . $content . '</p></div>';
}
add_shortcode( 'Author Info', 'about_author' );


function icon_list_view( $atts, $content = null ) {
   return '<div class="check_list">' . $content . '</p></div>';
}
add_shortcode( 'Icon List', 'icon_list_view' );


// Shortcodes - Boxes -------------------------------------------------------- //

function normal_box( $atts, $content = null ) {
   return '<div class="boxes normal_box">' . $content . '</p></div>';
}
add_shortcode( 'Normal_Box', 'normal_box' );

function warning_box( $atts, $content = null ) {
   return '<div class="boxes warning_box">' . $content . '</p></div>';
}
add_shortcode( 'Warning_Box', 'warning_box' );

function about_box( $atts, $content = null ) {
   return '<div class="boxes about_box">' . $content . '</p></div>';
}
add_shortcode( 'About_Box', 'about_box' );

function download_box( $atts, $content = null ) {
   return '<div class="boxes download_box">' . $content . '</p></div>';
}
add_shortcode( 'Download_Box', 'download_box' );

function info_box( $atts, $content = null ) {
   return '<div class="boxes info_box">' . $content . '</p></div>';
}
add_shortcode( 'Info_Box', 'info_box' );


function alert_box( $atts, $content = null ) {
   return '<div class="boxes alert_box">' . $content . '</p></div>';
}
add_shortcode( 'Alert_Box', 'alert_box' );



// Shortcodes - Boxes - Equal -------------------------------------------------------- //

function normal_box_equal( $atts, $content = null ) {
   return '<div class="boxes normal_box small">' . $content . '</p></div>';
}
add_shortcode( 'Normal_Box_Equal', 'normal_box_equal' );

function warning_box_equal( $atts, $content = null ) {
   return '<div class="boxes warning_box small">' . $content . '</p></div>';
}
add_shortcode( 'Warning_Box_Equal', 'warning_box_equal' );

function about_box_equal( $atts, $content = null ) {
   return '<div class="boxes about_box small">' . $content . '</p></div>';
}
add_shortcode( 'About_Box_Equal', 'about_box' );

function download_box_equal( $atts, $content = null ) {
   return '<div class="boxes download_box small">' . $content . '</p></div>';
}
add_shortcode( 'Download_Box_Equal', 'download_box_equal' );

function info_box_equal( $atts, $content = null ) {
   return '<div class="boxes info_box small">' . $content . '</p></div>';
}
add_shortcode( 'Info_Box_Equal', 'info_box_equal' );


function alert_box_equal( $atts, $content = null ) {
   return '<div class="boxes alert_box small">' . $content . '</p></div>';
}
add_shortcode( 'Alert_Box_Equal', 'alert_box_equal' );


// Shortcodes - Content Columns -------------------------------------------------------- //

function one_half_column( $atts, $content = null ) {
   return '<div class="one_half_column left">' . $content . '</p></div>';
}
add_shortcode( 'One_Half', 'one_half_column' );

function one_half_last( $atts, $content = null ) {
   return '<div class="one_half_column right">' . $content . '</p></div><div class="clear_spacer clearfix"></div>';
}
add_shortcode( 'One_Half_Last', 'one_half_last' );


function one_third_column( $atts, $content = null ) {
   return '<div class="one_third_column left">' . $content . '</p></div>';
}
add_shortcode( 'One_Third', 'one_third_column' );

function one_third_column_last( $atts, $content = null ) {
   return '<div class="one_third_column_last right">' . $content . '</p></div><div class="clear_spacer clearfix"></div>';
}
add_shortcode( 'One_Third_Last', 'one_third_column_last' );


function one_fourth_column( $atts, $content = null ) {
   return '<div class="one_fourth_column left">' . $content . '</p></div>';
}
add_shortcode( 'One_Fourth', 'one_fourth_column' );

function one_fourth_column_last( $atts, $content = null ) {
   return '<div class="one_fourth_column_last right">' . $content . '</p></div><div class="clear_spacer clearfix"></div>';
}
add_shortcode( 'One_Fourth_Last', 'one_fourth_column_last' );


function two_thirds( $atts, $content = null ) {
   return '<div class="two_thirds left">' . $content . '</p></div>';
}
add_shortcode( 'Two_Third', 'two_thirds' );

function two_thirds_last( $atts, $content = null ) {
   return '<div class="two_thirds_last right">' . $content . '</p></div><div class="clear_spacer clearfix"></div>';
}
add_shortcode( 'Two_Third_Last', 'two_thirds_last' );


function dropcaps( $atts, $content = null ) {
   return '<p class="dropcaps">' . $content . '</p>';
}
add_shortcode( 'Dropcaps', 'dropcaps' );


// Shortcodes - Small Buttons -------------------------------------------------------- //

function small_button( $atts, $content ) {
 return '<div class="small_button '.$atts['class'].'">' . $content . '</div>';
}
add_shortcode( 'Small_Button', 'small_button' );




// filters add -------------///

add_filter('templ_top_header_nav_below_filter','templ_top_header_nav_above_fun');
function templ_top_header_nav_above_fun()
{
	
	
?>
	

    
<?php
}

add_filter('templ_main_header_nav_above_filter','templ_main_header_nav_above_fun');
function templ_main_header_nav_above_fun()
{
?>
  
  
  
<?php
}

add_filter('templ_head_css','templ_print_css');
function templ_print_css()
{
	
?>
<link rel="stylesheet" type="text/css" href="<?php echo TT_CSS_FOLDER_URL; ?>print.css" media="print" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url'); ?>/js/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
 <?php
}
function user_post_visit_count($pid)
{
	if(get_post_meta($pid,'question_viewed_count',true))
	{
		return get_post_meta($pid,'question_viewed_count',true);
	}else
	{
		return '0';	
	}
}



// voting function
function voting($id) {

global $user_ID;
$currentvotes = get_post_meta($id, 'votes', true);
$voters = get_post_meta($id, 'thevoters', true);
$voters = explode(",", $voters);
foreach($voters as $voter) {
	if($voter == $user_ID) $alreadyVoted = true;
}
 
if(!$currentvotes) $currentvotes = 1;
echo '<div class="vote vote'.$id.'"><span class="span_currentvote" id="span_vote'.$id.'">'.$currentvotes.'</span>';
echo '<span class="span_plusvote" id="span_plus'.$id.'" style="display:none;">+1</span>';
echo '<span class="span_minusvote" id="span_minus'.$id.'" style="display:none;">-1</span>';
if($user_ID && !$alreadyVoted) echo '<a class="hype_link" id="vote_plus'.$id.'" post="'.$id.'" user="'.$user_ID.'" onclick="voted_plus('.$id.');" onmouseover="vote_over_plus('.$id.')" onmouseout="current_vote('.$id.')">'.__("Hype").'</a>';
if($user_ID && $alreadyVoted) echo '<a class="hype_link" id="vote_minus'.$id.'" post="'.$id.'" user="'.$user_ID.'" already="voted" onclick="voted_minus('.$id.');" onmouseover="vote_over_minus('.$id.')" onmouseout="current_vote('.$id.')">'.__("Hype").'</a>'; ?>
<a class="hype_link" id="voted_minus_lnk<?php echo $id; ?>" post="<?php echo $id; ?>" user="<?php echo $user_ID; ?>" style="display:none" already="voted" onclick="voted_minus_other(<?php echo $id; ?>);" onmouseover="vote_over_minus(<?php echo $id; ?>)" onmouseout="current_vote(<?php echo $id; ?>)">Hype</a>
<a class="hype_link" id="voted_plus_lnk<?php echo $id; ?>" post="<?php echo $id; ?>" user="<?php echo $user_ID; ?>" style="display:none" onclick="voted_plus_other(<?php echo $id; ?>);" onmouseover="vote_over_plus(<?php echo $id; ?>)" onmouseout="current_vote(<?php echo $id; ?>)">Hype</a>
<?php
if(!$user_ID) echo '<a class="hype_link" href="'.site_url().'/?ptype=register" onmouseover="vote_over_plus('.$id.')" onmouseout="current_vote('.$id.')">'.__("Hype").'</a>'; ?>

<?php
 echo '</div>';
}
// voting function
function voting_author($id) {
global $user_ID;
$currentvotes = get_post_meta($id, 'votes', true);
$voters = get_post_meta($id, 'thevoters', true);
$voters = explode(",", $voters);
foreach($voters as $voter) {
	if($voter == $user_ID) $alreadyVoted = true;
}
 
if(!$currentvotes) $currentvotes = 1;
echo '<div class="vote vote'.$id.'"><span class="span_currentvote" id="a_span_vote'.$id.'">'.$currentvotes.'</span>';
echo '<span class="span_plusvote" id="a_span_plus'.$id.'" style="display:none;">+1</span>';
echo '<span class="span_minusvote" id="a_span_minus'.$id.'" style="display:none;">-1</span>';
if($user_ID && !$alreadyVoted) echo '<a class="hype_link" id="a_vote_plus'.$id.'" post="'.$id.'" user="'.$user_ID.'" onclick="a_voted_plus('.$id.');" onmouseover="a_vote_over_plus('.$id.')" onmouseout="a_current_vote('.$id.')">'.__("Hype").'</a>';
if($user_ID && $alreadyVoted) echo '<a class="hype_link" id="a_vote_minus'.$id.'" post="'.$id.'" user="'.$user_ID.'" already="voted" onclick="a_voted_minus('.$id.');" onmouseover="a_vote_over_minus('.$id.')" onmouseout="a_current_vote('.$id.')">'.__("Hype").'</a>'; ?>
<a class="hype_link" id="a_voted_minus_lnk<?php echo $id; ?>" post="<?php echo $id; ?>" user="<?php echo $user_ID; ?>" style="display:none" already="voted" onclick="a_voted_minus_other(<?php echo $id; ?>);" onmouseover="a_vote_over_minus(<?php echo $id; ?>)" onmouseout="a_current_vote(<?php echo $id; ?>)">Hype</a>
<a class="hype_link" id="a_voted_plus_lnk<?php echo $id; ?>" post="<?php echo $id; ?>" user="<?php echo $user_ID; ?>" style="display:none" onclick="a_voted_plus_other(<?php echo $id; ?>);" onmouseover="a_vote_over_plus(<?php echo $id; ?>)" onmouseout="a_current_vote(<?php echo $id; ?>)">Hype</a>
<?php
if(!$user_ID) echo '<a class="hype_link" href="'.site_url().'/?ptype=register" onmouseover="a_vote_over_plus('.$id.')" onmouseout="a_current_vote('.$id.')">'.__("Hype").'</a>'; ?>

<?php
 echo '</div>';
}
?>
