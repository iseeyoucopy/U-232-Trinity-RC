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
// pdq 2010
function invincible($id, $invincible = true, $bypass_bans = true)
{
    global $CURUSER, $cache, $TRINITY20, $keys;
	$lang = load_language('invincible_function');
    $ip = '127.0.0.1';
    $setbits = $clrbits = 0;
    if ($invincible) {
        $display = $lang['invincible_now'];
        $setbits|= bt_options::PERMS_NO_IP; // don't log IPs
        if ($bypass_bans) {
            $setbits |= bt_options::PERMS_BYPASS_BAN;
        } // bypass ban on
        else {
            $clrbits|= bt_options::PERMS_BYPASS_BAN; // bypass ban off
            $display = $lang['invincible_now_bypass'];
        }
    } else {
        $display = $lang['invincible_no_longer'];
        $clrbits|= bt_options::PERMS_NO_IP; // log IPs
        $clrbits|= bt_options::PERMS_BYPASS_BAN; // bypass ban off
    }
    // update perms
    if ($setbits || $clrbits) {
        sql_query('UPDATE users SET perms = ((perms | '.$setbits.') & ~'.$clrbits.') 
                 WHERE id = '.sqlesc($id)) || sqlerr(__file__, __line__);
    }
    // grab current data
    ($res = sql_query('SELECT username, torrent_pass, ip, perms, modcomment FROM users 
                     WHERE id = ' . sqlesc($id) . ' LIMIT 1')) || sqlerr(__file__, __line__);
    $row = $res->fetch_assoc();
    $row['perms'] = (int)$row['perms'];
    // delete from iplog current ip
    sql_query('DELETE FROM `ips` WHERE userid = ' .sqlesc($id)) || sqlerr(__file__, __line__);
    // delete any iplog caches
    $cache->delete('ip_history_' . $id);
    $cache->delete('u_passkey_' . $row['torrent_pass']);
    // update ip in db
    $modcomment = get_date(TIME_NOW, '', 1) . ' - ' . $display . $lang['invincible_thanks_to'] . $CURUSER['username'] . "\n" . $row['modcomment'];
    //ipf = '.sqlesc($ip).',
    sql_query('UPDATE users SET ip = ' . sqlesc($ip) . ', modcomment = ' . sqlesc($modcomment) . '
              WHERE id = ' . sqlesc($id)) || sqlerr(__file__, __line__);
    //'ipf'   => $ip,
    // update ip in caches
    //$cache->delete('user'.$id);
    $cache->update_row('user' . $id, [
        'ip' => $ip,
        'perms' => $row['perms']
    ], $TRINITY20['expires']['user_cache']);
    $cache->update_row($keys['my_userid'] . $id, [
        'ip' => $ip,
        'perms' => $row['perms']
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row('user_stats_' . $id, [
        'modcomment' => $modcomment
    ], $TRINITY20['expires']['user_stats']);
    //'ipf'   => $ip,
    if ($id == $CURUSER['id']) {
        $cache->update_row('user' . $CURUSER['id'], [
            'ip' => $ip,
            'perms' => $row['perms']
        ], $TRINITY20['expires']['user_cache']);
        $cache->update_row($keys['my_userid'] . $CURUSER['id'], [
            'ip' => $ip,
            'perms' => $row['perms']
        ], $TRINITY20['expires']['curuser']);
        $cache->update_row('user_stats_' . $CURUSER['id'], [
            'modcomment' => $modcomment
        ], $TRINITY20['expires']['user_stats']);
    }
    write_log(''.$lang['invincible_member'].'[b][url=userdetails.php?id=' . $id . ']' . (htmlsafechars($row['username'])) . '[/url][/b]' . $lang['invincible_is'] . ' ' . $display . ' ' . $lang['invincible_thanks_to1'] . ' [b]' . $CURUSER['username'] . '[/b]');
    // header ouput
    $cache->set('display_' . $CURUSER['id'], $display, 5);
    header('Location: userdetails.php?id=' . $id);
    exit();
}
?>
