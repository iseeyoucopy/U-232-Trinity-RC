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
    //== Delete snatched
    $dt = (TIME_NOW - (30 * 86400));
    sql_query("DELETE FROM snatched WHERE complete_date < " . sqlesc($dt)) || sqlerr(__FILE__, __LINE__);
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";

    $snatchedcounts = [];
    $snatchedres = sql_query("SELECT torrentid, COUNT(*) AS count FROM snatched WHERE complete_date > 0 GROUP BY torrentid");
    while ($row = $snatchedres->fetch_assoc()) {
        $snatchedcounts[$row['torrentid']] = (int)$row['count'];
    }
    $tcompletedres = sql_query("SELECT id, times_completed FROM torrents");
    while ($row2 = $tcompletedres->fetch_assoc()) {
        if (array_key_exists($row2['id'], $snatchedcounts) && $row2['times_completed'] != $snatchedcounts[$row2['id']]) {
            sql_query("UPDATE torrents SET times_completed = " . $snatchedcounts[$row2['id']] . " WHERE id = " . $row2['id']);
        }
    }

    if ($queries > 0) {
        write_log("Snatch list clean-------------------- Removed snatches not seeded for 99 days. Cleanup Complete using $queries queries --------------------");
    }

    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
