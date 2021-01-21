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
dbconn(false);
loggedinorreturn();
$lang = array_merge(load_language('global') , load_language('viewnfo'));
/*
$stdhead = array(
    /** include css **/
/*
    'css' => array(
        'viewnfo'
    )
);
*/
$id = (int) $_GET["id"];
if ($CURUSER['class'] < UC_POWER_USER || !is_valid_id($id))
die;
$r = sql_query("SELECT name, nfo FROM torrents WHERE id=".sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$a = $r->fetch_assoc() or die("{$lang['text_puke']}");
$HTMLOUT = '';
$HTMLOUT .= "
<div class='row'>
<div  class='col-md-12 text-center'><h2>{$lang['text_nfofor']}<a href='{$TRINITY20['baseurl']}/details.php?id=$id'>".htmlsafechars($a['name'])."</a></h2></div>
<div  class='col-md-12 text-center'><h2>{$lang['text_forbest']}<a href='ftp://{$_SERVER['HTTP_HOST']}/misc/linedraw.ttf'>{$lang['text_linedraw']}</a>{$lang['text_font']}</h2></div>
<div class='row'><div class='col-md-12'>
<table class='table table-bordered'>
<tr>
<td class='text'>\n";
$HTMLOUT .= " <pre>" . format_urls(htmlsafechars($a['nfo'])) . "</pre>\n";
$HTMLOUT .= " </td>
</tr>
</table>\n";
$HTMLOUT .= " </div>
</div></div>";
// , true, $stdhead
echo stdhead($lang['text_stdhead']) . $HTMLOUT . stdfoot(); 
?>

