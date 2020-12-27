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
    $res = sql_query("SELECT id, times_completed, seeders, leechers, name from torrents ORDER BY seeders + leechers DESC LIMIT {$INSTALLER09['latest_torrents_limit']}") or sqlerr(__FILE__, __LINE__);
    while ($top10movie_all = mysqli_fetch_assoc($res)) 
		$top10movies_all[] = $top10movie_all;
    $cache->set('top10_movies_all_', $top10movies_all);
}
if (!empty($top10movies_all)) {
    $HTMLOUT.= "
            <div class='table-scroll'>
            <table class='stripped'>
            <thead><tr>
            <th scope='col'><b>*</b></th>
            <th scope='col'><b>TOP 10 Torrents of All Time In Movies</b></th>
			<th scope='col'><i class='fas fa-check'></i></th>
            <th scope='col'><i class='fas fa-arrow-up'></i></th>
            <th scope='col'><i class='fas fa-arrow-down'></i></th>
            </tr></thead>";
	if ($top10movies_all) {
		$counter = 1;
        foreach ($top10movies_all as $top10moviesall) {
            $torrname = htmlsafechars($top10moviesall['name']);
            if (strlen($torrname) > 50) 
				$torrname = substr($torrname, 0, 50) . "...";
            $HTMLOUT.= "
            <tbody><tr>
            <th scope='row'>". $counter++ ."</th>
            <td><a href=\"{$INSTALLER09['baseurl']}/details.php?id=" . (int)$top10moviesall['id'] . "&amp;hit=1\">{$torrname}</a></td>
			<td>" . (int)$top10moviesall['times_completed'] . "</td>
            <td>" . (int)$top10moviesall['seeders'] . "</td>
            <td>" . (int)$top10moviesall['leechers'] . "</td>     
	        </tr></tbody>";
        }
    } else {
        //== If there are no torrents
        if (empty($top10movies_all)) $HTMLOUT.= "<div class='table-scroll'><table class='stripped'><tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
    }
}
$HTMLOUT.= "</table></div>";
//==End	
// End Class
// End File
