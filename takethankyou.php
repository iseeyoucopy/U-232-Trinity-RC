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
$lang = array_merge(load_language('global'), load_language('takerate'));
if (!mkglobal("id")) {
    die();
}
$id = (int)$id;
if (!is_valid_id($id)) {
    stderr("Error", "Bad Id");
}
if (!isset($CURUSER)) {
    stderr("Error", "Your not logged in");
}
($res = sql_query("SELECT 1, thanks, comments FROM torrents WHERE id = ".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
$arr = $res->fetch_assoc();
if (!$arr) {
    stderr("Error", "Torrent not found");
}
($res1 = sql_query("SELECT 1 FROM thankyou WHERE torid=".sqlesc($id)." AND uid =".sqlesc($CURUSER["id"]))) || sqlerr(__FILE__, __LINE__);
$row = $res1->fetch_assoc();
if ($row) {
    stderr("Error", "You already thanked.");
}
$text = ":thankyou:";
$newid = $mysqli->insert_id;
sql_query("INSERT INTO thankyou (uid, torid, thank_date) VALUES (".sqlesc($CURUSER["id"]).", ".sqlesc($id).", '".TIME_NOW."')") || sqlerr(__FILE__,
    __LINE__);
sql_query("INSERT INTO comments (user, torrent, added, text, ori_text) VALUES (".sqlesc($CURUSER["id"]).", ".sqlesc($id).", '".TIME_NOW."', ".sqlesc($text).",".sqlesc($text).")") || sqlerr(__FILE__,
    __LINE__);
sql_query("UPDATE torrents SET thanks = thanks + 1, comments = comments + 1 WHERE id = ".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
$update['thanks'] = ($arr['thanks'] + 1);
$update['comments'] = ($arr['comments'] + 1);
$cache->update_row($cache_keys['torrent_details'].$id, [
    'thanks' => $update['thanks'],
    'comments' => $update['comments'],
], $TRINITY20['expires']['torrent_details']);
if ($TRINITY20['seedbonus_on'] == 1) {
    //===add karma
    sql_query("UPDATE users SET seedbonus = seedbonus+5.0 WHERE id = ".sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
    $update['seedbonus'] = ($CURUSER['seedbonus'] + 5);
    $cache->update_row($cache_keys['user_stats'].$CURUSER["id"], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['u_stats']);
    $cache->update_row($cache_keys['user_statss'].$CURUSER["id"], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['user_stats']);
    //===end

}
header("Refresh: 0; url=details.php?id=$id");
?>
