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
$userid = (int)$CURUSER["id"];
if ($Multi_forum['configs']['use_poll_mod'] && $_SERVER['REQUEST_METHOD'] == "POST") {
    $choice = htmlsafechars($_POST['choice']);
    $pollid = (int)$_POST["pollid"];
    if (ctype_digit($choice) && $choice < 256 && $choice == floor($choice)) {
        ($res = sql_query("SELECT pa.id "."FROM postpolls AS p "."LEFT JOIN postpollanswers AS pa ON pa.pollid = p.id AND pa.userid=".sqlesc($userid)." "."WHERE p.id = ".sqlesc($pollid))) || sqlerr(__FILE__,
            __LINE__);
        ($arr = $res->fetch_assoc()) || stderr('Sorry', 'Inexistent poll!');
        if (is_valid_id($arr['id'])) {
            stderr("Error...", "Dupe vote");
        }
        sql_query("INSERT INTO postpollanswers (pollid, userid, selection) VALUES(".sqlesc($pollid).", ".sqlesc($userid).", ".sqlesc($choice).")") || sqlerr(__FILE__,
            __LINE__);
        if ($mysqli->affected_rows() != 1) {
            stderr("Error...", "An error occured. Your vote has not been counted.");
        }
    } else {
        stderr("Error...", "Please select an option.");
    }
}
$topicid = (int)$_GET["topicid"];
if (!is_valid_id($topicid)) {
    stderr('Error', 'Invalid topic ID!');
}
$page = (isset($_GET["page"]) ? (int)$_GET["page"] : 0);
// ------ Get topic info
($res = sql_query("SELECT ".($Multi_forum['configs']['use_poll_mod'] ? 't.poll_id, ' : '')."t.locked, t.num_ratings, t.rating_sum,  t.topic_name, t.sticky, t.user_id AS t_userid, t.forum_id, f.name AS forum_name, f.min_class_read, f.min_class_write, f.min_class_create, (SELECT COUNT(id)FROM posts WHERE topic_id = t.id) AS p_count "."FROM topics AS t "."LEFT JOIN forums AS f ON f.id = t.forum_id "."WHERE t.id = ".sqlesc($topicid))) || sqlerr(__FILE__,
    __LINE__);
($arr = $res->fetch_assoc()) || stderr("Error", "Topic not found");
((bool)$res->free_result());
($Multi_forum['configs']['use_poll_mod'] ? $pollid = (int)$arr["poll_id"] : null);
$t_userid = (int)$arr['t_userid'];
$locked = ($arr['locked'] == 'yes');
$subject = $arr['topic_name'];
$sticky = ($arr['sticky'] == "yes");
$forumid = (int)$arr['forum_id'];
$forum = htmlsafechars($arr["forum_name"]);
$postcount = (int)$arr['p_count'];
if ($CURUSER["class"] < $arr["min_class_read"]) {
    stderr("Error", "You are not permitted to view this topic.");
}
// ------ Update hits column
sql_query("UPDATE topics SET views = views + 1 WHERE id=".sqlesc($topicid)) || sqlerr(__FILE__, __LINE__);
//------ Make page menu
$count = (int)$arr['p_count'];
$perpage = $Multi_forum['configs']['postsperpage'];
[$pager_menu, $LIMIT] = pager_new($count, $perpage, $page, 'forums.php?action=viewtopic&amp;topicid='.$topicid .($perpage == 20 ? '' : '&amp;perpage='.$perpage));

$HTMLOUT .= '
<ul class="breadcrumbs">
  <li class="disabled">'.$TRINITY20['site_name'].'</li>
  <li><a href="forums.php">Forums</a></li>
  <li><a href='.$TRINITY20['baseurl'].'/forums.php?action=viewforum&amp;forumid='.$forumid.'">'.$forum.'</a></li>
  <li>
    <span class="show-for-sr">Current: </span> '.htmlsafechars($subject).'
  </li>
