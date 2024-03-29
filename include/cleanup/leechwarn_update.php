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
    //== 09 Auto leech warn by Bigjoos/pdq
    //== Updated/modified autoleech warning script
    $minratio = 0.3; // ratio < 0.4
    $base_ratio = 0.0;
    $downloaded = 10 * 1024 * 1024 * 1024; // + 10 GB
    $length = 3 * 7; // Give 3 weeks to let them sort there shit
    ($res = sql_query("SELECT id, modcomment FROM users WHERE enabled='yes' AND class = " . UC_USER . " AND leechwarn = '0' AND uploaded / downloaded < $minratio AND uploaded / downloaded > $base_ratio AND downloaded >= $downloaded AND immunity = '0'")) || sqlerr(__FILE__,
        __LINE__);
    $msgs_buffer = $users_buffer = [];
    if ($res->num_rows > 0) {
        $dt = sqlesc(TIME_NOW);
        $subject = "Auto leech warned";
        $msg = "You have been warned and your download rights have been removed due to your low ratio. You need to get a ratio of 0.5 within the next 3 weeks or your Account will be disabled.";
        $leechwarn = TIME_NOW + ($length * 86400);
        while ($arr = $res->fetch_assoc()) {
            $modcomment = $arr['modcomment'];
            $modcomment = get_date(TIME_NOW, 'DATE', 1) . " - Automatically Leech warned and downloads disabled By System.\n" . $modcomment;
            $modcom = sqlesc($modcomment);
            $msgs_buffer[] = '(0,' . $arr['id'] . ', ' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
            $users_buffer[] = '(' . $arr['id'] . ',' . $leechwarn . ',\'0\', ' . $modcom . ')';
            $update['leechwarn'] = ($leechwarn);
            $cache->update_row($cache_keys['user'] . $arr['id'], [
                'leechwarn' => $update['leechwarn'],
                'downloadpos' => 0,
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row($cache_keys['my_userid'] . $arr['id'], [
                'leechwarn' => $update['leechwarn'],
                'downloadpos' => 0,
            ], $TRINITY20['expires']['curuser']);
            $cache->update_row($cache_keys['user_statss'] . $arr['id'], [
                'modcomment' => $modcomment,
            ], $TRINITY20['expires']['user_stats']);
            $cache->delete($cache_keys['inbox_new'] . $arr['id']);
            $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES " . implode(', ', $msgs_buffer)) || sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO users (id, leechwarn, downloadpos, modcomment) VALUES " . implode(', ',
                    $users_buffer) . " ON DUPLICATE key UPDATE leechwarn=values(leechwarn),downloadpos=values(downloadpos),modcomment=values(modcomment)") || sqlerr(__FILE__,
                __LINE__);
            write_log("Cleanup: System applied auto leech Warning(s) to  " . $count . " Member(s)");
        }
        unset($users_buffer, $msgs_buffer, $update, $count);
    }
    //End
    //== 09 Auto leech warn by Bigjoos/pdq
    //== Updated/Modified autoleech warn system - Remove warning and enable downloads
    $minratio = 0.5; // ratio > 0.5
    ($res = sql_query("SELECT id, modcomment FROM users WHERE downloadpos = '0' AND leechwarn > '1' AND uploaded / downloaded >= $minratio")) || sqlerr(__FILE__,
        __LINE__);
    $msgs_buffer = $users_buffer = [];
    if ($res->num_rows > 0) {
        $subject = "Auto leech warning removed";
        $msg = "Your warning for a low ratio has been removed and your downloads enabled. We highly recommend you to keep your ratio positive to avoid being automatically warned again.\n";
        while ($arr = $res->fetch_assoc()) {
            $modcomment = $arr['modcomment'];
            $modcomment = get_date(TIME_NOW, 'DATE', 1) . " - Leech warn removed and download enabled By System.\n" . $modcomment;
            $modcom = sqlesc($modcomment);
            $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ',  ' . sqlesc($subject) . ')';
            $users_buffer[] = '(' . $arr['id'] . ', \'0\', \'1\', ' . $modcom . ')';
            $cache->update_row($cache_keys['user'] . $arr['id'], [
                'leechwarn' => 0,
                'downloadpos' => 1,
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row($cache_keys['my_userid'] . $arr['id'], [
                'leechwarn' => 0,
                'downloadpos' => 1,
            ], $TRINITY20['expires']['curuser']);
            $cache->update_row($cache_keys['user_statss'] . $arr['id'], [
                'modcomment' => $modcomment,
            ], $TRINITY20['expires']['user_stats']);
            $cache->delete($cache_keys['inbox_new'] . $arr['id']);
            $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES " . implode(', ', $msgs_buffer)) || sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO users (id, leechwarn, downloadpos, modcomment) VALUES " . implode(', ',
                    $users_buffer) . " ON DUPLICATE key UPDATE leechwarn=values(leechwarn),downloadpos=values(downloadpos),modcomment=values(modcomment)") || sqlerr(__FILE__,
                __LINE__);
            write_log("Cleanup: System removed auto leech Warning(s) and renabled download(s) - " . $count . " Member(s)");
        }
        unset($users_buffer, $msgs_buffer, $count);
    }
    //==End
    //== 09 Auto leech warn by Bigjoos/pdq
    //== Disabled expired leechwarns
    ($res = sql_query("SELECT id, modcomment FROM users WHERE leechwarn > '1' AND leechwarn < " . TIME_NOW . " AND leechwarn <> '0' ")) || sqlerr(__FILE__,
        __LINE__);
    $users_buffer = [];
    if ($res->num_rows > 0) {
        while ($arr = $res->fetch_assoc()) {
            $modcomment = $arr['modcomment'];
            $modcomment = get_date(TIME_NOW, 'DATE', 1) . " - User disabled - Low ratio.\n" . $modcomment;
            $modcom = sqlesc($modcomment);
            $users_buffer[] = '(' . $arr['id'] . ' , \'0\', \'no\', ' . $modcom . ')';
            $cache->update_row($cache_keys['user'] . $arr['id'], [
                'leechwarn' => 0,
                'enabled' => 'no',
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row($cache_keys['user_statss'] . $arr['id'], [
                'modcomment' => $modcomment,
            ], $TRINITY20['expires']['user_stats']);
            $cache->update_row($cache_keys['my_userid'] . $arr['id'], [
                'leechwarn' => 0,
                'enabled' => 'no',
            ], $TRINITY20['expires']['curuser']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO users (id, leechwarn, enabled, modcomment) VALUES " . implode(', ',
                    $users_buffer) . " ON DUPLICATE key UPDATE class=values(class),leechwarn=values(leechwarn),enabled=values(enabled),modcomment=values(modcomment)") || sqlerr(__FILE__,
                __LINE__);
            write_log("Cleanup: Disabled " . $count . " Member(s) - Leechwarns expired");
        }
        unset($users_buffer, $count);
    }
    //==End
    if ($queries > 0) {
        write_log("Leechwarn Clean -------------------- Leechwarn cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
