/*

// Expects the following markup:
// 
// <div class="expandibox" id="XXXX_UNIQUE_NAME_HERE">
// 	<div class="expandibox_header">
// 		<div class="expandibox_button expandibox_button_collapsed"></div>
// 		<div class="expandibox_status_text">
// 			(3 items)
// 		</div>
// 		<div class="expandibox_header_text">
// 			<h3> title goes here </h3>
// 		</div>
// 		<div class="clear"></div>
// 	</div>
// 	<div class="expandibox_body">
// 			
// 		Content goes here. This DIV is the element that actually slides in and out.
// 			
// 	</div>
// </div>
// 
// ------


*/

(function($){
	
$.fn.expandibox = function(options)	{
	
	return $(this).each(function(){
		
		var settings = $.extend({
			startCollapsed: false
		},options);
		
		var collapsed = settings.startCollapsed;
		
		var head = $(this).find('.expandibox_header');
		var body = $(this).find('.expandibox_body');
		var button = $(this).find('.expandibox_button');
		
		var self = this;
		
		if(body && head) {
			if(settings.startCollapsed) {
				$(body).hide();
			}
			$(head).click(function(){
				self.toggle();
			});
			$(head).css('cursor','pointer');
			
			if(collapsed) {
				buttonToCollapsed();
			}
			else {
				buttonToExpanded();
			}
		}
		
	});
	
	function toggle() {
		this.slider.toggle();
		this.collapsed = !this.collapsed;
		if(!this.collapsed) {
			this.buttonToExpanded();
		}
		else {
			this.buttonToCollapsed();
		}
	}
	
	function expand() {
		if(this.collapsed) {
			this.toggle();
		}
	}
	
	function collapse() {
		if(!this.collapsed) {
			this.toggle();
		}
	}
	
	function buttonToCollapsed() {
		button.addClass('expandibox_button_collapsed');
		button.removeClass('expandibox_button_expanded');
	}
	
	function buttonToExpanded() {
		button.removeClass('expandibox_button_collapsed');
		button.addClass('expandibox_button_expanded');
	}
	
}


})(jQuery);