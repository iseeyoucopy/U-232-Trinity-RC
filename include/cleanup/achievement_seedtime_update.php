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
    set_time_limit(0);
    ignore_user_abort(1);
    // *Updated* Seedtime Achievements Mod by MelvinMeow
    $seedtime = 604800; // 7days
    $seedtime2 = 1_209_600; // 14days
    $seedtime3 = 1_814_400; // 21days
    $seedtime4 = 2_419_200; // 28days
    $seedtime5 = 3_888_000; // 45days
    $seedtime6 = 5_184_000; // 60days
    $seedtime7 = 7_776_000; // 90days
    $seedtime8 = 10_368_000; // 120days
    $seedtime9 = 12_960_000; // 200days
    $seedtime10 = 31_536_000; //1year
    ($res = sql_query("SELECT distinct snatched.userid, snatched.seedtime, usersachiev.dayseed FROM snatched LEFT JOIN usersachiev ON snatched.userid = usersachiev.id WHERE seedtime >= " . sqlesc($seedtime))) || sqlerr(__FILE__,
        __LINE__);
    $msg_buffer = $usersachiev_buffer = $achievements_buffer = [];
    if ($res->num_rows > 0) {
        $dt = TIME_NOW;
        $subject = sqlesc("New Achievement Earned!");
        $points = rand(1, 3);
        while ($arr = $res->fetch_assoc()) {
            $timeseeded = (int)$arr['seedtime'];
            $dayseed = (int)$arr['dayseed'];
            $arr['userid'] = (int)$arr['userid'];
            if ($dayseed == 0 && $timeseeded >= $seedtime) {
                $msg = sqlesc("Congratulations, you have just earned the [b]7 Day Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/7dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'7 Day Seeder\', \'7dayseed.png\' , \'Seeded a snatched torrent for a total of at least 7 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',7, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
            if ($dayseed == 7 && $timeseeded >= $seedtime2) {
                $msg = sqlesc("Congratulations, you have just earned the [b]14 Day Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/14dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'14 Day Seeder\', \'14dayseed.png\' , \'Seeded a snatched torrent for a total of at least 14 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',14, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
            if ($dayseed == 14 && $timeseeded >= $seedtime3) {
                $msg = sqlesc("Congratulations, you have just earned the [b]21 Day Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/21dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'21 Day Seeder\', \'21dayseed.png\' , \'Seeded a snatched torrent for a total of at least 21 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',21, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
            if ($dayseed == 21 && $timeseeded >= $seedtime4) {
                $msg = sqlesc("Congratulations, you have just earned the [b]28 Day Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/28dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'28 Day Seeder\', \'28dayseed.png\' , \'Seeded a snatched torrent for a total of at least 28 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',28, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
            if ($dayseed == 28 && $timeseeded >= $seedtime5) {
                $msg = sqlesc("Congratulations, you have just earned the [b]45 Day Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/45dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'45 Day Seeder\', \'45dayseed.png\' , \'Seeded a snatched torrent for a total of at least 45 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',45, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
            if ($dayseed == 45 && $timeseeded >= $seedtime6) {
                $msg = sqlesc("Congratulations, you have just earned the [b]60 Day Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/60dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'60 Day Seeder\', \'60dayseed.png\' , \'Seeded a snatched torrent for a total of at least 60 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',60, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
            if ($dayseed == 60 && $timeseeded >= $seedtime7) {
                $msg = sqlesc("Congratulations, you have just earned the [b]90 Day Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/90dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'90 Day Seeder\', \'90dayseed.png\' , \'Seeded a snatched torrent for a total of at least 90 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',90, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
            if ($dayseed == 90 && $timeseeded >= $seedtime8) {
                $msg = sqlesc("Congratulations, you have just earned the [b]120 Day Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/120dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'120 Day Seeder\', \'120dayseed.png\' , \'Seeded a snatched torrent for a total of at least 120 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',120, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
            if ($dayseed == 120 && $timeseeded >= $seedtime9) {
                $msg = sqlesc("Congratulations, you have just earned the [b]200 Day Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/200dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'200 Day Seeder\', \'200dayseed.png\' , \'Seeded a snatched torrent for a total of at least 200 days.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',200, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
            if ($dayseed == 200 && $timeseeded >= $seedtime10) {
                $msg = sqlesc("Congratulations, you have just earned the [b]1 Year Seeder[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/365dayseed.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['userid'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['userid'] . ', ' . TIME_NOW . ', \'365 Day Seeder\', \'365dayseed.png\' , \'Seeded a snatched torrent for a total of at least 1 Year.\')';
                $usersachiev_buffer[] = '(' . $arr['userid'] . ',365, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['userid']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['userid']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['userid']);
                $var1 = 'dayseed';
            }
        }
        $count = count($achievements_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES " . implode(', ', $msgs_buffer)) || sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO achievements (userid, date, achievement, icon, description) VALUES " . implode(', ',
                    $achievements_buffer) . " ON DUPLICATE key UPDATE date=values(date),achievement=values(achievement),icon=values(icon),description=values(description)") || sqlerr(__FILE__,
                __LINE__);
            sql_query("INSERT INTO usersachiev (id, $var1, achpoints) VALUES " . implode(', ',
                    $usersachiev_buffer) . " ON DUPLICATE key UPDATE $var1=values($var1), achpoints=achpoints+values(achpoints)") || sqlerr(__FILE__,
                __LINE__);
            if ($queries > 0) {
                write_log("Achievements Cleanup: Achievements Seedtime Completed using $queries queries. Seedtime Achievements awarded to - " . $count . " Member(s)");
            }
        }
        unset($usersachiev_buffer, $achievements_buffer, $msgs_buffer, $count);
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
