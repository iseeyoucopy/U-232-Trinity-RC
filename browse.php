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
require_once(INCL_DIR.'html_functions.php');
require_once INCL_DIR.'torrenttable_functions.php';
require_once INCL_DIR.'pager_functions.php';
require_once(INCL_DIR.'searchcloud_functions.php');
require_once(CLASS_DIR.'class_user_options.php');
require_once(CLASS_DIR.'class_user_options_2.php');

dbconn(false);
loggedinorreturn();
if (isset($_GET['clear_new']) && $_GET['clear_new'] == 1) {
    sql_query("UPDATE users SET last_browse=".TIME_NOW." WHERE id=".sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
    $cache->update_row($keys['my_userid'].$CURUSER['id'], [
        'last_browse' => TIME_NOW,
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row('user'.$CURUSER['id'], [
        'last_browse' => TIME_NOW,
    ], $TRINITY20['expires']['user_cache']);
    header("Location: {$TRINITY20['baseurl']}/browse.php");
}
$stdfoot = [
    /* include js **/
    'js' => [
        //'java_klappe',
        //'wz_tooltip'
    ],
];
$stdhead = [
    /* include css **/
    'css' => [
        /*'browse'*/
    ],
];
$lang = array_merge(load_language('global'), load_language('browse'), load_language('torrenttable_functions'), load_language('index'));

if (function_exists('parked')) {
    parked();
}

$HTMLOUT = $searchin = $select_searchin = $where = $addparam = $new_button = $searchstr = '';
$HTMLOUT = "
<script type='text/javascript' src='./scripts/jaxo.suggest.js'></script>
<script type='text/javascript'>
/*<![CDATA[*/
$(document).ready(function(){
$(\"input[placeholder='Search Torrents']\").search(options);
});
/*]]>*/
</script>";
$cats = genrelist();
if (isset($_GET["search"])) {
    $searchstr = unesc($_GET["search"]);
    $cleansearchstr = searchfield($searchstr);
    if (empty($cleansearchstr)) {
        unset($cleansearchstr);
    }
}
$selected = (isset($_GET["incldead"])) ? (int)$_GET["incldead"] : 0;
$valid_searchin = [
    'title' => [
        'name',
    ],
    'descr' => [
        'descr',
    ],
    'genre' => [
        'newgenre',
    ],
    'tags' => [
        'tags',
    ],
    'all' => [
        'name',
        'newgenre',
        'tags',
        'descr',
    ],
];
if (isset($_GET['searchin']) && isset($valid_searchin[$_GET['searchin']])) {
    $searchin = $valid_searchin[$_GET['searchin']];
    $select_searchin = $_GET['searchin'] ?? "name";
    $addparam .= sprintf('search=%s&amp;searchin=%s&amp;', $searchstr, $select_searchin);
} else {
    $searchin = $valid_searchin[key($valid_searchin)];
    $addparam .= sprintf('search=%s&amp;searchin=%s&amp;', $searchstr, key($valid_searchin));
}
//}
if (isset($_GET['sort']) && isset($_GET['type'])) {
    $column = $ascdesc = '';
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
    switch (htmlsafechars($_GET['type'])) {
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
    $orderby = "ORDER BY {$column} ".$ascdesc;
    $pagerlink = "sort=".(int)$_GET['sort']."&amp;type={$linkascdesc}&amp;";
} else {
    $orderby = "ORDER BY sticky ASC, id DESC";
    $pagerlink = "";
}
$wherea = $wherecatina = [];
if (isset($_GET["incldead"]) && $_GET["incldead"] == 1) {
    $addparam .= "incldead=1&amp;";
    if (!isset($CURUSER) || $CURUSER["class"] < UC_ADMINISTRATOR) {
        $wherea[] = "banned != 'yes'";
    }
} elseif (isset($_GET["incldead"]) && $_GET["incldead"] == 2) {
    $addparam .= "incldead=2&amp;";
    $wherea[] = "visible = 'no'";
} else {
    //$addparam.= "incldead=0&amp;";
    $wherea[] = "visible = 'yes'";
}
//=== added an only free torrents option \\o\o/o//
if (isset($_GET['only_free']) && $_GET['only_free'] == 1) {
    if ((XBT_TRACKER == true ? $wherea[] = "freetorrent >= '1'" : $wherea[] = "free >= '1'") !== '') {
    }
    //$wherea[] = "free >= '1'";
    $addparam .= "only_free=1&amp;";
}
$category = (isset($_GET["cat"])) ? (int)$_GET["cat"] : false;
$all = $_GET["all"] ?? false;
if (!$all) {
    if (!$_GET && $CURUSER["notifs"]) {
        $all = true;
        foreach ($cats as $cat) {
            $all &= $cat['id'];
            if (strpos($CURUSER["notifs"], "[cat".$cat['id']."]") !== false && $cat['min_class'] <= $CURUSER['class']) {
                $wherecatina[] = $cat['id'];
                $addparam .= "c{$cat['id']}=1&amp;";
            }
        }
    } elseif ($category) {
        $cnum = array_search((int)$category, array_column($cats, 'id'));
        if (!is_valid_id($category) || $cats[$cnum]['min_class'] > $CURUSER['class']) {
            stderr("{$lang['browse_error']}", "{$lang['browse_invalid_cat']}");
        }
        $wherecatina[] = $category;
        $addparam .= "cat=$category&amp;";
    } else {
        $all = true;
        foreach ($cats as $cat) {
            $all &= isset($_GET["c{$cat['id']}"]);
            if (isset($_GET["c{$cat['id']}"]) && $cat['min_class'] <= $CURUSER['class']) {
                $wherecatina[] = $cat['id'];
                $addparam .= "c{$cat['id']}=1&amp;";
            }
        }
    }
}
if ($all) {
    foreach ($cats as $cat) {
        if ($cat['min_class'] <= $CURUSER['class']) {
            $wherecatina[] = $cat['id'];
            $addparam .= "c{$cat['id']}=1&amp;";
        }
    }
    $addparam = "";
}
if (count($wherecatina) < 1) {
    foreach ($cats as $cat) {
        if ($cat['min_class'] <= $CURUSER['class']) {
            $wherecatina2[] = $cat['id'];
        }
    }
    $wherea[] = 'category IN ('.implode(', ', $wherecatina2).') ';
    $addparam = "";
}

if (count($wherecatina) > 1) {
    $wherea[] = 'category IN ('.implode(', ', $wherecatina).') ';
} elseif (count($wherecatina) == 1) {
    $wherea[] = 'category ='.$wherecatina[0];
}
//== boolean search by djgrr
if (isset($cleansearchstr) && $searchstr != '') {
    $addparam .= 'search='.rawurlencode($searchstr).'&amp;searchin='.$select_searchin.'&amp;incldead='.(int)$selected.'&amp;';
    $searchstring = str_replace([
        '_',
        '.',
        '-',
    ], ' ', $searchstr);
    $s = [
        '*',
        '?',
        '.',
        '-',
        ' ',
    ];
    $r = [
        '%',
        '_',
        '_',
        '_',
        '_',
    ];
    if (preg_match('/^\"(.+)\"$/i', $searchstring, $matches)) {
        $wherea[] = '`name` LIKE '.sqlesc('%'.str_replace($s, $r, $matches[1]).'%');
    } elseif (strpos($searchstr, '*') !== false || strpos($searchstr, '?') !== false) {
        $wherea[] = '`name` LIKE '.sqlesc(str_replace($s, $r, $searchstr));
    } elseif (preg_match('/^[A-Za-z0-9][a-zA-Z0-9()._-]+\-\w*[A-Za-z0-9]$/iD', $searchstr)) {
        $wherea[] = '`name` = '.sqlesc($searchstr);
    } else {
        $wherea[] = 'MATCH (`search_text`, `filename`, `newgenre`, `tags`) AGAINST ('.sqlesc($searchstr).' IN BOOLEAN MODE)';
    }
    //......
    $searcha = explode(' ', $cleansearchstr);
    //==Memcache search cloud by putyn
    searchcloud_insert($cleansearchstr);
    //==
    foreach ($searcha as $foo) {
        foreach ($searchin as $boo) {
            $searchincrt[] = sprintf('%s LIKE \'%s\'', $boo, '%'.$foo.'%');
        }
    }
    $wherea[] = '( '.implode(' OR ', $searchincrt).' )';
}

$where = (is_countable($wherea) ? count($wherea) : 0) > 0 ? 'WHERE '.implode(' AND ', $wherea) : '';
$where_key = 'where::'.sha1($where);
if (($count = $cache->get($where_key)) === false) {
    ($res = sql_query("SELECT COUNT(id) FROM torrents $where")) || sqlerr(__FILE__, __LINE__);
    $row = $res->fetch_row();
    $count = (int)$row[0];
    $cache->set($where_key, $count, $TRINITY20['expires']['browse_where']);
}

$torrentsperpage = ($CURUSER['torrentsperpage'] == 0) ? 15 : (int)$CURUSER['torrentsperpage'];
if ($count) {
    if ($addparam != "") {
        if ($pagerlink != "") {
            if ($addparam [strlen($addparam) - 1] != ";") { // & = &amp;
                $addparam .= $addparam."&".$pagerlink;
            } else {
                $addparam .= $addparam.$pagerlink;
            }
        }
    } else {
        $addparam .= $pagerlink;
    }

    $pager = pager($torrentsperpage, $count, "browse.php?".$addparam);

    $query = "SELECT id, search_text, category, leechers, seeders, bump, tags, release_group, subs, name, times_completed, size, added, poster, descr, type, free, freetorrent, silver, comments, numfiles, filename, anonymous, sticky, nuked, vip, nukereason, newgenre, description, owner, username, youtube, checked_by, IF(nfo <> '', 1, 0) as nfoav,"."IF(num_ratings < {$TRINITY20['minvotes']}, NULL, ROUND(rating_sum / num_ratings, 1)) AS rating "."FROM torrents {$where} {$orderby} {$pager['limit']}";
    ($res = sql_query($query)) || sqlerr(__FILE__, __LINE__);
} else {
    unset($query);
}

$title = isset($cleansearchstr) ? "{$lang['browse_search']} $searchstr" : '';
///Start top 10 torrents by categories in Slider
if (curuser::$blocks['browse_page'] & block_browse::SLIDER && $BLOCKS['browse_slider_on']) {
    require_once(BLOCK_DIR.'browse/slider_top10.php');
}
$HTMLOUT .= "<form role='form' method='get' action='browse.php'>";
$i = 0;
//Categories
$HTMLOUT .= '<div class="grid-x grid-margin-x">';
$HTMLOUT .= "<div class='cell large-12'>
    <ul class='accordion' data-accordion data-allow-all-closed='true'>
        <li class='accordion-item is-closed' data-accordion-item>
            <a href='#' class='accordion-title'>Categories</a>
            <div class='accordion-content' data-tab-content>
                <div class='grid-x grid-padding-x small-up-4 medium-up-6 large-up-8'>";
foreach ($cats as $cat) {
    if ($cat['min_class'] <= $CURUSER['class']) {
        $HTMLOUT .= ($i !== 0) ? "" : "";
        $HTMLOUT .= "<div class='cell'>
                            <input name='c".(int)$cat['id']."'  type='checkbox' ".(in_array($cat['id'], $wherecatina) ? "checked='checked' " : "")."value='1' >
                            <a href='browse.php?cat=".(int)$cat['id']."'> ".((curuser::$blocks['browse_page'] & block_browse::ICONS && $BLOCKS['browse_icons_on']) ? "<img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($cat['image'])."' alt='".htmlsafechars($cat['name'])."' title='".htmlsafechars($cat['name'])."'>" : "".htmlsafechars($cat['name'])."")."</a>
                            </div>";
        $i++;
    }
}
//=== Search only free :o)
$only_free = ((isset($_GET['only_free'])) ? (int)$_GET['only_free'] : '');
//=== checkbox for only free torrents
$HTMLOUT .= '<div class="cell">
                                <input type="checkbox" name="only_free" value="1"'.(isset($_GET['only_free']) ? ' checked="checked"' : '').'>
                                <img src="'.$TRINITY20['pic_base_url'].'/free.png" height="42" width="42">
                            </div>
                </div>
            </div>
        </li>
    </ul>
</div>
</div>';
//== clear new tag manually
if (curuser::$blocks['browse_page'] & block_browse::CLEAR_NEW_TAG_MANUALLY && $BLOCKS['browse_clear_tags_on']) {
    $new_button = "<a href='?clear_new=1'><input type='submit' value='clear new tag' class='button' /></a><br />";
} else {
    //== clear new tag automatically
    sql_query("UPDATE users SET last_browse=".TIME_NOW." where id=".$CURUSER['id']);
    $cache->update_row($keys['my_userid'].$CURUSER['id'], [
        'last_browse' => TIME_NOW,
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row('user'.$CURUSER['id'], [
        'last_browse' => TIME_NOW,
    ], $TRINITY20['expires']['user_cache']);
}
$deadcheck = "";
$deadcheck .= "<select class='input-group-field' name='incldead'>
    <option value='0'>{$lang['browse_active']}</option>
    <option value='1'".($selected == 1 ? " selected='selected'" : "").">{$lang['browse_inc_dead']}</option>
    <option value='2'".($selected == 2 ? " selected='selected'" : "").">{$lang['browse_dead']}</option>
    </select>";
$searchin = '<select class="input-group-field" name="searchin">';
foreach ([
             'title' => 'Name',
             'descr' => 'Description',
             'genre' => 'Genre',
             'tags' => 'Tags',
             'all' => 'All',
         ] as $k => $v) {
    $searchin .= '<option value="'.$k.'" '.($select_searchin == $k ? 'selected=\'selected\'' : '').'>'.$v.'</option>';
}
$searchin .= '</select>';
$HTMLOUT .= '<div class="input-group">
  <span class="input-group-label"><i class="fa fa-search-plus"></i></span>
  <input class="input-group-field" type="text" name="search" value="'.(isset($searchstr) ? htmlsafechars($searchstr, ENT_QUOTES) : "").'" placeholder="Search Torrents">
   <span>
    '.$searchin.'
  </span>
    <span>
    '.$deadcheck.'
  </span>
  <div class="input-group-button">
    <input class="button" type="submit" value="'.$lang['search_search_btn'].'">
  </div>
</div>';
$HTMLOUT .= "
<a href='{$TRINITY20["baseurl"]}/browse_catalogue.php' class='button'>Alternative Browse</a>
<a href='{$TRINITY20["baseurl"]}/catalogue.php' class='button'>Search our Catalogue</a>
</form><div class='res'></div>";
if (curuser::$blocks['browse_page'] & block_browse::VIEWSCLOUD && $BLOCKS['browse_viewscloud_on']) {
    $HTMLOUT .= "<div class='callout float-center text-center' style='width:80%;border:1px solid black;background-color:rgba(121,124,128,0.3);'>";
    //print out the tag cloud
    $HTMLOUT .= cloud()."
    </div>";
}
$HTMLOUT .= "{$new_button}";
if (isset($cleansearchstr)) {
    $HTMLOUT .= "<div class='row'><div class='col-md-6 col-md-offset-4'><h2>{$lang['browse_search']} ".htmlsafechars($searchstr,
            ENT_QUOTES)."</h2></div></div>\n";
}
if ($count) {
    $HTMLOUT .= "<!--<br /><div class='row'><div class='col-md-3 col-md-offset-5'><div style='display:inline-block;width:4px';></div><a href='{$TRINITY20["baseurl"]}/catalogue.php' class='btn btn-default btn-default'>Search our Catalogue</a></div></div><br /><br />-->";
    $HTMLOUT .= torrenttable($res);
    $HTMLOUT .= $pager['pagerbottom']."<br />";
} elseif (isset($cleansearchstr)) {
    $HTMLOUT .= "<div class='row'><div class='col-md-6 col-md-offset-4'><h2>{$lang['browse_not_found']}</h2>";
    $HTMLOUT .= "{$lang['browse_tryagain']}</div></div>\n";
} else {
    $HTMLOUT .= "<div class='row'><div class='col-md-6 col-md-offset-5'><h2>{$lang['browse_nothing']}</h2>\n";
    $HTMLOUT .= "{$lang['browse_sorry']}</div></div>\n";
}
$HTMLOUT .= "";
/*
$ip = getip();
//== Start ip logger - Melvinmeow, Mindless, pdq
$no_log_ip = ($CURUSER['perms'] & bt_options::PERMS_NO_IP);
if ($no_log_ip) {
    $ip = '127.0.0.1';
}
if (!$no_log_ip) {
    $userid = (int)$CURUSER['id'];
    $added = TIME_NOW;
    $res = sql_query("SELECT * FROM ips WHERE ip = " . sqlesc($ip) . " AND userid = " . sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
    if ($res->num_rows == 0) {
        sql_query("INSERT INTO ips (userid, ip, lastbrowse, type) VALUES (" . sqlesc($userid) . ", " . sqlesc($ip) . ", $added, 'Browse')") or sqlerr(__FILE__, __LINE__);
        $cache->delete('ip_history_' . $userid);
    } else {
        sql_query("UPDATE ips SET lastbrowse = $added WHERE ip=" . sqlesc($ip) . " AND userid = " . sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
        $cache->delete('ip_history_' . $userid);
    }
}
//== End Ip logger
*/
echo stdhead($title, true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
?>
