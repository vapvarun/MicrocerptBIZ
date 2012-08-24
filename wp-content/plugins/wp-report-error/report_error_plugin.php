<?php
/*
 Plugin Name: WP Report Error
 Plugin URI: http://www.daddydesign.com/wordpress/report-error-wp-plugin/
 Description: Plugin that inserts "WP report error" link into page.
 Version: 1.6
 Author: DaddyDesign
 Author URI: http://www.daddydesign.com
 Text Domain: wp-report-error
 Domain Path: /languages/

 Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : daddydesign@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

load_plugin_textdomain('wp-report-error', '/wp-content/plugins/wp-report-error/languages/');
/* Version check */
global $wp_version;	
$exit_msg='WP Report Error requires WordPress 2.8 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>';
global $plugin_url;
$plugin_url=defined('WP_PLUGIN_URL') ? (WP_PLUGIN_URL . '/' . dirname(plugin_basename(__FILE__))) : trailingslashit(get_bloginfo('wpurl')) . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__)); 


if (version_compare($wp_version,"2.8","<"))
{
	exit ($exit_msg);
}

add_action('admin_menu', 'WPEPE_options');

function WPEPE_options() {
  add_options_page('Report Page Errors', 'Report Page Errors', 10, 'WRPErrors_options', 'WRPErrors_options');
}


