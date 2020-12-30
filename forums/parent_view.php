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
    echo $HTMLOUT;
    exit();
}
        $ovfid = (isset($_GET["forid"]) ? (int)$_GET["forid"] : 0);
        if (!is_valid_id($ovfid))
            stderr('Error', 'Invalid ID!');
        $res = sql_query("SELECT name FROM over_forums WHERE id=".sqlesc($ovfid)) or sqlerr(__FILE__, __LINE__);
        $arr = mysqli_fetch_assoc($res) or stderr('Sorry', 'No forums with that ID!');
        sql_query("UPDATE users SET forum_access = ".TIME_NOW." WHERE id=".sqlesc($CURUSER['id'])) or sqlerr(__FILE__, __LINE__);
        if ($TRINITY20['forums_online'] == 0)
        $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
$HTMLOUT .="<nav aria-label='You are here:' role='navigation'>
  <ul class='breadcrumbs'>
    <li><a href='index.php'>" . $TRINITY20["site_name"] . "</a></li>
    <li><a href='forums.php'>Forums</a></li>
    <li>
      <span class='show-for-sr'>Current: </span>".htmlsafechars($arr["name"])."
    </li>
  </ul>
</nav>";
$HTMLOUT .= begin_main_div();
$HTMLOUT .= begin_head_div(); 
$HTMLOUT .= begin_head_label("collapse".$ovfid."");
$HTMLOUT .="<img src='templates/1/pic/carbon/collapse.png' alt='[+/-]' title='[+/-]' />";
$HTMLOUT .="&nbsp;&nbsp;<strong>".htmlsafechars($arr["name"])."</strong><span class='desc'></span>";
$HTMLOUT .= end_head_label();
$HTMLOUT .= end_div();
$HTMLOUT .= begin_body_div("collapse".$ovfid."");	
	$HTMLOUT .="
	<table border='0' cellspacing='0' cellpadding='5' class='tborder clear'>
";
        $HTMLOUT .= show_forums($ovfid);
        $HTMLOUT .= end_table();
      //  $HTMLOUT .= end_main_frame();
        echo stdhead("Forums", true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
        exit();
?>
