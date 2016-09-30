(function($) {
    var pluginName = 'easyTip';
    var defaults = {
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

    var easyTip = function(element, options) {
        this.element = element;
        var settings = $.extend(true, {}, defaults, options);

        if ($.metadata) {
            settings = $.extend(true, {}, settings, element.metadata({type:'class'}));
            settings = $.extend(true, {}, settings, element.metadata({type:'html5'}));
        }

        settings.events = settings.events || {};
        if ('undefined' === typeof settings.content.text || '&nbsp;' === settings.content.text) {
            if (settings.once === true) {
                settings.events = {};
                settings.content = {
                    text: this.ajax
                };
            } else {
                if (settings.events.show == null) {
                    settings.events = {
                        show: this.ajax
                    };
                }
            }
        }

        this.ajax = function(event, api) {
            element.ajax(event, api);
        };

        this.settings = settings;
        element.qtip(settings);
    };

    easyTip.prototype.ajax = function(event, api) {
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
        }, function(xhr, status, error) {
            api.set('content.text', status + ': ' + error);
        });
        return '<i class="fa fa-lg fa-spinner fa-spin"></i>';
    };

    $.fn.easyTip = function(options) {
        return this.each(function() {
            if (!$.data(this, pluginName)) {
                $.data(this, pluginName, new easyTip($(this), options));
            }
        });
    };

})(jQuery);