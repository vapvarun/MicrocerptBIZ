<?php
session_start();
global $upload_folder_path;

if($_POST)
{
	global $form_fields;
	foreach($form_fields as $fkey=>$fval)
	{
		$fldkey = "$fkey";
		$$fldkey = $_POST["$fkey"];
		if($fval['type']=='upload')
		{
			if($_FILES[$fkey]['name'] && $_FILES[$fkey]['size']>0)
			{
				$dirinfo = wp_upload_dir();
				$path = $dirinfo['path'];
				$url = $dirinfo['url'];
				$destination_path = $path."/";
				$destination_url = $url."/";
				
				$src = $_FILES[$fkey]['tmp_name'];
				$file_ame = date('Ymdhis')."_".$_FILES[$fkey]['name'];
				$target_file = $destination_path.$file_ame;
				if(move_uploaded_file($_FILES[$fkey]["tmp_name"],$target_file))
				{
					$image_path = $destination_url.$file_ame;
				}else
				{
					$image_path = '';	
				}
				
				$_POST[$fkey] = $image_path;
				$$fldkey = $image_path;
			}
			
		}
	}
	
	$_SESSION['submission_info'] = $_POST;
	$post_title = stripslashes($_POST['post_title']);
	$post_content = stripslashes($_POST['post_content']);
	$post_category = ($_POST['post_category']);
	$post_tags = ($_POST['post_tags']);
	}else
{
	$post_info = get_post_info($_REQUEST['pid']);
	$post_title = stripslashes($post_info['post_title']);
	$post_content = stripslashes($post_info['post_content']);
	
	if($_REQUEST['pid'])
	{
		$is_delet_property = 1;
	}
}

if(function_exists('pt_check_captch_cond') && $_REQUEST['pid']==''){
	if(!pt_check_captch_cond())
	{
	wp_redirect(site_url('/?ptype=submition&backandedit=1&emsg=captch'));
	exit;
	}
}

global $upload_folder_path;
$image_array = array();
if($_SESSION["file_info"])
{
	$image_array = $_SESSION["file_info"];
}else
{
	$image_src = $thumb_img_arr[0];
	if($_REQUEST['pid']){
		$large_img_arr = bdw_get_images($_REQUEST['pid'],'medium');
		$thumb_img_arr = bdw_get_images($_REQUEST['pid'],'thumb');
	}
	$image_src = $large_img_arr[0];
}

if($_REQUEST['pid'])
{
	$large_img_arr = bdw_get_images($_REQUEST['pid'],'medium');
	$thumb_img_arr = bdw_get_images($_REQUEST['pid'],'thumb');
	if($thumb_img_arr)
	{
		$image_array = array_merge($image_array,$thumb_img_arr);
	}
}

?>
<?php get_header(); ?>

<div id="wrapper" class="clearfix">
<div class="content content_full" >
<?php include (TEMPL_ADD_POST_FOLDER . "preview_buttons.php");?>

<?php if($_REQUEST['emsg']==1){?>
<div class="sucess_msg"><?php echo INVALID_USER_PW_MSG;?></div>
<?php }?>

<?php templ_page_title_above(); //page title above action hook?>
<div class="entry">
  <div class="post-meta"> <?php echo templ_page_title_filter($post_title,'<a href="#" rel="bookmark" title="Permanent Link to '.$post_title.'">','</a>'); //page tilte filter?> </div>
</div>
<?php templ_page_title_below(); //page title below action hook?>

<div class="post-content clearfix">


<?php
if($image_array){
?>		
<div class="preview_img">
<img src="<?php echo $image_array[0];?>" style="height:300px; width:300px;" />
<br /><br />
<?php
if(count($image_array)>1)
{
	for($im=1;$im<count($image_array);$im++)
	{
?>
<img src="<?php echo $image_array[$im];?>" style="width:80px; height:80px;" />
<?php
	}
}
?>
</div>
<?php }?>



<div class="preview_content">
<p> <?php echo $post_content;?></p>
<?php edit_post_link( __( 'Edit this Post' ,'templatic'), "\n\t\t\t\t\t\t<p class=\"edit-link\">", "</p>\n\t\t\t\t\t" ); ?>
<?php
if($form_fields)
{
   foreach($form_fields as $fkey=>$fval)
    {
        $fldkey = "$fkey";
        if($$fldkey)
        {
            if($fkey=='post_title' || $fkey=='post_content' || $fkey=='post_category' || $fkey=='post_tags')
            {
                
            }else
            if($fval['type']=='upload')
            {
                echo '<li class="'.$fval['type'].'"><img src="'.$$fldkey.'" /></li>';	
            }elseif($fval['type']=='catcheckbox')
            {
                echo '<li class="'.$fval['type'].'">'.$fval['label'].' : '.implode(",",$$fldkey).'</li>';
            }
			elseif($fval['type']=='multicheckbox')
            {
                echo '<li class="'.$fval['type'].'">'.$fval['label'].' : '.implode(",",$$fldkey).'</li>';
            }
			elseif($fval['type']=='textarea')
            {
                echo '<li class="'.$fval['type'].'">'.$fval['label'].' : '.stripslashes($$fldkey).'</li>';
            }
			elseif($fval['type']=='texteditor')
            {
                echo '<li class="'.$fval['type'].'">'.$fval['label'].' : '.nl2br(stripslashes($$fldkey)).'</li>';
            }
			else
            {
                echo '<li class="'.$fval['type'].'">'.$fval['label'].' : '.$$fldkey.'</li>';
            }
        }
		if($fval['type']=='geo_map')
		{
			echo '<li class="'.$fval['type'].'">'.__('Address','templatic').' : '.$_SESSION['submission_info']['geo_address'].'</li>';	
		}
    }
    echo '</ul>';
}

if($_REQUEST['pid']){
?>
<p><?php echo CUSTOM_MENU_CAT_LABEL.' : '.templ_get_post_taxonomy($_REQUEST['pid'],CUSTOM_CATEGORY_TYPE1);?></p>
<p><?php echo CUSTOM_MENU_TAG_LABEL.' : '.templ_get_post_taxonomy($_REQUEST['pid'],CUSTOM_TAG_TYPE1);?></p>
<?php
}
?>
</div>

<!-- content #end -->
 </div>
<?php get_footer(); ?>