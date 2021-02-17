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
define('IN_TRINITY20_CRON', true);
if (!isset($argv) || !is_array($argv) || count($argv) != 2 || !preg_match('/^[0-9a-fA-F]{32}$/i', $argv[1])) {
    exit('Go away!');
}
require_once __DIR__."/include/cronclean.php";
?>
