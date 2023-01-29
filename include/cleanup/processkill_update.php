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
    global $TRINITY20, $queries, $cache_keys;
    set_time_limit(1200);
    ignore_user_abort(1);
    $sql = sql_query("SHOW PROCESSLIST");
    $cnt = 0;
    while ($arr = $sql->fetch_assoc()) {
        if ($arr['db'] == $TRINITY20['mysql_db'] && $arr['Command'] == 'Sleep' && $arr['Time'] > 60) {
            sql_query("KILL {$arr['Id']}");
            $cnt++;
        }
    }
    if ($queries > 0) {
        write_log("Proccess Kill clean-------------------- Proccess Kill Complete using $queries queries --------------------");
    }
    if ($cnt != 0) {
        $data['clean_desc'] = "MySQLCleanup killed {$cnt} processes";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
