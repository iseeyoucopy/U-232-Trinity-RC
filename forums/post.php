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
    $HTMLOUT .= '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
        <head>
        <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}

// -------- Action: Post
$forumid = (isset($_POST['forumid']) ? (int)$_POST['forumid'] : null);
if (isset($forumid) && !is_valid_id($forumid)) {
    stderr('Error', 'Invalid forum ID!');
}

$posticon = (isset($_POST["iconid"]) ? (int)$_POST["iconid"] : 0);
$topicid = (isset($_POST['topicid']) ? (int)$_POST['topicid'] : null);
if (isset($topicid) && !is_valid_id($topicid)) {
    stderr('Error', 'Invalid topic ID!');
}

$newtopic = is_valid_id($forumid);
$subject = (isset($_POST["topic_name"]) ? htmlsafechars($_POST["topic_name"]) : '');

if ($newtopic) {
    $subject = trim($subject);

    if (empty($subject)) {
        stderr("Error", "You must enter a topic name.");
    }

    if (strlen($subject) > $Multi_forum['configs']['maxsubjectlength']) {
        stderr("Error", "Topic length is limited to {$Multi_forum['configs']['maxsubjectlength']} characters.");
    }
} else {
    ($forumid = get_topic_forum($topicid)) || die("Bad topic ID");
}
// ------ Make sure sure user has write access in forum
($arr = get_forum_access_levels($forumid)) || die("Bad forum ID");

if ($CURUSER['class'] < $arr["write"] || ($newtopic && $CURUSER['class'] < $arr["create"]) && !isMod($forumid, "topic")) {
    stderr("Error", "Permission denied.");
}

$body = trim($_POST["body"]);

if (empty($body)) {
    stderr("Error", "No body text.");
}

$userid = (int)$CURUSER["id"];

