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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
require_once INCL_DIR.'pager_functions.php';
require_once INCL_DIR.'torrenttable_functions.php';
require_once INCL_DIR.'html_functions.php';
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('mytorrents'), load_language('torrenttable_functions'));
$stdfoot = [
    /** include js **/
    'js' => [
        'wz_tooltip',
    ],
];
$HTMLOUT = '';
if (isset($_GET['sort']) && isset($_GET['type'])) {
    $column = '';
    $ascdesc = '';
    $_valid_sort = [
        'id',
        'name',
        'numfiles',
        'comments',
        'added',
        'size',
        'times_completed',
        'seeders',
        'leechers',
        'owner',
    ];
    $column = isset($_GET['sort']) && isset($_valid_sort[(int)$_GET['sort']]) ? $_valid_sort[(int)$_GET['sort']] : $_valid_sort[0];
    switch (htmlspecialchars($_GET['type'])) {
        case 'asc':
            $ascdesc = "ASC";
            $linkascdesc = "asc";
            break;

        case 'desc':
            $ascdesc = "DESC";
            $linkascdesc = "desc";
            break;

        default:
            $ascdesc = "DESC";
            $linkascdesc = "desc";
            break;
    }
    $orderby = "ORDER BY torrents.".$column." ".$ascdesc;
    $pagerlink = "sort=".(int)$_GET['sort']."&amp;type=".$linkascdesc."&amp;";
} else {
    $orderby = "ORDER BY torrents.sticky ASC, torrents.id DESC";
    $pagerlink = "";
}
$where = "WHERE owner = ".sqlesc($CURUSER["id"])." AND banned != 'yes'";
$res = sql_query("SELECT COUNT(id) FROM torrents $where");
$row = $res->fetch_row();
$count = (int)$row[0];
if ($count === 0) {
    $HTMLOUT .= "{$lang['mytorrents_no_torrents']}";
    $HTMLOUT .= "{$lang['mytorrents_no_uploads']}";
} else {
    $torrentsperpage = $CURUSER["torrentsperpage"];
    if (!$torrentsperpage) {
        $torrentsperpage = 20;
    }
    $pager = pager($torrentsperpage, $count, "mytorrents.php?{$pagerlink}");
    $res = sql_query("SELECT id, search_text, category, leechers, seeders, bump, tags, release_group, subs, name, times_completed, size, added, poster, descr, type, free, freetorrent, silver, comments, numfiles, filename, anonymous, sticky, nuked, vip, nukereason, newgenre, description, owner, username, youtube, checked_by, owner, IF(num_ratings < {$TRINITY20['minvotes']}, NULL, ROUND(rating_sum / num_ratings, 1)) AS rating, id, name, save_as, numfiles, added, size, views, visible, hits, times_completed, category, description, username FROM torrents $where $orderby ".$pager['limit']);
    $HTMLOUT .= $pager['pagertop'];
    $HTMLOUT .= "<br />";
    $HTMLOUT .= torrenttable($res, "mytorrents");
    $HTMLOUT .= $pager['pagerbottom'];
}
echo stdhead($CURUSER["username"]."'s torrents").$HTMLOUT.stdfoot($stdfoot);
?>
