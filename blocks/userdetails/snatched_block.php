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
$count_snatched = 0;
function snatchtable($res)
{
    global $TRINITY20, $lang, $CURUSER;
    $htmlout = '';
    $htmlout .= "<div class='divTable'>
    <div class='divTableHeading'>
        <div class='divTableCell'>{$lang['userdetails_s_cat']}</div>
        <div class='divTableCell'>{$lang['userdetails_s_torr']}</div>
        <div class='divTableCell'>{$lang['userdetails_s_up']}</div>
        <div class='divTableCell'>{$lang['userdetails_rate']}</div>
        ".($TRINITY20['ratio_free'] ? "" : "<div class='divTableCell'>{$lang['userdetails_downl']}</div>")."
        ".($TRINITY20['ratio_free'] ? "" : "<div class='divTableCell'>{$lang['userdetails_rate']}</div>")."
        <div class='divTableCell'>{$lang['userdetails_ratio']}</div>
        <div class='divTableCell'>{$lang['userdetails_activity']}</div>
        <div class='divTableCell'>{$lang['userdetails_s_fin']}</div>
        </div>";
    while ($arr = $res->fetch_assoc()) {
        $upspeed = ($arr["upspeed"] > 0 ? mksize($arr["upspeed"]) : ($arr["seedtime"] > 0 ? mksize($arr["uploaded"] / ($arr["seedtime"] + $arr["leechtime"])) : mksize(0)));
        $downspeed = ($arr["downspeed"] > 0 ? mksize($arr["downspeed"]) : ($arr["leechtime"] > 0 ? mksize($arr["downloaded"] / $arr["leechtime"]) : mksize(0)));
        $ratio = ($arr["downloaded"] > 0 ? number_format($arr["uploaded"] / $arr["downloaded"], 3) : ($arr["uploaded"] > 0 ? "Inf." : "---"));
        $XBT_or_PHP = (XBT_TRACKER == true ? $arr['tid'] : $arr['torrentid']);
        $XBT_or_PHP_TIME = (XBT_TRACKER == true ? $arr["completedtime"] : $arr["complete_date"]);
        $htmlout .= "<div class='divTableBody'>
            <div class='divTableRow'>
                <div class='divTableCell'><img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($arr["catimg"])."' alt='".htmlsafechars($arr["catname"])."' width='42' height='42'></div>
                <div class='divTableCell'><a href='details.php?id=".(int)$XBT_or_PHP."'><b>".(strlen($arr["name"]) > 50 ? substr($arr["name"], 0,
                    50 - 3)."..." : htmlsafechars($arr["name"]))."</b></a></div>
                <div class='divTableCell'>".mksize($arr["uploaded"])."</div>
                <div class='divTableCell'>$upspeed/s</div>
                ".($TRINITY20['ratio_free'] ? "" : "<div class='divTableCell'>".mksize($arr["downloaded"])."</div>")."
                ".($TRINITY20['ratio_free'] ? "" : "<div class='divTableCell'>$downspeed/s</div>")."
                <div class='divTableCell'>$ratio</div>
                <div class='divTableCell'>".mkprettytime($arr["seedtime"] + $arr["leechtime"])."</div>
                <div class='divTableCell'>".($XBT_or_PHP_TIME != 0 ? "<font color='green'><b>{$lang['userdetails_yes']}</b></font>" : "<font color='red'><b>{$lang['userdetails_no']}</b></font>")."</div>
            </div>
        </div>";
    }
    return $htmlout."</div>";
}

