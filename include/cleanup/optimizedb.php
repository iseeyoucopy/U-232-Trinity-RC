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
    global $TRINITY20, $queries, $cache_keys;
    set_time_limit(1200);
    ignore_user_abort(1);
    $sql = sql_query("SHOW TABLE STATUS FROM {$TRINITY20['mysql_db']}");
    $oht = '';
    while ($row = $sql->fetch_assoc()) {
        if ($row['Data_free'] > 100) {
            $oht .= $row['Data_free'].',';
        }
    }
    $oht = rtrim($oht, ',');
    if ($oht != '') {
        $sql = sql_query("OPTIMIZE TABLE {$oht}");
    }
    if ($queries > 0) {
        write_log("Auto-optimizedb--------------------Auto Optimization Complete using $queries queries --------------------");
    }
    if ($oht != '') {
        $data['clean_desc'] = "MySQLCleanup optimized {$oht} table(s)";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
