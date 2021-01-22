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
require_once (INCL_DIR . 'bt_client_functions.php');
require_once (INCL_DIR . 'html_functions.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('peerlist'));
$id = (int)$_GET['id'];
if (!isset($id) || !is_valid_id($id)) stderr($lang['peerslist_user_error'], $lang['peerslist_invalid_id']);
$HTMLOUT = '';
function dltable($name, $arr, $torrent)
{
    global $CURUSER, $lang, $TRINITY20;
    $htmlout = '';
    if (!count($arr)) return $htmlout = "
        <p>{$lang['peerslist_no']} $name {$lang['peerslist_data_available']}</p>";
    $htmlout.= "
    <table class='striped'>
    <tr><td colspan='11' class='text-left'>" . count($arr) . " $name</td></tr>
    <tr><td class='text-center'><i class='fas fa-user' title='{$lang['peerslist_user_ip']}'></i></td>
    <td class='text-center'><i class='fas fa-link' title='{$lang['peerslist_connectable']}'></i></td>
    <td class='text-center'><i class='fas fa-upload' title='{$lang['peerslist_uploaded']}'></i></td>
    <td class='text-center'><i class='fas fa-tachometer-alt' title='{$lang['peerslist_urate']}'></i></td>
    " . ($TRINITY20['ratio_free'] ? "" : "<td class='text-center'><i class='fas fa-download' title='{$lang['peerslist_downloaded']}'></i></td>") . "
    " . ($TRINITY20['ratio_free'] ? "" : "<td class='text-center'><i class='fas fa-tachometer-alt' title='{$lang['peerslist_drate']}'></i></td>") . "
    <td class='text-center'><i class='fa fa-percentage' title='{$lang['peerslist_ratio']}'></i></td>
    <td class='text-center'>{$lang['peerslist_complete']}</td>
    <td class='text-center'><i class='fas fa-user-clock' title='{$lang['peerslist_connected']}'></i></td>
    <td class='text-center'>{$lang['peerslist_idle']}</td>
    <td class='text-center'>{$lang['peerslist_client']}</td></tr>";
    $now = TIME_NOW;
    $mod = $CURUSER['class'] >= UC_STAFF;
    foreach ($arr as $e) {
        $htmlout.= "<tr>";
        $htmlout.= (($e['tanonymous'] == 'yes' && $e['owner'] == $e['userid'] || $e['anonymous'] == 'yes' && $CURUSER['id'] != $e['userid']) && $CURUSER['class'] < UC_STAFF) ? "<td class='text-left'><b>Kezer Soze</b></td>" : "<td class='text-left'><a href='userdetails.php?id=" . (int)$e['userid'] . "'><b>" . htmlsafechars($e['username']) . "</b></a>" . ($mod ? "<br />({$e["ip"]})" : "<br />(".preg_replace('~(\d+)\.(\d+)\.(\d+)\.(\d+)~', "$1.xxx.$3.xxx", $e["ip"]).")") . "</td>";
        $secs = max(1, ($now - $e["st"]) - ($now - $e["la"]));
        $htmlout.= "<td align='center'>" . ($e['connectable'] == "yes" ? "{$lang['peerslist_yes']}" : "<font color='red'>{$lang['peerslist_no']}</font>") . "</td>";
        $htmlout.= "<td class='text-center'>" . mksize($e["uploaded"]) . "</td>";
        $htmlout.= "<td class='text-center' style='white-space: nowrap;'>" . mksize(($e["uploaded"] - $e["uploadoffset"]) / $secs) . "/s</td>";
        $htmlout.= $TRINITY20['ratio_free'] ? "" : "<td class='text-center'>" . mksize($e["downloaded"]) . "</td>";
        $htmlout.= $TRINITY20['ratio_free'] ? "" : "<td class='text-center' style='white-space: nowrap;'>". ($e["seeder"] == "no" ? (mksize(($e["downloaded"] - $e["downloadoffset"]) / $secs) . "/s") : (mksize(($e["downloaded"] - $e["downloadoffset"]) / max(1, $e["finishedat"] - $e['st'])) . "/s"))."</td>";
        $htmlout.= "<td class='text-center'>" . member_ratio($e['uploaded'], $TRINITY20['ratio_free'] ? "0" : $e['downloaded']) . "</td>";
        $htmlout.= "<td class='text-center'>" . sprintf("%.2f%%", 100 * (1 - ($e["to_go"] / $torrent["size"]))) . "</td>";
        $htmlout.= "<td class='text-center'>" . mkprettytime($now - $e["st"]) . "</td>";
        $htmlout.= "<td class='text-center'>" . mkprettytime($now - $e["la"]) . "</td>";
        $htmlout.= "<td class='text-center'>".htmlsafechars(getagent($e["agent"], $e['peer_id']))."</td>";
        $htmlout.= "</tr>";
    }
    $htmlout.= "</table>";
    return $htmlout;
}
$res = sql_query("SELECT * FROM torrents WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
if ($res->num_rows == 0) stderr("{$lang['peerslist_error']}", "{$lang['peerslist_nothing']}");
$row = $res->fetch_assoc();
$downloaders = array();
$seeders = array();
$subres = sql_query("SELECT u.username, u.anonymous, t.owner, t.anonymous as tanonymous, p.seeder, p.finishedat, p.downloadoffset, p.uploadoffset, p.ip, p.port, p.uploaded, p.downloaded, p.to_go, p.started AS st, p.connectable, p.agent, p.last_action AS la, p.userid, p.peer_id
    FROM peers p
    LEFT JOIN users u ON p.userid = u.id
	LEFT JOIN torrents as t on t.id = p.torrent
    WHERE p.torrent = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
if (mysqli_num_rows($subres) == 0) stderr("{$lang['peerslist_warning']}", "{$lang['peerslist_no_data']}");
while ($subrow = $subres->fetch_assoc()) {
    if ($subrow["seeder"] == "yes") $seeders[] = $subrow;
    else $downloaders[] = $subrow;
}
function leech_sort($a, $b)
{
    if (isset($_GET["usort"])) return seed_sort($a, $b);
    $x = $a["to_go"];
    $y = $b["to_go"];
    if ($x == $y) return 0;
    if ($x < $y) return -1;
    return 1;
}
function seed_sort($a, $b)
{
    $x = $a["uploaded"];
    $y = $b["uploaded"];
    if ($x == $y) return 0;
    if ($x < $y) return 1;
    return -1;
}
usort($seeders, "seed_sort");
usort($downloaders, "leech_sort");
$HTMLOUT.= "
    <div class='card'>
        <div class='card-divider'>
            <p>Peerlist for Torrent <a href='{$TRINITY20['baseurl']}/details.php?id=$id'><span class='label secondary'>" . htmlsafechars($row['name']) . "</span></a></p>
        </div>
        <div class='card-section'>
            <div class='table-scroll'>";
                $HTMLOUT.= dltable("{$lang['peerslist_seeders']}<a name='seeders'></a>", $seeders, $row);
                $HTMLOUT.= dltable("{$lang['peerslist_leechers']}<a name='leechers'></a>", $downloaders, $row);
                $HTMLOUT."
            </div>
        </div>
    </div>";
echo stdhead("{$lang['peerslist_stdhead']}") . $HTMLOUT . stdfoot();
?>