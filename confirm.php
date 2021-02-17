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
require_once (INCL_DIR . 'password_functions.php');
global $CURUSER;
if (!$CURUSER) {
    get_template();
}
$lang = array_merge(load_language('global') , load_language('confirm'));
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$confirm = $_GET['secret'] ?? '';
if (!is_valid_id($id)) {
    stderr("{$lang['confirm_user_error']}", "{$lang['confirm_invalid_id']}");
}
//if (!preg_match("/^(?:[\d\w]){32}$/", $md5)) {
    //stderr("{$lang['confirm_user_error']}", "{$lang['confirm_invalid_key']}");
//}
dbconn();
$res = sql_query("SELECT hash3, email, added, status FROM users WHERE id =" . sqlesc($id));
$row = $res->fetch_assoc();
if (!$row) {
    stderr("{$lang['confirm_user_error']}", "{$lang['confirm_invalid_id']}");
}
if ($row['status'] != 'pending') {
    header("Refresh: 0; url={$TRINITY20['baseurl']}/ok.php?type=confirmed");
    exit();
}
if (!password_verify($row['email'].$row['added'], $confirm)) {
    stderr("{$lang['confirm_user_error']}", "{$lang['confirm_cannot_confirm']}");
}
sql_query("UPDATE users SET status='confirmed', editsecret='' WHERE id=" . sqlesc($id) . " AND status='pending'");
$cache->update_row($keys['my_userid'] . $id, [
    'status' => 'confirmed'
], $TRINITY20['expires']['curuser']);
$cache->update_row('user' . $id, [
    'status' => 'confirmed'
], $TRINITY20['expires']['user_cache']);
if (!$mysqli->affected_rows) {
    stderr("{$lang['confirm_user_error']}", "{$lang['confirm_cannot_confirm']}");
}
$passh = h_cook($row['hash3'], $_SERVER["REMOTE_ADDR"], $id);
logincookie($id, $passh);
header("Refresh: 0; url={$TRINITY20['baseurl']}/ok.php?type=confirm");
?>