if ($Multi_forum['configs']['use_flood_mod'] && $CURUSER['class'] < UC_STAFF && !isMod($forumid, "topic")) {
    $res = sql_query("SELECT COUNT(id) AS c FROM posts WHERE user_id=".sqlesc($CURUSER['id'])." AND added > '".(TIME_NOW - ($Multi_forum['configs']['minutes'] * 60))."'");
    $arr = $res->fetch_assoc();

    if ($arr['c'] > $Multi_forum['configs']['limit']) {
        stderr("Flood", "More than ".$Multi_forum['configs']['limit']." posts in the last ".$Multi_forum['configs']['minutes']." minutes.");
    }
}
if ($newtopic) {
    $subject = sqlesc($subject);
    $anonymous = (isset($_POST['anonymous']) && $_POST["anonymous"] != "" ? "yes" : "no");
    sql_query("INSERT INTO topics (user_id, forum_id, topic_name, anonymous) VALUES(".sqlesc($userid).", ".sqlesc($forumid).", $subject, ".sqlesc($anonymous).")") || sqlerr(__FILE__,
        __LINE__);
    ($topicid = $mysqli->insert_id) || stderr("Error", "No topic ID returned!");
    $added = sqlesc(TIME_NOW);
    $body = sqlesc($body);
    $anonymous = (isset($_POST['anonymous']) && $_POST["anonymous"] != "" ? "yes" : "no");
    sql_query("INSERT INTO posts (topic_id, user_id, added, body, anonymous, icon) VALUES(".sqlesc($topicid).", ".sqlesc($userid).", $added, $body, ".sqlesc($anonymous).",".sqlesc($posticon).")") || sqlerr(__FILE__,
        __LINE__);
    ($postid = $mysqli->insert_id) || stderr("Error", "No post ID returned!");
    update_topic_last_post($topicid);
    $cache->delete($cache_keys['last_posts_b'].$CURUSER['class']);
    if ($TRINITY20['autoshout_on'] == 1) {
        if ($anonymous == 'yes') {
            $message = "[Anonymous*] Created a new forum thread [url={$TRINITY20['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]";
        } else {
            $message = $CURUSER['username']." Created a new forum thread [url={$TRINITY20['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]";
        }
        if (!in_array($forumid, $TRINITY20['staff_forums'])) {
            autoshout($message);
            $cache->delete('shoutbox_');
        }
    }
    if ($TRINITY20['seedbonus_on'] == 1) {
        sql_query("UPDATE users SET seedbonus = seedbonus+".sqlesc($TRINITY20['bonus_per_topic'])." WHERE id =  ".sqlesc($CURUSER['id']."")) || sqlerr(__FILE__,
            __LINE__);
        $update['seedbonus'] = ($CURUSER['seedbonus'] + $TRINITY20['bonus_per_topic']);
        $cache->update_row($cache_keys['user_stats'].$CURUSER["id"], [
            'seedbonus' => $update['seedbonus'],
        ], $TRINITY20['expires']['u_stats']);
        $cache->update_row($cache_keys['user_statss'].$CURUSER["id"], [
            'seedbonus' => $update['seedbonus'],
        ], $TRINITY20['expires']['user_stats']);
    }
} else {
    //---- Make sure topic exists and is unlocked
    ($res = sql_query("SELECT locked, topic_name FROM topics WHERE id=".sqlesc($topicid))) || sqlerr(__FILE__, __LINE__);
    if ($res->num_rows == 0) {
        stderr('Error', 'Inexistent Topic!');
    }

    $arr = $res->fetch_assoc();
    $subject = htmlsafechars($arr["topic_name"]);
    if ($arr["locked"] == 'yes' && $CURUSER['class'] < UC_STAFF) {
        stderr("Error", "This topic is locked; No new posts are allowed.");
    }
    // === PM subscribed members
    ($res_sub = sql_query("SELECT user_id FROM subscriptions WHERE topic_id=".sqlesc($topicid))) || sqlerr(__FILE__, __LINE__);
    while ($row = $res_sub->fetch_assoc()) {
        ($res_yes = sql_query("SELECT subscription_pm, username FROM users WHERE id=".sqlesc($row["user_id"]))) || sqlerr(__FILE__, __LINE__);
        $arr_yes = $res_yes->fetch_assoc();
        $msg = "Hey there!!! \n a thread you subscribed to: ".htmlsafechars($arr["topic_name"])." has had a new post!\n click [url=".$TRINITY20['baseurl']."/forums.php?action=viewtopic&topicid=".$topicid."&page=last][b]HERE[/b][/url] to read it!\n\nTo view your subscriptions, or un-subscribe, click [url=".$TRINITY20['baseurl']."/subscriptions.php][b]HERE[/b][/url].\n\ncheers.";
        if ($arr_yes["subscription_pm"] == 'yes' && $row["user_id"] != $CURUSER["id"]) {
            sql_query("INSERT INTO messages (sender, subject, receiver, added, msg) VALUES(".sqlesc($TRINITY20['bot_id']).", ".sqlesc("New post in subscribed thread!").", ".sqlesc($row['user_id']).", '".TIME_NOW."', ".sqlesc($msg).")") || sqlerr(__FILE__,
                __LINE__);
        }
    }
    // ===end
    //------ Check double post
    ($doublepost = sql_query("SELECT p.id, p.added, p.user_id, p.body, t.last_post, t.id ".
        "FROM posts AS p ".
        "INNER JOIN topics AS t ON p.id = t.last_post ".
        "WHERE t.id =".sqlesc($topicid)." AND p.user_id=".sqlesc($userid)." AND p.added > ".(TIME_NOW - 1 * 86400)." ".
        "ORDER BY p.added asc LIMIT 1")) || sqlerr(__FILE__, __LINE__);
    if ($doublepost->num_rows == 0 || $CURUSER['class'] >= UC_STAFF) {
        $added = sqlesc(TIME_NOW);
        $body = sqlesc($body);
        $anonymous = (isset($_POST['anonymous']) && $_POST["anonymous"] != "" ? "yes" : "no");
        sql_query("INSERT INTO posts (topic_id, user_id, added, body, anonymous, icon) VALUES(".sqlesc($topicid).", ".sqlesc($userid).", $added, $body, ".sqlesc($anonymous).", ".sqlesc($posticon).")") || sqlerr(__FILE__,
            __LINE__);
        ($postid = $mysqli->insert_id) || die("Post id n/a");

        if ($TRINITY20['autoshout_on'] == 1) {
            if ($anonymous == 'yes') {
                $message = "[Anonymous*] replied to the thread [url={$TRINITY20['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]";
            } else {
                $message = $CURUSER['username']." replied to the thread [url={$TRINITY20['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]";
            }
            $cache->delete($cache_keys['last_posts_b'].$CURUSER['class']);
            if (!in_array($forumid, $TRINITY20['staff_forums'])) {
                autoshout($message);
                $cache->delete('shoutbox_');
            }
        }
        if ($TRINITY20['seedbonus_on'] == 1) {
            sql_query("UPDATE users SET seedbonus = seedbonus+".sqlesc($TRINITY20['bonus_per_post'])." WHERE id = ".sqlesc($CURUSER['id'])) || sqlerr(__FILE__,
                __LINE__);
            $update['seedbonus'] = ($CURUSER['seedbonus'] + $TRINITY20['bonus_per_post']);
            $cache->update_row($cache_keys['user_stats'].$CURUSER["id"], [
                'seedbonus' => $update['seedbonus'],
            ], $TRINITY20['expires']['u_stats']);
            $cache->update_row($cache_keys['user_statss'].$CURUSER["id"], [
                'seedbonus' => $update['seedbonus'],
            ], $TRINITY20['expires']['user_stats']);
        }

        update_topic_last_post($topicid);
    } else {
        $results = $doublepost->fetch_assoc();
        $postid = (int)$results['last_post'];
        sql_query("UPDATE posts SET body =".sqlesc(trim($results['body'])."\n\n".$body).", edit_date=".TIME_NOW.", edited_by=".sqlesc($userid).", icon=".sqlesc($posticon)." WHERE id=".sqlesc($postid)) || sqlerr(__FILE__,
            __LINE__);
    }
}

