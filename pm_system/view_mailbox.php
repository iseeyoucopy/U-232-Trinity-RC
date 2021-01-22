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
    $HTMLOUT.= '<!doctype html>
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
//=== get mailbox name
if ($mailbox > 1) {
    //== get name of PM box if not in or out
    $res_box_name = sql_query('SELECT name FROM pmboxes WHERE userid = ' . sqlesc($CURUSER['id']) . ' AND boxnumber=' . sqlesc($mailbox) . ' LIMIT 1') or sqlerr(__FILE__, __LINE__);
    $arr_box_name = mysqli_fetch_row($res_box_name);
    if (mysqli_num_rows($res_box_name) === 0) stderr($lang['pm_error'], $lang['pm_mailbox_invalid']);
    $mailbox_name = htmlsafechars($arr_box_name[0]);
    $other_box_info = '<p align="center"><span style="color: red;">' . $lang['pm_mailbox_asterisc'] . '</span><span style="font-weight: bold;">' . $lang['pm_mailbox_note'] . '</span>
                                            ' . $lang['pm_mailbox_max'] . '<span style="font-weight: bold;">' . $maxbox . '</span>' . $lang['pm_mailbox_either'] . '
                                            <span style="font-weight: bold;">' . $lang['pm_mailbox_inbox'] . '</span>' . $lang['pm_mailbox_or'] . '<span style="font-weight: bold;">' . $lang['pm_mailbox_sentbox'] . '</span>' . $lang['pm_mailbox_dot'] . '</p>';
}
//==== get count from PM boxs & get image & % box full
//=== get stuff for the pager
$res_count = sql_query('SELECT COUNT(id) FROM messages WHERE ' . ($mailbox === PM_INBOX ? 'receiver = ' . sqlesc($CURUSER['id']) . ' AND location = 1' : ($mailbox === PM_SENTBOX ? 'sender = ' . sqlesc($CURUSER['id']) . ' AND (saved = \'yes\' || unread= \'yes\') AND draft = \'no\' ' : 'receiver = ' . sqlesc($CURUSER['id'])) . ' AND location = ' . sqlesc($mailbox))) or sqlerr(__FILE__, __LINE__);
$arr_count = mysqli_fetch_row($res_count);
$messages = $arr_count[0];
//==== get count from PM boxs & get image & % box full
$filled = $messages > 0 ? (($messages / $maxbox) * 100) : 0;
//$filled = (($messages / $maxbox) * 100);
$mailbox_pic = get_percent_completed_image(round($filled));
$num_messages = number_format($filled, 0);
$link = 'pm_system.php?action=view_mailbox&amp;box=' . $mailbox . ($perpage < $messages ? '&amp;page=' . $page : '') . '&amp;order_by=' . $order_by . $desc_asc;
list($menu, $LIMIT) = pager_new($messages, $perpage, $page, $link);
//=== get message info we need to display then all nice and tidy like \o/
$res = sql_query('SELECT m.id AS message_id, m.sender, m.receiver, m.added, m.subject, m.unread, m.urgent, u.id, u.username, u.uploaded, u.downloaded, u.warned, u.suspended, u.enabled, u.donor, u.class, u.avatar, u.opt1, u.opt2,  u.leechwarn, u.chatpost, u.pirate, u.king, f.id AS friend, b.id AS blocked FROM messages AS m 
                            LEFT JOIN users AS u ON u.id=m.' . ($mailbox === PM_SENTBOX ? 'receiver' : 'sender') . ' 
                            LEFT JOIN friends AS f ON f.userid = ' . $CURUSER['id'] . ' AND f.friendid = m.sender
                            LEFT JOIN blocks AS b ON b.userid = ' . $CURUSER['id'] . ' AND b.blockid = m.sender
                            WHERE ' . ($mailbox === PM_INBOX ? 'receiver = ' . $CURUSER['id'] . ' AND location = 1' : ($mailbox === PM_SENTBOX ? 'sender = ' . $CURUSER['id'] . ' AND (saved = \'yes\' || unread= \'yes\') AND draft = \'no\' ' : 'receiver = ' . $CURUSER['id'] . ' AND location = ' . sqlesc($mailbox))) . ' 
                            ORDER BY ' . $order_by . (isset($_GET['ASC']) ? ' ASC ' : ' DESC ') . $LIMIT) or sqlerr(__FILE__, __LINE__);
