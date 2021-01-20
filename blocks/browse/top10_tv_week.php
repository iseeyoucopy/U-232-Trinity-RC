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
if (($top10tv_week = $cache->get('top10_tv_week_')) === false) {
    $tortime24tv = $_SERVER['REQUEST_TIME'] - 604800;
    $res_tvw = sql_query("SELECT id, times_completed, seeders, leechers, name from torrents WHERE last_action >= {$tortime24tv} AND category IN (".join(", ",$TRINITY20['tv_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}") or sqlerr(__FILE__, __LINE__);
    while ($top10tvs_week = mysqli_fetch_assoc($res_tvw)) 
		$top10tv_week[] = $top10tvs_week;
    $cache->set('top10_tv_week_', $top10tv_week);
}
    $HTMLOUT.= "
            <div class='table-scroll'>
            <table class='stripped'>
            <thead><tr>
            <th scope='col'><b>*</b></th>
            <th scope='col'><b>Latest 10 torrents in a week in TV</b></th>
			<th scope='col'><i class='fas fa-check'></i></th>
            <th scope='col'><i class='fas fa-arrow-up'></i></th>
            <th scope='col'><i class='fas fa-arrow-down'></i></th>
            </tr></thead>";
	if ($top10tv_week) {
		$counter = 1;
        foreach ($top10tv_week as $top10tvsweek) {
            $torrname = htmlsafechars($top10tvsweek['name']);
            if (strlen($torrname) > 50) 
				$torrname = substr($torrname, 0, 50) . "...";
            $HTMLOUT.= "
            <tbody><tr>
            <th scope='row'>". $counter++ ."</th>
            <td><a href=\"{$TRINITY20['baseurl']}/details.php?id=" . (int)$top10tvsweek['id'] . "&amp;hit=1\">{$torrname}</a></td>
			<td>" . (int)$top10tvsweek['times_completed'] . "</td>
            <td>" . (int)$top10tvsweek['seeders'] . "</td>
            <td>" . (int)$top10tvsweek['leechers'] . "</td>     
	        </tr></tbody>";
        }
    } else {
        //== If there are no torrents
        if (empty($top10tv_week)) 
        $HTMLOUT.= "<div class='table-scroll'><table class='stripped'><tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
    }
    $HTMLOUT.= "</table></div>";
//==End	
// End Class
// End File