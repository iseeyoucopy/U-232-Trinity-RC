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
//made by putyn @tbdev.net
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
dbconn();
loggedinorreturn();
global $TRINITY20,$cache;
$pm_what = isset($_POST["pm_what"]) && $_POST["pm_what"] == "last10" ? "last10" : "owner";
$reseedid = (int) $_POST["reseedid"];
$uploader = (int) $_POST["uploader"];
$use_subject = true;
$subject = "Request reseed!";
$pm_msg = "User " . $CURUSER["username"] . " asked for a reseed on torrent " . $TRINITY20['baseurl'] . "/details.php?id=" . $reseedid . " !\nThank You!";
$What_id = (XBT_TRACKER == true ? 'fid' : 'torrentid');
$What_user_id = (XBT_TRACKER == true ? 'uid' : 'userid');
$What_Table = (XBT_TRACKER == true ? 'xbt_files_users' : 'snatched');
$What_TF = (XBT_TRACKER == true ? "active='1'" : "seeder='yes'");
$pms = [];
if ($pm_what == "last10") {
    ($res = sql_query("SELECT $What_Table.$What_user_id as userid, $What_Table.$What_id FROM $What_Table WHERE $What_Table.$What_id =" . sqlesc($reseedid) . " AND $What_Table.$What_TF LIMIT 10")) || sqlerr(__FILE__, __LINE__);
    while ($row = $res->fetch_assoc()) {
        $pms[] = "(0," . sqlesc($row["userid"]) . "," . TIME_NOW . "," . sqlesc($pm_msg) . ($use_subject ? "," . sqlesc($subject) : "") . ")";
        $cache->delete('inbox_new::' . $row["userid"]);
        $cache->delete('inbox_new_sb::' . $row["userid"]);
    }
} elseif ($pm_what == "owner") {
    $pms[] = "(0,$uploader," . TIME_NOW . "," . sqlesc($pm_msg) . ($use_subject ? "," . sqlesc($subject) : "") . ")";
    $cache->delete('inbox_new::' . $uploader);
    $cache->delete('inbox_new_sb::' . $uploader);
}
if (count($pms) > 0) {
    sql_query("INSERT INTO messages (sender, receiver, added, msg " . ($use_subject ? ", subject" : "") . " ) VALUES " . implode(",", $pms)) || sqlerr(__FILE__, __LINE__);
    
}
sql_query("UPDATE torrents set last_reseed=" . TIME_NOW . " WHERE id=" . sqlesc($reseedid)) || sqlerr(__FILE__, __LINE__);
$cache->update_row('torrent_details_' . $reseedid, [
    'last_reseed' => TIME_NOW
], $TRINITY20['expires']['torrent_details']);
if ($TRINITY20['seedbonus_on'] == 1) {
    //===remove karma
    sql_query("UPDATE users SET seedbonus = seedbonus-{$TRINITY20['bonus_per_reseed']} WHERE id = " . sqlesc($CURUSER["id"])) || sqlerr(__FILE__, __LINE__);
    $update['seedbonus'] = ($CURUSER['seedbonus'] - $TRINITY20['bonus_per_reseed']);
    $cache->update_row($keys['user_stats'] . $CURUSER["id"], [
        'seedbonus' => $update['seedbonus']
    ], $TRINITY20['expires']['u_stats']);
    $cache->update_row('user_stats_' . $CURUSER["id"], [
        'seedbonus' => $update['seedbonus']
    ], $TRINITY20['expires']['user_stats']);
    //===end
}
header("Refresh: 0; url=./details.php?id=$reseedid&reseed=1");
