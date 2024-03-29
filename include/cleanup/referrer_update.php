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
    // Remove expired referrers...	
    $days = 30 * 86400; // 30 days
    $dt = (TIME_NOW - $days);
    sql_query("DELETE FROM referrers WHERE date < " . sqlesc($dt)) || sqlerr(__FILE__, __LINE__);
    // End Delete Last Referrers
    if ($queries > 0) {
        write_log("Referrer Clean -------------------- Referrer cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
