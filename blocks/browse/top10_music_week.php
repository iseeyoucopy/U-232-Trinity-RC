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
if (($top10music_week = $cache->get('top10_music_week_')) === false) {
    $tortimeweekmusic = $_SERVER['REQUEST_TIME'] - 604800;
    $res_musicw = sql_query("SELECT id, times_completed, seeders, poster, leechers, name, category from torrents WHERE last_action >= {$tortimeweekmusic}  AND category IN (".join(", ",$TRINITY20['music_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}") or sqlerr(__FILE__, __LINE__);
    while ($top10musicweek = $res_musicw->fetch_assoc()) 
		$top10music_week[] = $top10musicweek;
    $cache->set('top10_music_week_', $top10music_week);
}
    $HTMLOUT.= "<table class='top10'>
            <tr>
            <th><b>*</b></th>
            <th><b>Top 10 torrents in a week in Music</b></th>
			<th><i class='fas fa-check'></i></th>
            <th><i class='fas fa-arrow-up'></i></th>
            <th><i class='fas fa-arrow-down'></i></th></tr>";
	if ($top10music_week) {
		$counter = 1;
        foreach ($top10music_week as $top10music_w_arr) {
            $top10music_w_arr['cat_name'] = htmlsafechars($change[$top10music_w_arr['category']]['name']);
	    $top10music_w_arr['cat_pic'] = htmlsafechars($change[$top10music_w_arr['category']]['image']);
            $torrname = htmlsafechars($top10music_w_arr['name']);
            if (strlen($torrname) > 50) 
				$torrname = substr($torrname, 0, 50) . "...";
            $HTMLOUT.= "
            <tr>
            <td>". $counter++ ."</td>
            <td><a class ='float-left' href=\"{$TRINITY20['baseurl']}/details.php?id=" . (int)$top10music_w_arr['id'] . "&amp;hit=1\">{$torrname}</a></td>
			<td>" . (int)$top10music_w_arr['times_completed'] . "</td>
          <td>" . (int)$top10music_w_arr['seeders'] . "</td>
          <td>" . (int)$top10music_w_arr['leechers'] . "</td>     
	 </tr>";
        }
    } else {
        //== If there are no torrents
        if (empty($top10music_week)) $HTMLOUT.= "<tr><td>{$lang['top5torrents_no_torrents']}</td></tr>";
    }
$HTMLOUT.= "</table>";
//==End	
// End Class
// End File