//Include Jquery and Popup script on frontpage.
function WRPErrors_Scripts()
{
	if (!is_admin())
	{
	  global $plugin_url;
	  wp_enqueue_script('jquery');
	  wp_enqueue_script('report_errors_script', $plugin_url.'/js/report_errors_script.js', array('jquery')); 
	}
}
//add stylesheet to head
function WRPErrors_HeadAction()
{
	global $plugin_url;
	echo '<!-- Wordpress Report Page Errors -->';
	echo '<link rel="stylesheet" href="'.$plugin_url.'/report_page_errors.css" type="text/css" />'; 
	echo '<!-- Wordpress Report Page Errors -->';
}
function ReportPageErrors()
{
	global $post;
	global $plugin_url;
	
	// get the post title
	$title=urlencode($post->post_title);
	$permalink = get_permalink( $post->ID );
		
	// create a Digg link and return it	
	if(is_single() || is_page())
	{
		$email_hide=get_option('WRPErrors_email_option');
			$icon_hide=get_option('WRPErrors_hide_icon');
	$wprpcode='';

	if($icon_hide)
	{
		$wprpcode.= '<style type="text/css"> .wprperrorsform {background:none !important; padding:0px 0px 0px 10px;}</style>';
	}
		$wprpcode.= '<a class="wprperrorsform" href="#wprperrors">'.__('Report Spam','wp-report-error').'</a>
		<div id="wrpbody" class="wrphidden"></div>
		<div id="wrpsubmitform">
		<h2 class="wrphead">'.__('Report Spam','wp-report-error').'<img id="wrpcloseform" src="'.$plugin_url.'/images/close.png" alt="'.__('Close','wp-report-error').'" /></h2>
		<div id="wrpresponse"></div>
		<div id="wrperror"></div>
		<form class="wpreportform" action="'.$plugin_url.'/send_email.php?action=sendemail#wprresponse" method="post" id="wrpsubmitlink">
       
        <input type="hidden" name="pageurl" id="wrpurl" value="'.$permalink.'" />';
		if(!$email_hide)
		{
		$wprpcode.='<p>'.__('Your e-mail address','wp-report-error').' <small>('.__('required, but will not be published','wp-report-error').')</small><br />
            <input type="text" name="email" id="wrpemail" value="" /> 
        </p>';
		}
		$wprpcode.='<p>'.__('Error description','wp-report-error').'<small>(Maximum 250 characters.) '.__('required','wp-report-error').'</small><br />

            <textarea name="info" rows="5" cols="30" id="wrpinfo" onKeyDown="wprp_limitText(this,250);" onKeyUp="wprp_limitText(this,250);"></textarea>
			<br />
			
        </p>
		<p>'.__('Are you human?','wp-report-error').'  <small>('.__('required','wp-report-error').')</small><br />
			2 + 2 = 
			<input type="text" name="answer" id="wrpanswer" value="" />

		</p>
        <p>
            <input type="submit" name="wrpsubmitlink" value="'.__('Send Error','wp-report-error').'" class="wprpsubmit"/>
        </p>
	
    </form>
</div>';

	return $wprpcode;
   	}
}
function WRPErrors_options() {
	global $plugin_url;
  echo '<link rel="stylesheet" type="text/css" href="'.$plugin_url.'/options.css" />';
  echo '<div class="wrap">';
  echo '<h2>'.__('Wordpress Report Page/Post Error','wp-report-error').'</h2>';
  echo '<p>'.__('Use the options below to customize the Report Page / Post Error Plugin.','wp-report-error').'</p>';
  ?>
<form method="post" action="options.php"><?php wp_nonce_field('update-options');?>
<div class="WRPErrorsform_row">  
	<label><?php echo __('Email','wp-report-error'); ?></label><input type="text" id="WRPErrors_email" name="WRPErrors_email" value="<?php echo get_option('WRPErrors_email');?>" class="WRPErrors_text"/>
	<p class="WRPErrors_description"><?php echo __('Reported page / post errors will be sent to this email address.','wp-report-error');?></p>
</div> 
<div class="WRPErrorsform_row">  
	<label><?php echo __('Message','wp-report-error'); ?></label><textarea name="WRPErrors_message" id="WRPErrors_message" class="WRPErrors_textarea"><?php echo get_option('WRPErrors_message');?></textarea>
	<p class="WRPErrors_description"><?php echo __('This message will be displayed when the user successfully submits the Page / Post Error Report Form.','wp-report-error'); ?></p>
</div> 
<?php $checked = get_option('WRPErrors_code') ? "checked" : ""; ?>
<div class="WRPErrorsform_row">  
	<label><?php echo __('Report Error Link','wp-report-error');?></label><input type="checkbox" name="WRPErrors_code" id="WRPErrors_code" <?php echo $checked;?> class="WRPErrors_checkbox"/>
	<p class="WRPErrors_description"><?php echo __('Check if you want the Report Error Link added to your pages / posts automatically.','wp-report-error');?></p>
	<p class="WRPErrors_description"><?php echo __('Or you can add the following code to your theme PHP files:','wp-report-error');?><br /> &lt;&#63;php if(function_exists('ReportPageErrors')){ echo ReportPageErrors(); } &#63;&#62;</p>
	<p class="WRPErrors_description"><?php echo __('Or simply add','wp-report-error');?>  [WPRError] <?php echo __('shortcode in the wp-admin page or post html editor.','wp-report-error');?></p>
</div> 
<?php $email = get_option('WRPErrors_email_option') ? "checked" : ""; ?>
<div class="WRPErrorsform_row">  
	<label><?php echo __('Hide Email Field','wp-report-error');?></label><input type="checkbox" name="WRPErrors_email_option" id="WRPErrors_email_option" <?php echo $email;?> class="WRPErrors_checkbox"/>
	<p class="WRPErrors_description"><?php echo __('Check if you want to Hide Email Field from Popup form.','wp-report-error');?></p>
</div> 
<?php $hide_icons = get_option('WRPErrors_hide_icon') ? "checked" : ""; ?>
<div class="WRPErrorsform_row">  
	<label><?php echo __('Hide Icon','wp-report-error');?></label><input type="checkbox" name="WRPErrors_hide_icon" id="WRPErrors_hide_icon" <?php echo $hide_icons;?> class="WRPErrors_checkbox"/>
	<p class="WRPErrors_description"><?php echo __('Check if you want to Hide Icon.','wp-report-error');?></p>
</div> 
<div class="WRPErrorsform_row">  
	<label><?php echo __('Subject Of Email','wp-report-error');?></label><input type="text" name="WRPErrors_subject" id="WRPErrors_subject" value="<?php echo get_option('WRPErrors_subject');?>" class="WRPErrors_text"/>
	<p class="WRPErrors_description"><?php echo __('Subject of the email sent to your specified email address.','wp-report-error');?></p>
</div> 
<div class="WRPErrorsform_row">  
	<input type="submit" name="submit" value="<?php echo __('Submit','wp-report-error');?>" class="WRPErrors_submit"/>
</div> 
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="WRPErrors_email,WRPErrors_message,WRPErrors_code,WRPErrors_subject,WRPErrors_hide_icon,WRPErrors_email_option" />
<div class="WRPErrorsform_row" style="border:none;">
<h2><?php echo __('Need support?','wp-report-error');?></h2>
<p><?php echo __('If you have any problems with this plugin or need help, please visit ','wp-report-error');?><a href="http://www.daddydesign.com/wordpress/report-error-wp-plugin/" title="DaddyDesign <?php echo __('Report Error Plugin','wp-report-error');?>"><?php echo __('WP Report Error Plugin','wp-report-error');?></a></p>
<h2><?php echo __('Credits','wp-report-error');?></h2>
<p><?php echo __('This plugin was created by','wp-report-error');?> <a href="http://www.daddydesign.com" title="DaddyDesign">DaddyDesign.com</a> <?php echo __('with the assistance of','wp-report-error');?> <a href="http://www.snilesh.com" title="snilesh.com">Neel</a>.</p>
</div>
</form>

  <?php
  	
  echo '<br /><br /><br /><p style="text-align:right;"><a href="http://www.daddydesign.com" title="DaddyDesign.com"><img src="'.$plugin_url.'/images/daddydesign.png" alt="DaddyDesign.com" /></a></p>';	
  echo '</div>';
}
/**
 * Hook the_content to output html if we should display on any page
 */
