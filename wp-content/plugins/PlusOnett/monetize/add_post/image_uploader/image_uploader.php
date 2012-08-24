<?php
session_start();
if($_REQUEST['backandedit'])
{
}else
{
	$_SESSION["file_info"] = array();
}
$dirinfo = wp_upload_dir();
$path = $dirinfo['path'];
$url = $dirinfo['url'];
$subdir = $dirinfo['subdir'];
$basedir = $dirinfo['basedir'];
$baseurl = $dirinfo['baseurl'];
?>
<script type="text/javascript" src="<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/js/jquery-1.2.6.min.js"></script>

<script type="text/javascript">var img_delete = "<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/x.png";</script>
<script type="text/javascript" src="<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/swfupload/handlers.js"></script>
<script type="text/javascript" language="javascript">
/* <![CDATA[ */
var thumbnail_filepath = "<?php echo $baseurl;?>/tmp/";
var images_filepath = "<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/swfupload/images/";
var swfu;
function show_image_uploader () {
	swfu = new SWFUpload({
	// Backend Settings
	upload_url: "<?php echo site_url('/?ptype=image_uploader'); ?>",
	post_params: {"PHPSESSID": "<?php echo session_id(); ?>"},
	// File Upload Settings
	file_size_limit : "2 MB",	// 2MB
	file_types : "*.jpg",
	file_types_description : "JPG Images",
	file_upload_limit : "0",
	// Event Handler Settings - these functions as defined in Handlers.js
	//  The handlers are not part of SWFUpload but are part of my website and control how
	//  my website reacts to the SWFUpload events.
	file_queue_error_handler : fileQueueError,
	file_dialog_complete_handler : fileDialogComplete,
	upload_progress_handler : uploadProgress,
	upload_error_handler : uploadError,
	upload_success_handler : uploadSuccess,
	upload_complete_handler : uploadComplete,
	// Button Settings
	button_image_url : "<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/swfupload/b_upload.jpg",
	button_placeholder_id : "spanButtonPlaceholder",
	button_width: 200,
	button_height: 35,
	button_text : '<span class="button"><?php _e('Select Images','templatic');?> (<small class="buttonSmall"><?php _e('2 MB Max','templatic');?></small>)</span>',
	button_text_style : '.button { font-family: Arial, sans-serif; font-size: 14pt; font-weight:bold; color:#ffffff; } .buttonSmall { font-size: 10pt; }',
	button_text_top_padding: 7,
	button_text_left_padding: 7,
	//button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
	button_cursor: SWFUpload.CURSOR.HAND,
	// Flash Settings
	flash_url : "<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/swfupload/swfupload.swf",

	custom_settings : {
		upload_target : "divFileProgressContainer"
	},
	
	// Debug Settings
	debug: false
});
};
/* ]]> */
</script>
	<?php
	if( !function_exists("imagecopyresampled") ){
		?>
	<div class="message">
		<h4><strong>Error:</strong> </h4>
		<p>Application Demo requires GD Library to be installed on your system.</p>
		<p>Usually you only have to uncomment <code>;extension=php_gd2.dll</code> by removing the semicolon <code>extension=php_gd2.dll</code> and making sure your extension_dir is pointing in the right place. <code>extension_dir = "c:\php\extensions"</code> in your php.ini file. For further reading please consult the <a href="http://ca3.php.net/manual/en/image.setup.php">PHP manual</a></p>
	</div>
	<?php
	} else {
	?>
		<div style="margin-bottom:10px; margin-left:150px;">
			<div id="spanButtonPlaceholder"></div>
		</div>
	<?php
	}
	?>
	<div id="divFileProgressContainer" style="height: 75px;"></div>
	<div id="thumbnails">
     <div id="GalleryContainer">
	<?php 
	if($_SESSION["file_info"])
	{
		foreach($_SESSION["file_info"] as $key=>$val)
		{
			if($val){
			?>
				<img src="<?php echo $val; ?>" style="height:100px; width:100px;">
			<?php
			}
		}
	}
	global $thumb_img_arr;
	if($thumb_img_arr)
	{
		for($i=0;$i<count($thumb_img_arr);$i++)
		{		
		?>
        <div class="imageBox" id="div_<?php echo $thumb_img_arr[$i]['id'];?>">
            <div class="imageBox_theImage" id="photo_<?php echo $thumb_img_arr[$i]['id'];?>" style="background-image:url('<?php echo $thumb_img_arr[$i]['file'];?>'); height:100px; width:100px;" >
            <img src="<?php echo $thumb_img_arr[$i]['file'];?>" style="height:100px; width:100px;" />
            </div>
            <div class="imageBox_label"  ><span><a href="javascript:void(0);" id="a_photo_<?php echo $thumb_img_arr[$i]['id'];?>" onClick="javascript:removePhoto(<?php echo $thumb_img_arr[$i]['id'];?>,'');"><img src="<?php echo bloginfo('template_url'); ?>/images/x.png" class="img_delete" alt="" ></a></span></div>
		</div>
		<?php
		}
	}
	?>
    </div></div>
	<p class="message_note listing"><?php echo IMAGE_ORDERING_MSG;?></p>
<?php
if($_REQUEST['pid'])
{
?>	
<div id="insertionMarker">
	<img src="<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/marker_top.gif">
	<img src="<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/marker_middle.gif" id="insertionMarkerLine">
	<img src="<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/marker_bottom.gif">
</div>
<div id="dragDropContent"></div>
<div id="debug" style="clear:both"></div>
<?php if(count($thumb_img_arr)>1){?>
<div style="clear:both;padding-bottom:10px">
<input type="hidden" name="image_sort" id="image_sort"  />
<input type="button" class="b_submit" value="<?php _e('Save Order','templatic');?>" onClick="saveImageOrder();goToIndexforsave();">
<span id="sorted_successmsg_div"></span>
</div>
<?php }?>
<script type="text/javascript" src="<?php echo TEMPL_ADD_POST_URI; ?>image_uploader/js/floating_gallery.js"></script>
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
function goToIndexforsave()
{
	document.getElementById('sorted_successmsg_div').innerHTML = '<?php _e('processing ...','templatic');?>';
	var img_save_url = '<?php echo site_url(); ?>/index.php?ptype=sort_image&pid='+document.getElementById('image_sort').value;
	$.ajax({	
		url: img_save_url ,
		type: 'GET',
		dataType: 'html',
		timeout: 20000,
		error: function(){
			alert('<?php _e('Error loading agent favorite property.','templatic');?>');
		},
		success: function(html){
			document.getElementById('sorted_successmsg_div').innerHTML=html;								
		}
	});
	return false;
}
/* ]]> */
</script>
<?php }?>
<script type="text/javascript">
/* <![CDATA[ */
function removePhoto(image_id,tmp_image_id)
{
	var fav_url; 
	if(tmp_image_id != '' )
	{
			fav_url = '<?php echo site_url(); ?>/index.php?ptype=att_delete&remove=temp&pid='+tmp_image_id;
	}else
	{
			fav_url = '<?php echo site_url(); ?>/index.php?ptype=att_delete&pid='+image_id;			
	}
	$.ajax({	
		url: fav_url ,
		type: 'GET',
		dataType: 'html',
		timeout: 20000,
		error: function(){
			alert('<?php _e('Error loading deletion of property image.','templatic');?>');
		},
		success: function(html){	
			if(image_id =='')
			{
				image_id =tmp_image_id ;
			}	
			document.getElementById('div_'+image_id).style.display="none";								
		}
	});
	return false;
}
show_image_uploader();
/* ]]> */
</script>