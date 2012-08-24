<?php
session_start();
ob_start();
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
?>
<?php get_header(); 

include ('add_step4.php');
?>


<?php get_footer(); ?>