if ($Multi_forum['configs']['use_attachment_mod'] && (($_POST['uploadattachment'] ?? '') == 'yes')) {
    $file = htmlsafechars($_FILES['file']);
    $fname = htmlsafechars($file['name']);
    $size = (int)$file['size'];
    $tmpname = htmlsafechars($file['tmp_name']);
    $tgtfile = $Multi_forum['configs']['attachment_dir']."/".$fname;
    $pp = pathinfo($fname = $file['name']);
    $error = htmlsafechars($file['error']);
    $type = htmlsafechars($file['type']);

    $uploaderror = '';

    if (empty($fname)) {
        $uploaderror = "Invalid Filename!";
    }

    if (!validfilename($fname)) {
        $uploaderror = "Invalid Filename!";
    }

    foreach ($Multi_forum['configs']['allowed_file_extensions'] as $allowed_file_extension) {
    }
    if (!preg_match('/^(.+)\.['.implode(']|[', $Multi_forum['configs']['allowed_file_extensions']).']$/si', $fname, $matches)) {
        $uploaderror = 'Only files with the following extensions are allowed: '.implode(', ', $Multi_forum['configs']['allowed_file_extensions']).'.';
    }

    if ($size > $Multi_forum['configs']['maxfilesize']) {
        $uploaderror = "Sorry, that file is too large.";
    }

    if ($pp['basename'] != $fname) {
        $uploaderror = "Bad file name.";
    }

    if (file_exists($tgtfile)) {
        $uploaderror = "Sorry, a file with the name already exists.";
    }

    if (!is_uploaded_file($tmpname)) {
        $uploaderror = "Can't Upload file!";
    }

    if (!filesize($tmpname)) {
        $uploaderror = "Empty file!";
    }

    if ($error != 0) {
        $uploaderror = "There was an error while uploading the file.";
    }

    if (empty($uploaderror)) {
        sql_query("INSERT INTO attachments (topic_id, post_id, file_name, size, user_id, added, extension) VALUES (".sqlesc($topicid).",".sqlesc($postid).", ".sqlesc($fname).", ".sqlesc($size).", ".sqlesc($userid).", ".TIME_NOW.", ".sqlesc($type).")") || sqlerr(__FILE__,
            __LINE__);
        move_uploaded_file($tmpname, $tgtfile);
    }
}
$headerstr = "Location: forums.php?action=viewtopic&topicid=$topicid".($Multi_forum['configs']['use_attachment_mod'] && !empty($uploaderror) ? "&uploaderror=$uploaderror" : "")."&page=last";
header($headerstr.($newtopic ? '' : "#p$postid"));
exit();
