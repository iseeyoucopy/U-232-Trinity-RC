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
if (($top10others = $cache->get('top10_other_')) === false) {
    $res = sql_query("SELECT id, times_completed, seeders, leechers, name, category from torrents WHERE category IN (".join(", ",$TRINITY20['other_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}") or sqlerr(__FILE__, __LINE__);
    while ($top10other = mysqli_fetch_assoc($res)) 
		$top10others[] = $top10other;
    $cache->set('top10_other_', $top10others);
}
    $HTMLOUT.= "<table class='stack'>
            <thead><tr>
            <th scope='col'><b>*</b></th>
            <th scope='col'><b>Top 10 torrents of all in Other Categories</b></th>
			<th scope='col'><i class='fas fa-check'></i></th>
            <th scope='col'><i class='fas fa-arrow-up'></i></th>
            <th scope='col'><i class='fas fa-arrow-down'></i></th></tr></thead>";
	if ($top10others) {
		$counter = 1;
        foreach ($top10others as $top10otherarr) {
            $top10otherarr['cat_name'] = htmlsafechars($change[$top10otherarr['category']]['name']);
	    $top10otherarr['cat_pic'] = htmlsafechars($change[$top10otherarr['category']]['image']);
            $torrname = htmlsafechars($top10otherarr['name']);
            if (strlen($torrname) > 50) 
				$torrname = substr($torrname, 0, 50) . "...";
            $HTMLOUT.= "
            <tbody><tr>
            <th scope='row'>". $counter++ ."</th>
            <td><a href=\"{$TRINITY20['baseurl']}/details.php?id=" . (int)$top10otherarr['id'] . "&amp;hit=1\">{$torrname}</a></td>
			<td>" . (int)$top10otherarr['times_completed'] . "</td>
          <td>" . (int)$top10otherarr['seeders'] . "</td>
          <td>" . (int)$top10otherarr['leechers'] . "</td>     
	 </tr></tbody>";
        }
    } else {
        //== If there are no torrents
        if (empty($top10others)) $HTMLOUT.= "<tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
    }
$HTMLOUT.= "</table>";
//==End	
// End Class
// End File
