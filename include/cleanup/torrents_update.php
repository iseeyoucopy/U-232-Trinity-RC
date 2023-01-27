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
    global $TRINITY20, $queries, $mysqli, $keys;
    set_time_limit(0);
    ignore_user_abort(1);
    /** sync torrent counts - pdq **/
    $tsql = 'SELECT t.id, t.seeders, (
    SELECT COUNT(*)
    FROM peers
    WHERE torrent = t.id AND seeder = "yes"
    ) AS seeders_num,
    t.leechers, (
    SELECT COUNT(*)
    FROM peers
    WHERE torrent = t.id
    AND seeder = "no"
    ) AS leechers_num,
    t.comments, (
    SELECT COUNT(*)
    FROM comments
    WHERE torrent = t.id
    ) AS comments_num
    FROM torrents AS t
    ORDER BY t.id ASC';
    $updatetorrents = [];
    ($tq = sql_query($tsql)) || sqlerr(__FILE__,
    __LINE__);
    while ($t = $tq->fetch_assoc()) {
        if ($t['seeders'] != $t['seeders_num'] || $t['leechers'] != $t['leechers_num'] || $t['comments'] != $t['comments_num']) {
            $updatetorrents[] = '('.$t['id'].', '.$t['seeders_num'].', '.$t['leechers_num'].', '.$t['comments_num'].')';
        }
    }
    $tq->free();
    if (!empty($updatetorrents)) {
        sql_query('INSERT INTO torrents (id, seeders, leechers, comments) VALUES '.implode(', ',
                $updatetorrents).' ON DUPLICATE KEY UPDATE seeders = VALUES(seeders), leechers = VALUES(leechers), comments = VALUES(comments)') || sqlerr(__FILE__,
                    __LINE__);
    }
    unset($updatetorrents);
    if ($queries > 0) {
        write_log("Torrent clean-------------------- Torrent cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows." items updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
