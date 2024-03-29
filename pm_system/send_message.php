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
$draft = $subject = $body = '';
flood_limit('messages');
//=== don't allow direct access
if (!defined('BUNNY_PM_SYSTEM')) {
    $HTMLOUT .= '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
        <title>ERROR</title>
        </head><body>
        <h1 class="text-center">ERROR</h1>
        <p class="text-center">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
//=== check to see if it's a preview or a post
if (isset($_POST['buttonval']) && $_POST['buttonval'] == 'Send') {
    //=== check to see they have everything or...
    $receiver = sqlesc(isset($_POST['receiver']) ? (int)$_POST['receiver'] : 0);
    $subject = sqlesc(htmlsafechars($_POST['subject']));
    $body = sqlesc(trim($_POST['body']));
    $save = ((isset($_POST['save']) && $_POST['save'] === 1) ? 'yes' : 'no');
    $delete = sqlesc((isset($_POST['delete']) && $_POST['delete'] !== 0) ? (int)$_POST['delete'] : 0);
    $urgent = sqlesc((isset($_POST['urgent']) && $_POST['urgent'] == 'yes' && $CURUSER['class'] >= UC_STAFF) ? 'yes' : 'no');
    $returnto = htmlsafechars($_POST['returnto'] ?? '');
    //$returnto = htmlsafechars($_POST['returnto']);
    //=== get user info from DB
    ($res_receiver = sql_query('SELECT id, acceptpms, notifs, email, class, username FROM users WHERE id='.sqlesc($receiver))) || sqlerr(__FILE__,
        __LINE__);
    $arr_receiver = $res_receiver->fetch_assoc();
    if (!is_valid_id((int)$_POST['receiver']) || !is_valid_id($arr_receiver['id'])) {
        stderr($lang['pm_error'], $lang['pm_send_not_found']);
    }
    if (!isset($_POST['body'])) {
        stderr($lang['pm_error'], $lang['pm_send_nobody']);
    }


    //=== allow suspended users to PM / forward to staff only
    if ($CURUSER['suspended'] === 'yes') {
        ($res = sql_query('SELECT class FROM users WHERE id = '.sqlesc($receiver))) || sqlerr(__FILE__, __LINE__);
        $row = $res->fetch_assoc();
        if ($row['class'] < UC_STAFF) {
            stderr($lang['pm_error'], $lang['pm_send_your_acc']);
        }

    }
    //=== make sure they have space
    ($res_count = sql_query('SELECT COUNT(*) FROM messages WHERE receiver = '.sqlesc($receiver).' AND location = 1')) || sqlerr(__FILE__, __LINE__);
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
                $block1 = $block ?? "";
                if ($block1[0] > 0) {
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
    sql_query('INSERT INTO messages (poster, sender, receiver, added, msg, subject, saved, location, urgent) VALUES 
                            ('.sqlesc($CURUSER['id']).', '.sqlesc($CURUSER['id']).', '.sqlesc($receiver).', '.TIME_NOW.', '.$body.', '.$subject.', '.sqlesc($save).', 1,'.$urgent.')') || sqlerr(__FILE__,
        __LINE__);
    $cache->delete($cache_keys['inbox_new'].$receiver);
    $cache->delete($cache_keys['inbox_new_sb'].$receiver);
    $cache->delete('shoutbox_');
    //=== make sure it worked then...
    if ($mysqli->affected_rows === 0) {
        stderr($lang['pm_error'], $lang['pm_send_wasnt']);
    }

    //=== if they just have to know about it right away... send them an email (if selected if profile)
    if (strpos($arr_receiver['notifs'], '[pm]') !== false) {
        $username = htmlsafechars($CURUSER['username']);
        $body = <<<EOD
{$lang['pm_forwardpm_pmfrom']} $username!

{$lang['pm_forwardpm_url']}

{$TRINITY20['baseurl']}/pm_system.php

--
{$TRINITY20['site_name']}
EOD;
        @mail($user['email'], $lang['pm_forwardpm_pmfrom'].$username.'!', $body, "{$lang['pm_forwardpm_from']}{$TRINITY20['site_email']}");
    }
    //=== if they don't want to keep the message they are replying to then delete it!
    if ($delete != 0) {
        //=== be sure they should be deleting this...
        ($res = sql_query('SELECT saved, receiver FROM messages WHERE id='.sqlesc($delete))) || sqlerr(__FILE__, __LINE__);
        if ($res->num_rows > 0) {
            $arr = $res->fetch_assoc();
            //if ($arr['receiver'] !== $CURUSER['id'])
            if ($arr['receiver'] != $CURUSER['id']) {
                stderr($lang['pm_send_quote'], $lang['pm_send_thou']);
            }

            if ($arr['saved'] == 'no') {
                sql_query('DELETE FROM messages WHERE id = '.sqlesc($delete)) || sqlerr(__FILE__, __LINE__);
            } elseif ($arr['saved'] == 'yes') {
                sql_query('UPDATE messages SET location = 0 WHERE id = '.sqlesc($delete)) || sqlerr(__FILE__, __LINE__);
            }
        }
    }
    //=== if returnto sent
    if ($returnto) {
        header('Location: '.$returnto);
    } else {
        header('Location: pm_system.php?action=view_mailbox&sent=1');
    }


    die();
} //=== end of takesendmessage script
//=== basic page :D
$receiver = (isset($_GET['receiver']) ? (int)$_GET['receiver'] : (isset($_POST['receiver']) ? (int)$_POST['receiver'] : 0));
$replyto = (isset($_GET['replyto']) ? (int)$_GET['replyto'] : (isset($_POST['replyto']) ? (int)$_POST['replyto'] : 0));
$returnto = htmlsafechars($_POST['returnto'] ?? '');
if ($receiver === 0) {
    stderr($lang['pm_error'], $lang['pm_send_sysbot']);
}
if (!is_valid_id($receiver)) {
    stderr($lang['pm_error'], $lang['pm_send_mid']);
}


($res_member = sql_query('SELECT username FROM users WHERE id = '.sqlesc($receiver))) || sqlerr(__FILE__, __LINE__);
$arr_member = $res_member->fetch_row();
//=== if reply
if ($replyto != 0) {
    if (!validusername($arr_member[0])) {
        stderr($lang['pm_error'], $lang['pm_send_mid']);
    }

    //=== make sure they should be replying to this PM...
    ($res_old_message = sql_query('SELECT receiver, sender, subject, msg FROM messages WHERE id = '.sqlesc($replyto))) || sqlerr(__FILE__, __LINE__);
    $arr_old_message = $res_old_message->fetch_assoc();
    //print $arr_old_message['sender'];
    //exit();
    if ($arr_old_message['sender'] == $CURUSER['id']) {
        stderr($lang['pm_error'], $lang['pm_send_slander']);
    }

    if ($arr_old_message['receiver'] == $CURUSER['id']) {
        $body .= "\n\n\n{$lang['pm_send_wrote0']}$arr_member[0]{$lang['pm_send_wrote']}\n$arr_old_message[msg]\n";
        $subject = $lang['pm_send_re'].htmlsafechars($arr_old_message['subject']);
    }
}
//=== if preview or not replying
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $subject = trim(htmlsafechars($_POST['subject']));
    $body = trim(htmlsafechars($_POST['body']));
}
//=== and finally print the basic page  :D
$avatar = (($CURUSER['avatars'] === 'no') ? '' : (empty($CURUSER['avatar']) ? '
        <img width="80" src="pic/default_avatar.gif" alt="no avatar">' : (($CURUSER['offensive_avatar'] === 'yes' && $CURUSER['view_offensive_avatar'] === 'no') ? '<img width="80" src="pic/fuzzybunny.gif" alt="fuzzy!">' : '<img width="80" src="'.htmlsafechars($CURUSER['avatar']).'" alt="avatar">')));
//=== Code for preview Retros code
if (isset($_POST['buttonval']) && $_POST['buttonval'] == 'preview') {
    $HTMLOUT .= '<legend>'.$lang['pm_send_previewpm'].'</legend>
    <table class="striped">
    <tr>
        <td colspan="2" class="text-left"><span style="font-weight: bold;">subject: </span>'.htmlsafechars($subject).'</td>
    </tr>
    <tr>
        <td class="text-center" width="0px" id="photocol">'.$avatar.'</td>
        <td class="text-left" style="min-width:400px;padding:10px;vertical-align: top;">'.format_comment($body).'</td>
    </tr>
    </table>';
}
$HTMLOUT .= '<div class="callout">
	<form name="compose" method="post" action="pm_system.php">
            <input type="hidden" name="action" value="send_message">
            <input type="hidden" name="returnto" value="'.$returnto.'">
            <input type="hidden" name="replyto" value="'.$replyto.'">
            <input type="hidden" name="receiver" value="'.$receiver.'">
		<h6 class="sub-header">'.$lang['pm_send_sendmsg'].' <a class="altlink" href="userdetails.php?id='.$receiver.'">'.$arr_member[0].'</a></h6>
        <input name="subject" type="text" placeholder="'.$lang['pm_send_subject'].'" value="'.$subject.'">
        '.textbbcode('compose', 'body', $body).'
		<fieldset class="fieldset">
			<legend>Check these out</legend>
			'.($CURUSER['class'] >= UC_STAFF ? '<input id="urgent-check" type="checkbox" name="urgent" value="yes" '.((isset($_POST['urgent']) && $_POST['urgent'] === 'yes') ? ' checked="checked"' : '').'><label for="urgent-check">'.$lang['pm_send_mark'].'</label>' : '').'
			<input id="reply-check" type="checkbox" name="delete" value="'.$replyto.'" '.((isset($_POST['delete']) && $_POST['delete'] > 0) ? ' checked="checked"' : ($CURUSER['deletepms'] == 'yes' ? ' checked="checked"' : '')).'><label for="reply-check">'.$lang['pm_send_delete'].'</label>
			<input id="save-check" type="checkbox" name="save" value="1" '.((isset($_POST['draft']) && $_POST['draft'] == 1) ? ' checked="checked"' : '').'><label for="save-check">'.$lang['pm_send_savepm'].'</label>
			<input type="submit" class="small button float-right" name="buttonval" value="Preview">
			<input type="submit" class="small button float-right" name="buttonval" value="'.((isset($_POST['draft']) && $_POST['draft'] == 1) ? 'Save' : 'Send').'">
		</fieldset>
		</form></div>';
?>