//==Snatched
if (($user['opt1'] & user_options::HIDECUR || $CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) && ($user_snatches_data = $cache->get('user_snatches_data_'.$id)) === false) {
    if (XBT_TRACKER === false) {
        ($ressnatch = sql_query("SELECT s.*, t.name AS name, c.name AS catname, c.image AS catimg FROM snatched AS s INNER JOIN torrents AS t ON s.torrentid = t.id LEFT JOIN categories AS c ON t.category = c.id WHERE s.userid =".sqlesc($user['id'])." AND s.torrentid IN (SELECT id FROM torrents)")) || sqlerr(__FILE__,
            __LINE__);
    } else {
        ($ressnatch = sql_query("SELECT x.*, t.name AS name, c.name AS catname, c.image AS catimg FROM xbt_peers AS x INNER JOIN torrents AS t ON x.tid = t.id LEFT JOIN categories AS c ON t.category = c.id WHERE x.uid =".sqlesc($user['id'])." AND x.tid IN (SELECT id FROM torrents)")) || sqlerr(__FILE__,
            __LINE__);
    }
    $count_snatched = $ressnatch->num_rows;
    if ($ressnatch->num_rows > 0) {
        $user_snatches_data = snatchtable($ressnatch);
        $cache->set('user_snatches_data_'.$id, $user_snatches_data, $TRINITY20['expires']['user_snatches_data']);
    } else {
        $user_snatches_data = $lang['userdetails_s_nothing'];
    }
}
//=== start snatched
$count_snatched_staff = 0;
function snatchtable_staff($res)
{
    global $TRINITY20, $lang, $CURUSER, $id;
    $htmlout_snatch = '';
    $htmlout_snatch .= "<div class='divTable'>
    <div class='divTableHeading'>
        <div class='divTableCell'>{$lang['userdetails_s_cat']}</div>
        <div class='divTableCell'>{$lang['userdetails_s_torr']}</div>"."
        <div class='divTableCell'>{$lang['userdetails_s_sl']}</div>
        <div class='divTableCell'>{$lang['userdetails_s_up']}".($TRINITY20['ratio_free'] ? "" : "{$lang['userdetails_s_down']}")."</div>
        <div class='divTableCell'>{$lang['userdetails_s_tsize']}</div>"."
        <div class='divTableCell'>{$lang['userdetails_ratio']}</div>
        <div class='divTableCell'>{$lang['userdetails_client']}</div>
    </div>";
    while ($arr = $res->fetch_assoc()) {
        //=== speed color red fast green slow ;)
        if ($arr["upspeed"] > 0) {
            $ul_speed = ($arr["upspeed"] > 0 ? mksize($arr["upspeed"]) : ($arr["seedtime"] > 0 ? mksize($arr["uploaded"] / ($arr["seedtime"] + $arr["leechtime"])) : mksize(0)));
        } else {
            $ul_speed = mksize(($arr["uploaded"] / ($arr['l_a'] - $arr['s'] + 1)));
        }
        if ($arr["downspeed"] > 0) {
            $dl_speed = ($arr["downspeed"] > 0 ? mksize($arr["downspeed"]) : ($arr["leechtime"] > 0 ? mksize($arr["downloaded"] / $arr["leechtime"]) : mksize(0)));
        } else {
            $dl_speed = mksize(($arr["downloaded"] / ($arr['c'] - $arr['s'] + 1)));
        }
        switch (true) {
            case ($dl_speed > 600):
                $dlc = 'red';
                break;

            case ($dl_speed > 300):
                $dlc = 'orange';
                break;

            case ($dl_speed > 200):
                $dlc = 'yellow';
                break;

            case ($dl_speed < 100):
                $dlc = 'Chartreuse';
                break;
        }
        if ($arr["downloaded"] > 0) {
            $ratio = number_format($arr["uploaded"] / $arr["downloaded"], 3);
            $ratio = "<font color='".get_ratio_color($ratio)."'><b>{$lang['userdetails_s_ratio']}</b><br />$ratio</font>";
        } elseif ($arr["uploaded"] > 0) {
            $ratio = $lang['userdetails_inf'];
        } else {
            $ratio = "N/A";
        }
        if (XBT_TRACKER === false) {
            $htmlout_snatch .= "<div class='divTableBody'>
            <div class='divTableRow'>
            <div class='divTableCell'>".($arr['owner'] == $id ? "<strong><font color='orange'>{$lang['userdetails_s_towner']}</font></strong><br />" : "".($arr['complete_date'] != '0' ? "<b><font color='lightgreen'>{$lang['userdetails_s_fin']}</font></b><br />" : "<b><font color='red'>{$lang['userdetails_s_notfin']}</font></b><br />")."")."<img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($arr['image'])."' alt='".htmlsafechars($arr['name'])."' title='".htmlsafechars($arr['name'])."' /></div>"."
            <div class='divTableCell'><a class='altlink' href='{$TRINITY20['baseurl']}/details.php?id=".(int)$arr['torrentid']."'><b>".htmlsafechars($arr['torrent_name'])."</b></a>".($arr['complete_date'] != '0' ? "<br /><font color='yellow'>{$lang['userdetails_s_started']}".get_date($arr['start_date'],
                        0, 1)."</font><br />" : "<font color='yellow'>{$lang['userdetails_s_started']}".get_date($arr['start_date'], 0,
                        1)."</font><br /><font color='orange'>{$lang['userdetails_s_laction']}".get_date($arr['last_action'], 0,
                        1)."</font>".get_date($arr['complete_date'], 0,
                        1)." ".($arr['complete_date'] == '0' ? "".($arr['owner'] == $id ? "" : "[ ".mksize($arr["size"] - $arr["downloaded"])."{$lang['userdetails_s_still']}]")."" : "")."").$lang['userdetails_s_finished'].get_date($arr['complete_date'],
                    0,
                    1)."".($arr['complete_date'] != '0' ? "<br /><font color='silver'>{$lang['userdetails_s_ttod']}".($arr['leechtime'] != '0' ? mkprettytime($arr['leechtime']) : mkprettytime($arr['c'] - $arr['s'])."")."</font> <font color='$dlc'>[ {$lang['userdetails_s_dled']} $dl_speed ]</font><br />" : "<br />")."<font color='lightblue'>".($arr['seedtime'] != '0' ? $lang['userdetails_s_tseed'].mkprettytime($arr['seedtime'])." </font><font color='$dlc'> " : $lang['userdetails_s_tseedn'])."</font><font color='lightgreen'> [ {$lang['userdetails_s_uspeed']} ".$ul_speed." ] </font>".($arr['complete_date'] == '0' ? "<br /><font color='$dlc'>{$lang['userdetails_s_dspeed']}$dl_speed</font>" : "")."</div>"."
            <div class='divTableCell'>{$lang['userdetails_s_seed']}".(int)$arr['seeders']."<br />{$lang['userdetails_s_leech']}".(int)$arr['leechers']."</div>
            <div class='divTableCell'><font color='lightgreen'>{$lang['userdetails_s_upld']}<br /><b>".mksize($arr["uploaded"])."</b></font>".($TRINITY20['ratio_free'] ? "" : "<br /><font color='orange'>{$lang['userdetails_s_dld']}<br /><b>".mksize($arr["downloaded"])."</b></font>")."</div>
            <div class='divTableCell'>".mksize($arr["size"])."".($TRINITY20['ratio_free'] ? "" : "<br />{$lang['userdetails_s_diff']}<br /><font color='orange'><b>".mksize($arr['size'] - $arr["downloaded"])."</b></font>")."</div>
            <div class='divTableCell'>".$ratio."<br />".($arr['seeder'] == 'yes' ? "<font color='lightgreen'><b>{$lang['userdetails_s_seeding']}</b></font>" : "<font color='red'><b>{$lang['userdetails_s_nseeding']}</b></font>")."</div>
            <div class='divTableCell'>".htmlsafechars($arr["agent"])."<br />{$lang['userdetails_s_port']}".(int)$arr["port"]."<br />".($arr["connectable"] == 'yes' ? "<b>{$lang['userdetails_s_conn']}</b> <font color='lightgreen'>{$lang['userdetails_yes']}</font>" : "<b>{$lang['userdetails_s_conn']}</b> <font color='red'><b>{$lang['userdetails_no']}</b></font>")."</div>
            </div></div>";
        } else {
            $htmlout_snatch .= "<div class='divTableBody'>
            <div class='divTableRow'>
            <div class='divTableCell'>".($arr['owner'] == $id ? "<b><font color='orange'>{$lang['userdetails_s_towner']}</font></b><br />" : "".($arr['completedtime'] != '0' ? "<b><font color='lightgreen'>{$lang['userdetails_s_fin']}</font></b><br />" : "<b><font color='red'>{$lang['userdetails_s_nofin']}</font></b><br />")."")."<img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($arr['image'])."' alt='".htmlsafechars($arr['name'])."' title='".htmlsafechars($arr['name'])."' /></div>"."
            <div class='divTableCell'>
                <a class='altlink' href='{$TRINITY20['baseurl']}/details.php?id=".(int)$arr['tid']."'><b>".htmlsafechars($arr['torrent_name'])."</b></a>".($arr['completedtime'] != '0' ? "<br /><font color='yellow'>{$lang['userdetails_s_started']}".get_date($arr['started'],
                        0, 1)."</font><br />" : "<font color='yellow'>started:".get_date($arr['started'], 0,
                        1)."</font><br /><font color='orange'>{$lang['userdetails_s_laction']}".get_date($arr['mtime'], 0,
                        1)."</font>".get_date($arr['completedtime'], 0,
                        1)." ".($arr['completedtime'] == '0' ? "".($arr['owner'] == $id ? "" : "[ ".mksize($arr["size"] - $arr["downloaded"])."{$lang['userdetails_s_still']}]")."" : "")."")."{$lang['userdetails_s_finished']}".get_date($arr['completedtime'],
                    0,
                    1)."".($arr['completedtime'] != '0' ? "<br /><font color='silver'>{$lang['userdetails_s_ttod']}".($arr['leechtime'] != '0' ? mkprettytime($arr['leechtime']) : mkprettytime($arr['c'] - $arr['s'])."")."</font> <font color='$dlc'>[ {$lang['userdetails_s_dled']} $dl_speed ]</font><br />" : "<br />")."<font color='lightblue'>".($arr['seedtime'] != '0' ? "{$lang['userdetails_s_tseed']}".mkprettytime($arr['seedtime'])." </font><font color='$dlc'> " : "{$lang['userdetails_s_tseedn']}")."</font><font color='lightgreen'> [{$lang['userdetails_s_uspeed']}".$ul_speed." ] </font>".($arr['completedtime'] == '0' ? "<br /><font color='$dlc'>{$lang['userdetails_s_dspeed']} $dl_speed</font>" : "")."</div>"."
                <div class='divTableCell'>{$lang['userdetails_s_seed']}".(int)$arr['seeders']."<br />{$lang['userdetails_s_leech']}".(int)$arr['leechers']."</div>
                <div class='divTableCell'><font color='lightgreen'>{$lang['userdetails_s_upld']}<br /><b>".mksize($arr["uploaded"])."</b></font>".($TRINITY20['ratio_free'] ? "" : "<br /><font color='orange'>{$lang['userdetails_s_dld']}<br /><b>".mksize($arr["downloaded"])."</b></font>")."</div>
                <div class='divTableCell'>".mksize($arr["size"])."".($TRINITY20['ratio_free'] ? "" : "<br />{$lang['userdetails_s_diff']}<br /><font color='orange'><b>".mksize($arr['size'] - $arr["downloaded"])."</b></font>")."</div>
                <div class='divTableCell'>".$ratio."<br />".($arr['active'] == 1 ? "<font color='lightgreen'><b>{$lang['userdetails_s_seeding']}</b></font>" : "<font color='red'><b>{$lang['userdetails_s_nseeding']}</b></font>")."</div>
                <div class='divTableCell'>".htmlsafechars($arr["peer_id"])."<br />".($arr["connectable"] == 1 ? "<b>{$lang['userdetails_s_conn']}</b> <font color='lightgreen'>{$lang['userdetails_yes']}</font>" : "<b>{$lang['userdetails_s_conn']}</b> <font color='red'><b>{$lang['userdetails_no']}</b></font>")."</div>
                </div></div>";
        }
    }
    return $htmlout_snatch."</div>";
}

