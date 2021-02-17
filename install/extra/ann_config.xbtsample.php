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
error_reporting(E_ALL);
////////////////// GLOBAL VARIABLES /////////////////////////////////////
//== Php poop
$finished = $plist = $corupptthis = '';
if (!empty($_SERVER["HTTP_USER_AGENT"])) {
    $agent = $_SERVER["HTTP_USER_AGENT"];
}
if (!empty($_SERVER["HTTP_USER_AGENT"])) {
    $detectedclient = $_SERVER["HTTP_USER_AGENT"];
}
define('INCL_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(INCL_DIR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
define('CACHE_DIR', ROOT_DIR . 'cache' . DIRECTORY_SEPARATOR);
define('CLASS_DIR', INCL_DIR . 'class' . DIRECTORY_SEPARATOR);
define('VENDOR_DIR', ROOT_DIR . 'vendor' . DIRECTORY_SEPARATOR);
define('XBT_TRACKER', true);
$TRINITY20['cache'] = ROOT_DIR . 'cache';
require_once (CLASS_DIR . 'class_bt_options.php');
$TRINITY20['pic_base_url'] = "./pic/";
require_once (CACHE_DIR . 'class_config.php');
date_default_timezone_set('Europe/London');
//==Start Cache
use U232\Cache;
require_once (VENDOR_DIR . 'autoload.php');
require_once (INCL_DIR . 'cache_config.php');
require_once (CLASS_DIR . 'class_cacheM.php');
global $TRINITY20;
define('TIME_NOW', time());
define('ANN_SQL_DEBUG', 1);
define('ANN_SQL_LOGGING', 0);
define('ANN_IP_LOGGING', 1);
$TRINITY20['announce_interval'] = 60 * 30;
$TRINITY20['min_interval'] = 60 * 15;
$TRINITY20['connectable_check'] = 1;
$TRINITY20['wait_times'] = 0;
$TRINITY20['max_slots'] = 0;
$TRINITY20['ann_sql_error_log'] = 'sqlerr_logs/ann_sql_err_' . date('M_D_Y') . '.log';
$TRINITY20['ann_sql_log'] = 'sqlerr_logs/ann_sql_query_' . date('M_D_Y') . '.log';
$TRINITY20['crazy_hour'] = false; //== Off for XBT
$TRINITY20['happy_hour'] = false; //== Off for XBT
$TRINITY20['ratio_free'] = false;
// DB setup
$TRINITY20['baseurl'] = '#baseurl';
$TRINITY20['mysql_host'] = "#mysql_host";
$TRINITY20['mysql_user'] = "#mysql_user";
$TRINITY20['mysql_pass'] = "#mysql_pass";
$TRINITY20['mysql_db'] = "#mysql_db";
$TRINITY20['expires']['max_slots'] = 300; // 300 = 5 minutes
$TRINITY20['expires']['user_passkey'] = 3600 * 8; // 8 hours
$TRINITY20['expires']['contribution'] = 3 * 86400; // 3 * 86400 3 days
$TRINITY20['expires']['happyhour'] = 43200; // 43200 1/2 day
$TRINITY20['expires']['sitepot'] = 86400; // 86400 1 day
$TRINITY20['expires']['torrent_announce'] = 86400; // 86400 1 day
$TRINITY20['expires']['torrent_details'] = 30 * 86400; // = 30 days

?>
