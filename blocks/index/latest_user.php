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
/** latestuser index **/
if (($latestuser_cache = $cache->get($keys['latestuser'])) === false) {
    $latestuser_cache = mysqli_fetch_assoc(sql_query('SELECT id, username, class, donor, warned, enabled, chatpost, leechwarn, pirate, king FROM users WHERE status="confirmed" ORDER BY id DESC LIMIT 1'));
    $latestuser_cache['id'] = (int)$latestuser_cache['id'];
    $latestuser_cache['class'] = (int)$latestuser_cache['class'];
    $latestuser_cache['warned'] = (int)$latestuser_cache['warned'];
    $latestuser_cache['chatpost'] = (int)$latestuser_cache['chatpost'];
    $latestuser_cache['leechwarn'] = (int)$latestuser_cache['leechwarn'];
    $latestuser_cache['pirate'] = (int)$latestuser_cache['pirate'];
    $latestuser_cache['king'] = (int)$latestuser_cache['king'];
    $cache->set($keys['latestuser'], $latestuser_cache, $TRINITY20['expires']['latestuser']);
}
$HTMLOUT.= '<div class="card">
    <div class="card-divider">' . $lang['index_lmember'] . '</div>
        <div class="card-section">' . $lang['index_wmember'] . format_username($latestuser_cache) . '!</div>
    </div>';
//==End	
// End Class
// End File
