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
require_once(INCL_DIR.'bbcode_functions.php');
require_once(INCL_DIR.'pager_functions.php');
require_once(INCL_DIR.'comment_functions.php');
require_once(INCL_DIR.'add_functions.php');
require_once(INCL_DIR.'html_functions.php');
require_once(INCL_DIR.'function_rating.php');
require_once(INCL_DIR.'tvmaze_functions.php');
require_once(IMDB_DIR.'imdb.class.php');
require_once(INCL_DIR.'getpre.php');
//require_once(CLASS_DIR.'TVMazeIncludes.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('details'), load_language('free_details'));
parked();
$stdhead = [
    /* include css **/
    'css' => [
        'bbcode',
        'details',
        'rating_style',
    ],
];
$stdfoot = [
    /* include js **/
    'js' => [
        'popup',
        'jquery.thanks',
        'thumbs',
        'sack',
    ],
];
$HTMLOUT = $torrent_cache = '';
if (!isset($_GET['id']) || !is_valid_id($_GET['id'])) {
    stderr("{$lang['details_user_error']}", "{$lang['details_bad_id']}");
}
$id = (int)$_GET["id"];

$categorie = genrelist();
foreach ($categorie as $key => $value) {
    $change[$value['id']] = [
        'id' => $value['id'],
        'name' => $value['name'],
        'image' => $value['image'],
        'min_class' => $value['min_class'],
    ];
}
if (($torrents = $cache->get($keys['torrent_details'].$id)) === false) {
    $tor_fields_ar_int = [
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
        'checked_when',
    ];
    $tor_fields_ar_str = [
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
        'user_likes',
    ];
    $tor_fields = implode(', ', array_merge($tor_fields_ar_int, $tor_fields_ar_str));
    ($result = sql_query("SELECT ".$tor_fields.", (SELECT MAX(id) FROM torrents ) as max_id, (SELECT MIN(id) FROM torrents) as min_id, LENGTH(nfo) AS nfosz, IF(num_ratings < {$TRINITY20['minvotes']}, NULL, ROUND(rating_sum / num_ratings, 1)) AS rating FROM torrents WHERE id = ".sqlesc($id))) || sqlerr(__FILE__,
        __LINE__);
    $torrents = $result->fetch_assoc();
    foreach ($tor_fields_ar_int as $i) {
        $torrents[$i] = (int)$torrents[$i];
    }
    foreach ($tor_fields_ar_str as $i) {
        $torrents[$i] = $torrents[$i];
    }
    $cache->set($keys['torrent_details'].$id, $torrents, $TRINITY20['expires']['torrent_details']);
}
$tor_cat = $torrents['category'] ?? '';
if ($change[$tor_cat]['min_class'] > $CURUSER['class']) {
    stderr("{$lang['details_user_error']}", "{$lang['details_bad_id']}");
}
//==
if (($torrents_xbt = $cache->get($keys['torrent_xbt'].$id)) === false && XBT_TRACKER == true) {
    ($t_xbt_d = sql_query("SELECT seeders, leechers, times_completed FROM torrents WHERE id =".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $torrents_xbt = $t_xbt_d->fetch_assoc();
    $cache->set($keys['torrent_xbt'].$id, $torrents_xbt, $TRINITY20['expires']['torrent_xbt_data']);
}
//==
if (($torrents_txt = $cache->get($keys['torrent_details_txt'].$id)) === false) {
    ($t_txt_des = sql_query("SELECT descr FROM torrents WHERE id =".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $torrents_txt = $t_txt_des->fetch_assoc();
    $cache->set($keys['torrent_details_txt'].$id, $torrents_txt, $TRINITY20['expires']['torrent_details_text']);
}
//Memcache Pretime
if (($pretime = $cache->get($keys['torrent_pretime'].$id)) === false) {
    $prename = htmlsafechars($torrents['name']);
    ($pre_q = sql_query("SELECT time FROM releases WHERE releasename = ".sqlesc($prename))) || sqlerr(__FILE__, __LINE__);
    $pret = $pre_q->fetch_assoc();
    $pretimere = $pret['time'] ?? '';
    $pretime['time'] = strtotime($pretimere);
    $cache->set($keys['torrent_pretime'].$id, $pretime, $TRINITY20['expires']['torrent_pretime']);
}
$newgenre = '';
if (!empty($torrents['newgenre'])) {
    $newgenre = [];
    $torrents['newgenre'] = explode(',', $torrents['newgenre']);
    foreach ($torrents['newgenre'] as $foo) {
        $newgenre[] = '<a href="browse.php?search='.trim(strtolower($foo)).'&amp;searchin=genre">'.$foo.'</a>';
    }
    $newgenre = '<i>'.implode(' | ', $newgenre).'</i>';
}
//==
if (isset($_GET["hit"])) {
    sql_query("UPDATE torrents SET views = views + 1 WHERE id =".sqlesc($id));
    $update['views'] = ($torrents['views'] + 1);
    $cache->update_row($keys['torrent_details'].$id, [
        'views' => $update['views'],
    ], $TRINITY20['expires']['torrent_details']);
    header("Location: {$TRINITY20['baseurl']}/details.php?id=$id");
    exit();
}
$What_String = (XBT_TRACKER == true ? 'mtime' : 'last_action');
$What_String_Key = (XBT_TRACKER == true ? $keys['last_action_xbt'] : $keys['last_action']);
if (($l_a = $cache->get($What_String_Key.$id)) === false) {
    ($last_t_ac = sql_query('SELECT '.$What_String.' AS lastseed '.'FROM torrents '.'WHERE id = '.sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $l_a = $last_t_ac->fetch_assoc();
    $l_a['lastseed'] = (int)$l_a['lastseed'];
    $cache->set($keys['last_action'].$id, $l_a, 1800);
}
//==Thumbs Up
if (($thumbs = $cache->get('thumbs_up:'.$id)) === false) {
    ($thumbs_query = sql_query("SELECT id, type, torrentid, userid FROM thumbsup WHERE torrentid = ".sqlesc($torrents['id']))) || sqlerr(__FILE__,
        __LINE__);
    $thumbs = $thumbs_query->num_rows;
    $thumbs = (int)$thumbs;
    $cache->set('thumbs_up:'.$id, $thumbs, 0);
}
//==
/* seeders/leechers/completed caches pdq**/
$torrents['times_completed'] = ((XBT_TRACKER === false || $torrents_xbt['times_completed'] === false || $torrents_xbt['times_completed'] === 0 || $torrents_xbt['times_completed'] === false) ? $torrents['times_completed'] : $torrents_xbt['times_completed']);

//==rep user query by pdq
$torrent_user_rep = $torrent_cache['rep'] ?? '';
if (($torrent_user_rep = $cache->get($keys['user_rep'].$torrents['owner'])) === false) {
    $torrent_user_rep = [];
    ($us = sql_query("SELECT reputation FROM users WHERE id =".sqlesc($torrents['owner']))) || sqlerr(__FILE__, __LINE__);
    if ($us->num_rows) {
        $torrent_user_rep = $us->fetch_assoc();
        $cache->set($keys['user_rep'].$torrents['owner'], $torrent_user_rep, 14 * 86400);
    }
}
$HTMLOUT .= '<script type="text/javascript">
    jQuery(\'link[href="/css/bootstrap.min.css"]\').prop("disabled", true);
</script>';
$HTMLOUT .= "
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
if ($CURUSER["class"] >= UC_STAFF) {
    $owned = $moderator = 1;
} elseif ($CURUSER["id"] == $torrents["owner"]) {
    $owned = 1;
}
if ($torrents["vip"] == "1" && $CURUSER["class"] < UC_VIP) {
    stderr("{$lang['details_add_err1']}", "{$lang['details_add_err2']}");
}
if (!$torrents || ($torrents["banned"] == "yes" && !$moderator)) {
    stderr("{$lang['details_error']}", "{$lang['details_torrent_id']}");
}
$owned = $CURUSER["id"] == $torrents["owner"] || $CURUSER["class"] >= UC_STAFF ? 1 : 0;
$spacer = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
if (empty($torrents["tags"])) {
    $keywords = $lang['details_add_key'];
} else {
    $tags = explode(",", $torrents['tags']);
    $keywords = "";
    foreach ($tags as $tag) {
        $keywords .= "<a href='browse.php?search=$tag&amp;searchin=tags&amp;incldead=1'>".htmlsafechars($tag)."</a>,";
    }
    $keywords = substr($keywords, 0, (strlen($keywords) - 1));
}

if (isset($_GET["uploaded"])) {
    $HTMLOUT .= "<div class='alert alert-success' align='center'><h2>{$lang['details_success']}</h2>";
    $HTMLOUT .= "<p>{$lang['details_start_seeding']}</p></div>";
    $HTMLOUT .= '<meta http-equiv="refresh" content="1;url=download.php?torrent='.$id.'">';
} elseif (isset($_GET["edited"])) {
    $HTMLOUT .= "<div class='alert alert-success span11' align='center'><h2>{$lang['details_success_edit']}</h2></div>\n";
    if (isset($_GET["returnto"])) {
        $HTMLOUT .= "<p><b>{$lang['details_go_back']}<a href='".htmlsafechars($_GET["returnto"])."'>{$lang['details_whence']}</a>.</b></p>\n";
    }
} elseif (isset($_GET["reseed"])) {
    $HTMLOUT .= "<div class='alert alert-success' align='center'><h2>{$lang['details_add_succ1']}</h2></div>\n";
}
//==pdq's Torrent Moderation
if ($CURUSER['class'] >= UC_STAFF) {
    if (isset($_GET["checked"]) && $_GET["checked"] == 1) {
        sql_query("UPDATE torrents SET checked_by = ".sqlesc($CURUSER['username']).",checked_when = ".TIME_NOW." WHERE id =".sqlesc($id)." LIMIT 1") || sqlerr(__FILE__,
            __LINE__);
        $cache->update_row($keys['torrent_details'].$id, [
            'checked_by' => $CURUSER['username'],
            'checked_when' => TIME_NOW,
        ], $TRINITY20['expires']['torrent_details']);
        $cache->delete($keys['checked_by'].$id);
        write_log("Torrent <a href={$TRINITY20['baseurl']}/details.php?id=$id>(".htmlsafechars($torrents['name']).")</a>{$lang['details_add_chk']}{$CURUSER['username']}");
        header("Location: {$TRINITY20["baseurl"]}/details.php?id=$id&checked=done#Success");
    } elseif (isset($_GET["rechecked"]) && $_GET["rechecked"] == 1) {
        sql_query("UPDATE torrents SET checked_by = ".sqlesc($CURUSER['username']).",checked_when = ".TIME_NOW." WHERE id =".sqlesc($id)." LIMIT 1") || sqlerr(__FILE__,
            __LINE__);
        $cache->update_row($keys['torrent_details'].$id, [
            'checked_by' => $CURUSER['username'],
            'checked_when' => TIME_NOW,
        ], $TRINITY20['expires']['torrent_details']);
        $cache->delete($keys['checked_by'].$id);
        write_log("Torrent <a href={$TRINITY20['baseurl']}/details.php?id=$id>(".htmlsafechars($torrents['name']).")</a>{$lang['details_add_rchk']}{$CURUSER['username']}");
        header("Location: {$TRINITY20["baseurl"]}/details.php?id=$id&rechecked=done#Success");
    } elseif (isset($_GET["clearchecked"]) && $_GET["clearchecked"] == 1) {
        sql_query("UPDATE torrents SET checked_by = '', checked_when='' WHERE id =".sqlesc($id)." LIMIT 1") || sqlerr(__FILE__, __LINE__);
        $cache->update_row($keys['torrent_details'].$id, [
            'checked_by' => '',
            'checked_when' => '',
        ], $TRINITY20['expires']['torrent_details']);
        $cache->delete($keys['checked_by'].$id);
        write_log("Torrent <a href={$TRINITY20["baseurl"]}/details.php?id=$id>(".htmlsafechars($torrents['name']).")</a>{$lang['details_add_uchk']}{$CURUSER['username']}");
        header("Location: {$TRINITY20["baseurl"]}/details.php?id=$id&clearchecked=done#Success");
    }
    if (isset($_GET["checked"]) && $_GET["checked"] == 'done') {
        $HTMLOUT .= "<div class='alert alert-success span11' align='center'><h2><a name='Success'>{$lang['details_add_chksc']}{$CURUSER['username']}!</a></h2></div>";
    }
    if (isset($_GET["rechecked"]) && $_GET["rechecked"] == 'done') {
        $HTMLOUT .= "<div class='alert alert-success span11' align='center'><h2><a name='Success'>{$lang['details_add_rchksc']}{$CURUSER['username']}!</a></h2></div>";
    }
    if (isset($_GET["clearchecked"]) && $_GET["clearchecked"] == 'done') {
        $HTMLOUT .= "<div class='alert alert-success span11' align='center'><h2><a name='Success'>{$lang['details_add_uchksc']}{$CURUSER['username']}!</a></h2></div>";
    }
}
// end
$prev_id = ($id - 1);
$next_id = ($id + 1);
$url = "edit.php?id=".(int)$torrents["id"];
if (isset($_GET["returnto"])) {
    $addthis = "&amp;returnto=".urlencode($_GET["returnto"]);
    $url .= $addthis;
    $keepget = $addthis;
}
$editlink = "a href=\"$url\"";
if (!($CURUSER["downloadpos"] == 0 && $CURUSER["id"] != $torrents["owner"] || $CURUSER["downloadpos"] > 1)) {
    require_once MODS_DIR.'free_details.php';

    $possible_actions = [
        'torrents',
        'description',
        'moreinfo',
        'snatches',
        'imdb',
    ];
    $action = isset($_GET["action"]) ? htmlsafechars(trim($_GET["action"])) : 'torrents';
    if (!in_array($action, $possible_actions)) {
        stderr('Error',
            '<br><div class="alert alert-error span11">Error! Change a few things up and try submitting again.</div>');
    }
    get_script_access(basename($_SERVER['REQUEST_URI']));
    /*Tab selector begins*/
    require_once(BLOCK_DIR.'details/download.php');
    require_once(BLOCK_DIR.'details/nav.php');
    $HTMLOUT .= '<div class="tabs-content" data-tabs-content="details-tabs">
  <div class="tabs-panel is-active padding-0" id="details_panel1">';
    
    $HTMLOUT .= '</div>
  <div class="tabs-panel" id="details_panel2">';
    require_once(BLOCK_DIR.'details/description.php');
    $HTMLOUT .= '</div>
  <div class="tabs-panel" id="details_panel3">';
    require_once(BLOCK_DIR.'details/info.php');
    $HTMLOUT .= '</div>';
    $HTMLOUT .= "</div>";
} else {
    $HTMLOUT .= "<table class='striped'><tr><td align='right' class='heading'>{$lang['details_err_down1']}</td><td>{$lang['details_err_down2']}</td></tr></table>";
}
$HTMLOUT .= "<ul class='menu expanded align-center'>";
if ($torrents["id"] != $torrents["min_id"]) {
    $HTMLOUT .= "<li class='pagination-previous'><a href='details.php?id={$prev_id}'><b>{$lang['details_add_next']}</b></a></li>";
}
if ($torrents["id"] != $torrents["max_id"]) {
    $HTMLOUT .= "<li class='pagination-next'><a href='details.php?id={$next_id}'><b>{$lang['details_add_prev']}</b></a></li>";
}
echo stdhead("{$lang['details_details']}\"".htmlsafechars($torrents["name"], ENT_QUOTES)."\"", true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
?>
