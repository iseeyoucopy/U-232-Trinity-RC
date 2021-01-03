<?php
$checkbox = $USERBLOCKS['index_ie_alert_on'] == 'yes' ? "checked" : "";
$HTMLOUT.= "
<tr>
	<td>{$lang['user_b_nb1']}</td>
	<td>
		Yes<input onchange='this.form.submit()' type='radio' name='index_news_on' value='yes' " . ($USERBLOCKS['index_news_on'] == 'yes' ? 'checked' : '') . " />
		No<input onchange='this.form.submit()' type='radio' name='index_news_on' value='no' " . ($USERBLOCKS['index_news_on'] == 'no' ? 'checked' : '') . " />
	</td>
</tr>
<tr>
	<td>{$lang['user_b_sh1']}</td>
	<td>
		Yes<input type='radio' name='index_shoutbox_on' value='yes' " . ($USERBLOCKS['index_shoutbox_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
		No<input type='radio' name='index_shoutbox_on' value='no' " . ($USERBLOCKS['index_shoutbox_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
	</td>
</tr>";
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT.= "
<tr>
<td>{$lang['user_b_shst1']}</td>
	<td>
	Yes<input type='radio' name='index_staff_shoutbox_on' value='yes' " . ($USERBLOCKS['index_staff_shoutbox_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_staff_shoutbox_on' value='no' " . ($USERBLOCKS['index_staff_shoutbox_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>";
}
$HTMLOUT.= "<tr>
<td>{$lang['user_b_actu1']}</td>
	<td>
	Yes<input type='radio' name='index_active_users_on' value='yes' " . ($USERBLOCKS['index_active_users_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_active_users_on' value='no' " . ($USERBLOCKS['index_active_users_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_act24']}</td>
	<td>
	Yes<input type='radio' name='index_last_24_active_users_on' value='yes' " . ($USERBLOCKS['index_last_24_active_users_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_last_24_active_users_on' value='no' " . ($USERBLOCKS['index_last_24_active_users_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
</tr>
<tr>
<td>{$lang['user_b_bir1']}</td>
	<td>
	Yes<input type='radio' name='index_birthday_active_users_on' value='yes' " . ($USERBLOCKS['index_birthday_active_users_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_birthday_active_users_on' value='no' " . ($USERBLOCKS['index_birthday_active_users_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_sit1']}</td>
	<td>
	Yes<input type='radio' name='index_stats_on' value='yes' " . ($USERBLOCKS['index_stats_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_stats_on' value='no' " . ($USERBLOCKS['index_stats_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_disc1']}</td>
	<td>
	Yes<input type='radio' name='index_disclaimer_on' value='yes' " . ($USERBLOCKS['index_disclaimer_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_disclaimer_on' value='no' " . ($USERBLOCKS['index_disclaimer_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_last1']}</td>
	<td>
	Yes<input type='radio' name='index_latest_user_on' value='yes' " . ($USERBLOCKS['index_latest_user_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_latest_user_on' value='no' " . ($USERBLOCKS['index_latest_user_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_fol1']}</td>
	<td>
	Yes<input type='radio' name='index_forumposts_on' value='yes' " . ($USERBLOCKS['index_forumposts_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_forumposts_on' value='no' " . ($USERBLOCKS['index_forumposts_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_torl1']}</td>
	<td>
	Yes<input type='radio' name='index_latest_torrents_on' value='yes' " . ($USERBLOCKS['index_latest_torrents_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_latest_torrents_on' value='no' " . ($USERBLOCKS['index_latest_torrents_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_tors1']}</td>
	<td>
	Yes<input type='radio' name='index_latest_torrents_scroll_on' value='yes' " . ($USERBLOCKS['index_latest_torrents_scroll_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_latest_torrents_scroll_on' value='no' " . ($USERBLOCKS['index_latest_torrents_scroll_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_ann1']}</td>
	<td>
	Yes<input type='radio' name='index_announcement_on' value='yes' " . ($USERBLOCKS['index_announcement_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_announcement_on' value='no' " . ($USERBLOCKS['index_announcement_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_don1']}</td>
	<td>
	Yes<input type='radio' name='index_donation_progress_on' value='yes' " . ($USERBLOCKS['index_donation_progress_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_donation_progress_on' value='no' " . ($USERBLOCKS['index_donation_progress_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_adv1']}</td>
	<td>
	Yes<input type='radio' name='index_advertisements_on' value='yes' " . ($USERBLOCKS['index_advertisements_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_advertisements_on' value='no' " . ($USERBLOCKS['index_advertisements_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_rad1']}</td>
	<td>
	Yes<input type='radio' name='index_radio_on' value='yes' " . ($USERBLOCKS['index_radio_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_radio_on' value='no' " . ($USERBLOCKS['index_radio_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_tf1']}</td>
	<td>
	Yes<input type='radio' name='index_torrentfreak_on' value='yes' " . ($USERBLOCKS['index_torrentfreak_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_torrentfreak_on' value='no' " . ($USERBLOCKS['index_torrentfreak_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_xmas1']}</td>
	<td>
	Yes<input type='radio' name='index_xmas_gift_on' value='yes' " . ($USERBLOCKS['index_xmas_gift_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_xmas_gift_on' value='no' " . ($USERBLOCKS['index_xmas_gift_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_poll1']}</td>
	<td>
	Yes<input type='radio' name='index_active_poll_on' value='yes' " . ($USERBLOCKS['index_active_poll_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_active_poll_on' value='no' " . ($USERBLOCKS['index_active_poll_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_mow1']}</td>
	<td>
	Yes<input type='radio' name='index_movie_ofthe_week_on' value='yes' " . ($USERBLOCKS['index_movie_ofthe_week_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_movie_ofthe_week_on' value='no' " . ($USERBLOCKS['index_movie_ofthe_week_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>
<tr>
<td>{$lang['user_b_rqno1']}</td>
	<td>
	Yes<input type='radio' name='index_requests_and_offers_on' value='yes' " . ($USERBLOCKS['index_requests_and_offers_on'] == 'yes' ? 'checked=\'checked\'' : '') . " />
	No<input type='radio' name='index_requests_and_offers_on' value='no' " . ($USERBLOCKS['index_requests_and_offers_on'] == 'no' ? 'checked=\'checked\'' : '') . " />
</td>
</tr>

<tr><td colspan='2' class='table' align='center'><input class='btn btn-default' type='submit' value='{$lang['user_b_butt']}' /></td></tr>
</table>";
?>