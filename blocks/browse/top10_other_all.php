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
foreach ($categorie as $key => $value) {
    $change[$value['id']] = [
        'id' => $value['id'],
        'name' => $value['name'],
        'image' => $value['image'],
    ];
}
if (($top10others = $cache->get('top10_other_')) === false) {
    ($res_other = sql_query("SELECT id, times_completed, seeders, leechers, name, category from torrents WHERE category IN (".implode(", ",
            $TRINITY20['other_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}")) || sqlerr(__FILE__,
        __LINE__);
    while ($top10other = $res_other->fetch_assoc()) {
        $top10others = (array)$top10others;
        $top10others[] = $top10other;
    }
    $cache->set('top10_other_', $top10others);
}
$HTMLOUT .= "<table class='top10'>
            <tr>
            <th><b>*</b></th>
            <th><b>Top 10 torrents of all in Other Categories</b></th>
			<th><i class='fas fa-check'></i></th>
            <th><i class='fas fa-arrow-up'></i></th>
            <th><i class='fas fa-arrow-down'></i></th></tr>";
if ($top10others) {
    $counter = 1;
    foreach ($top10others as $top10otherarr) {
        $top10otherarr['cat_name'] = htmlsafechars($change[$top10otherarr['category']]['name']);
        $top10otherarr['cat_pic'] = htmlsafechars($change[$top10otherarr['category']]['image']);
        $torrname = htmlsafechars($top10otherarr['name']);
        if (strlen($torrname) > 50) {
            $torrname = substr($torrname, 0, 50)."...";
        }
        $HTMLOUT .= "
            <tr>
            <td>".$counter++."</td>
            <td><a class ='float-left' href=\"{$TRINITY20['baseurl']}/details.php?id=".(int)$top10otherarr['id']."&amp;hit=1\">{$torrname}</a></td>
			<td>".(int)$top10otherarr['times_completed']."</td>
          <td>".(int)$top10otherarr['seeders']."</td>
          <td>".(int)$top10otherarr['leechers']."</td>     
	 </tr>";
    }
} elseif (empty($top10others)) {
    $HTMLOUT .= "<tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
}
$HTMLOUT .= "</table>";
//==End	
// End Class
// End File
