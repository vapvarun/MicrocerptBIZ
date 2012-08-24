<?php 
/*
 * This is the page users will see logged in. 
 * You can edit this, but for upgrade safety you should copy and modify this file into your template folder.
 * The location from within your template folder is plugins/login-with-ajax/ (create these directories if they don't exist)
*/
?>
<?php
	global $current_user;
	if( $is_widget ){
		echo $before_widget . $before_title . '<span id="LoginWithAjax_Title">' . __( 'Hi', 'login-with-ajax' ) . " " . $current_user->display_name  . '</span>' . $after_title;
	}else{
		//If you want the AJAX login widget to work without refreshing the screen upon login, this is needed for the widget title to update.
		echo '<span id="LoginWithAjax_Title_Substitute" style="display:none">' . __( 'Hi', 'login-with-ajax' ) . " " . $current_user->display_name  . '</span>';
	}
?>
<div id="LoginWithAjax">
	<?php 
		global $wpmu_version;
		get_currentuserinfo();
	?>
			<div class="avatar" id="LoginWithAjax_Avatar">
				<?php if ( function_exists('bp_loggedinuser_avatar_thumbnail') ) : ?>
					<?php bp_loggedinuser_avatar_thumbnail() ?>
				<?php else: ?>
					<?php echo get_avatar( $current_user->user_email, $size = '50' );  ?>
				<?php endif; ?>		
			</div>
			<div id="LoginWithAjax_Title">
				<?php
					//Admin URL
					if ( $lwa_data['profile_link'] == '1' ) {
						?>
						<a href="<?php bloginfo('siteurl') ?>/wp-admin/profile.php"><?php echo strtolower(__('Profile')) ?></a><br/>
						<?php
					}
					//Logout URL
					if ( function_exists( 'wp_logout_url' ) ) {
						?>
						<a id="wp-logout" href="<?php echo wp_logout_url( site_url() ) ?>"><?php echo strtolower(__( 'Log Out' )) ?></a><br />
						<?php
					} else {
						?>
						<a id="wp-logout" href="<?php echo site_url() . '/wp-login.php?action=logout&amp;redirect_to=' . site_url() ?>"><?php echo strtolower(__( 'Log Out' )) ?></a><br />
						<?php
					}
				?>
				<?php
					if( !empty($wpmu_version) ) {
						?>
						<a href="<?php bloginfo('siteurl') ?>/wp-admin/">blog admin</a>
						<?php
					}
				?>
			</div>
			</div>
<?php
	if( $is_widget ){
		echo $after_widget;
	}
?>