<!--subject: Welcome to {$sitename} -->
<p> Greetings from {$sitename},</p>
<br />
<p>Please find the account details below</p>
<p>Username: <span style="color:#003e86;">{$mail_username}</span></p>
<p>Password: <span style="color:#003e86;">{if $mail_activation_link} ******** {else} {$mail_password} {/if}</span></p>
{if $mail_activation_link}
<p>-----------------------<br>
    The administrator of this site has requested all new accounts to be activated<br>
    by the users who created them thus your account is currently inactive.<br>
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
