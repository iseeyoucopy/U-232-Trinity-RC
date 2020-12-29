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
    global $TRINITY20, $queries, $cache;
    set_time_limit(0);
    ignore_user_abort(1);
    //== Delete iplog
    $dt = sqlesc(TIME_NOW - 1 * 86400);
    sql_query('DELETE FROM ips WHERE lastbrowse < ' . $dt . ' OR lastlogin < ' . $dt . ' OR  lastannounce < ' . $dt) or sqlerr(__FILE__, __LINE__);
    if ($queries > 0) write_log("Ip clean-------------------- Ip cleanup Complete using $queries queries --------------------");
    if (false !== mysqli_affected_rows($GLOBALS["___mysqli_ston"])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS["___mysqli_ston"]) . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
