//==============================================================
// CUSTOM SCRIPTS
// Author: Dharma Poudel
// ==============================================================


$(document).ready(function() {

//==============================================================
// TO DO : jQueryNav should be here
// ==============================================================
$('.bp-dropdown-menu').jQueryNav({
	'animationSpeed': 'fast',
	'activeclass': 'active',
	'holdtime' :1000,
	'slidetime' : 100,
	'responsive': true,
	'responsiveat': 500
});

//==============================================================
// get Widget title as a widget class
// ==============================================================

$('.widget_text').each( function(){
	var widgetTitle = $(this).find('.widget-title').text();
	var widgetTitleSlug = widgetTitle.replace(/ /gi, "-");
	widgetTitleSlug = widgetTitleSlug.toLowerCase();
	widgetTitleSlug = "widget-" + widgetTitleSlug;
	$(this).addClass(widgetTitleSlug);
});

/* flexslider */
/* $('#slider').flexslider({
animation: "fade",
slideshow: false,
directionNav:false,
manualControls: ".flex-control-nav li"
}); */

/* Fancybox */
//$(".fancybox").fancybox();

/* Fallback for HTML5 Placeholder */
if(!Modernizr.input.placeholder) {
	$("input[placeholder], textarea[placeholder]").each(function() {
		var placeholder = $(this).attr("placeholder");
		$(this).val(placeholder)
		.focus(function() {
			if($(this).val() == placeholder) {
			$(this).val("");
			}
		})
		.blur(function() {
			if($(this).val() == "") {
			$(this).val(placeholder);
			}
		});
	});
}

});
// end ready function here.


/* select all script */

function SelectAll(textboxID) { 
	var txtbox = document.getElementById(textboxID);
	if(txtbox == null)return;
	if(txtbox.createTextRange){  /*.IE*/
		t = txtbox.createTextRange();
		if(t.select)
			t.select();
		/*
		if(t.execCommand)
			t.execCommand('copy');
		*/    
	}
	if(txtbox.setSelectionRange){ /*.Mozilla*/
		txtbox.setSelectionRange(0,txtbox.value.length);
	}
	else if(txtbox.createTextRange){ /*.Opera 8*/
		var r = txtbox.createTextRange();
		r.select();
	}
	if(txtbox.focus)
		txtbox.focus();			
	return false;		
}     
    
