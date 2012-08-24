<?php 

	header("Content-type: text/css");

	$id = $_GET['widget_id'];
	$skin = $_GET['skin'];
	
	if(!empty($skin)){	
		$css = file_get_contents('./skins/' . $skin );
		$widget_skin = preg_replace('/%ID%/',$id, $css);
		echo $widget_skin;
	}

?>