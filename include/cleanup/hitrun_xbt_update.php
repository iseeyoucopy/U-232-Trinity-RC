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
    include(CACHE_DIR . 'hit_and_run_settings.php');
    if ($TRINITY20['hnr_online'] == 1) {
        //===09 hnr by sir_snugglebunny
        $secs = 43200;
        ($res = sql_query('SELECT tid, uid FROM xbt_peers WHERE seedtime = \'0\' OR seedtime < ' . sqlesc($secs) . ' AND completed >=\'1\' AND downloaded >\'0\'')) || sqlerr(__FILE__,
            __LINE__);
        while ($arr = $res->fetch_assoc()) {
            sql_query('UPDATE xbt_peers SET mark_of_cain = \'yes\', hit_and_run = ' . TIME_NOW . ' WHERE  tid=' . sqlesc($arr['tid']) . ' AND uid=' . sqlesc($arr['uid'])) || sqlerr(__FILE__,
                __LINE__);
        }
        //=== hit and run... disable Downloading rights if they have x marks of cain
        ($res_fuckers = sql_query('SELECT COUNT(*) AS poop, xbt_peers.uid, users.username, users.modcomment, users.hit_and_run_total, users.downloadpos, users.can_leech FROM xbt_peers LEFT JOIN users ON xbt_peers.uid = users.id WHERE xbt_peers.mark_of_cain = \'yes\' AND users.hnrwarn = \'no\' AND users.immunity = \'0\' GROUP BY xbt_peers.uid')) || sqlerr(__FILE__,
            __LINE__);
        while ($arr_fuckers = $res_fuckers->fetch_assoc()) {
            if ($arr_fuckers['poop'] > $TRINITY20['cainallowed'] && $arr_fuckers['downloadpos'] == 1) {
                //=== set them to no DLs
                $subject = sqlesc('Download disabled by System');
                $msg = sqlesc("Sorry " . htmlsafechars($arr_fuckers['username']) . ",\n Because you have " . $TRINITY20['cainallowed'] . " or more torrents that have not been seeded to either a 1:1 ratio, or for the expected seeding time, your downloading rights have been disabled by the Auto system !\nTo get your Downloading rights back is simple,\n just start seeding the torrents in your profile [ click your username, then click your [url=" . $TRINITY20['baseurl'] . "/userdetails.php?id=" . (int)$arr_fuckers['uid'] . "&completed=1]Completed Torrents[/url] link to see what needs seeding ] and your downloading rights will be turned back on by the Auto system after the next clean-time [ updates 4 times per hour ].\n\nDownloads are disabled after a member has three or more torrents that have not been seeded to either a 1 to 1 ratio, OR for the required seed time [ please see the [url=" . $TRINITY20['baseurl'] . "/faq.php]FAQ[/url] or [url=" . $TRINITY20['baseurl'] . "/rules.php]Site Rules[/url] for more info ]\n\nIf this message has been in error, or you feel there is a good reason for it, please feel free to PM a staff member with your concerns.\n\n we will do our best to fix this situation.\n\nBest of luck!\n " . $TRINITY20['site_name'] . " staff.\n");
                $modcomment = $arr_fuckers['modcomment'];
                $modcomment = get_date(TIME_NOW, 'DATE', 1) . " - Download rights removed for H and R - AutoSystem.\n" . $modcomment;
                $modcom = sqlesc($modcomment);
                $_pms = (array)$_pms;
                $_pms[] = '(0,' . sqlesc($arr_fuckers['uid']) . ',' . sqlesc(TIME_NOW) . ',' . $msg . ',' . $subject . ',0)';
                $_users = (array)$_users;
                $_users[] = '(' . sqlesc($arr_fuckers['uid']) . ',' . sqlesc($arr_fuckers['poop']) . ',0, \'yes\',0,' . $modcom . ')';
                if (count($_pms) > 0) {
                    sql_query("INSERT INTO messages (sender, receiver, added, msg, subject, poster) VALUES " . implode(',', $_pms)) || sqlerr(__FILE__,
                        __LINE__);
                }
                if (count($_users) > 0) {
                    sql_query("INSERT INTO users(id,hit_and_run_total,downloadpos,hnrwarn,can_leech,modcomment) VALUES " . implode(',',
                            $_users) . " ON DUPLICATE key UPDATE hit_and_run_total=hit_and_run_total+values(hit_and_run_total),downloadpos=values(downloadpos),hnrwarn=values(hnrwarn),can_leech=values(can_leech),modcomment=values(modcomment)") || sqlerr(__FILE__,
                        __LINE__);
                }
                unset($_pms, $_users);
                $update['hit_and_run_total'] = ($arr_fuckers['hit_and_run_total'] + $arr_fuckers['poop']);
                $cache->update_row($cache_keys['user'] . $arr_fuckers['uid'], [
                    'hit_and_run_total' => $update['hit_and_run_total'],
                    'downloadpos' => 0,
                    'can_leech' => 0,
                    'hnrwarn' => 'yes',
                ], $TRINITY20['expires']['user_cache']);
                $cache->update_row($cache_keys['userstats_xbt'] . $arr_fuckers['uid'], [
                    'modcomment' => $modcomment,
                ], $TRINITY20['expires']['u_stats_xbt']);
                $cache->update_row($cache_keys['user_stats_xbt'] . $arr_fuckers['uid'], [
                    'modcomment' => $modcomment,
                ], $TRINITY20['expires']['user_stats_xbt']);
                $cache->update_row($cache_keys['my_userid'] . $arr_fuckers['uid'], [
                    'hit_and_run_total' => $update['hit_and_run_total'],
                    'downloadpos' => 0,
                    'can_leech' => 0,
                    'hnrwarn' => 'yes',
                ], $TRINITY20['expires']['curuser']);
                $cache->delete($cache_keys['inbox_new'] . $arr_fuckers['uid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr_fuckers['uid']);
            }
        }
        //=== hit and run... turn their DLs back on if they start seeding again
        ($res_good_boy = sql_query('SELECT id, username, modcomment FROM users WHERE hnrwarn = \'yes\' AND downloadpos = \'0\' AND can_leech=\'0\'')) || sqlerr(__FILE__,
            __LINE__);
        while ($arr_good_boy = $res_good_boy->fetch_assoc()) {
            ($res_count = sql_query('SELECT COUNT(*) FROM xbt_peers WHERE seedtime >= ' . sqlesc($secs) . ' AND uid = ' . sqlesc($arr_good_boy['id']) . ' AND mark_of_cain = \'yes\' AND active=\'1\'')) || sqlerr(__FILE__,
                __LINE__);
            $arr_count = $res_count->fetch_row();
            if ($arr_count[0] < $TRINITY20['cainallowed']) {
                //=== set them to yes DLs
                $subject = sqlesc('Download restored by System');
                $msg = sqlesc("Hi " . htmlsafechars($arr_good_boy['username']) . ",\n Congratulations ! Because you have seeded the torrents that needed seeding, your downloading rights have been restored by the Auto System !\n\nhave fun !\n " . $TRINITY20['site_name'] . " staff.\n");
                $modcomment = $arr_good_boy['modcomment'];
                $modcomment = get_date(TIME_NOW, 'DATE', 1) . " - Download rights restored from H and R - AutoSystem.\n" . $modcomment;
                $modcom = sqlesc($modcomment);
                $_pms = (array)$_pms;
                $_pms[] = '(0,' . sqlesc($arr_good_boy['id']) . ',' . sqlesc(TIME_NOW) . ',' . $msg . ',' . $subject . ',0)';
                $_users = (array)$_users;
                $_users[] = '(' . sqlesc($arr_good_boy['id']) . ',1,\'no\',1,' . $modcom . ')';
                if (count($_pms) > 0) {
                    sql_query("INSERT INTO messages (sender, receiver, added, msg, subject, poster) VALUES " . implode(',', $_pms)) || sqlerr(__FILE__,
                        __LINE__);
                }
                if (count($_users) > 0) {
                    sql_query("INSERT INTO users(id,downloadpos,hnrwarn,can_leech,modcomment) VALUES " . implode(',',
                            $_users) . " ON DUPLICATE key UPDATE downloadpos=values(downloadpos),hnrwarn=values(hnrwarn),can_leech=values(can_leech),modcomment=values(modcomment)") || sqlerr(__FILE__,
                        __LINE__);
                }
                sql_query('UPDATE xbt_peers SET mark_of_cain = \'no\', hit_and_run = \'0\' WHERE uid=' . sqlesc($arr_good_boy['id'])) || sqlerr(__FILE__,
                    __LINE__);
                //tid='.sqlesc($arr['tid']).' AND
                unset($_pms, $_users);
                $cache->update_row($cache_keys['user'] . $arr_good_boy['id'], [
                    'downloadpos' => 1,
                    'can_leech' => 1,
                    'hnrwarn' => 'no',
                ], $TRINITY20['expires']['user_cache']);
                $cache->update_row($cache_keys['user_stats_xbt'] . $arr_good_boy['id'], [
                    'modcomment' => $modcomment,
                ], $TRINITY20['expires']['user_stats_xbt']);
                $cache->update_row($cache_keys['userstats_xbt'] . $arr_good_boy['id'], [
                    'modcomment' => $modcomment,
                ], $TRINITY20['expires']['u_stats_xbt']);
                $cache->update_row($cache_keys['my_userid'] . $arr_good_boy['id'], [
                    'downloadpos' => 1,
                    'can_leech' => 1,
                    'hnrwarn' => 'no',
                ], $TRINITY20['expires']['curuser']);
                $cache->delete($cache_keys['inbox_new'] . $arr_good_boy['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr_good_boy['id']);
            }
        }
        //==End
        if ($queries > 0) {
            write_log("XBT Hit And Run Clean -------------------- XBT Hit And Run Complete using $queries queries--------------------");
        }
        if (false !== $mysqli->affected_rows) {
            $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
        }
        if ($data['clean_log']) {
            cleanup_log($data);
        }
    }
}

?>
