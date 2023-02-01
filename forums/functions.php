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
function catch_up($id = 0)
{
    global $CURUSER, $TRINITY20, $mysqli;
    $userid = (int)$CURUSER['id'];
    ($res = sql_query("SELECT t.id, t.last_post, r.id AS r_id, r.last_post_read "."FROM topics AS t "."LEFT JOIN posts AS p ON p.id = t.last_post "."LEFT JOIN read_posts AS r ON r.user_id=".sqlesc($userid)." AND r.topic_id=t.id "."WHERE p.added > ".sqlesc(TIME_NOW - $TRINITY20['readpost_expiry']).
        (empty($id) ? '' : ' AND t.id '.(is_array($id) ? 'IN ('.implode(', ', $id).')' : '= '.sqlesc($id))))) || sqlerr(__FILE__, __LINE__);
    while ($arr = $res->fetch_assoc()) {
        $postid = (int)$arr['lastpost'];
        if (!is_valid_id($arr['r_id'])) {
            sql_query("INSERT INTO read_posts (user_id, topic_id, last_post_read) VALUES".sqlesc($userid).", ".sqlesc($arr['id']).", ".sqlesc($postid).")") || sqlerr(__FILE__,
                __LINE__);
        } elseif ($arr['last_post_read'] < $postid) {
            sql_query("UPDATE read_posts SET last_post_read=".sqlesc($postid)." WHERE id =".sqlesc($arr['r_id'])) || sqlerr(__FILE__, __LINE__);
        }
    }
    $res->free();
    $mysqli->next_result();
}

// -------- Returns the minimum read/write class levels of a forum
function get_forum_access_levels($forumid)
{
    ($res = sql_query("SELECT min_class_read, min_class_write, min_class_create FROM forums WHERE id=".sqlesc($forumid))) || sqlerr(__FILE__,
        __LINE__);
    if ($res->num_rows != 1) {
        return false;
    }
    $arr = $res->fetch_assoc();
    return ["read" => $arr["min_class_read"], "write" => $arr["min_class_write"], "create" => $arr["min_class_create"]];
}

// -------- Returns the forum ID of a topic, or false on error
function get_topic_forum($topicid)
{
    ($res = sql_query("SELECT forum_id FROM topics WHERE id=".sqlesc($topicid))) || sqlerr(__FILE__, __LINE__);
    if ($res->num_rows != 1) {
        return false;
    }
    $arr = $res->fetch_assoc();
    return (int)$arr['forum_id'];
}

// -------- Returns the ID of the last post of a forum
function update_topic_last_post($topicid)
{
    ($res = sql_query("SELECT MAX(id) AS id FROM posts WHERE topic_id=".sqlesc($topicid))) || sqlerr(__FILE__, __LINE__);
    ($arr = $res->fetch_assoc()) || die("No post found");
    sql_query("UPDATE topics SET last_post=".sqlesc($arr['id'])." WHERE id=".sqlesc($topicid)) || sqlerr(__FILE__, __LINE__);
}

function get_forum_last_post($forumid)
{
    ($res = sql_query("SELECT MAX(last_post) AS last_post FROM topics WHERE forum_id=".sqlesc($forumid))) || sqlerr(__FILE__, __LINE__);
    $arr = $res->fetch_assoc();
    $postid = (int)$arr['last_post'];
    return (is_valid_id($postid) ? $postid : 0);
}

function subforums($arr)
{
    global $TRINITY20;
    $sub = "<font class='small'>Subforums:";
    $i = 0;
    foreach ($arr as $k) {
        $sub .= "&nbsp;<img src='{$TRINITY20['pic_base_url']}bullet_".($k["new"] == 1 ? "green.png" : "white.png")."' width='8' title='".($k["new"] == 1 ? "New posts" : "Not new")."' border='0' alt='Subforum' /><a href='{$TRINITY20['baseurl']}/forums.php?action=viewforum&amp;forumid=".(int)$k["id"]."'>".htmlsafechars($k["name"])."</a>".((is_countable($arr) ? count($arr) : 0) - 1 === $i ? "" : ",");
        $i++;
    }
    return $sub."</font>";
}

