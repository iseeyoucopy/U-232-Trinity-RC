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
    do {
        $res = sql_query("SELECT id FROM torrents");
        $ar = [];
        while ($row = $res->fetch_array(MYSQLI_NUM)) {
            $id = $row[0];
            $ar[$id] = 1;
        }
        if (count($ar) === 0) {
            break;
        }

        $dp = opendir($TRINITY20['torrent_dir']);
        if (!$dp) {
            break;
        }

        $ar2 = [];
        while (($file = readdir($dp)) !== false) {
            if (!preg_match('/^(\d+)\.torrent$/', $file, $m)) {
                continue;
            }
            $id = $m[1];
            $ar2[$id] = 1;
            if (isset($ar[$id]) && $ar[$id]) {
                continue;
            }
            $ff = $TRINITY20['torrent_dir']."/$file";
            unlink($ff);
        }
        closedir($dp);
        if (count($ar2) === 0) {
            break;
        }

        $delids = [];
        foreach (array_keys($ar) as $k) {
            if (isset($ar2[$k]) && $ar2[$k]) {
                continue;
            }
            $delids[] = $k;
            unset($ar[$k]);
        }
        if (count($delids) > 0) {
            $ids = implode(",", $delids);
            sql_query("DELETE torrents t, peers p, files f FROM torrents t
                  left join files f on f.torrent=t.id
                  left join peers p on p.torrent=t.id
                  WHERE f.torrent IN ($ids) 
                  OR p.torrent IN ($ids) 
                  OR t.id IN ($ids)");
        }
    } while (0);
    if ($queries > 0) {
        write_log("Normalize clean-------------------- Normalize cleanup Complete using $queries queries --------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows." items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
