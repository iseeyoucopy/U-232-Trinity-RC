<?php
$HTMLOUT.= "
<div class='grid-x grid-margin-x'>
<div class='cell medium-4'>
<table class='striped'>";
//==subs by putyn
if (in_array($torrents["category"], $INSTALLER09['movie_cats']) && !empty($torrents["subs"])) {
    $HTMLOUT.= "<tr>
        <td class='rowhead'>{$lang['details_add_sub1']}</td>
        <td align='left'>";
    $subs_array = explode(",", $torrents["subs"]);
    foreach ($subs_array as $k => $sid) {
        require_once (CACHE_DIR . 'subs.php');
        foreach ($subs as $sub) {
            if ($sub["id"] == $sid) $HTMLOUT.= "<img border=\"0\" width=\"25px\" style=\"padding:3px;\"src=\"" . htmlsafechars($sub["pic"]) . "\" alt=\"" . htmlsafechars($sub["name"]) . "\" title=\"" . htmlsafechars($sub["name"]) . "\" />";
        }
    }
    $HTMLOUT.= "</td></tr>\n";
}
if ($CURUSER["class"] >= UC_POWER_USER && $torrents["nfosz"] > 0) $HTMLOUT.= "<tr><td class='rowhead'>{$lang['details_nfo']}</td><td align='left'><a href='viewnfo.php?id=" . (int)$torrents['id'] . "'><b>{$lang['details_view_nfo']}</b></a> (" . mksize($torrents["nfosz"]) . ")</td></tr>\n";
if ($torrents["visible"] == "no") $HTMLOUT.= tr("{$lang['details_visible']}", "<b>{$lang['details_no']}</b>{$lang['details_dead']}", 1);
if ($moderator) $HTMLOUT.= tr("{$lang['details_banned']}", $torrents["banned"]);
if ($torrents["nuked"] == "yes") $HTMLOUT.= "<tr><td class='rowhead'><b>{$lang['details_add_nuk1']}</b></td><td align='left'><img src='{$INSTALLER09['pic_base_url']}nuked.gif' alt='Nuked' title='Nuked' /></td></tr>\n";
if (!empty($torrents["nukereason"])) $HTMLOUT.= "<tr><td class='rowhead'><b>{$lang['details_add_nuk2']}</b></td><td align='left'>" . htmlsafechars($torrents["nukereason"]) . "</td></tr>\n";
$torrents['cat_name'] = htmlsafechars($change[$torrents['category']]['name']);
if (isset($torrents["cat_name"])) $HTMLOUT.= tr("{$lang['details_type']}", htmlsafechars($torrents["cat_name"]));
else $HTMLOUT.= tr("{$lang['details_type']}", "{$lang['details_add_none']}");
$HTMLOUT.= tr("{$lang['details_add_rate']}", getRate($id, "torrent") , 1);
// --------------- likes start------
        $att_str = '';
        if (!empty($torrents['user_likes'])) {
            $likes = explode(',', $torrents['user_likes']);
        } else {
            $likes = '';
        }
        if (!empty($likes) && count(array_unique($likes)) > 0) {
            if (in_array($CURUSER['id'], $likes)) {
                if (count($likes) == 1) {
                    $att_str = jq('You like this');
                } elseif (count(array_unique($likes)) > 1) {
                    $att_str = jq('You and&nbsp;') . ((count(array_unique($likes)) - 1) == '1' ? '1 other person likes this' : (count($likes) - 1) . '&nbsp;others like this');
                }
            } elseif (!(in_array($CURUSER['id'], $likes))) {
                if (count(array_unique($likes)) == 1) {
                    $att_str = '1 other person likes this';
                } elseif (count(array_unique($likes)) > 1) {
                    $att_str = (count(array_unique($likes))) . '&nbsp;others like this';
                }
            }
        }
        $wht = ((!empty($likes) && count(array_unique($likes)) > 0 && in_array($CURUSER['id'], $likes)) ? 'unlike' : 'like');

$HTMLOUT.= tr("Likes","<span id='mlike' data-com='" . (int)$torrents["id"] . "' class='details {$wht}'>[" . ucfirst($wht) . "]</span><span class='tot-" . (int)$torrents["id"] . "' data-tot='" . (!empty($likes) && count(array_unique($likes)) > 0 ? count(array_unique($likes)) : '') . "'>&nbsp;{$att_str}</span>" , 1);
$HTMLOUT.= tr("{$lang['details_last_seeder']}", "{$lang['details_last_activity']}" . get_date($l_a['lastseed'], '', 0, 1));
$HTMLOUT.= tr("{$lang['details_size']}", mksize($torrents["size"]) . " (" . number_format($torrents["size"]) . " {$lang['details_bytes']})");
$HTMLOUT.= tr("{$lang['details_added']}", get_date($torrents['added'], "{$lang['details_long']}"));
//Display pretime
    if ($pretime['time'] == '0') {
    $prestatement = "{$lang['details_add_pre1']}";
    } else {
    $prestatement = get_pretime(time() -  $pretime['time']) . "{$lang['details_add_pre2']}<br />{$lang['details_add_pre3']}" . get_pretime($torrents['added'] - $pretime['time']) . "{$lang['details_add_pre4']}";
    }
