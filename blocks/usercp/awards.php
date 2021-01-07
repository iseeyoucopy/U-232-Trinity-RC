<?php
$res = sql_query("SELECT users.id, users.username, usersachiev.achpoints, usersachiev.spentpoints FROM users LEFT JOIN usersachiev ON users.id = usersachiev.id WHERE users.id = " . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$arr = mysqli_fetch_assoc($res);
if (!$arr) stderr($lang['achievement_history_err'], $lang['achievement_history_err1']);

$res = sql_query("SELECT COUNT(*) FROM achievements WHERE userid =" . sqlesc($id)) or sqlerr(__FILE__, __LINE__);
$row = mysqli_fetch_row($res);
$count = $row[0];
$perpage = 15;
$pager = pager($perpage, $count, "?id=$id&amp;");

if ($count > $perpage) $HTMLOUT.= $pager['pagertop'];

$res_achiev = sql_query("SELECT a1.*, (SELECT COUNT(a2.id) FROM achievements AS a2 WHERE a2.achievement = a1.achievname) as count FROM achievementist AS a1 ORDER BY a1.id ") or sqlerr(__FILE__, __LINE__);

$HTMLOUT . '<div class="tabs-panel" id="panel4">';
require_once (BLOCK_DIR . 'achievements/ach_history.php');
$HTMLOUT . '</div>';
?>