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
    //== Delete cheaters
    $dt = (TIME_NOW - (30 * 86400));
    sql_query("DELETE FROM cheaters WHERE added < ".sqlesc($dt)) || sqlerr(__FILE__, __LINE__);
    if ($queries > 0) {
        write_log("Cheaters list clean-------------------- Removed old cheater entrys. Cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows." items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
