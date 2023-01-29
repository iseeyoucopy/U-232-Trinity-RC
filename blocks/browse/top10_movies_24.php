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

if (($top10movies24 = $cache->get($cache_keys['top10_mov_24'])) === false) {
    $tortime24movie = $_SERVER['REQUEST_TIME'] - 86400;
    ($res_m24 = sql_query("SELECT id, times_completed, seeders, poster, leechers, name, category from torrents WHERE last_action >= {$tortime24movie} AND category IN (".implode(", ",
            $TRINITY20['movie_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}")) || sqlerr(__FILE__,
        __LINE__);
    while ($top10movie24 = $res_m24->fetch_assoc()) {
        $top10movies24 = (array)$top10movies24;
        $top10movies24[] = $top10movie24;
    }
    $cache->set($cache_keys['top10_mov_24'], $top10movies24);
}
$HTMLOUT .= "<table class='top10'>
            <tr>
            <th><b>*</b></th>
            <th><b>Top 10 torrents in a week in Movies</b></th>
			<th><i class='fas fa-check'></i></th>
            <th><i class='fas fa-arrow-up'></i></th>
            <th><i class='fas fa-arrow-down'></i></th></tr>";
if ($top10movies24) {
    $counter = 1;
    foreach ($top10movies24 as $top10movie24arr) {
        if (is_array($top10movie24arr)) {
            $top10movie24arr['cat_name'] = htmlspecialchars($change[$top10movie24arr['category']]['name']);
            $top10movie24arr['cat_pic'] = htmlspecialchars($change[$top10movie24arr['category']]['image']);
            $torrname = htmlspecialchars($top10movie24arr['name']);
            if (strlen($torrname) > 50) {
                $torrname = substr($torrname, 0, 50)."...";
            }
            $HTMLOUT .= "
                <tr>
                <td>".$counter++."</td>
                <td><a class ='float-left' href='{$TRINITY20['baseurl']}/details.php?id=".(int)$top10movie24arr['id']."&amp;hit=1'>{$torrname}</a></td>
                <td>".(int)$top10movie24arr['times_completed']."</td>
            <td>".(int)$top10movie24arr['seeders']."</td>
            <td>".(int)$top10movie24arr['leechers']."</td>     
            </tr>";
        }
    }
} elseif (empty($top10movies24)) {
    $HTMLOUT .= "<tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
}
$HTMLOUT .= "</table>";
//==End	
// End Class
// End File