$WRPErrors_code_condition = get_option('WRPErrors_code');
if($WRPErrors_code_condition=='on') {
	add_filter('the_content', 'WRPErrors_display_hook');
	add_filter('the_excerpt', 'WRPErrors_display_hook');
}	
	/**
	 * Loop through the settings and check whether Sociable should be outputted.
	 */
	function WRPErrors_display_hook($content='') {

		$content .= ReportPageErrors();
		return $content;
	}

/* Add Plugin to the Wordpress */
add_action('wp_print_scripts', 'WRPErrors_Scripts');
add_action('wp_head', 'WRPErrors_HeadAction' );
add_filter('WPRError', 'ReportPageErrors');
if(!function_exists('daddydesign_checkSpam'))
{
function daddydesign_checkSpam ($content) {

	// innocent until proven guilty
	$isSpam = FALSE;

	$content = (array) $content;

	if (function_exists('akismet_init')) {

		$wpcom_api_key = get_option('wordpress_api_key');

		if (!empty($wpcom_api_key)) {

			global $akismet_api_host, $akismet_api_port;

			// set remaining required values for akismet api
			$content['user_ip'] = preg_replace( '/[^0-9., ]/', '', $_SERVER['REMOTE_ADDR'] );
			$content['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$content['referrer'] = $_SERVER['HTTP_REFERER'];
			$content['blog'] = get_option('home');

			if (empty($content['referrer'])) {
				$content['referrer'] = get_permalink();
			}

			$queryString = '';

			foreach ($content as $key => $data) {
				if (!empty($data)) {
					$queryString .= $key . '=' . urlencode(stripslashes($data)) . '&';
				}
			}

			$response = akismet_http_post($queryString, $akismet_api_host, '/1.1/comment-check', $akismet_api_port);
				

			if ($response[1] == 'true') {
				update_option('akismet_spam_count', get_option('akismet_spam_count') + 1);
				$isSpam = TRUE;
			}

		}

	}

	return $isSpam;

}
}
?>