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
if ($TRINITY20['report_alert'] && $CURUSER['class'] >= UC_STAFF) {
    if (($delt_with = $cache->get('new_report_')) === false) {
        $res_reports = sql_query("SELECT COUNT(id) FROM reports WHERE delt_with = '0'");
        list($delt_with) = mysqli_fetch_row($res_reports);
        $cache->set('new_report_', $delt_with, $TRINITY20['expires']['alerts']);
    }
    if ($delt_with > 0) {
        $htmlout.= "
        <a class='hollow small button notification' href='staffpanel.php?tool=reports&amp;action=reports'>{$lang['gl_reports_news']}<span class='badge_corner'>" . $delt_with[0] . "</span></a>";
    }
}
//==End
// End Class
// End File
