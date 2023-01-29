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
require_once(INCL_DIR.'comment_functions.php');
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('comment'), load_language('capprove'));
if ($CURUSER['suspended'] == 'yes') {
    stderr("Sorry", "Your account is suspended");
}
flood_limit('comments');
$action = (isset($_GET['action']) ? htmlspecialchars($_GET['action']) : 0);
//$vaction = array('add','delete','edit','approve','disapprove','vieworiginal','');
//$action = (isset($_POST['action']) && in_array($_POST['action'],$vaction) ? htmlspecialchars($_POST['action']) : (isset($_GET['action']) && in_array($_GET['action'],$vaction) ? htmlspecialchars($_GET['action']) : ''));
$stdhead = [
    /** include css **/
    'css' => [
        'bbcode',
        'forums',
        'style',
        'style2',
    ],
];
$stdfoot = [
    /** include js **/
    'js' => [
        'check_selected',
    ],
];
/** comment stuffs by pdq **/
$locale = 'torrent';
$locale_link = 'details';
$extra_link = '';
$sql_1 = 'name, owner, comments, anonymous FROM torrents'; // , anonymous
$name = 'name';
$table_type = $locale.'s';
$_GET['type'] ??= $_POST['locale'] ?? '';
if (isset($_GET['type'])) {
    $type_options = [
        'torrent' => 'details',
        'request' => 'viewrequests',
    ];
    if (isset($type_options[$_GET['type']])) {
        $locale_link = $type_options[$_GET['type']];
        $locale = $_GET['type'];
    }
    switch ($_GET['type']) {
        case 'request':
            $sql_1 = 'request FROM requests';
            $name = 'request';
            $extra_link = '&req_details';
            $table_type = $locale.'s';
            break;

        default:
            //case 'torrent':
            $sql_1 = 'name, owner, comments, anonymous FROM torrents'; // , anonymous
            $name = 'name';
            $table_type = $locale.'s';
            break;
    }
}
/** end comment stuffs by pdq **/
if ($action == 'add') {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = (isset($_POST['tid']) ? (int)$_POST['tid'] : 0);
        if (!is_valid_id($id)) {
            stderr("{$lang['comment_error']}", "{$lang['comment_invalid_id']}");
        }
        ($res = sql_query("SELECT $sql_1 WHERE id = ".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
        $arr = $res->fetch_array(MYSQLI_BOTH);
        if (!$arr) {
            stderr("{$lang['comment_error']}", "No $locale with that ID.");
        }
        $body = (isset($_POST['body']) ? trim($_POST['body']) : '');
        if ($body === '') {
            stderr("{$lang['comment_error']}", "{$lang['comment_body']}");
        }
        $owner = ($arr['owner'] ?? 0);
        $arr['anonymous'] = (isset($arr['anonymous']) && $arr['anonymous'] == 'yes' ? 'yes' : 'no');
        $arr['comments'] ??= 0;
        if ($CURUSER['id'] == $owner && $arr['anonymous'] == 'yes' || (isset($_POST['anonymous']) && $_POST['anonymous'] == 'yes')) {
            $anon = "'yes'";
        } else {
            $anon = "'no'";
        }

        sql_query("INSERT INTO comments (user, $locale, added, text, ori_text, anonymous) VALUES (".sqlesc($CURUSER["id"]).", ".sqlesc($id).", ".TIME_NOW.", ".sqlesc($body).", ".sqlesc($body).", $anon)");
        $newid = $mysqli->insert_id;
        sql_query("UPDATE $table_type SET comments = comments + 1 WHERE id = ".sqlesc($id)) || sqlerr(__FILE__, __LINE__);
        if ($TRINITY20['seedbonus_on'] == 1) {
            if ($TRINITY20['karma'] && isset($CURUSER['seedbonus'])) {
                sql_query("UPDATE users SET seedbonus = seedbonus+".sqlesc($TRINITY20['bonus_per_comment'])." WHERE id = ".sqlesc($CURUSER['id'])) || sqlerr(__FILE__,
                    __LINE__);
            }
            $update['comments'] = ($arr['comments'] + 1);
            $cache->update_row($cache_keys['torrent_details'].$id, [
                'comments' => $update['comments'],
            ], 0);
            $update['seedbonus'] = ($CURUSER['seedbonus'] + $TRINITY20['bonus_per_comment']);
            $cache->update_row($cache_keys['user_stats'].$CURUSER["id"], [
                'seedbonus' => $update['seedbonus'],
            ], $TRINITY20['expires']['u_stats']);
            $cache->update_row($cache_keys['user_statss'].$CURUSER["id"], [
                'seedbonus' => $update['seedbonus'],
            ], $TRINITY20['expires']['user_stats']);
            //===end
        }
        // --- pm if new comment mod---//
        ($cpm = sql_query("SELECT commentpm FROM users WHERE id = ".sqlesc($owner))) || sqlerr(__FILE__, __LINE__);
        $cpm_r = $cpm->fetch_assoc();
        if ($cpm_r['commentpm'] == 'yes') {
            $added = TIME_NOW;
            $subby = sqlesc("Someone has left a comment");
            $notifs = sqlesc("You have received a comment on your torrent [url={$TRINITY20['baseurl']}/details.php?id={$id}] ".htmlspecialchars($arr['name'])."[/url].");
            sql_query("INSERT INTO messages (sender, receiver, subject, msg, added) VALUES(0, ".sqlesc($arr['owner']).", $subby, $notifs, $added)") || sqlerr(__FILE__,
                __LINE__);
        }
        // ---end---//
        header("Refresh: 0; url=$locale_link.php?id=$id$extra_link&viewcomm=$newid#comm$newid");
        die;
    }
    $id = (isset($_GET['tid']) ? (int)$_GET['tid'] : 0);
    if (!is_valid_id($id)) {
        stderr("{$lang['comment_error']}", "{$lang['comment_invalid_id']}");
    }
    ($res = sql_query("SELECT $sql_1 WHERE id = ".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $arr = $res->fetch_assoc();
    if (!$arr) {
        stderr("{$lang['comment_error']}", "No $locale with that ID.");
    }
    $HTMLOUT = '';
    $body = htmlspecialchars(($_POST['body'] ?? ''));
    $HTMLOUT .= "<h1>{$lang['comment_add']}'".htmlspecialchars($arr[$name])."'</h1>
      <br /><form name='compose' method='post' action='comment.php?action=add'>
      <input type='hidden' name='tid' value='{$id}'/>
      <input type='hidden' name='locale' value='$name' />";
    if ($TRINITY20['BBcode'] && function_exists('textbbcode')) {
        $HTMLOUT .= textbbcode('comments', 'body');
    } else {
        $HTMLOUT .= "<textarea name='text' rows='10' cols='60'></textarea>";
    }
    $HTMLOUT .= "<br />
      <label for='anonymous'>Tick this to post anonymously</label>
      <input id='anonymous' type='checkbox' name='anonymous' value='yes' />
      <br /><input type='submit' class='btn' value='{$lang['comment_doit']}' /></form>";
    $res = sql_query("SELECT comments.id, text, comments.added, comments.$locale, comments.anonymous, comments.editedby, comments.editedat, comments.edit_name, username, users.id as user, users.title, users.avatar, users.offavatar, users.av_w, users.av_h, users.class, users.reputation, users.mood, users.donor, users.warned FROM comments LEFT JOIN users ON comments.user = users.id WHERE $locale = ".sqlesc($id)." ORDER BY comments.id DESC LIMIT 5");
    $allrows = [];
    while ($row = $res->fetch_assoc()) {
        $allrows[] = $row;
    }
    if (count($allrows) > 0) {
        require_once(INCL_DIR.'html_functions.php');
        require_once(INCL_DIR.'bbcode_functions.php');
        require_once(INCL_DIR.'user_functions.php');
        require_once(INCL_DIR.'comment_functions.php');
        $HTMLOUT .= "<h2>{$lang['comment_recent']}</h2>\n";
        $HTMLOUT .= commenttable($allrows, $locale);
    }
    echo stdhead("{$lang['comment_add']}'".$arr[$name]."'", true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
    die;
}

if ($action == "edit") {
    $commentid = (isset($_GET['cid']) ? (int)$_GET['cid'] : 0);
    if (!is_valid_id($commentid)) {
        stderr("{$lang['comment_error']}", "{$lang['comment_invalid_id']}");
    }
    ($res = sql_query("SELECT c.*, t.$name, t.id as tid FROM comments AS c LEFT JOIN $table_type AS t ON c.$locale = t.id WHERE c.id=".sqlesc($commentid))) || sqlerr(__FILE__,
        __LINE__);
    $arr = $res->fetch_assoc();
    if (!$arr) {
        stderr("{$lang['comment_error']}", "{$lang['comment_invalid_id']}.");
    }
    if ($arr["user"] != $CURUSER["id"] && $CURUSER['class'] < UC_STAFF) {
        stderr("{$lang['comment_error']}", "{$lang['comment_denied']}");
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $body = ($_POST['body'] ?? '');
        if ($body == '') {
            stderr("{$lang['comment_error']}", "{$lang['comment_body']}");
        }
        $text = htmlspecialchars($body);
        $editedat = TIME_NOW;
        if (isset($_POST['lasteditedby']) || $CURUSER['class'] < UC_STAFF) {
            sql_query("UPDATE comments SET text=".sqlesc($text).", editedat=$editedat, edit_name=".sqlesc($CURUSER['username']).", editedby=".sqlesc($CURUSER['id'])." WHERE id=".sqlesc($commentid)) || sqlerr(__FILE__,
                __LINE__);
        } else {
            sql_query("UPDATE comments SET text=".sqlesc($text).", editedat=$editedat, editedby=0 WHERE id=".sqlesc($commentid)) || sqlerr(__FILE__,
                __LINE__);
        }
        header("Refresh: 0; url=$locale_link.php?id=".(int)$arr['tid']."$extra_link&viewcomm=$commentid#comm$commentid");
        die;
    }
    $HTMLOUT = '';
    $HTMLOUT .= "<h1>{$lang['comment_edit']}'".htmlspecialchars($arr[$name])."'</h1>
      <form method='post' action='comment.php?action=edit&amp;cid=$commentid'>
      <input type='hidden' name='locale' value='$name' />
       <input type='hidden' name='tid' value='".(int)$arr['tid']."' />
      <input type='hidden' name='cid' value='$commentid' />";
    if ($TRINITY20['BBcode'] && function_exists('textbbcode')) {
        $HTMLOUT .= textbbcode('comments', 'body', $arr["text"]);
    } else {
        $HTMLOUT .= "<textarea name='text' rows='10' cols='60'>".htmlspecialchars($arr["text"])."</textarea>";
    }
    $HTMLOUT .= '
      <br />'.($CURUSER['class'] >= UC_STAFF ? '<input type="checkbox" value="lasteditedby" checked="checked" name="lasteditedby" id="lasteditedby" /> Show Last Edited By<br /><br />' : '').' <input type="submit" class="btn" value="'.$lang['comment_doit'].'" /></form>';
    echo stdhead("{$lang['comment_edit']}'".$arr[$name]."'", true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
    die;
} elseif ($action == "delete") {
    if ($CURUSER['class'] < UC_STAFF) {
        stderr("{$lang['comment_error']}", "{$lang['comment_denied']}");
    }
    $commentid = (isset($_GET['cid']) ? (int)$_GET['cid'] : 0);
    $tid = (isset($_GET['tid']) ? (int)$_GET['tid'] : 0);
    if (!is_valid_id($commentid)) {
        stderr("{$lang['comment_error']}", "{$lang['comment_invalid_id']}");
    }
    $sure = isset($_GET["sure"]) ? (int)$_GET["sure"] : false;
    if (!$sure) {
        stderr("{$lang['comment_delete']}",
            "{$lang['comment_about_delete']}\n"."<a href='comment.php?action=delete&amp;cid=$commentid&amp;tid=$tid&amp;sure=1".($locale == 'request' ? '&amp;type=request' : '')."'>
          here</a> {$lang['comment_delete_sure']}");
    }
    ($res = sql_query("SELECT $locale FROM comments WHERE id=".sqlesc($commentid))) || sqlerr(__FILE__, __LINE__);
    $arr = $res->fetch_assoc();
    $id = 0;
    if ($arr) {
        $id = $arr[$locale];
    }
    sql_query("DELETE FROM comments WHERE id=".sqlesc($commentid)) || sqlerr(__FILE__, __LINE__);
    if ($id && $mysqli->affected_rows > 0) {
        sql_query("UPDATE $table_type SET comments = comments - 1 WHERE id = ".sqlesc($id));
    }
    if ($TRINITY20['seedbonus_on'] == 1) {
        if ($TRINITY20['karma'] && isset($CURUSER['seedbonus'])) {
            sql_query("UPDATE users SET seedbonus = seedbonus-3.0 WHERE id =".sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
        }
        $arr['comments'] ??= 0;
        $update['comments'] = ($arr['comments'] - 1);
        $cache->update_row($cache_keys['torrent_details'].$id, [
            'comments' => $update['comments'],
        ], 0);
        $update['seedbonus'] = ($CURUSER['seedbonus'] - 3);
        $cache->update_row($cache_keys['user_stats'].$CURUSER["id"], [
            'seedbonus' => $update['seedbonus'],
        ], $TRINITY20['expires']['u_stats']);
        $cache->update_row($cache_keys['user_statss'].$CURUSER["id"], [
            'seedbonus' => $update['seedbonus'],
        ], $TRINITY20['expires']['user_stats']);
        //===end
    }
    header("Refresh: 0; url=$locale_link.php?id=$tid$extra_link");
    die;
} elseif ($action == "vieworiginal") {
    if ($CURUSER['class'] < UC_STAFF) {
        stderr("{$lang['comment_error']}", "{$lang['comment_denied']}");
    }
    $commentid = (isset($_GET['cid']) ? (int)$_GET['cid'] : 0);
    if (!is_valid_id($commentid)) {
        stderr("{$lang['comment_error']}", "{$lang['comment_invalid_id']}");
    }
    ($res = sql_query("SELECT c.*, t.$name FROM comments AS c LEFT JOIN $table_type AS t ON c.$locale = t.id WHERE c.id=".sqlesc($commentid))) || sqlerr(__FILE__,
        __LINE__);
    $arr = $res->fetch_assoc();
    if (!$arr) {
        stderr("{$lang['comment_error']}", "{$lang['comment_invalid_id']} $commentid.");
    }
    $HTMLOUT = '';
    $HTMLOUT .= "<h1>{$lang['comment_original_content']}#$commentid</h1>
      <table width='500' border='1' cellspacing='0' cellpadding='5'>
      <tr><td class='comment'>
      ".htmlspecialchars($arr["ori_text"])."
      </td></tr></table>";
    $returnto = (isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : 0);
    if ($returnto) {
        $HTMLOUT .= "<p>(<a href='$returnto'>back</a>)</p>\n";
    }
    echo stdhead("{$lang['comment_original']}", true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
    die;
} else {
    stderr("{$lang['comment_error']}", "{$lang['comment_unknown']}");
}
die;
?>
