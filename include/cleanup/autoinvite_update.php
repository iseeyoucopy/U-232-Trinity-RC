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
    global $TRINITY20, $queries, $cache, $mysqli, $cache_keys;
    set_time_limit(1200);
    ignore_user_abort(1);
    //== 09 Auto invite by Bigjoos/pdq
    $ratiocheck = 1.0;
    $joined = (TIME_NOW - 86400 * 90);
    ($res = sql_query("SELECT id, uploaded, invites, downloaded, modcomment FROM users WHERE invites='1' AND class = " . UC_USER . " AND uploaded / downloaded <= $ratiocheck AND enabled='yes' AND added < $joined")) || sqlerr(__FILE__,
        __LINE__);
    $msgs_buffer = $users_buffer = [];
    if ($res->num_rows > 0) {
        $subject = "Auto Invites";
        $msg = "Congratulations, your user group met a set out criteria therefore you have been awarded 2 invites  :)\n Please use them carefully. Cheers " . $TRINITY20['site_name'] . " staff.\n";
        while ($arr = $res->fetch_assoc()) {
            $ratio = number_format($arr['uploaded'] / $arr['downloaded'], 3);
            $modcomment = $arr['modcomment'];
            $modcomment = get_date(TIME_NOW, 'DATE',
                    1) . " - Awarded 2 bonus invites by System (UL=" . mksize($arr['uploaded']) . ", DL=" . mksize($arr['downloaded']) . ", R=" . $ratio . ") .\n" . $modcomment;
            $modcom = sqlesc($modcomment);
            $msgs_buffer[] = '(0,' . $arr['id'] . ', ' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
            $users_buffer[] = '(' . $arr['id'] . ', 2, ' . $modcom . ')'; //== 2 in the user_buffer is award amount :)
            $update['invites'] = ($arr['invites'] + 2); //== 2 in the user_buffer is award amount :)
            $cache->update_row($cache_keys['user'] . $arr['id'], [
                'invites' => $update['invites'],
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row($cache_keys['user_statss'] . $arr['id'], [
                'modcomment' => $modcomment,
            ], $TRINITY20['expires']['user_stats']);
            $cache->update_row($cache_keys['my_userid'] . $arr['id'], [
                'invites' => $update['invites'],
            ], $TRINITY20['expires']['curuser']);
            $cache->delete($cache_keys['inbox_new'] . $arr['id']);
            $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES " . implode(', ', $msgs_buffer)) || sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO users (id, invites, modcomment) VALUES " . implode(', ',
                    $users_buffer) . " ON DUPLICATE key UPDATE invites = invites+values(invites), modcomment=values(modcomment)") || sqlerr(__FILE__,
                __LINE__);
            write_log("Cleanup: Awarded 2 bonus invites to " . $count . " member(s) ");
        }
        unset($users_buffer, $msgs_buffer, $update, $count);
    }
    //==
    if ($queries > 0) {
        write_log("Auto Invites -------------------- Auto Cleanups cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
