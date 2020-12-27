<?php
$HTMLOUT.= "
<div class='card table-responsive-md'>
<div class='card-header'><h1>{$lang['achlst_std_head']}</h1></div>
<table class='table table-bordered'>
		<tr>
		<td>{$lang['achlst_achievname']}</td>
		<td>{$lang['achlst_description']}</td>
		<td>{$lang['achlst_earned']}</td>
		</tr>";
    while ($arr = mysqli_fetch_assoc($res)) {
        $notes = htmlsafechars($arr["notes"]);
        $clienticon = '';
        if ($arr["clienticon"] != "") {
            $clienticon = "<img src='" . $INSTALLER09['pic_base_url'] . "achievements/" . htmlsafechars($arr["clienticon"]) . "' title='" . htmlsafechars($arr['achievname']) . "' alt='" . htmlsafechars($arr['achievname']) . "' />";
        }
        $HTMLOUT.= "<tr>
			<td>$clienticon</td>
			<td>$notes</td>
			<td>" . htmlsafechars($arr['count']) . "<br />times</td>
			</tr>\n";
    }
$HTMLOUT.= "</table></div>";
?>