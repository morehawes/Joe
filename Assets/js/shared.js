//Tooltips
function waymark_setup_parameter_tooltips() {
	jQuery('a.waymark-tooltip').on({
    mouseenter: function(e) {
		  var title = jQuery(this).data('title');
		  jQuery('<p id="waymark-tooltip-active"></p>').text(title).appendTo('body').fadeIn('slow');
    },
    mouseleave: function(e) {
		  jQuery('#waymark-tooltip-active').remove();
    },
    mousemove: function(e) {
			if(waymark_is_touch_device()) {
			  var mousex = e.pageX - 250;			
			} else {
			  var mousex = e.pageX - 220;				
			}

		  var mousey = e.pageY + 5;
		  jQuery('#waymark-tooltip-active').css({ top: mousey, left: mousex });
    }	
	});
}

//Touch device?	
//Thanks https://stackoverflow.com/questions/4817029/whats-the-best-way-to-detect-a-touch-screen-device-using-javascript/4819886#4819886
function waymark_is_touch_device() {
  var prefixes = ' -webkit- -moz- -o- -ms- '.split(' ');
  var mq = function(media_qry) {
    return window.matchMedia(media_qry).matches;
  }

  if (('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch) {
    return true;
  }

  // include the 'heartz' as a way to have a non matching MQ to help terminate the join
  // https://git.io/vznFH
  var media_qry = ['(', prefixes.join('touch-enabled),('), 'heartz', ')'].join('');
  return mq(media_qry);
}

function waymark_setup_accordions() {
	var accordion_container = jQuery('.waymark-accordion-container');
	
	if(! accordion_container.length) {
		return;
	}
	
	accordion_container.addClass('waymark-self-clear');
	
	//For each accordion
	accordion_container.each(function() {
	  //Hide all but first initially
	  var group_index = 0;
		
		//Each group
	  jQuery('.waymark-accordion-group', jQuery(this)).each(function() {
	  	var group = jQuery(this);
	  	
	  	group.addClass('waymark-self-clear');
	  	group.data('waymark-index', group_index);
			
			var group_content = jQuery('.waymark-accordion-group-content', group);
			
			//Show first
		  if(group_index == 0) {	  	
		  	group.addClass('waymark-first waymark-active');
		  	
			  group_content.show().addClass(group_index);
			//Hide others
			} else {
			  group_content.hide().addClass(group_index);
		  }
			
			//Each legend
			jQuery('legend', jQuery(this)).each(function() {
				//Append text to legend (if not already exists)
				var legend_html = jQuery(this).html();			
				if(legend_html.indexOf('[+]') == -1 && legend_html.indexOf('[-]') == -1) {
					var text = (group_index == 0) ? '[-]' : '[+]';
					jQuery(this).html(legend_html + ' <span>' + text + '</span>');			
				}
				
				//Slide
				jQuery(this).click(function() { 	
					var clicked_group_index = jQuery(this).parents('.waymark-accordion-group').data('waymark-index');

					//For each parameter group
					jQuery('.waymark-accordion-group', jQuery(this).parents('.waymark-accordion-container')).each(function() {
						//If this was clicked
						if(jQuery(this).data('waymark-index') == clicked_group_index) {
							var legend = jQuery('legend', jQuery(this));

							//Is it active?
							if(jQuery(this).hasClass('waymark-active')) {
								legend.html(legend.html().replace('[-]', '[+]'));			

								jQuery(this).removeClass('waymark-active');								

								jQuery('.waymark-accordion-group-content', jQuery(this)).slideUp();		  															
							//Not active (yet)
							} else {
								legend.html(legend.html().replace('[+]', '[-]'));			

								jQuery(this).addClass('waymark-active');

								jQuery('.waymark-accordion-group-content', jQuery(this)).slideDown();		  										
							}							
						//Hide others
						} else {
							jQuery(this).removeClass('waymark-active');								

							var legend = jQuery('legend', jQuery(this));
							legend.html(legend.html().replace('[-]', '[+]'));			

							jQuery('.waymark-accordion-group-content', jQuery(this)).slideUp();		  							
						}
					})
				});				
			});
		  
		  group_index++;
	  });		
	});
}

jQuery(document).ready(function() {
	waymark_setup_parameter_tooltips();
	waymark_setup_accordions();	
});