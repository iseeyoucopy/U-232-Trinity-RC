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
if (($top10music_all = $cache->get($cache_keys['top10_music_all'])) === false) {
    ($res_musicall = sql_query("SELECT id, times_completed, seeders, poster, leechers, name, category from torrents WHERE category IN (".implode(", ",
            $TRINITY20['music_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}")) || sqlerr(__FILE__,
        __LINE__);
    while ($top10musicall = $res_musicall->fetch_assoc()) {
        $top10music_all = (array)$top10music_all;
        $top10music_all[] = $top10musicall;
    }
    $cache->set($cache_keys['top10_music_all'], $top10music_all);
}
$HTMLOUT .= "<table class='top10'>
            <tr>
            <th><b>*</b></th>
            <th><b>Top 10 torrents of all in Music</b></th>
			<th><i class='fas fa-check'></i></th>
            <th><i class='fas fa-arrow-up'></i></th>
            <th><i class='fas fa-arrow-down'></i></th></tr>";
if ($top10music_all) {
    $counter = 1;
    foreach ($top10music_all as $top10music_all_arr) {
        if (is_array($top10music_all_arr)) {
            $top10music_all_arr['cat_name'] = htmlspecialchars($change[$top10music_all_arr['category']]['name']);
            $top10music_all_arr['cat_pic'] = htmlspecialchars($change[$top10music_all_arr['category']]['image']);
            $torrname = htmlspecialchars($top10music_all_arr['name']);
            if (strlen($torrname) > 50) {
                $torrname = substr($torrname, 0, 50)."...";
            }
            $HTMLOUT .= "
                <tr>
                <td>".$counter++."</td>
                <td><a class ='float-left' href='{$TRINITY20['baseurl']}/details.php?id=".(int)$top10music_all_arr['id']."&amp;hit=1'>{$torrname}</a></td>
                <td>".(int)$top10music_all_arr['times_completed']."</td>
            <td>".(int)$top10music_all_arr['seeders']."</td>
            <td>".(int)$top10music_all_arr['leechers']."</td>     
        </tr>";
        }
    }
} elseif (empty($top10music_all)) {
    $HTMLOUT .= "<tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
}
$HTMLOUT .= "</table>";
//==End	
// End Class
// End File