</ul>';
if ($Multi_forum['configs']['use_poll_mod'] && is_valid_id($pollid)) {
    ($res = sql_query("SELECT p.*, pa.id AS pa_id, pa.selection FROM postpolls AS p LEFT JOIN postpollanswers AS pa ON pa.pollid = p.id AND pa.userid = ".sqlesc($CURUSER['id'])." WHERE p.id=".sqlesc($pollid))) || sqlerr(__FILE__,
        __LINE__);
    if ($res->num_rows > 0) {
        $arr1 = $res->fetch_assoc();
        $userid = (int)$CURUSER['id'];
        $question = htmlsafechars($arr1["question"]);
        $o = [
            $arr1["option0"],
            $arr1["option1"],
            $arr1["option2"],
            $arr1["option3"],
            $arr1["option4"],
            $arr1["option5"],
            $arr1["option6"],
            $arr1["option7"],
            $arr1["option8"],
            $arr1["option9"],
            $arr1["option10"],
            $arr1["option11"],
            $arr1["option12"],
            $arr1["option13"],
            $arr1["option14"],
            $arr1["option15"],
            $arr1["option16"],
            $arr1["option17"],
            $arr1["option18"],
            $arr1["option19"],
        ];
        $HTMLOUT .= "<div class='card'>
            <div class='card-divider'><strong>Poll: {$question}</strong></div>
                <div class='card-section'>";
        $voted = ((bool)is_valid_id($arr1['pa_id']));
        if (($locked && $CURUSER['class'] < UC_STAFF) ? true : $voted) {
            $uservote = ($arr1["selection"] != '' ? (int)$arr1["selection"] : -1);
            $res_v = sql_query("SELECT selection FROM postpollanswers WHERE pollid=".sqlesc($pollid)." AND selection < 20");
            $tvotes = $res_v->num_rows;
            $vs = $os = [];
            for ($i = 0; $i < 20; $i++) {
                $vs[$i] = 0;
            }
            while ($arr_v = $res_v->fetch_row()) {
                $vs[$arr_v[0]] += 1;
            }
            reset($o);
            $oCount = count($o);
            for ($i = 0; $i < $oCount; ++$i) {
                if ($o[$i]) {
                    $os[$i] = [
                        $vs[$i],
                        $o[$i],
                    ];
                }
            }
            function srt($a, $b)
            {
                if ($a[0] > $b[0]) {
                    return -1;
                }
                if ($a[0] < $b[0]) {
                    return 1;
                }
                return 0;
            }

            if ($arr1["sort"] == "yes") {
                usort($os, "srt");
            }
            $HTMLOUT .= "<br>
              ";
            foreach ($os as $a) {
                if ($i === $uservote) {
                    $a[1] .= " *";
                }
                $p = ($tvotes == 0 ? 0 : round($a[0] / $tvotes * 100));
                $c = ($i % 2 !== 0 ? '' : "poll");
                $p = ($tvotes == 0 ? 0 : round($a[0] / $tvotes * 100));
                $c = ($i % 2 !== 0 ? '' : "poll");
                $HTMLOUT .= "<label>".htmlsafechars($a[1])."</label>
                <div class='progress' role='progressbar' tabindex='0' aria-valuenow='".$p."' aria-valuemin='0' aria-valuetext='25 percent' aria-valuemax='100'>
                    <span class='progress-meter' style='width: ".$p."%'>
                        <span class='progress-meter-text'>".$p."%</span>
                    </span>
                </div>";
            }
            $HTMLOUT .= "<label>Votes:".number_format($tvotes)."</label>";
        } else {
            $HTMLOUT .= "<form method='post' action='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=".$topicid."'>
                  <input type='hidden' name='pollid' value='".$pollid."'>";
            for ($i = 0; $a = $o[$i]; ++$i) {
                $HTMLOUT .= "<input type='radio' name='choice' value='$i'>".htmlsafechars($a)."
                            <input class='tiny button' type='submit' value='Vote!'></form>";
            }
        }
        if ($userid === $t_userid || $CURUSER['class'] >= UC_STAFF) {
            $HTMLOUT .= "<a class='tiny button' href='{$TRINITY20['baseurl']}/forums.php?action=makepoll&amp;subaction=edit&amp;pollid=".$pollid."'><strong>Edit</strong></a>";
            if ($CURUSER['class'] >= UC_STAFF) {
                $HTMLOUT .= "<a class='tiny button' href='{$TRINITY20['baseurl']}/forums.php?action=deletepoll&amp;pollid=".$pollid."'><strong>Delete</strong></a>";
            }
        }
        $listvotes = (isset($_GET['listvotes']));
        if ($CURUSER['class'] >= UC_ADMINISTRATOR) {
            if (!$listvotes) {
                $HTMLOUT .= "<a class='tiny button' href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=$topicid&amp;listvotes'><strong>List Voters</strong></a>";
            } else {
                ($res_vv = sql_query("SELECT pa.userid, u.username, u.anonymous FROM postpollanswers AS pa LEFT JOIN users AS u ON u.id = pa.userid WHERE pa.pollid=".sqlesc($pollid))) || sqlerr(__FILE__,
                    __LINE__);
                $voters = '';
                while ($arr_vv = $res_vv->fetch_assoc()) {
                    if (!empty($voters) && !empty($arr_vv['username'])) {
                        $voters .= ', ';
                    }
                    if ($arr_vv["anonymous"] == "yes") {
                        if ($CURUSER['class'] < UC_STAFF && $arr_vv["userid"] != $CURUSER["id"]) {
                            $voters = "<i>Anonymous</i>";
                        } else {
                            $voters = "<i>Anonymous</i>[<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$arr_vv['userid']."'><strong>".htmlsafechars($arr_vv['username'])."</strong></a>]";
                        }
                    } else {
                        $voters .= "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$arr_vv['userid']."'><strong>".htmlsafechars($arr_vv['username'])."</strong></a>";
                    }
                }
                $HTMLOUT .= $voters."['><a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=$topicid'>hide</a>]";
            }
        }
        $HTMLOUT .= "</div></div>";
    } else {
        stderr('Sorry', "Poll doesn't exist");
    }
}
$maypost = $CURUSER['class'] >= isset($arr["min_class_write"]) && $CURUSER['class'] >= isset($arr["min_class_create"]);
if ($locked && $CURUSER['class'] < UC_STAFF && !isMod($forumid, "forum")) {
    $HTMLOUT .= "<p align='center'>This topic is locked; no new posts are allowed.</p>";
} else {
    $writearr = get_forum_access_levels($forumid);
    if ($CURUSER['class'] < $writearr["write"]) {
        $HTMLOUT .= "<p align='center'><i>You are not permitted to post in this forum.</i></p>";
        $maypost = false;
    } else {
        $maypost = true;
    }
}
$HTMLOUT .= '<div class ="clearfix">
    <div class="float-left">'.$pager_menu.'</div>
    <div class="float-right primary button-group small">
    <a class="button" href="'.$TRINITY20['baseurl'].'/subscriptions.php?topicid='.$topicid.'&amp;subscribe=1">Subscribe to Forum</a>
    <a href="forums.php?action=viewunread" class="button">Show Unread</a>';
    if ($maypost) {
        $HTMLOUT .= '<a href="forums.php?action=reply&topicid='.$topicid.'" class="button"><i class="fa fa-check-square"></i>New Reply</a>';
    } else {
        $HTMLOUT .= '<a class="button"><i class="fa fa-check-square"></i> No Permissions</a>';
    }
    $HTMLOUT .="</div>
