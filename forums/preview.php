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
*******/
if (!defined('IN_INSTALLER09_FORUM')) {
    $HTMLOUT = '';
    $HTMLOUT.= '<!DOCTYPE html>
        <html xmlns="http://www.w3.org/1999/xhtml" lang="en">
        <head>
        <meta charset="UTF-8" />
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
$body = (isset($_POST['body']) ? htmlsafechars($_POST['body']) : '');
$HTMLOUT .= begin_main_frame();
$HTMLOUT .= begin_frame("Preview Post", true);
$HTMLOUT .='
	<table class="table table-bordered">
	<tr><td class="forum_head" colspan="2"><span style="font-weight: bold;">Preview</span></td></tr>
	<tr><td width="80" valign="top" class="one">' . avatar_stuff($CURUSER) . '</td>
	<td valign="top" align="left" class="two">' . format_comment($body) . '</td>
	</tr></table><div align="center">
</div><br /><br />';
$HTMLOUT .= end_frame();
$HTMLOUT .= end_main_frame();
echo stdhead('Preview') . $HTMLOUT . stdfoot();
?>
