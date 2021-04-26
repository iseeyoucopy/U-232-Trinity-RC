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
require_once INCL_DIR.'torrenttable_functions_catalogue.php';
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
    $cache->update_row($keys['user'].$CURUSER['id'], [
        'last_browse' => TIME_NOW,
    ], $TRINITY20['expires']['user_cache']);
    header("Location: {$TRINITY20['baseurl']}/browse_catalogue.php");
}
$stdfoot = [
    /** include js **/
    'js' => [
        'java_klappe',
        'wz_tooltip',
    ],
];
$stdhead = [
    /** include css **/
    'css' => [
        /*'browse'*/
    ],
];
$lang = array_merge(load_language('global'), load_language('browse'), load_language('catalogue'), load_language('torrenttable_functions'));
if (function_exists('parked')) {
    parked();
}

$HTMLOUT = $searchin = $select_searchin = $where = $addparam = $new_button = $search_help_boolean = '';
$HTMLOUT .= '<script type="text/javascript">
/*<![CDATA[*/
$(document).ready(function(){
    $("#help_open").click(function(){
    $("#help").slideToggle("slow", function() {
    });
    });
})
/*]]>*/
</script>';
$search_help_boolean = '<div class="card">
    <div class="card-divider">
<h2 class="text-center text-info">'.$lang['bool_01'].'</h2>
</div><div class="panel">
    <div class="card-section">
 <p>   <span style="font-weight: bold;">+</span>'.$lang['bool_02'].'<br><br>
    <span style="font-weight: bold;">-</span>'.$lang['bool_03'].'<br><br>
       '.$lang['bool_04'].'<br><br>
    <span style="font-weight: bold;">*</span>'.$lang['bool_05'].'<br><br>
    <span style="font-weight: bold;">> <</span>'.$lang['bool_06'].'<br><br>
    <span style="font-weight: bold;">~</span>'.$lang['bool_07'].'<br><br>
    <span style="font-weight: bold;">" "</span>'.$lang['bool_08'].'<br><br>
    <span style="font-weight: bold;">( )</span>'.$lang['bool_09'].'
    </p> </div></div></div></div></div></div>';
$cats = genrelist();
if (isset($_GET["search"])) {
    $searchstr = sqlesc($_GET["search"]);
    $cleansearchstr = searchfield($searchstr);
    if (empty($cleansearchstr)) {
        unset($cleansearchstr);
    }
}
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
    'all' => [
        'name',
        'newgenre',
        'descr',
    ],
];
if (isset($_GET['searchin'], $valid_searchin[$_GET['searchin']])) {
    $searchin = $valid_searchin[$_GET['searchin']];
    $select_searchin = $_GET['searchin'];
    $addparam .= sprintf('search=%s&amp;searchin=%s&amp;', $searchstr, $select_searchin);
}
if (isset($_GET['sort'], $_GET['type'])) {
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
        if (!is_valid_id($category) || $cats[$category]['min_class'] >= $CURUSER['class']) {
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
    //$addparam = "";
}
if (count($wherecatina) < 1) {
    foreach ($cats as $cat) {
        if ($cat['min_class'] <= $CURUSER['class']) {
            $wherecatina2[] = $cat['id'];
        }
    }
    $wherea[] = 'category IN ('.implode(', ', $wherecatina2).') ';
    //$addparam = "";
}

if (count($wherecatina) > 1) {
    $wherea[] = 'category IN ('.implode(', ', $wherecatina).') ';
} elseif (count($wherecatina) == 1) {
    $wherea[] = 'category ='.$wherecatina[0];
}
//== boolean search by djgrr
if (isset($cleansearchstr) && $searchstr != '') {
    $addparam .= 'search='.rawurlencode($searchstr).'&amp;';
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
        $wherea[] = 'MATCH (`search_text`, `filename`, `newgenre`) AGAINST ('.sqlesc($searchstr).' IN BOOLEAN MODE)';
    }
    //......
    $orderby = 'ORDER BY id DESC';
    $searcha = explode(' ', $cleansearchstr);
    //==Memcache search cloud by putyn
    searchcloud_insert($cleansearchstr);
    //==
    foreach ($searcha as $foo) {
        foreach ($searchin as $boo) {
            $searchincrt[] = sprintf('%s LIKE \'%s\'', $boo, '%'.$foo.'%');
        }
    }
    $wherea[] = implode(' OR ', $searchincrt);
}
$where = (is_countable($wherea) ? count($wherea) : 0) > 0 ? 'WHERE '.implode(' AND ', $wherea) : '';
$where_key = 'where::'.sha1($where);
if (($count = $cache->get($where_key)) === false) {
    ($res = sql_query("SELECT COUNT(id) FROM torrents $where")) || sqlerr(__FILE__, __LINE__);
    $row = $res->fetch_row();
    $count = (int)$row[0];
    $cache->set($where_key, $count, $TRINITY20['expires']['browse_where']);
}
$torrentsperpage = $CURUSER["torrentsperpage"];
if (!$torrentsperpage) {
    $torrentsperpage = 16;
}
if ($count) {
    if ($addparam != "") {
        if ($pagerlink != "") {
            if ($addparam[strlen($addparam) - 1] != ";") { // & = &amp;
                $addparam = $addparam."&".$pagerlink;
            } else {
                $addparam .= $pagerlink;
            }
        }
    } else {
        $addparam = $pagerlink;
    }
    $pager = pager($torrentsperpage, $count, "browse_catalogue.php?".$addparam);
    $query = "SELECT id, search_text, category, leechers, seeders, bump, release_group, subs, name, times_completed, size, added, poster, descr, type, free, freetorrent, silver, comments, numfiles, filename, anonymous, sticky, nuked, vip, nukereason, newgenre, description, owner, username, youtube, checked_by, IF(nfo <> '', 1, 0) as nfoav,"."IF(num_ratings < {$TRINITY20['minvotes']}, NULL, ROUND(rating_sum / num_ratings, 1)) AS rating, url "."FROM torrents {$where} {$orderby} {$pager['limit']}";
    ($res = sql_query($query)) || sqlerr(__FILE__, __LINE__);
} else {
    unset($query);
}

