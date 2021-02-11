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
    //== Movie of the week
    $res_tor = sql_query("SELECT id, name FROM torrents WHERE times_completed > 0 AND category IN (" . join(", ", $TRINITY20['movie_cats']) . ") ORDER BY times_completed DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);
    if ($res_tor->num_rows > 0) {
        $arr = $res_tor->fetch_assoc();
        sql_query("UPDATE avps SET value_u=" . sqlesc($arr['id']) . ", value_i=" . sqlesc(TIME_NOW) . " WHERE avps.arg='bestfilmofweek'") or sqlerr(__FILE__, __LINE__);
        $cache->delete('top_movie_2');
        write_log("Torrent [" . (int)$arr["id"] . "]&nbsp;[" . htmlentities($arr["name"]) . "] was set 'Best Film of the Week' by system");
    }
    //==End
    if ($queries > 0) write_log("Auto Movie of the week-------------------- Movie of the week Cleanups Complete using $queries queries --------------------");
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
