<?php
$HTMLOUT .= "
<div class='grid-x grid-margin-x'>
<div class='cell medium-4'>
<table class='striped'>";
//==subs by putyn
if (in_array($torrents["category"], $TRINITY20['movie_cats']) && !empty($torrents["subs"])) {
    $HTMLOUT .= "<tr>
        <td>{$lang['details_add_sub1']}</td>
        <td>";
    $subs_array = explode(",", $torrents["subs"]);
    foreach ($subs_array as $k => $sid) {
        require_once(CACHE_DIR.'subs.php');
        foreach ($subs as $sub) {
            if ($sub["id"] == $sid) {
                $HTMLOUT .= "<img border='0' width='25px' style='padding:3px;' src='".htmlspecialchars($sub["pic"])."' alt='".htmlspecialchars($sub["name"])."' title='".htmlspecialchars($sub["name"])."'>";
            }
        }
    }
    $HTMLOUT .= "</td></tr>";
}
if ($CURUSER["class"] >= UC_POWER_USER && $torrents["nfosz"] > 0) {
    $HTMLOUT .= "<tr>
        <td>{$lang['details_nfo']}</td>
        <td>
            <a href='viewnfo.php?id=".(int)$torrents['id']."'>
                <b>{$lang['details_view_nfo']}</b>
            </a> (".mksize($torrents["nfosz"]).")
        </td>
    </tr>";
}
if ($torrents["visible"] == "no") {
    $HTMLOUT .= "<tr>
        <td>{$lang['details_visible']}</td>
        <td><b>{$lang['details_no']}</b>{$lang['details_dead']}</td>
    </tr>";
}
if ($moderator) {
    $HTMLOUT .= "<tr>
        <td>{$lang['details_banned']}</td>
        <td>".$torrents["banned"]."</td>
    </tr>";
}
if ($torrents["nuked"] == "yes") {
    $HTMLOUT .= "<tr>
        <td><b>{$lang['details_add_nuk1']}</b></td>
        <td><span data-tooltip tabindex='3' title='Nuked'><i class='fas fa-biohazard'></i></span></td>
    </tr>";
}
if (!empty($torrents["nukereason"])) {
    $HTMLOUT .= "<tr>
        <td><b>{$lang['details_add_nuk2']}</b></td>
        <td>".htmlspecialchars($torrents["nukereason"])."</td>
    </tr>";
}
$HTMLOUT .= "<tr>
    <td>{$lang['details_add_rate']}</td>
    <td>".getRate($id, "torrent")."<td>
</tr>";
// --------------- likes start------
$att_str = '';
$likes = empty($torrents['user_likes']) ? '' : explode(',', $torrents['user_likes']);
if (!empty($likes) && count(array_unique($likes)) > 0) {
    if (in_array($CURUSER['id'], $likes)) {
        if ((is_countable($likes) ? count($likes) : 0) == 1) {
            $att_str = jq('You like this');
        } elseif (count(array_unique($likes)) > 1) {
            $att_str = jq('You and&nbsp;').((count(array_unique($likes)) - 1) == '1' ? '1 other person likes this' : ((is_countable($likes) ? count($likes) : 0) - 1).'&nbsp;others like this');
        }
    } elseif (!(in_array($CURUSER['id'], $likes))) {
        if (count(array_unique($likes)) == 1) {
            $att_str = '1 other person likes this';
        } elseif (count(array_unique($likes)) > 1) {
            $att_str = (count(array_unique($likes))).'&nbsp;others like this';
        }
    }
}
$wht = ((!empty($likes) && count(array_unique($likes)) > 0 && in_array($CURUSER['id'], $likes)) ? 'unlike' : 'like');

$HTMLOUT .= "<tr>
        <td>Likes</td>
        <td>
            <span id='mlike' data-com='".(int)$torrents["id"]."' class='details {$wht}'>[".ucfirst($wht)."]</span>
            <span class='tot-".(int)$torrents["id"]."' data-tot='".(!empty($likes) && count(array_unique($likes)) > 0 ? count(array_unique($likes)) : '')."'>&nbsp;{$att_str}</span>
        </td>
    </tr>
    <tr>
        <td>{$lang['details_last_seeder']}</td>
        <td>{$lang['details_last_activity']}".get_date($l_a['lastseed'], '', 0, 1)."</td>
    </tr>";
//Display pretime
if ($pretime['time'] == '0') {
    $prestatement = "{$lang['details_add_pre1']}";
} else {
    $prestatement = get_pretime(time() - $pretime['time'])."{$lang['details_add_pre2']}<br />{$lang['details_add_pre3']}".get_pretime($torrents['added'] - $pretime['time'])."{$lang['details_add_pre4']}";
}
$HTMLOUT .= "<tr>
        <td>{$lang['details_add_pre5']}</td>
        <td>".$prestatement."</td>
    </tr>
    <tr>
        <td>{$lang['details_views']}</td>
        <td>".(int)$torrents["views"]."</td>
    </tr>
    <tr>
        <td>{$lang['details_hits']}</td>
        <td>".(int)$torrents["hits"]."</td>
    </tr>";
$HTMLOUT .= "
<script type='text/javascript'>
function showme() {
    document.getElementById('show').innerHTML = '{$CURUSER['username']} is viewing details for torrent {$TRINITY20['baseurl']}/details.php?id=".(int)$torrents['id']."\"';
}
</script>
<tr>
    <td>{$lang['details_add_statupd1']}</td>
    <td>
        <button type='button' class='button small info'  id='show' onclick='showme()'>{$lang['details_add_statupd2']}</button>
    </td>
