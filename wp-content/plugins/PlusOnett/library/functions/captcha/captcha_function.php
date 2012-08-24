<?php
function pt_get_captch()
{
	global $captchaimagepath;
	$captchaimagepath = get_bloginfo('template_url').'/library/functions/captcha/';
?>
<div class="checkout_row">
<label><?php _e(CAPTCHA,'templatic'); ?> <small>*</small></label>
<input type="text" name="captcha"  class="textfield" /> 
<img src="<?php bloginfo('template_url');?>/library/functions/captcha/captcha.php" alt="captcha image" class="captcha_img" />
<?php if($_REQUEST['emsg']=='captch'){echo '<span class="message_error2" id="category_span">'.__('Please enter valid black colored text only','templatic').'</span>';}?>
<p class="message_note"><?php _e('Enter the text as you see in the image. Only enter black colored text','templatic');?></p>
</div>
<?php
}
function pt_check_captch_cond()
{
	if($_SESSION["captcha"]==$_POST["captcha"])
	{
		return true;
	}
	else
	{
		return false;
	}	
}
?>