$(document).ready(function(){

    $(document).on('click', '.qtipbox,[tooltip="box"]', function(e) {
        $(this).easyTip();
        e.preventDefault();
    });
    $(document).on('mouseover', "[role='popover'],[tooltip='popover']", function(e) {
        $(this).easyTip();
        e.preventDefault();
    });
    $(document).on('click', '.qtiph, .qtiphidden,[tooltip="hidden"]', function(e) {
        $(this).easyTip({
            events: {
                show: function(event, api) {
                    var self = api.elements.target;
                    var content = $(self.data('target')).html();
                    api.set('content.text', content);
                }
            }
        });
        e.preventDefault();
    });
    $(document).on('click', '.qtiphf,[tooltip="form"]', function(e) {
        $(this).easyTip({
            events: {
                show: function(event, api) {
                    var self = api.elements.target;
                    var content = $(self.data('target')).html();
                    api.set('content.text', content);
                }
            },
            position: {
                container: $(this).parents('form:first')
            }
        });
        e.preventDefault();
    });
    $(document).on('click', '.qtipm,[tooltip="hmodel"]', function(e) {
        $(this).easyTip({
            events: {
                show: function(event, api) {
                    var self = api.elements.target;
                    var content = $(self.data('target')).html();
                    api.set('content.text', content);
                }
            },
            position: {
                viewport: false,
                at: 'center', // Position the tooltip above the link
                my: 'center',
                target: $(window)
            },
            show: {
                modal: true
            }
        });
        e.preventDefault();
    });
    $(document).on('click', '.qtipmodal,[tooltip="model"]', function(e) {
        $(this).easyTip({
            position: {
                viewport: false,
                at: 'center', // Position the tooltip above the link
                my: 'center',
                target: $(window)
            },
            show: {
                modal: true
            }
        });
        e.preventDefault();
    });
    $(document).on('mouseover', '.help-tip,[tooltip="title"]', function(event) {
        $(this).qtip({
            show: {
                event: event.type, // Use the same show event as the one that triggered the event handler
                ready: true // Show the tooltip as soon as it's bound, vital so it shows up the first time you hover!
            }
        });
    });
    $.fn.qtip.hide = function() {
        $('*').qtip('hide');
    }
    $(document).on('click', '.ac-close-qtip', function() {
        $("*").qtip('hide');
    });

    $(document).on('click', '[tooltip="noty"]', function(e) {

        if ($(this).hasClass('noty-visible')) {
            $("*").qtip('hide');
            return false;
        }

        $(this).addClass('noty-visible');
        $(this).find('.badge').html('');

        $(this).easyTip({
            content : {
                title : {
                    button : false
                }
            },
            hide: {
                event: 'unfocus',
                delay: 200
            },
            events : {
                show : null,
                hide:function(event, api){
                    var self   = api.elements.target;
                    self.removeClass('noty-visible');
                }
            }
        });
        e.preventDefault();
    });

});