</tr>
</table></div><!-- closing cell medium -->
<div class='cell medium-8'>
<table class='striped'>";
//==Report Torrent Link
$HTMLOUT .= "<tr>
    <td><form action='report.php?type=Torrent&amp;id=$id' method='post'>
        <div class='input-group' aria-describedby='report_t'>
            <span class='input-group-label'>{$lang['details_add_rprt1']}</span>
            <div class='input-group-button'>
                <input type='submit' name='submit' class='button' value='Submit'>
            </div>
        </div>
        <p class='help-text' id='report_t'>{$lang['details_add_rprt2']} {$lang['details_add_rprt3']}<a href='rules.php'>{$lang['details_add_rprt4']}</a></p>
        </form>
    </td>
    </tr>";
//== Tor Reputation by pdq
$torrent_rep = $torrent_cache['rep'] ?? '';
if ($torrent_rep && $TRINITY20['rep_sys_on']) {
    $torrents = array_merge($torrents, $torrent_rep);
    $member_reputation = get_reputation($torrents, 'torrents', $torrents['anonymous']);
    $HTMLOUT .= '<tr><td>'.$lang['details_add_reput1'].'</td>
        <td>'.$member_reputation.''.$lang['details_add_reput2'].'</td></tr>';
}
//==pdq's Torrent Moderation
if ($CURUSER['class'] >= UC_STAFF) {
    if (!empty($torrents['checked_by'])) {
        if (($checked_by = $cache->get($cache_keys['checked_by'].$id)) === false) {
            ($chckby_query = sql_query("SELECT id FROM users WHERE username=".sqlesc($torrents['checked_by']))) || sqlerr(__FILE__, __LINE__);
            $checked_by = $chckby_query->fetch_assoc();
            $cache->set($cache_keys['checked_by'].$id, $checked_by, 30 * 86400);
        }
        $HTMLOUT .= "<tr>
    <td>{$lang['details_add_bychk1']}</td>
    <td align='left'>
<p><a class='label label-primary' href='{$TRINITY20["baseurl"]}/userdetails.php?id=".(int)$checked_by['id']."'>
    <strong>".htmlspecialchars($torrents['checked_by'])."</strong></a></p>
    <p><a href='{$TRINITY20["baseurl"]}/details.php?id=".(int)$torrents['id']."&amp;rechecked=1'>
        <small><em class='label label-primary'><strong>{$lang['details_add_bychk2']}</strong></em></small></a> 
    <a href='{$TRINITY20["baseurl"]}/details.php?id=".(int)$torrents['id']."&amp;clearchecked=1'>
    <small><em class='label label-primary'><strong>{$lang['details_add_bychk3']}</strong></em></small></a></p>
    &nbsp;<p><em class=label label-primary'>{$lang['details_add_bychk4']}</em>
    ".(isset($torrents["checked_when"]) && $torrents["checked_when"] > 0 ? "<strong>{$lang['details_add_bychk5']}".get_date($torrents["checked_when"],
                    'DATE', 0, 1)."</strong>" : '')."</td></tr>";
    } else {
        $HTMLOUT .= "<tr><td class='rowhead'>{$lang['details_add_bychk1']}</td><td align='left'><em class='label label-primary'><strong>{$lang['details_add_bychk6']}</strong></em> 
       <a href='{$TRINITY20["baseurl"]}/details.php?id=".(int)$torrents['id']."&amp;checked=1'>
       <em class='label label-primary'><small><strong>{$lang['details_add_bychk7']}</strong></small></em></a>&nbsp;<em class='label label-primary'><strong>{$lang['details_add_bychk4']}</strong></em></p></td></tr>";
    }
}
// end
if ($torrents["type"] == "multi") {
    if (!isset($_GET["filelist"])) {
        $HTMLOUT .= tr("{$lang['details_num_files']}", "<a class='' href='./filelist.php?id=$id'>".(int)$torrents["numfiles"]." files</a>", 1);
    } else {
        $HTMLOUT .= tr("{$lang['details_num-files']}", (int)$torrents["numfiles"]."{$lang['details_files']}", 1);
    }
}
//==putyns thanks mod
$HTMLOUT .= tr($lang['details_thanks'], '
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
if ($torrents["last_reseed"] > 0) {
    $next_reseed = ($torrents["last_reseed"] + 172800);
} //add 2 days
$reseed = "<form method=\"post\" action=\"./takereseed.php\">
      <select name=\"pm_what\">
      <option value=\"last10\">{$lang['details_add_reseed1']}</option>
      <option value=\"owner\">{$lang['details_add_reseed2']}</option>
      </select>
      <input type=\"submit\"  ".(($next_reseed > TIME_NOW) ? "disabled='disabled'" : "")." value=\"{$lang['details_add_reseed3']}\" />
      <input type=\"hidden\" name=\"uploader\" value=\"".(int)$torrents["owner"]."\" />
      <input type=\"hidden\" name=\"reseedid\" value=\"$id\" />
      </form>";
$HTMLOUT .= tr($lang['details_add_reseed4'], $reseed, 1);
//==End
$HTMLOUT .= "</table></div><!-- closing col md 8 --></div><!-- closing row -->";
?>