</div>";
//=== who is here
sql_query('DELETE FROM now_viewing WHERE user_id ='.sqlesc($CURUSER['id']));
sql_query('INSERT INTO now_viewing (user_id, forum_id, topic_id, added) VALUES('.sqlesc($CURUSER['id']).', '.sqlesc($forumid).', '.sqlesc($topicid).', '.TIME_NOW.')');
//=== now_viewing
$cache_keys['now_viewing'] = 'now_viewing_topic';
if (($topic_users_cache = $cache->get($cache_keys['now_viewing'])) === false) {
    $topicusers = '';
    $topic_users_cache = [];
    ($res = sql_query('SELECT n_v.user_id, u.id, u.username, u.class, u.donor, u.suspended, u.warned, u.enabled, u.chatpost, u.leechwarn, u.pirate, u.king, u.perms FROM now_viewing AS n_v LEFT JOIN users AS u ON n_v.user_id = u.id WHERE topic_id = '.sqlesc($topicid))) || sqlerr(__FILE__,
        __LINE__);
    $actcount = $res->num_rows;
    while ($arr = $res->fetch_assoc()) {
        if ($topicusers !== '') {
            $topicusers .= ",\n";
        }
        $topicusers .= (($arr['perms'] & bt_options::PERMS_STEALTH) !== 0 ? '<i>UnKn0wn</i>' : format_username($arr));
    }
    $topic_users_cache['topic_users'] = $topicusers;
    $topic_users_cache['actcount'] = $actcount;
    $cache->set($cache_keys['now_viewing'], $topic_users_cache, $TRINITY20['expires']['forum_users']);
}

