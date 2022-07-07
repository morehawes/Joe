function joe_setup_repeatable_settings() {	
	//Each container
	jQuery('.joe-settings-tab .joe-repeatable').each(function() {
		var container = jQuery(this);
				
		//Each form table
		jQuery('.form-table', container).each(function() {
			var form = jQuery(this);
			var clones = [];
			
			form.remove();

			//Each input
			jQuery('.joe-input', form).each(function() {
				var input = jQuery(this);
				//Copy ID to class 
				input.addClass('joe-' + input.data('id'));

				//Get values
				if(input.get(0).nodeName != 'SELECT') {
					var values = input.val();
				} else {
					var values = input.data('multi-value');				
				}

				//Ensure is string
				if(typeof values != 'string') {
					values = values.toString();				
				}
				
				//Determine clone values
				values = values.split(waymark_admin_js.multi_value_seperator);
				for(i in values) {
					if(typeof clones[i] !== 'object') {
						clones[i] = {};						
					}
					clones[i][input.data('id')] = values[i];
				}				
			});
						
			//Each clone
			for(i = 0; i < clones.length; i++) {
				var clone = form.clone();
				
				//Create input
				for(j in clones[i]) {
					var set_value = clones[i][j];
					
					var input = jQuery('.joe-input-' + j, clone);
					input.attr('name', input.attr('name') + '[' + i + ']');
					
					//This is a Select without a valid option
					if((input.get(0).nodeName == 'SELECT') && (! jQuery("option[value='" + clones[i][j] + "']", input).length)) {
						//Use first as default
						set_value = jQuery("option", input).first().val();
					}
					
					//Set value
					input
						.attr('value', set_value)
						.val(set_value)
					;
					
					//Make uneditable
					if(input.parents('.joe-control-group').hasClass('joe-uneditable')) {
						input.attr('readonly', 'readonly');
					}										
				}

				//Delete button
				var delete_button = jQuery('<div />')
					.text('x')
					.attr('title', waymark_admin_js.lang.repeatable_delete_title)
					.addClass('joe-delete')						
					.on('click', function(e) {
						e.preventDefault();
		
						var form = jQuery(this).parents('.form-table');
						form.remove();
						
						return false;
					});		
				clone.append(delete_button);

				container.append(clone);
				container.attr('data-count', i);					
				joe_setup_parameter_tooltips();
			}
	
			var add_button = jQuery('<button />')
				.html('<i class="ion ion-plus"></i>')
				.addClass('button joe-add')
				.on('click', function(e) {
					e.preventDefault();
	
					//Increment count
					var container = jQuery(this).parents('.joe-repeatable');
					var count_old = parseInt(container.attr('data-count'));
					var count_new = count_old + 1;
					container.attr('data-count', count_new);
					
					//Modify clone
					var clone = form.clone();				
					jQuery('.joe-input', clone).each(function() {
						var input = jQuery(this);
						var input_name = input.attr('name') + '[' + count_new + ']';									
																				
						//Update
						input.attr('name', input_name);
						input.attr('placeholder', '');					
					
						//Clear text inputs
						if(input.get(0).nodeName != 'SELECT') {
							input.val('');						
						}

						switch(input.data('id')) {
							case 'line_colour' :
							case 'shape_colour' :
							case 'icon_colour' :
							case 'marker_colour' :
								input.wpColorPicker();
								
								break;

							case 'meta_options' :
								input.parents('tr').hide();
								
								break;
						}

						
					});
					
					jQuery(this).before(clone);
					joe_setup_parameter_tooltips();
// 					waymark_setup_select_meta_type();
// 					waymark_setup_select_icon_type();
					
					return false;
				})
			;
			
			container.append(add_button);
			//form.wrap(container);
			container.sortable();
		});
	});
}

function joe_setup_dropdowns() {
	jQuery('.joe-parameters-container').each(function() {
		var container = jQuery(this);
		
		jQuery('select', container).each(function() {
			//Prefix
			var class_string = 'joe-dropdown-' + jQuery(this).data('id') + '-';			

			//Add new
			class_string += jQuery(this).val();
			container.addClass(class_string);
			
			//On Change
			jQuery(this).on('change', function() {			
				//Prefix
				var class_string = 'joe-dropdown-' + jQuery(this).data('id') + '-';			
				
				//Remove old
				jQuery('option', jQuery(this)).each(function() {
					container.removeClass(class_string + jQuery(this).attr('value'))
				});

				//Add new
				class_string += jQuery(this).val();
				container.addClass(class_string);
			});
		});			
	});
}

