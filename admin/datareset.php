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
if (!defined('IN_TRINITY20_ADMIN')) {
    $HTMLOUT = '';
    $HTMLOUT .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br>You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'html_functions.php');
require_once(INCL_DIR.'function_memcache.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_datareset'));
$HTMLOUT = "";
//==delete torrents by putyn
function deletetorrent($tid)
{
    global $TRINITY20, $cache, $CURUSER, $lang, $cache_keys;
    sql_query("DELETE peers.*, files.*, comments.*, snatched.*, thanks.*, bookmarks.*, coins.*, rating.*,  torrents.* FROM torrents 
				 LEFT JOIN peers ON peers.torrent = torrents.id
				 LEFT JOIN files ON files.torrent = torrents.id
				 LEFT JOIN comments ON comments.torrent = torrents.id
				 LEFT JOIN thanks ON thanks.torrentid = torrents.id
				 LEFT JOIN bookmarks ON bookmarks.torrentid = torrents.id
				 LEFT JOIN coins ON coins.torrentid = torrents.id
				 LEFT JOIN rating ON rating.torrent = torrents.id
				 LEFT JOIN snatched ON snatched.torrentid = torrents.id
				 WHERE torrents.id =".sqlesc($tid)) || sqlerr(__FILE__, __LINE__);
    unlink("{$TRINITY20['torrent_dir']}/$id.torrent");
    $cache->delete($cache_keys['my_peers'].$CURUSER['id']);
}

function deletetorrent_xbt($tid)
{
    global $TRINITY20, $cache, $CURUSER, $lang, $cache_keys;
    sql_query("UPDATE torrents SET flags = 1 WHERE id = ".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    sql_query("DELETE files.*, comments.*, xbt_files_users.*, thanks.*, bookmarks.*, coins.*, rating.*,  torrents.* FROM torrents 
				 LEFT JOIN files ON files.torrent = torrents.id
				 LEFT JOIN comments ON comments.torrent = torrents.id
				 LEFT JOIN thanks ON thanks.torrentid = torrents.id
				 LEFT JOIN bookmarks ON bookmarks.torrentid = torrents.id
				 LEFT JOIN coins ON coins.torrentid = torrents.id
				 LEFT JOIN rating ON rating.torrent = torrents.id
				 LEFT JOIN xbt_files_users ON xbt_files_users.fid = torrents.id
				 WHERE torrents.id =".sqlesc($tid)." AND flags=1") || sqlerr(__FILE__, __LINE__);
    unlink("{$TRINITY20['torrent_dir']}/$id.torrent");
    $cache->delete($cache_keys['my_xbt_peers'].$CURUSER['id']);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tid = (isset($_POST["tid"]) ? 0 + $_POST["tid"] : 0);
    if ($tid == 0) {
        stderr($lang['datareset_stderr'], $lang['datareset_stderr1']);
    }
    if (get_row_count("torrents", "where id=".sqlesc($tid)) != 1) {
        stderr($lang['datareset_stderr'], $lang['datareset_stderr2']);
    }
    ($q1 = sql_query("SELECT s.downloaded as sd , t.id as tid, t.name,t.size, u.username,u.id as uid,u.downloaded as ud FROM torrents as t LEFT JOIN snatched as s ON s.torrentid = t.id LEFT JOIN users as u ON u.id = s.userid WHERE t.id =".sqlesc($tid))) || sqlerr(__FILE__,
        __LINE__);
    while ($a = $q1->fetch_assoc()) {
        $newd = ($a["ud"] > 0 ? $a["ud"] - $a["sd"] : 0);
        $new_download[] = "(".$a["uid"].",".$newd.")";
        $tname = htmlsafechars($a["name"]);
        $msg = $lang['datareset_hey'].htmlsafechars($a["username"])."\n";
        $msg .= $lang['datareset_looks'].htmlsafechars($a["name"]).$lang['datareset_nuked'];
        $msg .= $lang['datareset_down'].mksize($a["sd"]).$lang['datareset_downbe'].mksize($newd)."\n";
        $pms[] = "(0,".sqlesc($a["uid"]).",".TIME_NOW.",".sqlesc($msg).")";
        $cache->update_row($cache_keys['user_stats'].$a['uid'], [
            'downloaded' => $new_download,
        ], $TRINITY20['expires']['u_status']);
        $cache->update_row($cache_keys['user'].$a['uid'], [
            'downloaded' => $new_download,
        ], $TRINITY20['expires']['curuser']);
    }
    //==Send the pm !!
    sql_query("INSERT into messages (sender, receiver, added, msg) VALUES ".implode(",", array_map("$mysqli->real_escape_string", $pms))) || sqlerr(__FILE__, __LINE__);
    //==Update user download amount
    sql_query("INSERT INTO users (id,downloaded) VALUES ".implode(",",
            array_map("sqlesc", $new_download))." ON DUPLICATE key UPDATE downloaded=values(downloaded)") || sqlerr(__FILE__, __LINE__);
    if (XBT_TRACKER == true) {
        deletetorrent_xbt($tid);
    } else {
        deletetorrent($tid);
        remove_torrent_peers($tid);
    }
    write_log($lang['datareset_torr'].$tname.$lang['datareset_wdel'].htmlsafechars($CURUSER["username"]).$lang['datareset_allusr']);
    header("Refresh: 3; url=staffpanel.php?tool=datareset");
    stderr($lang['datareset_stderr'], $lang['datareset_pls']);
} else {
    $HTMLOUT .= "<div class='row'><div class='col-md-12'>";
    $HTMLOUT .= "<form action='staffpanel.php?tool=datareset&amp;action=datareset' method='post'>
	<fieldset>
	<legend>{$lang['datareset_reset']}</legend>
 <table class=table table-bordered'>
    	<tr><td align='right' nowrap='nowrap'>{$lang['datareset_tid']}</td><td align='left' width='100%'><input type='text' name='tid' size='20'></td></tr>
        <tr><td style='background:#990033; color:#CCCCCC;' colspan='2'>
        	<ul>
					<li>{$lang['datareset_tid_info']}</li>
					<li>{$lang['datareset_info']}</li>
					<li>{$lang['datareset_info1']}</b></li>
				</ul>
			</td></tr>
			<tr><td colspan='2' align='center'><input type='submit' value='{$lang['datareset_repay']}'></td></tr>
		</table>
	</fieldset>
	</form>";
    $HTMLOUT .= "</div></div>";
    echo stdhead($lang['datareset_stdhead']).$HTMLOUT.stdfoot();
}
?>