$title = isset($cleansearchstr) ? "{$lang['browse_search']} $searchstr" : '';
$HTMLOUT .= "<div class='row'><div class='col-md-10 col-md-offset-2'>";
if (($CURUSER['opt1'] & user_options::VIEWSCLOUD) !== 0) {
    $HTMLOUT .= "<div id='wrapper' style='width:80%;border:1px solid black;background-color:rgba(121,124,128,0.3);'>";
    //print out the tag cloud
    $HTMLOUT .= cloud()."
    </div>";
}
$HTMLOUT .= "<br><br>
    <form  role='form' class='form-horizontal' method='get' action='browse_catalogue.php'>
    <table>
    <tr>
    <td >
    <table>
    <tr>";
$i = 0;
foreach ($cats as $cat) {
    if ($cat['min_class'] <= $CURUSER['class']) {
        $HTMLOUT .= ($i && $i % $TRINITY20['catsperrow'] == 0) ? "</tr><tr>" : "";
        $HTMLOUT .= "<td style=\"padding-bottom: 2px;padding-left: 7px\">
             <input name='c".(int)$cat['id']."' class=\"styled\" type=\"checkbox\" ".(in_array($cat['id'],
                $wherecatina) ? "checked='checked' " : "")."value='1' /><a class='catlink' href='browse_catalogue.php?cat=".(int)$cat['id']."'> ".((($CURUSER['opt2'] & user_options_2::BROWSE_ICONS) !== 0) ? "<img src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/".htmlsafechars($cat['image'])."' alt='".htmlsafechars($cat['name'])."' title='".htmlsafechars($cat['name'])."' />" : "".htmlsafechars($cat['name'])."")."</a></td>\n";
        $i++;
    }
}
$alllink = "<div></div>";
$ncats = is_countable($cats) ? count($cats) : 0;
$nrows = ceil($ncats / $TRINITY20['catsperrow']);
$lastrowcols = $ncats % $TRINITY20['catsperrow'];
if ($lastrowcols != 0) {
    if ($TRINITY20['catsperrow'] - $lastrowcols != 1) {
        $HTMLOUT .= "<td class='bottom' rowspan='".($TRINITY20['catsperrow'] - $lastrowcols - 1)."'>&nbsp;</td>";
    }
    $HTMLOUT .= "<td>$alllink</td>\n";
}
$HTMLOUT .= "</tr>
    </table>
    </td>
    <td>
    <table>
    <tr>";
if ($ncats % $TRINITY20['catsperrow'] == 0) {
    $HTMLOUT .= "<td>$alllink</td>\n";
}
$HTMLOUT .= "</tr>
    </table>
    </td>
    </tr>
    </table><br>";
