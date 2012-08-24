<h4>
  <?php 
			 if($_REQUEST['page']=='login' && $_REQUEST['page1']=='sign_up')
			{
				echo REGISTRATION_NOW_TEXT;
			}else
			{
				echo SIGN_IN_PAGE_TITLE;
			}
			 ?>
</h4>
<?php

if ( $_REQUEST['logemsg']==1)
{
	echo "<p class=\"error_msg\"> ".INVALID_USER_PW_MSG." </p>";
}
if($_REQUEST['checkemail']=='confirm')
{
	echo '<p class="sucess_msg">'.PW_SEND_CONFIRM_MSG.'</p>';
}
?>
<div class="login_content"> <?php echo stripslashes(get_option('ptthemes_logoin_page_content'));?> </div>
<div class="login_form_box">
  <form name="loginform" id="loginform" action="<?php echo get_settings('home').'/index.php?ptype=login'; ?>" method="post" >
    <div class="form_row clearfix">
      <label><?php echo USERNAME_TEXT; ?> <span>*</span> </label>
      <input type="text" name="log" id="user_login" value="<?php echo esc_attr($user_login); ?>" size="20" class="textfield" />
      <span id="user_loginInfo"></span> </div>
    <div class="form_row clearfix">
      <label> <?php echo PASSWORD_TEXT; ?> <span>*</span> </label>
      <input type="password" name="pwd" id="user_pass" class="textfield" value="" size="20"  />
      <span id="user_passInfo"></span> </div>
    <?php do_action('login_form'); ?>
    <p class="rember">
      <input name="rememberme" type="checkbox" id="rememberme" value="forever" class="fl" />
      <?php echo REMEMBER_ON_COMPUTER_TEXT; ?> </p>
    <!-- <a  href="javascript:void(0);" onclick="chk_form_login();" class="highlight_button fl login" >Sign In</a>-->
    <input class="b_signin_n" type="submit" value="<?php echo SIGN_IN_BUTTON;?>"  name="submit" />
    <input type="hidden" name="redirect_to" value="<?php echo $_SERVER['HTTP_REFERER']; ?>" />
    <input type="hidden" name="testcookie" value="1" />
    <a href="javascript:void(0);showhide_forgetpw();"><?php echo FORGOT_PW_TEXT;?></a>
  </form>
  <?php 
  	
	if ( $_REQUEST['emsg']=='fw'){
		echo "<p class=\"error_msg\"> ".INVALID_USER_FPW_MSG." </p>";
		$display_style = 'style="display:block;"';
	}
	else{
		$display_style = 'style="display:none;"';
	}
  ?>
  
  <div id="lostpassword_form" <?php echo $display_style; ?>>
    <h4><?php echo FORGOT_PW_TEXT;?></h4>
    <form name="lostpasswordform" id="lostpasswordform" action="<?php echo site_url().'/?ptype=login&amp;action=lostpassword'; ?>" method="post">
      <div class="form_row clearfix">
        <label> <?php echo USERNAME_EMAIL_TEXT; ?>: </label>
        <input type="text" name="user_login" id="user_login1" value="<?php echo esc_attr($user_login); ?>" size="20" class="textfield" />
        <?php do_action('lostpassword_form'); ?>
      </div>
      <input type="submit" name="get_new_password" value="<?php echo GET_NEW_PW_TEXT;?>" class="b_signin_n " />
    </form>
  </div>
</div>
<script  type="text/javascript" >
function showhide_forgetpw()
{
	if(document.getElementById('lostpassword_form').style.display=='none')
	{
		document.getElementById('lostpassword_form').style.display = 'block';
	}else
	{
		document.getElementById('lostpassword_form').style.display = 'none';
	}	
}
</script>
