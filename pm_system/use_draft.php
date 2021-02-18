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
$preview = '';
//=== don't allow direct access
if (!defined('BUNNY_PM_SYSTEM')) {
    $HTMLOUT = '';
    $HTMLOUT .= '<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
$save_or_edit = (isset($_POST['edit']) ? 'edit' : (isset($_GET['edit']) ? 'edit' : 'save'));
$save_or_edit = (isset($_POST['send']) ? 'send' : (isset($_GET['send']) ? 'send' : $save_or_edit));
if (isset($_POST['buttonval']) && $_POST['buttonval'] == $save_or_edit) {
    //=== make sure they wrote something :P
    if (empty($_POST['subject'])) {
        stderr($lang['pm_error'], $lang['pm_draft_err']);
    }
    if (empty($_POST['body'])) {
        stderr($lang['pm_error'], $lang['pm_draft_err1']);
    }
    //=== check to see they have everything or...
    $body = sqlesc(trim($_POST['body']));
    $subject = sqlesc(strip_tags(trim($_POST['subject'])));
    $urgent = sqlesc((isset($_POST['urgent']) && $_POST['urgent'] == 'yes' && $CURUSER['class'] >= UC_STAFF) ? 'yes' : 'no');
    if ($save_or_edit === 'save') {
        sql_query('INSERT INTO messages (sender, receiver, added, msg, subject, location, draft, unread, saved) VALUES  
                                                                        ('.sqlesc($CURUSER['id']).', '.sqlesc($CURUSER['id']).','.TIME_NOW.', '.$body.', '.$subject.', \'-2\', \'yes\',\'no\',\'yes\')') || sqlerr(__FILE__,
            __LINE__);
    } elseif ($save_or_edit === 'edit') {
        sql_query('UPDATE messages SET msg = '.$body.', subject = '.$subject.' WHERE id = '.sqlesc($pm_id)) || sqlerr(__FILE__, __LINE__);
    } elseif ($save_or_edit === 'send') {
        //=== Try finding a user with specified name
        $res_receiver = sql_query('SELECT id, class, acceptpms, notifs, email, class, username FROM users WHERE LOWER(username)=LOWER('.sqlesc(htmlsafechars($_POST['to'])).') LIMIT 1');
        $arr_receiver = $res_receiver->fetch_assoc();
        if (!is_valid_id($arr_receiver['id'])) {
            stderr($lang['pm_error'], $lang['pm_forwardpm_nomember']);
        }
        $receiver = (int)$arr_receiver['id'];
        //=== allow suspended users to PM / forward to staff only
        if ($CURUSER['suspended'] === 'yes') {
            ($res = sql_query('SELECT class FROM users WHERE id = '.sqlesc($receiver))) || sqlerr(__FILE__, __LINE__);
            $row = $res->fetch_assoc();
            if ($row['class'] < UC_STAFF) {
                stderr($lang['pm_error'], $lang['pm_send_your_acc']);
            }
        }
        //=== make sure they have space
        ($res_count = sql_query('SELECT COUNT(id) FROM messages WHERE receiver = '.sqlesc($receiver).' AND location = 1')) || sqlerr(__FILE__,
            __LINE__);
        $arr_count = $res_count->fetch_row();
        if ($res_count->num_rows > ($maxbox * 6) && $CURUSER['class'] < UC_STAFF) {
            stderr($lang['pm_forwardpm_srry'], $lang['pm_forwardpm_full']);
        }
        //=== Make sure recipient wants this message
        if ($CURUSER['class'] < UC_STAFF) {
            $should_i_send_this = ($arr_receiver['acceptpms'] == 'yes' ? 'yes' : ($arr_receiver['acceptpms'] == 'no' ? 'no' : ($arr_receiver['acceptpms'] == 'friends' ? 'friends' : '')));
            switch ($should_i_send_this) {
                case 'yes':
                    ($r = sql_query('SELECT id FROM blocks WHERE userid = '.sqlesc($receiver).' AND blockid = '.sqlesc($CURUSER['id']))) || sqlerr(__FILE__,
                        __LINE__);
                    $block = $r->fetch_row();
                    if ($block[0] > 0) {
                        stderr($lang['pm_forwardpm_refused'], htmlsafechars($arr_receiver['username']).$lang['pm_send_blocked']);
                    }
                    break;

                case 'friends':
                    ($r = sql_query('SELECT id FROM friends WHERE userid = '.sqlesc($receiver).' AND friendid = '.sqlesc($CURUSER['id']))) || sqlerr(__FILE__,
                        __LINE__);
                    $friend = $r->fetch_row();
                    if ($friend[0] > 0) {
                        stderr($lang['pm_forwardpm_refused'], htmlsafechars($arr_receiver['username']).$lang['pm_send_onlyf']);
                    }
                    break;

                case 'no':
                    stderr($lang['pm_forwardpm_refused'], htmlsafechars($arr_receiver['username']).$lang['pm_send_doesnt']);
                    break;
            }
        }
        //=== ok all is well... post the message :D
        sql_query('INSERT INTO messages (poster, sender, receiver, added, msg, subject, saved, unread, location, urgent) VALUES 
                            ('.sqlesc($CURUSER['id']).', '.sqlesc($CURUSER['id']).', '.$receiver.', '.TIME_NOW.', '.$body.', '.$subject.', \'yes\', \'yes\', 1,'.$urgent.')') || sqlerr(__FILE__,
            __LINE__);
        $cache->delete('inbox_new::'.$receiver);
        $cache->delete('inbox_new_sb::'.$receiver);
        //=== make sure it worked then...
        if ($mysqli->affected_rows === 0) {
            stderr($lang['pm_error'], $lang['pm_send_wasnt']);
        }
        //=== if they just have to know about it right away... send them an email (if selected if profile)
        if (strpos($arr_receiver['notifs'], '[pm]') !== false) {
            $username = htmlsafechars($CURUSER['username']);
            $body = <<<EOD
{$lang['pm_forwardpm_pmfrom']} from $username!

{$lang['pm_forwardpm_url']}

{$TRINITY20['baseurl']}/pm_system.php

--
{$TRINITY20['site_name']}
EOD;
            @mail($user['email'], $lang['pm_forwardpm_pmfrom'].$username.$lang['pm_forwardpm_exc'], $body,
                "{$lang['pm_forwardpm_from']}{$TRINITY20['site_email']}");
        }
        //=== if returnto sent
        if ($returnto) {
            header('Location: '.$returnto);
        } else {
            header('Location: pm_system.php?action=view_mailbox&sent=1');
        }
        die();
    }
    //=== Check if messages was saved as draft
    if ($mysqli->affected_rows === 0) {
        stderr($lang['pm_error'], $lang['pm_draft_wasnt']);
    }
    header('Location: /pm_system.php?action=view_mailbox&box=-2&new_draft=1');
    die();
} //=== end save draft
//=== Code for preview Retros code
if (isset($_POST['buttonval']) && $_POST['buttonval'] == 'preview') {
    $subject = htmlsafechars(trim($_POST['subject']));
    $draft = trim($_POST['body']);
    $preview = '
    <table class="table table-striped">
    <tr>
        <td colspan="2" class="text-left"><span style="font-weight: bold;">'.$lang['pm_draft_subject'].'</span>'.htmlsafechars($subject).'</td>
    </tr>
    <tr>
        <td valign="top" class="text-center" width="80px" id="photocol">'.avatar_stuff($CURUSER).'</td>
        <td class="text-left" style="min-width:400px;padding:10px;vertical-align: top;">'.format_comment($draft).'</td>
    </tr>
    </table><br />';
} else {
    //=== Get the info
    ($res = sql_query('SELECT * FROM messages WHERE id='.sqlesc($pm_id))) || sqlerr(__FILE__, __LINE__);
    $message = $res->fetch_assoc();
    $subject = htmlsafechars($message['subject']);
    $draft = $message['msg'];
}
//=== print out the page
//echo stdhead('Use Draft');
$HTMLOUT .= '<h1>'.$lang['pm_usedraft'].''.$subject.'</h1>'.$top_links.$preview.'
        <form name="compose" action="pm_system.php" method="post">
        <input type="hidden" name="id" value="'.$pm_id.'" />
        <input type="hidden" name="'.$save_or_edit.'" value="1" />
        <input type="hidden" name="action" value="use_draft" />
    33333333<table class="table table-striped">
    <tr>
        <td class="colhead" align="left" colspan="2">'.$lang['pm_usedraft1'].'</td>
    </tr>
    <tr>
        <td class="text-right" valign="top"><span style="font-weight: bold;">'.$lang['pm_forward_to'].'</span></td>
        <td class="text-left" valign="top"><input type="text" name="to" value="'.((isset($_POST['to']) && validusername($_POST['to'],
            false)) ? htmlsafechars($_POST['to']) : $lang['pm_forward_user']).'" class="member" onfocus="this.value=\'\';" />
         '.$lang['pm_usedraft_usr'].'</td>
    </tr>
    <tr>
        <td class="text-right" valign="top"><span style="font-weight: bold;">'.$lang['pm_send_subject'].'</span></td>
        <td class="text-left" valign="top"><input type="text" class="text_default" name="subject" value="'.$subject.'" /></td>
    </tr>
    <tr>
        <td class="text-right" valign="top"><span style="font-weight: bold;">'.$lang['pm_send_body'].'</span></td>
        <td class="text-left" valign="top">'.textbbcode('use_draft', 'body', $message['msg']).'</td>
    </tr>
    <tr>
        <td colspan="2" class="text-center">'.($CURUSER['class'] >= UC_STAFF ? '
        <input type="checkbox" name="urgent" value="yes" '.((isset($_POST['urgent']) && $_POST['urgent'] === 'yes') ? ' checked="checked"' : '').' />
        <span style="font-weight: bold;color:red;">'.$lang['pm_send_mark'].'</span>' : '').'
        <input type="submit" class="button" name="buttonval" value="preview" onmouseover="this.className=\'button_hover\'" onmouseout="this.className=\'button\'" />
        <input type="submit" class="button" name="buttonval" value="'.$save_or_edit.'" onmouseover="this.className=\'button_hover\'" onmouseout="this.className=\'button\'" /></td>
    </tr>
    </table></form>';
?>