$HTMLOUT.="<tr><td align='right' class='heading'>{$lang['details_add_pre5']}</td><td width='99%' align='left'>". $prestatement."</td></tr>";
$HTMLOUT.= tr("{$lang['details_views']}", (int)$torrents["views"]);
$HTMLOUT.= tr("{$lang['details_hits']}", (int)$torrents["hits"]);
$XBT_Or_Default = (XBT_TRACKER == true ? 'snatches_xbt.php?id=' : 'snatches.php?id=');
$HTMLOUT.= tr("{$lang['details_snatched']}", ($torrents["times_completed"] > 0 ? "<a href='{$INSTALLER09["baseurl"]}/{$XBT_Or_Default}{$id}'>{$torrents['times_completed']} {$lang['details_times']}</a>" : "0 {$lang['details_times']}") , 1);
$HTMLOUT.= "
<script type='text/javascript'>
function showme() {
    document.getElementById('show').innerHTML = '{$CURUSER['username']} is viewing details for torrent {$INSTALLER09['baseurl']}/details.php?id=" . (int)$torrents['id'] . "\"';
}
</script>
<tr><td class='rowhead'>{$lang['details_add_statupd1']}</td><td><button type='button' class='button small info'  id='show' onclick='showme()'>{$lang['details_add_statupd2']}</button></td></tr>";
$HTMLOUT.= "</table></div><!-- closing cell medium -->
<div class='cell medium-8'>
<table align='center' class='striped'>";
//==Report Torrent Link
$HTMLOUT.= tr("{$lang['details_add_rprt1']}", "<form action='report.php?type=Torrent&amp;id=$id' method='post'><input class='button primary' type='submit' name='submit' value='".$lang['details_add_rprt2']."' />&nbsp;&nbsp;<strong><em class='label label-primary'>{$lang['details_add_rprt3']}<a href='rules.php'>{$lang['details_add_rprt4']}</a></em></strong></form>", 1);
//== Tor Reputation by pdq
$torrent_rep = isset($torrent_cache['rep']) ? $torrent_cache['rep'] : '';
if ($torrent_rep && $INSTALLER09['rep_sys_on']) {
    $torrents = array_merge($torrents, $torrent_rep);
    $member_reputation = get_reputation($torrents, 'torrents', $torrents['anonymous']);
    $HTMLOUT.= '<tr><td>'.$lang['details_add_reput1'].'</td>
        <td>' . $member_reputation . ''.$lang['details_add_reput2'].'</td></tr>';
}
//==Anonymous
$rowuser = (isset($torrents['username']) ? ("<a href='userdetails.php?id=" . (int)$torrents['owner'] . "'><b>" . htmlsafechars($torrents['username']) . "</b></a>") : "{$lang['details_unknown']}");
$uprow = (($torrents['anonymous'] == 'yes') ? ($CURUSER['class'] < UC_STAFF && $torrents['owner'] != $CURUSER['id'] ? '' : $rowuser . ' - ') . "<i>{$lang['details_anon']}</i>" : $rowuser);
if ($owned) $uprow.= " $spacer<$editlink><b>{$lang['details_edit']}</b>";
$HTMLOUT.= tr("{$lang['details_add_byup']}", $uprow, 1);
//==pdq's Torrent Moderation
if ($CURUSER['class'] >= UC_STAFF) {
    if (!empty($torrents['checked_by'])) {
        if (($checked_by = $cache->get('checked_by_' . $id)) === false) {
            $checked_by = mysqli_fetch_assoc(sql_query("SELECT id FROM users WHERE username=" . sqlesc($torrents['checked_by']))) or sqlerr(__FILE__, __LINE__);
            $cache->set('checked_by_' . $id, $checked_by, 30 * 86400);
        }
        $HTMLOUT.= "<tr>
    <td>{$lang['details_add_bychk1']}</td>
    <td align='left'>
<p><a class='label label-primary' href='{$INSTALLER09["baseurl"]}/userdetails.php?id=" . (int)$checked_by['id'] . "'>
    <strong>" . htmlsafechars($torrents['checked_by']) . "</strong></a></p>
    <p><a href='{$INSTALLER09["baseurl"]}/details.php?id=" . (int)$torrents['id'] . "&amp;rechecked=1'>
        <small><em class='label label-primary'><strong>{$lang['details_add_bychk2']}</strong></em></small></a> 
    <a href='{$INSTALLER09["baseurl"]}/details.php?id=" . (int)$torrents['id'] . "&amp;clearchecked=1'>
    <small><em class='label label-primary'><strong>{$lang['details_add_bychk3']}</strong></em></small></a></p>
    &nbsp;<p><em class=label label-primary'>{$lang['details_add_bychk4']}</em>
    ".(isset($torrents["checked_when"]) && $torrents["checked_when"] > 0 ? "<strong>{$lang['details_add_bychk5']}".get_date($torrents["checked_when"],'DATE',0,1)."</strong>":'' )."</td></tr>";
    } else {
        $HTMLOUT.= "<tr><td class='rowhead'>{$lang['details_add_bychk1']}</td><td align='left'><em class='label label-primary'><strong>{$lang['details_add_bychk6']}</strong></em> 
       <a href='{$INSTALLER09["baseurl"]}/details.php?id=" . (int)$torrents['id'] . "&amp;checked=1'>
       <em class='label label-primary'><small><strong>{$lang['details_add_bychk7']}</strong></small></em></a>&nbsp;<em class='label label-primary'><strong>{$lang['details_add_bychk4']}</strong></em></p></td></tr>";
    }
}
// end
if ($torrents["type"] == "multi") {
    if (!isset($_GET["filelist"])) 
		$HTMLOUT.= tr("{$lang['details_num_files']}", "<a class='' href='./filelist.php?id=$id'>".(int)$torrents["numfiles"] . " files</a>", 1);
    else {
        $HTMLOUT.= tr("{$lang['details_num-files']}", (int)$torrents["numfiles"] . "{$lang['details_files']}", 1);
    }
}

