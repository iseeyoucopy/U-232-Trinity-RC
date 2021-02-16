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
require_once (CLASS_DIR . 'page_verify.php');
require_once (INCL_DIR . 'user_functions.php');
dbconn();
loggedinorreturn();
$newpage = new page_verify();
$newpage->check('takecounts');
($res = sql_query("SELECT COUNT(*) FROM users WHERE enabled = 'yes' AND invitedby =" . sqlesc($CURUSER['id']))) || sqlerr(__FILE__, __LINE__);
$arr3 = $res->fetch_row();
$invitedcount = (int)$arr3['0'];
sql_query("UPDATE usersachiev SET invited=" . sqlesc($invitedcount) . " WHERE id=" . sqlesc($CURUSER['id'])) || sqlerr(__FILE__, __LINE__);
header("Location: {$TRINITY20['baseurl']}/index.php");
?>
