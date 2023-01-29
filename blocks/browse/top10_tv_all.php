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
foreach ($categorie as $key => $value) {
    $change[$value['id']] = [
        'id' => $value['id'],
        'name' => $value['name'],
        'image' => $value['image'],
    ];
}
if (($top10tvs_all = $cache->get($keys['top10_tv_all'])) === false) {
    ($res_tvall = sql_query("SELECT id, times_completed, seeders, leechers, name from torrents WHERE category IN (".implode(", ",
            $TRINITY20['tv_cats']).") ORDER BY seeders + leechers DESC LIMIT {$TRINITY20['latest_torrents_limit']}")) || sqlerr(__FILE__, __LINE__);
    while ($top10tv_all = $res_tvall->fetch_assoc()) {
        $top10tvs_all = (array)$top10tvs_all;
        $top10tvs_all[] = $top10tv_all;
    }
    $cache->set($keys['top10_tv_all'], $top10tvs_all);
}
$HTMLOUT .= "<table class='top10'>
            <tr>
            <th><b>*</b></th>
            <th><b>TOP 10 Torrents of All Time In TV</b></th>
			<th><i class='fas fa-check'></i></th>
            <th><i class='fas fa-arrow-up'></i></th>
            <th><i class='fas fa-arrow-down'></i></th>
            </tr>";
if ($top10tvs_all) {
    $counter = 1;
    foreach ($top10tvs_all as $top10tvsall) {
        if (is_array($top10tvsall)) {
            $torrname = htmlspecialchars($top10tvsall['name']);
            if (strlen($torrname) > 50) {
                $torrname = substr($torrname, 0, 50)."...";
            }
            $HTMLOUT .= "
                <tr>
                <td>".$counter++."</td>
                <td><a class ='float-left' href='{$TRINITY20['baseurl']}/details.php?id=".(int)$top10tvsall['id']."&amp;hit=1'>{$torrname}</a></td>
                <td>".(int)$top10tvsall['times_completed']."</td>
                <td>".(int)$top10tvsall['seeders']."</td>
                <td>".(int)$top10tvsall['leechers']."</td>     
                </tr>";
        }
    }
} elseif (empty($top10tvs_all)) {
    $HTMLOUT .= "<tbody><tr><td>{$lang['top5torrents_no_torrents']}</td></tr></tbody>";
}
$HTMLOUT .= "</table>";
//==End	
// End Class
// End File
