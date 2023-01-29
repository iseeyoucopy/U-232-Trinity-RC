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
    global $TRINITY20, $queries, $mysqli, $cache_keys;
    set_time_limit(0);
    ignore_user_abort(1);
    $dt = TIME_NOW;
    $subject = sqlesc("New Achievement Earned!");
    $points = rand(1, 3);
    //Reset the daily shoutbox limits
    sql_query("UPDATE `usersachiev` SET `dailyshouts` = '0'") || sqlerr(__FILE__, __LINE__);
    if ($queries > 0) {
        write_log("Achievements Cleanup:  Achievements dailyshouts reset Completed using $queries queries");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows." items updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
