<table class="table nice-form">
    <thead>
        <tr>
            <th>Sl.no</th>
            <th>login</th>
            <th>Browser Agent</th>
            <th>IP Address</th>
        </tr>
    </thead>
    <tbody>
        {foreach $rows as $row}
        <tr>
            <td>{$row.serial}</td>
            <td>{$row.created|todate}</td>
            <td>{$row.agent|truncate:60}</td>
            <td>{$row.ip}</td>
        </tr>
        {/foreach}
    </tbody>
</table>
