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
if (!defined('IN_TRINITY20_CRON')) {
    $HTMLOUT = '';
    $HTMLOUT .= "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\"
		\"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
		<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<title>Error!</title>
		</head>
		<body>
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br>You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once __DIR__."/config.php";

$mysqli = new mysqli($TRINITY20['mysql_host'], $TRINITY20['mysql_user'], $TRINITY20['mysql_pass'], $TRINITY20['mysql_db']);
if ($mysqli->connect_errno !== 0) {
    echo "Connection Problems".PHP_EOL;
    echo "Sorry, U-232 was unable to connect to the database. This may be caused by the server being busy. Please try again later. ".$mysqli->connect_error;
    exit();
}
$now = TIME_NOW;
$sql = sql_query("SELECT * FROM cleanup WHERE clean_cron_key = '{$argv[1]}' LIMIT 0,1");
$row = $sql->fetch_assoc();
if ($row['clean_id']) {
    $next_clean = (int)($now + ($row['clean_increment'] ? $row['clean_increment'] : 15 * 60));
    // don't really need to update if its cron. no point as yet.
    sql_query("UPDATE cleanup SET clean_time = $next_clean WHERE clean_id = {$row['clean_id']}");
    if (file_exists(CLEAN_DIR.''.$row['clean_file'])) {
        require_once(CLEAN_DIR.''.$row['clean_file']);
        if (function_exists('docleanup')) {
            register_shutdown_function('docleanup', $row);
        }
    }
}
function sqlesc($x)
{
    global $mysqli;
    return $mysqli->real_escape_string($x);
}

?>
