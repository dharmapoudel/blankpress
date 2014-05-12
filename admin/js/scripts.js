/**
Author: Dharma Poudel (@rogercomred)
Author URI: www.dharmaraj.com.np
Description: 
/**/
jQuery(document).ready(function($) {
	// be smart: prevent default behaviour on links with #
	$('a[href^="#"]').click(function(e){
		e.preventDefault();
	});
	
	// initializing my own jQueryTab plugin 
    $('.tabs-1').jQueryTab({
        initialTab:1,				// tab to open initially; start count at 1 not 0
        tabInTransition: 'slideUpIn',
        tabOutTransition: 'slideUpOut',
        cookieName: 'active-tab-1' 
    });
		
	// Image Upload
	$('.tab_content').on('click', '.media_image',  function() {	// when image is clicked
		// Get the Text element.
		var text = $( this ).siblings('.media_imageurl');

		// Show WP Media Uploader popup
		tb_show('Upload Image', 'media-upload.php?type=image&TB_iframe=true&post_id=0', false);

		// Re-define the global function 'send_to_editor'
		// Define where the new value will be sent to
		window.send_to_editor = function( html ) {
			// Get the URL of new image
			var src = $( 'img', html ).attr( 'src' );
			// Send this value to the Text field.
			text.attr( 'value', src ).change(function(){
				// bind the value of this field to preview field
				$( this ).parent().find('.media_image').attr({'src':this.value}).addClass('no_background');
			}).change();
			tb_remove(); // Then close the popup window
		}
		return false;
	} );
	
	//dynamically add new instance of social media
	$('.socialmedia_add').on('click', function(){
		$ul = $(this).parent('.row').find('ul');
        $(this).prev('.description').removeClass('hide');
		$ul.append('<li><img class="media_image" src=""><input type="hidden" name ="socialmedia_iconurl[]" class="media_imageurl" value="" /><input type="text" name="socialmedia_name[]"  value="" class="media_text" /><input type="text" name="socialmedia_url[]"  value="" class="media_url" /><a href="#" class="option_remove"><span>x</span></a></li>');
		$('.tabs-1').data('jQueryTab').resize();
    $('.socialmedia > li:last').hide().slideDown('500');
	});
	
		
		var timer = 0;
		
		// save all data automatically
		$('.tab_content').on('keyup', 'input, textarea', function(){
				clearTimeout(timer);
				timer = setTimeout(function(){
					$.post(bp.adminUrl, {
						action: 'remove_socialmedia',
						data: $('form').serialize()
					}); 
				}, 3000);
		});
		
		// animate the submit button with progress bar
		$('.progress-button').on('click', function(e){
			e.preventDefault();
			
			var $this = $(this).css('padding-right','35px').attr('disabled',true),
			$progressbar = $this.find('.progress-bar'),
			$progresstext = $this.find('.progress-text'),
			$spiner = $this.find('.spiner').css('opacity','1'),
			$initial_text = $progresstext.text();
			clearTimeout( timer );
			
			
			var progress = 0;
			var interval = setInterval( function() {
				$progresstext.text('saving');
				progress = Math.min( progress + Math.random() * 0.1, 1 );
				$progressbar.css('width', progress * ($this.outerWidth()+10));
				if( progress === 1 ) {
					clearInterval( interval );
					$progressbar.css('width', 0);
					$this.css('padding-right','5px');
					$spiner.css('opacity','0');
					$progresstext.text('saved');
					$.post(bp.adminUrl, {
						action: 'remove_socialmedia',
						data: $('form').serialize()
					}, function(){
					
							$progresstext.text($initial_text);
							$this.css('padding-right','5px').attr('disabled',false);
					}); 
				}
			}, 200 );
		});
		
		// remove the social media and webclip icon
		$('.tab_content').on('click', '.option_remove', function(e){
			e.preventDefault();
			$current_li = $(this).parent('li');
            $ul = $current_li.parent('ul');
            $span = $ul.next('.description');
			$media_imageurl = $current_li.find('.media_imageurl').val();
			$media_name = $current_li.find('.media_text').val();
			$media_url = $current_li.find('.media_url').val();
			$current_li.slideUp('400', function(){
        $(this).remove();
        if(!$ul.find('li').length){$span.addClass('hide');}
        $('.tabs-1').data('jQueryTab').resize();
        
				if($media_imageurl == '' &&  $media_name == '' &&  $media_url =='') { return; }
				clearTimeout(timer);
				timer = setTimeout(function(){
					$.post(bp.adminUrl, {
						action: 'remove_socialmedia',
						data: $('form').serialize()
					});	 
				}, 1000);
			})
		});
	
	//scroll to the custom taxonomy-tag on admin panel
		jQuery('[id$="-all"] > ul.categorychecklist').each(function() {
			var $list = jQuery(this);
			var $firstChecked = $list.find(':checked').first();

			if ( !$firstChecked.length )
				return;

			var pos_first = $list.find(':checkbox').position().top;
			var pos_checked = $firstChecked.position().top;

			$list.closest('.tabs-panel').scrollTop(pos_checked - pos_first + 5);
		});
		
	//dynamically add new instance of gallery items
	$('.pkg_media_add').on('click', function(){
		$ul = $(this).parent().find('ul');
		$ul.append('<li><img class="media_image" src=""><input type="text" name ="bpslider_imageurl[]" value="" class="media_imageurl bpslider_imageurl" /><input type="text" name ="bpslider_link[]" value="" class="bpslider_link" /><a href="#" class="pkg_option_remove"><span>x</span></a><input type="text" name="bpslider_text[]" value="" class="bpslider_text" /></li>');
    $ul.children().last().hide().slideDown('500');
	});
		
	// remove the gallery items
	$('.tab_content_gallery').on('click', '.pkg_option_remove', function(e){
				e.preventDefault();
				$current_li = $(this).closest('li');
				$current_li.slideUp('400', function(){
					$(this).remove();
				});
	});
			
	// Image Upload for the package gallery
	$('.tab_content_gallery').on('click', '.media_image',  function() {	// when image is clicked
		// Get the Text element.
		var text = $( this ).siblings('.media_imageurl');

		// Show WP Media Uploader popup
		tb_show('Upload Image', 'media-upload.php?type=image&TB_iframe=true&post_id=0', false);

		// Re-define the global function 'send_to_editor'
		// Define where the new value will be sent to
		window.send_to_editor = function( html ) {
			// Get the URL of new image
			var src = $( 'img', html ).attr( 'src' );
			// Send this value to the Text field.
			text.attr( 'value', src ).change(function(){
				// bind the value of this field to preview field
				$( this ).parent().find('.media_image').attr({'src':this.value}).addClass('no_background');
			}).change();
			tb_remove(); // Then close the popup window
		}
		return false;
	} );


	// Move the excerpt box above the WYSIWYG editor in animal page
	if($("input[value='animal']").length > 0){
		var $excerpt	= $('#postexcerpt'),
			$wysiwyg	= $('#postdivrich');
		$wysiwyg.prepend($excerpt);
		$excerpt.find('textarea').css('min-height', '100px');
	}
    
	//hide the slug edit box on bpslider
	if($("input[value='slider']").length > 0){
		$('#edit-slug-box, #post-body #normal-sortables').hide();
	}
	
    
    
	
}); // end ready function 