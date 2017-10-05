jQuery(document).ready(function($){
  $('.wcfm-wp-fields-uploader').each(function() {
  	addWCFMUploaderProperty($(this));	
  });
});

function addWCFMUploaderProperty(wcfmuploader) {
	wcfmuploader.find('img').each(function() {
	  var src = jQuery(this).attr('src');
	  //if(src.length == 0) jQuery(this).hide();
	  jQuery(this).parent().find('.upload_button').hide();
	  jQuery(this).parent().find('.remove_button').val('x');
	  if( jQuery(this).hasClass('placeHolder') )
	  	jQuery(this).parent().find('.remove_button').hide();
	});
	
	wcfmuploader.find('.upload_button').click(function(e) {
		var wcfmMediaUploader;
		var button = jQuery(this);
		var mime = jQuery(this).data('mime');
		var id = button.attr('id').replace('_button', '');
		
		e.preventDefault();
		
    // If the uploader object has already been created, reopen the dialog
    if (wcfmMediaUploader) {
      wcfmMediaUploader.open();
      return;
    }
    // Extend the wp.media object
    wcfmMediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Media',
      button: {
      text: 'Choose Media'
    }, multiple: false });

    // When a file is selected, grab the URL and set it as the text field's value
    wcfmMediaUploader.on('select', function() {
      var attachment = wcfmMediaUploader.state().get('selection').first().toJSON();
			if(mime  == 'image') {
				jQuery("#"+id+'_display').attr('src', attachment.url).removeClass('placeHolder').show();
				if(jQuery("#"+id+'_preview').length > 0)
					jQuery("#"+id+'_preview').attr('src', attachment.url);
			} else {
				jQuery("#"+id+'_display').attr('href', attachment.url);
			}
			jQuery("#"+id+'_display span').show();
			jQuery("#"+id).val(attachment.url);
			jQuery("#"+id).hide();
			button.hide();
			jQuery("#"+id+'_remove_button').show();
    });
    // Open the uploader dialog
    wcfmMediaUploader.open();
		
		return false;
	});
	
	wcfmuploader.find('img').click(function(e) {
		var wcfmMediaUploader;
		var button = jQuery(this).parent().find('.upload_button');
		var mime = button.data('mime');
		var id = button.attr('id').replace('_button', '');
		
		e.preventDefault();
		
    // If the uploader object has already been created, reopen the dialog
    if (wcfmMediaUploader) {
      wcfmMediaUploader.open();
      return;
    }
    // Extend the wp.media object
    wcfmMediaUploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
      text: 'Choose Image'
    }, multiple: false });

    // When a file is selected, grab the URL and set it as the text field's value
    wcfmMediaUploader.on('select', function() {
      var attachment = wcfmMediaUploader.state().get('selection').first().toJSON();
			if(mime  == 'image') {
					jQuery("#"+id+'_display').attr('src', attachment.url).removeClass('placeHolder').show();
					if(jQuery("#"+id+'_preview').length > 0)
						jQuery("#"+id+'_preview').attr('src', attachment.url);
				} else {
					jQuery("#"+id+'_display').attr('href', attachment.url);
				}
				jQuery("#"+id+'_display span').show();
				jQuery("#"+id).val(attachment.url);
				jQuery("#"+id).hide();
				//button.hide();
				jQuery("#"+id+'_remove_button').show().val('x');
    });
    // Open the uploader dialog
    wcfmMediaUploader.open();
    
		return false;
	});
	
	wcfmuploader.find('.remove_button').each(function() {
		var button = jQuery(this);
		var mime = jQuery(this).data('mime');
		var id = button.attr('id').replace('_remove_button', '');
		if(mime == 'image')
			var attachment_url = jQuery("#"+id+'_display').attr('src');
		else
			var attachment_url = jQuery("#"+id+'_display').attr('href');
		if(!attachment_url || attachment_url.length == 0) {
			button.hide();
			jQuery("#"+id+'_display span').hide();
		} else {
			jQuery("#"+id+'_button').hide();
		}
		button.click(function(e) {
			id = jQuery(this).attr('id').replace('_remove_button', '');
			if(mime == 'image') {
				jQuery("#"+id+'_display').attr('src', jQuery("#"+id+'_display').data('placeholder')).addClass('placeHolder');
			} else {
				jQuery("#"+id+'_display').attr('href', '#');
				jQuery("#"+id+'_button').show();
			}
			jQuery("#"+id+'_display span').hide();
			jQuery("#"+id).val('');
			jQuery(this).hide();
			return false;
		});
	});
}