<?php
/*
Plugin Name: Report Post mailto link
Description: Provides function that returns Report Post mailto link
Version: 1.0
Author: Jon Caine
Author URI: 
*/

function reportpostemail ($post_guid) {
 if(function_exists('ReportPageErrors')){ echo ReportPageErrors(); }
}

?>