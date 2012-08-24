<?php
if($_POST['coupon_code'])
{
	if(!templ_get_coupon_info($_POST['coupon_code']))
	{
		$url = site_url('/?ptype=submition&backandedit=1&emsg=invalid_coupon');
?>
<script type="text/javascript"> window.location.href="<?php echo $url;?>";</script>
<?php
		exit;
	}
}
	
$property_price_info = array();
if(function_exists('templ_get_package_price_info'))
{
	$property_price_info = templ_get_package_price_info($_REQUEST['package']);
}
$payable_amount = $property_price_info[0]['price'];
$alive_days = $property_price_info[0]['alive_days'];
$type_title = $property_price_info[0]['title'];
if($is_delet_property)
{	
}else
{
	if($_REQUEST['coupon_code']!='')
	{
		$discount_amt = get_discount_amount($_REQUEST['coupon_code'],$payable_amount);
		$payable_amount = $payable_amount-$discount_amt;
	}
}

if($_REQUEST['alook'])
{
}else
{
?>
<div class="preview_section" >
<?php
if($_REQUEST['pid'] || $_POST['renew'])
{
	$form_action_url = site_url('/?ptype=paynow');
}else
{
	$form_action_url = site_url('/?ptype=paynow');
}
?>
<form method="post" action="<?php echo $form_action_url; ?>" name="paynow_frm" id="paynow_frm" >
<input type="hidden" name="price_select" value="<?php echo $_REQUEST['price_select'];?>" />
<?php
if($is_delet_property)
{		
}else
{
	if(($_REQUEST['pid']=='' && $payable_amount>0) || ($_POST['renew'] && $payable_amount>0 && $_REQUEST['pid']!=''))
	{
		echo '<h5 class="free_property">';
		printf(GOING_TO_PAY_MSG, get_currency_sym().$payable_amount,$alive_days,$type_title);
		if($discount_amt>0)
		{
			printf(SUBMIT_POST_PREVIEW_DISCOUNT_MSG,$_REQUEST['coupon_code'],get_currency_sym().$discount_amt);
		}
		echo '</h5>';
	}else
	{
		echo '<h5 class="free_property">';
		if($_REQUEST['pid']==''){
			printf(SUBMIT_POST_PREVIEW_FREE_MSG, $alive_days,$type_title);
		}else
		{
			printf(GOING_TO_UPDATE_MSG, get_currency_sym().$payable_amount,$alive_days,$type_title);
		}
		echo '</h5>';
	}
}

if(($_REQUEST['pid']=='' && $payable_amount>0) || ($_POST['renew'] && $payable_amount>0 && $_REQUEST['pid']!=''))
{
    if(function_exists('templ_payment_option_radio'))
    {
        templ_payment_option_radio();
    }
}
?>
<script type="application/x-javascript">
function showoptions(paymethod)
{
<?php
for($i=0;$i<count($paymethodKeyarray);$i++)
{
?>
showoptvar = '<?php echo $paymethodKeyarray[$i]?>options';
if(eval(document.getElementById(showoptvar)))
{
    document.getElementById(showoptvar).style.display = 'none';
    if(paymethod=='<?php echo $paymethodKeyarray[$i]?>')
    {
        document.getElementById(showoptvar).style.display = '';
    }
}
<?php
}
?>
}
<?php
for($i=0;$i<count($paymethodKeyarray);$i++)
{
?>
if(document.getElementById('<?php echo $paymethodKeyarray[$i];?>_id').checked)
{
showoptions(document.getElementById('<?php echo $paymethodKeyarray[$i];?>_id').value);
}
<?php
}	
?>
</script>

<?php
if($is_delet_property)
{
?>
<h5 class="payment_head"><?php echo POST_DELETE_PRE_MSG;?></h5>
<input type="button" name="Delete" value="<?php echo POST_DELETE_BUTTON;?>" class="b_post_delete" onclick="window.location.href='<?php echo site_url('/?ptype=delete&pid='.$_REQUEST['pid']);?>'" />
<input type="button" name="Cancel" value="<?php echo PRO_CANCEL_BUTTON;?>" class="b_post_cancel" onclick="window.location.href='<?php echo get_author_link($echo = false, $current_user->data->ID);?>'" />
<?php
}else
{
?>
<input type="hidden" name="paynow" value="1">
<input type="hidden" name="coupon_code" value="<?php echo $_POST['coupon_code'];?>">
<input type="hidden" name="pid" value="<?php echo $_POST['pid'];?>">
<?php
if($_REQUEST['pid'])
{
?>
<input type="submit" name="Submit and Pay" value="<?php echo PRO_UPDATE_BUTTON;?>" class="b_post_update fr" />
<?php
}else
{
if($payable_amount>0)
{
?>
<input type="submit" name="Submit and Pay" value="<?php echo PRO_SUBMIT_PAY_BUTTON;?>" class="b_post_submit_pay" />
<?php		
}else
{
?>
<input type="submit" name="Submit and Pay" value="<?php echo PRO_SUBMIT_BUTTON;?>" class="b_post_submit" />
<?php		
}
}
?>
<a href="<?php echo site_url('/?ptype=submition&backandedit=1');?><?php if($_REQUEST['pid']){ echo '&pid='.$_REQUEST['pid'];}?><?php if($_REQUEST['renew']){echo '&renew=1';}?>" class="b_goback " ><?php echo PRO_BACK_AND_EDIT_TEXT;?></a>
<input type="button" name="Cancel" value="<?php echo PRO_CANCEL_BUTTON;?>" class="b_post_cancel" onclick="window.location.href='<?php echo get_author_link($echo = false, $current_user->data->ID);?>'" />
<?php }?>
</form>
</div>
<?php }?>