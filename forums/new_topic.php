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
/****
* Bleach Forums 
* Rev u-232v5
* Credits - Retro-Alex2005-Putyn-pdq-sir_snugglebunny-Bigjoos
* Bigjoos 2015
******
*/
if (!defined('IN_INSTALLER09_FORUM')) {
    $HTMLOUT = '';
    $HTMLOUT.= '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <head>
        <meta charset="'.charset().'" />
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    echo $HTMLOUT;
    exit();
}
// -------- Action: New topic
  $forumid = (int)$_GET["forumid"];
    if (!is_valid_id($forumid))
        stderr('Error', 'Invalid ID!');
    //$HTMLOUT .= begin_main_frame();
    if ($TRINITY20['forums_online'] == 0)
    $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
    $HTMLOUT .= insert_compose_frame($forumid, true, false, true);
    //$HTMLOUT .= end_main_frame();
    echo stdhead("New Topic", true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
    exit();
?>