//=== Start Page
//echo stdhead(htmlsafechars($mailbox_name));
//=== let's make the table
$HTMLOUT.= $h1_thingie;
$HTMLOUT.='<a name="pm"></a><form action="pm_system.php" method="post" name="checkme" onsubmit="return ValidateForm(this,\'pm\')"><div class="grid-x grid-margin-x">';
$HTMLOUT.='
	<div class="cell large-12 callout">
		' . $messages . ' / ' . $maxbox . '
		' . $mailbox_name . '
        ' . $lang['pm_mailbox_full'] . '
		' . $num_messages . '
		' . $lang['pm_mailbox_full1'] . '
        ' . $mailbox_pic . '
	</div>
	<div class="cell large-9 callout secondary">
    <table class="striped">
	<thead>
    <tr>
        <th width="20">
			<input type="hidden" name="action" value="move_or_delete_multi">
		</th>
        <th><a class="altlink" href="pm_system.php?action=view_mailbox&amp;box=' . $mailbox . ($perpage == 20 ? '' : '&amp;perpage=' . $perpage) . ($perpage < $messages ? '&amp;page=' . $page : '') . '&amp;order_by=subject' . $desc_asc . '#pm" title="' . $lang['pm_mailbox_sorder'] . '' . $desc_asc_2 . '">' . $lang['pm_mailbox_subject'] . '</a></th>
        <th><a class="altlink" href="pm_system.php?action=view_mailbox&amp;box=' . $mailbox . ($perpage == 20 ? '' : '&amp;perpage=' . $perpage) . ($perpage < $messages ? '&amp;page=' . $page : '') . '&amp;order_by=username' . $desc_asc . '#pm" title="' . $lang['pm_mailbox_morder'] . '' . $desc_asc_2 . '">' . ($mailbox === PM_SENTBOX ? $lang['pm_search_sent_to'] : $lang['pm_search_sender']) . '</a></th>
        <th><a class="altlink" href="pm_system.php?action=view_mailbox&amp;box=' . $mailbox . ($perpage == 20 ? '' : '&amp;perpage=' . $perpage) . ($perpage < $messages ? '&amp;page=' . $page : '') . '&amp;order_by=added' . $desc_asc . '#pm" title="' . $lang['pm_mailbox_dorder'] . '' . $desc_asc_2 . '">' . $lang['pm_mailbox_date'] . '</a></th>
        <th></th>
    </tr>
	</thead>';
if ($res->num_row() === 0) {
    $HTMLOUT.= '
        <tr>
           <td><span style="font-weight: bold;">' . $lang['pm_mailbox_nomsg'] . '' . $mailbox_name . '</span></td>
        </tr>';
} else {
    while ($row = $res->fetch_assoc()) {
        $subject = (!empty($row['subject']) ? htmlsafechars($row['subject']) : $lang['pm_search_nosubject']);
        $who_sent_it = ($row['id'] == 0 ? '<span style="font-weight: bold;">'. $lang['pm_forward_system'] . '</span>' : print_user_stuff($row));
        $read_unread = ($row['unread'] === 'yes' ? '<i class="fas fa-envelope"></i>' : '<i class="fas fa-envelope-open"></i>');
        $extra = ($row['unread'] === 'yes' ? $lang['pm_mailbox_char1'] . '<span style="color: red;">' . $lang['pm_mailbox_unread'] . '</span>' . $lang['pm_mailbox_char2'] . '' : '') . ($row['urgent'] === 'yes' ? '<span style="color: red;">' . $lang['pm_mailbox_urgent'] . '</span>' : '');
        $HTMLOUT.= '
		<tbody>
                <tr>
                    <td>' . $read_unread . '</td>
                    <td><a href="pm_system.php?action=view_message&amp;id=' . (int)$row['message_id'] . '">' . $subject . '</a>' . $extra . '</td>
                    <td>' .  $who_sent_it . '</td>
                    <td>' . get_date($row['added'], '') . '</td>
                    <td><input type="checkbox" name="pm[]" value="' . (int)$row['message_id'] . '"></td>
                </tr>
				</tbody>';
    }
}
//=== per page drop down
$per_page_drop_down = '<form action="pm_system.php" method="post">
	<select name="amount_per_page" onchange="location = this.options[this.selectedIndex].value;">';
$i = 20;
while ($i <= ($maxbox > 200 ? 200 : $maxbox)) {
    $per_page_drop_down.= '<option value="' . $link . '&amp;change_pm_number=' . $i . '"  ' . ($CURUSER['pms_per_page'] == $i ? ' selected="selected"' : '') . '>' . $i . '' .$lang['pm_edmail_perpage'] . '</option>';
    $i = ($i < 100 ? $i = $i + 10 : $i = $i + 25);
}
$per_page_drop_down.= '</select><input type="hidden" name="box" value="' . $mailbox . '" /></form>';
//=== the bottom
if ($res->num_row() > 0) {
	$HTMLOUT.='<a href="javascript:SetChecked(1,\'pm[]\')">' .$lang['pm_search_selall'] . '</a>
	<a href="javascript:SetChecked(0,\'pm[]\')">' .$lang['pm_search_unsellall'] . '</a>   
	<input type="submit" class="button" name="move" value="' .$lang['pm_search_move_to'] . '"> ' . get_all_boxes() . ' or
   <input type="submit" class="button" name="delete" value="' .$lang['pm_search_delete'] . '">
   <div class="callout">' . insertJumpTo($mailbox) . $other_box_info . ($perpage < $messages ? $menu . '' : '') . '</div>';
}
else
{
	$HTMLOUT.= '';
}
$HTMLOUT.= '</table></div><div class="cell large-3">
	<ul class="vertical menu">'.$top_links.'</ul></div></div></form>';
?>
