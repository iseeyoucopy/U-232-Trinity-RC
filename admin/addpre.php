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
	<div style='font-size:33px;color:white;background-color:red;text-align:center;'>Incorrect access<br />You cannot access this file directly.</div>
	</body></html>";
    echo $HTMLOUT;
    exit();
}
require_once(INCL_DIR.'user_functions.php');
require_once(CLASS_DIR.'class_check.php');
class_check(UC_STAFF);
$lang = array_merge($lang, load_language('ad_addpre'));
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tid = trim(htmlsafechars($_POST["id"]));
    $time = trim(htmlsafechars($_POST["time"]));
    if (!$tid || !$time) {
        stderr("{$lang['text_error']}", "{$lang['text_please']}");
    }
    ($res = sql_query("SELECT * FROM torrents WHERE id=".sqlesc($tid))) || sqlerr(__FILE__, __LINE__);
    if ($res->num_rows != 1) {
        stderr("{$lang['text_error']}", "{$lang['text_bad']}");
    }
    $arr = $res->fetch_assoc();
    $name = $arr['name'];
    ($res = sql_query("INSERT INTO releases (releasename, time, releasetime, section) VALUES (".sqlesc($name).", ".sqlesc($time).", ".sqlesc($time).", 'Site add')")) || sqlerr(__FILE__,
        __LINE__);

    $cache->delete('torrent_pretime_'.$tid);
    if ($mysqli->affected_rows != 1) {
        stderr("{$lang['text_error']}", "{$lang['text_unable']}");
    }
    stderr("{$lang['stderr_success']}", "{$lang['text_success']}");
}
$HTMLOUT = "<div class='card'>
  <div class='card-divider'>{$lang['text_addpre']}</div>
  <div class='card-section'>
    <form method='post' action='staffpanel.php?tool=addpre&amp;action=addpre'>
      <div class='input-group'>
        <span class='input-group-label'>{$lang['table_torrentid']}</span>
        <input class='input-group-field' type='text' name='id'>
      </div>
      <div class='input-group'>
        <span class='input-group-label'>{$lang['table_pretime']}</span>
        <input class='input-group-field' type='text' name='time'>
      </div>
          <input type='submit' class='button float-center' value='{$lang['btn_add']}'>
      </div>
    </form>
  </div>
</div>";
echo stdhead("{$lang['stdhead_addpre']}").$HTMLOUT.stdfoot();