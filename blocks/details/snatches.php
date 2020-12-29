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
if ($CURUSER['class'] >= UC_POWER_USER) { 
//== Snatched Torrents mod
$What_Table = (XBT_TRACKER == true ? 'xbt_peers' : 'snatched');
$What_cache = (XBT_TRACKER == true ? 'snatched_tor_xbt_' : 'snatched_tor_');
$What_Value = (XBT_TRACKER == true ? 'WHERE completedtime != "0"' : 'WHERE complete_date != "0"');
$Which_ID = (XBT_TRACKER == true ? 'tid' : 'id');
$Which_T_ID = (XBT_TRACKER == true ? 'tid' : 'torrentid');
$Which_Key_ID = (XBT_TRACKER == true ? 'snatched_count_xbt_' : 'snatched_count_');
$keys['Snatched_Count'] = $Which_Key_ID . $id;

    if (($Row_Count = $cache->get($keys['Snatched_Count'])) === false) {
$Count_Q = sql_query("SELECT COUNT($Which_ID) FROM $What_Table $What_Value AND $Which_T_ID =" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$Row_Count = mysqli_fetch_row($Count_Q);
$cache->set($keys['Snatched_Count'], $Row_Count, $INSTALLER09['expires']['details_snatchlist']);
}
$Count = $Row_Count[0];
$perpage = 15;
$pager = pager($perpage, $Count, "details.php?id=$id&amp;");
$HTMLOUT.= "<div class='callout'>{$lang['details_add_snatch1']}<a href='{$INSTALLER09['baseurl']}/details.php?id=" . (int)$torrents['id'] . "'>" . htmlsafechars($torrents['name']) . "</a>
<span class='badge float-right'>{$Row_Count['0']}" . ($Row_Count[0] == 1 ? "" : "es") . "</span></div>";

if (($Detail_Snatch = $cache->get($What_cache . $id)) === false) {
    if (XBT_TRACKER == true) {
     //== \\0//
      $Main_Q = sql_query("SELECT x.*, x.uid AS su, torrents.username as username1, users.username as username2, torrents.anonymous as anonymous1, users.anonymous as anonymous2, size, parked, warned, enabled, class, chatpost, leechwarn, donor, owner FROM xbt_peers AS x INNER JOIN users ON x.uid = users.id INNER JOIN torrents ON x.tid = torrents.id WHERE completedtime !=0 AND tid = " . sqlesc($id) . " ORDER BY completedtime DESC " . $pager['limit']) or sqlerr(__FILE__, __LINE__);
} else {
      $Main_Q = sql_query("SELECT s.*, s.userid AS su, torrents.username as username1, users.username as username2, torrents.anonymous as anonymous1, users.anonymous as anonymous2, size, parked, warned, enabled, class, chatpost, leechwarn, donor, timesann, owner FROM snatched AS s INNER JOIN users ON s.userid = users.id INNER JOIN torrents ON s.torrentid = torrents.id WHERE complete_date !=0 AND torrentid = " . sqlesc($id) . " ORDER BY complete_date DESC " . $pager['limit']) or sqlerr(__FILE__, __LINE__);
}
    while ($snatched_torrent = mysqli_fetch_assoc($Main_Q)) $Detail_Snatch[] = $snatched_torrent;
    $cache->set($What_cache . $id, $Detail_Snatch, $INSTALLER09['expires']['details_snatchlist']);
}

if (($Detail_Snatch && count($Detail_Snatch) > 0 && $CURUSER['class'] >= UC_STAFF)) {
    if ($Count > $perpage) 
		$HTMLOUT.= $pager['pagertop'];
 //== \\0//
 if (XBT_TRACKER == true) {
    $snatched_torrent = "
<thead>
<tr>
<th>{$lang['details_snatches_username']}</th>
<th>{$lang['details_snatches_uploaded']}</th>
" . ($INSTALLER09['ratio_free'] ? "" : "<th>{$lang['details_snatches_downloaded']}</th>") . "
<th>{$lang['details_snatches_ratio']}</th>
<th>{$lang['details_snatches_seedtime']}</th>
<th>{$lang['details_snatches_leechtime']}</th>
<th>{$lang['details_snatches_lastaction']}</th>
<th>{$lang['details_snatches_completedat']}</th>
<th>{$lang['details_snatches_announced']}</th>
<th>{$lang['details_snatches_active']}</th>
<th>{$lang['details_snatches_completed']}</th>
</tr>
</thead>";
    } 
	else 
	{
    $snatched_torrent = "
<table class='striped'>
<thead>
<tr>
<th class='text-center'><i class='fas fa-user-tie' title='{$lang['details_snatches_username']}'></i></th>
<th><i class='fas fa-check' title='{$lang['details_snatches_connectable']}'></i></th>
<th><i class='fas fa-upload' title='{$lang['details_snatches_uploaded']}'></i></th>
<th><i class='fas fa-tachometer-alt' title='{$lang['details_snatches_upspeed']}'></i></th>
" . ($INSTALLER09['ratio_free'] ? "" : "<th><i class='fas fa-download' title='{$lang['details_snatches_downloaded']}'></i></th>") . "
" . ($INSTALLER09['ratio_free'] ? "" : "<th><i class='fas fa-tachometer-alt' title='{$lang['details_snatches_downspeed']}'></i></th>") . "
<th><i class='fa fa-percentage' title='{$lang['details_snatches_ratio']}'></i></th>
<th class='text-center'><i class='fas fa-spinner' title='{$lang['details_snatches_completed']}'></i></th>
<th class='text-center'><i class='fas fa-hourglass-start' title='{$lang['details_snatches_seedtime']}'></i></th>
<th class='text-center'><i class='fas fa-hourglass-end' title='{$lang['details_snatches_leechtime']}'></i></th>
<th>{$lang['details_snatches_lastaction']}</th>
<th>{$lang['details_snatches_completedat']}</th>
<th>{$lang['details_snatches_client']}</th>
<th>{$lang['details_snatches_port']}</th>
<th><i class='fas fa-bullhorn' title='{$lang['details_snatches_announced']}'></i></th>
</tr>
</thead>";
}
if ($Detail_Snatch) {
        foreach ($Detail_Snatch as $D_S) {
          
if (XBT_TRACKER == true) {
           //== \\0//
           $ratio = ($D_S["downloaded"] > 0 ? number_format($D_S["uploaded"] / $D_S["downloaded"], 3) : ($D_S["uploaded"] > 0 ? "Inf." : "---"));
           $active = ($D_S['active'] == 1 ? $active = "<img src='" . $INSTALLER09['pic_base_url'] . "aff_tick.gif' alt='Yes' title='Yes' />" : $active = "<img src='" . $INSTALLER09['pic_base_url'] . "aff_cross.gif' alt='No' title='No' />");
           $completed = ($D_S['completed'] >= 1 ? $completed = "<img src='" . $INSTALLER09['pic_base_url'] . "aff_tick.gif' alt='Yes' title='Yes' />" : $completed = "<img src='" . $INSTALLER09['pic_base_url'] . "aff_cross.gif' alt='No' title='No' />");
           $snatchuserxbt = (isset($D_S['username2']) ? ("<a href='userdetails.php?id=" . (int)$D_S['uid'] . "'><b>" . htmlsafechars($D_S['username2']) . "</b></a>") : "{$lang['details_snatches_unknown']}");
           $username_xbt = (($D_S['anonymous2'] == 'yes') ? ($CURUSER['class'] < UC_STAFF && $D_S['uid'] != $CURUSER['id'] ? '' : $snatchuserxbt . ' - ') . "<i>{$lang['details_snatches_anon']}</i>" : $snatchuserxbt);
           $snatched_torrent.= "
		   <tbody><tr>
                                 <td><font size='2%'>{$username_xbt}</font></td>
                                 <td><font size='2%'>" . mksize($D_S["uploaded"]) . "</font></td>
  " . ($INSTALLER09['ratio_free'] ? "" : "<td><font size='2%'>" . mksize($D_S["downloaded"]) . "</font></td>") . "
                                 <td><font size='2%'>" . htmlsafechars($ratio) . "</font></td>
                                 <td><font size='2%'>" . mkprettytime($D_S["seedtime"]) . "</font></td>
                                 <td><font size='2%'>" . mkprettytime($D_S["leechtime"]) . "</font></td>
                                 <td><font size='2%'>" . get_date($D_S["mtime"], '', 0, 1) . "</font></td>
                                 <td><font size='2%'>" . get_date($D_S["completedtime"], '', 0, 1) . "</font></td>
                                 <td><font size='2%'>" . (int)$D_S["announced"] . "</font></td>
                                 <td><font size='2%'>" . $active . "</font></td>
                                 <td><font size='2%'>" . $completed . "</font></td>
        </tr>
		</tbody>";

} 
else {
 $upspeed = ($D_S["upspeed"] > 0 ? mksize($D_S["upspeed"]) : ($D_S["seedtime"] > 0 ? mksize($D_S["uploaded"] / ($D_S["seedtime"] + $D_S["leechtime"])) : mksize(0)));
           $downspeed = ($D_S["downspeed"] > 0 ? mksize($D_S["downspeed"]) : ($D_S["leechtime"] > 0 ? mksize($D_S["downloaded"] / $D_S["leechtime"]) : mksize(0)));
    $ratio = ($D_S["downloaded"] > 0 ? number_format($D_S["uploaded"] / $D_S["downloaded"], 3) : ($D_S["uploaded"] > 0 ? "Inf." : "---"));
           $completed = sprintf("%.2f%%", 100 * (1 - ($D_S["to_go"] / $D_S["size"])));
           $snatchuser = (isset($D_S['username2']) ? ("<a href='userdetails.php?id=" . (int)$D_S['userid'] . "'><b>" . htmlsafechars($D_S['username2']) . "</b></a>") : "{$lang['details_snatches_unknown']}");
           $username = (($D_S['anonymous2'] == 'yes') ? ($CURUSER['class'] < UC_STAFF && $D_S['userid'] != $CURUSER['id'] ? '' : $snatchuser . ' - ') . "<i>{$lang['details_snatches_anon']}</i>" : $snatchuser);
$snatched_torrent.= "<tbody><tr>
                                 <td><font size='2%'>{$username}</font></td>
                                 <td><font size='2%'>" . ($D_S["connectable"] == "yes" ? "<font color='green'>{$lang['details_add_yes']}</font>" : "<font color='red'>{$lang['details_add_no']}</font>") . "</font></td>
                                 <td><font size='2%'>" . mksize($D_S["uploaded"]) . "</font></td>
                                 <td><font size='2%'>" . htmlsafechars($upspeed) . "/s</font></td>
  " . ($INSTALLER09['ratio_free'] ? "" : "<td><font size='2%'>" . mksize($D_S["downloaded"]) . "</font></td>") . "
  " . ($INSTALLER09['ratio_free'] ? "" : "<td><font size='2%'>" . htmlsafechars($downspeed) . "/s</font></td>") . "
                                 <td><font size='2%'>" . htmlsafechars($ratio) . "</font></td>
                                 <td><font size='2%'>" . htmlsafechars($completed) . "</font></td>
                                <td><font size='2%'>" . mkprettytime($D_S["seedtime"]) . "</font></td>
                                <td><font size='2%'>" . mkprettytime($D_S["leechtime"]) . "</font></td>
                                 <td><font size='2%'>" . get_date($D_S["last_action"], '', 0, 1) . "</font></td>
                                 <td><font size='2%'>" . get_date($D_S["complete_date"], '', 0, 1) . "</font></td>
                                 <td><font size='2%'>" . htmlsafechars($D_S["agent"]) . "</font></td>
                                <td><font size='2%'>" . (int)$D_S["port"] . "</font></td>
                                 <td><font size='2%'>" . (int)$D_S["timesann"] . "</font></td>
        </tr></tbody>";
        }

}

$snatched_torrent.= "</table>";
$HTMLOUT.= "<div class='card-section table-scroll'>$snatched_torrent</div>";
} else
{
 if (empty($Detail_Snatch)) $HTMLOUT.= "<p class='text-center'>{$lang['details_add_snatch4']}</p>
<p class=text-center'>{$lang['details_add_snatch5']}</p>";
   }
}
$HTMLOUT .="";
}
?>