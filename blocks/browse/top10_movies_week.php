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

if (($top10moviesweek = $cache->get('top10_mov_week_')) === false) {
    $tortimeweekmovie = $_SERVER['REQUEST_TIME'] - 604800;
    $res = sql_query("SELECT id, times_completed, seeders, poster, leechers, name, category from torrents WHERE last_action >= {$tortimeweekmovie} AND category IN (".join(", ",$TRINITY20['movie_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}") or sqlerr(__FILE__, __LINE__);
    while ($top10movieweek = mysqli_fetch_assoc($res)) 
		$top10moviesweek[] = $top10movieweek;
    $cache->set('top10_mov_week_', $top10moviesweek);
}
    $HTMLOUT.= "<table class='stack'>
            <thead><tr>
            <th scope='col'><b>*</b></th>
            <th scope='col'><b>Top 10 torrents in a week in Movies</b></th>
			<th scope='col'><i class='fas fa-check'></i></th>
            <th scope='col'><i class='fas fa-arrow-up'></i></th>
            <th scope='col'><i class='fas fa-arrow-down'></i></th></tr></thead>";
	if ($top10moviesweek) {
		$counter = 1;
        foreach ($top10moviesweek as $top10movieweekarr) {
            $top10movieweekarr['cat_name'] = htmlsafechars($change[$top10movieweekarr['category']]['name']);
	    $top10movieweekarr['cat_pic'] = htmlsafechars($change[$top10movieweekarr['category']]['image']);
            $torrname = htmlsafechars($top10movieweekarr['name']);
            if (strlen($torrname) > 50) 
				$torrname = substr($torrname, 0, 50) . "...";
            $HTMLOUT.= "
            <tbody><tr>
            <th scope='row'>". $counter++ ."</th>
            <td><a href=\"{$TRINITY20['baseurl']}/details.php?id=" . (int)$top10movieweekarr['id'] . "&amp;hit=1\">{$torrname}</a></td>
			<td>" . (int)$top10movieweekarr['times_completed'] . "</td>
          <td>" . (int)$top10movieweekarr['seeders'] . "</td>
          <td>" . (int)$top10movieweekarr['leechers'] . "</td>     
	 </tr></tbody>";
        }
    } else {
        //== If there are no torrents
        if (empty($top10moviesweek)) $HTMLOUT.= "<tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
    }
$HTMLOUT.= "</table>";
//==End	
// End Class
// End File
