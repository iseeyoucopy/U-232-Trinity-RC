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
    echo $HTMLOUT;
    exit();
}
$error = false;
$found = '';
$keywords = (isset($_GET['keywords']) ? trim($_GET['keywords']) : '');
if (!empty($keywords)) {
    ($res = sql_query("SELECT COUNT(id) AS c FROM posts WHERE body LIKE ".sqlesc("%".sqlwildcardesc($keywords)."%"))) || sqlerr(__FILE__, __LINE__);
    $arr = $res->fetch_assoc();
    $count = (int)$arr['c'];
    $keywords = htmlsafechars($keywords);
    if ($count == 0) {
        $error = true;
    } else {
        $perpage = 10;
        $pager = pager($perpage, $count, $TRINITY20['baseurl'].'/forums.php?action='.$action.'&keywords='.$keywords.'&');
        ($res = sql_query("SELECT p.id, p.topic_id, p.user_id, p.added, t.forum_id, t.topic_name, f.name, f.min_class_read, u.username ".
            "FROM posts AS p ".
            "LEFT JOIN topics AS t ON t.id=p.topic_id ".
            "LEFT JOIN forums AS f ON f.id=t.forum_id ".
            "LEFT JOIN users AS u ON u.id=p.user_id ".
            "WHERE p.body LIKE ".sqlesc("%".$keywords."%")." ".$pager['limit']."")) || sqlerr(__FILE__, __LINE__);
        $num = $res->num_rows;
        $HTMLOUT .= $pager['pagertop']."<br>";
        $HTMLOUT .= "<div class='row'><div class='col-md-12'>";
        $HTMLOUT .= "<table class='table table-bordered'>
			       <tr align='left'>
            	<td><h4>Post</h4></td>
                <td><h4>Topic</h4></td>
                <td><h4>Forum</h4></td>
                <td><h4>Posted by</h4></td>
			          </tr>";
        for ($i = 0; $i < $num; ++$i) {
            $post = $res->fetch_assoc();
            if ($post['min_class_read'] > $CURUSER['class']) {
                --$count;
                continue;
            }
            $HTMLOUT .= "<tr>".
                "<td align='center'>".(int)$post['id']."</td>".
                "<td align=left width='100%'><a href='{$TRINITY20['baseurl']}/forums.php?action=viewtopic&amp;highlight=$keywords&amp;topicid=".(int)$post['topic_id']."&amp;page=p".(int)$post['id']."#".(int)$post['id']."'><b>".htmlsafechars($post['topic_name'])."</b></a></td>".
                "<td align=left style='white-space: nowrap;'>".(empty($post['name']) ? 'unknown['.(int)$post['forum_id'].']' : "<a href='{$TRINITY20['baseurl']}/forums.php?action=viewforum&amp;forumid=".(int)$post['forum_id']."'><b>".htmlsafechars($post['name'])."</b></a>")."</td>".
                "<td align=left style='white-space: nowrap;'>".(empty($post['username']) ? 'unknown['.(int)$post['user_id'].']' : "<b><a href='{$TRINITY20['baseurl']}/userdetails.php?id=".(int)$post['user_id']."'>".htmlsafechars($post['username'])."</a></b>")."<br>at ".get_date($post['added'],
                    'DATE', 1, 0)."</td>".
                "</tr>";
        }
        $HTMLOUT .= end_table();
        $HTMLOUT .= "</div></div>";
        $HTMLOUT .= "<br>".$pager['pagerbottom'];
        $found = "[<b><font color='red'> Found $count post".($count != 1 ? "s" : "")." </font></b> ]";
    }
}
$HTMLOUT .= "<div class='row'><div class='col-md-12'>
	  <div align='center'><h1>Search on Forums</h1> ".($error ? "[<b><font color='red'> Nothing Found</font></b> ]" : $found)."</div>
	<form method='get' action='{$TRINITY20['baseurl']}/forums.php' id='search_form' style='margin: 0pt; padding: 0pt; font-family: Tahoma,Arial,Helvetica,sans-serif; font-size: 11px;'>
	<input type='hidden' name='action' value='search'>
	<table class='table'>
	<tbody>
	<tr>
	<td valign='top'><b>By keyword:</b></td>
	</tr>
	<tr>
	<td valign='top'>			
  <input name='keywords' type='text' value='".$keywords."' size='65'><br>
  <font class='small'><b>Note:</b> Searches <u>only</u> in posts.</font></td>
	<td valign='top'>
	<input type='submit' value='search'></td>
	</tr>
	</tbody>
	</table>
	</form>
	</div></div>";
echo stdhead("Forum Search").$HTMLOUT.stdfoot($stdfoot);
exit();
