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
    //==delete torrents by putyn
    $days = 30;
    $dt = (TIME_NOW - ($days * 86400));
    $res = sql_query("SELECT id, name FROM torrents WHERE last_action < $dt AND seeders='0' AND leechers='0'");
    while ($arr = $res->fetch_assoc()) {
        sql_query("DELETE peers.*, files.*, comments.*, snatched.*, thankyou.*, thanks.*,thumbsup.*, bookmarks.*, coins.*, rating.*, ajax_chat_messages.*, torrents.* FROM torrents 
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
				 LEFT JOIN ajax_chat_messages ON ajax_chat_messages.torrent_id = torrents.id
				 WHERE torrents.id = ".sqlesc($arr['id'])) or sqlerr(__FILE__, __LINE__);
        @unlink("{$TRINITY20['torrent_dir']}/{$arr['id']}.torrent");
        write_log("Torrent ".(int)$arr['id']." (".htmlsafechars($arr['name']).") was deleted by system (older than $days days and no seeders)");
    }
    if ($queries > 0) write_log("Delete Old Torrents Clean -------------------- Delete Old Torrents cleanup Complete using $queries queries --------------------");
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
