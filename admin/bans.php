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
if (!defined('IN_TRINITY20_ADMIN')) {
    $HTMLOUT = '';
    $HTMLOUT .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br>You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'pager_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language('ad_bans'));
$remove = isset($_GET['remove']) ? (int)$_GET['remove'] : 0;
if ($remove > 0) {
    ($banned = sql_query('SELECT first, last FROM bans WHERE id = '.sqlesc($remove))) || sqlerr(__FILE__, __LINE__);
    if (!$banned->num_rows) {
        stderr($lang['stderr_error'], $lang['stderr_error1']);
    }
    $ban = $banned->fetch_assoc();
    $first = 0 + $ban['first'];
    $last = 0 + $ban['last'];
    for ($i = $first; $i <= $last; $i++) {
        $ip = long2ip($i);
        $cache->delete($cache_keys['bans'].$ip);
    }
    if (is_valid_id($remove)) {
        sql_query("DELETE FROM bans WHERE id=".sqlesc($remove)) || sqlerr(__FILE__, __LINE__);
        $removed = sprintf($lang['text_banremoved'], $remove);
        write_log("{$removed}".$CURUSER['id']." (".$CURUSER['username'].")");
    }
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && $CURUSER['class'] == UC_MAX) {
    $first = trim($_POST["first"]);
    $last = trim($_POST["last"]);
    $comment = htmlsafechars(trim($_POST["comment"]));
    if (!$first || !$last || !$comment) {
        stderr("{$lang['stderr_error']}", "{$lang['text_missing']}");
    }
    $first = ip2long($first);
    $last = ip2long($last);
    if ($first == -1 || $first === false || $last == -1 || $last === false) {
        stderr("{$lang['stderr_error']}", "{$lang['text_badip']}");
    }
    $added = TIME_NOW;
    for ($i = $first; $i <= $last; $i++) {
        $cache->delete($cache_keys['bans'].long2ip($i));
    }
    sql_query("INSERT INTO bans (added, addedby, first, last, comment) VALUES($added, ".sqlesc($CURUSER['id']).", ".sqlesc($first).", ".sqlesc($last).", ".sqlesc($comment).")") || sqlerr(__FILE__,
        __LINE__);
    header("Location: {$TRINITY20['baseurl']}/staffpanel.php?tool=bans");
    die;
}
($bc = sql_query('SELECT COUNT(*) FROM bans')) || sqlerr(__FILE__, __LINE__);
$bcount = $bc->fetch_row();
$count = $bcount[0];
$perpage = 15;
$pager = pager($perpage, $count, 'staffpanel.php?tool=bans&amp;');
($res = sql_query("SELECT b.*, u.username FROM bans b LEFT JOIN users u on b.addedby = u.id ORDER BY added DESC {$pager['limit']}")) || sqlerr(__FILE__,
    __LINE__);
$HTMLOUT = '';
if ($res->num_rows == 0) {
    $HTMLOUT .= "<div class='callout alert-callout-border alert'>
        <strong>{$lang['text_nothing']}</strong>
    </div>";
} else {
    $HTMLOUT .= "<div class='card'>
    <div class='card-divider'>{$lang['text_current']}</div>
    <div class='card-section'>
    <table class='stack striped'>
    <thead>
    <tr>
        <td>{$lang['header_firstip']}</td>
        <td>{$lang['header_lastip']}</td>
        <td>{$lang['header_by']}</td>
        <td>{$lang['header_comment']}</td>
        <td>{$lang['header_added']}</td>
        <td>{$lang['header_remove']}</td>
      </tr>
      </thead>";
    while ($arr = $res->fetch_assoc()) {
        $arr["first"] = long2ip($arr["first"]);
        $arr["last"] = long2ip($arr["last"]);
        $HTMLOUT .= "<tbody><tr>
          <td>".htmlsafechars($arr['first'])."</td>
          <td>".htmlsafechars($arr['last'])."</td>
          <td><a href='userdetails.php?id=".(int)$arr['addedby']."'>".htmlsafechars($arr['username'])."</a></td>
          <td>".htmlsafechars($arr['comment'], ENT_QUOTES)."</td>
          <td><a href='staffpanel.php?tool=bans&amp;remove=".(int)$arr['id']."'>{$lang['text_remove']}</a></td>
          <td>".get_date($arr['added'], '')."</td>
         </tr>
         </tbody>";
    }
    $HTMLOUT .= "</table>";
    if ($count > $perpage) {
        $HTMLOUT .= $pager['pagerbottom'];
    }
    $HTMLOUT .= "</div></div>";
}
if ($CURUSER['class'] == UC_MAX) {
    $HTMLOUT .= "<div class='card'>
        <div class='card-divider'>{$lang['text_addban']}</div>
        <div class='card-section'>
            <form method='post' action='staffpanel.php?tool=bans'>
                <div class='input-group'>
                    <span class='input-group-label'>{$lang['table_firstip']}</span>
                    <input class='input-group-field' type='text' name='first'>
                </div>
                <div class='input-group'>
                    <span class='input-group-label'>{$lang['table_lastip']}</span>
                    <input class='input-group-field' type='text' name='last'>
                </div>
                <div class='input-group'>
                    <span class='input-group-label'>{$lang['table_comment']}</span>
                    <input class='input-group-field' type='text' name='comment'>
                </div>
                <input class='button float-center' type='submit' name='okay' value='{$lang['btn_add']}'>
            </form>
        </div>
    </div>";
}
echo stdhead("{$lang['stdhead_adduser']}").$HTMLOUT.stdfoot();
?>