if (XBT_TRACKER === false) {
    ($res = sql_query("SELECT sn.start_date AS s, sn.complete_date AS c, sn.last_action AS l_a, sn.seedtime AS s_t, sn.seedtime, sn.leechtime AS l_t, sn.leechtime, sn.downspeed, sn.upspeed, sn.uploaded, sn.downloaded, sn.torrentid, sn.start_date, sn.complete_date, sn.seeder, sn.last_action, sn.connectable, sn.agent, sn.seedtime, sn.port, cat.name, cat.image, t.size, t.seeders, t.leechers, t.owner, t.name AS torrent_name "."FROM snatched AS sn "."LEFT JOIN torrents AS t ON t.id = sn.torrentid "."LEFT JOIN categories AS cat ON cat.id = t.category "."WHERE sn.userid=".sqlesc($id)." AND sn.torrentid IN (SELECT id FROM torrents) ORDER BY sn.start_date DESC")) || sqlerr(__FILE__,
        __LINE__);
} else {
    ($res = sql_query("SELECT x.started AS s, x.completedtime AS c, x.mtime AS l_a, x.seedtime AS s_t, x.seedtime, x.leechtime AS l_t, x.leechtime, x.downspeed, x.upspeed, x.uploaded, x.downloaded, x.tid, x.started, x.completedtime, x.active, x.mtime, x.connectable, x.peer_id, cat.name, cat.image, t.size, t.seeders, t.leechers, t.owner, t.name AS torrent_name "."FROM xbt_peers AS x "."LEFT JOIN torrents AS t ON t.id = x.tid "."LEFT JOIN categories AS cat ON cat.id = t.category "."WHERE x.uid=".sqlesc($id)." AND x.tid IN (SELECT id FROM torrents) ORDER BY x.started DESC")) || sqlerr(__FILE__,
        __LINE__);
}
$count_snatched_staff = $res->num_rows;
$user_snatches_data_staff = $res->num_rows > 0 ? snatchtable_staff($res) : $lang['userdetails_s_nothing'];
if (isset($user_snatches_data) && isset($user_snatches_data_staff)) {
    $HTMLOUT .= '<a class="button small hollow" data-open="exampleModal">'.$lang['userdetails_cur_snatched'].'</a>
    <div class="reveal" id="exampleModal" data-reveal>
    '.$user_snatches_data.'
    </div>
    <a class="button small hollow" data-open="exampleModal1">'.$lang['userdetails_snatched_menu'].'</a>
    <div class="reveal" id="exampleModal1" data-reveal>';
}
if ($CURUSER['class'] >= UC_STAFF) {
    $HTMLOUT .= $user_snatches_data_staff;
}
$HTMLOUT.'</div>';
//==End
// End Class
// End File
