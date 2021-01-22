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
//By Froggaard
require_once (__DIR__ . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'bittorrent.php');
require_once (INCL_DIR . 'user_functions.php');
dbconn();
$lang = array_merge(load_language('global'), load_language('ajax_thumbsup'));
$HTML = '';
$id = (int)$_REQUEST['id'];
$thumbs_query = sql_query("SELECT id, type, torrentid, userid FROM thumbsup WHERE torrentid = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$wtf = $thumbs_query->num_rows;
$res = sql_query("SELECT id, type, torrentid, userid FROM thumbsup WHERE userid = " . sqlesc($CURUSER['id']) . " AND torrentid = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$thumbsup = $res->num_rows;
if ($thumbsup == 0) {
    sql_query("INSERT INTO thumbsup (userid, torrentid) VALUES (" . sqlesc($CURUSER['id']) . ", " . sqlesc($id) . ")") or sqlerr(__FILE__, __LINE__);
    $cache->delete('thumbs_up:' . $id);
    $HTML.= "<a class='button'><i class='far fa-thumbs-up'></i>(" . ($wtf + 1) . ")</a>";
} else $HTML.= "<a class='button'><i class='far fa-thumbs-up'></i></a>({$wtf})";
echo $HTML;
?>
