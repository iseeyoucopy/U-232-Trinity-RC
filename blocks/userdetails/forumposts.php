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
//==posts
if (($forumposts = $cache->get('forum_posts_' . $id)) === false) {
//    $res = sql_query("SELECT COUNT(id) FROM posts WHERE user_id=" . sqlesc($user['id'])) or sqlerr(__FILE__, __LINE__);  // Old
    $res = sql_query("SELECT COUNT(id) FROM posts WHERE user_id=" . sqlesc($user['id'])) or sqlerr(__FILE__, __LINE__);
    list($forumposts) = mysqli_fetch_row($res);
    $cache->set('forum_posts_' . $id, $forumposts, $INSTALLER09['expires']['forum_posts']);
}
if ($CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) {
    if ($forumposts && (($user["class"] >= UC_POWER_USER && $user["id"] == $CURUSER["id"]) || $CURUSER['class'] >= UC_STAFF)) 
		$HTMLOUT.= "<a class='button' href='userhistory.php?action=viewposts&amp;id=$id'>{$lang['userdetails_posts']}<span class='badge success'>" . htmlsafechars($forumposts) . "</span></a>";
    else 
		$HTMLOUT.= "<a class='button'>{$lang['userdetails_posts']}<span class='badge success'>" . htmlsafechars($forumposts) . "</span></a>";
}
//==end
// End Class
// End File