if (!$topic_users_cache['topic_users']) {
    $topic_users_cache['topic_users'] = 'There have been no active users in the last 15 minutes.';
}
$topic_users = $topic_users_cache['topic_users'];
if ($topic_users != '') {
    $topic_users = 'Currently viewing this topic: '.$topic_users;
}
$HTMLOUT .= "<script type='text/javascript'>
/*<![CDATA[*/
function confirm_att(id) {
    if(confirm('Are you sure you want to delete this ?')) {
        window.open('{$TRINITY20['baseurl']}/forums.php?action=attachment&amp;subaction=delete&amp;attachmentid='+id,'attachment','toolbar=no, scrollbars=yes, resizable=yes, width=600, height=250, top=50, left=50');
        window.location.reload(true)
    }
}
function popitup(url) {
    newwindow=window.open(url,'./usermood.php','height=335,width=735,resizable=no,scrollbars=no,toolbar=no,menubar=no');
    if (window.focus) {newwindow.focus()}
    return false;
}
/*]]>*/
</script>
<div class='card'>
    <div class='card-divider'>".htmlsafechars($subject)."</div>
    <div class='card-section'>
        <div class='callout'>{$topic_users}
        <span class='float-right'>". (getRate($topicid, "topic"))."</span></div>";
    $HTMLOUT .= "<a id='top'></a>";
    $HTMLOUT .= '<div class="card-section">';
    ($res = sql_query("SELECT p.id, p.added, p.user_id, p.added, p.body, p.edited_by, p.edit_date, p.icon, p.anonymous as p_anon, p.user_likes, u.id AS uid, u.username as uusername, u.class, u.avatar, u.offensive_avatar, u.donor, u.title, u.username, u.reputation, u.mood, u.anonymous, u.country, u.enabled, u.warned, u.chatpost, u.leechwarn, u.pirate, u.king, u.uploaded, u.downloaded, u.signature, u.last_access, (SELECT COUNT(id)  FROM posts WHERE user_id = u.id) AS posts_count, u2.username as u2_username ".($Multi_forum['configs']['use_attachment_mod'] ? ", at.id as at_id, at.file_name as at_filename, at.post_id as at_postid, at.size as at_size, at.times_downloaded as at_downloads, at.user_id as at_owner " : "").", (SELECT last_post_read FROM read_posts WHERE user_id = ".sqlesc((int)$CURUSER['id'])." AND topic_id = p.topic_id LIMIT 1) AS last_post_read "."FROM posts AS p "."LEFT JOIN users AS u ON p.user_id = u.id ".($Multi_forum['configs']['use_attachment_mod'] ? "LEFT JOIN attachments AS at ON at.post_id = p.id " : "")."LEFT JOIN users AS u2 ON u2.id = p.edited_by "."WHERE p.topic_id = ".sqlesc($topicid)." ORDER BY id " . $LIMIT)) || sqlerr(__FILE__,
    __LINE__);
    $pc = $res->num_rows;
    $pn = 0;
    while ($arr = $res->fetch_assoc()) {
        ++$pn;
        // --------------- likes start------
        $att_str = '';
        $likes = empty($arr['user_likes']) ? '' : explode(',', $arr['user_likes']);
        if (!empty($likes) && count(array_unique($likes)) > 0) {
            if (in_array($CURUSER['id'], $likes)) {
                if ((is_countable($likes) ? count($likes) : 0) == 1) {
                    $att_str = jq('You like this');
                } elseif (count(array_unique($likes)) > 1) {
                    $att_str = jq('You and&nbsp;').((count(array_unique($likes)) - 1) == '1' ? '1 other person likes this' : ((is_countable($likes) ? count($likes) : 0) - 1).'&nbsp;others like this');
                }
            } elseif (!(in_array($CURUSER['id'], $likes))) {
                if (count(array_unique($likes)) == 1) {
                    $att_str = '1 other person likes this';
                } elseif (count(array_unique($likes)) > 1) {
                    $att_str = (count(array_unique($likes))).'&nbsp;others like this';
                }
            }
        }
        $wht = ((!empty($likes) && count(array_unique($likes)) > 0 && in_array($CURUSER['id'], $likes)) ? 'unlike' : 'like');
        // --------------- likes end------
        $lpr = (int)$arr['last_post_read'];
        $postid = (int)$arr["id"];
        $postadd = (int)$arr['added'];
        $posterid = (int)$arr['user_id'];
        $posticon = ($arr["icon"] > 0 ? "<img src='{$TRINITY20['pic_base_url']}post_icons/icon".htmlsafechars($arr["icon"]).".gif' style='padding-left:3px;' alt='post icon' title='post icon'>" : "&nbsp;");
        $added = get_date($arr['added'], 'DATE', 1, 0)." GMT <font class='small'>(".(get_date($arr['added'], 'LONG', 1, 0)).")</font>";
        // ---- Get poster details
        $uploaded = mksize($arr['uploaded']);
        $downloaded = mksize($arr['downloaded']);
        $member_reputation = $arr['uusername'] != '' ? get_reputation($arr, 'posts', true, $postid) : '';
        $last_access = get_date($arr['last_access'], 'DATE', 1, 0);
        $Ratio = member_ratio($arr['uploaded'], $TRINITY20['ratio_free'] ? '0' : $arr['downloaded']);
        if (($postid > $lpr) && ($postadd > (TIME_NOW - $TRINITY20['readpost_expiry']))) {
            $newp = "&nbsp;&nbsp;<span class='badge button warning disabled'>NEW</span>";
        }
        $moodname = (isset($mood['name'][$arr['mood']]) ? htmlsafechars($mood['name'][$arr['mood']]) : 'is feeling neutral');
        $moodpic = (isset($mood['image'][$arr['mood']]) ? htmlsafechars($mood['image'][$arr['mood']]) : 'noexpression.gif');
        $signature = ($CURUSER['signatures'] == 'yes' ? format_comment($arr['signature']) : '');
        $user_stuff = $arr;
        $user_stuff['id'] = (int)$arr['uid'];
        $postername = format_username($user_stuff, true);
        $width = '75';
        $avatar = ($CURUSER["avatars"] == "yes" ? (($arr['p_anon'] == 'yes' && $CURUSER['class'] < UC_STAFF) ? '<img style="max-width:'.$width.'px;" src="'.$TRINITY20['pic_base_url'].'anonymous_1.jpg" alt="avatar">' : avatar_stuff($arr)) : "");
        $title2 = (empty($postername) ? ('') : (empty($arr['title']) ? "(".get_user_class_name($arr['class']).")" : "(".(htmlsafechars($arr['title'])).")"));
        $title = ($arr['p_anon'] == 'yes' ? '<i>Anonymous</i>' : htmlsafechars($title2));
        $class_name = (($arr['p_anon'] == 'yes' && $CURUSER['class'] < UC_STAFF) ? "Anonymous" : get_user_class_name($arr["class"]));
        $forumposts = (empty($postername) ? ('N/A') : ($arr['posts_count'] != 0 ? (int)$arr['posts_count'] : 'N/A'));
        if ($arr["p_anon"] == "yes") {
            if ($CURUSER['class'] < UC_STAFF) {
                $by = "<i>Anonymous</i>";
            } else {
                $by = "<i>Anonymous</i> [<a href='{$TRINITY20['baseurl']}/userdetails.php?id=$posterid'> ".$postername."</a>]".($arr['enabled'] == 'no' ? "<img src='".$TRINITY20['pic_base_url']."disabled.gif' alt='This account is disabled' style='margin-left: 2px'>" : '')."$title";
            }
        } else {
            $by = (empty($postername) ? "unknown[".$posterid."]" : "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=$posterid'>".$postername."</a>".($arr['enabled'] == 'no' ? "<img src='".$TRINITY20['pic_base_url']."disabled.gif' alt='This account is disabled' style='margin-left: 2px'>" : ''))."";
        }
        if (empty($avatar)) {
            $avatar = "<img class='user-image' src='".$TRINITY20['pic_base_url'].$Multi_forum['configs']['forum_pics']['default_avatar']."'>";
        }
        $HTMLOUT .= ($pn == $pc ? '<a id="last"></a>' : '');
            $HTMLOUT .= '<div id="posts">
                <a id="'.$postid.'" id="'.$postid.'"></a>
                <div class="post" style="" id="post_'.$postid.'">
                    <div class="grid-x grid-margin-x">
                        <div class="cell large-2">
                            <div class="card-user-container">
                                <div class="card-user-avatar hide-on-medium">'.$avatar.'</div>
                                <div class="user-tabs">
                                <div class="user-tab">
                                    <input type="checkbox" hidden id="chck_'.$posterid.'_'.$postid.'">
                                    <label class="user-tab-label float-center" for="chck_'.$posterid.'_'.$postid.'">'.$by.'</label>
                                    <div class="user-tab-content">';
                                    if ($TRINITY20['mood_sys_on']) {
                                        $HTMLOUT .= '<!-- Mood -->
                                            <span class="smalltext"><a href="javascript:;" onclick="PopUp("usermood.php","Mood",530,500,1,1);"><img src="'.$TRINITY20['pic_base_url'].'smilies/'.$moodpic.'" alt="'.$moodname.'" border="0"></a>
                                            <span class="tip">'.(($arr['p_anon'] == 'yes' && $CURUSER['class'] < UC_STAFF) ? '<i>Anonymous</i>' : htmlsafechars($arr['username'])).' '.$moodname.' !</span>&nbsp;</span>';
                                    }
                                    if (($arr["p_anon"] == "yes") && $CURUSER['class'] < UC_STAFF &&        $posterid != $CURUSER["id"]) {
                                        $HTMLOUT .= "";
                                    } else {
                                        $HTMLOUT .= "<span class='smalltext'>Posts:{$forumposts}</span><br>
                                        <span class='smalltext'>Ratio:&nbsp;{$Ratio}</span><br>
                                        <span class='smalltext'>Uploaded:&nbsp;{$uploaded}</span><br>
                                        <span class='smalltext'>Downloaded:&nbsp;{$downloaded}</span><br>
                                        ".($TRINITY20['rep_sys_on'] ? $member_reputation : "")."";
                                    }
                                    $HTMLOUT .= '</div>
                                </div>
                            </div>';
                            $HTMLOUT .= "</div>
                    </div>
                    <div class='cell large-10'>
                    <div class='card'>
                        <div class='card-divider'>
                            <div class='float-right'>
                                <strong><a id='p".$postid."' name='p{$postid}' href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=".$topicid."&amp;page=p".$postid."#".$postid."'>#".$postid."</a></strong>
                                </div>
                            {$posticon}&nbsp;
                            <span class='post_date'>".$added."&nbsp;
                                <span id='mlike' data-com='".(int)$arr["id"]."' class='forum {$wht}'>[".ucfirst($wht)."]</span>
                                <span class='tot-".(int)$arr["id"]."' data-tot='".(!empty($likes) && count(array_unique($likes)) > 0 ? count(array_unique($likes)) : '')."'>&nbsp;{$att_str}</span>";
                                if (is_valid_id($arr['edited_by'])) {
                                    $HTMLOUT .= "<span class='post_edit' id='edited_by_14'><font size='1' class='small'>Last edited by <a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$arr['edited_by']."'><strong>".htmlsafechars($arr['u2_username'])."</strong></a> at ".get_date($arr['edit_date'],
                                            'LONG', 1, 0)." GMT</font></span>";
                                }
                            $HTMLOUT .= "</span>
                        </div>";
                        if (isset($newp)) {
                            $HTMLOUT .= $newp;
                        }
                        $highlight = (isset($_GET['highlight']) ? htmlsafechars($_GET['highlight']) : '');
                        $body = (empty($highlight) ? format_comment($arr['body']) : highlight(htmlsafechars(trim($highlight)), format_comment($arr['body'])));
                        if ($Multi_forum['configs']['use_attachment_mod'] && ((!empty($arr['at_filename']) && is_valid_id($arr['at_id'])) && $arr['at_postid'] == $postid)) {
                            foreach ($Multi_forum['configs']['allowed_file_extensions'] as $allowed_file_extension) {
                                if (substr($arr['at_filename'], -2) || substr($arr['at_filename'], -3) == $allowed_file_extension) {
                                    $aimg = $allowed_file_extension;
                                }
                            }
                            $body .= "<div>
                                    <fieldset class='fieldset'>
                                        <legend>Attached Files</legend>
                                        <table cellpadding='0' cellspacing='3' border='0'>
                                            <tr>
                                            <td><img class='inlineimg' src='{$TRINITY20['pic_base_url']}$aimg.gif' alt='' width='16' height='16' border='0' style='vertical-align:baseline'>&nbsp;</td>
                                            <td><a href='{$TRINITY20['baseurl']}/forums.php?action=attachment&amp;attachmentid=".(int)$arr['at_id']."' target='_blank'>".htmlsafechars($arr['at_filename'])."</a> [".mksize($arr['at_size']).", ".(int)$arr['at_downloads']." downloads]</td>
                                            <td>&nbsp;&nbsp;<input type='button' class='none' value='See who downloaded' tabindex='1' onclick='window.open('{$TRINITY20['baseurl']}/forums.php?action=whodownloaded&amp;fileid=".(int)$arr['at_id']."','whodownloaded','toolbar=no, scrollbars=yes, resizable=yes, width=600, height=250, top=50, left=50'); return false;'>".($CURUSER['class'] >= UC_STAFF ? "&nbsp;&nbsp;<input type='button' class='gobutton' value='Delete' tabindex='2' onclick='window.open('{$TRINITY20['baseurl']}/forums.php?action=attachment&amp;subaction=delete&amp;attachmentid=".(int)$arr['at_id']."','attachment','toolbar=no, scrollbars=yes, resizable=yes, width=600, height=250, top=50, left=50'); return false;'>" : "")."</td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                </div>";
                        }
                        if (!empty($signature) && $arr["p_anon"] == "no") {
                            $body .= "<p style='vertical-align:bottom'><br>____________________<br>".$signature."</p>";
                        }
                        $HTMLOUT .= "<div>{$body}</div>
                    </div>
                    <div class='clearfix'>
                    <div class='button-group small float-left'>";
                        if (($arr["p_anon"] == "yes") && $CURUSER['class'] < UC_STAFF) {
                            $HTMLOUT .= "";
                        } else {
                            $HTMLOUT .= "<a href='{$TRINITY20['baseurl']}/pm_system.php?action=send_message&amp;receiver=".$posterid."' title='Send this user a private message' class='button'><span>PM</span></a>";
                        }
                        $HTMLOUT .= "<a href='{$TRINITY20['baseurl']}/report.php?type=Post&amp;id=".$postid."&amp;id_2=".$topicid."&amp;id_3=".$posterid."' title='Report this post to a moderator' class='button'><span>Report</span></a>
                    </div>
                    <div class='button-group small float-right'>";
                        if (!$locked || $CURUSER['class'] >= UC_STAFF || isMod($forumid, "forum")) {
                            if ($arr["p_anon"] == "yes") {
                                if ($CURUSER['class'] < UC_STAFF) {
                                    $HTMLOUT .= "";
                                }
                            } else {
                                $HTMLOUT .= "<a href='{$TRINITY20['baseurl']}/forums.php?action=quotepost&amp;topicid=".$topicid."&amp;postid=".$postid."' class='button' ><span>Quote</span></a>";
                            }
                        } else {
                            $HTMLOUT .= "<a href='{$TRINITY20['baseurl']}/forums.php?action=quotepost&amp;topicid=".$topicid."&amp;postid=".$postid."' class='button'><span>Quote</span></a>";
                        }
                        if ($CURUSER['class'] >= UC_STAFF || isMod($forumid, "forum")) {
                            $HTMLOUT .= "<a href='{$TRINITY20['baseurl']}/forums.php?action=deletepost&amp;postid=".$postid."' class='button'><span>Delete</span></a>";
                        }
                        if (($CURUSER["id"] == $posterid && !$locked) || $CURUSER['class'] >= UC_STAFF || isMod($forumid, "forum")) {
                            $HTMLOUT .= "<a href='{$TRINITY20['baseurl']}/forums.php?action=editpost&amp;postid=".$postid."' class='button'><span>Edit</span></a>";
                        }
                        $HTMLOUT .= "<a class='button' href='#top'><i class='fas fa-arrow-up float-right'></i></a>";
                    $HTMLOUT .= '</div>
                </div>
                </div>
            </div>
        </div>';
    }
