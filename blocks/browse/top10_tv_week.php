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
//== Top 10 torrents in past 24 hours
if (($top10torrents = $cache->get('top10_tor_')) === false) {
    $res = sql_query("SELECT id, times_completed, seeders, poster, leechers, name, category from torrents ORDER BY seeders + leechers DESC LIMIT {$INSTALLER09['latest_torrents_limit']}") or sqlerr(__FILE__, __LINE__);
    while ($top10torrent = mysqli_fetch_assoc($res)) 
		$top10torrents[] = $top10torrent;
    $cache->set('top10_tor_', $top10torrents);
}
if (!empty($top10torrents)) {
    $HTMLOUT.= "<table class='stack'>
            <thead><tr>
            <th scope='col'><b>*</b></th>
            <th scope='col'><b>Top 10 torrents in past 24 hours</b></th>
			<th scope='col'><i class='fas fa-check'></i></th>
            <th scope='col'><i class='fas fa-arrow-up'></i></th>
            <th scope='col'><i class='fas fa-arrow-down'></i></th></tr></thead>";
	if ($top10torrents) {
		$counter = 1;
        foreach ($top10torrents as $top10torrentarr) {
            $top10torrentarr['cat_name'] = htmlsafechars($change[$top10torrentarr['category']]['name']);
	    $top10torrentarr['cat_pic'] = htmlsafechars($change[$top10torrentarr['category']]['image']);
            $torrname = htmlsafechars($top10torrentarr['name']);
            if (strlen($torrname) > 50) 
				$torrname = substr($torrname, 0, 50) . "...";
            $HTMLOUT.= "
            <tbody><tr>
            <th scope='row'>". $counter++ ."</th>
            <td><a href=\"{$INSTALLER09['baseurl']}/details.php?id=" . (int)$top10torrentarr['id'] . "&amp;hit=1\">{$torrname}</a></td>
			<td>" . (int)$top10torrentarr['times_completed'] . "</td>
          <td>" . (int)$top10torrentarr['seeders'] . "</td>
          <td>" . (int)$top10torrentarr['leechers'] . "</td>     
	 </tr></tbody>";
        }
    } else {
        //== If there are no torrents
        if (empty($top10torrents)) $HTMLOUT.= "<tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
    }
}
$HTMLOUT.= "</table>";
//==End	
// End Class
// End File
