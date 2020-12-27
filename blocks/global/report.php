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
if ($INSTALLER09['report_alert'] && $CURUSER['class'] >= UC_STAFF) {
    if (($delt_with = $cache->get('new_report_')) === false) {
        $res_reports = sql_query("SELECT COUNT(id) FROM reports WHERE delt_with = '0'");
        list($delt_with) = mysqli_fetch_row($res_reports);
        $cache->set('new_report_', $delt_with, $INSTALLER09['expires']['alerts']);
    }
    if ($delt_with > 0) {
        $htmlout.= "<a class='button small warning' href='staffpanel.php?tool=reports&amp;action=reports'>" . ($delt_with > 1 ? $lang['gl_reportss'] . $lang['gl_reports_news'] : $lang['gl_reports'] . $lang['gl_reports_new']) . "</a>";
    }
}
//==End
// End Class
// End File
