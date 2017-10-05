jQuery(document).ready( function($) {
	// Collapsible
  $('.page_collapsible').collapsible({
		defaultOpen: 'wcfm_profile_personal_head',
		speed: 'slow',
		loadOpen: function (elem) { //replace the standard open state with custom function
		  elem.next().show();
		},
		loadClose: function (elem, opts) { //replace the close state with custom function
			elem.next().hide();
		},
		animateOpen: function(elem, opts) {
			elem.find('span').addClass('fa-arrow-circle-o-up').removeClass('fa-arrow-circle-o-down').css( { 'float': 'right', 'padding': '5px' } ).show();
			elem.next().stop(true, true).slideDown(opts.speed);
		},
		animateClose: function(elem, opts) {
			elem.find('span').addClass('fa-arrow-circle-o-down').removeClass('fa-arrow-circle-o-up').css( { 'float': 'right', 'padding': '5px' } ).show();
			elem.next().stop(true, true).slideUp(opts.speed);
		}
	});	
	$('.page_collapsible').find('span').addClass('fa');
	$('.collapse-close').find('span').addClass('fa-arrow-circle-o-down').css( { 'float': 'right', 'padding': '5px' } ).show();
	$('.collapse-open').find('span').addClass('fa-arrow-circle-o-up').css( { 'float': 'right', 'padding': '5px' } ).show();
	
	// TinyMCE intialize - About
	if( $('#about').length > 0 ) {
		if( $('#about').hasClass('rich_editor') ) {
			var descTinyMCE = tinymce.init({
																		selector: '#about',
																		height: 75,
																		menubar: false,
																		plugins: [
																			'advlist autolink lists link charmap print preview anchor',
																			'searchreplace visualblocks code fullscreen',
																			'insertdatetime table contextmenu paste code',
																			'autoresize'
																		],
																		toolbar: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify |  bullist numlist outdent indent | link',
																		content_css: '//www.tinymce.com/css/codepen.min.css',
																		statusbar: false,
																		browser_spellcheck: true,
																		entity_encoding: "raw"
																	});
		}
	}
		
	if( $(".country_select").length > 0 ) {
		$(".country_select").select2({
			placeholder: "Choose ..."
		});
	}
	
	// Save Profile
	$('#wcfmprofile_save_button').click(function(event) {
	  event.preventDefault();
	  
	  var about = '';
	  if( $('#about').hasClass('rich_editor') ) {
			if( typeof tinymce != 'undefined' ) about = tinymce.get('about').getContent({format: 'raw'});
		} else {
			about = $('#about').val();
		}
  
	  // Validations
	  $is_valid = true; //wcfm_coupons_manage_form_validate();
	  
	  if($is_valid) {
			$('#wcfm_profile_form').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			var data = {
				action             : 'wcfm_ajax_controller',
				controller         : 'wcfm-profile',
				wcfm_profile_form  : $('#wcfm_profile_form').serialize(),
				about              : about
			}	
			$.post(wcfm_params.ajax_url, data, function(response) {
				if(response) {
					$response_json = $.parseJSON(response);
					$('.wcfm-message').html('').removeClass('wcfm-error').removeClass('wcfm-success').slideUp();
					if($response_json.status) {
						audio.play();
						$('#wcfm_profile_form .wcfm-message').html('<span class="wcicon-status-completed"></span>' + $response_json.message).addClass('wcfm-success').slideDown();
					} else {
						audio.play();
						$('#wcfm_profile_form .wcfm-message').html('<span class="wcicon-status-cancelled"></span>' + $response_json.message).addClass('wcfm-error').slideDown();
					}
					$('#wcfm_profile_form').unblock();
				}
			});	
		}
	});
});