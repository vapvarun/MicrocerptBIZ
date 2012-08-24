<?php include TT_ADMIN_TPL_PATH.'header.php'; ?>
<div class="info top-info"></div>
<div class="ajax-message<?php if ( isset( $message ) ) { echo ' show'; } ?>">
	<?php if ( isset( $message ) ) { echo $message; } ?>
</div>
	<div id="content">
		<div id="options_tabs">
			<ul class="options_tabs">
				<li><a href="#option_display_custom_usermeta">Display User Meta</a><span></span></li>
				<li><a href="#option_add_custom_usermeta">Add / Edit User Meta</a><span></span></li>						
			</ul> 