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
foreach ($categorie as $key => $value) $change[$value['id']] = array(
    'id' => $value['id'],
    'name' => $value['name'],
    'image' => $value['image']
);
if (($top10tvs_24 = $cache->get('top10_tv_24_')) === false) {
    $tortime24tv = $_SERVER['REQUEST_TIME'] - 86400;
    $res = sql_query("SELECT id, times_completed, seeders, leechers, name from torrents WHERE last_action >= {$tortime24tv} AND category IN (".join(", ",$TRINITY20['tv_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}") or sqlerr(__FILE__, __LINE__);
    while ($top10tv_24 = mysqli_fetch_assoc($res)) 
		$top10tvs_24[] = $top10tv_24;
    $cache->set('top10_tv_24_', $top10tvs_24);
}
    $HTMLOUT.= "
            <div class='table-scroll'>
            <table class='stripped'>
            <thead><tr>
            <th scope='col'><b>*</b></th>
            <th scope='col'><b>Top 10 torrents in 24hrs in TV</b></th>
			<th scope='col'><i class='fas fa-check'></i></th>
            <th scope='col'><i class='fas fa-arrow-up'></i></th>
            <th scope='col'><i class='fas fa-arrow-down'></i></th>
            </tr></thead>";
	if ($top10tvs_24) {
		$counter = 1;
        foreach ($top10tvs_24 as $top10tvs24) {
            $torrname = htmlsafechars($top10tvs24['name']);
            if (strlen($torrname) > 50) 
				$torrname = substr($torrname, 0, 50) . "...";
            $HTMLOUT.= "
            <tbody><tr>
            <th scope='row'>". $counter++ ."</th>
            <td><a href=\"{$TRINITY20['baseurl']}/details.php?id=" . (int)$top10tvs24['id'] . "&amp;hit=1\">{$torrname}</a></td>
			<td>" . (int)$top10tvs24['times_completed'] . "</td>
            <td>" . (int)$top10tvs24['seeders'] . "</td>
            <td>" . (int)$top10tvs24['leechers'] . "</td>     
	        </tr></tbody>";
        }
    } else {
        //== If there are no torrents
        if (empty($top10tvs_24)) 
        $HTMLOUT.= "<div class='table-scroll'><table class='stripped'><tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
    }
    $HTMLOUT.= "</table></div>";
//==End	
// End Class
// End File
