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
    // ===Clear funds after one month
    $secs = 30 * 86400;
    $dt = sqlesc(TIME_NOW - $secs);
    sql_query("DELETE FROM funds WHERE added < $dt");
    //if ($mysqli->affected_rows() > 0)
    $cache->delete($cache_keys['totalfunds']);
    // ===End
    //== Donation Progress Mod Updated For Tbdev 2009/2010 by Bigjoos/pdq
    ($res = sql_query("SELECT id, modcomment, vipclass_before FROM users WHERE donor='yes' AND donoruntil < " . TIME_NOW . " AND donoruntil <> '0'")) || sqlerr(__FILE__,
        __LINE__);
    $msgs_buffer = $users_buffer = [];
    if ($res->num_rows > 0) {
        $subject = "Donor status removed by system.";
        $msg = "Your Donor status has timed out and has been auto-removed by the system, and your Vip status has been removed. We would like to thank you once again for your support to {$TRINITY20['site_name']}. If you wish to re-new your donation, Visit the site paypal link. Cheers!\n";
        while ($arr = $res->fetch_assoc()) {
            $modcomment = $arr['modcomment'];
            $modcomment = get_date(TIME_NOW, 'DATE', 1) . " - Donation status Automatically Removed By System.\n" . $modcomment;
            $modcom = sqlesc($modcomment);
            $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ',' . sqlesc($subject) . ')';
            $users_buffer[] = '(' . $arr['id'] . ',' . $arr['vipclass_before'] . ',\'no\',\'0\', ' . $modcom . ')';
            $update['class'] = ($arr['vipclass_before']);
            $cache->update_row($cache_keys['user'] . $arr['id'], [
                'class' => $update['class'],
                'donor' => 'no',
                'donoruntil' => 0,
            ], $TRINITY20['expires']['user_cache']);
            $cache->update_row($cache_keys['user_statss'] . $arr['id'], [
                'modcomment' => $modcomment,
            ], $TRINITY20['expires']['user_stats']);
            $cache->update_row($cache_keys['my_userid'] . $arr['id'], [
                'class' => $update['class'],
                'donor' => 'no',
                'donoruntil' => 0,
            ], $TRINITY20['expires']['curuser']);
            $cache->delete($cache_keys['inbox_new'] . $arr['id']);
            $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
        }
        $count = count($users_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES " . implode(', ', $msgs_buffer)) || sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO users (id, class, donor, donoruntil, modcomment) VALUES " . implode(', ', $users_buffer) . " ON DUPLICATE key UPDATE class=values(class),
            donor=values(donor),donoruntil=values(donoruntil),modcomment=values(modcomment)") || sqlerr(__FILE__, __LINE__);
            write_log("Cleanup: Donation status expired - " . $count . " Member(s)");
        }
        unset($users_buffer, $msgs_buffer, $update, $count);
    }
    //===End===//
    if ($queries > 0) {
        write_log("Delete Old Funds Clean -------------------- Delete Old Funds cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