function get_count($arr)
{
    $topics = 0;
    $posts = 0;
    foreach ($arr as $k) {
        $topics += $k["topics"];
        $posts += $k["posts"];
    }
    return [$posts, $topics];
}

//== End subforum
//== Forum moderator - putyn
function isMod($id, $what = "topic")
{
    global $CURUSER;
    if ($what == "topic") {
        $topics = topicmods($CURUSER["id"], "", true);
        return (stristr($topics, "[".$id."]") == true);
    }

    if ($what == "forum") {
        return (stristr($CURUSER["forums_mod"], "[".$id."]") == true);
    } else {
        return false;
    }
}

function showMods($ars)
{
    $mods = "<font class='small'>Led by:&nbsp;";
    $i = 0;
    $count = is_countable($ars) ? count($ars) : 0;
    foreach ($ars as $a) {
        $mods .= "<a href='userdetails.php?id=".(int)$a[0]."'>".htmlsafechars($a[1])."</a>".($count - 1 === $i ? "" : " ,");
        $i++;
    }
    return $mods."</font>";
}

function forum_stats()
{
    global $TRINITY20, $cache;
    $htmlout = '';
    $cache_keys['f_activeusers'] = 'forum_activeusers';
    if (($forum_active_users_cache = $cache->get($cache_keys['f_activeusers'])) === false) {
        $dt = $_SERVER['REQUEST_TIME'] - 180;
        $htmlout = $forum_activeusers = '';
        $forum_active_users_cache = [];
        ($res = sql_query('SELECT id, username, class, donor, warned, enabled, chatpost, leechwarn, pirate, king '.
            'FROM users WHERE forum_access >= '.$dt.' '.
            'ORDER BY username ASC')) || sqlerr(__FILE__, __LINE__);
        $forum_actcount = $res->num_rows;
        while ($arr = $res->fetch_assoc()) {
            if ($forum_activeusers !== '') {
                $forum_activeusers .= ",\n";
            }
            $forum_activeusers .= '<b>'.format_username($arr).'</b>';
        }
        $forum_active_users_cache['activeusers'] = $forum_activeusers;
        $forum_active_users_cache['actcount'] = $forum_actcount;
        $cache->set($cache_keys['f_activeusers'], $forum_active_users_cache, $TRINITY20['expires']['forum_activeusers']);
    }
    if (!$forum_active_users_cache['activeusers']) {
        $forum_active_users_cache['activeusers'] = 'There have been no active users in the last 15 minutes.';
    }
    return $htmlout.('Active users on Forum:&nbsp;&nbsp;<span class="badge success disabled">'.$forum_active_users_cache["actcount"].'</span>
        <div class="callout">'.$forum_active_users_cache['activeusers'].'</div>');
}

function show_forums($forid, $subforums = false, $sfa = "", $mods_array = "", $show_mods = false)
{
    global $CURUSER, $TRINITY20, $Multi_forum;
    $mods_array = forummods();
    $htmlout = '';
    ($forums_res = sql_query("SELECT f.id, f.name, f.description, f.post_count, f.topic_count, f.min_class_read, p.added, p.topic_id, p.anonymous, p.user_id, p.id AS pid, u.id AS uid, u.username, u.class, u.donor, u.enabled, u.warned, u.chatpost, u.leechwarn, u.pirate, u.king, t.topic_name, t.last_post, r.last_post_read "."FROM forums AS f "."LEFT JOIN posts AS p ON p.id = (SELECT MAX(last_post) FROM topics WHERE forum_id = f.id) "."LEFT JOIN users AS u ON u.id = p.user_id "."LEFT JOIN topics AS t ON t.id = p.topic_id "."LEFT JOIN read_posts AS r ON r.user_id = ".sqlesc($CURUSER['id'])." AND r.topic_id = p.topic_id "."WHERE ".($subforums == false ? "f.forum_id = ".sqlesc($forid)." AND f.place =-1 ORDER BY f.forum_id ASC" : "f.place=".sqlesc($forid)." ORDER BY f.id ASC")."")) || sqlerr(__FILE__,
        __LINE__);
    $htmlout .= "<div class='divTable'>";
    while ($forums_arr = $forums_res->fetch_assoc()) {
        if ($CURUSER['class'] < $forums_arr["min_class_read"]) {
            continue;
        }
        $forumid = (int)$forums_arr["id"];
        $lastpostid = (int)$forums_arr['last_post'];
        $user_stuff = $forums_arr;
        $user_stuff['id'] = (int)$forums_arr['uid'];
        if ($subforums == false && !empty($sfa[$forumid])) {
            if (($sfa[$forumid]['last_post']['postid'] > $forums_arr['pid'])) {
                if ($sfa[$forumid]['last_post']["anonymous"] == "yes") {
                    if ($CURUSER['class'] < UC_STAFF && $sfa[$forumid]['last_post']['user_id'] != $CURUSER['id']) {
                        $lastpost1 = "Anonymous<br />";
                    } else {
                        $lastpost1 = "Anonymous[<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$sfa[$forumid]['last_--post']['userid']."'><b>".htmlsafechars($sfa[$forumid]['last_post']['user'])."</b></a>]<br />";
                    }
                } elseif ($sfa[$forumid]['last_post']["anonymous"] == "no") {
                    $lastpost1 = "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$sfa[$forumid]['last_post']['userid']."'><b>".htmlsafechars($sfa[$forumid]['last_post']['user'])."</b></a><br />";
                }
                $lastpost = "".get_date($sfa[$forumid]['last_post']['added'], 'LONG', 1,
                        0)."<br />"."by $lastpost1"."in <a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=".(int)$sfa[$forumid]['last_post']['topic']."&amp;page=p".(int)$sfa[$forumid]['last_post']['post_id']."#p".(int)$sfa[$forumid]['last_post']['post_id']."'><b>".htmlsafechars($sfa[$forumid]['last_post']['tname'])."</b></a>";
            } elseif (($sfa[$forumid]['last_post']['postid'] < $forums_arr['pid'])) {
                if ($forums_arr["anonymous"] == "yes") {
                    if ($CURUSER['class'] < UC_STAFF && $forums_arr["user_id"] != $CURUSER["id"]) {
                        $lastpost2 = "Anonymous<br />";
                    } else {
                        $lastpost2 = "Anonymous[<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$forums_arr["user_id"]."'><b>".format_username($user_stuff,
                                true)."</b></a>]<br />";
                    }
                } elseif ($forums_arr["anonymous"] == "no") {
                    $lastpost2 = "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$forums_arr["user_id"]."'><b>".format_username($user_stuff,
                            true)."</b></a><br />";
                }
                $lastpost = "".get_date($forums_arr["added"], 'LONG', 1,
                        0)."<br />"."by $lastpost2"."in <a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=".(int)$forums_arr["topic_id"]."&amp;page=p$lastpostid#p$lastpostid'><b>".htmlsafechars($forums_arr['topic_name'])."</b></a>";
            } else {
                $lastpost = "N/A";
            }
        } elseif (is_valid_id($forums_arr['pid'])) {
            if ($forums_arr["anonymous"] == "yes") {
                if ($CURUSER['class'] < UC_STAFF && $forums_arr["user_id"] != $CURUSER["id"]) {
                    $lastpost = "".get_date($forums_arr["added"], 'LONG', 1,
                            0)."<br />"."by <i>Anonymous</i><br />"."in <a href='".$TRINITY20['baseurl']."/forums.php?action=viewtopic&amp;topicid=".(int)$forums_arr["topic_id"]."&amp;page=p$lastpostid#p$lastpostid'><b>".htmlsafechars($forums_arr['topic_name'])."</b></a>";
                } else {
                    $lastpost = "".get_date($forums_arr["added"], 'LONG', 1,
                            0)."<br />"."by <i>Anonymous[</i><a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$forums_arr["user_id"]."'><b>".format_username($user_stuff,
                            true)."</b></a>]<br />"."in <a href='{$TRINITY20['baseurl']}/forums.php??action=viewtopic&amp;topicid=".(int)$forums_arr["topic_id"]."&amp;page=p$lastpostid#p$lastpostid'><b>".htmlsafechars($forums_arr['topic_name'])."</b></a>";
                }
            } else {
                $lastpost = "<span class='smalltext'><a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=".(int)$forums_arr["topic_id"]."&amp;page=p$lastpostid#p$lastpostid'>".htmlsafechars($forums_arr['topic_name'])."</a><br />"."".get_date($forums_arr["added"],
                        'LONG', 1,
                        0)."<br />"."by <a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$forums_arr["user_id"]."'>".format_username($user_stuff,
                        true)."</a> ";
            }
        } else {
            $lastpost = "N/A";
        }
        $image_to_use = ($forums_arr['added'] > (TIME_NOW - $TRINITY20['readpost_expiry'])) ? ((int)$forums_arr['pid'] > $forums_arr['last_post_read']) : 0;
        if (is_valid_id($forums_arr['pid'])) {
            $img = ($image_to_use ? '<span class="forum_status forum_on ajax_mark_read" title="Forum Contains New Posts" ></span>' : '<span class="forum_status forum_off ajax_mark_read" title="Forum Contains No New Posts" ></span>');
        } else {
            $img = "<span class='forum_status forum_offlock ajax_mark_read' title='Forum Contains No Posts' ></span>";
        }
        if ($subforums == false && !empty($sfa[$forumid])) {
            [$subposts, $subtopics] = get_count($sfa[$forumid]["count"]);
            $topics = $forums_arr["topic_count"] + $subtopics;
            $posts = $forums_arr["post_count"] + $subposts;
        } else {
            $topics = (int)$forums_arr["topic_count"];
            $posts = (int)$forums_arr["post_count"];
        }
        $htmlout .= "<div class='divTableBody'>
        <div class='divTableRow'>
            <div class='divTableCell'>".$img."</div>
            <div class='divTableCell'>
                <a href='{$TRINITY20['baseurl']}/forums.php?action=viewforum&amp;forumid=".$forumid."'>
                    <strong>".htmlsafechars($forums_arr["name"])."</strong>
                </a>
                <br>".((empty($forums_arr["description"])) ? '' : htmlsafechars($forums_arr["description"]))."<br>
                ".(($subforums == false && !empty($sfa[$forumid])) ? subforums($sfa[$forumid]["topics"]) : '')."
                ".(($show_mods == true && isset($mods_array[$forumid])) ? showMods($mods_array[$forumid]) : '')."
            </div>
            <div class='divTableCell'>
                <p>
                    ".number_format($posts)." posts<br>
                    ".number_format($topics)." topics
                </p>
            </div>
            <div class='divTableCell'>
                ".$lastpost."
            </div>";
        if ($CURUSER['class'] >= UC_ADMINISTRATOR || isMod($forumid, "forum")) {
            $htmlout .= "<div class='divTableCell'>
                    <a href='{$TRINITY20['baseurl']}/forums.php?action=editforum&amp;forumid=".$forumid."'><i class='fas fa-edit'></i></a>
                    <a href='{$TRINITY20['baseurl']}/forums.php?action=deleteforum&amp;forumid=".$forumid."'><i class='fas fa-eraser'></i></a>
                </div>";
        }
        $htmlout .= "</div>
    </div>";
    }
    return $htmlout."</div>";
}

if (!function_exists('highlight')) {
    function highlight($search, $subject, $hlstart = '<b><font color=\"red\">', $hlend = '</font></b>')
    {
        $srchlen = strlen($search); // length of searched string
        if ($srchlen == 0) {
            return $subject;
        }
        $find = $subject;
        while ($find = stristr($find, (string)$search)) { // find $search text in $subject - case insensitive
            $srchtxt = substr($find, 0, $srchlen); // get new search text
            $find = substr($find, $srchlen);
            $subject = str_replace($srchtxt, $hlstart.$srchtxt.$hlend, $subject); // highlight founded case insensitive search text
        }
        return $subject;
    }
}
