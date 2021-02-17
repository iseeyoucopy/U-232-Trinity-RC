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
require_once(__DIR__.DIRECTORY_SEPARATOR.'include'.DIRECTORY_SEPARATOR.'bittorrent.php');
require_once(INCL_DIR.'user_functions.php');
dbconn();
loggedinorreturn();
$lang = array_merge(load_language('global'), load_language('chat'));

$HTMLOUT = '';
$HTMLOUT .= "<div class='card'><iframe src='/chat/' class='chat-table' name='custom_ajax_chat'></iframe></div>";

echo stdhead("{$lang['chat_chat']}").$HTMLOUT.stdfoot();
?>
