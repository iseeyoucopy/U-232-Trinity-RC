<?php
/*
 |--------------------------------------------------------------------------|
 |   https://github.com/iseeyoucopy/                                        |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2020 U-232 Codename Trinity                              |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: iseeyoucopy, stonebreath, GodFather                   |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
*/
//==comments
if (($torrentcomments = $cache->get('torrent_comments_' . $id)) === false) {
    $res = sql_query("SELECT COUNT(id) FROM comments WHERE user=" . sqlesc($user['id'])) or sqlerr(__FILE__, __LINE__);
    list($torrentcomments) = mysqli_fetch_row($res);
    $cache->set('torrent_comments_' . $id, $torrentcomments, $INSTALLER09['expires']['torrent_comments']);
}
if ($CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) {
    if ($torrentcomments && (($user["class"] >= UC_POWER_USER && $user["id"] == $CURUSER["id"]) || $CURUSER['class'] >= UC_STAFF)) 
		$HTMLOUT.= "<a class='button' href='userhistory.php?action=viewcomments&amp;id=$id'>{$lang['userdetails_comments']}<span class='badge success'>" . (int)$torrentcomments . "</span></a>";
    else $HTMLOUT.= "<a class='button'>{$lang['userdetails_comments']}<span class='badge success'>" . (int)$torrentcomments . "</span></a>";
}
//==end
// End Class
// End File
