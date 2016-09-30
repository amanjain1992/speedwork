
function getForm($this) {

    var form = null;
    if ($this.parents('form:eq(0)').length > 0) {
        var form = $this.parents('form:eq(0)');
    } else if($this.parents().find('.ac-filter-form').length > 0){
        var form = jQuery('.ac-filter-form');
    } else{
        var form = jQuery('#ajax_form');
    }

    return form;
}

function displayNoty(response, callback, $this) {

    try {
        var res = JSON.parse(response);
    } catch (err) {
        var res = response;
        if ('object' !== typeof res) {
            res = {}
        }
    }

    if(res.status == 'OK') {
        noty({text:res.message, type:'success', layout:'topCenter'});
    } else if(res.status == 'INFO') {
        noty({text:res.message, type:'info', layout:'topCenter'});
    } else {
        noty({text:res.message, type:'error', layout:'topCenter'});
    }

    if (res.login) {
        var link = 'members/login';
            link += (res.next) ? '?next='+encodeURI(res.next) : '';

        res.link = {};
        res.link.url = _link(link);
        res.link.title = "Login to your account"
    }
        
    if (res.link) {
        var qtipSource = "tip-link";
        $("#" + qtipSource).remove();
        $('body').append($('<a id="' + qtipSource + '" style="display:none"></a>'));
        $("#" + qtipSource).easyTip({
            content: {
                text: function(event, api) {
                    api.set('content.title', res.link.title);
                    api.set('url', res.link.url);
                    return $.fn.easyTip.ajaxContent(event, api);
                }
            },
            position: {
                viewport: false,
                at: 'center', // Position the tooltip above the link
                my: 'center',
                target: $(window)
            },
            show: {
                modal: true,
                ready: true
            },
            events: {
                show: null
            }
        }).qtip('show');
        return true;
    }

    if (res.redirect) {
        var url = res.redirect;
        url = url.replace('&amp;', '&');
        setTimeout(function() {
            window.top.location.href = url;
        }, 1000);
    }

    if (typeof callback == 'function') {
        callback(res, $this);
    } else if(typeof window[callback] == 'function') {
        window[callback](res, $this);
    }
}

