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
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once(INCL_DIR . 'user_functions.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('createlink'));
if ($CURUSER['class'] < UC_STAFF) {
    stderr($lang['createlink_no_permision'], $lang['createlink_system_file']);
}
$id = (isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int) $_POST['id'] : null));
 if (!$id || !is_valid_id($id)) {
     stderr("{$lang['gl_error']}", "{$lang['gl_bad_id']}");
 }

$action = isset($_GET['action']) ? htmlsafechars($_GET['action']) : '';
($res = sql_query("SELECT hash1, username, passhash FROM users WHERE id = " . sqlesc($id) . " AND class >= " . UC_STAFF)) || sqlerr(__FILE__, __LINE__);
$arr = $res->fetch_assoc();
$hash1 = md5($arr['username'] . TIME_NOW . $arr['passhash']);
$hash2 = md5($hash1 . TIME_NOW . $arr['username']);
$hash3 = md5($hash1 . $hash2 . $arr['passhash']);
$hash1.= $hash2 . $hash3;
if ($action == 'reset') {
    $sure = isset($_GET['sure']) ? (int) ($_GET['sure']) : 0;
    if ($sure != '1') {
        stderr($lang['createlink_sanity_check'], "{$lang['createlink_you_are_about_to_reset_your_login_link']} <a href='createlink.php?action=reset&amp;id=$id&amp;sure=1'>{$lang['createlink_here']}</a> {$lang['createlink_if_you_are_sure']}.");
    }
    sql_query("UPDATE users SET hash1 = " . sqlesc($hash1) . " WHERE id = " . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    $cache->update_row('user' . $id, [
        'hash1' => $hash1
    ], $TRINITY20['expires']['user_cache']);
    $cache->update_row($keys['my_userid'] . $id, [
        'hash1' => $hash1
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row('hash1_' . $id, [
        'hash1' => $hash1
    ], $TRINITY20['expires']['user_hash']);
    header("Refresh: 1; url={$TRINITY20['baseurl']}/userdetails.php?id=$id");
    stderr($lang['createlink_success'], $lang['createlink_your_login_link_was_reset_successfully']);
} elseif ($arr['hash1'] === null || $arr['hash1'] === '') {
    sql_query("UPDATE users SET hash1 = " . sqlesc($hash1) . " WHERE id = " . sqlesc($id)) || sqlerr(__FILE__, __LINE__);
    header("Refresh: 2; url={$TRINITY20['baseurl']}/userdetails.php?id=$id");
    $cache->update_row('user' . $id, [
        'hash1' => $hash1
    ], $TRINITY20['expires']['user_cache']);
    $cache->update_row($keys['my_userid'] . $id, [
        'hash1' => $hash1
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row('hash1_' . $id, [
        'hash1' => $hash1
    ], $TRINITY20['expires']['user_hash']);
    stderr('Success', $lang['createlink_your_login_link_was_created_successfully']);
} else {
    header("Refresh: 2; url={$TRINITY20['baseurl']}/userdetails.php?id=$id");
    stderr($lang['gl_error'], $lang['createlink_you_have_already_created_your_login_link']);
}
?>
