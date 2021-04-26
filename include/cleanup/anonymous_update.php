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
    //=== Anonymous profile by Bigjoos/pdq:)
    ($res = sql_query("SELECT id, modcomment FROM users WHERE anonymous_until < ".TIME_NOW." AND anonymous_until <> '0'")) || sqlerr(__FILE__,
        __LINE__);
    $msgs_buffer = $users_buffer = [];
    if ($res->num_rows > 0) {
        $subject = "Anonymous profile expired.";
        $msg = "Your Anonymous profile has timed out and has been auto-removed by the system. If you would like to have it again, exchange some Karma Bonus Points again. Cheers!\n";
        while ($arr = $res->fetch_assoc()) {
            $modcomment = $arr['modcomment'];
            $modcomment = get_date(TIME_NOW, 'DATE', 1)." - Anonymous profile Automatically Removed By System.\n".$modcomment;
            $modcom = sqlesc($modcomment);
            $msgs_buffer[] = '(0,'.$arr['id'].','.TIME_NOW.', '.sqlesc($msg).', '.sqlesc($subject).' )';
            $users_buffer[] = '('.$arr['id'].', \'0\', \'no\', '.$modcom.')';
            $cache->update_row($keys['user'].$arr['id'], [
                'anonymous_until' => 0,
                'anonymous' => 'no',
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row('user_stats_'.$arr['id'], [
                'modcomment' => $modcomment,
            ], $TRINITY20['expires']['user_stats']);
            $cache->update_row($keys['my_userid'].$arr['id'], [
                'anonymous_until' => 0,
                'anonymous' => 'no',
            ], $TRINITY20['expires']['curuser']);
            $cache->delete($keys['inbox_new'].$arr['id']);
            $cache->delete($keys['inbox_new_sb'].$arr['id']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES ".implode(', ', $msgs_buffer)) || sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO users (id, anonymous_until, anonymous, modcomment) VALUES ".implode(', ',
                    $users_buffer)." ON DUPLICATE key UPDATE anonymous_until=values(anonymous_until),anonymous=values(anonymous), modcomment=values(modcomment)") || sqlerr(__FILE__,
                __LINE__);
            write_log("Cleanup - Removed Anonymous profile from ".$count." members");
        }
        unset($users_buffer, $msgs_buffer, $count);
    }
    //==End
    if ($queries > 0) {
        write_log("Anonymous Profile Clean -------------------- Anonymous Profile cleanup Complete using $queries queries --------------------");
    }
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows." items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
