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
require_once (INCL_DIR . 'bbcode_functions.php');
dbconn(true);
loggedinorreturn();
echo "";
//Check for Auto Shouts and cache it
//$cache->delete($keys['auto_shoutbox']);
//== cache the data
if (($shouts = $cache->get($keys['auto_shoutbox'])) === false) {
    ($res = sql_query("SELECT text FROM shoutbox WHERE staff_shout ='no' AND autoshout ='yes' ORDER BY id DESC LIMIT 10")) || sqlerr(__FILE__, __LINE__);
    while ($shout = $res->fetch_assoc()) $shouts[] = $shout;
    $cache->set($keys['auto_shoutbox'], $shouts, $TRINITY20['expires']['shoutbox']);
}

//Output the shouts
if (is_array($shouts)) {
	foreach ($shouts as $arr) {
		echo format_comment($arr["text"])."&nbsp;&nbsp;&nbsp;";
	}
}

?>
