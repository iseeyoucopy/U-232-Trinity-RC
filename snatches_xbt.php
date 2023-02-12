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
require_once INCL_DIR.'pager_functions.php';
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('snatches'));
$HTMLOUT = "";
if (empty($_GET['id'])) {
    $HTMLOUT = '';
    $HTMLOUT .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:18px;color:black;background-color:red;text-align:center;'>Incorrect access<br>Silly Rabbit - Trix are for kids.. Snatches must be accessed using a valid id !</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
$id = 0 + $_GET["id"];
if (!is_valid_id($id)) {
    stderr("Error", "It appears that you have entered an invalid id.");
}
($res = sql_query("SELECT id, name FROM torrents WHERE id = ".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
$arr = $res->fetch_assoc();
if (!$arr) {
    stderr("Error", "It appears that there is no torrent with that id.");
}
($res = sql_query("SELECT COUNT(tid) FROM xbt_peers WHERE completedtime !=0 AND tid =".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
$row = $res->fetch_row();
$count = $row[0];
$perpage = 15;
$pager = pager($perpage, $count, "snatches.php?id=$id&amp;");
if (!$count) {
    stderr("No snatches",
        "It appears that there are currently no snatches for the torrent <a href='details.php?id=".(int)$arr['id']."'>".htmlsafechars($arr['name'])."</a>.");
}
$HTMLOUT .= "<h1>Snatches for torrent <a href='{$TRINITY20['baseurl']}/details.php?id=".(int)$arr['id']."'>".htmlsafechars($arr['name'])."</a></h1>\n";
$HTMLOUT .= "<h2>Currently {$row['0']} snatch".($row[0] == 1 ? "" : "es")."</h2>\n";
if ($count > $perpage) {
    $HTMLOUT .= $pager['pagertop'];
}
$HTMLOUT .= "<table class='table table-bordered'>
<tr>
<td class='colhead' align='left'>{$lang['snatches_username']}</td>
<td class='colhead' align='right'>{$lang['snatches_uploaded']}</td>
".($TRINITY20['ratio_free'] ? "" : "<td class='colhead' align='right'>{$lang['snatches_downloaded']}</td>")."
<td class='colhead' align='right'>{$lang['snatches_ratio']}</td>
<td class='colhead' align='right'>{$lang['snatches_seedtime']}</td>
<td class='colhead' align='right'>{$lang['snatches_leechtime']}</td>
<td class='colhead' align='center'>{$lang['snatches_lastaction']}</td>
<td class='colhead' align='center'>{$lang['snatches_announced']}</td>
<td class='colhead' align='center'>Active</td>
<td class='colhead' align='right'>{$lang['snatches_completed']}</td>
</tr>\n";
($res = sql_query("SELECT x.*, x.uid AS xu, torrents.username as username1, users.username as username2, torrents.anonymous as anonymous1, users.anonymous as anonymous2, size, parked, warned, enabled, class, chatpost, leechwarn, donor, uid FROM xbt_peers AS x INNER JOIN users ON x.uid = users.id INNER JOIN torrents ON x.tid = torrents.id WHERE tid = ".sqlesc($id)." AND completedtime !=0 ORDER BY tid DESC ".$pager['limit'])) || sqlerr(__FILE__,
    __LINE__);
while ($arr = $res->fetch_assoc()) {
    $ratio = ($arr["downloaded"] > 0 ? number_format($arr["uploaded"] / $arr["downloaded"], 3) : ($arr["uploaded"] > 0 ? "Inf." : "---"));
    $active = ($arr['active'] == 1 ? $active = "<img src='".$TRINITY20['pic_base_url']."aff_tick.gif' alt='Yes' title='Yes'>" : $active = "<img src='".$TRINITY20['pic_base_url']."aff_cross.gif' alt='No' title='No'>");
    $completed = ($arr['completed'] >= 1 ? $completed = "<img src='".$TRINITY20['pic_base_url']."aff_tick.gif' alt='Yes' title='Yes'>" : $completed = "<img src='".$TRINITY20['pic_base_url']."aff_cross.gif' alt='No' title='No'>");
    $snatchuser = (isset($arr['username2']) ? ("<a href='userdetails.php?id=".(int)$arr['uid']."'><b>".htmlsafechars($arr['username2'])."</b></a>") : "{$lang['snatches_unknown']}");
    $username = (($arr['anonymous2'] == 'yes') ? ($CURUSER['class'] < UC_STAFF && $arr['uid'] != $CURUSER['id'] ? '' : $snatchuser.' - ')."<i>{$lang['snatches_anon']}</i>" : $snatchuser);
    $HTMLOUT .= "<tr>
  <td align='left'>{$username}</td>
  <td align='right'>".mksize($arr["uploaded"])."</td>
  ".($TRINITY20['ratio_free'] ? "" : "<td align='right'>".mksize($arr["downloaded"])."</td>")."
  <td align='right'>".htmlsafechars($ratio)."</td>
  <td align='right'>".mkprettytime($arr["seedtime"])."</td>
  <td align='right'>".mkprettytime($arr["leechtime"])."</td>
  <td align='right'>".get_date($arr["mtime"], '', 0, 1)."</td>
  <td align='right'>".(int)$arr["announced"]."</td>
  <td align='center'>".$active."</td>
  <td align='center'>".$completed."</td>
  </tr>\n";
}
$HTMLOUT .= "</table>\n";
if ($count > $perpage) {
    $HTMLOUT .= $pager['pagerbottom'];
}
echo stdhead('Snatches').$HTMLOUT.stdfoot();
die;
?>
