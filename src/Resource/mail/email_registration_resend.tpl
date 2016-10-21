<!--subject: Welcome to {$sitename} -->
<p> Greetings from {$sitename},</p>
<br />
{if $mail_activation_link}
As requested your activation link is as follow
<p>-----------------------<br>
    To activate your account, please visit this link:<br>
    <br>
    <a href="{$mail_activation_link}">{$mail_activation_link}</a>
</p>
{/if}
<p>You can manage your account online by visiting {$sitename}.</p>
<p>We suggest that you save this email for further reference.</p>
<strong>Important Security Tips:</strong>
<ol>
    <li>Always keep your account details safe.</li>
    <li>Never disclose your login details to anyone.</li>
    <li>Change your password regularly.</li>
    <li>Should you suspect someone is using your account illegally, please notify us immediately.</li>
</ol>
