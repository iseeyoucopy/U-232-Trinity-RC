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
    //== Delete expired announcements and processors
    sql_query("DELETE announcement_process FROM announcement_process LEFT JOIN users ON announcement_process.user_id = users.id WHERE users.id IS NULL") or sqlerr(__FILE__, __LINE__);
    sql_query("DELETE FROM announcement_main WHERE expires < " . TIME_NOW) or sqlerr(__FILE__, __LINE__);
    sql_query("DELETE announcement_process FROM announcement_process LEFT JOIN announcement_main ON announcement_process.main_id = announcement_main.main_id WHERE announcement_main.main_id IS NULL") or sqlerr(__FILE__, __LINE__);
    if ($queries > 0) write_log("Announcement Clean -------------------- Announcement cleanup Complete using $queries queries --------------------");
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
