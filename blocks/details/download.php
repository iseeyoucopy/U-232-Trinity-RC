<?php
require_once MODS_DIR.'slots_details.php';
$s = htmlspecialchars($torrents["name"], ENT_QUOTES);
$poster_url = ((empty($torrents["poster"])) ? $TRINITY20["pic_base_url"]."noposter.png" : htmlspecialchars($torrents["poster"]));
$Free_Slot = (XBT_TRACKER == true ? '' : $freeslot);
$torrents['cat_name'] = htmlspecialchars($change[$torrents['category']]['name']);
$tcatname = isset($torrents["cat_name"]) ? htmlspecialchars($torrents["cat_name"]) : $lang['details_add_none'];
$tadded = get_date($torrents['added'], "LONG");
$rowuser = (isset($torrents['username']) ? ("<a href='userdetails.php?id=".(int)$torrents['owner']."'><strong>".htmlspecialchars($torrents['username'])."</strong></a>") : "{$lang['details_unknown']}");
$uprow = (($torrents['anonymous'] == 'yes') ? ($CURUSER['class'] < UC_STAFF && $torrents['owner'] != $CURUSER['id'] ? '' : $rowuser.' - ')."<i>{$lang['details_anon']}</i>" : $rowuser);
$tseeders = (XBT_TRACKER == true) ? "<a href='./peerlist_xbt.php?id=$id#seeders'>".(int)$torrents_xbt["seeders"]."</a>" : "<a href='./peerlist.php?id=$id#seeders'>".(int)$torrents["seeders"]."</a>";
$tleechers = (XBT_TRACKER == true) ? "<a href='./peerlist_xbt.php?id=$id#leechers'>".(int)$torrents_xbt["leechers"]."</a>" : "<a href='./peerlist.php?id=$id#peers'>".(int)$torrents["leechers"]."</a>";
$tpeers = (XBT_TRACKER == true) ? "<a href='./peerlist_xbt.php?id=$id#peers'>".((int)$torrents_xbt["seeders"] + (int)$torrents_xbt["leechers"])."</a>" : "<a href='./peerlist.php?id=$id#peers'>".((int)$torrents["seeders"] + (int)$torrents["leechers"])."</a>";
$XBT_Or_Default = (XBT_TRACKER == true ? 'snatches_xbt.php?id=' : 'snatches.php?id=');
$XBT_or_default_link = "<a href='{$TRINITY20["baseurl"]}/{$XBT_Or_Default}{$id}'>".(int)$torrents['times_completed']."</a>";
$tsnatched = (int)$torrents["times_completed"] > 0 ? $XBT_or_default_link : "0";
$tsize = mksize($torrents["size"]);
/*
if ($owned) {
    $editlinkw = "<a href='edit.php?id=".(int)$torrents["id"]."'><i class='fas fa-edit'></i></a>";
}
*/
$editlinkw = $owned ? "<span class='label secondary float-right' tabindex='2' data-tooltip title='{$lang['details_edit']}'><a href='edit.php?id=".(int)$torrents["id"]."'><i class='fas fa-edit'></i></a>" : "";
/**  Mod by dokty, rewrote by pdq  **/
$my_points = 0;
if (($torrent['torrent_points_'] = $cache->get($keys['coin_points'].$id)) === false) {
    ($sql_points = sql_query('SELECT userid, points FROM coins WHERE torrentid='.sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $torrent['torrent_points_'] = [];
    if ($sql_points->num_rows !== 0) {
        while ($points_cache = $sql_points->fetch_assoc()) {
            $torrent['torrent_points_'][$points_cache['userid']] = $points_cache['points'];
        }
    }
    $cache->set($keys['coin_points'].$id, $torrent['torrent_points_'], 0);
}
$my_points = (isset($torrent['torrent_points_'][$CURUSER['id']]) ? (int)$torrent['torrent_points_'][$CURUSER['id']] : 0);
$HTMLOUT .= '<div class="grid-x">
    <div class="cell medium-12">
    <div class="card">
    <div class="card-divider">'.$s.'
    '.$editlinkw.'
</span></div>
    <div class="card-section">
'.$lang['details_added'].' at '.$tadded.' by '.$uprow.' | '.$lang['details_type'].' : '.$tcatname.'
</div>
</div>
    </div>
    <div class="cell medium-3">
        <div class="card-section">
            <div class="media-object-section padding-0">
                <div class="thumbnail">
                    <img src="'.$poster_url.'">
                </div>
            </div>
            <a class="button small expanded success" href="download.php?torrent='.$id.'" title="Download torrent">Download torrent</a>
            <a class="button small expanded" id="thumbsup" href="javascript:ThumbsUp('.(int)$torrents['id'].')">Like</a>
            <p>'.$Free_Slot.'</p>
        </div>
        </div>';
$HTMLOUT .= "
<div class='cell medium-9'>
<div class='grid-x grid-margin-x'>
<div class='cell medium-auto card'>
    <span class='label secondary padding-1' tabindex='2' data-tooltip title='{$lang['details_add_sd']}'>
        <i class='fas fa-arrow-up'></i> ".$tseeders."
    </span>
</div>
<div class='cell medium-auto card'>
    <span class='label secondary padding-1' tabindex='2' data-tooltip title='{$lang['details_add_lc']}'>
        <i class='fas fa-arrow-down'></i> ".$tleechers."
    </span>
</div>
<div class='cell medium-auto card'>
    <span class='label secondary padding-1' tabindex='2' data-tooltip title='{$lang['details_peer_total']}'>
        <i class='fas fa-dna'></i> {$tpeers}
    </span>
</div>
<div class='cell medium-auto card'>
    <span class='label secondary padding-1' tabindex='2' data-tooltip title='{$lang['details_snatched']}'>
        <i class='fas fa-check'></i> ".$tsnatched."
    </span>
</div>
<div class='cell medium-auto card'>
    <span class='label secondary padding-1' tabindex='2' data-tooltip title='{$lang['details_size']}'>
    <i class='fas fa-chart-pie'></i> ".$tsize."
    </span>
</div>
</div>
";
require_once(BLOCK_DIR.'details/imdb.php');
require_once(BLOCK_DIR.'details/tvmaze.php');
//require_once(BLOCK_DIR.'details/new_tvmaze.php');
require_once(BLOCK_DIR.'details/youtube.php');
$HTMLOUT .= "<div class='table-scroll'>
    <table class='striped'>
	<tbody>
        <tr>
        <td>{$lang['details_tags']}</td>
        <td>".$keywords."</td>
        </tr>";
$HTMLOUT .= '<tr>
        <td>'.$lang['details_add_karma1'].'</td>
        <td><b>'.$lang['details_add_karma2'].''.(int)$torrents['points'].''.$lang['details_add_karma3'].''.$my_points.''.$lang['details_add_karma4'].'<br /><br />
        <a href="coins.php?id='.$id.'&amp;points=10"><img src="'.$TRINITY20['pic_base_url'].'10coin.png" alt="10" title="'.$lang['details_add_kar10'].'" /></a>&nbsp;&nbsp;
        <a href="coins.php?id='.$id.'&amp;points=20"><img src="'.$TRINITY20['pic_base_url'].'20coin.png" alt="20" title="'.$lang['details_add_kar20'].'" /></a>&nbsp;&nbsp;
        <a href="coins.php?id='.$id.'&amp;points=50"><img src="'.$TRINITY20['pic_base_url'].'50coin.png" alt="50" title="'.$lang['details_add_kar50'].'" /></a>&nbsp;&nbsp;
        <a href="coins.php?id='.$id.'&amp;points=100"><img src="'.$TRINITY20['pic_base_url'].'100coin.png" alt="100" title="'.$lang['details_add_kar100'].'" /></a>&nbsp;&nbsp;
        <a href="coins.php?id='.$id.'&amp;points=200"><img src="'.$TRINITY20['pic_base_url'].'200coin.png" alt="200" title="'.$lang['details_add_kar200'].'" /></a>&nbsp;&nbsp;
        <a href="coins.php?id='.$id.'&amp;points=500"><img src="'.$TRINITY20['pic_base_url'].'500coin.png" alt="500" title="'.$lang['details_add_kar500'].'" /></a>&nbsp;&nbsp;
        <a href="coins.php?id='.$id.'&amp;points=1000"><img src="'.$TRINITY20['pic_base_url'].'1000coin.png" alt="1000" title="'.$lang['details_add_kar1000'].'" /></a></b>&nbsp;&nbsp;
        <br />'.$lang['details_add_karma'].'</td></tr>';
/** pdq's ratio afer d/load **/
$downl = ($CURUSER["downloaded"] + $torrents["size"]);
$sr = $CURUSER["uploaded"] / $downl;
switch (true) {
    case ($sr >= 4):
        $s = "w00t";
        break;

    case ($sr >= 2):
        $s = "grin";
        break;

    case ($sr >= 1):
        $s = "smile1";
        break;

    case ($sr >= 0.5):
        $s = "noexpression";
        break;

    case ($sr >= 0.25):
        $s = "sad";
        break;

    case ($sr > 0.00):
        $s = "cry";
        break;

    default;
        $s = "w00t";
        break;
}
$sr = floor($sr * 1000) / 1000;
$sr = "<font color='".get_ratio_color($sr)."'>".number_format($sr, 3)."</font>&nbsp;&nbsp;<img src='pic/smilies/{$s}.gif' alt='' />";
if ($torrents['free'] >= 1 || $torrents['freetorrent'] >= 1 || $isfree['yep'] || ($free_slot || $double_slot == 'yes') || $CURUSER['free_switch'] != 0) {
    $HTMLOUT .= "<tr>
        <td>{$lang['details_add_ratio1']}</td>
        <td class='details-text-ellipsis'><del>{$sr}&nbsp;&nbsp;{$lang['details_add_ratio2']}</del> <b><font size='' color='#FF0000'>{$lang['details_add_ratio3']}</font></b>{$lang['details_add_ratio4']}</td></tr>";
} else {
    $HTMLOUT .= "<tr>
        <td>{$lang['details_add_ratio1']}</td>
        <td>{$sr}&nbsp;&nbsp;{$lang['details_add_ratio2']}</td></tr>";
}
//==End
function hex_esc($matches)
{
    return sprintf("%02x", ord($matches[0]));
}

$HTMLOUT .= tr("{$lang['details_info_hash']}",
    '<div class="details-text-ellipsis">'.preg_replace_callback('/./s', "hex_esc", hash_pad($torrents["info_hash"])).'</div>', true);
$HTMLOUT .= "</tbody></table></div></div>";
$HTMLOUT .= "</div>";
