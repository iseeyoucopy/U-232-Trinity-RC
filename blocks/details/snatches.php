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
    $What_cache = (XBT_TRACKER == true ? $cache_keys['snatched_tor_xbt'] : $cache_keys['snatched_tor']);
    $What_Value = (XBT_TRACKER == true ? 'WHERE completedtime != "0"' : 'WHERE complete_date != "0"');
    $Which_ID = (XBT_TRACKER == true ? 'tid' : 'id');
    $Which_T_ID = (XBT_TRACKER == true ? 'tid' : 'torrentid');
    $Which_Key_ID = (XBT_TRACKER == true ? $cache_keys['snatched_count_xbt'] : $cache_keys['snatched_count']);
    $cache_keys['Snatched_Count'] = $Which_Key_ID.$id;
    if (($Row_Count = $cache->get($cache_keys['Snatched_Count'])) === false) {
        ($Count_Q = sql_query("SELECT COUNT($Which_ID) FROM $What_Table $What_Value AND $Which_T_ID =".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
        $Row_Count = $Count_Q->fetch_row();
        $cache->set($cache_keys['Snatched_Count'], $Row_Count, $TRINITY20['expires']['details_snatchlist']);
    }
    $Count = $Row_Count[0];
    $perpage = 15;
    $pager = pager($perpage, $Count, "details.php?id=$id&amp;");
    $HTMLOUT .= "<div class='card'>
        <div class='card-divider'>{$lang['details_add_snatch1']}
        <a class='label' href='{$TRINITY20['baseurl']}/details.php?id=".(int)$torrents['id']."'>".htmlsafechars($torrents['name'])."</a>
        <span class='badge float-right'>{$Row_Count['0']}".($Row_Count[0] == 1 ? "" : "es")."</span>
    </div>
    </div>";

    if (($Detail_Snatch = $cache->get($What_cache.$id)) === false) {
        if (XBT_TRACKER == true) {
            //== \\0//
            ($Main_Q = sql_query("SELECT x.*, x.uid AS su, torrents.username as username1, users.username as username2, torrents.anonymous as anonymous1, users.anonymous as anonymous2, size, parked, warned, enabled, class, chatpost, leechwarn, donor, owner FROM xbt_peers AS x INNER JOIN users ON x.uid = users.id INNER JOIN torrents ON x.tid = torrents.id WHERE completedtime !=0 AND tid = ".sqlesc($id)." ORDER BY completedtime DESC ".$pager['limit'])) || sqlerr(__FILE__,
                __LINE__);
        } else {
            ($Main_Q = sql_query("SELECT s.*, s.userid AS su, torrents.username as username1, users.username as username2, torrents.anonymous as anonymous1, users.anonymous as anonymous2, size, parked, warned, enabled, class, chatpost, leechwarn, donor, timesann, owner FROM snatched AS s INNER JOIN users ON s.userid = users.id INNER JOIN torrents ON s.torrentid = torrents.id WHERE complete_date !=0 AND torrentid = ".sqlesc($id)." ORDER BY complete_date DESC ".$pager['limit'])) || sqlerr(__FILE__,
                __LINE__);
        }
        while ($snatched_torrent = $Main_Q->fetch_assoc()) {
            $Detail_Snatch = (array)$Detail_Snatch;
            $Detail_Snatch[] = $snatched_torrent;
        }
        $cache->set($What_cache.$id, $Detail_Snatch, $TRINITY20['expires']['details_snatchlist']);
    }

    if (($Detail_Snatch && (is_countable($Detail_Snatch) ? count($Detail_Snatch) : 0) > 0 && $CURUSER['class'] >= UC_STAFF)) {
        if ($Count > $perpage) {
            $HTMLOUT .= $pager['pagertop'];
        }
        //== \\0//
        if (XBT_TRACKER == true) {
            $snatched_torrent = "<thead>
                <tr>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_username']}'>
                            <i class='fas fa-user-tie'></i>
                        </span>
                    </th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_uploaded']}'>
                            <i class='fas fa-upload'></i>
                        </span>
                    </th>
                    ".(($TRINITY20['ratio_free'] == true) ? "" : "<th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_downloaded']}'>
                            <i class='fas fa-download'></i>
                        </span>
                    </th>")."
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_ratio']}'>
                            <i class='fas fa-percentage'></i>
                        </span>
                    </th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_seedtime']}'>
                            <i class='fas fa-hourglass-start'></i>
                        </span>
                    </th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_leechtime']}'>
                            <i class='fas fa-hourglass-end'></i>
                        </span>
                    </th>
                    <th>{$lang['details_snatches_lastaction']}</th>
                    <th>{$lang['details_snatches_completedat']}</th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_announced']}'>
                            <i class='fas fa-bullhorn'></i>
                        </span>
                    </th>
                    <th>{$lang['details_snatches_active']}</th>
                    <th>{$lang['details_snatches_completed']}</th>
                </tr>
            </thead>";
        } else {
            $snatched_torrent = "<thead>
                <tr>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_username']}'>
                            <i class='fas fa-user-tie'></i>
                        </span>
                    </th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_connectable']}'>
                            <i class='fas fa-check'></i>
                        </span>                        
                    </th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_uploaded']}'>
                            <i class='fas fa-upload'></i>
                        </span>
                    </th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_upspeed']}'>
                            <i class='fas fa-tachometer-alt'></i>
                        </span>
                    </th>
                    ".($TRINITY20['ratio_free'] ? "" : "<th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_downloaded']}'>
                            <i class='fas fa-download'></i>
                        </span>
                    </th>")."
                    ".($TRINITY20['ratio_free'] ? "" : "<th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_downspeed']}'>
                            <i class='fas fa-tachometer-alt'></i>
                        </span>
                    </th>")."
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_ratio']}'>
                            <i class='fas fa-percentage'></i>
                        </span>
                    </th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_completed']}'>
                            <i class='fas fa-spinner'></i>
                        </span>
                    </th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_seedtime']}'>
                            <i class='fas fa-hourglass-start'></i>
                        </span>
                    </th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_leechtime']}'>
                            <i class='fas fa-hourglass-end'></i>
                        </span>
                    </th>
                    <th>{$lang['details_snatches_lastaction']}</th>
                    <th>{$lang['details_snatches_completedat']}</th>
                    <th>{$lang['details_snatches_client']}</th>
                    <th>{$lang['details_snatches_port']}</th>
                    <th>
                        <span data-tooltip tabindex='3' title='{$lang['details_snatches_announced']}'>
                            <i class='fas fa-bullhorn'></i>
                        </span>
                    </th>
                </tr>
            </thead>";
        }
        if ($Detail_Snatch) {
            foreach ($Detail_Snatch as $D_S) {
                if (is_array($D_S)) {
                    if (XBT_TRACKER == true) {
                        //== \\0//
                        $ratio = ($D_S["downloaded"] > 0 ? number_format($D_S["uploaded"] / $D_S["downloaded"],
                            3) : ($D_S["uploaded"] > 0 ? "Inf." : "---"));
                        $active = ($D_S['active'] == 1 ? $active = "<img src='".$TRINITY20['pic_base_url']."aff_tick.gif' alt='Yes' title='Yes' />" : $active = "<img src='".$TRINITY20['pic_base_url']."aff_cross.gif' alt='No' title='No' />");
                        $completed = ($D_S['completed'] >= 1 ? $completed = "<img src='".$TRINITY20['pic_base_url']."aff_tick.gif' alt='Yes' title='Yes' />" : $completed = "<img src='".$TRINITY20['pic_base_url']."aff_cross.gif' alt='No' title='No' />");
                        $snatchuserxbt = (isset($D_S['username2']) ? ("<a href='userdetails.php?id=".(int)$D_S['uid']."'><strong>".htmlsafechars($D_S['username2'])."</strong></a>") : "{$lang['details_snatches_unknown']}");
                        $username_xbt = (($D_S['anonymous2'] == 'yes') ? ($CURUSER['class'] < UC_STAFF && $D_S['uid'] != $CURUSER['id'] ? '' : $snatchuserxbt.' - ')."<i>{$lang['details_snatches_anon']}</i>" : $snatchuserxbt);
                        $snatched_torrent .= "<tbody>
                            <tr>
                                <td>{$username_xbt}</td>
                                <td>".mksize($D_S["uploaded"])."</td>
                                ".($TRINITY20['ratio_free'] ? "" : "<td>".mksize($D_S["downloaded"])."</td>")."
                                <td>".htmlsafechars($ratio)."</td>
                                <td>".mkprettytime($D_S["seedtime"])."</td>
                                <td>".mkprettytime($D_S["leechtime"])."</td>
                                <td>".get_date($D_S["mtime"], '', 0, 1)."</td>
                                <td>".get_date($D_S["completedtime"], '', 0, 1)."</td>
                                <td>".(int)$D_S["announced"]."</td>
                                <td>".$active."</td>
                                <td>".$completed."</td>
                            </tr>
                        </tbody>";

                    } else {
                        $upspeed = ($D_S["upspeed"] > 0 ? mksize($D_S["upspeed"]) : ($D_S["seedtime"] > 0 ? mksize($D_S["uploaded"] / ($D_S["seedtime"] + $D_S["leechtime"])) : mksize(0)));
                        $downspeed = ($D_S["downspeed"] > 0 ? mksize($D_S["downspeed"]) : ($D_S["leechtime"] > 0 ? mksize($D_S["downloaded"] / $D_S["leechtime"]) : mksize(0)));
                        $ratio = ($D_S["downloaded"] > 0 ? number_format($D_S["uploaded"] / $D_S["downloaded"],
                            3) : ($D_S["uploaded"] > 0 ? "Inf." : "---"));
                        $ds_size = isset($D_S["size"]) ? $D_S["size"] : 1;
                        $completed = sprintf("%.2f%%", 100 * (1 - ($D_S["to_go"] / $ds_size)));
                        $snatchuser = (isset($D_S['username2']) ? ("<a href='userdetails.php?id=".(int)$D_S['userid']."'><strong>".htmlsafechars($D_S['username2'])."</strong></a>") : "{$lang['details_snatches_unknown']}");
                        $username = (($D_S['anonymous2'] == 'yes') ? ($CURUSER['class'] < UC_STAFF && $D_S['userid'] != $CURUSER['id'] ? '' : $snatchuser.' - ')."<i>{$lang['details_snatches_anon']}</i>" : $snatchuser);
                        $snatched_torrent .= "<tbody>
                            <tr>
                                <td>{$username}</td>
                                <td>".($D_S["connectable"] == "yes" ? "<font color='green'>{$lang['details_add_yes']}" : "<font color='red'>{$lang['details_add_no']}")."</td>
                                <td>".mksize($D_S["uploaded"])."</td>
                                <td>".htmlsafechars($upspeed)."/s</td>
                                ".($TRINITY20['ratio_free'] ? "" : "<td>".mksize($D_S["downloaded"])."</td>")."
                                ".($TRINITY20['ratio_free'] ? "" : "<td>".htmlsafechars($downspeed)."/s</td>")."
                                <td>".htmlsafechars($ratio)."</td>
                                <td>".htmlsafechars($completed)."</td>
                                <td>".mkprettytime($D_S["seedtime"])."</td>
                                <td>".mkprettytime($D_S["leechtime"])."</td>
                                <td>".get_date($D_S["last_action"], '', 0, 1)."</td>
                                <td>".get_date($D_S["complete_date"], '', 0, 1)."</td>
                                <td>".htmlsafechars($D_S["agent"])."</td>
                                <td>".(int)$D_S["port"]."</td>
                                <td>".(int)$D_S["timesann"]."</td>
                            </tr>
                        </tbody>";
                    }
                }
            }
            $HTMLOUT .= "<div class='card-section table-scroll'>
                <table class='striped'>$snatched_torrent</table></div>";
        } elseif (empty($Detail_Snatch)) {
            $HTMLOUT .= "<p class='text-center'>{$lang['details_add_snatch4']}</p>
                <p class=text-center'>{$lang['details_add_snatch5']}</p>";
        }
    }
    $HTMLOUT .= "";
}