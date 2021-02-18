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
//=== invite stuff - who invited this member by snuggles
if ($user['invitedby'] > 0) {
    //=== Fetch inviter info
    ($res_get_invitor = sql_query('SELECT id, class, username, warned, suspended, enabled, donor, chatpost, leechwarn, pirate, king FROM users WHERE id='.sqlesc($user['invitedby']))) || sqlerr(__FILE__,
        __LINE__);
    $user_get_invitor = $res_get_invitor->fetch_assoc();
    $HTMLOUT .= '<tr><td class="rowhead">'.$lang['userdetails_invited_by'].'</td><td align="left">'.format_username($user_get_invitor).'</td></tr>';
} else {
    $HTMLOUT .= '<tr><td class="rowhead">'.$lang['userdetails_invited_by'].'</td><td align="left"><b>'.$lang['userdetails_iopen_s'].'</b></td></tr>';
}
//=== members invites by snuggles
$rez_invited = sql_query('SELECT id, class, username, email, uploaded, downloaded, status, warned, suspended, enabled, donor, email, ip, chatpost, leechwarn, pirate, king FROM users WHERE invitedby = '.sqlesc($user['id']).' ORDER BY added');
$inviteted_by_this_member = '';
if ($rez_invited->num_rows < 1) {
    $inviteted_by_this_member .= 'No invitees yet.';
} else {
    $inviteted_by_this_member .= '<table width="100%" border="1" cellspacing="0" cellpadding="5">
		<tr><td class="colhead"><b>'.$lang['userdetails_u_ip'].'</b></td>
		<td class="colhead"><b>'.$lang['userdetails_email'].'l</b></td>
		<td class="colhead"><b>'.$lang['userdetails_uploaded'].'</b></td>
		'.($TRINITY20['ratio_free'] ? '' : '<td class="colhead"><b>'.$lang['userdetails_downloaded'].'</b></td>').'
		<td class="colhead"><b>'.$lang['userdetails_ratio'].'</b></td>
		<td class="colhead"><b>'.$lang['userdetails_status'].'</b></td></tr>';
    while ($arr_invited = $rez_invited->fetch_assoc()) {
        $inviteted_by_this_member .= '<tr><td>'.($arr_invited['status'] == 'pending' ? htmlsafechars($arr_invited['username']) : format_username($arr_invited).'<br />'.$arr_invited['ip']).'</td>
		<td>'.htmlsafechars($arr_invited['email']).'</td>
		<td>'.mksize($arr_invited['uploaded']).'</td>
		'.($TRINITY20['ratio_free'] ? '' : '<td>'.mksize($arr_invited['downloaded']).'</td>').'
		<td>'.member_ratio($arr_invited['uploaded'], $TRINITY20['ratio_free'] ? '0' : $arr_invited['downloaded']).'</td>
		<td>'.($arr_invited['status'] == 'confirmed' ? '<span style="color: green;">'.$lang['userdetails_confirmed'].'</span></td></tr>' : '<span style="color: red;">'.$lang['userdetails_pending'].'</span></td></tr>');
    }
    $inviteted_by_this_member .= '</table>';
}
$the_flip_box_5 = '[ <a name="invites"></a><a class="altlink" href="#invites" onclick="javascript:flipBox(\'5\')" name="b_5" title="'.$lang['userdetails_open_close_inv'].'">'.$lang['userdetails_inv_view'].')" src="pic/panel_on.gif" name="b_5" style="vertical-align:middle;"  width="8" height="8" alt="'.$lang['userdetails_open_close_inv1'].'" title="'.$lang['userdetails_open_close_inv1'].'" /></a> ] [ <a class="altlink" href="staffpanel.php?tool=invite_tree&amp;action=invite_tree&amp;id='.(int)$user['id'].'" title="'.$lang['userdetails_inv_click'].'">'.$lang['userdetails_inv_viewt'].'</a> ]';
$HTMLOUT .= '<tr><td class="rowhead">'.$lang['userdetails_invitees'].'</td><td align="left">'.($rez_invited->num_rows > 0 ? $the_flip_box_5.'<div align="left" id="box_5" style="display:none">
    <br />'.$inviteted_by_this_member.'</div>' : $lang['userdetails_no_invitees']).'</td></tr>';
// End Class
// End File
