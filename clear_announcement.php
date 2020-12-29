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
dbconn(false);
loggedinorreturn();
$query1 = sprintf('UPDATE users SET curr_ann_id = 0, curr_ann_last_check = \'0\' ' . 'WHERE id = %s AND curr_ann_id != 0', sqlesc($CURUSER['id']));
sql_query($query1);
$cache->update_row('user' . $CURUSER['id'], [
    'curr_ann_id' => 0,
    'curr_ann_last_check' => 0
], $TRINITY20['expires']['user_cache']);
$cache->update_row('MyUser_' . $CURUSER['id'], [
    'curr_ann_id' => 0,
    'curr_ann_last_check' => 0
], $TRINITY20['expires']['curuser']);
//$status = 2;
header("Location: {$TRINITY20['baseurl']}/index.php");
?>
