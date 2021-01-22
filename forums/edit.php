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
 // -------- Action: Edit Forum
        $forumid = (int)$_GET["forumid"];
        if ($CURUSER['class'] >= MAX_CLASS || isMod($forumid, "forum")) {
        if (!is_valid_id($forumid))
            stderr('Error', 'Invalid ID!');
        $res = sql_query("SELECT name, description, min_class_read, min_class_write, min_class_create FROM forums WHERE id=".sqlesc($forumid)) or sqlerr(__FILE__, __LINE__);
        if ($res->num_row() == 0)
        stderr('Error', 'No forum found with that ID!');
        $forum = $res->fetch_assoc();
        if ($TRINITY20['forums_online'] == 0)
        $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
        $HTMLOUT .= begin_main_frame();
        $HTMLOUT .= begin_frame("Edit Forum", "center");
        $HTMLOUT .="<form method='post' action='{$TRINITY20['baseurl']}/forums.php?action=updateforum&amp;forumid=$forumid'>\n";
        $HTMLOUT .= begin_table();
        $HTMLOUT .="<tr><td>Forum name</td>
        <td align='left' style='padding: 0px'><input type='text' size='60' maxlength='{$Multi_forum['configs']['maxsubjectlength']}' name='name' style='border: 0px; height: 19px' value=\"".htmlsafechars($forum['name'])."\" /></td></tr>
        <tr><td>Description</td><td align='left' style='padding: 0px'><textarea name='description' cols='68' rows='3' style='border: 0px'>" . htmlsafechars($forum['description']) . "</textarea></td></tr>
        <tr><td></td><td align='left' style='padding: 0px'>&nbsp;Minimum <select name='readclass'>";
        for ($i = 0; $i <= MAX_CLASS; ++$i)
        $HTMLOUT .="<option value='$i' " . ($i == $forum['min_class_read'] ? " selected='selected'" : "") .">".get_user_class_name($i)."</option>\n";
        $HTMLOUT .="</select> Class required to View<br />\n&nbsp;Minimum <select name='writeclass'>";
        for ($i = 0; $i <= MAX_CLASS; ++$i)
        $HTMLOUT .="<option value='$i' " . ($i == $forum['min_class_write'] ? " selected='selected'" : "") .">".get_user_class_name($i)."</option>\n";
        $HTMLOUT .="</select> Class required to Post<br />\n&nbsp;Minimum <select name='createclass'>";
        for ($i = 0; $i <= MAX_CLASS; ++$i)
        $HTMLOUT .="<option value='$i' " . ($i == $forum['min_class_create'] ? " selected='selected'" : "") .">".get_user_class_name($i)."</option>\n";
        $HTMLOUT .="</select> Class required to Create Topics</td></tr>
        <tr><td colspan='2' align='center'><input type='submit' value='Submit' /></td></tr>\n";
        $HTMLOUT .= end_table();
        $HTMLOUT .="</form>";
        $HTMLOUT .= end_frame();
        $HTMLOUT .= end_main_frame();
        echo stdhead("{$lang['forums_title']}", true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
        exit();
    }
?>
