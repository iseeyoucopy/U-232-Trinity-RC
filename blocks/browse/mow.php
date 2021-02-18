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
//== Best film of the week
$categorie = genrelist();
foreach ($categorie as $key => $value) {
    $change[$value['id']] = [
        'id' => $value['id'],
        'name' => $value['name'],
        'image' => $value['image'],
    ];
}
if (($motw_cached = $cache->get('top_movie_2')) === false) {
    ($motw = sql_query("SELECT torrents.id, torrents.leechers, torrents.seeders, torrents.category, torrents.name, torrents.times_completed FROM torrents INNER JOIN avps ON torrents.id=avps.value_u WHERE avps.arg='bestfilmofweek' LIMIT 1")) || sqlerr(__FILE__,
        __LINE__);
    while ($motw_cache = $motw->fetch_assoc()) {
        $motw_cached = (array)$motw_cached;
        $motw_cached[] = $motw_cache;
    }
    $cache->set('top_movie_2', $motw_cached, 0);
}
$HTMLOUT .= "{$lang['index_mow_title']}
                <table class='stack'>
					<thead>
						<tr>
							<th>{$lang['index_mow_type']}</th>
							<th>{$lang['index_mow_name']}</th>
							<th class='col-md-2 text-center'>{$lang['index_mow_snatched']}</th>
							<th class='col-md-2 text-center'>{$lang['index_mow_seeder']}</th>
							<th class='col-md-2 text-center'>{$lang['index_mow_leecher']}</th>
						</tr>
					</thead>";
if ($motw_cached) {
    foreach ($motw_cached as $m_w) {
        if (is_array($m_w)) {
            $mw['cat_name'] = htmlsafechars($change[$m_w['category']]['name']);
            $mw['cat_pic'] = htmlsafechars($change[$m_w['category']]['image']);
            $HTMLOUT .= "
                        <tbody>
                            <tr>
                                <td class='text-center'><img src='pic/caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($mw["cat_pic"])."' alt='".htmlsafechars($mw["cat_name"])."' title='".htmlsafechars($mw["cat_name"])."' /></td>
                                <td><a href='{$TRINITY20['baseurl']}/details.php?id=".(int)$m_w["id"]."'><b>".htmlsafechars($m_w["name"])."</b></a></td>
                                <td class='text-center'><span class='badge'>".(int)$m_w["times_completed"]."</span></td>
                                <td class='text-center'><span class='badge'>".(int)$m_w["seeders"]."</span></td>
                                <td class='text-center'><span class='badge'>".(int)$m_w["leechers"]."</span> </td>
                            </tr>
                        </tbody>";
        }
    }
} elseif (empty($motw_cached)) {
    $HTMLOUT .= "<tbody><tr><td>{$lang['index_mow_no']}!</td></tr></tbody>";
}
$HTMLOUT .= "</table>";
//==End
// End Class
// End File
