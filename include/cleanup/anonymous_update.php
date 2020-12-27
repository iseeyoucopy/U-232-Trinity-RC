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
    global $INSTALLER09, $queries, $cache;
    set_time_limit(0);
    ignore_user_abort(1);
    //=== Anonymous profile by Bigjoos/pdq:)
    $res = sql_query("SELECT id, modcomment FROM users WHERE anonymous_until < " . TIME_NOW . " AND anonymous_until <> '0'") or sqlerr(__FILE__, __LINE__);
    $msgs_buffer = $users_buffer = array();
    if (mysqli_num_rows($res) > 0) {
        $subject = "Anonymous profile expired.";
        $msg = "Your Anonymous profile has timed out and has been auto-removed by the system. If you would like to have it again, exchange some Karma Bonus Points again. Cheers!\n";
        while ($arr = mysqli_fetch_assoc($res)) {
            $modcomment = $arr['modcomment'];
            $modcomment = get_date(TIME_NOW, 'DATE', 1) . " - Anonymous profile Automatically Removed By System.\n" . $modcomment;
            $modcom = sqlesc($modcomment);
            $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ' )';
            $users_buffer[] = '(' . $arr['id'] . ', \'0\', \'no\', ' . $modcom . ')';
            $cache->begin_transaction('user' . $arr['id']);
            $cache->update_row(false, array(
                'anonymous_until' => 0,
                'anonymous' => 'no'
            ));
            $cache->commit_transaction($INSTALLER09['expires']['user_cache']);
            $cache->begin_transaction('user_stats_' . $arr['id']);
            $cache->update_row(false, array(
                'modcomment' => $modcomment
            ));
            $cache->commit_transaction($INSTALLER09['expires']['user_stats']);
            $cache->begin_transaction('MyUser_' . $arr['id']);
            $cache->update_row(false, array(
                'anonymous_until' => 0,
                'anonymous' => 'no'
            ));
            $cache->commit_transaction($INSTALLER09['expires']['curuser']);
            $cache->delete('inbox_new_' . $arr['id']);
            $cache->delete('inbox_new_sb_' . $arr['id']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES " . implode(', ', $msgs_buffer)) or sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO users (id, anonymous_until, anonymous, modcomment) VALUES " . implode(', ', $users_buffer) . " ON DUPLICATE key UPDATE anonymous_until=values(anonymous_until),anonymous=values(anonymous), modcomment=values(modcomment)") or sqlerr(__FILE__, __LINE__);
            write_log("Cleanup - Removed Anonymous profile from " . $count . " members");
        }
        unset($users_buffer, $msgs_buffer, $count);
    }
    //==End
    if ($queries > 0) write_log("Anonymous Profile Clean -------------------- Anonymous Profile cleanup Complete using $queries queries --------------------");
    if (false !== mysqli_affected_rows($GLOBALS["___mysqli_ston"])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS["___mysqli_ston"]) . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
