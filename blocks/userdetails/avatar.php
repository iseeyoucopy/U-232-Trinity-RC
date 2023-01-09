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
if ($user["avatar"]) {
    $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_avatar']}</td><td align='left'><img class='img-polaroid' src='".htmlspecialchars($user["avatar"])."'></td></tr>\n";
} else {
    $HTMLOUT .= "
	<tr><td class='rowhead'>{$lang['userdetails_avatar']}</td><td align='left'><img class='img-polaroid' src='{$TRINITY20['pic_base_url']}default_avatar.gif'></td></tr>\n";
}
//==end
// End Class
// End File
