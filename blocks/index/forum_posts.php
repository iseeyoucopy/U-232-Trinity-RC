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
//== Latest forum posts [set limit from config] - Multilayer
$HTMLOUT .= "";
$page = 1;
$num = 0;
if (($topics = $cache->get($keys['last_postsb'] . $CURUSER['class'])) === false) {
	($topicres = sql_query("SELECT t.id, t.user_id, t.topic_name, t.locked, t.forum_id, t.last_post, t.sticky, t.views, t.anonymous AS tan, f.min_class_read, f.name " . ", (SELECT COUNT(id) FROM posts WHERE topic_id=t.id) AS p_count " . ", p.user_id AS puser_id, p.added, p.anonymous AS pan " . ", u.id AS uid, u.username " . ", u2.username AS u2_username " . "FROM topics AS t " . "LEFT JOIN forums AS f ON f.id = t.forum_id " . "LEFT JOIN posts AS p ON p.id=(SELECT MAX(id) FROM posts WHERE topic_id = t.id) " . "LEFT JOIN users AS u ON u.id=p.user_id " . "LEFT JOIN users AS u2 ON u2.id=t.user_id " . "WHERE f.min_class_read <= " . $CURUSER['class'] . " " . "ORDER BY t.last_post DESC LIMIT {$TRINITY20['latest_posts_limit']}")) || sqlerr(__FILE__, __LINE__);
	while ($topic = $topicres->fetch_assoc()) {
		$topics = (array) $topics;
		$topics[] = $topic;
	}
	$cache->set($keys['last_postsb'] . $CURUSER['class'], $topics, $TRINITY20['expires']['latestposts']);
}
if ($topics && count($topics) > 0) {
	$HTMLOUT .= "<div class='card'>
	<div class='card-divider'>
		{$lang['latestposts_title']}
	</div>
	<div class='card-section'>
				<table class='hover'>
					<thead>
						<tr>
							<th scope='col'>{$lang['latestposts_topic_title']}</th>
							<th scope='col'>{$lang['latestposts_replies']}</th>
							<th scope='col'>{$lang['latestposts_views']}</th>
							<th scope='col'>{$lang['latestposts_last_post']}</th>
						</tr>
					</thead>";
	if ($topics) {
		foreach ($topics as $topicarr) {
			$topicid = (int)$topicarr['id'];
			$topic_userid = (int)$topicarr['user_id'];
			$perpage = empty($CURUSER['postsperpage']) ? 10 : (int)$CURUSER['postsperpage'];
			if ($perpage === 0) $perpage = 24;
			$posts = 0 + $topicarr['p_count'];
			$replies = max(0, $posts - 1);
			$first = ($page * $perpage) - $perpage + 1;
			$last = $first + $perpage - 1;
			if ($last > $num) $last = $num;
			$pages = ceil($posts / $perpage);
			$menu = '';
			for ($i = 1; $i <= $pages; $i++) {
				if ($i == 1 && $i != $pages) {
					$menu .= "[ ";
				}
				if ($pages > 1) {
					$menu .= "<a href='/forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=$i'>$i</a>";
				}
				if ($i < $pages) {
					$menu .= "|\n";
				}
				if ($i == $pages && $i > 1) {
					$menu .= "]";
				}
			}
			$added = get_date($topicarr['added'], '', 0, 1);
			if ($topicarr['pan'] == 'yes') {
				if ($CURUSER['class'] < UC_STAFF && $topicarr['user_id'] != $CURUSER['id']) $username = (empty($topicarr['username']) ? "<i>{$lang['index_fposts_unknow']}</i>" : "<i>{$lang['index_fposts_anonymous']}</i>");
				else $username = (empty($topicarr['username']) ? "<i>{$lang['index_fposts_unknow']}[$topic_userid]</i>" : "<i>{$lang['index_fposts_anonymous']}</i>&nbsp;&nbsp;<a href='" . $TRINITY20['baseurl'] . "/userdetails.php?id=" . (int)$topicarr['puser_id'] . "'><b>[" . htmlsafechars($topicarr['username']) . "]</b></a>");
			} else {
				$username = (empty($topicarr['username']) ? "<i>{$lang['index_fposts_unknow']}[$topic_userid]</i>" : "<a href='" . $TRINITY20['baseurl'] . "/userdetails.php?id=" . (int)$topicarr['puser_id'] . "'><b>" . htmlsafechars($topicarr['username']) . "</b></a>");
			}
			if ($topicarr['tan'] == 'yes') {
				if ($CURUSER['class'] < UC_STAFF && $topicarr['user_id'] != $CURUSER['id']) $author = (empty($topicarr['u2_username']) ? $topic_userid == '0' ? "<i>System</i>" : "<i>{$lang['index_fposts_unknow']}</i>" : ("<i>{$lang['index_fposts_anonymous']}</i>"));
				else $author = (empty($topicarr['u2_username']) ? $topic_userid == '0' ? "<i>System</i>" : "<i>{$lang['index_fposts_unknow']}[$topic_userid]</i>" : ("<i>{$lang['index_fposts_anonymous']}</i>&nbsp;&nbsp;<a href='" . $TRINITY20['baseurl'] . "/userdetails.php?id=" . $topic_userid . "'><b>[" . htmlsafechars($topicarr['u2_username']) . "]</b></a>"));
			} else {
				$author = (empty($topicarr['u2_username']) ? $topic_userid == '0' ? "<i>System</i>" : "<i>{$lang['index_fposts_unknow']}[$topic_userid]</i>" : ("<a href='" . $TRINITY20['baseurl'] . "/userdetails.php?id=" . $topic_userid . "'><b>" . htmlsafechars($topicarr['u2_username']) . "</b></a>"));
			}
			$staffimg = ($topicarr['min_class_read'] >= UC_STAFF ? "<img src='" . $TRINITY20['pic_base_url'] . "staff.png' border='0' alt='Staff forum' title='Staff Forum' />" : '');
			$stickyimg = ($topicarr['sticky'] == 'yes' ? "<img src='" . $TRINITY20['pic_base_url'] . "sticky.gif' border='0' alt='{$lang['index_fposts_sticky']}' title='{$lang['index_fposts_stickyt']}' />&nbsp;&nbsp;" : '');
			$lockedimg = ($topicarr['locked'] == 'yes' ? "<img src='" . $TRINITY20['pic_base_url'] . "forumicons/locked.gif' alt='{$lang['index_fposts_locked']}' title='{$lang['index_fposts_lockedt']}' />&nbsp;" : '');
			$topic_name = $lockedimg . $stickyimg . "<a href='/forums.php?action=viewtopic&amp;topicid=$topicid&amp;page=last#" . (int)$topicarr['last_post'] . "'><b>" . htmlsafechars($topicarr['topic_name']) . "</b></a>&nbsp;&nbsp;$staffimg&nbsp;&nbsp;$menu<br />{$lang['index_fposts_in']}<a href='forums.php?action=viewforum&amp;forumid=" . (int)$topicarr['forum_id'] . "'>" . htmlsafechars($topicarr['name']) . "</a>&nbsp;by&nbsp;$author&nbsp;&nbsp;($added)";
			$HTMLOUT .= "
					<tr>
						<td>{$topic_name}</td>
						<td><span class='badge'>{$replies}</span></td>
						<td><span class='badge'>" . number_format($topicarr['views']) . "</span></td>
						<td>{$username}</td>
					</tr>";
		}
		$HTMLOUT .= "</table></div>";
	} elseif (empty($topics)) {
		$HTMLOUT .= "<tr><td>{$lang['latestposts_no_posts']}</td></tr></table>";
	}
	$HTMLOUT .= "</div>";
}
//$cache->delete('last_posts_b_' . $CURUSER['class']);
//==End
// End Class
// End File
