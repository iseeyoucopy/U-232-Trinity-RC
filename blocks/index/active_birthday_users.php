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
//==Start birthdayusers pdq
$current_date = getdate();
if (($birthday_users_cache = $cache->get($keys['birthdayusers'])) === false) {
    $birthdayusers = '';
    $birthday_users_cache = [];
    ($res = sql_query("SELECT id, username, class, donor, title, warned, enabled, chatpost, leechwarn, pirate, king, birthday, perms FROM users WHERE MONTH(birthday) = ".sqlesc($current_date['mon'])." AND DAYOFMONTH(birthday) = ".sqlesc($current_date['mday'])." AND perms < ".bt_options::PERMS_STEALTH." ORDER BY username ASC")) || sqlerr(__FILE__,
        __LINE__);
    $actcount = $res->num_rows;
    while ($arr = $res->fetch_assoc()) {
        if ($birthdayusers !== '') {
            $birthdayusers .= ",";
        }
        $birthdayusers .= '<b>'.format_username($arr).'</b>';
    }
    $birthday_users_cache['birthdayusers'] = $birthdayusers;
    $birthday_users_cache['actcount'] = $actcount;
    $cache->set($keys['birthdayusers'], $birthday_users_cache, $TRINITY20['expires']['birthdayusers']);
}
if (!$birthday_users_cache['birthdayusers']) {
    $birthday_users_cache['birthdayusers'] = $lang['index_birthday_no'];
}
$birthday_users = '<div class="card">
	<div class="card-divider">
		<label for="checkbox_4" class="text-left">'.$lang['index_birthday'].'&nbsp;&nbsp;<span class="badge btn btn-success disabled" style="color:#fff">'.$birthday_users_cache['actcount'].'</span></label>
	</div>
	<div class="card-section">'.$birthday_users_cache['birthdayusers'].'</div></div>';
$HTMLOUT .= $birthday_users."";
//==End
// End Class
// End File
