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

if (($top10moviesweek = $cache->get($cache_keys['top10_mov_week'])) === false) {
    $tortimeweekmovie = $_SERVER['REQUEST_TIME'] - 604800;
    ($res_movweek = sql_query("SELECT id, times_completed, seeders, poster, leechers, name, category from torrents WHERE last_action >= {$tortimeweekmovie} AND category IN (".implode(", ",
            $TRINITY20['movie_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}")) || sqlerr(__FILE__,
        __LINE__);
    while ($top10movieweek = $res_movweek->fetch_assoc()) {
        $top10moviesweek = (array)$top10moviesweek;
        $top10moviesweek[] = $top10movieweek;
    }
    $cache->set($cache_keys['top10_mov_week'], $top10moviesweek);
}
$HTMLOUT .= "<table class='top10'>
            <tr>
            <th><b>*</b></th>
            <th><b>Top 10 torrents in a week in Movies</b></th>
			<th><i class='fas fa-check'></i></th>
            <th><i class='fas fa-arrow-up'></i></th>
            <th><i class='fas fa-arrow-down'></i></th></tr>";
if ($top10moviesweek) {
    $counter = 1;
    foreach ($top10moviesweek as $top10movieweekarr) {
        if (is_array($top10movieweekarr)) {
            $top10movieweekarr['cat_name'] = htmlspecialchars($change[$top10movieweekarr['category']]['name']);
            $top10movieweekarr['cat_pic'] = htmlspecialchars($change[$top10movieweekarr['category']]['image']);
            $torrname = htmlspecialchars($top10movieweekarr['name']);
            if (strlen($torrname) > 50) {
                $torrname = substr($torrname, 0, 50)."...";
            }
            $HTMLOUT .= "
                <tr>
                <td>".$counter++."</td>
                <td><a class ='float-left' href='{$TRINITY20['baseurl']}/details.php?id=".(int)$top10movieweekarr['id']."&amp;hit=1'>{$torrname}</a></td>
                <td>".(int)$top10movieweekarr['times_completed']."</td>
            <td>".(int)$top10movieweekarr['seeders']."</td>
            <td>".(int)$top10movieweekarr['leechers']."</td>     
        </tr>";
        }
    }
} elseif (empty($top10moviesweek)) {
    $HTMLOUT .= "<tr><td></td><td>{$lang['top5torrents_no_torrents']}</td></tr>";
}
$HTMLOUT .= "</table>";
//==End	
// End Class
// End File
