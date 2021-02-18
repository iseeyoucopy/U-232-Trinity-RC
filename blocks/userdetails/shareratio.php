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
//=== share ratio
if (($CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) && $user_stats['downloaded'] > 0) {
    $HTMLOUT .= '<tr>
			<td class="rowhead" style="vertical-align: middle">'.$lang['userdetails_share_ratio'].'</td>
			<td align="left" valign="middle" style="padding-top: 1px; padding-bottom: 0px">
	<table border="0"cellspacing="0" cellpadding="0">
		<tr>
         <td class="embedded">'.member_ratio($user_stats['uploaded'], $TRINITY20['ratio_free'] ? "0" : $user_stats['downloaded']).'</td>
         <td class="embedded">&nbsp;&nbsp;'.get_user_ratio_image($user_stats['uploaded'] / ($TRINITY20['ratio_free'] ? "1" : $user_stats['downloaded'])).'</td>
		</tr>
	</table>
			</td>
		</tr>';
}
//==end
// End Class
// End File
