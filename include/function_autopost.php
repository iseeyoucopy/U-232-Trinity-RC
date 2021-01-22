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
// === auto post by retro
function auto_post($subject = "Error - Subject Missing", $body = "Error - No Body") // Function to use the special system message forum
{
    global $CURUSER, $TRINITY20, $cache, $mysqli;
    $res = sql_query("SELECT id FROM topics WHERE forum_id = {$TRINITY20['staff']['forumid']} AND topic_name = " . sqlesc($subject));
    if (mysqli_num_rows($res) == 1) { // Topic already exists in the system forum.
        $arr = $res->fetch_assoc();
        $topicid = (int)$arr['id'];
    } else { // Create new topic.
        sql_query("INSERT INTO topics (user_id, forum_id, topic_name) VALUES({$TRINITY20['bot_id']}, {$TRINITY20['staff']['forumid']}, $subject)") or sqlerr(__FILE__, __LINE__);
        $topicid = $mysqli->insert_id;
    $cache->delete('last_posts_' . $CURUSER['class']);
    $cache->delete('forum_posts_' . $CURUSER['id']);
    }
    $added = TIME_NOW;
    sql_query("INSERT INTO posts (topic_id, user_id, added, body) " . "VALUES(" . sqlesc($topicid) . ", {$TRINITY20['bot_id']}, $added, ".sqlesc($body).")") or sqlerr(__FILE__, __LINE__);
    $res = sql_query("SELECT id FROM posts WHERE topic_id=" . sqlesc($topicid) . " ORDER BY id DESC LIMIT 1") or sqlerr(__FILE__, __LINE__);
    $arr = mysqli_fetch_row($res) or die("No post found");
    $postid = $arr[0];
    sql_query("UPDATE topics SET last_post=" . sqlesc($postid) . " WHERE id=" . sqlesc($topicid)) or sqlerr(__FILE__, __LINE__);
    $cache->delete('last_posts_' . $CURUSER['class']);
    $cache->delete('forum_posts_' . $CURUSER['id']);
    }
?>
