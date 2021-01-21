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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once INCL_DIR . 'pager_functions.php';
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('snatches'));
$HTMLOUT = "";
if (empty($_GET['id'])) {
    $HTMLOUT = '';
    $HTMLOUT.= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:18px;color:black;background-color:red;text-align:center;'>Incorrect access<br />Silly Rabbit - Trix are for kids.. Snatches must be accessed using a valid id !</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
$id = 0 + $_GET["id"];
if (!is_valid_id($id)) stderr("Error", "It appears that you have entered an invalid id.");
$res = sql_query("SELECT id, name FROM torrents WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$arr = $res->fetch_assoc();
if (!$arr) stderr("Error", "It appears that there is no torrent with that id.");
$res = sql_query("SELECT COUNT(id) FROM snatched WHERE complete_date !=0 AND torrentid =" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysqli_fetch_row($res);
$count = $row[0];
$perpage = 15;
$pager = pager($perpage, $count, "snatches.php?id=$id&amp;");
if (!$count) stderr("No snatches", "It appears that there are currently no snatches for the torrent <a href='details.php?id=" . (int)$arr['id'] . "'>" . htmlsafechars($arr['name']) . "</a>.");
$HTMLOUT.= "
<div class='grid-x grid-paddinx-x'>
  <div class='card'>
    <div class='card-divider'>
      <p>Snatches for torrent <a href='{$TRINITY20['baseurl']}/details.php?id=" . (int)$arr['id'] . "'><span class='label secondary'>" . htmlsafechars($arr['name']) . "</span></a></p>
    </div>
    <div class='card-section'>
    <span class='label'>Currently <span class='badge success'>{$row['0']}</span> snatch" . ($row[0] == 1 ? "" : "es") . "</span>";
if ($count > $perpage) $HTMLOUT.= $pager['pagertop'];
$HTMLOUT.= "
<div class='table-scroll'>
<table class='striped'>
<tr>
<td class='text-center'>{$lang['snatches_username']}</td>
<td class='text-center'><i class='fas fa-link' title='{$lang['snatches_connectable']}'></i></td>
<td class='text-center'><i class='fas fa-upload' title='{$lang['snatches_uploaded']}'></i></td>
<td class='text-center'><i class='fas fa-tachometer-alt' title='{$lang['snatches_upspeed']}'></i></td>
" . ($TRINITY20['ratio_free'] ? "" : "<td class='text-center'><i class='fas fa-download' title='{$lang['snatches_downloaded']}'></i></td>") . "
" . ($TRINITY20['ratio_free'] ? "" : "<td class='text-center'><i class='fas fa-tachometer-alt' title='{$lang['snatches_downspeed']}'></i></td>") . "
<td class='text-center'><i class='fa fa-percentage' title='{$lang['snatches_ratio']}'></i></td>
<td class='text-center'>{$lang['snatches_completed']}</td>
<td class='text-center'><i class='fas fa-user-clock' title='{$lang['snatches_seedtime']}'></i></td>
<td class='text-center'><i class='fas fa-user-clock' title='{$lang['snatches_leechtime']}'></i></td>
<td class='text-center'>{$lang['snatches_lastaction']}</td>
<td class='text-center'>{$lang['snatches_completedat']}</td>
<td class='text-center'><i class='fab fa-app-store-ios' title='{$lang['snatches_client']}'></i></td>
<td class='text-center'>{$lang['snatches_port']}</td>
<td class='text-center'><i class='fa fa-bullhorn' title='{$lang['snatches_announced']}'></i></td>
</tr>";
$res = sql_query("SELECT s.*, s.userid AS su, torrents.username as username1, users.username as username2, torrents.anonymous as anonymous1, users.anonymous as anonymous2, size, parked, warned, enabled, class, chatpost, leechwarn, donor, timesann, owner FROM snatched AS s INNER JOIN users ON s.userid = users.id INNER JOIN torrents ON s.torrentid = torrents.id WHERE complete_date !=0 AND torrentid = " . sqlesc($id) . " ORDER BY complete_date DESC " . $pager['limit']) or sqlerr(__FILE__, __LINE__);
while ($arr = $res->fetch_assoc()) {
    $upspeed = ($arr["upspeed"] > 0 ? mksize($arr["upspeed"]) : ($arr["seedtime"] > 0 ? mksize($arr["uploaded"] / ($arr["seedtime"] + $arr["leechtime"])) : mksize(0)));
    $downspeed = ($arr["downspeed"] > 0 ? mksize($arr["downspeed"]) : ($arr["leechtime"] > 0 ? mksize($arr["downloaded"] / $arr["leechtime"]) : mksize(0)));
    $ratio = ($arr["downloaded"] > 0 ? number_format($arr["uploaded"] / $arr["downloaded"], 3) : ($arr["uploaded"] > 0 ? "Inf." : "---"));
    $completed = sprintf("%.2f%%", 100 * (1 - ($arr["to_go"] / $arr["size"])));
    $snatchuser = (isset($arr['username2']) ? ("<a href='userdetails.php?id=" . (int)$arr['userid'] . "'><b>" . htmlsafechars($arr['username2']) . "</b></a>") : "{$lang['snatches_unknown']}");
    $username = (($arr['anonymous2'] == 'yes') ? ($CURUSER['class'] < UC_STAFF && $arr['userid'] != $CURUSER['id'] ? '' : $snatchuser . ' - ') . "<i>{$lang['snatches_anon']}</i>" : $snatchuser);
    //if($arr['owner'] != $arr['su']){
    $HTMLOUT.= "<tr>
  <td align='left'>{$username}</td>
  <td class='text-center'>" . ($arr["connectable"] == "yes" ? "<font color='green'>Yes</font>" : "<font color='red'>No</font>") . "</td>
  <td class='text-center'>" . mksize($arr["uploaded"]) . "</td>
  <td class='text-center'>" . htmlsafechars($upspeed) . "/s</td>
  " . ($TRINITY20['ratio_free'] ? "" : "<td class='text-center'>" . mksize($arr["downloaded"]) . "</td>") . "
  " . ($TRINITY20['ratio_free'] ? "" : "<td class='text-center'>" . htmlsafechars($downspeed) . "/s</td>") . "
  <td class='text-center'>" . htmlsafechars($ratio) . "</td>
  <td class='text-center'>" . htmlsafechars($completed) . "</td>
  <td class='text-center'>" . mkprettytime($arr["seedtime"]) . "</td>
  <td class='text-center'>" . mkprettytime($arr["leechtime"]) . "</td>
  <td class='text-center'>" . get_date($arr["last_action"], '', 0, 1) . "</td>
  <td class='text-center'>" . get_date($arr["complete_date"], '', 0, 1) . "</td>
  <td class='text-center'>" . htmlsafechars($arr["agent"]) . "</td>
  <td class='text-center'>" . (int)$arr["port"] . "</td>
  <td class='text-center'>" . (int)$arr["timesann"] . "</td>
  </tr>";
}
//}
$HTMLOUT.= "</table></div>
</div></div></div>";
if ($count > $perpage) $HTMLOUT.= $pager['pagerbottom'];
echo stdhead('Snatches') . $HTMLOUT . stdfoot();
die;
?>
