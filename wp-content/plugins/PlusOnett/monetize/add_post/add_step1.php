<?php
session_start();
ob_start();
?>
<?php get_header(); ?>
<div class="content content_full">
<div class="content_top content_top_full"></div>
	<div class="content_bg content_bg_full">
  <div class="entry">
    <div <?php post_class('single clear'); ?> id="post_<?php the_ID(); ?>">
      <div class="post-meta">
      	<?php // templ_page_title_above(); //page title above action hook?>
        	<h1><?php _e('Submit Article','templatic');?></h1>
        <?php templ_page_title_below(); //page title below action hook?>
      </div>
      <div id="post_<?php the_ID(); ?>">
        <div class="post-content">
		<?php
		if($current_user->ID!=''){
				echo '<script>location.href="'.site_url('/?ptype=add_step2').'"</script>';
			}
			else
			 {?>
         <div class="steps">
            	<ul>
                	<li class="current"><?php _e('1. Login or Register','templatic');?></li>
                    <li><?php _e('2. Article Detail','templatic');?></li>
                    <li><?php _e('3. Article Preview','templatic');?></li>
                    <li class="<?php if($_GET['ptype'] == 'add_step5'){?>last_current <?php }?> bgn"><?php _e(' 4. Success','templatic');?></li>
                </ul>
            </div>
            
              <div class="login_step1">
			  
              
               <h3><?php _e(LOGINORREGISTER,'templatic'); ?> </h3>
            
        
          <?php if($_REQUEST['usererror']==1)
			{
				//print_r($_SESSION['userinset_error']);
				//die;
				
				if(is_object($_SESSION['userinset_error']))
				{
					foreach($_SESSION['userinset_error'] as $errorsObj)
					{
						foreach($errorsObj as $key=>$val)
						{
							for($i=0;$i<count($val);$i++)
							{
							echo "<p class=\"message_error\">".$val[$i].'</p>';	
							}
						} 
					}
				}
			}
			if($_REQUEST['emsg']==1)
			{
			?>
	          <div class="message_error"><?php _e(INVALID_USER_PW_MSG,'templatic');?></div>
          <?php
			}
			$error_msg = apply_filters('templ_submit_form_emsg_filter','');
			if($error_msg)
			{
			?>
	          <div class="message_error"><?php _e($error_msg,'templatic');?></div>
          	<?php	
			}
			
			
        	
           
        
       
		if($_GET['usererror'] ==1){
			$checkedexisting_user = '';
			$checkednew_user = 'checked';
		}
		else{
			$checkedexisting_user = 'checked';
			$checkednew_user = '';
		}
		
		 if($_GET['usererror'] == 1)
			{ ?> 
			<script language="javascript" type="text/javascript">
				
				document.getElementById('contact_detail_id').style.display = '';
				document.getElementById('login_user_frm_id').style.display = 'none';
				</script>
			
			<?php 
				$contactdisp_style ="style='display:visible;'";
				$logindisp_style ="style='display:none;'";
				$loginornot = 'new_user';
			}
			else{
				$contactdisp_style ="style='display:none;'";
				$logindisp_style ="style='display:visible;'";
				$loginornot = 'existing_user';
			}
			
			
			?>
         
         
       
         
       
        
         <div class="addlisting_row">
        	 <p class="left choice"> <label> <input name="user_login_or_not" type="radio" value="existing_user" <?php echo $checkedexisting_user;  ?> onclick="set_login_registration_frm(this.value);" /> <?php _e(EXISTING_USER_TEXT,'templatic');?> </label> </p>
             
             <p class="left"> <label> <input  name="user_login_or_not" type="radio" value="new_user" <?php echo $checkednew_user;  ?>  onclick="set_login_registration_frm(this.value);" /> <?php _e(NEW_USER_TEXT,'templatic');?> </label></p>
        </div> 
        
       
        
        
		
         <form name="formadd_step1" id="formadd_step1" action="<?php echo site_url('/?ptype=add_step2'); ?>" method="post" > 
		 <input type="hidden" name="user_login_or_not" id="user_login_or_not" value="<?php echo $loginornot; ?>" />
          <div class="member_login" id="login_user_frm_id" <?php echo $logindisp_style; ?>>
         
        	<div class="addlisting_row"><label><?php _e(LOGIN_TEXT,'templatic');?> <small>*</small></label> <input id="user_login" name="log" type="text" class="textfield" />
            </div>
            <div class="addlisting_row"><label> <?php _e(PASSWORD_TEXT,'templatic'); ?> <small>*</small></label> <input id="user_pass" name="pwd" type="password" class="textfield" /></div>
            
             <?php	$login_redirect_link = site_url('/?ptype=add_step1');?>
              <input type="hidden" name="redirect_to" value="<?php echo $login_redirect_link; ?>" />
              <input type="hidden" name="testcookie" value="1" />
              <input type="hidden" name="pagetype" value="<?php echo $login_redirect_link; ?>" />
            
            
        </div> <!-- member login #end -->
		  
		  
		  <?php if(allow_user_register() && $current_user->ID==''){?>		  
          
         <div class="member_login" id="contact_detail_id" <?php echo $contactdisp_style; ?>>
            
            <div class="addlisting_row"><label> <?php _e(CONTACT_NAME_TEXT,'templatic'); ?> <small>*</small></label> <input name="user_fname" id="user_fname" value="<?php echo $user_fname;?>" type="text" class="textfield" /></div>
            <div class="addlisting_row"><label> <?php _e(EMAIL_TEXT,'templatic'); ?> <small>*</small></label> <input  name="user_email" id="user_email" value="<?php echo $user_email;?>" type="text" class="textfield" /></div>
            <div class="addlisting_row"><label> <?php _e(CONTACT_TEXT,'templatic'); ?> <small>*</small></label> <input name="user_phone" id="user_phone" value="<?php echo $user_phone;?>" type="text" class="textfield" /></div>
        </div> <!-- member login #end -->
          <?php }?>
           </div>
         <div class="addlisting_row">
        <input name="submit" type="submit" value="Next Step" class="button" /> 
         </div>
      </form>
        </div>
        <!-- post content #end -->
        
      </div>
	   <?php }
		 
		 ?>
    </div>
  </div>
</div>
</div> <!-- content bg #end -->
    <div class="content_bottom content_bottom_full"></div>
</div> <!-- content end -->
<script language="javascript" type="text/javascript">
function set_login_registration_frm(val)
{
	if(val=='existing_user')
	{
		document.getElementById('contact_detail_id').style.display = 'none';
		document.getElementById('login_user_frm_id').style.display = '';
		document.getElementById('user_login_or_not').value = val;
	}else  //new_user
	{
		document.getElementById('contact_detail_id').style.display = '';
		document.getElementById('login_user_frm_id').style.display = 'none';
		document.getElementById('user_login_or_not').value = val;
	}
}
<?php if($user_login_or_not)
{
?>
set_login_registration_frm('<?php echo $user_login_or_not;?>');
<?php
}
?>
</script>

<?php get_footer(); ?>
