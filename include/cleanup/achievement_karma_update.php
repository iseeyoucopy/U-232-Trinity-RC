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
    // *Updated* Bonus Point Achievements
    ($res = sql_query("SELECT users.id, users.seedbonus, usersachiev.bonus FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE enabled = 'yes' AND users.seedbonus >= '1' AND usersachiev.bonus >= '0'")) || sqlerr(__FILE__,
        __LINE__);
    $msg_buffer = $usersachiev_buffer = $achievements_buffer = [];
    if ($res->num_rows > 0) {
        $dt = TIME_NOW;
        $points = rand(1, 3);
        $subject = sqlesc("New Achievement Earned!");
        while ($arr = $res->fetch_assoc()) {
            $seedbonus = (float)$arr['seedbonus'];
            $lvl = (int)$arr['bonus'];
            if ($seedbonus >= 1 && $lvl == 0) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL1[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus1.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL1\', \'bonus1.png\' , \'Earned at least 1 bonus point.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',1, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 100 && $lvl == 1) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL2[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus2.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL2\', \'bonus2.png\' , \'Earned at least 100 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',2, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 500 && $lvl == 2) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL3[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus3.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL3\', \'bonus3.png\' , \'Earned at least 500 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',3, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 1000 && $lvl == 3) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL4[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus4.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL4\', \'bonus4.png\' , \'Earned at least 1000 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',4, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 2000 && $lvl == 4) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL5[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus5.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL5\', \'bonus5.png\' , \'Earned at least 2000 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',5, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 5000 && $lvl == 5) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL6[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus6.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL6\', \'bonus6.png\' , \'Earned at least 5000 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',6, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 10000 && $lvl == 6) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL7[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus7.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL7\', \'bonus7.png\' , \'Earned at least 10000 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',7, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 30000 && $lvl == 7) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL8[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus8.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL8\', \'bonus8.png\' , \'Earned at least 30000 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',8, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 70000 && $lvl == 8) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL9[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus9.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL9\', \'bonus9.png\' , \'Earned at least 70000 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',9, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 100000 && $lvl == 9) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL10[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus10.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL10\', \'bonus10.png\' , \'Earned at least 100000 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',10, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $var1 = 'bonus';
            }
            if ($seedbonus >= 1_000_000 && $lvl == 10) {
                $msg = sqlesc("Congratulations, you have just earned the [b]Bonus Banker LVL11[/b] achievement. :) [img]" . $TRINITY20['baseurl'] . "/pic/achievements/bonus11.png[/img]");
                $msgs_buffer[] = '(0,' . $arr['id'] . ',' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $achievements_buffer[] = '(' . $arr['id'] . ', ' . TIME_NOW . ', \'Bonus Banker LVL11\', \'bonus11.png\' , \'Earned at least 1000000 bonus points.\')';
                $usersachiev_buffer[] = '(' . $arr['id'] . ',11, ' . $points . ')';
                $cache->delete($cache_keys['inbox_new'] . $arr['id']);
                $cache->delete($cache_keys['inbox_new_sb'] . $arr['id']);
                $cache->delete($cache_keys['user_achiev_points'] . $arr['id']);
                $var1 = 'bonus';
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
                write_log("Achievements Cleanup: Achievements karma Completed using $queries queries. Karma Achievements awarded to - " . $count . " Member(s)");
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
