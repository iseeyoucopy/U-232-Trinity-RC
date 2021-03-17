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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('coins'));

// / Mod by dokty - tbdev.net
$id = (int)$_GET["id"];
$points = (int)$_GET["points"];
if (!is_valid_id($id) || !is_valid_id($points)) {
    die();
}
$pointscangive = [
    "10",
    "20",
    "50",
    "100",
    "200",
    "500",
    "1000",
];
if (!in_array($points, $pointscangive)) {
    stderr($lang['gl_error'], $lang['coins_you_cant_give_that_amount_of_points']);
}
($sdsa = sql_query("SELECT 1 FROM coins WHERE torrentid=".sqlesc($id)." AND userid =".sqlesc($CURUSER["id"]))) || sqlerr(__FILE__, __LINE__);
$asdd = $sdsa->fetch_assoc();
if ($asdd) {
    stderr($lang['gl_error'], $lang['coins_you_already_gave_points_to_this_torrent']);
}
($res = sql_query("SELECT owner,name,points FROM torrents WHERE id = ".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
($row = $res->fetch_assoc()) || stderr($lang['gl_error'], $lang['coins_torrent_was_not_found']);
$userid = (int)$row["owner"];
if ($userid == $CURUSER["id"]) {
    stderr($lang['gl_error'], $lang['coins_you_cant_give_your_self_points']);
}
if ($CURUSER["seedbonus"] < $points) {
    stderr($lang['gl_error'], $lang['coins_you_dont_have_enough_points']);
}
($sql = sql_query('SELECT seedbonus '.'FROM users '.'WHERE id = '.sqlesc($userid))) || sqlerr(__FILE__, __LINE__);
$User = $sql->fetch_assoc();
sql_query("INSERT INTO coins (userid, torrentid, points) VALUES (".sqlesc($CURUSER["id"]).", ".sqlesc($id).", ".sqlesc($points).")") || sqlerr(__FILE__,
    __LINE__);
sql_query("UPDATE users SET seedbonus=seedbonus+".sqlesc($points)." WHERE id=".sqlesc($userid)) || sqlerr(__FILE__, __LINE__);
sql_query("UPDATE users SET seedbonus=seedbonus-".sqlesc($points)." WHERE id=".sqlesc($CURUSER["id"])) || sqlerr(__FILE__, __LINE__);
sql_query("UPDATE torrents SET points=points+".sqlesc($points)." WHERE id=".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
$msg = sqlesc("{$lang['coins_you_have_been_given']} ".htmlsafechars($points)." {$lang['coins_points_by']} ".$CURUSER["username"]." {$lang['coins_for_torrent']} [url=".$TRINITY20['baseurl']."/details.php?id=".$id."]".htmlsafechars($row["name"])."[/url].");
$subject = sqlesc($lang['coins_you_have_been_given_a_gift']);
sql_query("INSERT INTO messages (sender, receiver, msg, added, subject) VALUES({$TRINITY20['bot_id']}, ".sqlesc($userid).", $msg, ".TIME_NOW.", $subject)") || sqlerr(__FILE__,
    __LINE__);
$update['points'] = ($row['points'] + $points);
$update['seedbonus_uploader'] = ($User['seedbonus'] + $points);
$update['seedbonus_donator'] = ($CURUSER['seedbonus'] - $points);
//==The torrent
$cache->update_row($keys['torrent_details'].$id, [
    'points' => $update['points'],
], $TRINITY20['expires']['torrent_details']);
//==The uploader
$cache->update_row($keys['user_stats'].$userid, [
    'seedbonus' => $update['seedbonus_uploader'],
], $TRINITY20['expires']['u_stats']);
$cache->update_row('user_stats_'.$userid, [
    'seedbonus' => $update['seedbonus_uploader'],
], $TRINITY20['expires']['user_stats']);
//==The donator
$cache->update_row($keys['user_stats'].$CURUSER["id"], [
    'seedbonus' => $update['seedbonus_donator'],
], $TRINITY20['expires']['u_stats']);
$cache->update_row('user_stats_'.$CURUSER["id"], [
    'seedbonus' => $update['seedbonus_donator'],
], $TRINITY20['expires']['user_stats']);
//== delete the pm keys
$cache->delete('inbox_new::'.$userid);
$cache->delete('inbox_new_sb::'.$userid);
$cache->delete($keys['coin_points'].$id);
header("Refresh: 3; url=details.php?id=$id");
stderr($lang['coins_done'], $lang['coins_successfully_gave_points_to_this_torrent']);
?>
