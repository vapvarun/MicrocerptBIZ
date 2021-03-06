<?php 
/*
 * This is the page users will see logged out. 
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
<?php
	if( $is_widget ){
		echo $before_widget . $before_title . '<span id="LoginWithAjax_Title">' . __('Log In') . '</span>' . $after_title;
	}
?>
	<div id="LoginWithAjax" class="default"><?php //ID must be here, and if this is a template, class name should be that of template directory ?>
        <span id="LoginWithAjax_Status"></span>
        <form name="LoginWithAjax_Form" id="LoginWithAjax_Form" action="<?php echo $this->url_login; ?>" method="post">
            <table width='100%' cellspacing="0" cellpadding="0">
                <tr id="LoginWithAjax_Username">
                    <td class="username_label">
                        <label><?php _e( 'Username' ) ?></label>
                    </td>
                    <td class="username_input">
                        <input type="text" name="log" id="lwa_user_login" class="input" value="<?php echo attribute_escape(stripslashes($user_login)); ?>" />
                    </td>
                </tr>
                <tr id="LoginWithAjax_Password">
                    <td class="password_label">
                        <label><?php _e( 'Password' ) ?></label>
                    </td>
                    <td class="password_input">
                        <input type="password" name="pwd" id="lwa_user_pass" class="input" value="" />
                    </td>
                </tr>
                <tr id="LoginWithAjax_Submit">
                    <td id="LoginWithAjax_SubmitButton">
                        <input type="submit" name="wp-submit" id="lwa_wp-submit" value="<?php _e('Log In'); ?>" tabindex="100" />
                        <input type="hidden" name="redirect_to" value="http://<?php echo $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'] ?>" />
                        <input type="hidden" name="testcookie" value="1" />
                        <input type="hidden" name="lwa_profile_link" value="<?php echo $lwa_data['profile_link'] ?>" />
                    </td>
                    <td id="LoginWithAjax_Links">
                        <input name="rememberme" type="checkbox" id="lwa_rememberme" value="forever" /> <label><?php _e( 'Remember Me' ) ?></label>
                        <br />
                        <a id="LoginWithAjax_Links_Remember" href="<?php echo site_url('wp-login.php?action=lostpassword', 'login') ?>" title="<?php _e('Password Lost and Found') ?>"><?php _e('Lost your password?') ?></a>
                        <?php
                            //Signup Links
                            if ( get_option('users_can_register') && $lwa_data['registration'] == true ) {
                                echo "<br />";  
                                if ( function_exists('bp_get_signup_page') ) { //Buddypress
                                	$register_link = bp_get_signup_page();
                                }elseif ( file_exists( ABSPATH."/wp-signup.php" ) ) { //MU + WP3
                                    $register_link = site_url('wp-signup.php', 'login');
                                } else {
                                    $register_link = site_url('wp-login.php?action=register', 'login');
                                }
                                ?>
                                <a href="<?php echo $register_link ?>" id="LoginWithAjax_Links_Register" rel="#LoginWithAjax_Register"><?php _e('Register') ?></a>
                                <?php
                            }
                        ?>
                    </td>
                </tr>
            </table>
        </form>
        <form name="LoginWithAjax_Remember" id="LoginWithAjax_Remember" action="<?php echo $this->url_remember ?>" method="post">
            <table width='100%' cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <strong><?php echo __("Forgotten Password", 'login-with-ajax'); ?></strong>         
                    </td>
                </tr>
                <tr>
                    <td class="forgot-pass-email">  
                        <?php $msg = __("Enter username or email", 'login-with-ajax'); ?>
                        <input type="text" name="user_login" id="lwa_user_remember" value="<?php echo $msg ?>" onfocus="if(this.value == '<?php echo $msg ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo $msg ?>'}" />   
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="<?php echo __("Get New Password", 'login-with-ajax'); ?>" />
                          <a href="#" id="LoginWithAjax_Links_Remember_Cancel"><?php _e("Cancel"); ?></a>
                        <input type="hidden" name="login-with-ajax" value="remember" />         
                    </td>
                </tr>
            </table>
        </form>
        <?php 
		//Taken from wp-login.php
		?>
        <?php if ( get_option('users_can_register') && $lwa_data['registration'] == true ) : ?>
		<div id="LoginWithAjax_Register">
			<form name="registerform" id="registerform" action="<?php echo $this->url_register ?>" method="post">
			<table width='100%' cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <strong><?php _e('Register For This Site') ?></strong>         
                    </td>
                </tr>
                <tr>
                    <td>  
                        <?php $msg = __('Username') ?>
                        <input type="text" name="user_login" id="user_login"  value="<?php echo $msg ?>" onfocus="if(this.value == '<?php echo $msg ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo $msg ?>'}" /></label>   
                    </td>
                </tr>
                <tr>
                    <td>  
                        <?php $msg = __('E-mail') ?>
                        <input type="text" name="user_email" id="user_email"  value="<?php echo $msg ?>" onfocus="if(this.value == '<?php echo $msg ?>'){this.value = '';}" onblur="if(this.value == ''){this.value = '<?php echo $msg ?>'}"/></label>   
                    </td>
                </tr>
                <tr>
                    <td>
						<?php
							//If you want other plugins to play nice, you need this: 
							do_action('register_form'); 
						?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p><?php _e('A password will be e-mailed to you.') ?></p>
						<input type="submit" value="<?php esc_attr_e('Register'); ?>" tabindex="100" />
						<a href="#" id="LoginWithAjax_Links_Register_Cancel"><?php _e("Cancel"); ?></a>
						<input type="hidden" name="lwa" value="1" />
                    </td>
                </tr>
            </table>
			</form>
		</div>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				//Register
				$('#LoginWithAjax_Register').hide();
				$('#LoginWithAjax_Links_Register').click(function(event){
					event.preventDefault();
					$('#LoginWithAjax_Register').show('slow');
				});
				$('#LoginWithAjax_Links_Register_Cancel').click(function(event){
					event.preventDefault();
					$('#LoginWithAjax_Register').hide('slow');
				});
			});
		</script>
		<?php endif; ?>	
	</div>
<?php
	if( $is_widget ){
		echo $after_widget;
	}
?>