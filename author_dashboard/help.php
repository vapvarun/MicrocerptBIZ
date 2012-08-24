<?php
require_once('../wp-load.php');

if ( !is_user_logged_in()) {
	echo "UH OH! You aren't allowed to view this page. Please go back from whence you came.";
}
?>
<!--/////////// edit only below this line ////////// -->
<p>this is a sample entry</p>