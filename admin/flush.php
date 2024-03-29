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
if (!defined('IN_TRINITY20_ADMIN')) {
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
require_once(INCL_DIR.'user_functions.php');
require_once(INCL_DIR.'bbcode_functions.php');
require_once(CLASS_DIR.'class_check.php');
$class = get_access(basename($_SERVER['REQUEST_URI']));
class_check($class);
$lang = array_merge($lang, load_language("ad_flush"));
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!is_valid_id($id)) {
    stderr($lang['flush_stderror'], $lang['flush_invalid']);
}
if ($CURUSER['class'] >= UC_STAFF) {
    $dt = TIME_NOW;
    ($res = sql_query("SELECT username FROM users WHERE id=".sqlesc($id))) || sqlerr(__FILE__, __LINE__);
    $arr = $res->fetch_assoc();
    $username = htmlsafechars($arr['username']);
    sql_query("DELETE FROM peers WHERE userid=".sqlesc($id));
    $effected = $mysqli->affected_rows;
    //=== write to log
    write_log($lang['flush_log1'].$username.$lang['flush_log2'].get_date($dt, 'LONG', 0, 1).$lang['flush_log3'].(int)$effected.$lang['flush_log4']);
    //write_log("User " . $username . " just flushed torrents at " . get_date($dt, 'LONG',0,1) . ". $effected torrents where sucessfully cleaned.");
    header("Refresh: 3; url=index.php");
    stderr($lang['flush_success'], "$effected {$lang['flush_success2']}".($effected ? 's' : '').$lang['flush_success3']);
} else {
    stderr($lang['flush_fail'], $lang['flush_fail2']);
}
?>
