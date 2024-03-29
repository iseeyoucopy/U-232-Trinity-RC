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
    sql_query("UPDATE `freeslots` SET `addedup` = 0 WHERE `addedup` != 0 AND `addedup` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `freeslots` SET `addedfree` = 0 WHERE `addedfree` != 0 AND `addedfree` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("DELETE FROM `freeslots` WHERE `addedup` = 0 AND `addedfree` = 0") || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `users` SET `free_switch` = 0 WHERE `free_switch` > 1 AND `free_switch` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `torrents` SET `free` = 0 WHERE `free` > 1 AND `free` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `users` SET `downloadpos` = 1 WHERE `downloadpos` > 1 AND `downloadpos` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `users` SET `uploadpos` = 1 WHERE `uploadpos` > 1 AND `uploadpos` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `users` SET `chatpost` = 1 WHERE `chatpost` > 1 AND `chatpost` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `users` SET `avatarpos` = 1 WHERE `avatarpos` > 1 AND `avatarpos` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `users` SET `immunity` = 0 WHERE `immunity` > 1 AND `immunity` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `users` SET `warned` = 0 WHERE `warned` > 1 AND `warned` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `users` SET `pirate` = 0 WHERE `pirate` > 1 AND `pirate` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    sql_query("UPDATE `users` SET `king` = 0 WHERE `king` > 1 AND `king` < " . TIME_NOW) || sqlerr(__FILE__, __LINE__);
    if ($queries > 0) {
        write_log("User Clean -------------------- User Clean Complete using $queries queries--------------------");
    }
    if ($mysqli->affected_rows) $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}

?>
