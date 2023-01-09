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
$topicid = (isset($_GET['topicid']) ? (int)$_GET['topicid'] : (isset($_POST['topicid']) ? (int)$_POST['topicid'] : 0));
if (!is_valid_id($topicid)) {
    stderr('Error...', 'Invalid topic ID!');
}
($topic_res = sql_query('SELECT t.sticky, t.locked, t.topic_name, t.forum_id, f.min_class_write, '.'(SELECT COUNT(id) FROM posts WHERE topic_id = t.id) As post_count '.'FROM topics AS t '.'LEFT JOIN forums AS f ON f.id = t.forum_id '.'WHERE t.id='.sqlesc($topicid))) || sqlerr(__FILE__,
    __LINE__);
if ($topic_res->num_rows == 0) {
    stderr('Error...', 'No topic with that ID!');
}
$topic_arr = $topic_res->fetch_assoc();
if (isMod($topic_arr["forum_id"]) || $CURUSER['class'] >= UC_STAFF) {
    if (($CURUSER['class'] < $topic_arr['min_class_write']) && !isMod($topic_arr["forum_id"], "topic")) {
        stderr('Error...', 'You are not allowed to edit this topic.');
    }
    $forumid = (int)$topic_arr['forum_id'];
    $subject = htmlspecialchars($topic_arr['topic_name']);
    if ((isset($_GET['delete']) ? htmlspecialchars($_GET['delete']) : (isset($_POST['delete']) ? htmlspecialchars($_POST['delete']) : '')) == 'yes') {
        if ((isset($_GET['sure']) ? htmlspecialchars($_GET['sure']) : (isset($_POST['sure']) ? htmlspecialchars($_POST['sure']) : '')) != 'yes') {
            stderr("Sanity check...",
                "You are about to delete this topic: <b>".$subject."</b>. Click <a href='{$TRINITY20['baseurl']}/forums.php?action=$action&amp;topicid=$topicid&amp;delete=yes&amp;sure=yes'>here</a> if you are sure.");
        }
        write_log("topicdelete",
            "Topic <b>".$subject."</b> was deleted by <a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$CURUSER['id']."'>".htmlspecialchars($CURUSER['username'])."</a>.");
        if ($Multi_forum['configs']['use_attachment_mod']) {
            ($res = sql_query("SELECT attachments.file_name "."FROM posts "."LEFT JOIN attachments ON attachments.post_id = posts.id "."WHERE posts.topic_id=".sqlesc($topicid))) || sqlerr(__FILE__,
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
            ($Multi_forum['configs']['use_attachment_mod'] ? "LEFT JOIN attachments ON attachments.post_id = posts.id "."LEFT JOIN attachmentdownloads ON attachmentdownloads.file_id = attachments.id " : "").
            ($Multi_forum['configs']['use_poll_mod'] ? "LEFT JOIN postpolls ON postpolls.id = topics.poll_id "."LEFT JOIN postpollanswers ON postpollanswers.pollid = postpolls.id " : "")."WHERE topics.id=".sqlesc($topicid)) || sqlerr(__FILE__,
            __LINE__);
        header('Location: '.$TRINITY20['baseurl'].'/forums.php?action=viewforum&forumid='.$forumid);
        exit();
    }
    $returnto = $TRINITY20['baseurl'].'/forums.php?action=viewtopic&topicid='.$topicid;
    $updateset = [];
    $locked = ($_POST['locked'] == 'yes' ? 'yes' : 'no');
    if ($locked != $topic_arr['locked']) {
        $updateset[] = 'locked='.sqlesc($locked);
    }
    $sticky = ($_POST['sticky'] == 'yes' ? 'yes' : 'no');
    if ($sticky != $topic_arr['sticky']) {
        $updateset[] = 'sticky='.sqlesc($sticky);
    }
    $new_subject = htmlspecialchars($_POST['topic_name']);
    if ($new_subject != $subject) {
        if (empty($new_subject)) {
            stderr('Error...', 'Topic name cannot be empty.');
        }
        $updateset[] = 'topic_name='.sqlesc($new_subject);
    }
    $new_forumid = (int)$_POST['new_forumid'];
    if (!is_valid_id($new_forumid)) {
        stderr('Error...', 'Invalid forum ID!');
    }
    if ($new_forumid !== $forumid) {
        $post_count = (int)$topic_arr['post_count'];
        ($res = sql_query("SELECT min_class_write FROM forums WHERE id=".sqlesc($new_forumid))) || sqlerr(__FILE__, __LINE__);
        if ($res->num_rows != 1) {
            stderr("Error...", "Forum not found!");
        }
        $arr = $res->fetch_assoc();
        if ($CURUSER['class'] < (int)$arr['min_class_write']) {
            stderr('Error...', 'You are not allowed to move this topic into the selected forum.');
        }
        $updateset[] = 'forum_id='.sqlesc($new_forumid);
        sql_query("UPDATE forums SET topic_count=topic_count-1, post_count=post_count-".sqlesc($post_count)." WHERE id=".sqlesc($forumid)) || sqlerr(__FILE__,
            __LINE__);
        sql_query("UPDATE forums SET topic_count=topic_count+1, post_count=post_count+".sqlesc($post_count)." WHERE id=".sqlesc($new_forumid)) || sqlerr(__FILE__,
            __LINE__);
        $returnto = $TRINITY20['baseurl'].'/forums.php?action=viewforum&forumid='.$new_forumid;
    }
    if ((is_countable($updateset) ? count($updateset) : 0) > 0) {
        sql_query("UPDATE topics SET ".implode(', ', $updateset)." WHERE id=".sqlesc($topicid)) || sqlerr(__FILE__, __LINE__);
    }
    header('Location: '.$returnto);
    exit();
}
