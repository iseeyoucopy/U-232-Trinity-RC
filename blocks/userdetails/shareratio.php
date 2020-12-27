<?php
/*
 |--------------------------------------------------------------------------|
 |   https://github.com/iseeyoucopy/                                        |
 |--------------------------------------------------------------------------|
 |   Licence Info: WTFPL                                                    |
 |--------------------------------------------------------------------------|
 |   Copyright (C) 2020 U-232 Codename Trinity                              |
 |--------------------------------------------------------------------------|
 |   A bittorrent tracker source based on TBDev.net/tbsource/bytemonsoon.   |
 |--------------------------------------------------------------------------|
 |   Project Leaders: iseeyoucopy, stonebreath, GodFather                   |
 |--------------------------------------------------------------------------|
  _   _   _   _   _     _   _   _   _   _   _     _   _   _   _
 / \ / \ / \ / \ / \   / \ / \ / \ / \ / \ / \   / \ / \ / \ / \
( U | - | 2 | 3 | 2 )-( S | o | u | r | c | e )-( C | o | d | e )
 \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/ \_/ \_/   \_/ \_/ \_/ \_/
*/
//=== share ratio
if ($CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) {
    if ($user_stats['downloaded'] > 0) {
        $HTMLOUT.= '<tr>
			<td class="rowhead" style="vertical-align: middle">' . $lang['userdetails_share_ratio'] . '</td>
			<td align="left" valign="middle" style="padding-top: 1px; padding-bottom: 0px">
	<table border="0"cellspacing="0" cellpadding="0">
		<tr>
         <td class="embedded">' . member_ratio($user_stats['uploaded'], $INSTALLER09['ratio_free'] ? "0" : $user_stats['downloaded']) . '</td>
         <td class="embedded">&nbsp;&nbsp;' . get_user_ratio_image($user_stats['uploaded'] / ($INSTALLER09['ratio_free'] ? "1" : $user_stats['downloaded'])) . '</td>
		</tr>
	</table>
			</td>
		</tr>';
    }
}
//==end
// End Class
// End File
