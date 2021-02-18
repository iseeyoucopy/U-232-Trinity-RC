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
    echo $HTMLOUT;
    exit();
}
($res = sql_query('SELECT
					 COUNT(p.id) AS post_count
				FROM posts AS p
				LEFT JOIN topics AS t ON t.id = p.topic_id 
				LEFT JOIN forums AS f ON f.id = t.forum_id 
				WHERE 
					p.added > '.TIME_NOW.' - 86400 
				AND 
					f.min_class_read <= '.sqlesc($CURUSER['class']))) || sqlerr(__FILE__, __LINE__);
$arr = $res->fetch_assoc();
$res->free();
$mysqli->next_result();
$count = (int)$arr['post_count'];
if (empty($count)) {
    stderr('Sorry', 'No posts in the last 24 hours.');
}
if ($TRINITY20['forums_online'] == 0) {
    $HTMLOUT .= stdmsg('Warning', 'Forums are currently in maintainance mode');
}
$HTMLOUT .= "<div class='row'><div class='col-md-12'>";
$perpage = 20;
$pager = pager($perpage, $count, $TRINITY20['baseurl'].'/forums.php?action='.$action.'&amp;');
$HTMLOUT .= "<div class='container'>
	<div class='card'>
		<div class='card-section'>
			<nav aria-label='You are here:' role='navigation'>
				<ul class='breadcrumbs'>
					<li><a href='index.php'>".$TRINITY20["site_name"]."</a></li>
					<li><a href='forums.php'>Forums</a></li>
					<li>
						<span class='show-for-sr'>Current: </span> Today Posts (Last 24 Hours)
					</li>
				</ul>
			</nav>
		</div>
	</div>
</div>
<div class='card'>
    <div class='card-divider'><strong>Today Posts (Last 24 Hours)</strong></div>
		<div class='card-section'>
			<div class='divTable'>
				<div class='divTableHeading'>
						<div class='divTableCell'>Topic Title</div>
						<div class='divTableCell'>Views</div>
						<div class='divTableCell'>Author</div>
						<div class='divTableCell'>Posted At</div>
				</div>";
($res = sql_query('SELECT p.id AS pid, p.topic_id, p.user_id AS userpost, p.added, t.id AS tid, t.topic_name, t.forum_id, t.last_post, t.views, f.name, f.min_class_read, f.topic_count, u.username '.
    'FROM posts AS p '.
    'LEFT JOIN topics AS t ON t.id = p.topic_id '.
    'LEFT JOIN forums AS f ON f.id = t.forum_id '.
    'LEFT JOIN users AS u ON u.id = p.user_id '.
    'LEFT JOIN users AS topicposter ON topicposter.id = t.user_id '.
    'WHERE p.added > '.TIME_NOW.' - 86400 AND f.min_class_read <= '.sqlesc($CURUSER['class']).' '.
    'ORDER BY p.added DESC '.$pager["limit"])) || sqlerr(__FILE__, __LINE__);
while ($getdaily = $res->fetch_assoc()) {
    $postid = (int)$getdaily['pid'];
    $posterid = (int)$getdaily['userpost'];
    $HTMLOUT .= "<div class='divTableBody'>
                        <div class='divTableRow'>
                            <div class='divTableCell'>
                                <a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;topicid=".(int)$getdaily['tid']."&amp;page=".$postid."#".$postid."'>".htmlsafechars($getdaily['topic_name'])."</a>
                                <b>In</b>&nbsp;<a href='{$TRINITY20['baseurl']}/forums.php?action=viewforum&amp;forumid=".(int)$getdaily['forum_id']."'>".htmlsafechars($getdaily['name'])."</a>
                            </div>
                            <div class='divTableCell'>".number_format($getdaily['views'])."</div>
                            <div class='divTableCell'>
                                ".(empty($getdaily['username']) ? "<b>unknown[".$posterid."]</b>" : "<a href='{$TRINITY20['baseurl']}/userdetails.php?id=".$posterid."'>".htmlsafechars($getdaily['username'])."</a>")."
                            </div>
                            <div class='divTableCell'>".get_date($getdaily['added'], 'LONG', 1, 0)."</div>
                        </div>
                    </div>";
}
$HTMLOUT .= "</div>";
$res->free();
$mysqli->next_result();
$HTMLOUT .= $pager['pagerbottom'];
echo stdhead('Today Posts (Last 24 Hours)').$HTMLOUT.stdfoot($stdfoot);
