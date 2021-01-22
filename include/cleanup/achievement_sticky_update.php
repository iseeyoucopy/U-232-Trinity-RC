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
    // *Updated* Sticky Torrents Achievements Mod by MelvinMeow
    $res = sql_query("SELECT id, stickyup, stickyachiev FROM usersachiev WHERE stickyup >= '1'") or sqlerr(__FILE__, __LINE__);
    $msg_buffer = $usersachiev_buffer = $achievements_buffer = array();
    if ($res->num_row() > 0) {
        $dt = TIME_NOW;
        $subject = sqlesc("New Achievement Earned!");
        $points = rand(1, 3);
        while ($arr = $res->fetch_assoc()) {
            $stickyup = (int)$arr['stickyup'];
            $lvl = (int)$arr['stickyachiev'];
            if ($stickyup >= 1 && $lvl == 0) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Stick Em Up LVL1[/b] achievement. :) [img]".$TRINITY20['baseurl']."/pic/achievements/sticky1.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Stick Em Up LVL1\', \'sticky1.png\' , \'Uploading at least 1 sticky torrent to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',1, ' . $points . ')';
                $cache->delete('inbox_new::' . $arr['id']);
                $cache->delete('inbox_new_sb::' . $arr['id']);
                $cache->delete('user_achievement_points_' . $arr['id']);
                $var1 = 'stickyachiev';
            }
            if ($stickyup >= 5 && $lvl == 1) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Stick Em Up LVL2[/b] achievement. :) [img]".$TRINITY20['baseurl']."/pic/achievements/sticky2.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Stick Em Up LVL2\', \'sticky2.png\' , \'Uploading at least 5 sticky torrents to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',2, ' . $points . ')';
                $cache->delete('inbox_new::' . $arr['id']);
                $cache->delete('inbox_new_sb::' . $arr['id']);
                $cache->delete('user_achievement_points_' . $arr['id']);
                $var1 = 'stickyachiev';
            }
            if ($stickyup >= 10 && $lvl == 2) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Stick Em Up LVL3[/b] achievement. :) [img]".$TRINITY20['baseurl']."/pic/achievements/sticky3.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Stick Em Up LVL3\', \'sticky3.png\' , \'Uploading at least 10 sticky torrents to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',3, ' . $points . ')';
                $cache->delete('inbox_new::' . $arr['id']);
                $cache->delete('inbox_new_sb::' . $arr['id']);
                $cache->delete('user_achievement_points_' . $arr['id']);
                $var1 = 'stickyachiev';
            }
            if ($stickyup >= 25 && $lvl == 3) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Stick Em Up LVL4[/b] achievement. :) [img]".$TRINITY20['baseurl']."/pic/achievements/sticky4.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Stick Em Up LVL4\', \'sticky4.png\' , \'Uploading at least 25 sticky torrents to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',4, ' . $points . ')';
                $cache->delete('inbox_new::' . $arr['id']);
                $cache->delete('inbox_new_sb::' . $arr['id']);
                $cache->delete('user_achievement_points_' . $arr['id']);
                $var1 = 'stickyachiev';
            }
            if ($stickyup >= 50 && $lvl == 4) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Stick Em Up LVL5[/b] achievement. :) [img]".$TRINITY20['baseurl']."/pic/achievements/sticky5.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Stick Em Up LVL5\', \'sticky5.png\' , \'Uploading at least 50 sticky torrents to the site.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',5, ' . $points . ')';
                $cache->delete('inbox_new::' . $arr['id']);
                $cache->delete('inbox_new_sb::' . $arr['id']);
                $cache->delete('user_achievement_points_' . $arr['id']);
                $var1 = 'stickyachiev';
            }
        }
        $count = count($achievements_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES " . implode(', ', $msgs_buffer)) or sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO achievements (userid, date, achievement, icon, description) VALUES " . implode(', ', $achievements_buffer) . " ON DUPLICATE key UPDATE date=values(date),achievement=values(achievement),icon=values(icon),description=values(description)") or sqlerr(__FILE__, __LINE__);
            sql_query("INSERT INTO usersachiev (id, $var1, achpoints) VALUES " . implode(', ', $usersachiev_buffer) . " ON DUPLICATE key UPDATE $var1=values($var1), achpoints=achpoints+values(achpoints)") or sqlerr(__FILE__, __LINE__);
            if ($queries > 0) write_log("Achievements Cleanup: Achievements Stickied Completed using $queries queries. Stickied Achievements awarded to - " . $count . " Member(s)");
        }
        unset($usersachiev_buffer, $achievements_buffer, $msgs_buffer, $count);
    }
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
