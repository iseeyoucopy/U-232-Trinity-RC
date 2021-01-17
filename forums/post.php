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
    $HTMLOUT.= '<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ERROR</title>
    <link rel="stylesheet" href="/../../foundation/dist/assets/css/app.css">
  </head>
  <body>
  <div class="grid-container">
        <div class="grid-x grid-padding-x align-center-middle text-center margin-top-3">
        <div class="callout alert margin-top-3">
          <h5>You are not allowed to enter in here</h5>
          <p>Please go back to<a href="/../../index.php"> Homepage</a></p>
        </div> 
        </div>
</div>
  </body>
</html>';
    echo $HTMLOUT;
    exit();
}

// -------- Action: Post
  $forumid = (isset($_POST['forumid']) ? (int)$_POST['forumid'] : null);
    if (isset($forumid) && !is_valid_id($forumid))
        stderr('Error', 'Invalid forum ID!');

    $posticon = (isset($_POST["iconid"]) ? (int)$_POST["iconid"] : 0);
    $topicid = (isset($_POST['topicid']) ? (int)$_POST['topicid'] : null);
    if (isset($topicid) && !is_valid_id($topicid))
        stderr('Error', 'Invalid topic ID!');

    $newtopic = is_valid_id($forumid);
    $subject = (isset($_POST["topic_name"]) ? htmlsafechars($_POST["topic_name"]) : '');

    if ($newtopic) {
        $subject = trim($subject);

        if (empty($subject))
            stderr("Error", "You must enter a topic name.");

        if (strlen($subject) > $Multi_forum['configs']['maxsubjectlength'])
            stderr("Error", "Topic length is limited to {$Multi_forum['configs']['maxsubjectlength']} characters.");
    } else
    $forumid = get_topic_forum($topicid) or die("Bad topic ID");
    // ------ Make sure sure user has write access in forum
    $arr = get_forum_access_levels($forumid) or die("Bad forum ID");

    if ($CURUSER['class'] < $arr["write"] || ($newtopic && $CURUSER['class'] < $arr["create"]) && !isMod($forumid, "topic"))
        stderr("Error", "Permission denied.");

    $body = trim($_POST["body"]);

    if (empty($body))
        stderr("Error", "No body text.");

    $userid = (int)$CURUSER["id"];

    if ($Multi_forum['configs']['use_flood_mod'] && $CURUSER['class'] < UC_STAFF && !isMod($forumid, "topic")) {
        $res = sql_query("SELECT COUNT(id) AS c FROM posts WHERE user_id=".sqlesc($CURUSER['id'])." AND added > '".(TIME_NOW - ($Multi_forum['configs']['minutes'] * 60))."'");
        $arr = mysqli_fetch_assoc($res);

        if ($arr['c'] > $Multi_forum['configs']['limit'])
            stderr("Flood", "More than ".$Multi_forum['configs']['limit']." posts in the last ".$Multi_forum['configs']['minutes']." minutes.");
    }
    if ($newtopic)
	  {
    $subject = sqlesc($subject);
  	 $anonymous = (isset($_POST['anonymous']) && $_POST["anonymous"] != "" ? "yes" : "no");
 	 sql_query("INSERT INTO topics (user_id, forum_id, topic_name, anonymous) VALUES(".sqlesc($userid).", ".sqlesc($forumid).", $subject, ".sqlesc($anonymous).")") or sqlerr(__FILE__, __LINE__);
	 $topicid = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res) or stderr("Error", "No topic ID returned!");
	 $added = sqlesc(TIME_NOW);
	 $body = sqlesc($body);
	 $anonymous = (isset($_POST['anonymous']) && $_POST["anonymous"] != "" ? "yes" : "no");
	 sql_query("INSERT INTO posts (topic_id, user_id, added, body, anonymous, icon) VALUES(".sqlesc($topicid).", ".sqlesc($userid).", $added, $body, ".sqlesc($anonymous).",".sqlesc($posticon).")") or sqlerr(__FILE__, __LINE__);
	  $postid = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res) or stderr("Error", "No post ID returned!");
	  update_topic_last_post($topicid);
          $cache->delete($keys['last_postsb'] . $CURUSER['class']);
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
        sql_query("UPDATE users SET seedbonus = seedbonus+".sqlesc($TRINITY20['bonus_per_topic'])." WHERE id =  " . sqlesc($CURUSER['id'] . "")) or sqlerr(__FILE__, __LINE__);
        $update['seedbonus'] = ($CURUSER['seedbonus'] + $TRINITY20['bonus_per_topic']);
        $cache->update_row('userstats_' . $CURUSER["id"], [
            'seedbonus' => $update['seedbonus']
        ], $TRINITY20['expires']['u_stats']);
        $cache->update_row('user_stats_' . $CURUSER["id"], [
            'seedbonus' => $update['seedbonus']
        ], $TRINITY20['expires']['user_stats']);
    }
	  }
	  else
	  {
		//---- Make sure topic exists and is unlocked
		$res = sql_query("SELECT locked, topic_name FROM topics WHERE id=".sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);
		if (mysqli_num_rows($res) == 0)
			stderr('Error', 'Inexistent Topic!');
		
		$arr = mysqli_fetch_assoc($res);
		$subject = htmlsafechars($arr["topic_name"]);
		if ($arr["locked"] == 'yes' && $CURUSER['class'] < UC_STAFF)
			stderr("Error", "This topic is locked; No new posts are allowed.");
		 // === PM subscribed members
        $res_sub = sql_query("SELECT user_id FROM subscriptions WHERE topic_id=".sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);
        while ($row = mysqli_fetch_assoc($res_sub)) {
            $res_yes = sql_query("SELECT subscription_pm, username FROM users WHERE id=".sqlesc($row["user_id"])) or sqlerr(__FILE__, __LINE__);
            $arr_yes = mysqli_fetch_assoc($res_yes);
            $msg = "Hey there!!! \n a thread you subscribed to: ".htmlsafechars($arr["topic_name"])." has had a new post!\n click [url=".$TRINITY20['baseurl']."/forums.php?action=viewtopic&topicid=".$topicid."&page=last][b]HERE[/b][/url] to read it!\n\nTo view your subscriptions, or un-subscribe, click [url=".$TRINITY20['baseurl']."/subscriptions.php][b]HERE[/b][/url].\n\ncheers.";
            if ($arr_yes["subscription_pm"] == 'yes' && $row["user_id"] != $CURUSER["id"])
            sql_query("INSERT INTO messages (sender, subject, receiver, added, msg) VALUES(".sqlesc($TRINITY20['bot_id']).", ".sqlesc("New post in subscribed thread!").", ".sqlesc($row['user_id']).", '".TIME_NOW."', ".sqlesc($msg).")") or sqlerr(__FILE__, __LINE__);
         }
    // ===end
		//------ Check double post     
		$doublepost = sql_query("SELECT p.id, p.added, p.user_id, p.body, t.last_post, t.id ".
								  "FROM posts AS p ".
								  "INNER JOIN topics AS t ON p.id = t.last_post ".
								  "WHERE t.id =".sqlesc($topicid)." AND p.user_id=".sqlesc($userid)." AND p.added > ".(TIME_NOW - 1*86400)." ".
								  "ORDER BY p.added asc LIMIT 1") or sqlerr(__FILE__, __LINE__);
		  if (mysqli_num_rows($doublepost) == 0 || $CURUSER['class'] >= UC_STAFF)
		  {
	    $added = sqlesc(TIME_NOW);
	    $body = sqlesc($body);
	    $anonymous = (isset($_POST['anonymous']) && $_POST["anonymous"] != "" ? "yes" : "no");
	    sql_query("INSERT INTO posts (topic_id, user_id, added, body, anonymous, icon) VALUES(".sqlesc($topicid).", ".sqlesc($userid).", $added, $body, ".sqlesc($anonymous).", ".sqlesc($posticon).")") or sqlerr(__FILE__, __LINE__);
		 $postid = ((is_null($___mysqli_res = mysqli_insert_id($GLOBALS["___mysqli_ston"]))) ? false : $___mysqli_res) or die("Post id n/a");

             if ($TRINITY20['autoshout_on'] == 1) {
                 if ($anonymous == 'yes') {
                     $message = "[Anonymous*] replied to the thread [url={$TRINITY20['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]"; 
               } else {
                     $message = $CURUSER['username'] . " replied to the thread [url={$TRINITY20['baseurl']}/forums.php?action=viewtopic&topicid=$topicid&page=last]{$subject}[/url]";
        }
        $cache->delete($keys['last_postsb'] . $CURUSER['class']);
        if (!in_array($forumid, $TRINITY20['staff_forums'])) {
            autoshout($message);
            $cache->delete('shoutbox_');
        }
    }
    if ($TRINITY20['seedbonus_on'] == 1) {
        sql_query("UPDATE users SET seedbonus = seedbonus+".sqlesc($TRINITY20['bonus_per_post'])." WHERE id = " . sqlesc($CURUSER['id'])) or sqlerr(__FILE__, __LINE__);
        $update['seedbonus'] = ($CURUSER['seedbonus'] + $TRINITY20['bonus_per_post']);
        $cache->update_row('userstats_' . $CURUSER["id"], [
            'seedbonus' => $update['seedbonus']
        ], $TRINITY20['expires']['u_stats']);
        $cache->update_row('user_stats_' . $CURUSER["id"], [
            'seedbonus' => $update['seedbonus']
        ], $TRINITY20['expires']['user_stats']);
    }
		
		 update_topic_last_post($topicid);
       } else {
       $results = mysqli_fetch_assoc($doublepost);
       $postid = (int)$results['last_post'];
       sql_query("UPDATE posts SET body =".sqlesc(trim($results['body'])."\n\n".$body).", edit_date=".TIME_NOW.", edited_by=".sqlesc($userid).", icon=".sqlesc($posticon)." WHERE id=".sqlesc($postid)) or sqlerr(__FILE__, __LINE__);
       }
       }

    if ($Multi_forum['configs']['use_attachment_mod'] && ((isset($_POST['uploadattachment']) ? $_POST['uploadattachment'] : '') == 'yes')) {
        $file = htmlsafechars($_FILES['file']);
        $fname = htmlsafechars($file['name']);
        $size = intval($file['size']);
        $tmpname = htmlsafechars($file['tmp_name']);
        $tgtfile = $Multi_forum['configs']['attachment_dir']."/".$fname;
        $pp = pathinfo($fname = $file['name']);
        $error = htmlsafechars($file['error']);
        $type = htmlsafechars($file['type']);

        $uploaderror = '';

        if (empty($fname))
            $uploaderror = "Invalid Filename!";

        if (!validfilename($fname))
            $uploaderror = "Invalid Filename!";

        foreach ($Multi_forum['configs']['allowed_file_extensions'] as $allowed_file_extension);
        if (!preg_match('/^(.+)\.[' . join(']|[', $Multi_forum['configs']['allowed_file_extensions']) . ']$/si', $fname, $matches))
            $uploaderror = 'Only files with the following extensions are allowed: ' . join(', ', $Multi_forum['configs']['allowed_file_extensions']) . '.';

        if ($size > $Multi_forum['configs']['maxfilesize'])
            $uploaderror = "Sorry, that file is too large.";

        if ($pp['basename'] != $fname)
            $uploaderror = "Bad file name.";

        if (file_exists($tgtfile))
            $uploaderror = "Sorry, a file with the name already exists.";

        if (!is_uploaded_file($tmpname))
            $uploaderror = "Can't Upload file!";

        if (!filesize($tmpname))
            $uploaderror = "Empty file!";

        if ($error != 0)
            $uploaderror = "There was an error while uploading the file.";

        if (empty($uploaderror)) {
            sql_query("INSERT INTO attachments (topic_id, post_id, file_name, size, user_id, added, extension) VALUES (".sqlesc($topicid).",".sqlesc($postid).", ".sqlesc($fname).", ".sqlesc($size).", ".sqlesc($userid).", ".TIME_NOW.", ".sqlesc($type).")") or sqlerr(__FILE__, __LINE__);
        move_uploaded_file($tmpname, $tgtfile);
        }
    }
    $headerstr = "Location: forums.php?action=viewtopic&topicid=$topicid".($Multi_forum['configs']['use_attachment_mod'] && !empty($uploaderror) ? "&uploaderror=$uploaderror" : "") . "&page=last";
    header($headerstr . ($newtopic ? '' : "#p$postid"));
    exit();

?>
