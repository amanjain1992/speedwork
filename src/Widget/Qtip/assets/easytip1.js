;(function($, window, undefined) {
    'use strict';
    var options = {};
    $.fn.easyTip = function(options) {
        if (!$.fn.qtip) {
            console.warn('Missing Qtip plugin');
            return false;
        }
        return this.each(function() {
            $.fn.easyTip.render($(this), options);
        });
    };
    $.fn.easyTip.render = function($this, options) {
        var defaults = $.fn.easyTip.defaults;
        var settings = $.extend(true, {}, settings, defaults, options, {
            this: $this
        });
        
        settings.events = settings.events || {};
        if ('undefined' === typeof settings.content.text || '&nbsp;' === settings.content.text) {
            if (settings.once === true) {
                settings.events = {};
                settings.content = {
                    text: $.fn.easyTip.ajaxContent
                };
            } else {
                if (settings.events.show == null) {
                    settings.events = {
                        show: $.fn.easyTip.ajaxContent
                    };
                }
            }
        }

        $this.qtip(settings);
    };
    $.fn.easyTip.ajaxContent = function(event, api) {
        var id = api.get('id');
        $('#' + id).empty();

        var self   = api.elements.target;
        var target = api.get('url') || self.attr('href');

        //add loading
        api.set('content.text', '<center><i class="fa fa-lg fa-spinner fa-spin"></i></center>');
        $.ajax({
            url: target,
            data: {
                type: 'html'
            },
            cache: false
        }).then(function(html) {
            api.set('content.text', html);
            onloadEvents();
        }, function(xhr, status, error) {
            api.set('content.text', status + ': ' + error);
        });
        return '<i class="fa fa-lg fa-spinner fa-spin"></i>';
    };

    $.fn.easyTip.defaults = {
        //overwrite: false,
        content: {
            text: '&nbsp;',
            title: {
                text: function(event, api) {
                    return $(this).data('original-title') || $(this).attr('title');
                },
                button: true
            }
        },
        position: {
            at: 'bottom center', // Position the tooltip above the link
            my: 'top center',
            viewport: $(window), // Keep the tooltip on-screen at all times
            effect: false // Disable positioning animation
        },
        show: {
            event: 'click',
            ready: true,
            solo: true // Only show one tooltip at a time
        },
        hide: {
            delay: 0,
            /*target: function() {
                    return $('body').children().not($(this)).not('.qtip').not('.ui-datepicker').not($('.ui-datepicker').children())
            },*/
            event: 'mousedown'
        },
        events: {
            show : null
        },
        style: {
            classes: 'qtip-default qtip qtip-shadow qtip-rounded qtip-bootstrap '
        }
    };
}(jQuery, this));