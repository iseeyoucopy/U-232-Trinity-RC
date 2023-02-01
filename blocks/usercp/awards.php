<?php
if ($TRINITY20['achieve_sys_on'] == false) {
    $HTMLOUT .= "<div class='callout alert-callout-border alert'>
    <strong><p>{$lang['achievement_history_err']}</p></strong>
    <p>{$lang['achievement_history_off']}</p></div>";
} else {
    ($res = sql_query("SELECT users.id, users.username, usersachiev.achpoints, usersachiev.spentpoints FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE users.id = ".sqlesc($CURUSER['id']))) || sqlerr(__FILE__,
        __LINE__);
    $arr = $res->fetch_assoc();
    if (!$arr) {
        stderr($lang['achievement_history_err'], $lang['achievement_history_err1']);
    }
    $achpoints = (int)$arr['achpoints'];
    $spentpoints = (int)$arr['spentpoints'];
    ($res = sql_query("SELECT COUNT(*) FROM achievements WHERE userid =".sqlesc($CURUSER['id']))) || sqlerr(__FILE__, __LINE__);
    $row = $res->fetch_row();
    $count = $row[0];
    $perpage = 5;
    if (!$count) {
        $HTMLOUT .= "{$lang['achievement_history_err2']}<a href='userdetails.php?id=".(int)$CURUSER['id']."'>".htmlsafechars($CURUSER['username'])."</a>{$lang['achievement_history_err3']}";
    }
    $pager = pager($perpage, $count, "usercp.php?action=awards&");
    $HTMLOUT .= "<div class='card'>
    <div class='card-divider'>Achievements</div>
<div class='card-section'>Exchange your points into a random gift.
<a href='achievementbonus.php'>Get a bonus</a>
<p>{$lang['achievement_history_c']}".htmlsafechars($row['0'])."{$lang['achievement_history_a']}".($row[0] == 1 ? "" : "s")."</p>
    <p>".htmlsafechars($achpoints)."{$lang['achievement_history_pa']}".htmlsafechars($spentpoints)."{$lang['achievement_history_ps']}</p>";
    ($res = sql_query("SELECT * FROM achievements WHERE userid=".sqlesc($CURUSER['id'])." ORDER BY date DESC {$pager['limit']}")) || sqlerr(__FILE__,
        __LINE__);
    while ($arr = $res->fetch_assoc()) {
        $HTMLOUT .= "
<div class='media-object callout'>
  <div class='media-object-section'>
    <div class='thumbnail'>
    <img src='pic/achievements/".htmlsafechars($arr['icon'])."' alt='".htmlsafechars($arr['achievement'])."' title='".htmlsafechars($arr['achievement'])."'>
    </div>
  </div>
  <div class='media-object-section'>
    <p>".htmlsafechars($arr['description'])."</p>
    <p>".get_date($arr['date'], '')."</p>
  </div>
</div>";
    }
    $HTMLOUT .= "</div>";
    if ($count > $perpage) {
        $HTMLOUT .= $pager['pagerbottom'];
    }
    $HTMLOUT .= "</div>";
}
?>