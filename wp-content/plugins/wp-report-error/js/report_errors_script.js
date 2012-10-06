/**
 * @author daddydesign
 * @website http://www.daddydesign.com
 */
var wprpjquery = jQuery.noConflict();

wprpjquery(document).ready(function(){
/** Show/Hide Form **/	
	wprpjquery(".wprperrorsform").click(function(){
	    wprpjquery("#wrpsubmitform").fadeIn(200);
	    wprpjquery("#wrperror").fadeOut();
	    wprpjquery("#wrpresponse").fadeOut();
		wprpjquery('#wrpbody').removeClass('wrphidden').css({'opacity':'0.8','filter':'alpha(opacity=80)'})
	})
	wprpjquery("#wrpcloseform").click(function(){
		wprpjquery("#wrpsubmitform").fadeOut(200);
		wprpjquery('#wrpbody').addClass('wrphidden').css({'opacity':'1','filter':'alpha(opacity=100)'})
	})
/*** Ajax requests via forms ***/
	wprpjquery('.wpreportform').submit(function(){
		
		var url = wprpjquery(this).attr('action');
		var update = url.split("#")[1];
		var email = wprpjquery("#wrpemail").val();
		var info = wprpjquery("#wrpinfo").val();
		var pageurl=wprpjquery("#wrpurl").val();
		var dataString = 'email='+ email + '&info=' + info+'&url='+url+'&pageurl='+pageurl; 
		var emailFormat = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
		var answer = wprpjquery("#wrpanswer").val();
		if (email == '' || info == '') {
			wprpjquery("#wrperror").fadeIn(200).html("All fields are required !");
		}else if (email.search(emailFormat) == -1) {
			wprpjquery("#wrperror").fadeIn(200).html("Invalid Email Address!");
		}else if (answer == "" || answer != 4) {
			wprpjquery("#wrperror").fadeIn(200).html("Incorrect Answer!");
		}else {
			
			wprpjquery.ajax({
				type: "POST",
				url: "" + url + "",
				data: dataString,
				success: function(response){
					
					wprpjquery('#wrpresponse').html(response);
					
				},
				complete: function(){
					wprpjquery("#wrperror").fadeOut(200);
					wprpjquery("#wrpresponse").fadeIn(200);
					wprpjquery("#wrpsubmitform input[type='text'], #wrpsubmitform textarea").val('');
				}
				
			});
			
		}
	    return false;
	})
})
function wprp_limitText(limitField, limitNum) {
    if (limitField.value.length > limitNum) {
        limitField.value = limitField.value.substring(0, limitNum);
    } 
}