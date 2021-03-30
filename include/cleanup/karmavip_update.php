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
    set_time_limit(1200);
    ignore_user_abort(1);
    //=== Updated remove karma vip by Bigjoos/pdq - change class number '1' in the users_buffer and $update[class'] to whatever is under your vip class number
    ($res = sql_query("SELECT id, modcomment FROM users WHERE vip_added='yes' AND vip_until < ".TIME_NOW."")) || sqlerr(__FILE__, __LINE__);
    $msgs_buffer = $users_buffer = [];
    if ($res->num_rows > 0) {
        $subject = "VIP status expired.";
        $msg = "Your VIP status has timed out and has been auto-removed by the system. Become a VIP again by donating to {$TRINITY20['site_name']} , or exchanging some Karma Bonus Points. Cheers !\n";
        while ($arr = $res->fetch_assoc()) {
            $modcomment = $arr['modcomment'];
            $modcomment = get_date(TIME_NOW, 'DATE', 1)." - Vip status Automatically Removed By System.\n".$modcomment;
            $modcom = sqlesc($modcomment);
            $msgs_buffer[] = '(0,'.$arr['id'].','.TIME_NOW.', '.sqlesc($msg).', '.sqlesc($subject).')';
            $users_buffer[] = '('.$arr['id'].',1, \'no\', \'0\' , '.$modcom.')';
            $cache->update_row('user'.$arr['id'], [
                'class' => 1,
                'vip_added' => 'no',
                'vip_until' => 0,
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row('user_stats'.$arr['id'], [
                'modcomment' => $modcomment,
            ], $TRINITY20['expires']['user_stats']);
            $cache->update_row($keys['my_userid'].$arr['id'], [
                'class' => 1,
                'vip_added' => 'no',
                'vip_until' => 0,
            ], $TRINITY20['expires']['curuser']);
            $cache->delete($keys['inbox_new'].$arr['id']);
            $cache->delete($keys['inbox_new_sb'].$arr['id']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES ".implode(', ', $msgs_buffer)) || sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO users (id, class, vip_added, vip_until, modcomment) VALUES ".implode(', ',
                    $users_buffer)." ON DUPLICATE key UPDATE class=values(class),vip_added=values(vip_added),vip_until=values(vip_until),modcomment=values(modcomment)") || sqlerr(__FILE__,
                __LINE__);
            write_log("Cleanup - Karma Vip status expired on - ".$count." Member(s)");
        }
        unset($users_buffer, $msgs_buffer, $count);
        status_change($arr['id']); //== For Retros announcement mod

    }
    //==
    if ($queries > 0) {
        write_log("Karma Vip Clean -------------------- Karma Vip cleanup Complete using $queries queries --------------------");
    }
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows." items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
