<form class="easySubmit uploader-form" action="{speed link="media/upload"}" method="POST">
    <input type="hidden" name="task" value="save">
    <input type="hidden" id="onSuccess" value="onSuccessFullUpload">
    <input type="hidden" name="_request" value="iframe">
    <input type="hidden" name="_format" value="json">
    <div data-role="upload" class="uploader">
        <!-- <div>Drag &amp; drop a image</div>-->
        <div><!-- Or --> Click to choose from your computer</div>
    </div>
    <input type="file" class="uploader-input" name="image" title="Click to add Files">
</form>