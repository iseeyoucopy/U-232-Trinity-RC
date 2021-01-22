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
if (($active_users_cache = $cache->get($keys['act_users'] )) === false) {
    $dt = $_SERVER['REQUEST_TIME'] - 180;
    $activeusers = '';
    $active_users_cache = array();
    $res = sql_query('SELECT id, username, class, donor, title, warned, enabled, chatpost, leechwarn, pirate, king, perms ' . 'FROM users WHERE last_access >= ' . $dt . ' ' . 'AND perms < ' . bt_options::PERMS_STEALTH . ' ORDER BY username ASC') or sqlerr(__FILE__, __LINE__);
    $actcount = $res->num_rows();
    $v = ($actcount != 1 ? 's' : '');
    while ($arr = $res->fetch_assoc()) {
        if ($activeusers) $activeusers.= ",";
        $activeusers.= format_username($arr);
    }
    $active_users_cache['activeusers'] = $activeusers;
    $active_users_cache['actcount'] = $actcount;
    $active_users_cache['au'] = number_format($actcount);
    $active_users_cache['aaaa'] = "<span class='badge'>" . $active_users_cache['actcount'] . "</span>";
    $last24_cache['v'] = $v;
    $cache->set($keys['act_users'], $active_users_cache, $TRINITY20['expires']['activeusers']);
}
if (!$active_users_cache['activeusers']) $active_users_cache['activeusers'] = $lang['index_active_users_no'];

$active_users = '
<div class="card">
<div class="card-divider">
<label for="checkbox_4" class="text-left">' . $lang['index_active'] . '&nbsp;&nbsp;'.$active_users_cache['aaaa'].'</label>
	</div>
<div class="card-section">
<p>' . $active_users_cache['activeusers'] . '</p>
</div></div>';
$HTMLOUT.= $active_users."";
//==End
// End Class
// End File
