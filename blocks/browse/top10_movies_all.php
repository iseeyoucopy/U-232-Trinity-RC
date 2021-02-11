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
if (($top10movies_all = $cache->get('top10_movies_all_')) === false) {
    $res_movall = sql_query("SELECT id, times_completed, seeders, leechers, name from torrents WHERE category IN (".join(", ",$TRINITY20['movie_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}") or sqlerr(__FILE__, __LINE__);
    while ($top10movie_all = $res_movall->fetch_assoc()) 
		$top10movies_all[] = $top10movie_all;
    $cache->set('top10_movies_all_', $top10movies_all);
}
    $HTMLOUT.= "<table class='top10'>
            <tr>
            <th><b>*</b></th>
            <th><b>TOP 10 Torrents of All Time In Movies</b></th>
			<th><i class='fas fa-check'></i></th>
            <th><i class='fas fa-arrow-up'></i></th>
            <th><i class='fas fa-arrow-down'></i></th>
            </tr>";
	if ($top10movies_all) {
		$counter = 1;
        foreach ($top10movies_all as $top10moviesall) {
            $torrname = htmlsafechars($top10moviesall['name']);
            if (strlen($torrname) > 50) 
				$torrname = substr($torrname, 0, 50) . "...";
            $HTMLOUT.= "
            <tr>
            <td>". $counter++ ."</td>
            <td><a class ='float-left' href='{$TRINITY20['baseurl']}/details.php?id=" . (int)$top10moviesall['id'] . "&amp;hit=1'>{$torrname}</a></td>
			<td>" . (int)$top10moviesall['times_completed'] . "</td>
            <td>" . (int)$top10moviesall['seeders'] . "</td>
            <td>" . (int)$top10moviesall['leechers'] . "</td>     
	        </tr>";
        }
    } else {
        //== If there are no torrents
        if (empty($top10movies_all)) $HTMLOUT.= "<tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
    }
$HTMLOUT.= "</table>";
//==End	
// End Class
// End File
