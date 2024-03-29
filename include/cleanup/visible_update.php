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
    $deadtime_tor = TIME_NOW - $TRINITY20['max_dead_torrent_time'];
    $What_Time = (XBT_TRACKER == true ? 'mtime' : 'last_action');
    sql_query("UPDATE torrents SET visible='no' WHERE visible='yes' AND $What_Time < $deadtime_tor");
    if (XBT_TRACKER == true) {
        sql_query("UPDATE torrents SET visible='yes' WHERE visible='no' AND seeders > 0");
    }
    if ($queries > 0) {
        write_log("Torrent Visible clean-------------------- Torrent Visible cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
