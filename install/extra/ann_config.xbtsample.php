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
$agent = $_SERVER["HTTP_USER_AGENT"];
$detectedclient = $_SERVER["HTTP_USER_AGENT"];
define('INCL_DIR', __DIR__ . DIRECTORY_SEPARATOR);
define('ROOT_DIR', realpath(INCL_DIR . '..' . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR);
define('CACHE_DIR', ROOT_DIR . 'cache' . DIRECTORY_SEPARATOR);
define('CLASS_DIR', INCL_DIR . 'class' . DIRECTORY_SEPARATOR);
define('XBT_TRACKER', true);
$TRINITY['cache'] = ROOT_DIR . 'cache';
require_once (CLASS_DIR . 'class_cache.php');
require_once (CLASS_DIR . 'class_bt_options.php');
$TRINITY['pic_base_url'] = "./pic/";
require_once (CACHE_DIR . 'class_config.php');
date_default_timezone_set('Europe/London');
$cache = NEW CACHE();
//$cache->MemcachePrefix = 'u232_3_';
define('TIME_NOW', time());
define('ANN_SQL_DEBUG', 1);
define('ANN_SQL_LOGGING', 0);
define('ANN_IP_LOGGING', 1);
$TRINITY['announce_interval'] = 60 * 30;
$TRINITY['min_interval'] = 60 * 15;
$TRINITY['connectable_check'] = 1;
$TRINITY['wait_times'] = 0;
$TRINITY['max_slots'] = 0;
$TRINITY['ann_sql_error_log'] = 'sqlerr_logs/ann_sql_err_' . date('M_D_Y') . '.log';
$TRINITY['ann_sql_log'] = 'sqlerr_logs/ann_sql_query_' . date('M_D_Y') . '.log';
$TRINITY['crazy_hour'] = false; //== Off for XBT
$TRINITY['happy_hour'] = false; //== Off for XBT
$TRINITY['ratio_free'] = false;
// DB setup
$TRINITY['baseurl'] = '#baseurl';
$TRINITY['mysql_host'] = "#mysql_host";
$TRINITY['mysql_user'] = "#mysql_user";
$TRINITY['mysql_pass'] = "#mysql_pass";
$TRINITY['mysql_db'] = "#mysql_db";
$TRINITY['expires']['max_slots'] = 300; // 300 = 5 minutes
$TRINITY['expires']['user_passkey'] = 3600 * 8; // 8 hours
$TRINITY['expires']['contribution'] = 3 * 86400; // 3 * 86400 3 days
$TRINITY['expires']['happyhour'] = 43200; // 43200 1/2 day
$TRINITY['expires']['sitepot'] = 86400; // 86400 1 day
$TRINITY['expires']['torrent_announce'] = 86400; // 86400 1 day
$TRINITY['expires']['torrent_details'] = 30 * 86400; // = 30 days

?>
