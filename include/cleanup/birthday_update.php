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
    //== Pm birthday users
    $current_date = getdate();
    $res = sql_query("SELECT id, username, class, donor, title, warned, enabled, chatpost, leechwarn, pirate, king, uploaded, birthday FROM users WHERE MONTH(birthday) = " . sqlesc($current_date['mon']) . " AND DAYOFMONTH(birthday) = " . sqlesc($current_date['mday']) . " ORDER BY username ASC") or sqlerr(__FILE__, __LINE__);
    $msgs_buffer = $users_buffer = array();
    if ($res->num_rows() > 0) {
        while ($arr = $res->fetch_assoc()) {
            $msg = "Hey there  " . htmlsafechars($arr['username']) . " happy birthday, hope you have a good day we awarded you 10 gig...Njoi.\n";
            $subject = "Its your birthday!!";
            $msgs_buffer[] = '(0,' . $arr['id'] . ', ' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
            $users_buffer[] = '(' . $arr['id'] . ', 10737418240)';
            $update['uploaded'] = ($arr['uploaded'] + 10737418240);
            $cache->update_row($keys['user_stats'] . $arr['id'], [
                'uploaded' => $update['uploaded']
            ], $TRINITY20['expires']['u_stats']);
            $cache->update_row('user_stats_' . $arr['id'], [
                'uploaded' => $update['uploaded']
            ], $TRINITY20['expires']['user_stats']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES " . implode(', ', $msgs_buffer)) or sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO users (id, uploaded) VALUES " . implode(', ', $users_buffer) . " ON DUPLICATE key UPDATE uploaded=uploaded+values(uploaded)") or sqlerr(__FILE__, __LINE__);
            write_log("Cleanup: Pm'd' " . $count . " member(s) and awarded a birthday prize");
        }
        unset($users_buffer, $msgs_buffer, $count);
    }
    //==End
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
