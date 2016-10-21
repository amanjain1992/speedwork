<form class="form-general" role="easySubmit" data-reload="true" action="{speed link="index.php?option=menu&view=item"}" method="POST">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="left"><h4>Add Menu Item</h4></td>
            <td align="right" width="50%">
                <div class="btn-group">
                    <a href="{speed link="index.php?option=menu&view=items&t={$menu_type}"}" title="Go Back" class="btn btn-info" role="button"><i class="fa fa-undo"></i> Back</a>
                    <button type="reset" class="btn btn-default">Reset</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </td>
        </tr>
    </table>
    <table border="0" cellspacing="3" cellpadding="0" class="table nice-form">
        <tr>
            <td width="130"><strong>Enable?</strong></td>
            <td>
                <input type="checkbox" name="data[status]" notchecked="0" value="1" {if $row.status}checked="checked"{/if}/>
            </td>
        </tr>
        <tr>
            <td><strong>Name</strong></td>
            <td>
                <input type="text" name="data[name]" required="required" value="{$row.name}" placeholder="Name" size="26"/>
            </td>
        </tr>
        <tr>
            <td><strong>URL</strong></td>
            <td>
                <input type="text" name="data[link]" required="required" value="{$row.link}" placeholder="Url" size="26"/>
            </td>
        </tr>
        <tr>
            <td><strong>Access Level</strong></td>
            <td>
                <input type="radio" name="data[access]" value="0" {if $row.access eq 0} checked="checked"{/if}/> Public
                <input type="radio" name="data[access]" value="1" {if $row.access eq 1} checked="checked"{/if}/> Registered
                <input type="radio" name="data[access]" value="2" {if $row.access eq 2} checked="checked"{/if}/> Special <span class="require">*</span>
            </td>
        </tr>
        <tr>
            <td><strong>Parent Item</strong></td>
            <td>
                <div style="height:220px; overflow:auto">
                    <input type="radio" name="category[]" {if $row.parent_id}{else}checked="checked"{/if} value="0"> None
                    {$menutree}
                </div>
            </td>
        </tr>
        <tr>
            <td><strong>Attributes</strong></td>
            <td>
                <table class="ac-attrs">
                    <thead>
                        <tr>
                            <td >Name</td>
                            <td></td>
                            <td>Value</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach $row.attributes as $val => $attr}
                        <tr>
                            <td><input type="text" name="key[]" value="{$val}" size="15" /></td>
                            <td>=</td>
                            <td><input type="text" name="value[]" value="{$attr}" size="15" /></td>
                            <td><i class="fa fa-lg fa-minus ac-remove" title="Delete"></i></td>
                        </tr>
                        {/foreach}
                        <tr>
                            <td><input type="text" name="key[]" size="15" /></td>
                            <td>=</td>
                            <td><input type="text" name="value[]" size="15" /></td>
                            <td><i title="Add Fields" class="fa fa-plus-square fa-2x help-tip ac-add"></i></td>
                        </tr>
                    </tbody>
                    <tfoot style="display:none">
                    <tr>
                        <td><input type="text" name="key[]" size="15" /></td>
                        <td></td>
                        <td><input type="text" name="value[]" size="15" /></td>
                        <td><i class="fa fa-lg fa-minus ac-remove" title="Delete"></i></td>
                    </tr>
                    </tfoot>
                </table>
            </td>
        </tr>
    </table>
    <input type="hidden" name="id" value="{$row.id}" />
    <input type="hidden" name="menu_type" value="{$menu_type}" />
    <input type="hidden" name="task" value="save" />
</form>
