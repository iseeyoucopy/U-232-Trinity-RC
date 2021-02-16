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
if (($last24_cache = $cache->get($keys['last24'])) === false) {
    $last24_cache = array();
    $time24 = $_SERVER['REQUEST_TIME'] - 86400;
    $activeusers24 = '';
    ($arr_query = sql_query('SELECT * FROM avps WHERE arg = "last24"')) || sqlerr(__FILE__, __LINE__);
    $arr = $arr_query->fetch_assoc();
    ($res = sql_query('SELECT id, username, class, donor, title, warned, enabled, chatpost, leechwarn, pirate, king, perms ' . 'FROM users WHERE last_access >= ' . $time24 . ' ' . 'AND perms < ' . bt_options::PERMS_STEALTH . ' ORDER BY username ASC')) || sqlerr(__FILE__, __LINE__);
    $totalonline24 = $res->num_rows;
    $_ss24 = $totalonline24;
    $last24record = get_date($arr['value_u'], '');
    $last24 = $arr['value_i'];
    if ($totalonline24 > $last24) {
        $last24 = $totalonline24;
        $period = $_SERVER['REQUEST_TIME'];
        sql_query('UPDATE avps SET value_s = 0, ' . 'value_i = ' . sqlesc($last24) . ', ' . 'value_u = ' . sqlesc($period) . ' ' . 'WHERE arg = "last24"') || sqlerr(__FILE__, __LINE__);
    }
    while ($arr = $res->fetch_assoc()) {
        if ($activeusers24 !== '') $activeusers24.= ",\n";
        $activeusers24.= '<b>' . format_username($arr) . '</b>';
    }
    $last24_cache['activeusers24'] = $activeusers24;
    $last24_cache['totalonline24'] = number_format($totalonline24);
    $last24_cache['last24record'] = $last24record;
    $last24_cache['last24'] = number_format($last24);
    $last24_cache['ss24'] = $_ss24;
    $cache->set($keys['last24'], $last24_cache, $TRINITY20['expires']['last24']);
}
if (!$last24_cache['activeusers24']) $last24_cache['activeusers24'] = $lang['index_last24_nousers'];
$last24_cache['ss24'] = $last24_cache['totalonline24'] != 1 ? $lang['gl_members'] : $lang['gl_member'];
$last_24 = '<div class="card">
		<div class="card-divider">' . $lang['index_active24'] . '&nbsp;&nbsp;<span class="badge success" style="color:#fff">' . $last24_cache['totalonline24'] . '</span>&nbsp;&nbsp;' . $lang['index_last24_list'] . '</div>
        <div class="card-section">
        <p><b>' . $last24_cache['totalonline24'] . $last24_cache['ss24'] . '' . $lang['index_last24_during'] . '</b></p>
     <hr>
     <p>' . $last24_cache['activeusers24'] . '</p>
	 <hr>
     <p><b>' . $lang['index_last24_most'] . $last24_cache['last24'] . $last24_cache['ss24'] . $lang['index_last24_on'] . $last24_cache['last24record'] . '</b></p>
     </div></div>';
$HTMLOUT.= $last_24;
//==End
// End Class
// End File