$HTMLOUT .= "</div></div>";
//end of posts
if ($locked) {
    $HTMLOUT .= "";
} else {
    if ($Multi_forum['configs']['use_poll_mod'] && (($userid === $t_userid || $CURUSER['class'] >= UC_STAFF || isMod($forumid,"forum")) && !is_valid_id($pollid))) {
        $HTMLOUT .= "<form id='addApoll' method='post' action='forums.php'>
            <input type='hidden' name='action' value='makepoll'>
            <input type='hidden' name='topicid' value='".$topicid."'>
        </form>";
    }
    $HTMLOUT .= insert_quick_jump_menu($forumid);
    $HTMLOUT .= '<div class ="clearfix">
        <div class="float-left">'.$pager_menu.'</div>
        <div class="float-right primary button-group small">';
        // ------ "View unread" / "Add reply" buttons
        $HTMLOUT .= "
        <button class='button' type='submit' form='addApoll'>Add a Poll</button>
        <a href='forums.php?action=viewunread' class='button'>Show Unread</a>";
        if ($maypost) {
            $HTMLOUT .= '
            <a href="forums.php?action=reply&topicid='.$topicid.'" class="button"><i class="fa fa-check-square"></i>New Reply</a>';
        } else {
            $HTMLOUT .= '<a class="button"><i class="fa fa-check-square"></i> No Permissions</a>';
        }
        $HTMLOUT .= "</div>
        </div>
        <div class='card'>
            <div class='card-divider'>Quick Reply</div>
            <div class='card-section'>".insert_compose_frame($topicid, false, false, true)."</div>
        </div>";
}
if (($postid > $lpr) && ($postadd > (TIME_NOW - $TRINITY20['readpost_expiry']))) {
    if ($lpr) {
        sql_query("UPDATE read_posts SET last_post_read=".sqlesc($postid)." WHERE user_id=".sqlesc($userid)." AND topic_id=".sqlesc($topicid)) || sqlerr(__FILE__,
            __LINE__);
    } else {
        sql_query("INSERT INTO read_posts (user_id, topic_id, last_post_read) VALUES(".sqlesc($userid).", ".sqlesc($topicid).", ".sqlesc($postid).")") || sqlerr(__FILE__,
            __LINE__);
    }
}
// ------ Mod options
if ($CURUSER['class'] >= UC_STAFF || isMod($forumid, "forum")) {
    require_once FORUM_DIR."/mod_panel.php";
}
if (isMod($topicid)) {
    $CURUSER['class'] = UC_STAFF;
}
echo stdhead("Forums :: View Topic: $subject", true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
$uploaderror = (isset($_GET['uploaderror']) ? htmlsafechars($_GET['uploaderror']) : '');
if (!empty($uploaderror)) {
    $HTMLOUT .= "<script>alert('Upload Failed: ".$uploaderror." However your post was successful saved! Click \'OK\' to continue.');</script>";
}
exit();