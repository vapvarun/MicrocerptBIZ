/*
 * SimpleModal Basic Modal Dialog
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2009 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: basic.js 212 2009-09-03 05:33:44Z emartin24 $
 *
 */
$(document).ready(function () {
	$('.listing_info li a.l_send_inquiry').click(function (e) {
		e.preventDefault();
		$('#basic-modal-content').modal();
	});
});
$(document).ready(function () {
	$('.listing_info li a.l_sendtofriend').click(function (e) {
		e.preventDefault();
		$('#basic-modal-content2').modal();
	});
});