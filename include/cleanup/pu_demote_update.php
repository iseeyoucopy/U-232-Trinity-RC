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
    //== Updated demote power users

    //Get promotion rules from DB//
    ($pconf = sql_query('SELECT * FROM class_promo ORDER BY id ASC ')) || sqlerr(__FILE__, __LINE__);
    while ($ac = $pconf->fetch_assoc()) {
        $class_config[$ac['name']]['id'] = $ac['id'];
        $class_config[$ac['name']]['name'] = $ac['name'];
        $class_config[$ac['name']]['min_ratio'] = $ac['min_ratio'];
        $class_config[$ac['name']]['uploaded'] = $ac['uploaded'];
        $class_config[$ac['name']]['time'] = $ac['time'];
        $class_config[$ac['name']]['low_ratio'] = $ac['low_ratio'];
        //Sets the Min ratio for demoting a class.
        $minratio = $class_config[$ac['name']]['low_ratio'];
        //Get the class value we are working on
        //AND Set the next class value to - 1
        $class_value = $class_config[$ac['name']]['name'];
        $res1 = sql_query("SELECT * from class_config WHERE value = '$class_value' ");
        while ($arr1 = $res1->fetch_assoc()) {
            //Changed for testing
            // As we are working on the class name which is being demoted, we need to -1 from it, to get the class the users are going in
            //  i.e UC_POWER_USER = 1, but we are demoting UC_USER = 0.
            $class_name = $arr1['classname'];
            $prev_class = $class_value - 1;
            /*
            $class = $arr1['value'];
            $next_class = $class + 1;
            */
        }
        // Get the class name and value of the previous class //
        $res2 = sql_query("SELECT * from class_config WHERE value = '$prev_class' ");
        while ($arr2 = $res2->fetch_assoc()) {
            $prev_class_name = $arr2['classname'];
        }
        ($res = sql_query("SELECT id, uploaded, downloaded, modcomment FROM users WHERE class = $class_value AND uploaded / downloaded < $minratio")) || sqlerr(__FILE__,
            __LINE__);
        $subject = "Auto Demotion";
        $msgs_buffer = $users_buffer = [];
        if ($res->num_rows > 0) {
            $msg = "You have been auto-demoted from [b]{$class_name}[/b] to [b]{$prev_class_name}[/b] because your share ratio has dropped below {$minratio}.";

            while ($arr = $res->fetch_assoc()) {
                $ratio = number_format($arr['uploaded'] / $arr['downloaded'], 3);
                $modcomment = $arr['modcomment'];
                $userid = $arr['id'];
                $modcomment = get_date(TIME_NOW, 'DATE',
                        1) . " - Demoted To " . $prev_class_name . " by System (UL=" . mksize($arr['uploaded']) . ", DL=" . mksize($arr['downloaded']) . ", R=" . $ratio . ").\n" . $modcomment;
                $modcom = sqlesc($modcomment);
                $msgs_buffer[] = '(0,' . $userid . ', ' . TIME_NOW . ', ' . sqlesc($msg) . ', ' . sqlesc($subject) . ')';
                $users_buffer[] = '(' . $userid . ', ' . $prev_class . ', ' . $modcom . ')';
                $cache->update_row($cache_keys['user'] . $userid, [
                    'class' => $prev_class,
                ], $TRINITY20['expires']['user_cache']);
                $cache->update_row($cache_keys['user_statss'] . $userid, [
                    'modcomment' => $modcomment,
                ], $TRINITY20['expires']['user_stats']);
                $cache->update_row($cache_keys['my_userid'] . $userid, [
                    'class' => $prev_class,
                ], $TRINITY20['expires']['curuser']);
                $cache->delete($cache_keys['inbox_new'] . $userid);
                $cache->delete($cache_keys['inbox_new_sb'] . $userid);
            }
            $count = count($users_buffer);
            if ($count > 0) {
                sql_query("INSERT INTO messages (sender,receiver,added,msg,subject) VALUES " . implode(', ', $msgs_buffer)) || sqlerr(__FILE__,
                    __LINE__);
                sql_query("INSERT INTO users (id, class, modcomment) VALUES " . implode(', ',
                        $users_buffer) . " ON DUPLICATE key UPDATE class=values(class),modcomment=values(modcomment)") || sqlerr(__FILE__, __LINE__);
                write_log("Cleanup: Demoted " . $count . " member(s) from $class_name to $prev_class_name");
                status_change($userid); //== For Retros announcement mod
            }
            unset($users_buffer, $msgs_buffer, $count);

        }
        //==End
        if ($queries > 0) {
            write_log("$prev_class_name Updates -------------------- Power User Demote Updates Clean Complete using $queries queries--------------------");
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
