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
    //== Delete old backup's
    $days = 3;
    ($res = sql_query("SELECT id, name FROM dbbackup WHERE added < " . sqlesc(TIME_NOW - ($days * 86400)))) || sqlerr(__FILE__, __LINE__);
    if ($res->num_rows > 0) {
        $ids = [];
        while ($arr = $res->fetch_assoc()) {
            $ids[] = (int)$arr['id'];
            $filename = $TRINITY20['backup_dir'] . '/' . $arr['name'];
            if (is_file($filename)) {
                unlink($filename);
            }
        }
        sql_query('DELETE FROM dbbackup WHERE id IN (' . implode(', ', $ids) . ')') || sqlerr(__FILE__, __LINE__);
    }
    //== end
    if ($queries > 0) {
        write_log("Backup Clean -------------------- Backup Clean Complete using $queries queries--------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
