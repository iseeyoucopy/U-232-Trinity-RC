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
    global $INSTALLER09, $queries, $cache;
    set_time_limit(0);
    ignore_user_abort(1);
    //=== Clean silver
    $res = sql_query("SELECT id, silver FROM torrents WHERE silver > 1 AND silver < " . TIME_NOW) or sqlerr(__FILE__, __LINE__);
    $Silver_buffer = array();
    if (mysqli_num_rows($res) > 0) {
        while ($arr = mysqli_fetch_assoc($res)) {
            $Silver_buffer[] = '(' . $arr['id'] . ', \'0\')';
            $cache->begin_transaction('torrent_details_' . $arr['id']);
            $cache->update_row(false, array(
                'silver' => 0
            ));
            $cache->commit_transaction($INSTALLER09['expires']['torrent_details']);
        }
        $count = count($Silver_buffer);
        if ($count > 0) {
            sql_query("INSERT INTO torrents (id, silver) VALUES " . implode(', ', $Silver_buffer) . " ON DUPLICATE key UPDATE silver=values(silver)") or sqlerr(__FILE__, __LINE__);
            write_log("Cleanup - Removed Silver from " . $count . " torrents");
        }
        unset($Silver_buffer, $count);
    }
    //==End
    if ($queries > 0) write_log("Free clean-------------------- Silver Torrents cleanup Complete using $queries queries --------------------");
    if (false !== mysqli_affected_rows($GLOBALS["___mysqli_ston"])) {
        $data['clean_desc'] = mysqli_affected_rows($GLOBALS["___mysqli_ston"]) . " items updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
