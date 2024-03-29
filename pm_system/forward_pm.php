<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */
//=== don't allow direct access
if (!defined('BUNNY_PM_SYSTEM')) {
    $HTMLOUT = '';
    $HTMLOUT .= '<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERROR</title>
    <link rel="stylesheet" href="/../../foundation/dist/assets/css/app.css">
  </head>
  <body>
  <div class="grid-container">
        <div class="grid-x grid-padding-x align-center-middle text-center margin-top-3">
        <div class="callout alert margin-top-3">
          <h5>You are not allowed to enter in here</h5>
          <p>Please go back to<a href="/../../index.php"> Homepage</a></p>
        </div> 
        </div>
</div>
  </body>
</html>';
    echo $HTMLOUT;
    exit();
}
//=== make sure they "should" be forwarding this PM
($res = sql_query('SELECT * FROM messages WHERE id='.sqlesc($pm_id))) || sqlerr(__FILE__, __LINE__);
$message = $res->fetch_assoc();
if ($res->num_rows === 0) {
    stderr($lang['pm_error'], $lang['pm_forwardpm_notfound']);
}
if ($message['receiver'] == $CURUSER['id'] && $message['sender'] == $CURUSER['id']) {
    stderr($lang['pm_error'], $lang['pm_forwardpm_gentleman']);
}
//=== Try finding a user with specified name
$res_username = sql_query('SELECT id, class, acceptpms, notifs FROM users WHERE LOWER(username)=LOWER('.sqlesc(htmlsafechars($_POST['to'])).') LIMIT 1');
$to_username = $res_username->fetch_assoc();
if ($res_username->num_rows === 0) {
    stderr($lang['pm_error'], $lang['pm_forwardpm_nomember']);
}
//=== make sure the reciever has space in their box
($res_count = sql_query('SELECT COUNT(id) FROM messages WHERE receiver = '.sqlesc($to_username['id']).' AND location = 1')) || sqlerr(__FILE__,
    __LINE__);
if ($res_count->num_rows > ($maxbox * 6) && $CURUSER['class'] < UC_STAFF) {
    stderr($lang['pm_forwardpm_srry'], $lang['pm_forwardpm_full']);
}
//=== allow suspended users to PM / forward to staff only
if ($CURUSER['suspended'] === 'yes') {
    ($res = sql_query('SELECT class FROM users WHERE id = '.sqlesc($to_username['id']))) || sqlerr(__FILE__, __LINE__);
    $row = $res->fetch_assoc();
    if ($row['class'] < UC_STAFF) {
        stderr($lang['pm_error'], $lang['pm_forwardpm_account']);
    }
}
//=== Other then from staff, Make sure recipient wants this message...
if ($CURUSER['class'] < UC_STAFF) {
    //=== first if they have PMs turned off
    if ($to_username['acceptpms'] === 'no') {
        stderr($lang['pm_error'], $lang['pm_forwardpm_dont_accept']);
    }
    //=== if this member has blocked the sender
    ($res2 = sql_query('SELECT id FROM blocks WHERE userid='.sqlesc($to_username['id']).' AND blockid='.sqlesc($CURUSER['id']))) || sqlerr(__FILE__,
        __LINE__);
    if ($res2->num_rows === 1) {
        stderr($lang['pm_forwardpm_refused'], $lang['pm_forwardpm_blocked']);
    }
    //=== finally if they only allow PMs from friends
    if ($to_username['acceptpms'] === 'friends') {
        ($res2 = sql_query('SELECT * FROM friends WHERE userid='.sqlesc($to_username['id']).' AND friendid='.sqlesc($CURUSER['id']))) || sqlerr(__FILE__,
            __LINE__);
        if ($res2->num_rows != 1) {
            stderr($lang['pm_forwardpm_refused'], $lang['pm_forwardpm_accept']);
        }
    }
}
//=== ok... all is good... let's get the info and send it :D
$subject = htmlsafechars($_POST['subject']);
$first_from = (validusername($_POST['first_from']) ? htmlsafechars($_POST['first_from']) : '');
$body = "\n\n".$_POST['body']."\n\n{$lang['pm_forwardpm_0']}[b]".$first_from."{$lang['pm_forwardpm_1']}[/b] \"".htmlsafechars($message['subject'])."\"{$lang['pm_forwardpm_2']}".$message['msg']."\n";
sql_query('INSERT INTO `messages` (`sender`, `receiver`, `added`, `subject`, `msg`, `unread`, `location`, `saved`, `poster`, `urgent`) 
                        VALUES ('.sqlesc($CURUSER['id']).', '.sqlesc($to_username['id']).', '.TIME_NOW.', '.sqlesc($subject).', '.sqlesc($body).', \'yes\', 1, '.sqlesc($save).', 0, '.sqlesc($urgent).')') || sqlerr(__FILE__,
    __LINE__);
$cache->delete($cache_keys['inbox_new'].$to_username['id']);
$cache->delete($cache_keys['inbox_new_sb'].$to_username['id']);
//=== Check if message was forwarded
if ($mysqli->affected_rows === 0) {
    stderr($lang['pm_error'], $lang['pm_forwardpm_msg_fwd']);
}
//=== if they just have to know about it right away... send them an email (if selected if profile)
if (strpos($to_username['notifs'], '[pm]') !== false) {
    $username = htmlsafechars($CURUSER['username']);
    $body = <<<EOD
{$lang['pm_forwardpm_pmfrom']}$username{$lang['pm_forwardpm_exc']}

{$lang['pm_forwardpm_url']}

{$TRINITY20['baseurl']}/pm_system.php

--
{$TRINITY20['site_name']}
EOD;
    @mail($user['email'], $lang['pm_forwardpm_pmfrom'].$username.$lang['pm_forwardpm_exc'], $body,
        "{$lang['pm_forwardpm_from']}{$TRINITY20['site_email']}");
}
header('Location: pm_system.php?action=view_mailbox&forwarded=1');
die();
?>
