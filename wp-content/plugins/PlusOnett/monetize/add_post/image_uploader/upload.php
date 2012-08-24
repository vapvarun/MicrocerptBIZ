<?php
	/* Note: This thumbnail creation script requires the GD PHP Extension.  
		If GD is not installed correctly PHP does not render this page correctly
		and SWFUpload will get "stuck" never calling uploadSuccess or uploadError
	 */

	// Get the session Id passed from SWFUpload. We have to do this to work-around the Flash Player Cookie Bug
	if (isset($_POST["PHPSESSID"])) {
		session_id($_POST["PHPSESSID"]);
	}

	session_start();
	ini_set("html_errors", "0");

	// Check the upload
	if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
		echo "ERROR:invalid upload";
		exit(0);
	}

	if (!isset($_SESSION["file_info"])) {
		$_SESSION["file_info"] = array();
	}

	$img_name = time().'-'.$_FILES["Filedata"]["name"];	
	$target_folder_arr = get_image_phy_destination_path();
	$target_folder = $target_folder_arr[0];
	$target_file = $target_folder_arr[0].$img_name;
	$target_file_url = $target_folder_arr[1].$img_name;
	if(move_uploaded_file($_FILES["Filedata"]["tmp_name"],$target_file))
	{
	}else
	{
		echo "ERROR:cannot upload file";
		exit(0);	
	}

	$_SESSION["file_info"][] = $target_file_url;
	echo "FILEID:" . $target_file_url;	// Return the file id to the script

function get_image_phy_destination_path()
{	
	$dirinfo = wp_upload_dir();
	$path = $dirinfo['path'];
	$url = $dirinfo['url'];
	$subdir = $dirinfo['subdir'];
	$basedir = $dirinfo['basedir'];
	$baseurl = $dirinfo['baseurl'];

	$destination_path = $path."/";
	$destination_url = $url."/";
	 
	if (!file_exists($destination_path)){
		$year_path = '';
      $imagepatharr = explode('/',$destination_path);
	   for($i=0;$i<count($imagepatharr);$i++)
		{
		  if($imagepatharr[$i])
		  {
			$year_path .= $imagepatharr[$i]."/";
			  if (!file_exists($year_path)){
				  mkdir($year_path, 0777);
			  }     
			}
		}
	}
	 return array($destination_path,$destination_url);
}	
?>