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
        <meta charset="' . charset() . '" />
        <title>ERROR</title>
        </head><body>
        <h1 style="text-align:center;">Error</h1>
        <p style="text-align:center;">How did you get here? silly rabbit Trix are for kids!.</p>
        </body></html>';
    echo $HTMLOUT;
    exit();
}
// -------- Action: View unread posts
if ((isset($_POST[$action]) ? htmlsafechars($_POST[$action]) : '') == 'clear') {
    $topic_ids = (isset($_POST['topic_id']) ? $_POST['topic_id'] : []);
    if (empty($topic_ids)) {
        header('Location: ' . $TRINITY20['baseurl'] . '/forums.php?action=' . $action);
        exit();
    }
    foreach ($topic_ids as $topic_id) {
        if (!is_valid_id($topic_id)) {
            stderr('Error...', 'Invalid ID!');
        }
    }
    $HTMLOUT .= catch_up($topic_ids);
    header('Location: ' . $TRINITY20['baseurl'] . '/forums.php?action=' . $action);
    exit();
} else {
    $added = (TIME_NOW - $TRINITY20['readpost_expiry']);
    $res = sql_query('SELECT t.last_post, r.last_post_read, f.min_class_read ' . 'FROM topics AS t ' . 'LEFT JOIN posts AS p ON t.last_post=p.id ' . 'LEFT JOIN read_posts AS r ON r.user_id=' . sqlesc((int) $CURUSER['id']) . ' AND r.topic_id=t.id ' . 'LEFT JOIN forums AS f ON f.id=t.forum_id ' . 'WHERE p.added > ' . $added) or sqlerr(__FILE__, __LINE__);
    $count = 0;
    while ($arr = $res->fetch_assoc($res)) {
        if ($arr['last_post_read'] >= $arr['last_post'] || $CURUSER['class'] < $arr['min_class_read']) {
            continue;
        }
        $count++;
    }
    ((mysqli_free_result($res) || (is_object($res) && (get_class($res) == "mysqli_result"))) ? true : false);
    if ($count > 0) {
        $perpage = 25;
        $pager = pager($perpage, $count, $TRINITY20['baseurl'] . '/forums.php?action=' . $action . '&amp;');

        if ($TRINITY20['forums_online'] == 0) {
            $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
        }
        //   $HTMLOUT .= begin_main_frame();
        //$HTMLOUT .="<h1 align='center'>Topics with unread posts</h1>";
        $HTMLOUT .= "<div class='navigation'>
				<a href='index.php'>" . $TRINITY20["site_name"] . "</a> 
				&gt;
				<a href='forums.php'>Forums</a>
				<br><img src='templates/1/pic/carbon/nav_bit.png' alt=''>
				<span class='active'>Catch Up</span>
				</div> <br />";
        $HTMLOUT .= $pager['pagertop'];

        $HTMLOUT .= "	<script type='text/javascript'>
			             /*<![CDATA[*/
				var checkflag = 'false';
				function check(a)
				{
					if (checkflag == 'false')
					{
						for(i=0; i < a.length; i++)
							a[i].checked = true;
						checkflag = 'true';
						value = 'Uncheck';
					}
					else
					{
						for(i=0; i < a.length; i++)
							a[i].checked = false;
						checkflag = 'false';
						value = 'Check';
					}
					return value + ' All';
				};
			/*]]>*/
			</script>";

        $HTMLOUT .= "<form method='post' action='{$TRINITY20['baseurl']}/forums.php?action=viewunread'>
			<input type='hidden' name='viewunread' value='clear' />";
        $HTMLOUT .= "<br /><table class='table table-bordered'>
		  	<tr>
		<td class='thead' colspan='8'>
			<div>
				<strong>Topics with unread posts </strong>
			</div>
		</td>
	</tr>
			<tr align='left'>
				<td class='tcat' colspan='2'>Topic</td>
				<td class='tcat' width='1%'>Clear</td>
			</tr>";
        $res = sql_query('SELECT t.id, t.forum_id, t.topic_name, t.last_post, r.last_post_read, f.name, f.min_class_read ' . 'FROM topics AS t ' . 'LEFT JOIN posts AS p ON t.last_post=p.id ' . 'LEFT JOIN read_posts AS r ON r.user_id=' . sqlesc((int) $CURUSER['id']) . ' AND r.topic_id=t.id ' . 'LEFT JOIN forums AS f ON f.id=t.forum_id ' . 'WHERE p.added > ' . $added . ' ' . ' ORDER BY t.forum_id ' . $pager['limit']) or sqlerr(__FILE__, __LINE__);
        while ($arr = $res->fetch_assoc($res)) {
            if ($arr['last_post_read'] >= $arr['last_post'] || $CURUSER['class'] < $arr['min_class_read']) {
                continue;
            }

            $HTMLOUT .= "<tr>
					<td class=row align='center' width='3%'>
						<span class='thread_status newfolder' title='New posts.'>&nbsp;</span>
					</td>
					<td class=row align='left'>
						<a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=" . (int) $arr['id'] . "&amp;page=last#last'>" . htmlsafechars($arr['topic_name']) . "</a><br />in&nbsp;<font class='small'><a href='{$TRINITY20['baseurl']}/forums.php?action=viewforum&amp;forumid=" . (int) $arr['forum_id'] . "'>" . htmlsafechars($arr['name']) . "</a></font>
					 </td>
					<td class=row align='center'>
						<input type='checkbox' name='topic_id[]' value='" . htmlsafechars($arr['id']) . "' />
					</td>
				</tr>";
        }
        ((mysqli_free_result($res) || (is_object($res) && (get_class($res) == "mysqli_result"))) ? true : false);

        $HTMLOUT .= "<tr>
				<td class='tfoot' align='right' colspan='3'>
					<input class='btn btn-primary dropdown-toggle' type='button' value='Check All' onclick=\"this.value = check(form);\" />&nbsp;<input class='btn btn-primary dropdown-toggle' type='submit' value='Clear selected' />
				</td>
			</tr>";

        $HTMLOUT .= "</table>";
        $HTMLOUT .= "</form>";
        $HTMLOUT .= $pager['pagerbottom'];
        $HTMLOUT .= "<div align='center'><a href='{$TRINITY20['baseurl']}/forums.php?catchup'>Mark all posts as read</a></div>";

        echo stdhead("Catch Up", true, $stdhead) . $HTMLOUT . stdfoot($stdfoot);
        die();
    } else {
        stderr("Sorry...", "There are no unread posts.<br /><br />Click <a href='{$TRINITY20['baseurl']}/forums.php?action=getdaily'>here</a> to get today's posts (last 24h).") . "<br>";
    }
}
