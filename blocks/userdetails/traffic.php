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
//==Uploaded/downloaded
if ($CURUSER['id'] == $id || $CURUSER['class'] >= UC_STAFF) {
    $days = round((TIME_NOW - $user['added']) / 86400);
    if ($TRINITY20['ratio_free']) {
        $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_h_days']}</td><td align='left'>{$lang['userdetails_rfree_effect']}</td></tr>
    <tr><td class='rowhead'>{$lang['userdetails_uploaded']}</td><td align='left'>".mksize($user_stats['uploaded'])." {$lang['userdetails_daily']}".($days > 1 ? mksize($user_stats['uploaded'] / $days) : mksize($user_stats['uploaded']))."</td></tr>\n";
    } else {
        $HTMLOUT .= "<tr><td class='rowhead'>{$lang['userdetails_downloaded']}</td><td align='left'>".mksize($user_stats['downloaded'])." {$lang['userdetails_daily']}".($days > 1 ? mksize($user_stats['downloaded'] / $days) : mksize($user_stats['downloaded']))."</td></tr>
    <tr><td class='rowhead'>{$lang['userdetails_uploaded']}</td><td align='left'>".mksize($user_stats['uploaded'])." {$lang['userdetails_daily']}".($days > 1 ? mksize($user_stats['uploaded'] / $days) : mksize($user_stats['uploaded']))."</td></tr>\n";
    }
}
//==end
// End Class
// End File
