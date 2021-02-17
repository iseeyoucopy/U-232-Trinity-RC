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
    set_time_limit(0);
    ignore_user_abort(1);
    require_once (INCL_DIR . 'function_happyhour.php');
    //==Putyns HappyHour
    $f = $TRINITY20['happyhour'];
    $happy = unserialize(file_get_contents($f));
    $happyHour = strtotime($happy["time"]);
    $curDate = TIME_NOW;
    $happyEnd = $happyHour + 3600;
    if ($happy["status"] == 0 && $TRINITY20['happy_hour'] == true) {
        write_log("Happy hour was @ " . get_date($happyHour, 'LONG', 1, 0) . " and Catid " . $happy["catid"] . " ");
        happyFile("set");
    } elseif (($curDate > $happyEnd) && $happy["status"] == 1) {
        happyFile("reset");
    }
    //== End
    if ($queries > 0) {
        write_log("Happyhour Clean -------------------- Happyhour cleanup Complete using $queries queries --------------------");
    }
    if (false !== $mysqli->affected_rows) {
        $data['clean_desc'] = $mysqli->affected_rows . " items deleted/updated";
    }
    if ($data['clean_log']) {
        cleanup_log($data);
    }
}
?>
