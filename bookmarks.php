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
//==bookmarks.php - by pdq
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'torrenttable_functions.php');
require_once (INCL_DIR . 'pager_functions.php');
require_once (INCL_DIR . 'password_functions.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('torrenttable_functions'), load_language('bookmark'));
$HTMLOUT = '';
$possible_actions = array(
    'add',
    'delete',
    'public',
    'private'
);
$view_actions = array(
    'viewbookmarks',
    'viewsharemarks'
);
$action = (isset($_GET['action']) ? htmlsafechars($_GET['action']) : 'viewbookmarks');
if (!in_array($action, $possible_actions) && !in_array($action, $view_actions)) stderr($lang['bookmark_err'], $lang['bookmark_aruffian']);

if (in_array($action, $possible_actions)){
$torrentid = (int)$_GET['torrent'];
if (!is_valid_id($torrentid)) stderr($lang['bookmark_err'], $lang['bookmark_invalidid']);
$sure = isset($_GET['sure']) ? 0 + $_GET['sure'] : '';
$hash = h_store($torrentid);
}

if ($action == 'add') {
    if ((get_row_count("torrents", "WHERE id = " . sqlesc($torrentid))) == 0) stderr($lang['bookmarks_err'], $lang['bookmark_invalidid']);
    if (!$sure) stderr($lang['bookmark_add'],$lang['bookmark_add_click'] . "<a href='?torrent=$torrentid&amp;action=add&amp;sure=1&amp;h=$hash'>{$lang['bookmark_here']}</a>{$lang['bookmark_sure']}", FALSE);
    if ($_GET['h'] != $hash) stderr($lang['bookmark_err'], $lang['bookmark_waydoing']);
    function addbookmark($torrentid)
    {
        global $CURUSER, $cache, $lang, $keys;
        if ((get_row_count("bookmarks", "WHERE userid=" . sqlesc($CURUSER['id']) . " AND torrentid = " . sqlesc($torrentid))) > 0) stderr($lang['bookmark_err'], $lang['bookmark_already']);
        sql_query("INSERT INTO bookmarks (userid, torrentid) VALUES (" . sqlesc($CURUSER['id']) . ", " . sqlesc($torrentid) . ")") or sqlerr(__FILE__, __LINE__);
        $cache->delete($keys['bookmark_key'] . $CURUSER['id']);
        make_bookmarks($CURUSER['id'], $keys['bookmark_key']);
    }
    addbookmark($torrentid);
    header("Refresh: 2; url={$TRINITY20['baseurl']}/bookmarks.php");
    stderr("Added", "Bookmark added to your library. Redirecting...");
} elseif ($action == 'delete') {
    if ((get_row_count("bookmarks", "WHERE userid=" . sqlesc($CURUSER['id']) . " AND torrentid = " . sqlesc($torrentid))) == 0) stderr($lang['bookmark_err'], $lang['bookmark_waydoing']);
    if (!$sure) stderr($lang['bookmark_delete'], $lang['bookmark_del_click'] . "<a href='?torrent=$torrentid&amp;action=delete&amp;sure=1&amp;h=$hash'>{$lang['bookmark_here']}</a>{$lang['bookmark_sure']}", FALSE);
    if ($_GET['h'] != $hash) stderr($lang['bookmark_err'], $lang['bookmark_waydoing']);
    function deletebookmark($torrentid)
    {
        global $CURUSER, $cache, $keys;
        sql_query("DELETE FROM bookmarks WHERE torrentid = " . sqlesc($torrentid) . " AND userid = " . sqlesc($CURUSER['id']));
        $cache->delete($keys['bookmark_key'] . $CURUSER['id']);
        make_bookmarks($CURUSER['id'], $keys['bookmark_key']);
    }
    deletebookmark($torrentid);
    header("Refresh: 2; url={$TRINITY20['baseurl']}/bookmarks.php");
    stderr("Deleted", "Bookmark deleted from your library. Redirecting...");
} elseif ($action == 'public') {
    if ((get_row_count("bookmarks", "WHERE userid=" . sqlesc($CURUSER['id']) . " AND torrentid = " . sqlesc($torrentid))) == 0) stderr($lang['bookmark_err'], $lang['bookmark_waydoing']);
    if (!$sure) stderr($lang['bookmark_share'], $lang['bookmark_share_click'] . "<a href='?torrent=$torrentid&amp;action=public&amp;sure=1&amp;h=$hash'>{$lang['bookmark_here']}</a>{$lang['bookmark_sure']}", FALSE);
    if ($_GET['h'] != $hash) stderr($lang['bookmark_err'], $lang['bookmark_waydoing']);
    function publickbookmark($torrentid)
    {
        global $CURUSER, $cache, $keys;
        sql_query("UPDATE bookmarks SET private = 'no' WHERE private = 'yes' AND torrentid = " . sqlesc($torrentid) . " AND userid = " . sqlesc($CURUSER['id']));
        $cache->delete($keys['bookmark_key'] . $CURUSER['id']);
        make_bookmarks($CURUSER['id'], $keys['bookmark_key']);
    }
    publickbookmark($torrentid);
    header("Location: {$TRINITY20['baseurl']}/bookmarks.php?action=viewsharemarks&id=".(int)$CURUSER['id']);
    exit();
} elseif ($action == 'private') {
    if ((get_row_count("bookmarks", "WHERE userid=" . sqlesc($CURUSER['id']) . " AND torrentid = " . sqlesc($torrentid))) == 0) stderr($lang['bookmark_err'], $lang['bookmark_waydoing']);
    if (!$sure) stderr($lang['bookmark_make_private'], $lang['bookmark_click_private'] . "<a href='?torrent=$torrentid&amp;action=private&amp;sure=1&amp;h=$hash'>{$lang['bookmark_here']}</a>{$lang['bookmark_sure']}", FALSE);
    if ($_GET['h'] != $hash) stderr($lang['bookmark_err'], $lang['bookmark_waydoing']);
    if (!is_valid_id($torrentid)) stderr($lang['bookmark_err'], $lang['bookmark_invalidid']);
    function privatebookmark($torrentid)
    {
        global $CURUSER, $cache, $keys;
        sql_query("UPDATE bookmarks SET private = 'yes' WHERE private = 'no' AND torrentid = " . sqlesc($torrentid) . " AND userid = " . sqlesc($CURUSER['id']));
        $cache->delete($keys['bookmark_key'] . $CURUSER['id']);
        make_bookmarks($CURUSER['id'], $keys['bookmark_key']);
    }
    privatebookmark($torrentid);
    header("Location: {$TRINITY20['baseurl']}/bookmarks.php");
    exit();
} elseif ($action == 'viewbookmarks') {
//==Bookmarks
$userid = isset($_GET['id']) ? (int)$_GET['id'] : $CURUSER['id'];
if (!is_valid_id($userid)) stderr($lang['bookmarks_err'], $lang['bookmark_invalidid']);
if ($userid != $CURUSER["id"]) stderr($lang['bookmarks_err'], "{$lang['bookmarks_denied']}<a href=\"bookmarks.php?action=viewsharemarks&amp;id=" . $userid . "\">{$lang['bookmarks_here']}</a>");
$pagetitle = $lang['bookmarks_stdhead'];
$res = sql_query("SELECT COUNT(id) FROM bookmarks WHERE userid = " . sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
$row = $res->fetch_array();
$count = $row[0];
if ($count == 0){
    $HTMLOUT.= "<h1>{$lang['bookmarks_my']}</h1>";
    $HTMLOUT.= "<b>Sorry, you have no bookmarks. Click <a href='{$TRINITY20["baseurl"]}/browse.php'>here</a> to go on torrents page.</b><br/><br/>";
    $HTMLOUT.= "<b><a href='bookmarks.php?action=viewsharemarks&amp;id=" . (int)$CURUSER['id'] . "'>{$lang['bookmarks_my_share']}</a></b><br/><br/>";
} else {
//==Bookmark Table
function bookmarktable($res)
{
    global $TRINITY20, $CURUSER, $lang;
    $HTMLOUT = $wait = '';
    $HTMLOUT.= "<div class='table-scroll'>
    <span>{$lang['bookmarks_icon']}
    <i class='fas fa-trash'></i>{$lang['bookmarks_del1']}
    <i class='fas fa-save'></i>{$lang['bookmarks_down1']}
    <i class='fas fa-lock'></i>{$lang['bookmarks_private1']}
    <i class='fas fa-lock-open'></i>{$lang['bookmarks_public1']}</span>
    <table class='striped'>
    <thead>
    <tr>
    <td class='text-center'>{$lang['torrenttable_type']}</td>
    <td class='text-left'>{$lang['torrenttable_name']}</td>
    <td class='text-center'><i class='fas fa-trash' title='{$lang['bookmarks_del2']}'></i></td>
    <td class='text-center'><i class='fas fa-save' title='{$lang['bookmarks_down2']}'></i></td>
    ".($TRINITY20['wait_times'] == 1 && $CURUSER['class'] < UC_VIP ? "<td class='text-center'><i class='fas fa-clock' title='Wait Time'></td>" : "")."
    <td class='text-center'><i class='fas fa-share-alt' title='{$lang['bookmarks_share']}'></i></td>
    <td class='text-center'><i class='far fa-folder-open' title='{$lang['torrenttable_files']}'></i></td>
    <td class='text-center'><i class='fas fa-comments' title='{$lang['torrenttable_comments']}'></i></td>
    <td class='text-center'><i class='fas fa-stopwatch' title='{$lang['torrenttable_added']}'></i></td>
    <td class='text-center'><i class='fas fa-chart-pie' title='{$lang['torrenttable_size']}'></i></td>
    <td class='text-center'><i class='fas fa-history' title='{$lang['torrenttable_snatched']}'></i></td>
    <td class='text-center'><i class='fas fa-arrow-up' title='{$lang['torrenttable_seeders']}' style='color:green'></i></td>
    <td class='text-center'><i class='fas fa-arrow-down' title='{$lang['torrenttable_leechers']}' style='color:red'></i></td>
    <td class='text-center'>{$lang['torrenttable_uppedby']}</td>
    </tr></thead><tbody>";
    //CATEGORY ICONS
    $categories = genrelist();
    foreach ($categories as $key => $value) $change[$value['id']] = array(
        'id' => $value['id'],
        'name' => $value['name'],
        'image' => $value['image']
    );
    while ($row = $res->fetch_assoc()) {
        $row['cat_name'] = htmlsafechars($change[$row['category']]['name']);
        $row['cat_pic'] = htmlsafechars($change[$row['category']]['image']);
        $id = (int)$row["id"];
        $HTMLOUT.= "<tr>";
        $HTMLOUT.= "<td class='text-center' style='padding: 0px'>";
        if (isset($row["cat_name"])) {
            $HTMLOUT.= '<a href="browse.php?cat=' . (int)$row['category'] . '">';
            if (isset($row["cat_pic"]) && $row["cat_pic"] != "") $HTMLOUT.= "<img border='0' src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/" . htmlsafechars($row['cat_pic']) . "' alt='" . htmlsafechars($row['cat_name']) . "' />";
            else {
                $HTMLOUT.= htmlsafechars($row["cat_name"]);
            }
            $HTMLOUT.= "</a>";
        } else {
            $HTMLOUT.= "-";
        }
        $HTMLOUT.= "</td>";
        //TORRENT NAME
        $dispname = htmlsafechars($row["name"]);
        $HTMLOUT.= "<td class='text-left'><a href='details.php?id=$id&amp;hit=1'><b>" . CutName($dispname, 35) . "</b></a>&nbsp;</td>";
        //DELETE BOOKMARK
        $HTMLOUT.= "<td class='text-center'><a href='bookmarks.php?torrent={$id}&amp;action=delete'><i class='fas fa-trash' title='{$lang['bookmarks_del3']}'></i></a></td>";
        //DOWNLOAD
        $HTMLOUT.= "<td class='text-center'><a href='download.php?torrent={$id}'<i class='fas fa-save' title='{$lang['bookmarks_down3']}'></i></a></td>";
        //WAIT TIME
        if ($CURUSER["class"] < UC_VIP && $TRINITY20['wait_times'] == 1){
            $gigs = $CURUSER["uploaded"] / (1024*1024*1024);
            $ratio = (($CURUSER["downloaded"] > 0) ? ($CURUSER["uploaded"] / $CURUSER["downloaded"]) : 0);
            if ($ratio < 0.5 || $gigs < 5) $wait = 48;
            elseif ($ratio < 0.65 || $gigs < 6.5) $wait = 24;
            elseif ($ratio < 0.8 || $gigs < 8) $wait = 12;
            elseif ($ratio < 0.95 || $gigs < 9.5) $wait = 6;
            else $wait = 0;
            $elapsed = floor((TIME_NOW - $row["added"]) / 3600);
            if ($elapsed < $wait) {
            $ttl = $elapsed == 1 ? "<br />".$lang["torrenttable_hour_singular"] : "<br />".$lang["torrenttable_hour_plural"];
            $color = dechex(floor(127*($wait - $elapsed)/48 + 128)*65536);
            $HTMLOUT .= "<td class='text-center'><font color='$color'>" . number_format($wait - $elapsed) . "<br />".$ttl."</font></td>";
            } else
            $HTMLOUT .= "<td class='text-center'>{$lang["torrenttable_wait_none"]}</td>";
        }
        //SHAREMARKS
        $bm = sql_query("SELECT * FROM bookmarks WHERE torrentid=" . sqlesc($id) . " AND userid=" . sqlesc($CURUSER['id']));
        $bms = $bm->fetch_assoc();
        if ($bms['private'] == 'yes' && $bms['userid'] == $CURUSER['id']) {
            $makepriv = "<a href='bookmarks.php?torrent={$id}&amp;action=public'><i class='fas fa-lock' title='{$lang['bookmarks_public2']}'></i></a>";
            $HTMLOUT.= "<td class='text-center'>{$makepriv}</td>";
        } elseif ($bms['private'] == 'no' && $bms['userid'] == $CURUSER['id']) {
            $makepriv = "<a href='bookmarks.php?torrent=" . $id . "&amp;action=private'><i class='fas fa-lock-open' title='{$lang['bookmarks_private2']}'></i></a>";
            $HTMLOUT.= "<td class='text-center'>{$makepriv}</td>";
        }
        //NUMFILES
        $HTMLOUT .= ($row["type"] == "single") ? "<td class='text-center'>" . (int)$row['numfiles'] . "</td>" : "<td align='right'><b><a href='filelist.php?id=$id'>" . (int)$row['numfiles'] . "</a></b></td>";
        //COMMENTS
        $HTMLOUT.= (!$row["comments"]) ? "<td class='text-center'>" . (int)$row['comments'] . "</td>" : "<td align='right'><b><a href='details.php?id=$id&amp;hit=1&amp;tocomm=1'>" . (int)$row['comments'] . "</a></b></td>";
        //ADDED
        $HTMLOUT.= "<td class='text-center'><span style='white-space: nowrap;'>" . str_replace(",", "<br />", get_date($row['added'], '')) . "</span></td>";
        //SIZE
        $HTMLOUT.= "<td class='text-center'>" . str_replace(" ", "&nbsp;", mksize($row["size"])) . "</td>";
        //SNATCHES
        $_s = ($row["times_completed"] != 1 ? $lang["torrenttable_time_plural"] : $lang["torrenttable_time_singular"]);
        $What_Script_S = (XBT_TRACKER == true ? 'snatches_xbt.php?id=' : 'snatches.php?id=' );
        $HTMLOUT.= "<td class='text-center'><a href='$What_Script_S"."$id'>" . number_format($row["times_completed"]) . "</a>&nbsp;$_s</td>";
        //SEEDERS
        $What_Script_P = (XBT_TRACKER == true ? 'peerlist_xbt.php?id=' : 'peerlist.php?id=' );
        if ($row["seeders"]) {
                if ($row["leechers"]) $ratio = $row["seeders"] / $row["leechers"];
                else $ratio = 1;
                $HTMLOUT.= "<td class='text-center'><b><a href='$What_Script_P"."$id#seeders'><font color='" . get_slr_color($ratio) . "'>" . (int)$row["seeders"] . "</font></a></b></td>";
        } else $HTMLOUT.= "<td class='text-center'><font color='" . linkcolor($row["seeders"]) . "'>" . (int)$row["seeders"] . "</font></td>";
        //LEECHERS
        if ($row["leechers"]) {
            $HTMLOUT.= "<td class='text-center'><b><a href='$What_Script_P"."$id#leechers'>" . number_format($row["leechers"]) . "</a></b></td>";
        } else $HTMLOUT.= "<td class='text-center'>0</td>";
        //UPLOADER
        $HTMLOUT.= "<td class='text-center'>" . (isset($row["username"]) ? (($row['opt1'] & user_options::ANONYMOUS && $CURUSER['class'] < UC_STAFF && $row['owner'] != $CURUSER['id']) ? "<i>" . $lang['torrenttable_anon'] . "</i>" : "<a href='userdetails.php?id=" . (int)$row["owner"] . "'><b>" . htmlsafechars($row["username"]) . "</b></a>") : "<i>(" . $lang["torrenttable_unknown_uploader"] . ")</i>") . "</td></tr>";
    }
    $HTMLOUT.= "</tbody></table></div>";
    return $HTMLOUT;
}

$torrentsperpage = $CURUSER["torrentsperpage"];
if (!$torrentsperpage) $torrentsperpage = 25;
    $HTMLOUT.= "<h1>{$lang['bookmarks_my']}</h1>";
    $HTMLOUT.= "<b><a href='bookmarks.php?action=viewsharemarks&amp;id=" . (int)$CURUSER['id'] . "'>{$lang['bookmarks_my_share']}</a></b>";
    $pager = pager($torrentsperpage, $count, "bookmarks.php?action=viewbookmarks&amp;");
    $query1 = "SELECT bookmarks.id as bookmarkid, torrents.username, torrents.owner, torrents.id, torrents.name, torrents.type, torrents.anonymous, torrents.comments, torrents.leechers, torrents.seeders, torrents.save_as, torrents.numfiles, torrents.added, torrents.filename, torrents.size, torrents.views, torrents.visible, torrents.hits, torrents.times_completed, torrents.category FROM bookmarks LEFT JOIN torrents ON bookmarks.torrentid = torrents.id WHERE bookmarks.userid =" . sqlesc($userid) . " ORDER BY torrents.id DESC {$pager['limit']}" or sqlerr(__FILE__, __LINE__);
    $res = sql_query($query1) or sqlerr(__FILE__, __LINE__);
    $HTMLOUT.= $pager['pagertop'];
    $HTMLOUT.= bookmarktable($res, TRUE);
    $HTMLOUT.= $pager['pagerbottom'];
    }
} elseif ($action == 'viewsharemarks') {
//==Sharemarks
$userid = isset($_GET['id']) ? (int)$_GET['id'] : $CURUSER['id'];
if (!is_valid_id($userid)) stderr($lang['bookmarks_err'], $lang['bookmark_invalidid']);
$res = sql_query("SELECT id, username FROM users WHERE id = " . sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
$arr = $res->fetch_array();
if ($arr == 0) {
     stderr($lang['bookmarks_err'], $lang['bookmark_invalidid']);
} else {
$res = sql_query("SELECT COUNT(id) FROM bookmarks WHERE private='no' AND userid = " . sqlesc($userid)) or sqlerr(__FILE__, __LINE__);
$row = $res->fetch_array();
$count = $row[0];
if ($count == 0){
    header("Refresh: 3; url={$TRINITY20['baseurl']}/bookmarks.php");
    stderr($lang['bookmarks_err'], "User ".htmlsafechars($arr['username'])." has no sharemarks");
} else {
$pagetitle = $lang['bookmarks_sharefor'] . htmlsafechars($arr['username']);
//==Sharemarks Table
function sharetable($res, $variant = "index")
{
    global $TRINITY20, $CURUSER, $lang;
    $HTMLOUT = '';
    $HTMLOUT.= "
    <span>{$lang['bookmarks_icon']}
    <i class='fas fa-trash'></i>{$lang['bookmarks_del1']}
    <i class='fas fa-save'></i>{$lang['bookmarks_down1']}
    <i class='fas fa-lock'></i>{$lang['bookmarks_private1']}
    <i class='fas fa-lock-open'></i>{$lang['bookmarks_public1']}</span>
<table border='1' cellspacing='0' cellpadding='5'>
<tr>
<td class='text-center'>{$lang["torrenttable_type"]}</td>
<td class='colhead' align='left'>{$lang["torrenttable_name"]}</td>";
    $userid = (int)$_GET['id'];
    if ($CURUSER['id'] == $userid) $HTMLOUT.= ($variant == 'index' ? '<td class="colhead" align="center">Download</td><td class="colhead" align="right">' : '') . 'Delete</td>';
    else $HTMLOUT.= ($variant == 'index' ? '<td class="colhead" align="center">Download</td><td class="colhead" align="right">' : '') . 'Bookmark</td>';
    if ($variant == "mytorrents") {
        $HTMLOUT.= "<td class='text-center'>{$lang["torrenttable_edit"]}</td>\n";
        $HTMLOUT.= "<td class='text-center'>{$lang["torrenttable_visible"]}</td>\n";
    }
    $HTMLOUT.= "<td class='text-center'>{$lang["torrenttable_files"]}</td>
   <td class='text-center'>{$lang["torrenttable_comments"]}</td>
   <td class='text-center'>{$lang["torrenttable_added"]}</td>
   <td class='text-center'>{$lang["torrenttable_size"]}</td>
   <td class='text-center'>{$lang["torrenttable_snatched"]}</td>
   <td class='text-center'>{$lang["torrenttable_seeders"]}</td>
   <td class='text-center'>{$lang["torrenttable_leechers"]}</td>";
    if ($variant == 'index') $HTMLOUT.= "<td class='text-center'>{$lang["torrenttable_uppedby"]}</td>\n";
    $HTMLOUT.= "</tr>\n";
    $categories = genrelist();
    foreach ($categories as $key => $value) $change[$value['id']] = array(
        'id' => $value['id'],
        'name' => $value['name'],
        'image' => $value['image']
    );
    while ($row = $res->fetch_assoc()) {
        $row['cat_name'] = htmlsafechars($change[$row['category']]['name']);
        $row['cat_pic'] = htmlsafechars($change[$row['category']]['image']);
        $id = (int)$row["id"];
        $HTMLOUT.= "<tr>\n";
        $HTMLOUT.= "<td align='center' style='padding: 0px'>";
        if (isset($row["cat_name"])) {
            $HTMLOUT.= "<a href='browse.php?cat=" . (int)$row['category'] . "'>";
            if (isset($row["cat_pic"]) && $row["cat_pic"] != "") $HTMLOUT.= "<img border='0' src='{$TRINITY20['pic_base_url']}caticons/{$CURUSER['categorie_icon']}/{$row['cat_pic']}' alt='{$row['cat_name']}' />";
            else {
                $HTMLOUT.= $row["cat_name"];
            }
            $HTMLOUT.= "</a>";
        } else {
            $HTMLOUT.= "-";
        }
        $HTMLOUT.= "</td>\n";
        $dispname = htmlsafechars($row["name"]);
        $HTMLOUT.= "<td align='left'><a href='details.php?";
        if ($variant == "mytorrents") $HTMLOUT.= "returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;";
        $HTMLOUT.= "id=$id";
        if ($variant == "index") $HTMLOUT.= "&amp;hit=1";
        $HTMLOUT.= "'><b>$dispname</b></a>&nbsp;</td>";
        $HTMLOUT.= ($variant == "index" ? "<td align='center'><a href=\"download.php?torrent={$id}\"><img src='{$TRINITY20['pic_base_url']}zip.gif' border='0' alt='Download Bookmark!' title='Download Bookmark!' /></a></td>" : "");
        $bm = sql_query("SELECT * FROM bookmarks WHERE torrentid=" . sqlesc($id) . " AND userid=" . sqlesc($CURUSER['id']));
        $bms = $bm->fetch_assoc();
        $bookmarked = (empty($bms) ? '<a href=\'bookmarks.php?torrent=' . $id . '&amp;action=add\'><img src=\'' . $TRINITY20['pic_base_url'] . 'bookmark.gif\' border=\'0\' alt=\'Bookmark it!\' title=\'Bookmark it!\'></a>' : '<a href="bookmarks.php?torrent=' . $id . '&amp;action=delete"><img src=\'' . $TRINITY20['pic_base_url'] . 'aff_cross.gif\' border=\'0\' alt=\'Delete Bookmark!\' title=\'Delete Bookmark!\' /></a>');
        $HTMLOUT.= ($variant == "index" ? "<td align='center'>{$bookmarked}</td>" : "");
        if ($variant == "mytorrents") $HTMLOUT.= "</td><td align='center'><a href='edit.php?returnto=" . urlencode($_SERVER["REQUEST_URI"]) . "&amp;id=" . (int)$row['id'] . "'>{$lang["torrenttable_edit"]}</a>\n";
        if ($variant == "mytorrents") {
            $HTMLOUT.= "<td align='right'>";
            if ($row["visible"] == "no") $HTMLOUT.= "<b>{$lang["torrenttable_not_visible"]}</b>";
            else $HTMLOUT.= "{$lang["torrenttable_visible"]}";
            $HTMLOUT.= "</td>\n";
        }
        if ($row["type"] == "single") {
            $HTMLOUT.= "<td align='right'>" . (int)$row["numfiles"] . "</td>\n";
        } else {
            if ($variant == "index") {
                $HTMLOUT.= "<td align='right'><b><a href='filelist.php?id=$id'>" . (int)$row["numfiles"] . "</a></b></td>\n";
            } else {
                $HTMLOUT.= "<td align='right'><b><a href='filelist.php?id=$id'>" . (int)$row["numfiles"] . "</a></b></td>\n";
            }
        }
        if (!$row["comments"]) {
            $HTMLOUT.= "<td align='right'>" . (int)$row["comments"] . "</td>\n";
        } else {
            if ($variant == "index") {
                $HTMLOUT.= "<td align='right'><b><a href='details.php?id=$id&amp;hit=1&amp;tocomm=1'>" . (int)$row["comments"] . "</a></b></td>\n";
            } else {
                $HTMLOUT.= "<td align='right'><b><a href='details.php?id=$id&amp;page=0#startcomments'>" . (int)$row["comments"] . "</a></b></td>\n";
            }
        }
        $HTMLOUT.= "<td align='center'><span style='white-space: nowrap;'>" . str_replace(",", "<br />", get_date($row['added'], '')) . "</span></td>\n";
        $HTMLOUT.= "
    <td align='center'>" . str_replace(" ", "<br />", mksize($row["size"])) . "</td>\n";
        if ($row["times_completed"] != 1) $_s = "" . $lang["torrenttable_time_plural"] . "";
        else $_s = "" . $lang["torrenttable_time_singular"] . "";
        $HTMLOUT.= "<td align='center'><a href='snatches.php?id=$id'>" . number_format($row["times_completed"]) . "<br />$_s</a></td>\n";
        if ($row["seeders"]) {
            if ($variant == "index") {
                if ($row["leechers"]) $ratio = $row["seeders"] / $row["leechers"];
                else $ratio = 1;
                $HTMLOUT.= "<td align='right'><b><a href='peerlist.php?id=$id#seeders'>
               <font color='" . get_slr_color($ratio) . "'>" . (int)$row["seeders"] . "</font></a></b></td>\n";
            } else {
                $HTMLOUT.= "<td align='right'><b><a class='" . linkcolor($row["seeders"]) . "' href='peerlist.php?id=$id#seeders'>" . (int)$row["seeders"] . "</a></b></td>\n";
            }
        } else {
            $HTMLOUT.= "<td align='right'><span class='" . linkcolor($row["seeders"]) . "'>" . (int)$row["seeders"] . "</span></td>\n";
        }
        if ($row["leechers"]) {
            if ($variant == "index") $HTMLOUT.= "<td align='right'><b><a href='peerlist.php?id=$id#leechers'>" . number_format($row["leechers"]) . "</a></b></td>\n";
            else $HTMLOUT.= "<td align='right'><b><a class='" . linkcolor($row["leechers"]) . "' href='peerlist.php?id=$id#leechers'>" . (int)$row["leechers"] . "</a></b></td>\n";
        } else $HTMLOUT.= "<td align='right'>0</td>\n";
        if ($variant == "index") $HTMLOUT.= "<td align='center'>" . (isset($row["username"]) ? ("<a href='userdetails.php?id=" . (int)$row["owner"] . "'><b>" . htmlsafechars($row["username"]) . "</b></a>") : "<i>(" . $lang["torrenttable_unknown_uploader"] . ")</i>") . "</td>\n";
        $HTMLOUT.= "</tr>\n";
    }
    $HTMLOUT.= "</table>\n";
    return $HTMLOUT;
}

$HTMLOUT.= "<h1>". $lang['bookmarks_sharefor']." <a href=\"userdetails.php?id=" . $userid . "\">" . htmlsafechars($arr['username']) . "</a></h1>";
$HTMLOUT.= "<b><a href=\"bookmarks.php?action=viewbookmarks&amp;id=".(int)$CURUSER['id']."\">{$lang['bookmarks_my']}</a></b>";
$torrentsperpage = $CURUSER["torrentsperpage"];
    if (!$torrentsperpage) $torrentsperpage = 25;
    $pager = pager($torrentsperpage, $count, "bookmarks.php?action=viewsharemarks&amp;id=".$userid."&amp;");
    $query1 = "SELECT bookmarks.id as bookmarkid, torrents.username, torrents.owner, torrents.id, torrents.name, torrents.type, torrents.comments, torrents.leechers, torrents.seeders, torrents.save_as, torrents.numfiles, torrents.added, torrents.filename, torrents.size, torrents.views, torrents.visible, torrents.hits, torrents.times_completed, torrents.category FROM bookmarks LEFT JOIN torrents ON bookmarks.torrentid = torrents.id WHERE bookmarks.userid = " . sqlesc($userid) . " AND bookmarks.private = 'no' ORDER BY id DESC {$pager['limit']}";
    $res = sql_query($query1) or sqlerr(__FILE__, __LINE__);
    $HTMLOUT.= $pager['pagertop'];
    $HTMLOUT.= sharetable($res, "index", TRUE);
    $HTMLOUT.= $pager['pagerbottom'];
        }
    }
}
echo stdhead($pagetitle) . $HTMLOUT . stdfoot();
?>