$(document).ready(function(){

    $(document).on('click', '[data-href]', function(e){
             
        e.preventDefault();
    
        var $this  = $(this);
        var callback = $this.data('callback');

        $.ajax({
           url: $(this).data('href'),
           data:{format:'json'},
           success:function(response){
                displayNoty(response, callback, $this);
           }
        });
    });

	//common delete script
	$(document).on('click', '.ac-action-delete, [data-delete], [data-action="delete"]', function(e){
		
		if(!confirm(_e('Are you sure want to delete?'))) {
			return false;
        }
			
		var $this  = $(this);
    	var tag    = $this.attr('data-tag') || 'tr'; 
    	var parent = $this.parents(tag+':eq(0)');
        var link   = $(this).attr('href') || $this.data('delete');
				
		$.ajax({
		   url: link,
		   data:{format:'json'},
		   success:function(res){
			    if(res.status == 'OK') {
					parent.remove();
					$('.ac-ajax-total').html(parseInt($('.ac-ajax-total').text()) - 1);
			    }

                displayNoty(res);
		   }
		});
				
		e.preventDefault();
	});

	//common delete script
	$(document).on('click', '.ac-action-request', function(e){
		
		var $this  = $(this);
        var link   = $(this).attr('href') || $this.data('status');
				
		$.ajax({
		   url: link,
		   data:{format:'json'},
		   success:function(res){
                displayNoty(res);
		   }
		});
			
		e.preventDefault();
	});

    //common status change script
    $(document).on('click', '.ac-action-status a', function(e){
        
        var $this = $(this);
        var parent = $this.parent('.ac-action-status');
                
        $.ajax({
           url: parent.data('link'),
           data:{status:$this.data('status'), format:'json'},
           success:function(res){
               if(res.status == 'OK') {
                    parent.find('a:hidden').show();
                    $this.hide();
               }
               displayNoty(res);
           }
        });
                
        e.preventDefault();
    });

    //common status change script
    $(document).on('change', '.ac-action-status select', function(e){
        
        var $this = $(this);
        var parent = $this.parent('.ac-action-status');
        var v = $(this).val();
                
        $.ajax({
           url: parent.data('link'),
           data:{status:v, format:'json'},
           success:function(res){
               displayNoty(res);
           }
        });
                
        e.preventDefault();
    });

	//common delete script
	$(document).on('click', '.ac-action-request-confirm', function(e){
		
		if(!confirm(_e('Are you sure want to proceed?')))
			return false;

		var $this = $(this);
				
		$.ajax({
		   url: $(this).attr('href'),
		   data:{format:'json'},
		   success:function(res){
			   displayNoty(res);
		   }
		});
				
		e.preventDefault();
	});


	$(document).on("click", ".ac-filter-reset", function(e) {
		var form  = getForm($(this));
		form[0].reset();
		e.preventDefault();
		form.submit();

		if ($.browser.msie) {
			init();
		}
	});

	$(document).on('click', ".ac-filter-export", function(e){
		var $this = $(this);
		var form  = getForm($this);
		$.fn.clearWatermark();
		var base = $this.attr('base-href');
		
		if(base === undefined){
			$this.attr('base-href', $this.attr('href'));
		}
		$this.attr('href', $this.attr('base-href') + '&' + form.serialize());
	});

    $(document).on('click', '[data-task]', function(e){
        e.preventDefault();

        var $this = $(this);
        var form = getForm($this);

        var task = $this.data('task');
        var name = $this.data('task-name') || 'task';
        var input = form.find("input[name='"+name+"']");

        form.find("input[name='page']").val(1);

        if(input.length > 0){
            var oldtask = input.attr('task', input.val());
            input.val(task);
        } else{
            jQuery('<input/>', {
                name: name,
                value: task,
                type: 'hidden'
            }).appendTo(form);
        }

        form.submit(); 
    });

	$(document).on('click', '[data-order]', function(e){
        e.preventDefault();

        var name= jQuery(this).attr('data-order');
        var order= jQuery(this).attr('data-order-type');
        jQuery(this).addClass('ordering');
        var task = 1;

        if(new RegExp('ASC').test(order)) {
            task = 0;
        }

        if(task == 1) {
            jQuery(this).attr('data-order-type','ASC');
            jQuery(this).removeClass('desc');
            jQuery(this).addClass('asc');
            jQuery(this).children('i').removeClass('fa-sort-amount-desc').addClass('fa-sort-amount-asc');
        }else{
            jQuery(this).removeClass('asc');
            jQuery(this).addClass('desc');
            jQuery(this).attr('data-order-type','DESC');
            jQuery(this).children('i').removeClass('fa-sort-amount-asc').addClass('fa-sort-amount-desc');
        }
        var form = getForm($(this));;
      
        if(form.find('.ac-sort-name').length > 0){
            jQuery('.ac-sort-name').val(name);
        } else{
            jQuery('<input/>', {
                class: 'ac-sort-name',
                name: 'sort',
                value: name,
                type: 'hidden'
            }).appendTo(form);
        }
        
        if(form.find('.ac-sort-order').length > 0 ){
            jQuery('.ac-sort-order').val(order);
        } else{
            jQuery('<input/>', {
                class: 'ac-sort-order',
                name: 'order',
                value: order,
                type: 'hidden'
            }).appendTo(form);
        }

        form.submit(); 

    });

    $(document).on('click', '[data-prevent],.quickboxmodal,.qtipmodal,.quickbox,.qtipbox', function(e){
        e.preventDefault();
    });

    // Drag and drop sortable
    if($.fn.sortable){

    	var _gridSortHelper = function(e, ui) {
    		ui.children().each(function() {
    			$(this).width($(this).width());
    		});
    		return ui;
    	};

    	var _gridSortUpdateHandler = function(e, ui) {

            var form = getForm($(this));

    		if(form.find('.ac-task-input').length > 0){
                jQuery('.ac-task-input').val('sorting');
            } else{
                jQuery('<input/>', {
                    class: 'ac-task-input',
                    name: 'task',
                    value: 'sorting',
                    type: 'hidden'
                }).appendTo(form);
            }

    		form.submit();
    		return ui;
    	};	

    	$('.table_sortable_body').sortable({
    		//containment: '.ac-ui-sortable',
    		connectWith: ['.table_sortable_body'],
    		handle: '.griddragdrop',
    		opacity: 0.6,
    		helper: _gridSortHelper,
    		update: _gridSortUpdateHandler
    	}).disableSelection();
    }

    if($.fn.autocomplete) {

        $("[data-auto]").livequery(function() {
            var $this = $(this);

            var options = {
                minLength:2,
                delay: 100,
                source: function( request, response ) {
                    $.ajax({
                        url: _link($this.data('auto')),
                        data: { task:'auto', q: request.term, format : 'json', options : $this.data('options')},
                        success: function( data ) {
                            response(data);
                        }
                    });
                },
                select: function( event, ui ) {
                    var id = ui.item.id;
                    $(this).parent().find('[data-auto-value]').val(id);
                    $(this).next(".ac-auto-value:first").val(id);
                },
                change: function (event, ui) {
                    if(!ui.item){
                        $(this).val("");
                    }
                }
            };

            $(this).autocomplete(options).data("ui-autocomplete")._renderItem = function (ul, item) {
             return $("<li></li>")
                 .data("item.autocomplete", item)
                 .append(item.label)
                 .appendTo(ul);
            };
        });
    }

    if($.fn.tokenfield) {
        $("[data-token]").livequery(function() {
            var $this = $(this);

            var autocomplete = {
                minLength:2,
                delay: 100,
                source: function( request, response ) {
                    $.ajax({
                        url: _link($this.data('token')),
                        data: { task:'auto', q: request.term, format : 'json', options : $this.data('options')},
                        success: function( data ) {
                            response(data);
                        }
                    });
                }
            };
            var tokens = [];
            try {
                tokens = $.parseJSON($this.attr('tokens'));
            }catch(err) {
                console.log(err);
            }

           $this.tokenfield({
                tokens:tokens,
                autocomplete: autocomplete
            })
            .on('tokenfield:createtoken', function (event) {
                var existingTokens = $(this).tokenfield('getTokens');
                $.each(existingTokens, function(index, token) {
                    if (token.value === event.attrs.value) {
                        event.preventDefault();
                    }
                });
            });
        });
    }

    $(document).on('click', '.ac-go-top', function(){
        jQuery('html, body').animate({scrollTop:0}, 500);
    });

    $(window).scroll(function(){
        //show and hide go-to-top buttom
        if(jQuery(window).scrollTop() <= 250){
            jQuery('.ac-go-top').fadeOut(500);
        }else{
            jQuery('.ac-go-top').fadeIn(500);
        }
    });
});
