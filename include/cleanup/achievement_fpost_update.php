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
    // *Updated* Forum Post Achievements Mod by MelvinMeow
    ($res = sql_query("SELECT id, forumposts, postachiev FROM usersachiev WHERE forumposts >= '1'")) || sqlerr(__FILE__, __LINE__);
    $msg_buffer = $usersachiev_buffer = $achievements_buffer = [];
    if ($res->num_rows > 0) {
        $dt = TIME_NOW;
        $subject = sqlesc("New Achievement Earned!");
        $points = rand(1, 3);
        while ($arr = $res->fetch_assoc()) {
            $posts = (int)$arr['forumposts'];
            $lvl = (int)$arr['postachiev'];
            if ($posts >= 1 && $lvl == 0) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Poster Level 1[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/fpost1.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Poster LVL1\', \'fpost1.png\' , \'Made at least 1 post in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',1, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'postachiev';
            }
            if ($posts >= 25 && $lvl == 1) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Poster Level 2[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/fpost2.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Poster LVL2\', \'fpost2.png\' , \'Made at least 25 posts in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',2, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'postachiev';
            }
            if ($posts >= 50 && $lvl == 2) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Poster Level 3[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/fpost3.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Poster LVL3\', \'fpost3.png\' , \'Made at least 50 posts in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',3, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'postachiev';
            }
            if ($posts >= 100 && $lvl == 3) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Poster Level 4[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/fpost4.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Poster LVL4\', \'fpost4.png\' , \'Made at least 100 posts in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',4, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'postachiev';
            }
            if ($posts >= 250 && $lvl == 4) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Poster Level 5[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/fpost5.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Poster LVL5\', \'fpost5.png\' , \'Made at least 250 posts in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',5, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'postachiev';
            }
            if ($posts >= 500 && $lvl == 5) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Poster Level 6[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/fpost6.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Poster LVL6\', \'fpost6.png\' , \'Made at least 500 posts in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',6, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'postachiev';
            }
            if ($posts >= 750 && $lvl == 6) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Forum Poster Level 7[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/fpost7.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Forum Poster LVL7\', \'fpost7.png\' , \'Made at least 750 posts in the forums.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',7, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'postachiev';
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
                write_log("Achievements Cleanup: Achievements Forum Posts Completed using $queries queries. Forum Posts Achievements awarded to - " . $count . " Member(s)");
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
