<form role="easySubmit" class="form-general" data-reload="true" action="{speed link="index.php?option=menu&view=add"}" method="POST">
    <table border="0" cellspacing="3" width="500" cellpadding="0" class="table">
        <tr>
            <td width="110">
                <label>Title</label>
            </td>
            <td>
                <input type="text" name="data[title]" value="{$row.title}" size="40" required="required"/>
            </td>
        </tr>
        <tr>
            <td>
                <label>Menu Type</label>
            </td>
            <td>
                <input type="text" name="data[menu_type]" value="{$row.menu_type}" required="required" />
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="Submit"  value="Save" class="btn btn-info" />
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" value="{$row.id}" />
    <input type="hidden" name="task" value="save" />
</form>