function joe_setup_settings_nav() {
	var nav_container = jQuery('body.wp-admin #joe-settings-nav');
	
	if(! nav_container) {
		return false;
	}

	var admin_container = jQuery('#joe-admin-container');
	var form = jQuery('form', admin_container);

	var tabs = jQuery('.joe-settings-tab', admin_container);
	var init_tab_key = nav_container.data('init_tab_key');

	//Change
	var select = jQuery('select', nav_container);
	select.hover(function() {
		jQuery(this).attr('size', jQuery('option', jQuery(this)).length);
  },
  function() {
    jQuery(this).removeAttr('size');
  });
	
	select.change(function () {
	  select.removeAttr('size');
	
		var selected_content_id = jQuery(this).val();
		admin_container.attr('class', '');
		
		//Update form redirect
		var redirect_input = jQuery('input[name="_wp_http_referer"]', form);
		var redirect_to = document.location.toString();
		if(redirect_to.indexOf('content=') > 0) {
			redirect_to = redirect_to.replace('content=' + init_tab_key, 'content=' + selected_content_id);
		} else {
			redirect_to = redirect_to + '&content=' + selected_content_id;
		}
		redirect_input.val(redirect_to);
	
		var show_content = jQuery('.' + selected_content_id).first();
		
		//Each Tab
		jQuery('.joe-settings-tab').each(function() {
			var tab = jQuery(this);
			tab.hide();
			
			//Entire Tab
			if(selected_content_id.indexOf('settings-tab')) {
				//Selected
				if(tab.hasClass(selected_content_id)) {
					tab.show();
					admin_container.addClass('joe-active-' + selected_content_id);
				}
			}
			
			//Each Section
			jQuery('.joe-settings-section', tab).each(function() {
				var section = jQuery(this);			
				
				if(selected_content_id.indexOf('settings-tab') > 0) {
					section.show();		
				} else if(selected_content_id.indexOf('settings-section') > 0) {
					section.hide();

					//Selected
					if(section.hasClass(selected_content_id)) {
						tab.show();		
						section.show();
						admin_container.addClass('joe-active-' + selected_content_id);						
					}
				}					
			});					
		});
	});	
	select.trigger('change');
}

function joe_setup_repeatable_parameters() {
	jQuery('.joe-repeatable-container').each(function() {
		var repeatable_container = jQuery(this);
		var repeatable_count = repeatable_container.data('count');
		
		var template = jQuery('.joe-repeatable-template', repeatable_container);
		template.removeClass('joe-repeatable-template');

		//Do stuff to template (while it's still in the DOM)...			
// 		template = waymark_handle_repeatable_template(template);		
		
		template.remove();

		//Each
		jQuery('.joe-parameters-container', repeatable_container).each(function() {
			var parameter_container = jQuery(this);
			
			var delete_button = jQuery('<button />')
				.html('<i class="ion ion-android-delete"></i>')
				.addClass('button joe-delete')
				.on('click', function(e) {
					e.preventDefault();

					parameter_container.remove();						
				})
			;
			parameter_container.append(delete_button);		
		});

		//Add		
		var add_button = jQuery('.joe-repeatable-add', repeatable_container).first();
		add_button.on('click', function(e) {
			e.preventDefault();
	
			var clone = template.clone();
			
			//Update inputs
			jQuery('.joe-input', clone).each(function() {
				var input = jQuery(this);
			
				input.attr('name', input.attr('name').replace('__count__', repeatable_count));
			});	

			jQuery('.waymark-control-label', clone).each(function() {
				var label = jQuery(this);
			
				label.attr('for', label.attr('for').replace('__count__', repeatable_count));
			});							

			//Add		
			add_button.before(clone);

			//Do stuff to clone (now it's in the DOM)...			
			clone = waymark_handle_repeatable_clone(clone);
			
			joe_setup_dropdowns();
			
			//Update count
			repeatable_container.data('count', ++repeatable_count);
			
			return false;
		});
	});
}
function waymark_admin_message(text = null, type = 'info', container_selector = '#wpbody-content') {
	if(text) {
		var prefix = '';
		
		//Prefix available?
		if(typeof waymark_admin_js.lang[type + '_message_prefix'] !== 'undefined') {
			prefix = waymark_admin_js.lang[type + '_message_prefix'];		
		}
				
		switch(type) {
// 			case 'error' :
// 				
// 				break;
			default:
// 			case 'info' :

				break;			
		}
		
		if(prefix) {
			prefix = '<b>[' + prefix + ']</b> ';
		}
		
		var message = prefix + text;

		//Get container
		var container = jQuery(container_selector).first();

		//Container exists
		if(container.length) {
			//Remove existing
			jQuery('.waymark-notice', container).each(function() {
				jQuery(this).remove();
			});

			var notice_div = jQuery('<div />')
				.attr({
					'class' : 'waymark-notice notice notice-' + type
				})
			;
		
			var notice_p = jQuery('<p />')
				.html(message)
			;
		
			//Put together
			notice_div.append(notice_p);
		
			//Display
			container.prepend(notice_div);	
		}	else {
			alert(message);			
		}
	}
}

jQuery(document).ready(function() {
	joe_setup_settings_nav();
	joe_setup_repeatable_settings();
	joe_setup_repeatable_parameters();
	joe_setup_dropdowns();
});