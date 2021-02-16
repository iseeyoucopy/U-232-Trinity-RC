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
 //Theme Reset
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'));
global $cache, $TRINITY20;
$sid = 1;
if ($sid > 0 && $sid != $CURUSER['id'])
    sql_query('UPDATE users SET stylesheet=' . sqlesc($sid) . ' WHERE id = ' . sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
    $cache->update_row($keys['my_userid'] . $CURUSER['id'], [
        'stylesheet' => $sid
    ], $TRINITY20['expires']['curuser']);
    $cache->update_row('user' . $CURUSER['id'], [
        'stylesheet' => $sid
    ], $TRINITY20['expires']['user_cache']);
header("Location: {$TRINITY20['baseurl']}/index.php");
?>
