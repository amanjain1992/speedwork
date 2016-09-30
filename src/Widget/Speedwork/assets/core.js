
jQuery(document).ready(function(){
	onload_events();
	onloadEvents();
});

function onloadEvents()
{	//checkbox fix
	$('[notchecked]').each(function(){
		if($(this).is(':checked')) {
			return;
		}
		$(this).before('<input type="hidden" name="'+$(this).attr('name')+'" value="'+$(this).attr('notchecked')+'"  dummy-checkbox="true"/>');								
	});
}

function onload_events()
{	
	$.ajaxSetup({
	    headers: {
	        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    }
	});

	//place holder for non supported browsers
	if(jQuery.fn.placeholder){
		$('[placeholder]').livequery(function() {
			$(this).placeholder();
		});
	}

	$(document).on('click', '[data-prevent]', function(e){
		e.preventDefault();
		return false;
	});

	$(document).on('submit', 'form', function(){
		$(this).clearWatermark();						 
	});


    $(document).on('click', '[data-task]', function() {
        var t = $(this).parents('form:first').find('input[name=task]');
        t.data('task', t.val());
        t.val($(this).data('task'));
    })

	$(document).on('click', '.ac-dologin, [role=login]', function(e){
		if(is_user_logged_in){
			return true;
		}

		e.preventDefault();
		var next = $(this).data('next') || 1;

		$(this).easyTip({
			content : {
				text: function(event, api) {
					var url = 'members/login';
					if (next) {
						url = url + '?next='+encodeURI(next);
					}
					api.set('content.title', "Login to your account");
					api.set('url', url);
					return $.fn.easyTip.ajaxContent(event, api);
				}
			},
			position : {
				viewport : false,
				at: 'center', // Position the tooltip above the link
				my: 'center',
				target: $(window)
			},
			show : {
				modal : true,
				ready: true
			},
			events : {show : null}
		});
		return false;	
	});

	$(document).on('click', '.noty_close', function(e) {
		$(this).parents('.noty_bar:first').slideUp('slow');
	});

	jQuery(document).on('click', '.easySubmit :submit,.easySubmit :button', function(){
		$(this).parents('form:first').find('input').removeClass('clicked');
		$(this).parents('form:first').find('button').removeClass('clicked');
		$(this).addClass('clicked');							
	});

	jQuery('.easySubmit, [data-role="easySubmit"], [role="easySubmit"]').livequery(function(){
		jQuery(this).easySubmit(); 											
	});

	jQuery('.easyValidate, [role="easyValidate"]').livequery(function(){
		jQuery(this).validate(); 											
	});

	jQuery('.easyValidator, [role="easyValidator"]').livequery(function(){
		jQuery(this).validator(); 											
	});

	jQuery('.easyRender, [role="render"]').livequery(function(){
		jQuery(this).speedRender(); 											
	});

	jQuery(document).on('click', "div.error, div.ui-error", function(){
		jQuery(this).fadeOut();
	});

	$(document).on('click', '[notchecked]', function(){
		if($(this).is(':checked')){
			$(this).prev('[dummy-checkbox]').remove();
		}else{
			$(this).before('<input type="hidden" name="'+$(this).attr('name')+'" value="'+$(this).attr('notchecked')+'" dummy-checkbox="true"/>');								
		}
	});
	
	jQuery(document).on('click', '.ac-add', function(){
        jQuery('.ac-attrs tfoot:first tr').clone().appendTo('.ac-attrs tbody');                            
    });
    
    jQuery(document).on('click', '.ac-remove', function(){
        jQuery(this).parents('tr:eq(0)').remove();                                 
    });

	//prevent hash url
	jQuery(document).on('click', 'a[href=#]', function(e) {
		e.preventDefault();
	});
};


var ajaxData = {};
//AUTO PAGING
jQuery(document).ready(function(){

	$('.ac-filter-form').submit(function(e){
		ajaxData = $(this).serializeFormJSON();
		// reset pagination
		$('#page').val(1);
		ajaxPagination.load();
		e.preventDefault();
	});
		
	ajaxPagination.init();
	ajaxPaging.init();

});


var ajaxPaging = {
	
	init:function(){
		var ps_loaded = false
			,oldtop	  = 0;
			
		jQuery(document).on('click', ".ac-load-more", function(e){
			var page = jQuery(this).attr('data-page');
			jQuery("#page").val(page);
			ajaxPaging.load();
			e.preventDefault();
			return false;
		});
		
		jQuery(window).scroll(function(){
								  
			var offset = jQuery(".ac-load-more").offset();
			var tops = (offset) ? offset.top : 0;
			
			if(isNaN(tops) || tops == 0)
				return false;
			
			if(oldtop != tops){
				oldtop = tops;
				var p = jQuery("#page").val();
				
			 if(p < 3) {
			 	ps_loaded = false;
			 }
			}
			if  (!ps_loaded && jQuery(window).scrollTop() + jQuery(window).height() > tops){
				ps_loaded = true;
			   jQuery(".ac-load-more").click();
			}
		}); 
	},
	
	before:function(){
		jQuery(".ac-load-more").next('.ac-load-more-loading').show();
		jQuery(".ac-load-more").remove();
	},
	after:function(responseText){
		jQuery(".ac-load-more-loading").parents('.ac-load-more-remove:eq(0)').remove();
		jQuery(".ac-load-more-loading").remove();
		jQuery('.ac-ajax-content:eq(0)').append(responseText);
		onloadEvents();
	},
	load:function(){
		ajaxData['type'] = 'html';
		var options = { 
			beforeSubmit	: ajaxPaging.before,  	// pre-submit callback 
			success			: ajaxPaging.after,
			data			: ajaxData//{type:'html'}
		};

		jQuery('#ajax_form').ajaxSubmit(options); 
		return false; 
	}
}

//normal pagination
var ajaxPagination = {
	
	 start:function(page) {
	 	var page = page || 1;

	 	$('#page').val(page);
        this.load();
	 },

     init:function(){
		jQuery(document).on('click', ".ac-ajax-pagination a", function(e){
			var page = jQuery(this).data('page');
			if(!page) {
				return false;
			}
			jQuery("#page").val(page);

			this.load();
			e.preventDefault();
			return false;
		});
	 },
	 
	 before:function(){
		jQuery(".ac-ajax-component-loader").show();
		jQuery('.ac-ajax-content').addClass('ui-ajax-overlay');
	 },
	 after:function(responseText){
		jQuery("ac-ajax-component-loader").hide();
		jQuery('.ac-ajax-content').removeClass('ui-ajax-overlay');
		jQuery('.ac-ajax-content').html(responseText);
		var total = jQuery('.ui-load-more-results:first').data('total');
		$('.ac-ajax-total').html(total);
        jQuery("#total").val(total);
		onloadEvents();
	 },
	 
	 load:function()
	 {
		ajaxData['type'] = 'html';
		var options = { 
			beforeSubmit	: ajaxPagination.before,  	// pre-submit callback 
			success			: ajaxPagination.after,		// post-submit callback 
			data			: ajaxData //{type:'html'}// post-submit callback 
		};
		
		if ($("#ajax_form").attr('role') == 'render') {
			return true;
		}
		
		jQuery('#ajax_form').ajaxSubmit(options); 
		return false; 
	 }
}
