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
    ($res_tvw = sql_query("SELECT id, times_completed, seeders, leechers, name from torrents WHERE last_action >= {$tortime24tv} AND category IN (".implode(", ",$TRINITY20['tv_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}")) || sqlerr(__FILE__, __LINE__);
    while ($top10tvs_week = $res_tvw->fetch_assoc()) {
        $top10tv_week = (array) $top10tv_week;
        $top10tv_week[] = $top10tvs_week;
    }
    $cache->set('top10_tv_week_', $top10tv_week);
}
    $HTMLOUT.= "
            <table class='top10'>
            <tr>
            <th><b>*</b></th>
            <th><b>Latest 10 torrents in a week in TV</b></th>
			<th><i class='fas fa-check'></i></th>
            <th><i class='fas fa-arrow-up'></i></th>
            <th><i class='fas fa-arrow-down'></i></th>
            </tr>";
	if ($top10tv_week) {
     $counter = 1;
     foreach ($top10tv_week as $top10tvsweek) {
         $torrname = htmlsafechars($top10tvsweek['name']);
         if (strlen($torrname) > 50) 
				$torrname = substr($torrname, 0, 50) . "...";
         $HTMLOUT.= "
            <tr>
            <td>". $counter++ ."</td>
            <td><a class ='float-left' href='{$TRINITY20['baseurl']}/details.php?id=" . (int)$top10tvsweek['id'] . "&amp;hit=1'>{$torrname}</a></td>
			<td>" . (int)$top10tvsweek['times_completed'] . "</td>
            <td>" . (int)$top10tvsweek['seeders'] . "</td>
            <td>" . (int)$top10tvsweek['leechers'] . "</td>     
	        </tr>";
     }
 } elseif (empty($top10tv_week)) {
     $HTMLOUT.= "<tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
 }
    $HTMLOUT.= "</table>";
//==End	
// End Class
// End File
