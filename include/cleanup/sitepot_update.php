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
    //== sitepot
    sql_query("UPDATE avps SET value_i = 0, value_s = '0' WHERE arg = 'sitepot' AND value_u < " . TIME_NOW . " AND value_s = '1'") || sqlerr(__file__, __line__);
    $cache->delete('Sitepot_');
    
    if ($queries > 0) {
        write_log("Sitepot -------------------- Sitepot CLean Complete using $queries queries--------------------");
    }
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
