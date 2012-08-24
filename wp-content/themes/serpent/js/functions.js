
function setCookie(c_name,value,exdays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate() + exdays);
	var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function resizeVideo(){
	if(jQuery('.embedded_videos').length){
  		var iframe_width = jQuery('.embedded_videos').parent().width();
  		var_iframe_height = iframe_width/1.37;	
  		jQuery('.embedded_videos iframe ').each(function(){

  			jQuery(this).attr('width',iframe_width);
  			jQuery(this).attr('height',var_iframe_height);
  		});

  		jQuery('.embedded_videos div.video-js ').each(function(){

  			jQuery(this).attr('width',iframe_width);
  			jQuery(this).attr('height',var_iframe_height);
  			jQuery(this).css('width',iframe_width);
  			jQuery(this).css('height',var_iframe_height);
  		});
  		

  	}
}

jQuery(document).ready(function(){
  
  	/*resize FB comments depending on viewport*/

  	setTimeout('viewPort()',3000); 
  	
  	resizeVideo();

  	jQuery( window ).resize( function(){
       viewPort();
       resizeVideo();
    });

  	/*Fixed user bar*/
	jQuery(function () {
		var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;
		if (!msie6 && jQuery('.sticky-bar').length != 0) {
			var top = jQuery('#sticky-bar').offset().top - parseFloat(jQuery('#sticky-bar').css('margin-top').replace(/auto/, 0));
			jQuery(window).scroll(function (event) {
				// what the y position of the scroll is
				var y = jQuery(this).scrollTop();
				// whether that's below the form
				if (y >= top-0) {
					// if so, ad the fixed class
					jQuery('#sticky-bar').addClass('fixed');
				} else {
					// otherwise remove it
					jQuery('#sticky-bar').removeClass('fixed');
				}
			});
		}
	});
	
	/* Accordion */
	jQuery('.cosmo-acc-container').hide();
	jQuery('.cosmo-acc-trigger:first').addClass('active').next().show();
	jQuery('.cosmo-acc-trigger').click(function(){
		if( jQuery(this).next().is(':hidden') ) {
			jQuery('.cosmo-acc-trigger').removeClass('active').next().slideUp();
			jQuery(this).toggleClass('active').next().slideDown();
		}
		return false;
	});
	
	//Superfish menu
	jQuery("ul.sf-menu").supersubs({
			minWidth:    12,
			maxWidth:    32,
			extraWidth:  1
		}).superfish({
			delay: 200,
			speed: 250
		});
		
	/*Fixed user bar*/
	jQuery(function () {
		var msie6 = jQuery.browser == 'msie' && jQuery.browser.version < 7;
		if (!msie6 && jQuery('.sticky-bar').length != 0) {
			var top = jQuery('#sticky-bar').offset().top - parseFloat(jQuery('#sticky-bar').css('margin-top').replace(/auto/, 0));
			jQuery(window).scroll(function (event) {
				// what the y position of the scroll is
				var y = jQuery(this).scrollTop();
				// whether that's below the form
				if (y >= top-0) {
					// if so, ad the fixed class
					jQuery('#sticky-bar').addClass('fixed');
				} else {
					// otherwise remove it
					jQuery('#sticky-bar').removeClass('fixed');
				}
			});
		}
	});
	
	/* Hide Tooltip */
	jQuery(function() {
		jQuery('a.close').click(function() {
			jQuery(jQuery(this).attr('href')).slideUp();
            jQuery.cookie(cookies_prefix + "_tooltip" , 'closed' , {expires: 365, path: '/'});
            jQuery('.header-delimiter').removeClass('hidden');
			return false;
		});
	});
	
	/* Mosaic fade */
	jQuery('.readmore').mosaic();
	jQuery('.circle, .gallery-icon').mosaic({
		opacity:	0.5
	});
	jQuery('.fade').mosaic({
		animation:	'slide'
	});

	/* initialize tabs */
	jQuery(function() { 
		jQuery('.cosmo-tabs').tabs({ fxFade: true, fxSpeed: 'fast' });
		jQuery( 'div.cosmo-tabs' ).not( '.submit' ).find( '.tabs-nav li:first-child a' ).click();
	});
	
	/* Hide title from menu items */
	jQuery(function(){
		jQuery("li.menu-item > a").hover(function(){
			jQuery(this).stop().attr('title', '');},
			function(){jQuery(this).stop().attr();
		});
		
		  
	});
	jQuery( '.toggle-menu' ).click( function(){
        jQuery('.mobile-nav-menu').slideToggle();
    });
	jQuery(document).ready(function() {
	    jQuery(".mobile-nav-menu").saccordion({
	        saccordion:false,
	        speed: 200,
	        closedSign: '',
	        openedSign: ''
	    });
	});
	jQuery(document).ready(function() {
		jQuery('aside.widget').append('<div class="clear"></div>');
	});
	/* Mobile responsiveness */
  	var sidebar_type = jQuery('#secondary').attr('class');
	jQuery(window).on('resize load orientationChanged', function() {
	  // do your stuff here
	  if(jQuery(this).width() < 767){
	    jQuery('#d-menu').addClass('hide');
	    jQuery('.mobile-menu').removeClass('hide');
	    jQuery('.mobile-menu').css('display','block');
	    jQuery('#sticky-bar').css('display','none');
	    jQuery('.keyboard-demo').css('display','none');
	    jQuery('#menu-login').css('display','none');
	    jQuery('#secondary').removeClass('right-sidebar left-sidebar');
	  } else{
	    jQuery('#d-menu').removeClass('hide');
	    jQuery('.mobile-menu').css('display','none');
	    jQuery('.mobile-menu').addClass('hide');
	    jQuery('#sticky-bar').css('display','block');
	    jQuery('.keyboard-demo').css('display','block');
	    jQuery('#menu-login').css('display','block');
	    jQuery('#secondary').addClass(sidebar_type);
	  }
	});

	/* twitter widget */
	if (jQuery().slides) {
		jQuery(".dynamic .cosmo_twitter").slides({
			play: 5000,
			effect: 'fade',
			generatePagination: false,
			autoHeight: true
		});
	}
	
	/* show/hide color switcher */
	jQuery('.show_colors').toggle(function(){
		jQuery(".style_switcher").animate({
		    left: "10px"

		  }, 500 );
	}, function () {
		jQuery(".style_switcher").animate({
		    left: "-152px"

		  }, 500 );

	});
	
	 /* widget tabber */
    jQuery( 'ul.widget_tabber li a' ).click(function(){
        jQuery(this).parent('li').parent('ul').find('li').removeClass('active');
        jQuery(this).parent('li').parent('ul').parent('div').find( 'div.tab_menu_content.tabs-container').fadeTo( 200 , 0 );
        jQuery(this).parent('li').parent('ul').parent('div').find( 'div.tab_menu_content.tabs-container').hide();
        jQuery( jQuery( this ).attr('href') + '_panel' ).fadeTo( 600 , 1 );
        jQuery( this ).parent('li').addClass('active');
    });
	
		
	/*toogle*/
	/*Case when by default the toggle is closed */
	jQuery(".open_title").toggle(function(){ 
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('toggle_close'); 
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
		}, function () {
		
			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('toggle_close');
			jQuery(this).find('a').addClass('show');		 
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();
			
	});
	
	/*Case when by default the toggle is oppened */		
	jQuery(".close_title").toggle(function(){ 
			jQuery(this).next('div').slideUp();
			jQuery(this).find('a').removeClass('toggle_close');
			jQuery(this).find('a').addClass('show');		 
			jQuery(this).find('.title_open').hide();
			jQuery(this).find('.title_closed').show();
		}, function () {
			jQuery(this).next('div').slideDown();
			jQuery(this).find('a').removeClass('show');
			jQuery(this).find('a').addClass('toggle_close');
			jQuery(this).find('.title_closed').hide();
			jQuery(this).find('.title_open').show();
			
	});	
	
	/*Accordion*/
	jQuery('.cosmo-acc-container').hide();
	jQuery('.cosmo-acc-trigger:first').addClass('active').next().show();
	jQuery('.cosmo-acc-trigger').click(function(){
		if( jQuery(this).next().is(':hidden') ) {
			jQuery('.cosmo-acc-trigger').removeClass('active').next().slideUp();
			jQuery(this).toggleClass('active').next().slideDown();
		}
		return false;
	}); 
	
	//Scroll to top
	jQuery(window).scroll(function() {
		if(jQuery(this).scrollTop() != 0) {
			jQuery('#toTop').fadeIn();	
		} else {
			jQuery('#toTop').fadeOut();
		}
	});
	jQuery('#toTop').click(function() {
		jQuery('body,html').animate({scrollTop:0},300);
	});
	
	jQuery('div.sticky-bar li.my-add').mouseover(function () {
		jQuery('.show-first').hide();
		jQuery('.show-hover').fadeIn(10);
	});
	
	jQuery('#sticky-bar').mouseleave(function () {
		jQuery('.show-hover').hide(); 
		//jQuery('.show-first').show('slow');
		jQuery('.show-first').fadeIn(10);
	});
});

