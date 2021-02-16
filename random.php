<?php
// Random Torrent by pdq
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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
dbconn();
loggedinorreturn();
$lang = load_language('global');
/** got code help from system =] **/
$where = '';
if ($CURUSER['notifs']) {
    $parts = preg_split('`[\[\]]`', $CURUSER['notifs'], -1, PREG_SPLIT_NO_EMPTY);
    $cats = array(
        998,
        999
    ); // junk data
    foreach ($parts as $x) if (substr($x, 0, 3) === 'cat') $cats[] = substr($x, 3);
    $where = (count($cats) === 2) ? '' : 'WHERE category IN(' . implode(',', $cats) . ') AND visible=\'yes\'';
}
/** end **/
// possible to shuffle torrents within specific category, overides previous $where
if (isset($_GET['cat'])) {
    $cat = (int)$_GET['cat'];
    $where = 'WHERE category IN (' . sqlesc($cat) . ') AND visible="yes"';
}
$cat_id = (isset($cat) ? '&cat=' . $cat : '');
$res = sql_query('SELECT id FROM torrents ' . $where . ' ORDER BY RAND() LIMIT 1'); //dunno if adding LIMIT here would help any since dies after 1st row
while ([$id] = $res->fetch_array(MYSQLI_BOTH)) {
    if ($id != NULL) {
        header("Location: details.php?id=" . $id . $cat_id . '&random'); //add &random to indicate on details.php random browsing
        die();
    }
}
?>
