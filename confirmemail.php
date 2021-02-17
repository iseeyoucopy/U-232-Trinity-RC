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
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
$lang = array_merge(load_language('global') , load_language('confirmemail'));
if (!isset($_GET['uid']) || !isset($_GET['key']) || !isset($_GET['email'])) {
    stderr("{$lang['confirmmail_user_error']}", "{$lang['confirmmail_idiot']}");
}
//if (!preg_match("/^(?:[\d\w]){32}$/", $_GET['key'])) {
//    stderr("{$lang['confirmmail_user_error']}", "{$lang['confirmmail_no_key']}");
//}
if (!preg_match("/^(?:\d){1,}$/", $_GET['uid'])) {
    stderr("{$lang['confirmmail_user-error']}", "{$lang['confirmmail_no_id']}");
}
$id = (int) $_GET['uid'];
$confirm = $_GET['key'];
$email = urldecode($_GET['email']);
if (!validemail($email)) {
    stderr("{$lang['confirmmail_user_error']}", "{$lang['confirmmail_false_email']}");
}
dbconn();
$res = sql_query("SELECT editsecret, added FROM users WHERE id =" . sqlesc($id));
$row = $res->fetch_assoc();
if (!$row) {
    stderr("{$lang['confirmmail_user_error']}", "{$lang['confirmmail_not_complete']}");
}

if (!password_verify($email.$row['added'], $confirm)) {
    stderr("{$lang['confirmmail_user_error']}", "{$lang['confirmmail_not_complete']}");
}
sql_query("UPDATE users SET editsecret='', email=" . sqlesc($email) . " WHERE id=" . sqlesc($id) . " AND editsecret=" . sqlesc($row["editsecret"]));
$cache->update_row($keys['my_userid'] . $id, [
    'editsecret' => '',
    'email' => $email
], $TRINITY20['expires']['curuser']);
$cache->update_row('user' . $id, [
    'editsecret' => '',
    'email' => $email
], $TRINITY20['expires']['user_cache']);
if (!$mysqli->affected_rows) {
    stderr("{$lang['confirmmail_user_error']}", "{$lang['confirmmail_not_complete']}");
}
header("Refresh: 0; url={$TRINITY20['baseurl']}/usercp.php?action=security&emailch=1");
?>
