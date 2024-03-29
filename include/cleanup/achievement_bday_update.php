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
    // *Updated* Birthday Achievements Mod by MelvinMeow
    $maxdt = (TIME_NOW - 86400 * 365); // 1year
    $maxdt2 = (TIME_NOW - 86400 * 730); // 2 years
    $maxdt3 = (TIME_NOW - 86400 * 1095); // 3 years
    $maxdt4 = (TIME_NOW - 86400 * 1460); // 4 years
    $maxdt5 = (TIME_NOW - 86400 * 1825); // 5 years
    $maxdt6 = (TIME_NOW - 86400 * 2190); // 6 years
    ($res = sql_query("SELECT users.id, users.added, usersachiev.bday FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE enabled = 'yes' AND added < $maxdt")) || sqlerr(__FILE__,
        __LINE__);
    $msg_buffer = $usersachiev_buffer = $achievements_buffer = [];
    if ($res->num_rows > 0) {
        $dt = TIME_NOW;
        $subject = sqlesc("New Achievement Earned!");
        $points = rand(1, 3);
        while ($arr = $res->fetch_assoc()) {
            $bday = (int)$arr['bday'];
            $added = (int)$arr['added'];
            if ($bday == 0 && $added < $maxdt) {
                $msg = sqlesc("Congratulations, you have just earned the [b]First Birthday[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/birthday1.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'First Birthday\', \'birthday1.png\' , \'Been a member for at least 1 year.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',1, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bday';
            }
            if ($bday == 1 && $added < $maxdt2) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Second Birthday[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/birthday2.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Second Birthday\', \'birthday2.png\' , \'Been a member for a period of at least 2 years.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',2, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'bday';
            }
            if ($bday == 2 && $added < $maxdt3) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Third Birthday[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/birthday3.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Third Birthday\', \'birthday3.png\' , \'Been a member for a period of at least 3 years.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',3, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'bday';
            }
            if ($bday == 3 && $added < $maxdt4) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Fourth Birthday[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/birthday4.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Fourth Birthday\', \'birthday4.png\' , \'Been a member for a period of at least 4 years.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',4, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'bday';
            }
            if ($bday == 4 && $added < $maxdt5) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Fifth Birthday[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/birthday5.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Fifth Birthday\', \'birthday5.png\' , \'Been a member for a period of at least 5 years.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',5, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'bday';
            }
            if ($bday == 5 && $added < $maxdt6) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Sixth Birthday[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/birthday6.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Sixth Birthday\', \'birthday6.png\' , \'Been a member for a period of at least 6 years.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',6, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'bday';
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
                write_log("Achievements Cleanup: Achievements Birthdays Completed using $queries queries. Birthday Achievements awarded to - " . $count . " Member(s)");
            }
        }
        unset($usersachiev_buffer, $achievement_buffer, $msgs_buffer, $count);
    }
    if ($mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
