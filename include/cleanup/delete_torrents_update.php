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
    //==delete torrents by putyn
    $days = 30;
    $dt = (TIME_NOW - ($days * 86400));
    $res = sql_query("SELECT id, name FROM torrents WHERE last_action < $dt AND seeders='0' AND leechers='0'");
    while ($arr = $res->fetch_assoc()) {
        sql_query("DELETE peers.*, files.*, comments.*, snatched.*, thankyou.*, thanks.*,thumbsup.*, bookmarks.*, coins.*, rating.*, torrents.* FROM torrents 
				 LEFT JOIN peers ON peers.torrent = torrents.id
				 LEFT JOIN files ON files.torrent = torrents.id
				 LEFT JOIN comments ON comments.torrent = torrents.id
                                 LEFT JOIN thankyou ON thankyou.torid = torrents.id
				 LEFT JOIN thanks ON thanks.torrentid = torrents.id
				 LEFT JOIN bookmarks ON bookmarks.torrentid = torrents.id
				 LEFT JOIN coins ON coins.torrentid = torrents.id
				 LEFT JOIN rating ON rating.torrent = torrents.id
                                 LEFT JOIN thumbsup ON thumbsup.torrentid = torrents.id
				 LEFT JOIN snatched ON snatched.torrentid = torrents.id
				 WHERE torrents.id = " . sqlesc($arr['id'])) || sqlerr(__FILE__, __LINE__);
        @unlink("{$TRINITY20['torrent_dir']}/{$arr['id']}.torrent");
        $cache->delete($cache_keys['torrent_details'] . $arr['id']);
        $cache->delete($cache_keys['torrent_pretime'] . $arr['id']);
        $cache->delete($cache_keys['last_action'] . $arr['id']);
        $cache->delete($cache_keys['last_action'] . $arr['id']);
        $cache->delete($cache_keys['thumbs_up'] . $arr['id']);
        $cache->delete($cache_keys['torrent_details_txt'] . $arr['id']);

        write_log("Torrent " . (int)$arr['id'] . " (" . htmlsafechars($arr['name']) . ") was deleted by system (older than $days days and no seeders)");
    }
    if ($queries > 0) {
        write_log("Delete Old Torrents Clean -------------------- Delete Old Torrents cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
