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
$forumid = (int)$_GET["forumid"];
if ($CURUSER['class'] >= MAX_CLASS || isMod($forumid, "forum")) {
    if (!is_valid_id($forumid)) stderr('Error', 'Invalid ID!');
    ($res = sql_query("SELECT 
                        name, 
                        description, 
                        min_class_read, 
                        min_class_write, 
                        min_class_create 
                    FROM 
                        forums 
                    WHERE 
                        id=".sqlesc($forumid))) || sqlerr(__FILE__, __LINE__);
    if ($res->num_rows == 0) stderr('Error', 'No forum found with that ID!');
    $forum = $res->fetch_assoc();
    if ($TRINITY20['forums_online'] == 0) 
        $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
    $HTMLOUT .= "<div class='card'>
        <div class='card-divider'>
            <strong>Edit Forum</strong>
        </div>
        <div class='card-section'>
            <form method='post' action='{$TRINITY20['baseurl']}/forums.php?action=updateforum&amp;forumid=$forumid'>
                <div class='input-group'>
                    <span class='input-group-label'>Forum name</span>
                    <input class='input-group-field' type='text' size='60' maxlength='{$Multi_forum['configs']['maxsubjectlength']}' name='name' value='" . htmlsafechars($forum['name']) . "'>
                </div>
                <div class='input-group'>
                    <span class='input-group-label'>Description</span>
                    <textarea class='input-group-field' name='description' cols='60' rows='3'>" . htmlsafechars($forum['description']) . "</textarea>
                </div>
                <div class='input-group'>
                    <span class='input-group-label'>Minimum Class required to View</span>
                    <select class='input-group-field' name='readclass'>";
                        for ($i = 0; $i <= MAX_CLASS; ++$i) {
                            $HTMLOUT .="<option value='$i' " . ($i == $forum['min_class_read'] ? " selected='selected'" : "") .">".get_user_class_name($i)."</option>";
                        }
                    $HTMLOUT .= "</select>
                </div>
                <div class='input-group'>
                    <span class='input-group-label'>Minimum Class required to Post</span>
                    <select class='input-group-field' name='writeclass'>";
                        for ($i = 0; $i <= MAX_CLASS; ++$i) {
                            $HTMLOUT .="<option value='$i' " . ($i == $forum['min_class_write'] ? " selected='selected'" : "") .">".get_user_class_name($i)."</option>";
                        }
                    $HTMLOUT .="</select>
                </div>
                <div class='input-group'>
                    <span class='input-group-label'>Minimum Class required to Create Topics</span>
                    <select class='input-group-field' name='createclass'>";
                        for ($i = 0; $i <= MAX_CLASS; ++$i) {
                            $HTMLOUT .="<option value='$i' " . ($i == $forum['min_class_create'] ? " selected='selected'" : "") .">".get_user_class_name($i)."</option>";
                        }
                    $HTMLOUT .="</select>
                </div>
                <input class='button float-center' type='submit' value='Submit'>
            </form>
        </div>
    </div>";
    echo stdhead($lang['forums_title_edit'], true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
    exit();
}
?>
