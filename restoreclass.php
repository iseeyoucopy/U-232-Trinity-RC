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
dbconn(false);
loggedinorreturn();
sql_query("UPDATE users SET override_class='255' WHERE id = ".sqlesc($CURUSER['id']));
$cache->update_row($keys['my_userid'].$CURUSER['id'], [
    'override_class' => 255,
], $TRINITY20['expires']['curuser']);
$cache->update_row($keys['user'].$CURUSER['id'], [
    'override_class' => 255,
], $TRINITY20['expires']['user_cache']);
header("Location: {$TRINITY20['baseurl']}/index.php");
die();
