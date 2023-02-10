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
// -------- Action: View forum
$forumid = isset($_GET['forumid']) ? (int)$_GET['forumid'] : 0;
if (!is_valid_id($forumid)) {
    stderr('Error', 'Invalid ID!');
}
$page = (isset($_GET['page']) && is_numeric($_GET['page']) ) ? $_GET['page'] : 0;
$userid = (int)$CURUSER["id"];
// ------ Get forum details
($res = sql_query("SELECT
				f.name AS forum_name, 
				f.min_class_read, 
				(SELECT COUNT(id) FROM topics WHERE forum_id = f.id) AS t_count "."
			FROM 
				forums AS f "."
			WHERE 
			f.id = ".sqlesc($forumid))) || sqlerr(__FILE__, __LINE__);
($arr = $res->fetch_assoc()) || stderr('Error', 'No forum with that ID!');
if ($CURUSER['class'] < $arr["min_class_read"]) {
    stderr('Error', 'Access Denied!');
}
// pagination
$count = (int)$arr['t_count'];
$perpage = (empty($CURUSER['topicsperpage']) ? 4 : (int)$CURUSER['topicsperpage']);
[$pager_menu, $LIMIT] = pager_new($count, $perpage, $page, 'forums.php?action=viewforum&amp;forumid='.$forumid . ($perpage == 20 ? '' : '&amp;perpage='.$perpage));
($topics_res = sql_query("SELECT 
							t.id, 
							t.user_id, 
							t.views, 
							t.locked, 
							t.sticky,
							".($Multi_forum['configs']['use_poll_mod'] ? 't.poll_id' : '').", 
							t.num_ratings, 
							t.rating_sum, 
							t.topic_name, 
							t.anonymous,  
							u1.id AS uid1, 
							u1.enabled, 
							u1.class, 
							u1.donor, 
							u1.chatpost,  
							u1.warned, 
							u1.leechwarn, 
							u1.pirate, 
							u1.king, 
							u1.username, 
							r.last_post_read, 
							p.id AS p_id,
							p2.icon, 
							p.user_id AS p_userid, 
							p.anonymous as p_anon, 
							p.added AS p_added, 
							(SELECT COUNT(id) FROM posts WHERE topic_id=t.id) AS p_count, 
							u2.id AS uid2, 
							u2.enabled, 
							u2.class, 
							u2.donor, 
							u2.chatpost, 
							u2.warned, 
							u2.leechwarn, 
							u2.pirate,
							u2.king,
							u2.username AS u2_username "."
						FROM 
							topics AS t "."
							LEFT JOIN users AS u1 ON u1.id=t.user_id "."
							LEFT JOIN read_posts AS r ON r.user_id = ".sqlesc($userid)." 
						AND 
							r.topic_id = t.id "."
							LEFT JOIN posts AS p ON p.id = (SELECT MAX(id) FROM posts WHERE topic_id = t.id) "."
							LEFT JOIN posts AS p2 ON p2.id = (SELECT MIN(id) FROM posts WHERE topic_id = t.id) "."
							LEFT JOIN users AS u2 ON u2.id = p.user_id "."
						WHERE t.forum_id=".sqlesc($forumid)." 
						ORDER BY 
							t.sticky, 
							t.last_post 
						DESC " . $LIMIT)) || sqlerr(__FILE__, __LINE__);
// subforums
($r_subforums = sql_query("SELECT 
							id 
						FROM 
							forums 
						WHERE 
							place=".sqlesc($forumid))) || sqlerr(__FILE__, __LINE__);
$subforums = $r_subforums->num_rows;
$HTMLOUT .= "<div class='container'>
		<div class='card'>
			<div class='card-section'>
				<nav aria-label='You are here:' role='navigation'>
					<ul class='breadcrumbs'>
  						<li><a href='index.php'>".$TRINITY20["site_name"]."</a></li>
  						<li><a href='forums.php'>Forums</a></li>
  						<li>
							<span class='show-for-sr'>Current: </span>".htmlsafechars($arr["forum_name"])."
  						</li>
					</ul>
				</nav>
			</div>
		</div>";
if ($TRINITY20['forums_online'] == 0) {
    $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
}
if ($subforums > 0) {
    $HTMLOUT .= "<div class='card'>
				<div class='card-divider'>
					<strong>".htmlsafechars($arr["forum_name"])."</strong>
					</label>
				</div>
				<div class='card-section'>".show_forums($forumid, true)."</div>
			</div>";
}
$HTMLOUT .= '<div class="clearfix">';
if ($Multi_forum['configs']['use_forum_stats_mod']) {
    $HTMLOUT .= '<div class="float-left">'.$pager_menu.'</div>';
}
$newtopicarr = get_forum_access_levels($forumid);
$maypost = ($CURUSER['class'] >= $newtopicarr["write"] && $CURUSER['class'] >= $newtopicarr["create"]);
$HTMLOUT .= '<div class="float-right primary button-group small">';
$HTMLOUT .= "<a class='button' href='forums.php?action=viewunread'>View Unread</a>";
if ($maypost) {
	$HTMLOUT .= '<a class="button" href="forums.php?action=newtopic&forumid='.$forumid.'"><i class="fa fa-check-square"></i> Start new topic</a>';
} else {
	$HTMLOUT .= '<a class="button"><i class="fa fa-check-square"></i> No Permissions</a>';
}
$HTMLOUT .='</div></div>';
if ($topics_res->num_rows > 0) {
    $HTMLOUT .= "
		  <div class='card'>
			<div class='card-divider'>".htmlsafechars($arr["forum_name"])."</div>
			<div class='card-section'>
			".forum_stats()."
				<div class='divTable'>
    				<div class='divTableHeading'>
        				<div class='divTableCell'>Thread / Author</div>
						<div class='divTableCell hide-for-small-only'>Rating</div>
						<div class='divTableCell hide-for-small-only'>Replies / Views</div>
						<div class='divTableCell hide-for-small-only'>Last&nbsp;post</div>
					</div>";
    while ($topic_arr = $topics_res->fetch_assoc()) {
        $user_stuff = $topic_arr;
        $user_stuff['id'] = (int)$topic_arr['uid1'];
        $user_stuff1 = $topic_arr;
        $user_stuff1['id'] = (int)$topic_arr['uid2'];
        $user_stuff1['username'] = htmlsafechars($topic_arr['u2_username']);
        $topicid = (int)$topic_arr['id'];
        $topic_userid = (int)$topic_arr['user_id'];
        $sticky = ($topic_arr['sticky'] == "yes");
        $pollim = $topic_arr['poll_id'] > "0";
        ($Multi_forum['configs']['use_poll_mod'] ? $topicpoll = is_valid_id($topic_arr["poll_id"]) : null);
        $tpages = floor($topic_arr['p_count'] / $Multi_forum['configs']['postsperpage']);
        if ($tpages * $Multi_forum['configs']['postsperpage'] != $topic_arr['p_count']) {
            ++$tpages;
        }
        if ($tpages > 1) {
            $topicpages = "&nbsp;(<img src='".$TRINITY20['pic_base_url']."multipage.gif' alt='Multiple pages' title='Multiple pages' />";
            $split = $tpages > 10;
            $flag = false;
            for ($i = 1; $i <= $tpages; ++$i) {
                if ($split && ($i > 4 && $i < ($tpages - 3))) {
                    if (!$flag) {
                        $topicpages .= '&nbsp;...';
                        $flag = true;
                    }
                    continue;
                }
                $topicpages .= "&nbsp;<a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=$i'>$i</a>";
            }
            $topicpages .= ")";
        } else {
            $topicpages = '';
        }
        if ($topic_arr["p_anon"] == "yes") {
            if ($CURUSER['class'] < UC_STAFF && $topic_arr["p_userid"] != $CURUSER["id"]) {
                $lpusername = "<i>Anonymous</i>";
            } else {
                $lpusername = "<i>Anonymous</i>(<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$topic_arr['p_userid']."'>".format_username($user_stuff1,
                        true)."</a>)";
            }
        } else {
            $lpusername = (is_valid_id($topic_arr['p_userid']) && !empty($topic_arr['u2_username']) ? "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$topic_arr['p_userid']."'>".format_username($user_stuff1,
                    true)."</a>" : "unknown[$topic_userid]");
        }
        if ($topic_arr["anonymous"] == "yes") {
            if ($CURUSER['class'] < UC_STAFF && $topic_arr["user_id"] != $CURUSER["id"]) {
                $lpauthor = "<i>Anonymous</i>";
            } else {
                $lpauthor = "<i>Anonymous</i>[<a href='{$TRINITY20['baseurl']}/userdetails.php?id=$topic_userid'>".format_username($user_stuff,
                        true)."</a>]";
            }
        } else {
            $lpauthor = (is_valid_id($topic_arr['user_id']) && !empty($topic_arr['username']) ? "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=$topic_userid'>".format_username($user_stuff,
                    true)."</a>" : "unknown[$topic_userid]");
        }
        $new = ($topic_arr["p_added"] > (TIME_NOW - $TRINITY20['readpost_expiry'])) ? ((int)$topic_arr['p_id'] > $topic_arr['last_post_read']) : 0;
		$topicpic = ($topic_arr['locked'] == "yes" ? ($new ? "<span class='thread_status newlockfolder' title='Topic locked, new posts.'>&nbsp;</span>" : "<span class='thread_status newlockfolder' title='Topic Locked.'>&nbsp;</span>") : ($new ? "<span class='thread_status newfolder' title='New posts.'>&nbsp;</span>" : "<span class='thread_status dot_folder' title='No new posts.'>&nbsp;</span>"));
		$post_icon = ($sticky ? "<img src=\"".$TRINITY20['pic_base_url']."sticky.gif\" alt=\"Sticky topic\" title=\"Sticky topic\"/>" : ($topic_arr["icon"] > 0 ? "<img src=\"".$TRINITY20['pic_base_url']."post_icons/icon".htmlsafechars($topic_arr["icon"]).".gif\" alt=\"post icon\" title=\"post icon\" />" : "&nbsp;"));
        $HTMLOUT .= "<div class='divTableBody'>
				  	<div class='divTableRow'>
						<div class='divTableCell'>
						  	<span class='icon-wrapper'>	".$topicpic."</span>
							<a class='topictitle' href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=".$topicid."'>
								".htmlsafechars($topic_arr['topic_name'])."
							</a>
							<span class='float-right'>".$post_icon."</span>
							<span class='float-right'>".($pollim ? "<i class='fas fa-poll'></i>" : '')."</span><br>
							Started by ".$lpauthor."
							{$topicpages}</div>
						<div class='divTableCell hide-for-small-only'>".(getRate($topicid, "topic"))."</div>
						<div class='divTableCell hide-for-small-only'>Replies : ".max(0, $topic_arr['p_count'] - 1)."<br>
						Views : ".number_format($topic_arr['views'])."
						</div>
						<div class='divTableCell hide-for-small-only'>".get_date($topic_arr["p_added"], 'DATE', 1, 0)."<br />by&nbsp;".$lpusername."</div>
					</div>
				</div>";
    }
    $HTMLOUT .= "</div>
			</div>
		</div>";
} else {
    $HTMLOUT .= "<div class='card'>
			<div class='card-divider'><strong>".htmlsafechars($arr["forum_name"])." </strong></div>
			<div class='card-section'>
				<div class='callout alert-callout-border alert'>
				<strong><p>No Topics Found</p></strong>
				</div>
			</div>
		</div>";
}
$HTMLOUT .= '<div class="clearfix">
	<div class="float-left">'.$pager_menu.'</div>
	<div class="float-right primary button-group small">
	<a class="button" href="forums.php?action=viewunread">View Unread</a>';
	if ($maypost) {
		$HTMLOUT .= '<a class="button" href="forums.php?action=newtopic&forumid='.$forumid.'"><i class="fa fa-check-square"></i> Start new topic</a>';
	} else {
		$HTMLOUT .= '<a class="button"><i class="fa fa-check-square"></i> No Permissions</a>';
	}
	$HTMLOUT .= '</div>
	</div>
	<div class="callout">'.insert_quick_jump_menu($forumid).'</div>
</div>';
echo stdhead("View Forums", true, $stdhead).$HTMLOUT.stdfoot($stdfoot);
exit();
?>
