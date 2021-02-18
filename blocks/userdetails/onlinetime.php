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
//== Online time
if ($user['onlinetime'] > 0) {
    $onlinetime = time_return($user['onlinetime']);
    $HTMLOUT .= "<tr><td class='rowhead' width='1%'>{$lang['userdetails_time_online']}</td><td align='left' width='99%'>{$onlinetime}</td></tr>";
} else {
    $onlinetime = $lang['userdetails_notime_online'];
    $HTMLOUT .= "<tr><td class='rowhead' width='1%'>{$lang['userdetails_time_online']}</td><td align='left' width='99%'>{$onlinetime}</td></tr>";
}
// end
// End Class
// End File
