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
        <meta charset="'.charset().'">
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
$topicid = (int)$_GET['topicid'];
if (!is_valid_id($topicid)) {
    stderr('Error', 'Invalid ID');
}
($r = sql_query("SELECT t.id, t.topic_name ".($Multi_forum['configs']['use_poll_mod'] ? ",t.poll_id" : "").",t.forum_id,(SELECT COUNT(p.id) FROM posts as p where p.topic_id=".sqlesc($topicid).") AS posts FROM topics as t WHERE t.id=".sqlesc($topicid))) || sqlerr(__FILE__,
    __LINE__);
($a = $r->fetch_assoc()) || stderr("Error", "No topic was found");
if ($CURUSER["class"] >= UC_STAFF || isMod($a["forum_id"], "forum")) {
    $sure = isset($_GET['sure']) && (int)$_GET['sure'];
    if (!$sure) {
        stderr("Sanity check...",
            "You are about to delete topic ".htmlsafechars($a["topic_name"]).". Click <a href='{$TRINITY20['baseurl']}/forums.php?action=deletetopic&amp;topicid=$topicid&amp;sure=1'>here</a> if you are sure.");
    } else {
        write_log("topicdelete",
            "Topic <b>".htmlsafechars($a["topic_name"])."</b> was deleted by <a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$CURUSER['id']."'>".htmlsafechars($CURUSER['username'])."</a>.");
        if ($Multi_forum['configs']['use_attachment_mod']) {
            ($res = sql_query("SELECT attachments.file_name "."FROM posts "."LEFT JOIN attachments ON attachments.post_id = posts.id "."WHERE posts.topic_id = ".sqlesc($topicid))) || sqlerr(__FILE__,
                __LINE__);
            while ($arr = $res->fetch_assoc()) {
                if (!empty($arr['filename']) && is_file($Multi_forum['configs']['attachment_dir']."/".$arr['filename'])) {
                    unlink($Multi_forum['configs']['attachment_dir']."/".$arr['filename']);
                }
            }
        }
        sql_query("DELETE posts, topics ".
            ($Multi_forum['configs']['use_attachment_mod'] ? ", attachments, attachmentdownloads " : "").
            ($Multi_forum['configs']['use_poll_mod'] ? ", postpolls, postpollanswers " : "")."FROM topics "."LEFT JOIN posts ON posts.topic_id = topics.id ".
            ($Multi_forum['configs']['use_attachment_mod'] ? "LEFT JOIN attachments ON attachments.post_id = posts.id "."LEFT JOIN attachmentdownloads ON attachmentdownloads.file_id = attachments.id " : "").($Multi_forum['configs']['use_poll_mod'] ? "LEFT JOIN postpolls ON postpolls.id = topics.poll_id "."LEFT JOIN postpollanswers ON postpollanswers.pollid = postpolls.id " : "")."WHERE topics.id=".sqlesc($topicid)) || sqlerr(__FILE__,
            __LINE__);
        header('Location: '.$TRINITY20['baseurl'].'/forums.php?action=viewforum&forumid='.(int)$a["forumid"]);
        exit();
    }
}
