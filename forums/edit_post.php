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
    $HTMLOUT .= '<!DOCTYPE html>
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
// -------- Action: Edit post
$postid = (int)$_GET["postid"];
if (!is_valid_id($postid)) {
    stderr('Error', 'Invalid ID!');
}
($res = sql_query("SELECT p.user_id, p.topic_id, p.icon, p.body, t.locked, t.forum_id  "."FROM posts AS p "."LEFT JOIN topics AS t ON t.id = p.topic_id "."WHERE p.id = ".sqlesc($postid))) || sqlerr(__FILE__,
    __LINE__);
if ($res->num_rows == 0) {
    stderr("Error", "No post with that ID!");
}
$arr = $res->fetch_assoc();
if (($CURUSER["id"] != $arr["user_id"] || $arr["locked"] == 'yes') && $CURUSER['class'] < UC_STAFF && !isMod($arr["forum_id"], "forum")) {
    stderr("Error", "Access Denied!");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $body = trim(htmlspecialchars($_POST['body']));
    $posticon = (isset($_POST["iconid"]) ? 0 + $_POST["iconid"] : 0);
    if (empty($body)) {
        stderr("Error", "Body cannot be empty!");
    }
    if (!isset($_POST['lasteditedby'])) {
        sql_query("UPDATE posts SET body=".sqlesc($body).", edit_date=".TIME_NOW.", edited_by=".sqlesc($CURUSER['id']).", icon=".sqlesc($posticon)." WHERE id=".sqlesc($postid)) || sqlerr(__FILE__,
            __LINE__);
    } else {
        sql_query("UPDATE posts SET body=".sqlesc($body).", icon=".sqlesc($posticon)." WHERE id=".sqlesc($postid)) || sqlerr(__FILE__, __LINE__);
    }
    header("Location: {$TRINITY20['baseurl']}/forums.php?action=viewtopic&topicid=".(int)$arr['topic_id']."&page=p$postid#p$postid");
    exit();
}
if ($TRINITY20['forums_online'] == 0) {
    $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
}
$HTMLOUT .= "<form name='compose' method='post' action='{$TRINITY20['baseurl']}/forums.php?action=editpost&amp;postid=".$postid."'>
        <div class='card'>
            <div class='card-section'>
                <div class='grid-x grid-margin-x'>
                    <div class='cell medium-6 large-8'>
                        <div class='card'>
                            <div class='card-divider'>Edit Post</div>
                            <div class='card-section'>
                                ".(function_exists('textbbcode') ? textbbcode('compose', 'body',
        htmlspecialchars($arr["body"])) : "<textarea name='body' style='width:99%' rows='7'>".htmlspecialchars($arr["body"])."</textarea>")." 
                            </div>
                        </div>
                    </div>
                    <div class='cell medium-6 large-4'>
                        <div class='card'>
                            <div class='card-divider'>Post Icons (Optional)</div>
                            <div class='card-section'>
                                ".(post_icons($arr["icon"]))."
                            </div>
                        </div>";
if ($CURUSER["class"] >= UC_STAFF) {
    $HTMLOUT .= "<div class='card'>
                                <div class='card-divider'>Do you want to show the `Last edited by` ?</div>
                                <div class='card-section float-center'>
                                    <div class='switch'>
                                        <input class='input-group-field switch-input' type='checkbox' id='lasteditedby_chk' name='lasteditedby' value='yes'>
                                        <label class='switch-paddle' for='lasteditedby_chk'>
                                            <span class='switch-active' aria-hidden='true'>Yes</span>
                                            <span class='switch-inactive' aria-hidden='true'>No</span>
                                        </label>
                                    </div>
                                </div>
                            </div>";
}
$HTMLOUT .= "</div>
                </div>
                <input type='submit' class='button float-center' value='Update post'>
            </div>
        </div>
    </form>";
echo stdhead("Edit Post", true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
exit;
