<form class="easySubmit {ldelim}reload:true{rdelim}" action="{speed link="index.php?option=noty&view=add"}" method="POST">
    <table class="table nice-form" >
        <tr>
            <td width="100"><strong>Is active?</strong></td>
            <td width="300">
                <input type="hidden" name="data[status]" value="0">
                <input type="checkbox" name="data[status]" value="1" {if $row.status eq 1} checked="checked" {/if}>
            </td>
        </tr>
        <tr>
            <td><strong>Message</strong></td>
            <td>
                <textarea style="resize:none" name="data[message]" rows="3" cols="20" maxlength="1000" required="required">{$row.message}</textarea>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="Submit"  value="Save" class="btn btn-primary" />
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" value="{$row.id}" />
    <input type="hidden" name="task" value="save" />
</form>