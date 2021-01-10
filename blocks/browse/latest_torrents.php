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
$categorie = genrelist();
foreach ($categorie as $key => $value) $change[$value['id']] = array(
    'id' => $value['id'],
    'name' => $value['name'],
    'image' => $value['image']
);
//== O9 Top 5 and last5 torrents with tooltip
$HTMLOUT.= "<script src='{$TRINITY20['baseurl']}/scripts/wz_tooltip.js'></script>";
$HTMLOUT.="<div class='card'>
	<div class='card-header'>
		<label for='checkbox_4' class='text-left'>{$lang['index_latest']}</label>
	</div>
	<div class='card-section'>
";
if (($last5torrents = $cache->get('last5_tor_')) === false) {
    $sql = "SELECT id, seeders, poster, leechers, name, category FROM torrents WHERE visible='yes' ORDER BY added DESC LIMIT {$TRINITY20['latest_torrents_limit']}";
    $result = sql_query($sql) or sqlerr(__FILE__, __LINE__);
    while ($last5torrent = mysqli_fetch_assoc($result)) $last5torrents[] = $last5torrent;
    $cache->set('last5_tor_', $last5torrents, $TRINITY20['expires']['last5_torrents']);
}
if ($last5torrents && count($last5torrents) > 0) {
    $HTMLOUT.= "<div class='module'><div class='tbadge tbadge-new'></div>
    	        <table class='table table-bordered'>
                <thead><tr>
                <th scope='col'><b>{$lang['last5torrents_type']}</b></th>
                <th scope='col'><b>{$lang['last5torrents_name']}</b></th>
                <th scope='col'>{$lang['last5torrents_seeders']}</th>
                <th scope='col'>{$lang['last5torrents_leechers']}</th>
				<th scope='col'>{$lang['top5torrents_health']}</th>
                </tr></thead>";
    if ($last5torrents) {
        foreach ($last5torrents as $last5torrentarr) {
            $last5torrentarr['cat_name'] = htmlsafechars($change[$last5torrentarr['category']]['name']);
	    $last5torrentarr['cat_pic'] = htmlsafechars($change[$last5torrentarr['category']]['image']);
            $thealth = health($last5torrentarr['leechers'], $last5torrentarr['seeders']);
            $torrname = htmlsafechars($last5torrentarr['name']);
            if (strlen($torrname) > 50) $torrname = substr($torrname, 0, 50) . "...";
            $poster = empty($last5torrentarr["poster"]) ? "<img src=\'{$TRINITY20['pic_base_url']}noposter.jpg\' width=\'150\' height=\'220\' />" : "<img src=\'" . htmlsafechars($last5torrentarr['poster']) . "\' width=\'150\' height=\'220\' />";
            $HTMLOUT.= "
            <tbody><tr>
            <th scope='row'><img src='pic/caticons/{$CURUSER['categorie_icon']}/" . htmlsafechars($last5torrentarr["cat_pic"]) . "' alt='" . htmlsafechars($last5torrentarr["cat_name"]) . "' title='" . htmlsafechars($last5torrentarr["cat_name"]) . "' /></th>
            <td><a href=\"{$TRINITY20['baseurl']}/details.php?id=" . (int)$last5torrentarr['id'] . "&amp;hit=1\"></a><a href=\"{$TRINITY20['baseurl']}/details.php?id=" . (int)$last5torrentarr['id'] . "&amp;hit=1\" onmouseover=\"Tip('<b>{$lang['index_ltst_name']}" . htmlsafechars($last5torrentarr['name']) . "</b><br /><b>{$lang['index_ltst_seeder']}" . (int)$last5torrentarr['seeders'] . "</b><br /><b>{$lang['index_ltst_leecher']}" . (int)$last5torrentarr['leechers'] . "</b><br />$poster');\" onmouseout=\"UnTip();\">{$torrname}</a></td>
            <td><span class='badge'>".(int)$last5torrentarr['seeders']."</span></td>
            <td><span class='badge'>".(int)$last5torrentarr['leechers']."</span></td>
<td><span class='badge'>$thealth</td>             
	    </tr></tbody>";
        }
        $HTMLOUT.= "</table>";
    } else {
        //== If there are no torrents
        if (empty($last5torrents)) $HTMLOUT.= "<table class='table table-bordered'><tbody><tr><td class='text-left' colspan='3'>{$lang['last5torrents_no_torrents']}</td></tr></tbody></table>";
    }
	$HTMLOUT.="</div>";
}
$HTMLOUT.="</div></div>";
//== End 09 last5 and top5 torrents
//==End	
// End Class
// End File
