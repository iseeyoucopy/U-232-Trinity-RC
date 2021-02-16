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
$subject = $friends = '';
//=== don't allow direct access
if (!defined('BUNNY_PM_SYSTEM')) {
    $HTMLOUT.= '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
        <title>ERROR</title>
        </head><body>
        <h1 class="text-center">ERROR</h1>
        <p class="text-center">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
//=== Get the message
($res = sql_query('SELECT m.*, f.id AS friend, b.id AS blocked
                            FROM messages AS m LEFT JOIN friends AS f ON f.userid = ' . sqlesc($CURUSER['id']) . ' AND f.friendid = m.sender
                            LEFT JOIN blocks AS b ON b.userid = ' . sqlesc($CURUSER['id']) . ' AND b.blockid = m.sender WHERE m.id = ' . sqlesc($pm_id) . ' AND (receiver=' . sqlesc($CURUSER['id']) . ' OR (sender=' . sqlesc($CURUSER['id']) . ' AND (saved = \'yes\' || unread= \'yes\'))) LIMIT 1')) || sqlerr(__FILE__, __LINE__);
$message = $res->fetch_assoc();
if (!$res)	stderr($lang['pm_error'], $lang['pm_viewmsg_err']);
//=== get user stuff ===//
($res_user_stuff = sql_query('SELECT id, username, uploaded, warned, suspended, enabled, donor, class, avatar, leechwarn, chatpost, pirate, king, opt1, opt2 FROM users WHERE id=' . ($message['sender'] === $CURUSER['id'] ? sqlesc($message['receiver']) : sqlesc($message['sender'])))) || sqlerr(__FILE__, __LINE__);
$arr_user_stuff = $res_user_stuff->fetch_assoc();
$id = isset($arr_user_stuff['id']) ? (int)$arr_user_stuff['id'] : '';
//=== Mark message read ===//
sql_query('UPDATE messages SET unread=\'no\' WHERE id=' . sqlesc($pm_id) . ' AND receiver=' . sqlesc($CURUSER['id']) . ' LIMIT 1') || sqlerr(__FILE__, __LINE__);
$cache->delete('inbox_new::' . $CURUSER['id']);
$cache->delete('inbox_new_sb::' . $CURUSER['id']);
if ($message['friend'] > 0) 
	$friends = '<a class="tiny button" href="friends.php?action=delete&amp;type=friend&amp;targetid=' . $id . '">' . $lang['pm_mailbox_removef'] . '</a>';
elseif ($message['blocked'] > 0) 
	$friends = '<a class="tiny button" href="friends.php?action=delete&amp;type=block&amp;targetid=' . $id . '">' . $lang['pm_mailbox_removeb'] . '</a>';
elseif ($id > 0) $friends = '<a class="tiny button" href="friends.php?action=add&amp;type=friend&amp;targetid=' . $id . '">' . $lang['pm_mailbox_addf'] . '</a>
<a class="tiny button" href="friends.php?action=add&amp;type=block&amp;targetid=' . $id . '">' . $lang['pm_mailbox_addb'] . '</a>';
$avatar = ((!$CURUSER['opt1'] & user_options::AVATARS) !== 0 ? '' : (empty($arr_user_stuff['avatar']) ? '
    <img width="80" src="pic/default_avatar.gif" alt="no avatar" />' : (($arr_user_stuff['opt1'] & user_options::OFFENSIVE_AVATAR && !$CURUSER['opt1'] & user_options::VIEW_OFFENSIVE_AVATAR) ? '<img width="80" src="pic/fuzzybunny.gif" alt="fuzzy!" />' : '<a href="' . htmlsafechars($arr_user_stuff['avatar']) . '"><img width="80" src="' . htmlsafechars($arr_user_stuff['avatar']) . '" alt="avatar" /></a>')));
//=== get mailbox name ===//
if ($message['location'] > 1) {
    //=== get name of PM box if not in or out ===//
    ($res_box_name = sql_query('SELECT name FROM pmboxes WHERE userid = ' . sqlesc($CURUSER['id']) . ' AND boxnumber=' . sqlesc($mailbox) . ' LIMIT 1')) || sqlerr(__FILE__, __LINE__);
    $arr_box_name = $res_box_name->fetch_row();
    if ($res->num_rows === 0) stderr($lang['pm_error'], $lang['pm_mailbox_invalid']);
    $mailbox_name = htmlsafechars($arr_box_name[0]);
    $other_box_info = '<p class="text-center"><span style="color: red;">' . $lang['pm_mailbox_asterisc'] . '</span><span style="font-weight: bold;">' . $lang['pm_mailbox_note'] . '</span>
                                           ' . $lang['pm_mailbox_max'] . '<span style="font-weight: bold;">' . $maxbox . '</span>' . $lang['pm_mailbox_either'] . '
                                            <span style="font-weight: bold;">' . $lang['pm_mailbox_inbox'] . '</span>' . $lang['pm_mailbox_or'] . '<span style="font-weight: bold;">' . $lang['pm_mailbox_sentbox'] . '</span>.</p>';
}
//=== Display the message ===//
$userStuffId = isset($arr_user_stuff['id']) ? (int)$arr_user_stuff['id'] : '';
$HTMLOUT.= $h1_thingie . ($message['draft'] === 'yes' ? '<h6>' . $lang['pm_viewmsg_tdraft'] . '</h6>' : '<h6>' . $lang['pm_viewmsg_mailbox'] . '' . $mailbox_name . '</h6>');
$HTMLOUT.='<div class="grid-x callout">';
$HTMLOUT.='<div class="cell large-3 secondary"><ul class="vertical menu">
'.$top_links.'
<ul></div>
<div class="cell large-9 secondary">
		<div class="callout success margin-0">
		<div class="clearfix">
		<div class="float-left">
			'.($message['draft'] === 'no' ? '<a href="pm_system.php?action=save_or_edit_draft&amp;id=' . $pm_id . '">' . $lang['pm_viewmsg_sdraft'] . '</a>' : '<a href="pm_system.php?action=save_or_edit_draft&amp;edit=1&amp;id=' . $pm_id . '">' . $lang['pm_viewmsg_dedit'] . '</a> | <a href="pm_system.php?action=use_draft&amp;send=1&amp;id=' . $pm_id . '">' . $lang['pm_viewmsg_duse'] . '</a>').'
		</div>
		<div class="float-right">
		' .(($id < 1 || $message['sender'] === $CURUSER['id']) ? '' : '<a href="pm_system.php?action=send_message&amp;receiver=' . (int)$message['sender'] . '&amp;replyto=' . $pm_id . '" title="' . $lang['pm_viewmsg_reply'] . '"><i class="fab fa-replyd"></i></a>') . '
			<a href="pm_system.php?action=forward&amp;id=' . $pm_id . '" title="' . $lang['pm_viewmsg_fwd'] . '"><i class="fas fa-share"></i></a>
			<a href="pm_system.php?action=delete&amp;id=' . $pm_id . '" title="' . $lang['pm_viewmsg_delete'] . '"><i class="fas fa-trash-alt"></i></a>
			<!---<a href=""><input type="submit" class="small button" value="' . $lang['pm_viewmsg_move'] . '"></a>--->
		</div>
		</div><p>' . ($message['sender'] === $CURUSER['id'] ? $lang['pm_viewmsg_to'] : 'From:') . ($userStuffId == 0 ? $lang['pm_viewmsg_sys'] : print_user_stuff($arr_user_stuff)).'</p>
		' . $lang['pm_send_subject'] . '
		' . ($message['subject'] !== '' ? htmlsafechars($message['subject']) : $lang['pm_search_nosubject'] ) . ($message['urgent'] === 'yes' ? '<span class="float-right label alert"> ' . $lang['pm_mailbox_urgent'].'</span>' : '') . '       
        '. (($message['sender'] === $CURUSER['id'] && $message['unread'] == 'yes') ? '' . $lang['pm_mailbox_char1'] . $lang['pm_mailbox_unread'] . $lang['pm_mailbox_char2'] : '').'
		<div class="callout success margin-0">'. format_comment($message['msg']) . '</div>
		<div class="clearfix">
		 <div class="float-left">'. $friends .'</div>
		<div class="float-right">'. get_date($message['added'], '').'</div></div> 
	</div>
</div>
<form role="form" action="pm_system.php" method="post"> 
<div class="input-group">      
<input type="hidden" name="id" value="' . $pm_id . '">
<input type="hidden" name="action" value="move"></div></form></div>';
?>
