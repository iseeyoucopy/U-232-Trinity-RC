<?php
$HTMLOUT.= "<div class='alert alert-primary' role='alert'>
 {$lang['achievement_history_afu']}
			<a class='altlink' href='{$TRINITY20['baseurl']}/userdetails.php?id=" . (int)$arr['id'] . "'>" . htmlsafechars($arr['username']) . "</a><br />
			{$lang['achievement_history_c']}" . htmlsafechars($row['0']) . "{$lang['achievement_history_a']}" . ($row[0] == 1 ? "" : "s") . ".";
if ($id == $CURUSER['id']) {
    $HTMLOUT.= "
			<a class='altlink' href='achievementbonus.php'>" . htmlsafechars($achpoints) . "{$lang['achievement_history_pa']}" . htmlsafechars($spentpoints) . "{$lang['achievement_history_ps']}</a>";
}
$HTMLOUT.= "</div>";
?>