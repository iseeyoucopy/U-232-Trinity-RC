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
require_once(INCL_DIR.'emoticons.php');
require_once(INCL_DIR.'html_functions.php');
dbconn(false);
loggedinorreturn();
$lang = load_language('global');
$HTMLOUT = stdhead();
$HTMLOUT .= begin_main_frame();
$HTMLOUT .= insert_smilies_frame();
$HTMLOUT .= end_main_frame();
$HTMLOUT .= stdfoot();
echo $HTMLOUT;
?>
