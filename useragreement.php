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
require_once (INCL_DIR . 'html_functions.php');
dbconn();
$lang = array_merge(load_language('global') , load_language('useragreement'));
$HTMLOUT = '';
$HTMLOUT.= "<h2 class='text-center'>" . $TRINITY20['site_name'] . "&nbsp;{$lang['frame_usragrmnt']}</h2>";
$HTMLOUT.= "<div class='card'>{$lang['text_usragrmnt']}</div><br>";
echo stdhead("{$lang['stdhead_usragrmnt']}") . $HTMLOUT . stdfoot();
?>
