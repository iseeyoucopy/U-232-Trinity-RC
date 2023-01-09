<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */
// Announcement Code...
$ann_subject = trim((string)$CURUSER['curr_ann_subject']);
$ann_body = trim((string)$CURUSER['curr_ann_body']);
if (!empty($ann_subject) && !empty($ann_body)) {
    $HTMLOUT .= "
	<div class='card'>
		<div class='card-divider'>{$lang['index_announce']}</div>
		<div class='card-section'>
			<table class='table table-bordered'>
				<tr>
					<td bgcolor='transparent'><b><font color='red'>{$lang['index_ann_title']}&nbsp;: ".htmlspecialchars($ann_subject)."</font></b></td>
				</tr>
				<tr>
					<td style='padding: 10px; background:lightgrey'>".format_comment($ann_body)."<br /><hr /><br />   {$lang['index_ann_click']}<a href='{$TRINITY20['baseurl']}/clear_announcement.php'>
					<i><b>{$lang['index_ann_here']}</b></i></a>{$lang['index_ann_clear']}</td>
				</tr>
			</table>
		</div></div>";
}
//==End
// End Class
// End File
