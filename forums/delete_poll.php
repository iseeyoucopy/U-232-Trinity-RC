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
if (!defined('IN_TRINITY20_FORUM')) {
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
    exit();
}
    $pollid = (int)$_GET["pollid"];
    if (!is_valid_id($pollid))
        stderr("Error", "Invalid ID!");
    $res = sql_query("SELECT pp.id, t.id AS tid FROM postpolls AS pp LEFT JOIN topics AS t ON t.poll_id = pp.id WHERE pp.id=".sqlesc($pollid)) or sqlerr(__FILE__, __LINE__);
    if (mysqli_num_rows($res) == 0)
        stderr("Error", "No poll found with that ID.");
    $arr = $res->fetch_assoc();
    $sure = isset($_GET['sure']) && (int) $_GET['sure'];
    if (!$sure || $sure != 1)
        stderr('Sanity check...', 'You are about to delete a poll. Click <a href='.$TRINITY20['baseurl'].'/forums.php?action='.htmlsafechars($action).'&amp;pollid='.(int)$arr['id'].'&amp;sure=1>here</a> if you are sure.');
    sql_query("DELETE pp.*, ppa.* FROM postpolls AS pp LEFT JOIN postpollanswers AS ppa ON ppa.pollid = pp.id WHERE pp.id=".sqlesc($pollid));
    if (mysqli_affected_rows($GLOBALS["___mysqli_ston"]) == 0)
        stderr('Sorry...', 'There was an error while deleting the poll, please re-try.');
    sql_query("UPDATE topics SET poll_id = '0' WHERE poll_id=".sqlesc($pollid));
    header('Location: '.$TRINITY20['baseurl'].'/forums.php?action=viewtopic&topicid='.(int)$arr['tid']);
    exit();
?>
