(function($) {
    var pluginName = 'easySubmit';

    var defaults = {
        target: '', // target element(s) to be updated with server response 
        reload: false,
        return_url: null,
        validate: true,
        onComplete: function() {},
        onFailed: function() {},
        inputEvent: 'blur' // change, blur
    };

    var easySubmit = function(element, options) {
        this.element = element;

        this.settings = $.extend({}, defaults, options);

        if ($.metadata) {
            this.settings = $.extend({}, this.settings, element.metadata({type:'class'}));
            this.settings = $.extend({}, this.settings, element.metadata({type:'html5'}));
        }

        if (element.is("form")) {
            this.form = element;
        } else {
            this.form = element.parents('form:eq(0)');
        }

        if (this.settings.validate == true) {
            this.validate();
        } else {
            var widget = this;
            this.form.submit(function() {
                widget.formSubmit();
            })
        }
    };

    easySubmit.prototype.validate = function() {
        var widget = this;

        this.form.validator(widget.settings).submit(function(e) {
            // client-side validation OK.   
            if (!e.isDefaultPrevented()) {

                widget.callback('onValidationSuccess', {});

                widget.form.attr({
                    valid: true
                });
                widget.formSubmit();
                return false;
            }
            widget.callback('onValidationFail', {});
            e.preventDefault();
            widget.form.attr({
                valid: false
            });

            var msg = widget.form.attr('message') || _e('Please correct the highlighted error(s)');
            noty({
                text: msg,
                type: 'error'
            });
        });
    };

    easySubmit.prototype.formSubmit = function() {
        var widget = this;
        var beforeSubmit = this.settings.beforeSubmit || this.beforeSubmit;
        var success  = this.settings.success || this.success;
        var progress = this.settings.onProgress || this.progress;

        var fromOptions = {
            beforeSubmit: function() {
                widget.beforeSubmit();
            },
            success: function(response) {
                widget.success(response);
            },
            uploadProgress: function(event, position, total, percentComplete) {
                widget.progress(percentComplete, total, position);
            },
            format: 'json',
            data: {
                format: 'json'
            }
        }

        var options = $.extend({}, this.settings, fromOptions);
        this.element.ajaxSubmit(options);
    };

    easySubmit.prototype.beforeSubmit = function() {
        var confirm = this.form.data('confirm')
        if (undefined != confirm && '' != confirm) {
            if (!confirm(confirm)) {
                return false;
            }
        }
        this.addLoading();
        return true;
    };

    easySubmit.prototype.addLoading = function() {
        
        var opt = this.getTarget();

        if ('object' == typeof opt) {
            opt.attr('disabled', true);
            var button = false;

            if (opt.is('button')) {
                button = true;
                var value = opt.html();
            } else {
                var value = opt.val();
            }

            opt.data('value',value);

            opt.addClass('loader');
            if (opt.data('loader') != undefined) {
                var loader = opt.data('loader').split(';');
                if (button) {
                    opt.html(loader[0]);
                } else {
                    opt.val(loader[0]);
                }
            } else {
                if (button) {
                    opt.val(_e('Please wait...'));
                } else {
                    opt.val(_e('Please wait...'));
                }
            }
        }
    };

    easySubmit.prototype.removeLoading = function() {
        var opt = this.getTarget();

        if ('object' == typeof opt) {
            opt.attr('disabled', false);
            opt.removeClass('loader');

            if (opt.is('button')) {
                opt.html(opt.data('value'));
            } else {
                opt.val(opt.data('value'));
            }
        }
    };

    easySubmit.prototype.getTarget = function() {
        var element = this.element;
        if (element.is("form")) {
            var target = (element.find('.clicked').length) ? element.find('.clicked') : element.find('[type=submit]:first');
            if ('undefined' !== typeof element.data('submit-target')) {
                var target = $(element.data('submit-target'));
            }
        } else {
            var target = this;
        }

        return target;
    }

    easySubmit.prototype.progress = function(percentComplete, total, position) {

        if (this.settings.progress) {
            $(this.settings.progress).width(percentComplete+'%');
        }

        var fnc = this.form.find("#onProgress").val();
        if (fnc != '' && fnc != undefined) {
            var fn = window[fnc];
            if (typeof fn === 'function') {
                fn(percentComplete, total, position, this);
            }
        }
    };

    easySubmit.prototype.success = function(response) {
        var widget = this;

        widget.removeLoading();
        //reload captcha
        $('.ac-captcha').each(function() {
            var src = $(this).attr('src');
            src += (src.indexOf('?')) ? '&' : '?';
            src += 'sid=' + Math.random();
            $(this).attr({
                src: src
            });
        });

        try {
            var res = JSON.parse(response);
        } catch (err) {
           /* noty({
                text: 'Something went wrong at our end. Unable to parse the response. Please try again.',
                type: 'error'
            });
            return true;*/
            var res = response;
            if ('object' !== typeof res) {
                res = null
            }
        }

        if (res === false || res === null) {
            noty({
                text: 'Something went wrong and we cannot service your request. Please try again.',
                type: 'error'
            });
            return true;
        }

        widget.callback('onComplete', res);

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

        if (res.status == "200" || res.status == 'OK') {

            this.callback('onSuccess', res);

            if (res.preview) {
                if (!res.preview.body) {
                    noty({
                        text: 'Invalid response from server. Please try again.',
                        type: 'error'
                    });
                    return true;
                }
                var title = res.preview.title || "Preview";
                var qtipSource = "tip-preview"
                $("#" + qtipSource).remove();
                $('body').append($('<a id="' + qtipSource + '" style="display:none" title="' + title + '">' + title + '</a>'));
                $("#" + qtipSource).easyTip({
                    content: {
                        text: res.preview.body
                    },
                    position: {
                        viewport: false,
                        at: 'center', // Position the tooltip above the link
                        my: 'center',
                        target: $(window)
                    },
                    show: {
                        modal: true,
                        ready: true,
                        solo: true
                    },
                    events: {
                        show: null
                    }
                }).qtip('show');
                return true;
            }
            if (res.message) {
                noty({
                    text: res.message,
                    type: 'success'
                });
            }
            if (widget.settings.alert) {
                alert(res.message);
            }
            if (widget.settings.reset) {
                widget.form.resetForm();
            }
            if (widget.settings.clear) {
                widget.form.clearForm();
            }

            if (widget.settings.hide && $.fn.qtip) {
                setTimeout(function() {
                    $('*').qtip('hide');
                }, 1000);
            }

            var redirect = widget.form.find('#return_url').val();
            if (widget.settings.return_url) {
                widget.redirect(return_url, 1000);
            } else if (redirect != '' && redirect != undefined) {
                widget.redirect(redirect, 1000);
            } else if (widget.settings.reload) {
                setTimeout("location.reload(true);", 1000);
            }

            if (widget.settings.render) {
                $('#page').val(1);
                ajaxPagination.load();
            }

            var submit = widget.settings.submit;
            if (submit == 'parent') {
                widget.form.closest('form').submit();
            } else if (submit) {
                $(widget.settings.submit).submit();
            }

            if (res.redirect) {
                widget.redirect(res.redirect, 1000);
            }
        } else if (res.status == 'INFO') {
            if (res.message) {
                noty({
                    text: res.message,
                    type: 'information'
                });
            }
            widget.callback('onInfo', res);
        } else {

            if ('undefined' === typeof res.status && res.redirect) {
                widget.redirect(res.redirect, 1000);
                return true;
            }

            if (res.message) {
                noty({
                    text: res.message,
                    type: 'error'
                });
            }

            if (res.errors) {
                widget.form.data("validator").invalidate(res.errors);
            }

            widget.callback('onFailed', res);
        }

        if (widget.settings.target) {
            $(widget.settings.target).empty().show().html(message);
        }
    };

    easySubmit.prototype.redirect = function(url, time) {
        var t = (time) ? time : 0;
        var url = url.replace('&amp;', '&');
        setTimeout(function() {
            window.top.location.href = url;
        }, t);
    };

    easySubmit.prototype.callback = function(name, res) {
        var widget = this;

        var fnc = widget.settings[name];
        if (fnc && 'undefined' !== typeof fnc) {
            if ('function' == typeof fnc) {
                fnc(res, widget, widget.settings);
            } else {
               var fn = window[fnc];
                if (typeof fn === 'function') {
                    fn(res, widget, widget.settings);
                } 
            }
        }

        //lower case
        var fnn = name.toLowerCase();
        var fnc = widget.settings[fnn];
        if (fnc && 'undefined' !== typeof fnc) {
            if ('function' == typeof fnc) {
                fnc(res, widget, widget.settings);
            } else {
               var fn = window[fnc];
                if (typeof fn === 'function') {
                    fn(res, widget, widget.settings);
                } 
            }
        }

        var fnc = widget.form.find("#" + name).val();
        if (fnc != '' && 'undefined' !== typeof fnc) {
            var fn = window[fnc];
            if (typeof fn === 'function') {
                fn(res, widget, widget.settings);
            }
        }
    };

    $.fn.easySubmit = function(options){
        return this.each(function() {
          if(!$.data(this, pluginName)){
            $.data(this, pluginName, new easySubmit($(this), options));
          }
        });
    };

})(jQuery);