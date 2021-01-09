<?php
$res = sql_query("SELECT COUNT(*) FROM achievements WHERE userid =" . sqlesc($CURUSER['id'])) or sqlerr(__FILE__, __LINE__);
$row = mysqli_fetch_row($res);
$count = $row[0];
$perpage = 5;
if (!$count) {
    $HTMLOUT.= "{$lang['achievement_history_err2']}<a class='altlink' href='userdetails.php?id=" . (int) $CURUSER['id'] . "'>" . htmlsafechars($CURUSER['username']) . "</a>{$lang['achievement_history_err3']}";
}
$pager = pager($perpage, $count, "usercp.php?action=awards&");
$HTMLOUT.= "<div class='table-responsive-md'>
    <table class='table table-bordered '>
        <thead>
            <tr>
                <th>{$lang['achievement_history_award']}</th>
                <th>{$lang['achievement_history_descr']}</th>
                <th>{$lang['achievement_history_date']}</th>
            </tr>
        </thead>
        ";
$res = sql_query("SELECT * FROM achievements WHERE userid=" . sqlesc($CURUSER['id']) . " ORDER BY date DESC {$pager['limit']}") or sqlerr(__FILE__, __LINE__);
while ($arr = mysqli_fetch_assoc($res)) {
$HTMLOUT.= "
            <tr>
                <td><img src='pic/achievements/" . htmlsafechars($arr['icon']) . "' alt='" . htmlsafechars($arr['achievement']) . "' title='" . htmlsafechars($arr['achievement']) . "' /></td>
                <td>" . htmlsafechars($arr['description']) . "</td>
                <td>" . get_date($arr['date'], '') . "</td>
            </tr>
    ";
}
$HTMLOUT.= "
    </table>";
    if ($count > $perpage) {
        $HTMLOUT.= $pager['pagerbottom'];
    }
    $HTMLOUT.= "</div>";
?>