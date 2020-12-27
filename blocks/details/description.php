<?php
$HTMLOUT.= "<div class='row'>
<div class='col-md-12'>";
if (!empty($torrents_txt["descr"])) {
    $HTMLOUT.= "
    <table class='striped'>
    <tr><td><b>{$lang['details_description']}</b></td></tr>
    <tr>
    <td>
    " . str_replace(array("\n","  ") , array("<br />\n","&nbsp; ") , format_comment($torrents_txt["descr"])) . "<!--</div>--></td></tr></table>";
}

$HTMLOUT .= '</div><!-- closing col md 12 --></div><!-- closing row -->';
$HTMLOUT.= "<div class='row'>
<div class='col-md-12'>";
//== Similar Torrents mod
$searchname = substr($torrents['name'], 0, 6);
$query1 = str_replace(" ", ".", sqlesc("%" . $searchname . "%"));
$query2 = str_replace(".", " ", sqlesc("%" . $searchname . "%"));
if (($sim_torrents = $cache->get('similiar_tor_' . $id)) === false) {
    $r = sql_query("SELECT id, name, size, added, seeders, leechers, category FROM torrents WHERE name LIKE {$query1} AND id <> " . sqlesc($id) . " OR name LIKE {$query2} AND id <> " . sqlesc($id) . " ORDER BY name") or sqlerr(__FILE__, __LINE__);
    while ($sim_torrent = mysqli_fetch_assoc($r)) $sim_torrents[] = $sim_torrent;
    $cache->set('similiar_tor_' . $id, $sim_torrents, 86400);
}
if ($sim_torrents && count($sim_torrents) > 0) {
    $sim_torrent = "<div class='table-scroll'>
	<table class='table'>" . "
        <thead>
        <tr>
        <th>{$lang['details_type']}</th>
        <th>{$lang['details_add_inf1']}</th>
        <th>{$lang['details_size']}</th>
        <th>{$lang['details_added']}</th>
        <th>{$lang['details_add_inf2']}</th>
        <th>{$lang['details_add_inf3']}</th>
        </tr>
        </thead>";
    if ($sim_torrents) {
        foreach ($sim_torrents as $a) {
            $sim_tor['cat_name'] = htmlsafechars($change[$a['category']]['name']);
            $sim_tor['cat_pic'] = htmlsafechars($change[$a['category']]['image']);
            $cat = "<img src=\"pic/caticons/{$CURUSER['categorie_icon']}/{$sim_tor['cat_pic']}\" alt=\"{$sim_tor['cat_name']}\" title=\"{$sim_tor['cat_name']}\" />";
            $name = htmlsafechars(CutName($a["name"]));
            $seeders = (int)$a["seeders"];
            $leechers = (int)$a["leechers"];
            $added = get_date($a["added"], 'DATE', 0, 1);
            $sim_torrent.= "<tbody><tr>
            <td>{$cat}</td>
            <td><a href='details.php?id=" . (int)$a["id"] . "&amp;hit=1'><b>{$name}</b></a></td>
            <td>" . mksize($a['size']) . "</td>
            <td>{$added}</td>
            <td>{$seeders}</td>
            <td>{$leechers}</td></tr></tbody>";
        }
        $sim_torrent.= "</table><div>";
 $HTMLOUT.= "<table class='striped'><tr><td align='right' class='heading'>{$lang['details_similiar']}<a href=\"javascript: klappe_news('a5')\"><img border=\"0\" src=\"pic/plus.png\" id=\"pica5".(int)$a['id']."\" alt=\"[Hide/Show]\" title=\"[Hide/Show]\" /></a><div id=\"ka5\" style=\"display: none;\"><br />$sim_torrent</div></td></tr></table>";
    } else {
        if (empty($sim_torrents)) $HTMLOUT.= "
        <table class='striped'>\n
        <tr>
        <td>{$lang['details_sim_no1']}" . htmlsafechars($torrents["name"]) . "{$lang['details_sim_no2']}</td>
        </tr></table>";
    }
}
$HTMLOUT .= '</div><!-- closing col md 12 --></div><!-- closing row -->';
?>