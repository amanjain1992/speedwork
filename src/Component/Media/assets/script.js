$(document).ready(function() {
    $('.media-place').on("click", "img", function() {
        item_url = $(this).data("src");
        top.tinymce.activeEditor.windowManager.getParams().set(item_url);
    });
    $('.uploader-form').on('change', 'input[type=file]', function() {
        $(this).parents('form:first').submit();
    });
    $('[data-role="upload"]').click(function() {
        $(this).parents('form:first').find('input[type=file]').click();
    });
});

function onSuccessFullUpload(res) {
    if (res.status == 'OK') {
        $.each(res.files, function(index, file) {
            top.tinymce.activeEditor.windowManager.getParams().set(file.path);
        });
    }
}
