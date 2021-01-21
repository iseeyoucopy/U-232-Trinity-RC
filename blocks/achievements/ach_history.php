<?php
$HTMLOUT.= "<div class='card'>
			<div class='table-responsive-md'>
				<table class='table table-bordered '>
					<thead>
						<tr>
							<th>{$lang['achievement_history_award']}</th>
							<th>{$lang['achievement_history_descr']}</th>
							<th>{$lang['achievement_history_date']}</th>
						</tr>
					</thead>
					";
$res = sql_query("SELECT * FROM achievements WHERE userid=" . sqlesc($id) . " ORDER BY date DESC {$pager['limit']}") or sqlerr(__FILE__, __LINE__);
while ($arr = $res->fetch_assoc()) {
    $HTMLOUT.= "
						<tr>
							<td><img src='pic/achievements/" . htmlsafechars($arr['icon']) . "' alt='" . htmlsafechars($arr['achievement']) . "' title='" . htmlsafechars($arr['achievement']) . "' /></td>
							<td>" . htmlsafechars($arr['description']) . "</td>
							<td>" . get_date($arr['date'], '') . "</td>
						</tr>
				";
}
$HTMLOUT.= "
				</table>
				</div>
			</div>";
?>