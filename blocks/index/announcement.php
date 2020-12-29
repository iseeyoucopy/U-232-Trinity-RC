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
$ann_subject = trim($CURUSER['curr_ann_subject']);
$ann_body = trim($CURUSER['curr_ann_body']);
if ((!empty($ann_subject)) AND (!empty($ann_body)))
   {
   $HTMLOUT .= "
	<div class='card'>
		<div class='card-header'>
			<label for='checkbox_4' class='text-left'>{$lang['index_announce']}</label>
		</div>
		<div class='card-body'>
			<table class='table table-bordered'>
				<tr>
					<td bgcolor='transparent'><b><font color='red'>{$lang['index_ann_title']}&nbsp;: " . htmlsafechars($ann_subject) . "</font></b></td>
				</tr>
				<tr>
					<td style='padding: 10px; background:lightgrey'>" . format_comment($ann_body) . "<br /><hr /><br />   {$lang['index_ann_click']}<a href='{$TRINITY20['baseurl']}/clear_announcement.php'>
					<i><b>{$lang['index_ann_here']}</b></i></a>{$lang['index_ann_clear']}</td>
				</tr>
			</table>
		</div></div>";
   }
//==End
// End Class
// End File
