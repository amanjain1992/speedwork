var mce = {};

mce.options = {
    mode : "exact",
    plugins: [
        "advlist autolink autosave link image lists print preview hr anchor pagebreak",
        "searchreplace wordcount code fullscreen media",
        "table contextmenu directionality emoticons textcolor paste"
    ],
    menubar: false,
    statusbar: true,
    paste_use_dialog : false,
    force_br_newlines : true,
    force_p_newlines : false,
    toolbar_items_size: 'small',
    apply_source_formatting : true,
    document_base_url: image_url,
    relative_urls: false,
    toolbar1 : "styleselect forecolor backcolor bold italic underline strikethrough hr bullist numlist blockquote undo redo link unlink anchor image lmedia media emoticons pagebreak table removeformat searchreplace print fullscreen preview code"
};

mce.browser_call = function(field_name, uri, type, win) {
    tinymce.activeEditor.windowManager.open({
        title: 'Browse Image',
        file : _link('media?type=component&_request=iframe&media='+type),
        width: 640,
        height: 480,
        resizable : "yes",
        inline : "yes"
    },{
        set: function (url) {
          win.document.getElementById(field_name).value = url;
          tinymce.activeEditor.windowManager.close();
        }
    });

    return false;
};

mce.initsetup = function(editor) {
    // Add a custom button
    editor.addButton('lmedia', {
        title : 'Upload & insert local images',
        icon : 'image',
        onclick : function() {

            editor.windowManager.open({
                title: 'Upload an image',
                file : _link('media/upload?type=component'),
                width : 640,
                height: 320,
                resizable : "yes",
                inline : "yes"
            },{
                set : function(path) {
                    var text = '<img src="'+path+'" />';
                    tinymce.activeEditor.execCommand('mceInsertContent', false, text);
                    tinymce.activeEditor.windowManager.close();
                }
            });
        }
    });
};

function initAdvanced() {
    var mceOptions = mce.options;
    mceOptions.file_browser_callback = mce.browser_call;
    mceOptions.setup = mce.initsetup;
    $("textarea.tinymceeditor, .editorfull").livequery(function() {
        $(this).tinymce(mceOptions);
    })
}

function initBasic() {
    var mceOptions = mce.options;
    delete mceOptions.file_browser_callback;
    mceOptions.toolbar1 = "styleselect forecolor backcolor hr bullist numlist blockquote link unlink image media lmedia emoticons";
    mceOptions.setup = mce.initsetup;
    $('textarea.editorbasic, [data-role="editor"]').livequery(function() {
        $(this).tinymce(mceOptions);
    })
}

$(document).ready(function(){
    initAdvanced();
    initBasic();
});