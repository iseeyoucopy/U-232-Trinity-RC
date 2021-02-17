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
// Achievements mod by MelvinMeow
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
require_once (INCL_DIR . 'pager_functions.php');
require_once (CLASS_DIR . 'page_verify.php');
dbconn();
loggedinorreturn();
$newpage = new page_verify();
$newpage->create('takecounts');
$lang = array_merge(load_language('global') , load_language('achievement_history'));

if ($TRINITY20['achieve_sys_on'] == false) {
stderr($lang['achievement_history_err'], $lang['achievement_history_off']);
exit();
}

$HTMLOUT = "";
$id = (int)$_GET["id"];

if (!is_valid_id($id)) {
    stderr($lang['achievement_history_err'], $lang['achievement_history_err1']);
}
($res = sql_query("SELECT users.id, users.username, usersachiev.achpoints, usersachiev.spentpoints FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE users.id = " . sqlesc($id))) || sqlerr(__FILE__, __LINE__);
$arr = $res->fetch_assoc();
if (!$arr) {
    stderr($lang['achievement_history_err'], $lang['achievement_history_err1']);
}
$achpoints = (int)$arr['achpoints'];
$spentpoints = (int)$arr['spentpoints'];
($res = sql_query("SELECT COUNT(*) FROM achievements WHERE userid =" . sqlesc($id))) || sqlerr(__FILE__, __LINE__);
$row = $res->fetch_row();
$count = $row[0];
$perpage = 15;
if (!$count) {
    stderr($lang['achievement_history_no'],
        "{$lang['achievement_history_err2']}<a class='altlink' href='userdetails.php?id=".(int)$arr['id']."'>".htmlsafechars($arr['username'])."</a>{$lang['achievement_history_err3']}");
}
$pager = pager($perpage, $count, "?id=$id&amp;");
if ($id == $CURUSER['id']) {
	require_once (BLOCK_DIR . 'achievements/ach_history_nav.php');
}
require_once (BLOCK_DIR . 'achievements/ach_history_info.php');
if ($count > $perpage) {
    $HTMLOUT .= $pager['pagertop'];
}
require_once (BLOCK_DIR . 'achievements/ach_history.php');
if ($count > $perpage) {
    $HTMLOUT .= $pager['pagerbottom'];
}
echo stdhead($lang['achievement_history_stdhead']) . $HTMLOUT . stdfoot();
die;
?>
