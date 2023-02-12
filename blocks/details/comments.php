<?php
$commentbar = " 
<div class='row'>
<div class='col-md-2'></div>
<div class='col-md-4'>
<div class='content'><br><p align='center' ><a  class='index' href='comment.php?action=add&amp;tid=$id'>{$lang['details_add_comment']}</a>
    <br><a class='index' href='{$TRINITY20['baseurl']}/takethankyou.php?id=".$id."'>
    <img src='{$TRINITY20['pic_base_url']}smilies/thankyou.gif' alt='Thanks' title='".$lang['details_add_TY']."' border='0'></a></p></div>
     </div>
   <div class='row'>
<div class='col-md-2'></div>
<div class='col-md-8'>\n";
$count = (int)$torrents['comments'];
if ($count === 0) {
    $HTMLOUT .= "
<div class='container'>
<div class='row'>
<div class='col-md-6 col-md-offset-5'>
<h2>{$lang['details_no_comment']}</h2>\n";
} else {
    $perpage = 15;
    $pager = pager($perpage, $count, "details.php?id=$id&amp;", [
        'lastpagedefault' => 1,
    ]);
    ($subres = sql_query("SELECT comments.id, comments.text, comments.user_likes, comments.user, comments.torrent, comments.added, comments.anonymous, comments.editedby, comments.editedat, comments.edit_name, users.warned, users.enabled, users.chatpost, users.leechwarn, users.pirate, users.king, users.perms, users.avatar, users.av_w, users.av_h, users.offavatar, users.warned, users.reputation, users.opt1, users.opt2, users.mood, users.username, users.title, users.class, users.donor FROM comments LEFT JOIN users ON comments.user = users.id WHERE torrent = ".sqlesc($id)." ORDER BY comments.id ".$pager['limit'])) || sqlerr(__FILE__,
        __LINE__);
    $allrows = [];
    while ($subrow = $subres->fetch_assoc()) {
        $allrows[] = $subrow;
    }
    $HTMLOUT .= "
     </div>
     </div>
     </div>
<div class='row'>
<div class='col-md-3'></div>
<div class='col-md-8'>";
    $HTMLOUT .= "<br><div class='col-sm-offset-3'><div style='display:inline-block;width:0%;'></div><button type='button' class='btn btn-default' data-toggle='collapse' data-target='#dropdown'>{$lang['details_add_openclose']}</button></div><br><div id='dropdown' class='collapse in'>";
    $HTMLOUT .= $commentbar;
    $HTMLOUT .= $pager['pagertop'];
    $HTMLOUT .= commenttable($allrows);
    $HTMLOUT .= $pager['pagerbottom'];
    $HTMLOUT .= "</div></div></div></div><br>";
}
$HTMLOUT .= "</div></div><div class='col-md-1'></div><div class='col-md-10'>";
?>
