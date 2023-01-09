<?php
$HTMLOUT .= "<div class='card'>
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
($res = sql_query("SELECT * FROM achievements WHERE userid=".sqlesc($id)." ORDER BY date DESC {$pager['limit']}")) || sqlerr(__FILE__, __LINE__);
while ($arr = $res->fetch_assoc()) {
    $HTMLOUT .= "
						<tr>
							<td><img src='pic/achievements/".htmlspecialchars($arr['icon'])."' alt='".htmlspecialchars($arr['achievement'])."' title='".htmlspecialchars($arr['achievement'])."' /></td>
							<td>".htmlspecialchars($arr['description'])."</td>
							<td>".get_date($arr['date'], '')."</td>
						</tr>
				";
}
$HTMLOUT .= "
				</table>
				</div>
			</div>";
?>
