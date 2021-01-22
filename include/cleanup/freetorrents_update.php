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
    set_time_limit(0);
    ignore_user_abort(1);
    //=== Clean free
    $res = sql_query("SELECT id, free FROM torrents WHERE free > 1 AND free < " . TIME_NOW) or sqlerr(__FILE__, __LINE__);
    $Free_buffer = array();
    if ($res->num_rows() > 0) {
        while ($arr = $res->fetch_assoc()) {
            $Free_buffer[] = '(' . $arr['id'] . ', \'0\')';
            $cache->update_row('torrent_details_' . $arr['id'], [
                'free' => 0
            ], $TRINITY20['expires']['torrent_details']);
        }
        $count = count($Free_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO torrents (id, free) VALUES " . implode(', ', $Free_buffer) . " ON DUPLICATE key UPDATE free=values(free)") or sqlerr(__FILE__, __LINE__);
            write_log("Cleanup - Removed Free from " . $count . " torrents");
        }
        unset($Free_buffer, $count);
    }
    //==End
    if ($queries > 0) write_log("Free clean-------------------- Free Torrents cleanup Complete using $queries queries --------------------");
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
