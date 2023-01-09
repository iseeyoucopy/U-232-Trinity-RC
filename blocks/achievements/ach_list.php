<?php
$HTMLOUT .= "
<div class='card table-responsive-md'>
<div class='card-divider'><h1>{$lang['achlst_std_head']}</h1></div>
<table class='table table-bordered'>
		<tr>
		<td>{$lang['achlst_achievname']}</td>
		<td>{$lang['achlst_description']}</td>
		<td>{$lang['achlst_earned']}</td>
		</tr>";
while ($arr = $res->fetch_assoc()) {
    $notes = htmlspecialchars($arr["notes"]);
    $clienticon = '';
    if ($arr["clienticon"] != "") {
        $clienticon = "<img src='".$TRINITY20['pic_base_url']."achievements/".htmlspecialchars($arr["clienticon"])."' title='".htmlspecialchars($arr['achievname'])."' alt='".htmlspecialchars($arr['achievname'])."' />";
    }
    $HTMLOUT .= "<tr>
			<td>$clienticon</td>
			<td>$notes</td>
			<td>".htmlspecialchars($arr['count'])."<br />times</td>
			</tr>\n";
}
$HTMLOUT .= "</table></div>";
?>
