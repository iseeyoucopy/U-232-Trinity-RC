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
    require_once(INCL_DIR.'ann_functions.php');
    $torrent_seeds = $torrent_leeches = [];
    $deadtime = TIME_NOW - floor($TRINITY20['announce_interval'] * 1.3);
    ($dead_peers = sql_query('SELECT torrent, userid, peer_id, seeder FROM peers WHERE last_action < '.$deadtime)) || sqlerr(__FILE__, __LINE__);
    while ($dead_peer = $dead_peers->fetch_assoc()) {
        $torrentid = (int)$dead_peer['torrent'];
        $seed = $dead_peer['seeder'] === 'yes'; // you use 'yes' i thinks :P
        sql_query('DELETE FROM peers WHERE torrent = '.$torrentid.' AND peer_id = '.sqlesc($dead_peer['peer_id'])) || sqlerr(__FILE__,
        __LINE__);
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
        adjust_torrent_peers($tid, -$torrent_seeds[$tid], -$torrent_leeches[$tid], 0);
        if ($torrent_seeds[$tid] !== 0) {
            $update[] = 'seeders = (seeders - '.$torrent_seeds[$tid].')';
        }
        if ($torrent_leeches[$tid] !== 0) {
            $update[] = 'leechers = (leechers - '.$torrent_leeches[$tid].')';
        }
        sql_query('UPDATE torrents SET '.implode(', ', $update).' WHERE id = '.$tid) || sqlerr(__FILE__,
        __LINE__);
    }
    if ($queries > 0) {
        write_log("Peers clean-------------------- Peer cleanup Complete using $queries queries --------------------");
    }
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows." items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
