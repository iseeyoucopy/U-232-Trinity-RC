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
    // *Updated* Forum Topic Achievements Mod by MelvinMeow
    ($res = sql_query("SELECT id, forumtopics, topicachiev FROM usersachiev WHERE forumtopics >= '1'")) || sqlerr(__FILE__, __LINE__);
    $msg_buffer = $usersachiev_buffer = $achievements_buffer = [];
    if ($res->num_rows > 0) {
        $dt = TIME_NOW;
        $subject = sqlesc("New Achievement Earned!");
        $points = rand(1, 3);
        while ($arr = $res->fetch_assoc()) {
            $topics = (int)$arr['forumtopics'];
            $lvl = (int)$arr['topicachiev'];
            if ($topics >= 1 && $lvl == 0) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Topic Starter Level 1[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/ftopic1.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Topic Starter LVL1\', \'ftopic1.png\' , \'Started at least 1 topic in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',1, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'topicachiev';
            }
            if ($topics >= 10 && $lvl == 1) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Topic Starter Level 2[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/ftopic2.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Topic Starter LVL2\', \'ftopic2.png\' , \'Started at least 10 topics in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',2, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'topicachiev';
            }
            if ($topics >= 25 && $lvl == 2) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Topic Starter Level 3[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/ftopic3.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Topic Starter LVL3\', \'ftopic3.png\' , \'Started at least 25 topics in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',3, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'topicachiev';
            }
            if ($topics >= 50 && $lvl == 3) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Topic Starter Level 4[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/ftopic4.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Topic Starter LVL4\', \'ftopic4.png\' , \'Started at least 50 topics in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',4, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'topicachiev';
            }
            if ($topics >= 75 && $lvl == 4) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Topic Starter Level 5[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/ftopic5.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Topic Starter LVL5\', \'ftopic5.png\' , \'Started at least 75 topics in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',5, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'topicachiev';
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
                write_log("Achievements Cleanup: Achievements Forum Topics Completed using $queries queries. Forum Topics Achievements awarded to - " . $count . " Member(s)");
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
