var $ai = jQuery.noConflict();
$ai(document).ready(function(){
	$ai('.author-info').hide();
	$ai('#show_author_info').click(function() {
	 	$ai('.author-info').slideDown(1000);
	});
	$ai('#hide_author_info').click(function() {
	 	$ai('.author-info').slideUp(1000);
	});

});