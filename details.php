<?php
/*
 |--------------------------------------------------------------------------|
 |   https://github.com/iseeyoucopy/                                        |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2020 U-232 Codename Trinity                              |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: iseeyoucopy, stonebreath, GodFather                   |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
*/
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'bbcode_functions.php');
require_once (INCL_DIR . 'pager_functions.php');
require_once (INCL_DIR . 'comment_functions.php');
require_once (INCL_DIR . 'add_functions.php');
require_once (INCL_DIR . 'html_functions.php');
require_once (INCL_DIR . 'function_rating.php');
//require_once (INCL_DIR . 'tvrage_functions.php');
//require_once (INCL_DIR . 'tvmaze_functions.php');// uncomment to use tvmaze
require_once (IMDB_DIR . 'imdb.class.php');
require_once (INCL_DIR . 'getpre.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('details'), load_language('free_details'));
parked();
$stdhead = array(
    /* include css **/
    'css' => array(
    'bbcode',
        'details',
        'rating_style',
    )
);
$stdfoot = array(
    /* include js **/
    'js' => array(
        'popup',
        'jquery.thanks',
        'wz_tooltip',
        'java_klappe',
        'balloontip',
        'shout',
        'thumbs',
        'sack'
    )
);
$HTMLOUT = $torrent_cache = '';
if (!isset($_GET['id']) || !is_valid_id($_GET['id'])) stderr("{$lang['details_user_error']}", "{$lang['details_bad_id']}");
$id = (int)$_GET["id"];

$categorie = genrelist();
foreach ($categorie as $key => $value) $change[$value['id']] = array(
    'id' => $value['id'],
    'name' => $value['name'],
    'image' => $value['image'],
    'min_class' => $value['min_class']
);
if (($torrents = $cache->get('torrent_details_' . $id)) === false) {
    $tor_fields_ar_int = array(
        'id',
        'leechers',
        'seeders',
        'thanks',
        'comments',
        'owner',
        'size',
        'added',
        'views',
        'hits',
        'numfiles',
        'times_completed',
        'points',
        'last_reseed',
        'category',
        'free',
        'freetorrent',
        'silver',
        'rating_sum',
		'checked_when',
        'num_ratings',
        'mtime',
        'checked_when'
    );
    $tor_fields_ar_str = array(
        'banned',
        'info_hash',
        'checked_by',
        'filename',
        'search_text',
        'name',
        'save_as',
        'visible',
        'type',
        'poster',
        'url',
        'anonymous',
        'allow_comments',
        'description',
        'nuked',
        'nukereason',
        'vip',
        'subs',
        'username',
        'newgenre',
        'release_group',
        'youtube',
        'tags',
        'user_likes'
    );
    $tor_fields = implode(', ', array_merge($tor_fields_ar_int, $tor_fields_ar_str));
    $result = sql_query("SELECT " . $tor_fields . ", (SELECT MAX(id) FROM torrents ) as max_id, (SELECT MIN(id) FROM torrents) as min_id, LENGTH(nfo) AS nfosz, IF(num_ratings < {$INSTALLER09['minvotes']}, NULL, ROUND(rating_sum / num_ratings, 1)) AS rating FROM torrents WHERE id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
    $torrents = mysqli_fetch_assoc($result);
    foreach ($tor_fields_ar_int as $i) $torrents[$i] = (int)$torrents[$i];
    foreach ($tor_fields_ar_str as $i) $torrents[$i] = $torrents[$i];
    $cache->set('torrent_details_' . $id, $torrents, $INSTALLER09['expires']['torrent_details']);
}
	$tor_cat = isset($torrents['category']) ? $torrents['category'] : '';
   if ($change[$tor_cat]['min_class'] > $CURUSER['class']) stderr("{$lang['details_user_error']}", "{$lang['details_bad_id']}");
//==
if (($torrents_xbt = $cache->get('torrent_xbt_data_' . $id)) === false && XBT_TRACKER == true) {
    $torrents_xbt = mysqli_fetch_assoc(sql_query("SELECT seeders, leechers, times_completed FROM torrents WHERE id =" . sqlesc($id))) or sqlerr(__FILE__, __LINE__);
    $cache->set('torrent_xbt_data_' . $id, $torrents_xbt, $INSTALLER09['expires']['torrent_xbt_data']);
}
//==
if (($torrents_txt = $cache->get('torrent_details_txt' . $id)) === false) {
    $torrents_txt = mysqli_fetch_assoc(sql_query("SELECT descr FROM torrents WHERE id =" . sqlesc($id))) or sqlerr(__FILE__, __LINE__);
    $cache->set('torrent_details_txt' . $id, $torrents_txt, $INSTALLER09['expires']['torrent_details_text']);
}
//Memcache Pretime
if (($pretime = $cache->get('torrent_pretime_'.$id)) === false) {
    $prename = htmlsafechars($torrents['name']);
    $pre_q = sql_query("SELECT time FROM releases WHERE releasename = " . sqlesc($prename)) or sqlerr(__FILE__, __LINE__);
    $pret = mysqli_fetch_assoc($pre_q);
	$pretimere = isset($pret['time']) ? $pret['time'] : '';
    $pretime['time'] = strtotime($pretimere);
    $cache->set('torrent_pretime_'.$id, $pretime, $INSTALLER09['expires']['torrent_pretime']);
}
$newgenre = '';
if (!empty($torrents['newgenre'])) {
	$newgenre = array();
	$torrents['newgenre'] = explode(',', $torrents['newgenre']);
	foreach ($torrents['newgenre'] as $foo) $newgenre[] = '<a href="browse.php?search=' . trim(strtolower($foo)) . '&amp;searchin=genre">' . $foo . '</a>';
	$newgenre = '<i>' . join(' | ', $newgenre) . '</i>';
}
//==
if (isset($_GET["hit"])) {
    sql_query("UPDATE torrents SET views = views + 1 WHERE id =" . sqlesc($id));
    $update['views'] = ($torrents['views'] + 1);
    $cache->update_row('torrent_details_' . $id, [
        'views' => $update['views']
    ], $INSTALLER09['expires']['torrent_details']);
    header("Location: {$INSTALLER09['baseurl']}/details.php?id=$id");
    exit();
}
$What_String = (XBT_TRACKER == true ? 'mtime' : 'last_action');
$What_String_Key = (XBT_TRACKER == true ? 'last_action_xbt_' : 'last_action_');
if (($l_a = $cache->get($What_String_Key.$id)) === false) {
    $l_a = mysqli_fetch_assoc(sql_query('SELECT '.$What_String.' AS lastseed ' . 'FROM torrents ' . 'WHERE id = ' . sqlesc($id))) or sqlerr(__FILE__, __LINE__);
    $l_a['lastseed'] = (int)$l_a['lastseed'];
    $cache->set('last_action_' . $id, $l_a, 1800);
}
//==Thumbs Up
if (($thumbs = $cache->get('thumbs_up_' . $id)) === false) {
    $thumbs = mysqli_num_rows(sql_query("SELECT id, type, torrentid, userid FROM thumbsup WHERE torrentid = " . sqlesc($torrents['id'])));
    $thumbs = (int)$thumbs;
    $cache->set('thumbs_up_' . $id, $thumbs, 0);
}
//==
/* seeders/leechers/completed caches pdq**/
$torrents['times_completed'] = ((XBT_TRACKER === false || $torrents_xbt['times_completed'] === false || $torrents_xbt['times_completed'] === 0 || $torrents_xbt['times_completed'] === false) ? $torrents['times_completed'] : $torrents_xbt['times_completed']);

//==rep user query by pdq
$torrent_user_rep = isset($torrent_cache['rep']) ? $torrent_cache['rep'] : '';
if (($torrent_user_rep = $cache->get('user_rep_' . $torrents['owner'])) === false) {
    $torrent_user_rep = array();
    $us = sql_query("SELECT reputation FROM users WHERE id =" . sqlesc($torrents['owner'])) or sqlerr(__FILE__, __LINE__);
    if (mysqli_num_rows($us)) {
        $torrent_user_rep = mysqli_fetch_assoc($us);
        $cache->set('user_rep_' . $torrents['owner'], $torrent_user_rep, 14 * 86400);
    }
}
$HTMLOUT.='<script type="text/javascript">
    jQuery(\'link[href="/css/bootstrap.min.css"]\').prop("disabled", true);
</script>';
$HTMLOUT.= "
<script type='text/javascript'>
    /*<![CDATA[*/
    //var e = new sack();
function do_rate(rate,id,what) {
        var box = document.getElementById('rate_'+id);
        e.setVar('rate',rate);
        e.setVar('id',id);
        e.setVar('ajax','1');
        e.setVar('what',what);
        e.requestFile = 'rating.php';
        e.method = 'GET';
        e.element = 'rate_'+id;
        e.onloading = function () {
            box.innerHTML = 'Loading ...'
        }
        e.onCompletion = function() {
            if(e.responseStatus)
                box.innerHTML = e.response();
        }
        e.onerror = function () {
            alert('That was something wrong with the request!');
        }
        e.runAJAX();
}
/*]]>*/
</script>";
$owned = $moderator = 0;
if ($CURUSER["class"] >= UC_STAFF) $owned = $moderator = 1;
elseif ($CURUSER["id"] == $torrents["owner"]) $owned = 1;
if ($torrents["vip"] == "1" && $CURUSER["class"] < UC_VIP) stderr("{$lang['details_add_err1']}", "{$lang['details_add_err2']}");
if (!$torrents || ($torrents["banned"] == "yes" && !$moderator)) stderr("{$lang['details_error']}", "{$lang['details_torrent_id']}");
if ($CURUSER["id"] == $torrents["owner"] || $CURUSER["class"] >= UC_STAFF) $owned = 1;
else $owned = 0;
$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if (empty($torrents["tags"])) {
    $keywords = $lang['details_add_key'];
} else {
    $tags = explode(",", $torrents['tags']);
    $keywords = "";
    foreach ($tags as $tag) {
        $keywords.= "<a href='browse.php?search=$tag&amp;searchin=tags&amp;incldead=1'>" . htmlsafechars($tag) . "</a>,";
    }
    $keywords = substr($keywords, 0, (strlen($keywords) - 1));
}

if (isset($_GET["uploaded"])) {
    $HTMLOUT.= "<div class='alert alert-success col-md-11' align='center'><h2>{$lang['details_success']}</h2>";
    $HTMLOUT.= "<p>{$lang['details_start_seeding']}</p></div>\n";
    $HTMLOUT.= '<meta http-equiv="refresh" content="1;url=download.php?torrent=' . $id . '">';
} elseif (isset($_GET["edited"])) {
    $HTMLOUT.= "<div class='alert alert-success span11' align='center'><h2>{$lang['details_success_edit']}</h2></div>\n";
    if (isset($_GET["returnto"])) $HTMLOUT.= "<p><b>{$lang['details_go_back']}<a href='" . htmlsafechars($_GET["returnto"]) . "'>{$lang['details_whence']}</a>.</b></p>\n";
} elseif (isset($_GET["reseed"])) {
    $HTMLOUT.= "<div class='alert alert-success col-md-11' align='center'><h2>{$lang['details_add_succ1']}</h2></div>\n";
}
//==pdq's Torrent Moderation
if ($CURUSER['class'] >= UC_STAFF) {
    if (isset($_GET["checked"]) && $_GET["checked"] == 1) {
        sql_query("UPDATE torrents SET checked_by = " . sqlesc($CURUSER['username']) . ",checked_when = ".TIME_NOW." WHERE id =" . sqlesc($id) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
 $cache->update_row('torrent_details_' . $id, [
            'checked_by' => $CURUSER['username'],
            'checked_when' => TIME_NOW
        ], $INSTALLER09['expires']['torrent_details']);
        $cache->delete('checked_by_' . $id);
        write_log("Torrent <a href={$INSTALLER09['baseurl']}/details.php?id=$id>(" . htmlsafechars($torrents['name']) . ")</a>{$lang['details_add_chk']}{$CURUSER['username']}");
        header("Location: {$INSTALLER09["baseurl"]}/details.php?id=$id&checked=done#Success");
    } elseif (isset($_GET["rechecked"]) && $_GET["rechecked"] == 1) {
        sql_query("UPDATE torrents SET checked_by = " . sqlesc($CURUSER['username']) . ",checked_when = ".TIME_NOW." WHERE id =" . sqlesc($id) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
        $cache->update_row('torrent_details_' . $id, [
            'checked_by' => $CURUSER['username'],
            'checked_when' => TIME_NOW
        ], $INSTALLER09['expires']['torrent_details']);
        $cache->delete('checked_by_' . $id);
        write_log("Torrent <a href={$INSTALLER09['baseurl']}/details.php?id=$id>(" . htmlsafechars($torrents['name']) . ")</a>{$lang['details_add_rchk']}{$CURUSER['username']}");
        header("Location: {$INSTALLER09["baseurl"]}/details.php?id=$id&rechecked=done#Success");
    } elseif (isset($_GET["clearchecked"]) && $_GET["clearchecked"] == 1) {
        sql_query("UPDATE torrents SET checked_by = '', checked_when='' WHERE id =" . sqlesc($id) . " LIMIT 1") or sqlerr(__FILE__, __LINE__);
        $cache->update_row('torrent_details_' . $id, [
            'checked_by' => '',
            'checked_when' => ''
        ], $INSTALLER09['expires']['torrent_details']);
        $cache->delete('checked_by_' . $id);
        write_log("Torrent <a href={$INSTALLER09["baseurl"]}/details.php?id=$id>(" . htmlsafechars($torrents['name']) . ")</a>{$lang['details_add_uchk']}{$CURUSER['username']}");
        header("Location: {$INSTALLER09["baseurl"]}/details.php?id=$id&clearchecked=done#Success");
    }
    if (isset($_GET["checked"]) && $_GET["checked"] == 'done') 
		$HTMLOUT.= "<div class='alert alert-success span11' align='center'><h2><a name='Success'>{$lang['details_add_chksc']}{$CURUSER['username']}!</a></h2></div>";
    if (isset($_GET["rechecked"]) && $_GET["rechecked"] == 'done') 
		$HTMLOUT.= "<div class='alert alert-success span11' align='center'><h2><a name='Success'>{$lang['details_add_rchksc']}{$CURUSER['username']}!</a></h2></div>";
    if (isset($_GET["clearchecked"]) && $_GET["clearchecked"] == 'done') 
		$HTMLOUT.= "<div class='alert alert-success span11' align='center'><h2><a name='Success'>{$lang['details_add_uchksc']}{$CURUSER['username']}!</a></h2></div>";
}
// end
$prev_id = ($id - 1);
         $next_id = ($id + 1);
$s = htmlsafechars($torrents["name"], ENT_QUOTES);
$url = "edit.php?id=" . (int)$torrents["id"];
if (isset($_GET["returnto"])) {
    $addthis = "&amp;returnto=" . urlencode($_GET["returnto"]);
    $url.= $addthis;
    $keepget = $addthis;
}
$editlink = "a href=\"$url\" class=\"sublink\"";
if (!($CURUSER["downloadpos"] == 0 && $CURUSER["id"] != $torrents["owner"] OR $CURUSER["downloadpos"] > 1)) {
require_once MODS_DIR . 'free_details.php';

$possible_actions = array(
    'torrents',
    'description',
    'moreinfo',
    'snatches',
    'imdb'
);
$action = isset($_GET["action"]) ? htmlsafechars(trim($_GET["action"])) : 'torrents';
if (!in_array($action, $possible_actions)) stderr('Error','<br><div class="alert alert-error span11">Error! Change a few things up and try submitting again.</div>');
get_script_access(basename($_SERVER['REQUEST_URI']));
/*Tab selector begins*/
$HTMLOUT .="
<div class='callout'>
<div class='clearfix' ><h4 class='float-left'>$s</h4><a class='float-right' id='thumbsup' href=\"javascript:ThumbsUp('" . (int)$torrents['id'] . "')\"><i class='far fa-thumbs-up'></i></a><hr></div>";
require_once (BLOCK_DIR . 'details/nav.php');
$HTMLOUT.='<div class="tabs-content" data-tabs-content="details-tabs">
  <div class="tabs-panel is-active" id="details_panel1">';
require_once (BLOCK_DIR . 'details/download.php');
$HTMLOUT.='</div>
  <div class="tabs-panel" id="details_panel2">';
require_once (BLOCK_DIR . 'details/description.php'); 
$HTMLOUT.='</div>
  <div class="tabs-panel" id="details_panel3">';
require_once (BLOCK_DIR . 'details/info.php');
$HTMLOUT.='</div>
  <div class="tabs-panel" id="details_panel4">';
require_once (BLOCK_DIR . 'details/snatches.php');
$HTMLOUT.='</div>
  <div class="tabs-panel" id="details_panel5">';
  require_once (BLOCK_DIR . 'details/imdb.php');
$HTMLOUT.='</div>';
}
else {
    $HTMLOUT.="<table class='striped'><tr><td align='right' class='heading'>{$lang['details_err_down1']}</td><td>{$lang['details_err_down2']}</td></tr></table>";
}
$HTMLOUT.='</div>';
$HTMLOUT.= "<ul class='menu expanded align-center'>";
if($torrents["id"] != $torrents["min_id"])
	$HTMLOUT .= "<li class='pagination-previous'><a href='details.php?id={$prev_id}'><b>{$lang['details_add_next']}</b></a></li>";
if($torrents["id"] != $torrents["max_id"])
	$HTMLOUT .= "<li class='pagination-next'><a href='details.php?id={$next_id}'><b>{$lang['details_add_prev']}</b></a></li>";;
$HTMLOUT.= "</div>";
///Add Comment

////Comment disable


//Comments
//////////////////////// HTML OUTPUT ////////////////////////////
echo stdhead("{$lang['details_details']}\"" . htmlsafechars($torrents["name"], ENT_QUOTES) . "\"", true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
?>
