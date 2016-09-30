function _e(s){
    var argv = Array.apply(null, arguments).slice(1);
    if ($.isArray(argv)) {
        $.each(argv, function(i) {
            s = s.replace(this, argv[i]);
        });
    }   
    return s;
}

function _link(url){

    // Seo is disable
    if (!seo_urls && url.substr(0,6) != 'index.') {
        var spl = url.split('?');
        var details = spl[0].split('/');
        url = 'index.php?option='+details[0];
        if (details[1]) {
            url = url+'&view='+details[1];
        }
        if (spl[1]) {
            url = url+'&'+spl[1];
        }
    }

    if (!(new RegExp('^(http(s)?[:]\/\/)','i')).test(url)) {
        if (url.substr(0, 2) != '//') {
            url = '/' + url;
        }
    }

   return url;
}


(function( jQuery, window, undefined ) {

    jQuery.uaMatch = function( ua ) {
        ua = ua.toLowerCase();

        var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
            /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
            /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
            /(msie) ([\w.]+)/.exec( ua ) ||
            ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
            [];

        return {
            browser: match[ 1 ] || "",
            version: match[ 2 ] || "0"
        };
    };

    matched = jQuery.uaMatch( navigator.userAgent );
    browser = {};

    if ( matched.browser ) {
        browser[ matched.browser ] = true;
        browser.version = matched.version;
    }

    // Chrome is Webkit, but Webkit is also Safari.
    if ( browser.chrome ) {
        browser.webkit = true;
    } else if ( browser.webkit ) {
        browser.safari = true;
    }

    jQuery.browser = browser;

    jQuery.fn.live = function( types, data, fn ) {
        jQuery( document ).on( types, this.selector, data, fn );
        return this;
    };

})( jQuery, window );


function trace( text ){
    if( (window['console'] !== undefined) ){
        console.log( text );
    }
}

;(function($){
    $.fn.clearWatermark = function(){
        $('.placeholder', this).each(function() {
        if ($(this).val() == $(this).attr('placeholder'))
            $(this).val('');            
        });
    }
})(jQuery);

;(function($) {
    $.fn.serializeFormJSON = function() {

       var o = {};
       var a = this.serializeArray();
       $.each(a, function() {
           if (o[this.name]) {
               if (!o[this.name].push) {
                   o[this.name] = [o[this.name]];
               }
               o[this.name].push(this.value || '');
           } else {
               o[this.name] = this.value || '';
           }
       });
       return o;
    };
})(jQuery);