if(XBT_TRACKER == true) {
$HTMLOUT.= tr("{$lang['details_peers']}", "<a href='./peerlist_xbt.php?id=$id#seeders'>".(int)$torrents_xbt["seeders"] . "{$lang['details_add_sd']}" . (int)$torrents_xbt["leechers"] . "{$lang['details_add_lc']}" . ((int)$torrents_xbt["seeders"] + (int)$torrents_xbt["leechers"]) . "{$lang['details_peer_total']}</a>", 1);
} else {
$HTMLOUT.= tr("{$lang['details_peers']}", "<a href='./peerlist.php?id=$id#seeders'>".(int)$torrents["seeders"] . "{$lang['details_add_sd']}" . (int)$torrents["leechers"] . "{$lang['details_add_lc']}" . ((int)$torrents["seeders"] + (int)$torrents["leechers"]) . "{$lang['details_peer_total']}</a>", 1);
}
//==putyns thanks mod
$HTMLOUT.= tr($lang['details_thanks'], '
      <script type="text/javascript">
        /*<![CDATA[*/
        $(document).ready(function() {
            var tid = '.$id.';
            show_thanks(tid);
        });
        
        /*]]>*/
        
        </script>
        <noscript><iframe id="thanked" src ="thanks.php?torrentid='.$id.'" style="width:500px;height:50px;border:none;overflow:auto;">
      <p>Your browser does not support iframes. And it has Javascript disabled!</p>
      
      </iframe></noscript>
      <div id="thanks_holder"></div>', 1);
//==End
//==09 Reseed by putyn
$next_reseed = 0;
if ($torrents["last_reseed"] > 0) $next_reseed = ($torrents["last_reseed"] + 172800); //add 2 days
$reseed = "<form method=\"post\" action=\"./takereseed.php\">
      <select name=\"pm_what\">
      <option value=\"last10\">{$lang['details_add_reseed1']}</option>
      <option value=\"owner\">{$lang['details_add_reseed2']}</option>
      </select>
      <input type=\"submit\"  " . (($next_reseed > TIME_NOW) ? "disabled='disabled'" : "") . " value=\"{$lang['details_add_reseed3']}\" />
      <input type=\"hidden\" name=\"uploader\" value=\"" . (int)$torrents["owner"] . "\" />
      <input type=\"hidden\" name=\"reseedid\" value=\"$id\" />
      </form>";
$HTMLOUT.= tr($lang['details_add_reseed4'], $reseed, 1);
//==End
$HTMLOUT.= "</table></div><!-- closing col md 8 --></div><!-- closing row -->";
?>