/* grid / list switch */



/*functions for style switcher*/

function changeBgColor(rd_id,element){

    if(element == "footer"){
		jQuery('.b_head').css('background-color', '#'+jQuery('#'+rd_id).val());
		jQuery('.b_body_f').css('background-color', '#'+jQuery('#'+rd_id).val());

		jQuery('#link-color').val('#'+jQuery('#'+rd_id).val());
		jQuery.cookie(cookies_prefix + "_b_f_color",'#' + jQuery('#'+rd_id).val(), {expires: 365, path: '/'});
    }
    else if(element == "content"){
    	jQuery('#main').css('background-color', '#'+jQuery('#'+rd_id).val());
    	jQuery('#content-link-color').val('#'+jQuery('#'+rd_id).val());
    	jQuery.cookie(cookies_prefix + "_content_bg_color",'#' + jQuery('#'+rd_id).val(), {expires: 365, path: '/'});
    }


    return false;
}

function setPickedColor(a,element){
	if(element == 'footer'){
		jQuery('.b_f_c').css('background-color', a);
		jQuery.cookie(cookies_prefix + "_footer_bg_color",a, {expires: 365, path: '/'}); /*de_css*/
	}
	else if(element == "content"){
		jQuery('body').css('background-color', a);
		jQuery.cookie(cookies_prefix + "_content_bg_color",a, {expires: 365, path: '/'});
	}

}

