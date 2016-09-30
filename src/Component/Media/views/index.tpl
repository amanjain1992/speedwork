{if !$is_ajax_request || $is_iframe_request}
<form id="ajax_form" method="POST" action="{speed link="media"}" class="media-place">
    <section class="ac-ajax-content">
        {/if}
        {foreach $files as $file}
        <img src="{$file.url}" data-src="{$file.path}" class="img-thumbnail" style="width:120px; margin:3px; height:80px">
        {/foreach}
        {if !$is_ajax_request || $is_iframe_request}
    </section>
    <input type="hidden" name="source" value="">
</form>
<br>
<form role="easySubmit" class="uploader-form uploader-small" data-render="true" data-reset="true" action="{speed link="media/upload"}" method="POST">
    <input type="hidden" name="task" value="save">
    <input type="hidden" id="onSuccess" value="onSuccessFullUpload">
    <input type="hidden" name="_request" value="iframe">
    <input type="hidden" name="_format" value="json">
    <div data-role="upload" class="uploader text-center">
        <!-- <div>Drag &amp; drop a image</div>-->
        <!-- <div>Or</div> -->
        <div>Click to choose from your computer</div>
    </div>
    <input type="file" capture="camera" class="uploader-input" name="image" title="Click to add Files">
</form>
{/if}
