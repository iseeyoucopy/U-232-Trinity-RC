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
dbconn(false);
loggedinorreturn();
sql_query("UPDATE users SET override_class='255' WHERE id = " . sqlesc($CURUSER['id']));
$cache->update_row('MyUser_' . $CURUSER['id'], [
    'override_class' => 255
], $INSTALLER09['expires']['curuser']);
$cache->update_row('user' . $CURUSER['id'], [
    'override_class' => 255
], $INSTALLER09['expires']['user_cache']);
header("Location: {$INSTALLER09['baseurl']}/index.php");
die();
