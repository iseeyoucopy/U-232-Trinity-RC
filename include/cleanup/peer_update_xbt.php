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
/** sync torrent counts - pdq **/
function docleanup($data)
{
    global $TRINITY20, $queries, $cache, $mysqli, $keys;
    set_time_limit(0);
    ignore_user_abort(1);
    $torrent_seeds = $torrent_leeches = [];
    $deadtime = TIME_NOW - floor($TRINITY20['announce_interval'] * 1.3);
    $dead_peers = sql_query('SELECT tid, uid, peer_id, `left`, `active` FROM xbt_peers WHERE mtime < '.$deadtime);
    while ($dead_peer = $dead_peers->fetch_assoc()) {
        $torrentid = (int)$dead_peer['tid'];
        $userid = (int)$dead_peer['uid'];
        $seed = $dead_peer['left'] === 0;
        sql_query('DELETE FROM xbt_peers WHERE tid = '.sqlesc($torrentid).' AND peer_id = '.sqlesc($dead_peer['peer_id']).' AND `active` = "0" AND uploaded="0" AND downloaded="0"');
        if (!isset($torrent_seeds[$torrentid])) {
            $torrent_seeds[$torrentid] = $torrent_leeches[$torrentid] = 0;
        }
        if ($seed) {
            $torrent_seeds[$torrentid]++;
        } else {
            $torrent_leeches[$torrentid]++;
        }
    }
    foreach (array_keys($torrent_seeds) as $tid) {
        $update = [];
        if ($torrent_seeds[$tid] !== 0) {
            $update[] = 'seeders = (seeders < '.$torrent_seeds[$tid].')';
        }
        if ($torrent_leeches[$tid] !== 0) {
            $update[] = 'leechers = (leechers < '.$torrent_leeches[$tid].')';
        }
        sql_query('UPDATE torrents SET '.implode(', ', $update).' WHERE id = '.sqlesc($tid));
    }
    if ($queries > 0) {
        write_log("XBT Peers clean-------------------- XBT Peer cleanup Complete using $queries queries --------------------");
    }
    $data['clean_desc'] = $mysqli->affected_rows." items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