//== clear new tag manually
if (($CURUSER['opt1'] & user_options::CLEAR_NEW_TAG_MANUALLY) !== 0) {
    $new_button = "<a href='?clear_new=1'><input type='submit' value='clear new tag' class='button' /></a><br>";
} else {
    //== clear new tag automatically
    sql_query("UPDATE users SET last_browse=".TIME_NOW." where id=".$CURUSER['id']);
    $cache->update_row($keys['my_userid'].$CURUSER['id'], [
        'last_browse' => TIME_NOW,
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row($keys['user'].$CURUSER['id'], [
        'last_browse' => TIME_NOW,
    ], $TRINITY20['expires']['user_cache']);
}
$HTMLOUT .= "</div></div><br>
<div class='container'>
 <div class='col-md-12'>   
<div>
<div class='col-md-4 col-md-offset-4'>
<br><a href='#myModal' class='btn btn-default btn-small' data-toggle='modal'>{$lang['search_inf_01']}</a><br><br>
<div id='myModal' class='modal fade'>
        <div class='modal-dialog'>
            <div class='modal-content'>
                <div class='modal-header'>
                    <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>".$search_help_boolean."
                </div>
            </div>
        </div>
    </div></div>
<div class='row'>
<div class='form-group'>

<!--<div class='col-md-4 input-group input-group-md text-center'><span class='input-group-addon'><i class='fa fa-search-plus'></i></span><input  class='form-control' placeholder='{$lang['search_fct_01']}' type='text' name='search' value='' /></div>-->
<div class='col-md-4'><i class='fa fa-search-plus'></i><input  class='form-control' placeholder='{$lang['search_fct_01']}' type='text' name='search' value='' /></div>";

//=== only free option :o)
$only_free = ((isset($_GET['only_free'])) ? (int)$_GET['only_free'] : '');
//=== checkbox for only free torrents
$only_free_box = '<input type="checkbox" name="only_free" value="1"'.(isset($_GET['only_free']) ? ' checked="checked"' : '').' />'.$lang['search_inf_02'].'';

$selected = (isset($_GET["incldead"])) ? (int)$_GET["incldead"] : "";
$deadcheck = "";
$deadcheck .= " <!--<div class='col-md-1 text-right'>in:</div><div class='col-md-2'><select class='form-control' name='incldead'>-->
in: 
<select class='form-control' name='incldead'>  
 <option value='0'>{$lang['browse_active']}</option>
    <option value='1'".($selected == 1 ? " selected='selected'" : "").">{$lang['browse_inc_dead']}</option>
    <option value='2'".($selected == 2 ? " selected='selected'" : "").">{$lang['browse_dead']}</option>
    </select><!--</div>-->";
$searchin = 'by:<select class="form-control"  name="searchin"><!--<div class="col-md-1 text-right">by:</div><div class="col-md-2"><select class="form-control"  name="searchin">-->';
foreach ([
             'title' => 'Name',
             'descr' => 'Description',
             'genre' => 'Genre',
             'all' => 'All',
         ] as $k => $v) {
    $searchin .= '<option value="'.$k.'" '.($select_searchin == $k ? 'selected=\'selected\'' : '').'>'.$v.'</option>';
}
$searchin .= '</select><!--</div>-->';

//$HTMLOUT.= $searchin . $deadcheck . $only_free_box ;
$HTMLOUT .= "<div class='col-md-3'>$searchin</div>"."<div class='col-md-3'>$deadcheck</div>"."<br>".$only_free_box."</div>";

$HTMLOUT .= "</div><br><div class='col-md-2 col-md-offset-5'><input class='form-control' type='submit' value='{$lang['search_search_btn']}' class='btn btn-primary'></div>
           </form><br >";
$HTMLOUT .= "{$new_button}";
if (isset($cleansearchstr)) {
    $HTMLOUT .= "<div class='row'><div class='col-md-6 col-md-offset-4'><h2>{$lang['browse_search']} ".htmlsafechars($searchstr,
            ENT_QUOTES)."</h2></div></div>\n";
}
if ($count) {
    $HTMLOUT .= "<br><div class='row'><div class='col-md-3 col-md-offset-5'><div style='display:inline-block;width:10px';></div><a href='{$TRINITY20["baseurl"]}/browse.php' class='btn btn-default btn-default'>{$lang['old_school_b']}</a></div></div><br><br>".$pager['pagertop'];
    $HTMLOUT .= "<br >";
    $HTMLOUT .= torrenttable($res);
    $HTMLOUT .= "<br >";
    $HTMLOUT .= $pager['pagerbottom'];
    $HTMLOUT .= "<br >";
} elseif (isset($cleansearchstr)) {
    $HTMLOUT .= "<div class='row'><div class='col-md-6 col-md-offset-4'><h2>{$lang['browse_not_found']}</h2>";
    $HTMLOUT .= "{$lang['browse_tryagain']}</div></div>\n";
} else {
    $HTMLOUT .= "<div class='row'><div class='col-md-6 col-md-offset-5'><h2>{$lang['browse_nothing']}</h2>\n";
    $HTMLOUT .= "{$lang['browse_sorry']}</div></div>\n";
}
$HTMLOUT .= "<!--</div></div>-->";
$ip = getip();
//== Start ip logger - Melvinmeow, Mindless, pdq
$no_log_ip = ($CURUSER['perms'] & bt_options::PERMS_NO_IP);
if ($no_log_ip !== 0) {
    $ip = '127.0.0.1';
}
if ($no_log_ip === 0) {
    $userid = (int)$CURUSER['id'];
    $added = TIME_NOW;
    ($res = sql_query("SELECT * FROM ips WHERE ip = ".sqlesc($ip)." AND userid = ".sqlesc($userid))) || sqlerr(__FILE__, __LINE__);
    if ($res->num_rows == 0) {
        sql_query("INSERT INTO ips (userid, ip, lastbrowse, type) VALUES (".sqlesc($userid).", ".sqlesc($ip).", $added, 'Browse')") || sqlerr(__FILE__,
            __LINE__);
        $cache->delete($keys['ip_history'].$userid);
    } else {
        sql_query("UPDATE ips SET lastbrowse = $added WHERE ip=".sqlesc($ip)." AND userid = ".sqlesc($userid)) || sqlerr(__FILE__, __LINE__);
        $cache->delete($keys['ip_history'].$userid);
    }
}
//== End Ip logger
echo stdhead($title, true, $stdhead).$HTMLOUT.stdfoot($stdfoot); ?>
