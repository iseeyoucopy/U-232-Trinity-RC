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
require_once(INCL_DIR.'function_memcache.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('delete'));
if (!mkglobal("id")) {
    stderr("{$lang['delete_failed']}", "{$lang['delete_missing_data']}");
}
$id = 0 + $id;
if (!is_valid_id($id)) {
    stderr("{$lang['delete_failed']}", "{$lang['delete_missing_data']}");
}
//==delete torrents by putyn
function deletetorrent($id)
{
    global $TRINITY20, $cache, $CURUSER, $lang, $cache_keys;
    sql_query("DELETE peers.*, files.*, comments.*, snatched.*, thanks.*, bookmarks.*, coins.*, rating.*, thumbsup.*, ajax_chat_messages.*, torrents.* FROM torrents 
				 LEFT JOIN peers ON peers.torrent = torrents.id
				 LEFT JOIN files ON files.torrent = torrents.id
				 LEFT JOIN comments ON comments.torrent = torrents.id
				 LEFT JOIN thanks ON thanks.torrentid = torrents.id
				 LEFT JOIN bookmarks ON bookmarks.torrentid = torrents.id
				 LEFT JOIN coins ON coins.torrentid = torrents.id
				 LEFT JOIN rating ON rating.torrent = torrents.id
                                 LEFT JOIN thumbsup ON thumbsup.torrentid = torrents.id
				 LEFT JOIN snatched ON snatched.torrentid = torrents.id
				 LEFT JOIN ajax_chat_messages ON ajax_chat_messages.torrent_id = torrents.id
				 WHERE torrents.id =".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    unlink("{$TRINITY20['torrent_dir']}/$id.torrent");
    $cache->delete($cache_keys['my_peers'].$CURUSER['id']);
}

function deletetorrent_xbt($id)
{
    global $TRINITY20, $cache, $CURUSER, $lang, $cache_keys;
    sql_query("UPDATE torrents SET flags = 1 WHERE id = ".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    sql_query("DELETE files.*, comments.*, thankyou.*, thanks.*, thumbsup.*, bookmarks.*, coins.*, rating.*, ajax_chat_messages.*, xbt_files_users.* FROM xbt_files_users
                                     LEFT JOIN files ON files.torrent = xbt_files_users.fid
                                     LEFT JOIN comments ON comments.torrent = xbt_files_users.fid
                                     LEFT JOIN thankyou ON thankyou.torid = xbt_files_users.fid
                                     LEFT JOIN thanks ON thanks.torrentid = xbt_files_users.fid
                                     LEFT JOIN bookmarks ON bookmarks.torrentid = xbt_files_users.fid
                                     LEFT JOIN coins ON coins.torrentid = xbt_files_users.fid
                                     LEFT JOIN rating ON rating.torrent = xbt_files_users.fid
                                     LEFT JOIN thumbsup ON thumbsup.torrentid = xbt_files_users.fid
									 LEFT JOIN ajax_chat_messages ON ajax_chat_messages.torrent_id = xbt_files_users.fid
                                     WHERE xbt_files_users.fid =".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    unlink("{$TRINITY20['torrent_dir']}/$id.torrent");
    $cache->delete($cache_keys['my_xbt_peers'].$CURUSER['id']);
}

$res = sql_query("SELECT name, owner, seeders FROM torrents WHERE id =".sqlesc($id));
$row = $res->fetch_assoc();
if (!$row) {
    stderr("{$lang['delete_failed']}", "{$lang['delete_not_exist']}");
}
if ($CURUSER["id"] != $row["owner"] && $CURUSER["class"] < UC_STAFF) {
    stderr("{$lang['delete_failed']}", "{$lang['delete_not_owner']}\n");
}
$rt = 0 + $_POST["reasontype"];
if (!is_int($rt) || $rt < 1 || $rt > 5) {
    stderr("{$lang['delete_failed']}", "{$lang['delete_invalid']}");
}
$reason = $_POST["reason"];
if ($rt == 1) {
    $reasonstr = "{$lang['delete_dead']}";
} elseif ($rt == 2) {
    $reasonstr = "{$lang['delete_dupe']}".($reason[0] ? (": ".trim($reason[0])) : "!");
} elseif ($rt == 3) {
    $reasonstr = "{$lang['delete_nuked']}".($reason[1] ? (": ".trim($reason[1])) : "!");
} elseif ($rt == 4) {
    if (!$reason[2]) {
        stderr("{$lang['delete_failed']}", "{$lang['delete_violated']}");
    }
    $reasonstr = $TRINITY20['site_name']."{$lang['delete_rules']}".trim($reason[2]);
} else {
    if (!$reason[3]) {
        stderr("{$lang['delete_failed']}", "{$lang['delete_reason']}");
    }
    $reasonstr = trim($reason[3]);
}
if (XBT_TRACKER == true) {
    deletetorrent_xbt($id);
} else {
    deletetorrent($id);
    remove_torrent_peers($id);
}
//$cache->delete($cache_keys['lastest_tor']);
$cache->delete($cache_keys['top5_tor']);
$cache->delete($cache_keys['last5_tor']);
$cache->delete($cache_keys['scroll_tor']);
$cache->delete($cache_keys['torrent_details'].$id);
$cache->delete($cache_keys['torrent_details_txt'].$id);
write_log("{$lang['delete_torrent']} $id ({$row['name']}){$lang['delete_deleted_by']}{$CURUSER['username']} ($reasonstr)\n");
if ($TRINITY20['seedbonus_on'] == 1) {
    //===remove karma
    sql_query("UPDATE users SET seedbonus = seedbonus-".sqlesc($TRINITY20['bonus_per_delete'])." WHERE id = ".sqlesc($row["owner"])) || sqlerr(__FILE__,
        __LINE__);
    $update['seedbonus'] = ($CURUSER['seedbonus'] - $TRINITY20['bonus_per_delete']);
    $update['seedbonus'] = ($CURUSER['seedbonus'] - $TRINITY20['bonus_per_delete']);
    $cache->update_row($cache_keys['user_stats'].$row["owner"], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['u_stats']);
    $cache->update_row($cache_keys['user_statss'].$row["owner"], [
        'seedbonus' => $update['seedbonus'],
    ], $TRINITY20['expires']['user_stats']);
    //===end

}
if ($CURUSER["id"] != $row["owner"] && $CURUSER['pm_on_delete'] == 'yes') {
    $added = TIME_NOW;
    $pm_on = (int)$row["owner"];
    $message = "Torrent $id (".htmlspecialchars($row['name']).") has been deleted.\n  Reason: $reasonstr";
    sql_query("INSERT INTO messages (sender, receiver, msg, added) VALUES(0, ".sqlesc($pm_on).",".sqlesc($message).", $added)") || sqlerr(__FILE__,
        __LINE__);
    $cache->delete($cache_keys['inbox_new'].$pm_on);
    $cache->delete($cache_keys['inbox_new_sb'].$pm_on);
}
if (isset($_POST["returnto"])) {
    $ret = "<a href='".htmlspecialchars($_POST["returnto"])."'>{$lang['delete_go_back']}</a>";
} else {
    $ret = "<a href='{$TRINITY20['baseurl']}/browse.php'>{$lang['delete_back_browse']}</a>";
}
$HTMLOUT = '';
$HTMLOUT .= "<h2>{$lang['delete_deleted']}</h2>
    <p>$ret</p>";
echo stdhead("{$lang['delete_deleted']}").$HTMLOUT.stdfoot();
?>
