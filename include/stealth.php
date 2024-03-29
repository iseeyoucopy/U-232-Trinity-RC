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
function stealth($id, $stealth = true)
{
    global $CURUSER, $cache, $TRINITY20, $cache_keys;
    $setbits = $clrbits = 0;
    if ($stealth) {
        $display = 'is';
        $setbits |= bt_options::PERMS_STEALTH; // stealth on
    } else {
        $display = 'is not';
        $clrbits |= bt_options::PERMS_STEALTH; // stealth off
    }
    // update perms
    if ($setbits || $clrbits) {
        sql_query('UPDATE users SET perms = ((perms | ' . $setbits . ') & ~' . $clrbits . ') 
                 WHERE id = ' . sqlesc($id)) || sqlerr(__file__, __line__);
    }
    // grab current data
    ($res = sql_query('SELECT username, perms, modcomment FROM users 
                     WHERE id = ' . sqlesc($id) . ' LIMIT 1')) || sqlerr(__file__, __line__);
    $row = $res->fetch_assoc();
    $row['perms'] = (int)$row['perms'];
    $modcomment = get_date(TIME_NOW, '', 1) . ' - ' . $display . ' in Stealth Mode thanks to ' . $CURUSER['username'] . "\n" . $row['modcomment'];
    sql_query('UPDATE users SET modcomment = ' . sqlesc($modcomment) . ' WHERE id = ' . sqlesc($id)) || sqlerr(__file__, __line__);
    // update caches
    $cache->update_row($cache_keys['user'] . $id, [
        'perms' => $row['perms'],
    ], $TRINITY20['expires']['user_cache']);
    $cache->update_row($cache_keys['my_userid'] . $id, [
        'perms' => $row['perms'],
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row($cache_keys['user_statss'] . $id, [
        'modcomment' => $modcomment,
    ], $TRINITY20['expires']['user_stats']);
    if ($id == $CURUSER['id']) {
        $cache->update_row($cache_keys['user'] . $CURUSER['id'], [
            'perms' => $row['perms'],
        ], $TRINITY20['expires']['user_cache']);
        $cache->update_row($cache_keys['my_userid'] . $CURUSER['id'], [
            'perms' => $row['perms'],
        ], $TRINITY20['expires']['curuser']);
        $cache->update_row($cache_keys['user_statss'] . $CURUSER['id'], [
            'modcomment' => $modcomment,
        ], $TRINITY20['expires']['user_stats']);
    }
    write_log('Member [b][url=userdetails.php?id=' . $id . ']' . (htmlsafechars($row['username'])) . '[/url][/b] ' . $display . ' in Stealth Mode thanks to [b]' . $CURUSER['username'] . '[/b]');
    // header ouput
    $cache->set($cache_keys['display_stealth'] . $CURUSER['id'], $display, 5);
    header('Location: userdetails.php?id=' . $id);
    exit();
}

?>