function setBgColor(rb_id,element){
	jQuery('#' + rb_id).trigger('click');
	changeBgColor(rb_id,element);
}

function setBgImage(rb_id){
	jQuery('#' + rb_id).trigger('click');
	changeBgImage(rb_id);
}

/* Keyboard toggles */

jQuery(function() {
	jQuery('.keyboard-demo').click(function() {
		jQuery('#big-keyboard').slideToggle();
		var top = jQuery( 'body' ).offset().top;
		jQuery.scrollTo( top, 400 );
	});
});
jQuery(function() {
	jQuery('.close').click(function() {
		jQuery(this).parent().parent().slideToggle();
	});
});
/* E.of keyboard toggles*/



/*EOF functions for style switcher*/

function viewPort(){  
	/* Determine screen resolution */
	//var $body = jQuery('body');
	wSizes = [1200, 960, 768, 480, 320, 240];
	wSizesClasses = ['w1200', 'w960', 'w768', 'w480', 'w320', 'w240'];
	
	//$body.removeClass(wSizesClasses.join(' '));
	var size = jQuery(this).width();
	//alert(size);
	for (var i=0; i<wSizes.length; i++) { 
		if (size >= wSizes[i] ) { 
			//$body.addClass(wSizesClasses[i]);

			
			jQuery('.fb_iframe_widget iframe,.fb_iframe_widget span').css({'width':jQuery('#primary').width() });   
			
			break;
		}
	}
	if(typeof(FB) != 'undefined' ){
		FB.Event.subscribe('xfbml.render', function(response) {
			FB.Canvas.setAutoResize();
		});
	}  
	/** Mobile/Default      -   320px
 * Mobile (landscape)  -   480px
 * Tablet              -   768px
 * Desktop             -   960px
 * Widescreen          -   1200px
 * Widescreen HD       -   1920px*/
	
}