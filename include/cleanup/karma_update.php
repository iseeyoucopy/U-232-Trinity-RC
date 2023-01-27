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
function docleanup($data)
{
    global $TRINITY20, $queries, $cache, $mysqli, $keys;
    set_time_limit(0);
    ignore_user_abort(1);
    //=== Update karma seeding bonus... made nicer by devinkray :D
    //==   Updated and optimized by pdq :)
    //=== Using this option will work for multiple torrents UP TO 5!... change the 5 to whatever... 1 to give the karma for only 1 torrent at a time, or 100 to make it unlimited (almost) your choice :P /*if ($arr['tcount'] >= 5) $arr['tcount'] = 1;*/
    ///====== Seeding bonus per torrent
    if ($TRINITY20['seedbonus_on'] == 1) {
        $What_id = (XBT_TRACKER == true ? 'tid' : 'torrent');
        $What_user_id = (XBT_TRACKER == true ? 'uid' : 'userid');
        $What_Table = (XBT_TRACKER == true ? 'xbt_peers' : 'peers');
        $What_Where = (XBT_TRACKER == true ? "`left` = 0 AND `active` = 1" : "seeder = 'yes' AND connectable = 'yes'");
        ($res = sql_query('SELECT COUNT('.$What_id.') As tcount, '.$What_user_id.', seedbonus, users.id As users_id FROM '.$What_Table.' LEFT JOIN users ON users.id = '.$What_user_id.' WHERE '.$What_Where.' GROUP BY '.$What_user_id)) || sqlerr(__FILE__,
            __LINE__);
        if ($res->num_rows > 0) {
            while ($arr = $res->fetch_assoc()) {
                /*if ($arr['tcount'] >= 5) $arr['tcount'] = 1;*/
                $Buffer_User = (XBT_TRACKER == true ? $arr['uid'] : $arr['userid']);
                if ($arr['users_id'] == $Buffer_User && $arr['users_id'] != null) {
                    $users_buffer[] = '('.$Buffer_User.', '.$TRINITY20['bonus_per_duration'].' * '.$arr['tcount'].')';
                    $update['seedbonus'] = ($arr['seedbonus'] + $TRINITY20['bonus_per_duration'] * $arr['tcount']);
                    $cache->update_row($keys['user_stats'].$Buffer_User, [
                        'seedbonus' => $update['seedbonus'],
                    ], $TRINITY20['expires']['u_stats']);
                    $cache->update_row($keys['user_stats_'].$Buffer_User, [
                        'seedbonus' => $update['seedbonus'],
                    ], $TRINITY20['expires']['user_stats']);
                }
            }
            $count = count($users_buffer);
            if ($count > 0) {
                sql_query("INSERT INTO users (id,seedbonus) VALUES ".implode(', ',
                        $users_buffer)." ON DUPLICATE key UPDATE seedbonus=seedbonus+values(seedbonus)") || sqlerr(__FILE__, __LINE__);
                write_log("Cleanup - ".$count." users received seedbonus");
            }
            unset($users_buffer, $update, $count);
        }
    }
    //== End
    if ($queries > 0) {
        write_log("Karma clean-------------------- Karma cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows." items updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
