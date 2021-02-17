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
    set_time_limit(1200);
    ignore_user_abort(1);
    //== Delete shout
    $secs = 2 * 8_640_000;
    $dt = sqlesc(TIME_NOW - $secs);
    sql_query("DELETE FROM shoutbox WHERE " . TIME_NOW . " - date > $secs") || sqlerr(__FILE__, __LINE__);
    
    
    if ($queries > 0) {
        write_log("Shout Clean -------------------- Shout Clean Complete using $queries queries--------------------");
    }
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
