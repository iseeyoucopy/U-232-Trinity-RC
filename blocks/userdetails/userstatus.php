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
if (($CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) && isset($user_status['last_status'])) {
    $HTMLOUT .= "<tr valign='top'><td class='rowhead'>{$lang['userdetails_status']}</td><td align='left'>".format_urls($user_status['last_status'])."<br/><small>added ".get_date($user_status['last_update'],
            '', 0, 1)."</small></td></tr>\n";
}
//==end
// End Class
// End File
