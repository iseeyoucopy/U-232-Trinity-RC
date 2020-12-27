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
dbconn();
/*if(!($CURUSER)) {
die('Error You already logged out you muppet');
exit();
}*/
global $CURUSER;
$hash_please = (isset($_GET['hash_please']) && htmlsafechars($_GET['hash_please']));
$salty_username = isset($CURUSER['username']) ? "{$CURUSER['username']}" : '';
$salty = md5("Th15T3xtis5add3dto66uddy6he@water...". $salty_username . "");
if (empty($hash_please)) die("No Hash your up to no good MOFO");
if ($hash_please != $salty) die("Unsecure Logout - Hash mis-match please contact site admin");
logoutcookie();
Header("Location: {$INSTALLER09['baseurl']}/");
?>
